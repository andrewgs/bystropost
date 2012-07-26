<?=form_open($this->uri->uri_string(),array('class'=>'form-horizontal')); ?>
	<fieldset>
		<legend>Пополнение баланса<div style="float:right;">Текущий баланс: <?=$userinfo['balance'];?>.00 руб.</div></legend>
		<div class="control-group">
			<label for="balance" class="control-label">Сумма:</label>
			<div class="controls">
				<div class="input-prepend input-append">
					<span class="add-on">руб</span><input type="text" class="input-small digital" id="balance" name="balance"><span class="add-on">.00</span>
				</div>
				<div class="help-inline" id="hlbalance" style="display:none;">&nbsp;</div>
			</div>
		</div>
		<div class="control-group">
			<label for="purse" class="control-label">Кошелек WM:</label>
			<div class="controls">
				<div class="input-prepend input-append">
					<span class="add-on">R</span><input type="text" class="input-medium digital" maxlength="12" id="purse" name="purse">
				</div>
				<div class="help-inline" id="hlpurse" style="display:none;">&nbsp;</div>
			</div>
		</div>
		<div class="control-group">
			<label for="wmid" class="control-label">WMID: </label>
			<div class="controls">
				<div class="help-block" style="margin-top: 5px;"><?=$wmid;?></div>
			</div>
		</div>
		<div class="control-group">
			<div class="controls">
				<div class="help-block" style="margin-top: 5px;">Внимание! Дождитесь сообщения о состоянии оплаты. Не меняйте и не закрывайте браузер. Если деньги списались с Вашего кошелька, а на баланс не поступили - обратитесь к администрации сайта.</div>
			</div>
		</div>
		<div class="form-actions">
			<button class="btn btn-primary" type="submit" name="submit" id="send" value="submit">Пополнить баланс</button>
		</div>
	</fieldset>
<?= form_close(); ?>