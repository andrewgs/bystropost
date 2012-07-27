<?=form_open($this->uri->uri_string(),array('class'=>'form-horizontal')); ?>
	<div id="addMarket" class="modal hide fade mkmodal">
		<div class="modal-header">
			<a class="close" data-dismiss="modal">×</a>
			<h3>Добавление учетной записи на бирже</h3>
		</div>
		<div class="modal-body">
			<fieldset>
				<div class="control-group">
					<label for="market" class="control-label">Биржа:</label>
					<div class="controls">
						<select id="MarketList" name="market" class="input-medium">
						<?php for($i=0;$i<count($markets);$i++):?>
							<option value="<?=$markets[$i]['id'];?>"><?=$markets[$i]['title'];?></option>
						<?php endfor;?>
						</select>
					</div>
				</div>
				<div class="control-group">
					<label class="control-label" for="login">Логин:</label>
					<div class="controls">
						<input type="text" id="login" class="input-xlarge inpval" name="login">
						<span class="help-inline" style="display:none;">&nbsp;</span>
					</div>
				</div>
				<div class="control-group">
					<label class="control-label" for="login">Пароль:</label>
					<div class="controls">
						<input type="text" id="password" class="input-xlarge inpval" name="password">
						<span class="help-inline" style="display:none;">&nbsp;</span>
					</div>
				</div>
				<div class="control-group">
					<label class="control-label" for="login">Код:</label>
					<div class="controls">
						<input type="text" id="loading" class="input-small inpval digital" name="loading">
						<span class="help-inline" style="display:none;">&nbsp;</span>
						<span class="help-block" style="margin-top: 5px;">Внимание! Площадки импортируются автоматически.<br/>Если импортировать не нужно введите код: <strong>9846</strong></span>
					</div>
				</div>
			</fieldset>
		</div>
		<div class="modal-footer">
			<button class="btn" data-dismiss="modal">Отменить</button>
			<button class="btn btn-success" type="submit" id="amsend" name="amsubmit" value="send">Добавить</button>
		</div>
	</div>
<?= form_close(); ?>