<?php $this->load->view('header'); ?>
<style>
.wrapper {
  margin-top: 80px;
  margin-bottom: 80px;
}

.form-signin {
  max-width: 380px;
  padding: 15px 35px 45px;
  margin: 0 auto;
  background-color: #fff;
  border: 1px solid rgba(0, 0, 0, 0.1);
}
</style>
<div class="container">
    <div class="row">
        <div class="wrapper">
            <?php if ($formErrors ?? '') { ?>
                <div class="alert alert-danger">
                    <?=$formErrors?>
                </div>
            <?php } ?>
            <form class="form-signin" method="post" action="<?php echo base_url('login');?>" enctype="multipart/form-data">
                <div class="form-group" id="errorLogin">
                    <?php echo $error; ?>
                </div>
                <div class="form-group">
                    <label>Identificação:</label>
                    <input type="text" id="cnpj" name="cnpj" class="form-control" placeholder="CFP ou CNPJ" required>
                </div>
                <div class="form-group">
                    <label>E-mail:</label>
                    <input type="mail" id="email" name="email" class="form-control" placeholder="E-mail" required>
                </div>

                <div class="form-group">
                    <button type="submit" class="btn btn-primary float-right">Entrar</button>
                </div>
            </form>
        </div>
    </div>
</div>
<?php $this->load->view('footer'); ?>