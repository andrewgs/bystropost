<?=form_open($this->uri->uri_string(),array('class'=>'form-horizontal')); ?>
	<input type="hidden" class="idUser" value="" name="uid" />
	<div id="editUser" class="modal hide fade dmodal">
		<div class="modal-header">
			<a class="close" data-dismiss="modal">×</a>
			<h3>Редактирование информации о пользователе</h3>
		</div>
		<div class="modal-body">
			<fieldset>
				<div class="control-group">
					<label for="fio" class="control-label">Имя пользователя: </label>
					<div class="controls">
						<input type="text" disabled="disabled" class="input-xlarge euinput eFio" name="fio">
						<span class="help-inline" style="display:none;">&nbsp;</span>
					</div>
				</div>
				<div class="control-group">
					<label for="login" class="control-label">Логин пользователя: </label>
					<div class="controls">
						<input type="text" disabled="disabled" class="input-xlarge euinput eLogin" name="login">
						<span class="help-inline" style="display:none;">&nbsp;</span>
					</div>
				</div>
				<div class="control-group">
					<label for="wmid" class="control-label">WMID: </label>
					<div class="controls">
						<input type="text" id="eWMID" class="input-xlarge euinput digital" name="wmid">
						<span class="help-inline" style="display:none;">&nbsp;</span>
					</div>
				</div>
				<div class="control-group">
					<label for="balance" class="control-label">Баланс: </label>
					<div class="controls">
						<input type="text" id="eBalance" class="input-xlarge euinput digital" name="balance">
						<span class="help-inline" style="display:none;">&nbsp;</span>
					</div>
				</div>
				<div class="control-group">
					<label for="type" class="control-label">Тип пользователя: </label>
					<div class="controls">
						<select id="eType" name="type">
							<option value="1">Вебмастер</option>
							<option value="2">Менеджер</option>
							<option value="3">Оптимизатор</option>
							<option value="4">Резерв</option>
							<option value="5">Администратор</option>
						</select><br/>
						<span class="help-inline" style="display:none;">&nbsp;</span>
					</div>
				</div>
			</fieldset>
		</div>
		<div class="modal-footer">
			<button class="btn" data-dismiss="modal">Отменить</button>
			<button class="btn btn-success" type="submit" id="eusend" name="eusubmit" value="send">Сохранить</button>
		</div>
	</div>
<?= form_close(); ?>