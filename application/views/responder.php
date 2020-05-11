<div class= "col-sm-1">
    <button type="button" data-toggle="modal" data-target="#responder" title="Responder" class="btn btn-success btn-xs"><label>Responder&nbsp;</label><i class="fa fa-reply" aria-hidden="true"></i></button>
</div>
<!-- Modal -->
<div class="modal fade" id="responder" tabindex="-1" role="dialog" aria-labelledby="responder" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="responder">Responder atividade</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true" titlle="Fechar">&times;</span>
        </button>
      </div>
      <div class="modal-body">
      <form class="" method="post" action="<?php echo base_url('resposta');?>" enctype="multipart/form-data">
        <div class="row">
        <div class="form-group col-md-8">
        <label for="descricao">Descrição da Resposta</label>
            <textarea name="descricao" class="form-control" rows="5" id="descricao" required></textarea>
        </div>
        </div>
        <!-- 
        <div class="row">
          <div class="form-group col-md-8">
            <label for="descricao">Anexo</label>
              <input type="file" class="form-control" name="anexo">
          </div>
        </div>
       -->
       <input type="hidden" id="id_chamado" name="id_chamado" value="<?php echo $this->uri->segment(2); ?>">
        <hr>
        <div class="row">
        <div class="col-md-12">
            <button type="submit" class="btn btn-primary">Salvar</button>
            <button type="button" class="btn btn-default" data-dismiss="modal" aria-label="Close">Cancelar</button>
        </div>
        </div>
     </form>
      </div>
      <div class="modal-footer">
      </div>
    </div>
  </div>
</div>
