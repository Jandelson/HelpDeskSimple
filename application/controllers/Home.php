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
}
