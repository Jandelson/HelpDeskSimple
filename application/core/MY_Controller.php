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
        $this->load->library('form_validation');
        $this->load->helper('form');
    }

    public function indexController()
    {
        $this->idusu = $this->session->userdata('idusu');
        $data['title'] = $this->title;
        $data['cliente'] = $this->clientes->getCliente($this->session->userdata('cgc_cpf'));
        $data['error'] = '';
        if (empty($data['cliente'])) {
            $data['formErrors'] = 'Usuário não encontrado!';
            return $this->load->view('/login/login', $data);
        }
        /**
         * Load helper de paginação
         */
        $this->load->helper('paginate_helper');
        $page = ($this->uri->segment(3) <> $this->session->userdata('codusu')) ? $this->uri->segment(3) : 0;
        /**
         * Retorna where permissão se pode ou não ver todos os chamados
         */
        $data['dados'] = $this->chamados->getChamados(0, $this->idusu, numRegister4PagePaginate(), $page, $this->retornaPermissao());
        $data['paginacao'] = createPaginate('home/index', $this->chamados->contador());
        $data['status'] = $this->chamados->getChamadosStatus(
            0,
            $this->idusu,
            $this->retornaPermissao()
        );

        $this->chamados->getCorStatus($data['dados']);
        $this->load->view('home', $data);
    }

    private function LoadModels()
    {
        $this->load->model('ChamadoModel', 'chamados');
        $this->load->model('ClienteModel', 'clientes');
        $this->load->model('TipoChamadoModel', 'tipoChamados');
    }
    /**
     * Retorna Where para busca dos chamados     *
     */
    private function retornaPermissao(): string
    {
        if ($this->session->userdata('nomusu') == 'admin@admin.com.br') {
            return 'id_chamado<>0';
        } else {
            return 'id_chamado<>0 and id_usuario_helpdesk="' . $this->idusu . '"';
        }
    }
}
