<?php
/**
 * Criando controller para modificações personailzadas e padronizadas
 */
class MY_Controller extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->LoadModels();
    }

    public function indexController()
    {
        $this->idusu = $this->retornaIDU();
        $data['title'] = $this->title;
        $data['agente'] = $this->clientes->getCliente($this->session->userdata('cgc_cpf'));
        /**
         * Load helper de paginação
         */
        $this->load->helper('paginate_helper');
        $page = ($this->uri->segment(3) <> $this->session->userdata('codusu')) ? $this->uri->segment(3) : 0;
        /**
         * Retorna where permissão se pode ou não ver todos os chamados
         */
        $where = $this->getWherePermissao();
        $data['dados'] = $this->chamados->getChamados(0, $this->idusu, numRegister4PagePaginate(), $page, $where);
        $data['paginacao'] = createPaginate('home/index', $this->chamados->contador());
        $data['status'] = $this->chamados->getChamadosStatus(0, $this->idusu, $where);
        $this->analisaCorStatus($data['dados']);

        if (empty($data['agente'])) {
           $this->load->view('/errors/helpdesk/erro_login', $data);
        } else {
            $this->load->view('home', $data);
        }
    }

    /**
     * Retorna IDU id unica dao usuário.
     */
    public function retornaIDU()
    {
        $identificacao = md5($this->session->userdata('cgc_cpf') . $this->session->userdata('email'));
        if ($this->session->userdata('userid') <> 0) {
            $identificacao = md5($this->session->userdata('cgc_cpf') . $this->session->userdata('userid'));
        }
        return $identificacao;
    }

    /**
     * analisa Cores dos status
     *
     * @param array $dados
     *
     * @return void
     */
    public function analisaCorStatus($dados)
    {
        foreach ($dados as $k => $v) {
            /*
             * Analisa Status Cor
             */
            switch ($v->status) {
                case 0:
                    $dados[$k]->corstatus = 'danger';
                    break;
                case 1:
                    $dados[$k]->corstatus = 'warning';
                    break;
                case 2:
                    $dados[$k]->corstatus = 'success ';
                    break;
                case 3:
                    $dados[$k]->corstatus = 'primary';
                    break;
                case 4:
                    $dados[$k]->corstatus = 'info';
                    break;
                case 5:
                    $dados[$k]->corstatus = 'primary';
                    break;
                default:
                    $dados[$k]->corstatus = 'primary';
                    break;
            }
        }
    }

    public function LoadModels()
    {
        $this->load->model('ChamadoModel', 'chamados');
        $this->load->model('ClienteModel', 'clientes');
        $this->load->model('TipoChamadoModel', 'tipoChamados');
    }
    /**
     * Retorna Where para busca dos chamados da empresa ou do usuário
     * De arcordo com a opção supervisor_helpdesk
     *
     * @return void
     */
    private function getWherePermissao()
    {
        if (strtoupper($this->session->userdata('nomusu')) == 'ADMIN' or $this->session->userdata('supervisor_helpdesk') == 1) {
            return 'p.id_chamado<>0';
        } else {
            return 'p.id_chamado<>0 and p.id_usuario_helpdesk="' . $this->idusu . '"';
        }
    }
}
