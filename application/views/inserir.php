<?php $this->load->view('header'); ?>
<div id="main" class="container-fluid">
  <h3 class="page-header">Novo Ticket</h3>
  <form class="" method="post" action="<?php echo base_url('salvar');?>" enctype="multipart/form-data">
  <div class="row">
	  <div class="form-group col-md-8">
      <label for="Prioridade">Tipo do chamado</label>
      	<select name="id_tipo_chamado" class="form-control" id="id_tipo_chamado" required>
            <option value="">Selecione uma opção</option>
            <?php foreach ($tipo_chamados as $dados):?>
                <option value=<?php echo $dados->id_tipo_chamado; ?>><?php echo $dados->descricao;?></option>
            <?php endforeach;?>
		</select>
  	  </div>
    </div>
  <div class="row">
	  <div class="form-group col-md-8">
	  <label for="titulo">Título do chamado</label>
	  	<input type="text" name="titulo" class="form-control" id="titulo" placeholder="Informe um título do chamado para melhor identificação." required></textarea>
  	  </div>
    </div>
    <div class="row">
	  <div class="form-group col-md-8">
	  <label for="email">E-mail</label>
	  	<input type="email" name="email_helpdesk" class="form-control" id="email_helpdesk" value='<?php echo $this->session->userdata('email');?>' placeholder="Informe um E-mail valido para receber os retonos do chamado." required></textarea>
  	  </div>
    </div>
	<div class="row">
	  <div class="form-group col-md-8">
	  <label for="descricao">Descrição da Pendência</label>
	  	<textarea name="descricao" class="form-control" rows="5" id="descricao" placeholder="Descrever com o máximo de detalhes possíveis para que possamos atende-lo de forma rapida!" required></textarea>
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
	<hr />
	<div class="row">
	  <div class="col-md-12">
	  	<button type="submit" class="btn btn-primary">Salvar</button>
		<a href="<?php echo base_url();?>" class="btn btn-default">Cancelar</a>
	  </div>
	</div>

  </form>
 </div>
 <?php $this->load->view('footer'); ?>