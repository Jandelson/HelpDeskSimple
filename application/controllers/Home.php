<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Home extends MY_Controller
{
    public $id_chamado;
    public $title;
    public $file = '';

    /**
     * Construtor
     */
    public function __construct()
    {
        parent::__construct();
        $this->title = $this->config->config['descricao'] ?? 'HelpDesk';
        $this->LoadModels();
    }
    /**
     * My Controller controla os dados da interface index
     *
     * @return void
     */
    public function index()
    {
        $this->indexController();
    }

    /**
     * Fluxo 1 tratamento de login para acesso externo do sistema analisar.
     */
    public function login()
    {
        unset(
            $_SESSION['cgc_cpf'],
            $_SESSION['codusu'],
            $_SESSION['nomusu'],
            $_SESSION['userid'],
            $_SESSION['email'],
            $_SESSION['supervisor_helpdesk']
        );

        $this->cgc_cpf = $this->uri->segment(2);
        $this->codusu = $this->uri->segment(3);
        $this->nomusu = $this->uri->segment(4);
        $this->codemp = $this->uri->segment(5);
        $this->supervisor_helpdesk = $this->uri->segment(7);
        /**
         * remove criptografia da vinda na url
         */
        $this->email = strtr((base64_decode($this->uri->segment(6))), '+/=', '._-');

        $this->session->set_userdata('supervisor_helpdesk', $this->supervisor_helpdesk);
        $this->session->set_userdata('cgc_cpf', $this->cgc_cpf);
        $this->session->set_userdata('codusu', $this->codusu);
        $this->session->set_userdata('nomusu', $this->nomusu);
        $this->session->set_userdata('codemp', $this->codemp);
        $this->session->set_userdata('userid', $this->cgc_cpf . $this->codusu);
        $this->session->set_userdata('email', $this->email);

        $this->indexController();
    }

    /**
     * Processo de upload
     *
     * @param [type] $arquivo
     * @return void
     */
    private function uploadAnexo($arquivo)
    {
        /*
         * Arquivo Anexo
         */
        if ($arquivo['size'] > 0) {
            $anexo = $arquivo;
            $ext = substr($arquivo['name'], -3);
            if (!empty($anexo['name'])) {
                $file_name = $anexo['name'];
            }

            $config = [
                'upload_path' => '/anexos',
                'AllowedTypes' => 'pdf',
                'remove_spaces' => true,
                'encrypt_name' => true,
                'file_name' => str_replace('-', '', $file_name),
                'codusu'    => 'HELPDESK'
            ];
            $this->load->library('upload');
            $this->upload->initialize($config);

            if (!$this->upload->do_upload()) {
                echo "<script>alert('Erro ao realizar upload do anexo!\\nTipos permitidos: jpeg|jpg|png|pdf|zip|rar|doc|xls|xlsx|txt|xml');</script>";
                $this->upload->file_name = $file_name = $arquivo['name'] = '';
                return ['nome_arquivo' => $this->upload->file_name];
            } else {
                return [
                    'nome_arquivo' => $this->upload->file_name,
                    'descricao_arquivo' => $this->upload->client_name
                ];
            }
        } else {
            return $arquivo['name'] = '';
        }
    }

    /**
     * Rotina de envio de e-mail
     * Dados de configuração de e-mail estão na  config/email.php.
     *
     * @param int    $id
     * @param string $descricao
     */
    public function enviarEmail($id, $descricao, $mail)
    {

        $this->email->from('', 'Remetente'); // Remetente
        $this->email->to($mail, $this->session->userdata('nomusu')); // Destinatário

        $detalhes = '<br><br>Detalhes: ' . $descricao;
        $descricao = '';

        // Define o assunto do email
        $this->email->subject('Ticket Chamado: ' . $id);
        $descricao .= 'Olá: ' . $this->session->userdata('nomusu') . ' <br> seu chamado nº ' . $id;
        $descricao .= $detalhes;

        /*
         * Configuração de template teste
         */
        if (isset($dados['template'])) {
            $this->email->message($this->load->view('email-template', $dados, true));
        } else {
            $this->email->message($descricao);
        }
        /*
         * Configuração de anexos
         */
        if (isset($dados['anexo'])) {
            $this->email->attach('.png');
        }

        if ($this->email->send()) {
            $this->session->set_flashdata('success', 'Email enviado com sucesso!');
        } else {
            $this->session->set_flashdata('error', $this->email->print_debugger());
        }
    }

    /**
     * Visualização do ticket.
     */
    public function visualizar($id_chamado = 0)
    {
        $data['title'] = $this->title;
        $this->id_chamado = $id_chamado;
        if ($this->id_chamado == 0) {
            $this->id_chamado = $this->uri->segment(2);
        }
        $data['arquivo'] = $this->file;
        $data['dados'] = $this->chamados->getChamados($this->id_chamado);
        $data['anexos'] = $this->chamados->getChamadosAnexo($this->id_chamado);
        $data['status'] = $this->chamados->getChamadosStatus($this->id_chamado);
        $data_log['dados'] = $this->chamados->getChamadosLog($this->id_chamado);

        foreach ($data_log['dados'] as $k => $v) {
            /*
             * Analisar usuário na visualização
             */
            if (empty($v->id)) {
                $v->id = $this->session->userdata('nomusu');
            }
            /*
             * Analisa Status
             */
            switch ($v->status) {
                case 0:
                    $data_log['dados'][$k]->status = 'Em Aberto';
                    break;
                case 1:
                    $data_log['dados'][$k]->status = 'Executando';
                    break;
                case 2:
                    $data_log['dados'][$k]->status = 'Concluído ';
                    break;
                case 3:
                    $data_log['dados'][$k]->status = 'Testando';
                    break;
                case 4:
                    $data_log['dados'][$k]->status = 'Aguardando Cliente';
                    break;
                case 5:
                    $data_log['dados'][$k]->status = 'Em Analise';
                    break;
                case 6:
                    $data_log['dados'][$k]->status = 'Aguardando aprovação orçamento';
                    break;
            }
        }
        $this->analisaCorStatus($data['dados']);

        $this->load->view('visualizar', $data);
        $this->load->view('log', $data_log);
    }

    /**
     * Edição do ticket.
     */
    public function editar()
    {
        $data['title'] = $this->title;
        $this->load->view('edit', $data);
    }

    /**
     * Interface tela de Inserção do ticket.
     */
    public function inserir()
    {
        $data['title'] = $this->title;

        $data['tipo_chamados'] = $this->tipoChamados  ->getTipoChamados();
        $this->load->view('inserir', $data);
    }

    public function finalizar()
    {
        $data['title'] = $this->title;
        $this->load->view('finalizar', $data);
    }

    /**
     * Salvar dados da pendência.
     */
    public function salvar()
    {
        /*
         * Recupera dados do Cliente
         */
        $data['agente'] = $this->clientes->getCliente($this->session->userdata('cgc_cpf'));
        /*
        * post fixo
        */
        $_POST['data'] = date('Y-m-d');
        $_POST['data_hora_criacao'] = date('Y-m-d H:i:s');
        $_POST['data_hora_alteracao'] = date('Y-m-d H:i:s');
        $_POST['id_usuario_responsavel'] = $this->config->config['id_usuario_responsavel'];
        $_POST['id_usuario_helpdesk'] = $this->retornaIDU();
        $_POST['solicitante'] = $this->session->userdata('nomusu');
        $_POST['id_usuario_cliente'] = $this->session->userdata('codusu');
        $_POST['id_cliente'] = $data['agente'][0]->id_cliente;
        //$_POST['email_helpdesk'] = $this->session->userdata('email');
        /*
         * Arquivo Anexo
        $dados_arquivo = $this->uploadAnexo($_FILES['anexo']);
        $_POST['nome_arquivo'] = $dados_arquivo['nome_arquivo'];
        $_POST['descricao_arquivo'] = $dados_arquivo['descricao_arquivo'];
         */
        
        $id_chamado = $this->chamados->insertChamados($this->input->post());
        //Email aguardando validação
        if (ENVIRONMENT !== 'production') {
            //$this->enviarEmail($id_chamado, $_POST['descricao'], $this->session->userdata('email'));
        } else {
            // more
        }

        redirect('/visualizar/' . $id_chamado, 'refresh');
    }

    /**
     * Interege com a pendência, realizando responsta ou comentário da mesma.
     */
    public function resposta($redirect = true, $upload = false)
    {
        /*
         * post fixo
         */
        $_POST['data_hora'] = date('Y-m-d H:i:s');
        $_POST['hora_final'] = date('H:i');
        $_POST['status'] = 2;
        $_POST['id_usuario_cliente'] = $this->session->userdata('codusu');
        $_POST['visivel_cliente'] = 0;

        if ($upload) {
            $dados_arquivo = $this->uploadAnexo($_FILES['anexo']);
            $_POST['nome_arquivo'] = $dados_arquivo['nome_arquivo'];
            $_POST['descricao_arquivo'] = $dados_arquivo['descricao_arquivo'];
        }

        $id_chamado = $this->chamados->insertchamados($this->input->post(), 'chamado_log');
        if ($redirect) {
            redirect('visualizar/' . $id_chamado, 'refresh');
        }
    }

    /**
     * encerra pendência do lado do cliente.
     */
    public function concluir()
    {
        $id_chamado = $this->uri->segment(2);

        $dados = [
            'os.status' => 2,
            'os.data_hora_alteracao' => date('Y-m-d H:i:s'),
            'os.data_conclusao' => date('Y-m-d'),
        ];

        $this->db->set($dados);
        $this->db->where('os.id_chamado', $id_chamado);
        $this->db->update('chamado os');

        /*
         * Grava resposta da finalização
         */
        $_POST['id_chamado'] = $id_chamado;
        $this->resposta(true, false);
    }
}
