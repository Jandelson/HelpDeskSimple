<!-- Modal -->
<?php $this->load->view('header'); ?>
<div id="main" class="container">
<br>
<div id="finalizar">
  <div role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="responder">Finalizar Pendência #<?php echo $this->uri->segment(2);?></h5>
      </div>
      <div class="modal-body">
      <form method="post" action="<?php echo base_url('concluir/'.$this->uri->segment(2));?>">
        <div class="row">
        <div class="form-group col-md-8">
        <label for="descricao">Motivo da finalização</label>
            <textarea name="descricao" class="form-control" rows="5" id="descricao" required></textarea>
        </div>
        </div>
        <hr>
        <div class="row">
        <div class="col-md-12">
            <button type="submit" class="btn btn-success">Salvar</button>
            <a href="<?php echo base_url('visualizar/'.$this->uri->segment(2));?>" class="btn btn-danger">Cancelar</a>
        </div>
        </div>
     </form>
      </div>
      <div class="modal-footer">
      </div>
    </div>
  </div>
</div>
</div>
<?php $this->load->view('footer'); ?>