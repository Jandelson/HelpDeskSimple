<?php

defined('BASEPATH') or exit('No direct script access allowed');

class ChamadoModel extends CI_Model
{
    public $id_chamado;
    public $where;
    public $table = 'chamado';
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
            $this->where = 'p.id_chamado=' . $this->id_chamado;
        } else {
            $this->where = $where;
        }

        $query = $this->db
            ->select('
                p.*,
                otc.descricao descricao_tipo_chamado,
                DATE_FORMAT(p.data_hora_criacao,"%d/%m/%Y %H:%i:%s") data_hora_criacao,
                DATE_FORMAT(p.data_previsao,"%d/%m/%Y %H:%i:%s") data_previsao,
                DATE_FORMAT(p.data_previsao_atendimento,"%d/%m/%Y %H:%i:%s") data_previsao_atendimento,
                DATE_FORMAT(p.data_conclusao,"%d/%m/%Y") data_conclusao,
                u1.id usu,
                u2.id res,
                a.nome
            ')
            ->from($this->table . ' p')
            ->join('usuario u1', 'u1.codigo=p.id_usuario', 'left')
            ->join('usuario u2', 'u2.codigo=p.id_usuario_responsavel', 'left')
            ->join('cliente a', 'a.id_cliente=p.id_cliente', 'left')
            ->join('tipo_chamado otc', 'otc.id_tipo_chamado = p.id_chamado', 'left')
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
        $this->where = 'p.id_chamado=' . $id_chamado;
        $query = $this->db
            ->select('
                p.id_usuario_responsavel
                ,p.status
            ')
            ->from('chamado' . ' p')
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
     * Retorno de Status da pendência.
     *
     * @param int $id_chamado
     */
    public function getChamadosStatus($id_chamado = 0, $idusu = 0, $where = '')
    {
        $this->id_chamado = $id_chamado;

        if ($this->id_chamado != 0) {
            $this->where = 'p.id_chamado=' . $this->id_chamado;
        } else {
            $this->where = $where;
        }

        $query = $this->db
            ->select('p.id_chamado id, ot.descricao status')
            ->from($this->table . ' p')
            ->join('helpdesk_status ot', 'ot.id_helpdesk_status=p.status')
            ->where($this->where);

        $query = $this->db->get();

        $arr = $query->result();

        if ($this->id_chamado == 0) {
            $ret = [];
            foreach ($arr as $k => $v) {
                $ret[$v->id] = $v->status;
            }
        } else {
            $ret = $arr;
        }

        return $ret;
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
        if (isset($dados['nome_arquivo'])) {
            $nome_arquivo = $dados['nome_arquivo'];
            $descricao_arquivo = $dados['descricao_arquivo'];
            unset($dados['nome_arquivo']);
            unset($dados['descricao_arquivo']);
        }
        if (!empty($table)) {
            $this->table = $table;
            /*
             * Pega mensagem para enviar o recado
             */
            $msg = $dados['descricao'];
        }

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
            $dados_recado = [
                'usuario' => $data['dados'][0]->id_usuario_responsavel,
                'origem' => $this->config->config['usuario_recado'],
                'recado' => 'Pendência Helpdesk: ' . '<a href="' . $this->config->config['link_chamado'] . '' . $this->id_chamado . '" target="_blank">
                <u>' . $this->id_chamado . '</u>
                </a>'
                    . '<br>' . $this->session->userdata('nomusu') . ' : ' . $msg,
                'lido' => 0,
                'datarecado' => date('Y-m-d H:i:s'),
            ];
        }
        /*
         * Insert do Anexo
         */
        if (!empty($nome_arquivo)) {
            $dados_anexo = [
                'ligacao' => 4,
                'id_ligacao' => $this->id_chamado,
                'data_upload' => date('Y-m-d H:i:s'),
                'id_usuario' => 1,
                'nome' => $nome_arquivo,
                'descricao' => $descricao_arquivo
            ];
            $this->db->insert('documentos', $dados_anexo);
        }

        return $this->id_chamado;
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
