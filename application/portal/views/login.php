<html xmlns="http://www.w3.org/1999/xhtml" lang="en" xml:lang="en">
  <head>
    <title><?php 'echo $title' ?> - Modulo Albo Pretorio <?php 'echo MISSIONI_VER' ?></title>
    <meta http-equiv="Content-Type" content="text/html;charset=utf-8" />
    <link rel="stylesheet" href="<?php echo base_url()?>css/mainstyle.css" media="screen,handheld,projection" />
    <link rel="shortcut icon" href="<?php echo base_url()?>images/web/favicon.ico" />
    </head>
  <body><h1>WELCOME Portale Applicativo Ortona </h1>
  <br>
  <b>Prego fornire le credenziali per accedere al Sistema:</b>
  <br>
  
<?php echo 'CI version-->'.CI_VERSION ?>
<?php  echo form_open('main/index/')?>
	<?php echo form_fieldset('Login Form')?>

		<div class="textfield">
			<?php echo form_label('username', 'user_name')?>
			<?php echo form_input('user_name')?>
                        <br></br>
                        <?php echo form_label('password', 'user_pass')?>
			<?php echo form_password('user_pass')?>
		</div>

		<div class="buttons">
			<?php echo form_submit('login', 'Login')?>
		</div>

	<?php echo form_fieldset_close()?>
<?php echo form_close();?>
<?php  require dirname(__FILE__).'/includes/footer.php'; ?>
</body>
</html>