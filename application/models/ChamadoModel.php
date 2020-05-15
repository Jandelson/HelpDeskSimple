<?php

defined('BASEPATH') or exit('No direct script access allowed');

class ChamadoModel extends CI_Model
{
    public $id_chamado;
    public $where;
    public $table;
    /**
     * Consulta das pendencias.
     *
     * @param int $id_chamado
     */
    public function getChamados($id_chamado = 0, $idusu = 0, $_limit = 10, $_start = 0, $where = '')
    {
        $this->id_chamado = $id_chamado;
        $this->idusu = $idusu;

        if ($this->id_chamado != 0) {
            $this->where = 'chamado.id_chamado=' . $this->id_chamado;
        } else {
            $this->where = $where;
        }

        $query = $this->db
            ->select('
                chamado.*,
                tipo_chamado.descricao descricao_tipo_chamado,
                DATE_FORMAT(chamado.data_hora_criacao,"%d/%m/%Y %H:%i:%s") data_hora_criacao,
                DATE_FORMAT(chamado.data_previsao,"%d/%m/%Y %H:%i:%s") data_previsao,
                DATE_FORMAT(chamado.data_previsao_atendimento,"%d/%m/%Y %H:%i:%s") data_previsao_atendimento,
                DATE_FORMAT(chamado.data_conclusao,"%d/%m/%Y") data_conclusao,
                u1.id usu,
                u2.id res,
                cliente.nome
            ')
            ->from($this->table)
            ->join('usuario u1', 'u1.codigo=chamado.id_usuario', 'left')
            ->join('usuario u2', 'u2.codigo=chamado.id_usuario_responsavel', 'left')
            ->join('cliente', 'cliente.id_cliente=chamado.id_cliente', 'left')
            ->join('tipo_chamado', 'tipo_chamado.id_tipo_chamado = chamado.id_chamado', 'left')
            ->where($this->where)
            ->limit($_limit, $_start)
            ->order_by('id_chamado', 'desc');

        $query = $this->db->get();

        return $query->result();
    }

    /**
     * Retorna id do responsavel e status
     *
     * @param int $id_chamado
     *
     * @return void
     */
    private function getChamadosIdResposavel($id_chamado)
    {
        $this->where = 'chamado.id_chamado=' . $id_chamado;
        $query = $this->db
            ->select('
                chamado.id_usuario_responsavel
                ,chamado.status
            ')
            ->from('chamado')
            ->where($this->where);

        $query = $this->db->get();

        return $query->result();
    }

    /**
     * retorno anexos na os.
     *
     * @param int $id_chamado
     */
    public function getChamadosAnexo($id_chamado)
    {
        $query = $this->db
            ->distinct()
            ->select('doc.nome anexo, doc.descricao')
            ->from('documento doc')
            ->where('id_chamado = ' . $id_chamado . ' and nome<>""');

        $query = $this->db->get();

        return $query->result();
    }

    /**
     * Retorno de Status da pendêncicliente.
     *
     * @param int $id_chamado
     */
    public function getChamadosStatus($id_chamado = 0, $idusu = 0, $where = '')
    {
        $this->id_chamado = $id_chamado;

        if ($this->id_chamado != 0) {
            $this->where = 'chamado.id_chamado=' . $this->id_chamado;
        } else {
            $this->where = $where;
        }

        $query = $this->db
            ->select('chamado.id_chamado id, ot.descricao status')
            ->from($this->table)
            ->join('helpdesk_status ot', 'ot.id_helpdesk_status=chamado.status')
            ->where($this->where);

        $query = $this->db->get();

        $dadosStatus = $query->result();

        if ($this->id_chamado == 0) {
            $ret = [];
            foreach ($dadosStatus as $k => $v) {
                $ret[$v->id] = $v->status;
            }

            return $ret;
        }

        return $dadosStatus;
    }

    /**
     * Retorna Histórico de interações.
     *
     * @param int $id_chamado
     */
    public function getChamadosLog($id_chamado)
    {
        $query = $this->db
            ->distinct()
            ->select('
                l.id_chamado_log
                ,l.id_chamado
                ,l.status
                ,l.descricao
                ,DATE_FORMAT(l.data_hora,"%d/%m/%Y %H:%i:%s") data_hora
                ,l.hora_final
                ,u.id
                ,u.codigo
            ')
            ->from('chamado_log l')
            ->join('usuario u', 'u.codigo=l.id_usuario', 'left')
            ->where('l.id_chamado = ' . $id_chamado)
            ->where('l.visivel_cliente = 0')
            ->order_by('id_chamado_log', 'desc');

        $query = $this->db->get();

        return $query->result();
    }

    /**
     * Inserção da Chamados.
     *
     * @param array $dados
     */
    public function insertChamados($dados, $table = '')
    {
        $this->table = $table;

        $this->db->insert($this->table, $dados);
        //Retorna do id após inserção
        $this->id_chamado = $this->db->insert_id();

        /**
         * Se inseriu algo em outra tabela que não seja apenas pendência retornar id do post
         */
        if (!empty($table)) {
            $this->id_chamado = $dados['id_chamado'];
            $data['dados'] = $this->getChamadosIdResposavel($this->id_chamado);
            /**
             * Update quando o chamado for respondido pelo cliente ajustar de aguardando cliente para executando
             */
            if ($data['dados'][0]->status == 4) {
                $sql = $this->db->update_string(
                    'os_pendencias',
                    ['status' => 1],
                    'id_chamado = ' . $this->id_chamado
                );
                $this->db->query($sql);
            }
        }

        return $this->id_chamado;
    }

    /**
     * analisa Cores dos status
     *
     * @param array $dados
     *
     * @return void
     */
    public function getCorStatus($dados)
    {
        foreach ($dados as $k => $v) {
            /*
             * Analisa Status Cor
             */
            switch ($v->status) {
                case 1:
                    $dados[$k]->corstatus = 'danger';
                    break;
                case 2:
                    $dados[$k]->corstatus = 'success';
                    break;
                case 3:
                    $dados[$k]->corstatus = 'warning ';
                    break;
                case 4:
                    $dados[$k]->corstatus = 'primary';
                    break;
                case 5:
                    $dados[$k]->corstatus = 'info';
                    break;
                case 6:
                    $dados[$k]->corstatus = 'primary';
                    break;
                default:
                    $dados[$k]->corstatus = 'primary';
                    break;
            }
        }
    }

    /**
     * Contador para paginação
     *
     * @return void
     */
    public function contador()
    {
        return $this->db->count_all($this->table);
    }
}
