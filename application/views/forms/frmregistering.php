<?=form_open($this->uri->uri_string(),array('id'=>'registration')); ?>
	<?php $this->load->view('alert_messages/alert-error');?>
	<big> Форма регистрации пользователя</big><br/>
	<input name="fio" type="text" class="inpval" value="" placeholder="Введите полное имя (обязательно)" /><br/>
	<input name="login" id="email" type="text" class="inpval" value="" placeholder="Введите Email (обязательно)" /><br/>
	<input name="password" id="password" type="password" class="inpval checkpass" placeholder="Введите пароль (обязательно)" value="" /><br/>
	<input name="confpass" id="confpass" class="inpval" type="password" placeholder="Повторите пароль (обязательно)" value="" /><br/>
	<input name="wmid" type="text" class="inpval digital" placeholder="Введите WMID (обязательно)" value="" maxlength="12"/><br/>
	<textarea name="knowus" placeholder="Откуда Вы узнали о нас?" rows="5" cols="55"></textarea><br/>
	<div style="margin:5px 0 0 60px;">
		<input name="sendmail" type="checkbox" value="1" class="checkbox" />
		<span style="margin-left:10px;">Присылать уведомления и важные новости</span><br/>
	</div>
	<button type="submit" name="submit" id="userRegister" style="padding: 0;" value="submit"><img src="<?=$baseurl;?>images/registration_go.png" alt="" /></button>
<?= form_close(); ?>