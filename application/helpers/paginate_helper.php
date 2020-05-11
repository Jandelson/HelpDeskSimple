<?php
defined('BASEPATH') or exit('No direct script access allowed');
/**
 * Metodo que configura numero de registro por pagina
 */
function numRegister4PagePaginate()
{
    return 100;
}

/**
* Metodo que cria link de paginacao
    */
function createPaginate($_modulo, $_total)
{
    $ci = &get_instance();
    $ci->load->library('pagination');

    $config['base_url']    = base_url($_modulo);
    $config['total_rows']  = $_total;
    $config['per_page']    = numRegister4PagePaginate();
    $config["uri_segment"] = 3;

    $ci->pagination->initialize($config);
    return $ci->pagination->create_links();
}
