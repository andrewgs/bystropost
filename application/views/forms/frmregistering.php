<?=form_open($this->uri->uri_string(),array('id'=>'registration')); ?>
	<?php $this->load->view('alert_messages/alert-error');?>
	<big> Форма регистрации <?=$usertype;?></big><br/>
	<input name="fio" type="text" class="inpval" value="" placeholder="Введите полное имя (обязательно)" /><br/>
	<input name="login" id="email" type="text" class="inpval" value="" placeholder="Введите Email (обязательно)" /><br/>
	<input name="password" id="password" type="password" class="inpval checkpass" placeholder="Введите пароль (обязательно)" value="" /><br/>
	<input name="confpass" id="confpass" class="inpval" type="password" placeholder="Повторите пароль (обязательно)" value="" /><br/>
	<input name="wmid" id="wmid" type="text" class="inpval digital" placeholder="Введите WMID (обязательно)" value="" maxlength="12"/><br/>
	<textarea name="knowus" placeholder="Откуда Вы узнали о нас?" rows="5" cols="55"></textarea><br/>
	<div style="margin:5px 0 20px 60px;">
	<?php if($this->uri->segment(3) == 'webmaster'):?>
		<input name="promo" id="promo" class="digital" type="password" placeholder="Введите промокод" value="" style="width:150px;margin-left:110px;" maxlength="8"/><br/>
	<?php endif;?>
		<input name="sendmail" type="checkbox" value="1" class="checkbox" checked="checked" />
		<span style="margin-left:10px;">Присылать уведомления и важные новости</span><br/>
	</div>
	<button type="submit" name="submit" id="userRegister" style="padding:0;" value="submit"><img src="<?=$baseurl;?>images/registration_go.png" alt="" /></button>
<?= form_close(); ?>