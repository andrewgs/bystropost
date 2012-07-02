<?=form_open($this->uri->uri_string(),array('class'=>'form-horizontal')); ?>
	<fieldset>
		<legend>Рассылка системных сообщений</legend>
		<div class="control-group">
			<label for="users" class="control-label">Типы пользователей</label>
			<div class="controls">
				<select id="group" name="group">
					<option value="1">Вебмастера</option>
					<option value="2">Менеджеры</option>
					<option value="3">Оптимизаторы</option>
					<option value="5">Администраторы</option>
				</select>
			</div>
		</div>
		<div class="control-group">
			<label for="messages" class="control-label">Тип сообщения</label>
			<div class="controls">
				<select id="type" name="type">
					<option value="1">Важное</option>
					<option value="2">Информация</option>
					<option value="3">Предупреждение</option>
					<option value="4">Уведомление</option>
				</select>
			</div>
		</div>
		<div class="control-group">
			<label for="text" class="control-label">Сообщение</label>
			<div class="controls">
				<textarea id="text" class="input-xlarge redactor" name="text" style="width: 520px; height: 300px;"></textarea>
			</div>
		</div>
		<div class="form-actions">
			<button class="btn btn-primary" id="send" value="sens" name="submit" type="submit">Отправить сообщение</button>
		</div>
	</fieldset>
<?= form_close(); ?>