<?=form_open($this->uri->uri_string(),array('id'=>'frmaddticket','class'=>'form-horizontal')); ?>
	<fieldset>
		<div class="control-group warning">
			<label for="login" class="control-label">Сообщение:</label>
			<div class="controls">
				<textarea class="span7 input-valid" data-provide="typeahead" autocomplete="off" data-placement="bottom" role="tooltip" data-original-title="Поле не должно быть пустым" name="text" rows="4"></textarea>
			</div>
		</div>
	<?php if($ticket['recipient']):?>
		<div class="control-group">
			<div class="controls">
				<label class="checkbox">
					<input id="sendMail" name="sendmail" type="checkbox" value="1">Уведомить получателя по почте
				</label>
			</div>
		</div>
	<?php endif;?>
	</fieldset>
	<div class="form-actions">
		<button class="btn btn-primary SubmitTicket" type="submit" name="insticket" value="submit">Создать</button>
		<input class="btn btn-inverse BtnInsertTicket" type="button" value="Отменить">
	</div>
<?= form_close(); ?>