<?=form_open($this->uri->uri_string(),array('class'=>'form-horizontal')); ?>
	<fieldset>
		<legend>Пополнение баланса<div style="float:right;">Текущий баланс: <?=$userinfo['balance'];?>.00 руб.</div></legend>
		<div class="control-group">
			<label for="mkprice" class="control-label">Сумма:</label>
			<div class="controls">
				<div class="input-prepend input-append">
					<span class="add-on">руб</span><input type="text" class="input-small digital" id="balance" name="balance"><span class="add-on">.00</span>
				</div>
				<div class="help-inline" style="display:none;">&nbsp;</div>
			</div>
			<hr/>
			<div class="controls">
				<div class="help-block">Внимание! Дождитесь сообщения о состоянии оплаты. Не меняйте и не закрывайте браузер. Если деньги списались с Вашего кошелька, а на баланс не поступили - обратитесь к администрации сайта.</div>
			</div>
		</div>
		<div class="form-actions">
			<button class="btn btn-primary" type="submit" name="submit" id="send" value="submit">Пополнить баланс</button>
		</div>
	</fieldset>
<?= form_close(); ?>