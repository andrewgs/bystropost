<?=form_open($this->uri->uri_string(),array('class'=>'form-horizontal')); ?>
	<input type="hidden" class="idMarket" value="" name="mid" />
	<div id="editMarket" class="modal hide fade mkmodal">
		<div class="modal-header">
			<a class="close" data-dismiss="modal">×</a>
			<h3>Редактирование учетной записи на бирже</h3>
		</div>
		<div class="modal-body">
			<fieldset>
				<div class="control-group">
					<label for="market" class="control-label">Биржа:</label>
					<div class="controls">
						<select id="eMarketList" name="market" class="input-medium disabled" disabled="disabled">
						<?php for($i=0;$i<count($markets);$i++):?>
							<option value="<?=$markets[$i]['id'];?>"><?=$markets[$i]['title'];?></option>
						<?php endfor;?>
						</select>
					</div>
				</div>
				<div class="control-group">
					<label class="control-label" for="login">Логин:</label>
					<div class="controls">
						<input type="text" id="elogin" class="input-xlarge einpval disabled" name="login" disabled="disabled">
						<span class="help-inline" style="display:none;">&nbsp;</span>
					</div>
				</div>
				<div class="control-group">
					<label class="control-label" for="login">Новый пароль:</label>
					<div class="controls">
						<input type="text" id="epassword" class="input-xlarge einpval" name="password">
						<span class="help-inline" style="display:none;">&nbsp;</span>
					</div>
				</div>
			</fieldset>
		</div>
		<div class="modal-footer">
			<button class="btn" data-dismiss="modal">Отменить</button>
			<button class="btn btn-success" type="submit" id="smsend" name="smsubmit" value="send">Сохранить</button>
		</div>
	</div>
<?= form_close(); ?>