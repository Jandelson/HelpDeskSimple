<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Login extends MY_Controller
{
    public function __construct()
    {
        parent::__construct();
    }

    public function index()
    {
        $data['title'] = $this->title = 'HelpDesk';
        $data['error'] = '';

        $this->form_validation->set_rules('cnpj', 'Cnpj', 'trim|required|min_length[11]');
        $this->form_validation->set_rules('email', 'Email', 'trim|required|valid_email');
        
        $data['formErrors'] = null;

        if ($this->form_validation->run() == false) {
            $data['formErrors'] = validation_errors();
            return $this->load->view('login/login', $data);
        }

        $this->session->set_userdata('cgc_cpf', $_POST['cnpj']);
        $this->session->set_userdata('nomusu', $_POST['email']);
        $this->session->set_userdata('userid', $_POST['cnpj'] . $_POST['email']);
        $this->session->set_userdata('email', $_POST['email']);
        $this->session->set_userdata('idusu', $this->retornaIDU());
        redirect('home');
    }

    /**
     * Retorna IDU id unica dao usuário.
     */
    private function retornaIDU()
    {
        $identificacao = md5($this->session->userdata('cgc_cpf') . $this->session->userdata('email'));
        if ($this->session->userdata('userid') <> 0) {
            $identificacao = md5($this->session->userdata('cgc_cpf') . $this->session->userdata('userid'));
        }
        return $identificacao;
    }

    public function logout()
    {
        unset(
            $_SESSION['cgc_cpf'],
            $_SESSION['codusu'],
            $_SESSION['nomusu'],
            $_SESSION['userid']
        );

        $this->session->sess_destroy();

        redirect('/login', 'refresh');
    }

    public function error()
    {
        $data['title'] = 'Error 404';
        $this->load->view('login/error', $data);
    }
}
