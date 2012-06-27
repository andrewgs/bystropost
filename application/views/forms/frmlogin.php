<?=form_open('',array('id'=>'login')); ?>
	<?php $this->load->view('alert_messages/alert-auth-error');?>
	<input type="text" id="login" class="input inpval" name="login" placeholder="Введите логин" />
	<input type="password" id="password" class="input inpval" name="password" placeholder="Введите пароль" />
	<input type=image src="<?=$baseurl;?>images/login.jpg" name="submit" class="go" />
	<br/><a href="">Забыли пароль?</a>
<?= form_close(); ?>