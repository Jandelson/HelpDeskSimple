<?php $this->load->view('header'); ?>
<!-- Fim Modal-->
 <div id="main" class="container">
    <h4 class="page-header">
        <div id="actions" class="row">
        <div class="col-md-12">
            <a class="" data-toggle="collapse" href="#<?php echo $dados[0]->id_chamado;?>" role="button" aria-expanded="false" aria-controls="collapseExample">
                Ticket #<?php echo $dados[0]->id_chamado;  echo '  (' . $dados[0]->titulo . ' ) '?>
            </a>
            <div class="text-right">
            <a href="<?php echo base_url('home');?>" class="btn btn-primary btn-xs">Voltar</a>
            <?php if ($dados[0]->status <> 2) { ?>
            <a href="<?php echo base_url('finalizar/' . $this->uri->segment(2)); ?>" class="btn btn-warning btn-xs">Finalizar</a>
            <?php } ?>
            </div>
        </div>
    </h4>
    <div class="panel-collapse collapse in" id="<?php echo $dados[0]->id_chamado;?>">
        <div class="card card-body">
            <div class="row">
                <div class="col-md-4">
                <p><strong>Nome do Solicitante</strong></p>
                <p><?php echo $dados[0]->solicitante;?></p>
                </div>

                <div class="col-md-4">
                <p><strong>Responsável</strong></p>
                <p><?php echo $dados[0]->res;?></p>
                </div>

                <div class="col-md-4">
                <p><strong>Status</strong></p>
                <p><span class="label label-<?php echo $dados[0]->corstatus;?>"><?php echo $status[0]->status;?></span></p>
                </div>

                <div class="col-md-4">
                <p><strong>Tipo chamado</strong></p>
                <p> <?php echo $dados[0]->descricao_tipo_chamado;?></p>
                </div>

                <div class="col-md-4">
                <p><strong>Previsão atendimento</strong></p>
                <p><?php echo $dados[0]->data_previsao_atendimento;?></p>
                </div>

                <div class="col-md-4">
                <p><strong>Previsão conclusão</strong></p>
                <p><?php echo $dados[0]->data_previsao;?></p>
                </div>

                <div class="col-md-8">
                <p><strong>Descrição</strong></p>
                <p><?php echo $dados[0]->descricao;?></p>
                </div>

                <div class="col-md-8">
                <p><strong>Anexos</strong>
                    <?php foreach ($anexos as $docs):?>
                    <a href="<?php echo '/documentos_anexos/' . $docs->anexo;?>" target="_blank" class="btn btn-default"><i class="far fa-file-pdf"></i>
                    <?php echo $docs->descricao; ?>
                    </a>
                    <?php endforeach;?>
                </p>
                </div>
            </div>
        </div>
    </div>
</div>
<?php $this->load->view('footer'); ?>