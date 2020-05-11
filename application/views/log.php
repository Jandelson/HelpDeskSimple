<style>
.table-wrapper-scroll-y {
margin-top:20px;
display: block;
max-height: 500px;
overflow-y: auto;
-ms-overflow-style: -ms-autohiding-scrollbar;
}
</style>
<?php $this->load->view('responder'); ?>
<br>
<div class="table-wrapper-scroll-y">
<table class="table table-bordered table-striped">
    <thead>
      <tr>
        <th scope="col">Usuário</th>
        <th scope="col">Tarefa</th>
        <th scope="col">Descrição</th>
        <th scope="col">Status</th>
        <th scope="col">Data/Hora Início</th>
      </tr>
    </thead>
    <tbody>
        <?php foreach ($dados as $log):?>
            <tr>
            <th scope="row"><?php echo $log->id;?></th>
            <td><?php echo 'SUPORTE';?></td>
            <td>
                <p><?php echo $log->descricao;?></p>
            </td>
            <td><?php echo $log->status;?></td>
            <td><?php echo $log->data_hora;?></td>
            </tr>
        <?php endforeach;?>
    </tbody>
  </table>
</div>