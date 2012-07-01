<?=form_open($this->uri->uri_string(),array('class'=>'form-horizontal')); ?>
	<fieldset>
		<legend>Рассылка системных сообщений</legend>
		<div class="control-group">
			<label for="users" class="control-label">Типы пользователей</label>
			<div class="controls">
				<select id="users">
					<option value="0">Вебмастера</option>
					<option value="1">Менеджеры</option>
					<option value="2">Оптимизаторы</option>
					<option value="3">Администраторы</option>
				</select>
			</div>
		</div>
		<div class="control-group">
			<label for="messages" class="control-label">Тип сообщения</label>
			<div class="controls">
				<select id="messages">
					<option value="0">Важное</option>
					<option value="1">Информация</option>
					<option value="2">Предупреждение</option>
					<option value="3">Уведомление</option>
				</select>
			</div>
		</div>
		<div class="control-group">
			<label for="text" class="control-label">Сообщение</label>
			<div class="controls">
				<textarea id="text" class="input-xlarge redactor" style="width: 520px; height: 300px;"></textarea>
			</div>
		</div>
		<div class="form-actions">
			<button class="btn btn-primary" id="send" value="sens" name="submit" type="submit">Отправить сообщение</button>
		</div>
	</fieldset>
<?= form_close(); ?>