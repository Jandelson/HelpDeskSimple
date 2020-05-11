<?php

defined('BASEPATH') or exit('No direct script access allowed');

class ClienteModel extends CI_Model
{
    public $id_cliente;
    public $where;
    public $table = 'cliente';

    /**
     * Consulta Cliente.
     *
     * @param int $cgc_cpf
     */
    public function getCliente($cgc_cpf = 0): array
    {
        $this->cgc_cpf = $cgc_cpf;
        
        $this->where = 'a.identificacao='."'".$this->cgc_cpf."'";
    
        $query = $this->db
            ->select('a.nome, a.id_cliente, l.id_cliente')
            ->from($this->table.' a')
            ->join('licencas l', 'l.id_cliente=a.id_cliente', 'left')
            ->where($this->where)
            ->limit(1);

        $query = $this->db->get();

        return $query->result();
    }
}
