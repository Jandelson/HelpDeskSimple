<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Chamado extends MY_Controller
{
    /**
     * Visualização do ticket.
     */
    public function visualizar($id_chamado = 0)
    {
        $this->id_chamado = $id_chamado;
        if ($this->id_chamado == 0) {
            $this->id_chamado = $this->uri->segment(2);
        }
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
                case 1:
                    $data_log['dados'][$k]->status = 'Em Aberto';
                    break;
                case 2:
                    $data_log['dados'][$k]->status = 'Executando';
                    break;
                case 3:
                    $data_log['dados'][$k]->status = 'Concluído ';
                    break;
                case 4:
                    $data_log['dados'][$k]->status = 'Testando';
                    break;
                case 5:
                    $data_log['dados'][$k]->status = 'Aguardando Cliente';
                    break;
                case 6:
                    $data_log['dados'][$k]->status = 'Em Analise';
                    break;
                case 7:
                    $data_log['dados'][$k]->status = 'Aguardando aprovação orçamento';
                    break;
            }
        }

        $this->chamados->getCorStatus($data['dados']);

        $this->load->view('visualizar', $data);
        $this->load->view('log', $data_log);
    }

    /**
     * Edição do ticket.
     */
    public function editar()
    {
        $this->load->view('edit');
    }

    /**
     * Interface tela de Inserção do ticket.
     */
    public function inserir()
    {
        $data['title'] = $this->session->userdata('title');
        $data['tipo_chamados'] = $this->tipoChamados->getTipoChamados();
        $this->load->view('inserir', $data);
    }

    public function finalizar()
    {
        $data['title'] = $this->session->userdata('title');
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
        $_POST['status'] = 1;
        $_POST['data_hora_criacao'] = date('Y-m-d H:i:s');
        $_POST['data_hora_alteracao'] = date('Y-m-d H:i:s');
        $_POST['id_usuario_responsavel'] = $this->config->config['id_usuario_responsavel'];
        $_POST['id_usuario_helpdesk'] = $this->session->userdata('idusu');
        $_POST['solicitante'] = $this->session->userdata('nomusu');
        $_POST['id_usuario_cliente'] = $this->session->userdata('codusu');
        $_POST['id_cliente'] = $data['agente'][0]->id_cliente;
        $id_chamado = $this->chamados->insertChamados($this->input->post(), 'chamado');
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