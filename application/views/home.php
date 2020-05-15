<?php $this->load->view('header');?>
<div id="main" class="container-fluid" style="margin-top: 50px">
<h5>Empresa (<?php echo $cliente[0]->nome;?>)</h5>
     <div id="top" class="row">
        <div class="col-sm-3">
            <h2>Tickets - <?php echo $this->session->userdata('nomusu')?></h2>
        </div>
        <div class="col-sm-6">

            <div class="input-group h2">
                <input name="data[search]" class="form-control" id="search" type="text" placeholder="Pesquisar Tickets">
                <span class="input-group-btn">
                    <button class="btn btn-primary" type="submit">
                        <span class="glyphicon glyphicon-search"></span>
                    </button>
                </span>
            </div>

        </div>
        <div class="col-sm-3">
            <a href="<?php echo base_url('inserir')?>" class="btn btn-primary pull-right h2">Novo Ticket +</a>
        </div>
    </div> <!-- /#top -->

     <hr />
     <div id="list" class="row">

    <div class="table-responsive col-md-12">
        <table class="table table-striped" cellspacing="0" cellpadding="0">
            <thead>
                <tr>
                    <th>Ticket</th>
                    <th>Chamado</th>
                    <th>Tipo</th>
                    <th>Responsável</th>
                    <th>Data/Hora</th>
                    <th>Concluido</th>
                    <th>Status</th>
                    <th class="actions">Ações</th>
                </tr>
            </thead>
            <tbody id="tabletickets">
                <?php foreach ($dados as $tickets) :?>
                <tr>
                <td><?php echo $tickets->id_chamado;?></td>
                <td>
                    <?php echo '#' . $tickets->titulo;?>
                </td>
                <td><?php echo $tickets->descricao_tipo_chamado;?></td>
                <td><?php echo $tickets->res;?></td>
                <td><?php echo $tickets->data_hora_criacao;?></td>
                <td><?php echo $tickets->data_conclusao;?></td>
                <td><span class="label label-<?php echo $tickets->corstatus;?>"><?php echo $status[$tickets->id_chamado];?></span></td>
                <td class="actions">
                        <a class="btn btn-info btn-xs" href="<?php echo base_url('/visualizar/' . $tickets->id_chamado) ?>"
                        title="Previsão de Atendimento: <?php echo $tickets->data_previsao_atendimento;?>">Visualizar</a>
                        <!-- <a type="button" data-toggle="modal" data-target="#finalizar" title="finalizar" class="btn btn-warning btn-xs">Finalizar</a> -->
                    </td>
                </tr>
                <?php endforeach;?>
            </tbody>
        </table>
    </div>
    <hr />
    </div> <!-- /#list -->

    <div id="bottom" class="row">
        <div class="col-md-12">
            <ul class="pagination">
                <li> <?php echo str_replace(
                    ['<strong>', '</strong>'],
                    ['<li class="active"><a href="#">', ''],
                    $paginacao
                );
                ?></li>
            </ul>
        </div>
    </div>
    </div>

<!-- Modal -->
<div class="modal fade" id="delete-modal" tabindex="-1" role="dialog" aria-labelledby="modalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Fechar"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="modalLabel">Excluir Item</h4>
      </div>
      <div class="modal-body">
        Deseja realmente excluir este item?
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-primary">Sim</button>
    <button type="button" class="btn btn-default" data-dismiss="modal">N&atilde;o</button>
      </div>
    </div>
  </div>
</div>
<?php $this->load->view('footer'); ?>