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

        if (!empty($_POST['cnpj']) and !empty($_POST['email'])) {
            $this->session->set_userdata('cgc_cpf', $_POST['cnpj']);
            $this->session->set_userdata('codusu', 0);
            $this->session->set_userdata('nomusu', $_POST['email']);
            $this->session->set_userdata('codemp', 0);
            $this->session->set_userdata('userid', $_POST['cnpj'] . $_POST['email']);
            $this->session->set_userdata('email', $_POST['email']);
            $this->indexController();
        } else {
            $this->load->view('login/login', $data);
        }
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
