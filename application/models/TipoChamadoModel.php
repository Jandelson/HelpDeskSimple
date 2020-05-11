<?php

defined('BASEPATH') or exit('No direct script access allowed');

class TipoChamadoModel extends CI_Model
{
    public $where;
    public $table = 'tipo_chamado';

    /**
     * Retorna tipos chamado.
     *
     */
    public function getTipoChamados()
    {
        $this->where = 'descricao<>""';
        $query = $this->db
            ->select('*')
            ->from($this->table)
            ->where($this->where)
            ->order_by('id_tipo_chamado', 'desc');

        $query = $this->db->get();

        return $query->result();
    }
}
