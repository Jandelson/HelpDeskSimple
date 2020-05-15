<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Email extends MY_Controller
{
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
}