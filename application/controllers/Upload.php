<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Upload extends MY_Controller
{
    /**
     * Processo de upload
     *
     * @param [type] $arquivo
     * @return void
     */
    public function uploadAnexo($arquivo)
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
}