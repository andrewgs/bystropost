<?=form_open($this->uri->uri_string(),array('class'=>'form-horizontal')); ?>
	<fieldset>
		<legend>Общие данные</legend>
		<div class="control-group">
			<label for="login" class="control-label">Логин (E-mail):</label>
			<div class="controls">
				<input type="text" id="login" class="input-xlarge" disabled="disabled" value="<?=$user['login'];?>">
			</div>
		</div>
		<div class="control-group">
			<label for="fio" class="control-label">Полное имя:</label>
			<div class="controls">
				<input type="text" id="fio" class="input-xlarge" name="fio" value="<?=$user['fio'];?>">
				<span class="help-inline" style="display:none;">&nbsp;</span>
			</div>
		</div>
		<div class="control-group">
			<label for="fio" class="control-label">Дата регистрации:</label>
			<div class="controls">
				<input type="text" id="fio" class="input-medium" disabled="disabled" value="<?=$user['signdate'];?>">
			</div>
		</div>
		<legend>Смена пароля</legend>
		<div class="control-group">
			<label for="oldpas" class="control-label">Старый пароль:</label>
			<div class="controls">
				<input type="password" id="oldpas" class="input-medium" name="oldpas" value="">
				<span class="help-inline" style="display:none;">&nbsp;</span>
			</div>
		</div>
		<div class="control-group">
			<label for="password" class="control-label">Новый пароль:</label>
			<div class="controls">
				<input type="password" id="password" class="input-medium" name="password" value="">
				<span class="help-inline" style="display:none;">&nbsp;</span>
			</div>
		</div>
		<div class="control-group">
			<label for="confpass" class="control-label">Повторите пароль:</label>
			<div class="controls">
				<input type="password" id="confpass" class="input-medium" name="confpass" value="">
				<span class="help-inline" style="display:none;">&nbsp;</span>
			</div>
		</div>
		<legend>Личные данные</legend>
		<div class="control-group">
			<label for="wmid" class="control-label">WMID:</label>
			<div class="controls">
				<input type="text" id="wmid" class="input-medium digital" name="wmid" maxlength="10" value="<?=$user['wmid'];?>">
			</div>
		</div>
		<div class="control-group">
			<label for="phones" class="control-label">Контактный номер:</label>
			<div class="controls">
				<input type="text" id="phones" class="input-xlarge" name="phones" value="<?=$user['phones'];?>">
			</div>
		</div>
		<div class="control-group">
			<label for="icq" class="control-label">Номер ICQ:</label>
			<div class="controls">
				<input type="text" id="icq" class="input-xlarge digital" name="icq" value="<?=$user['icq'];?>">
			</div>
		</div>
		<div class="control-group">
			<label for="skype" class="control-label">Логин Skype:</label>
			<div class="controls">
				<input type="text" id="skype" class="input-xlarge" name="skype" value="<?=$user['skype'];?>">
			</div>
		</div>
		<legend>Дополнительно</legend>
		<div class="control-group">
			<div class="controls">
				<label class="checkbox">
					<input type="checkbox" id="sendmail" name="sendmail" value="1" <?= ($user['sendmail'] == 1)? 'checked="checked"': '';?>>
					Присылать уведомления и важные новости
				</label>
			</div>
		</div>	
		<div class="form-actions">
			<button class="btn btn-primary" type="submit" name="submit" id="submit" value="submit">Сохранить</button>
		</div>
	</fieldset>
<?= form_close(); ?>