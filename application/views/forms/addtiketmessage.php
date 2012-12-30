<?=form_open($this->uri->uri_string(),array('id'=>'frmaddticket','class'=>'form-horizontal')); ?>
	<fieldset>
		<div class="control-group">
			<label for="login" class="control-label">Текст ответа:</label>
			<div class="controls">
				<textarea class="span7 input-valid" data-provide="typeahead" autocomplete="off" data-placement="bottom" role="tooltip" data-original-title="Поле не должно быть пустым" name="text" rows="4"></textarea>
			</div>
		</div>
		<div class="control-group">
			<div class="controls">
				<label class="checkbox span1">
					<input id="closeTicket" name="closeticket" type="checkbox" autocomplete="off" value="1"><span class="label label-warning">ЗАКРЫТЬ ТИКЕТ</span>
				</label>
			<?php if(($ticket['recipient'] && $ticket['sender']) OR ($this->user['utype'] == 5) ):?>
				<div class="clear"></div>
				<label class="checkbox span3">
					<input id="sendMail" name="sendmail" type="checkbox" autocomplete="off" value="1"><span class="label label-inverse">Уведомить получателя по почте</span>
				</label>
			<?php endif;?>
			</div>
		</div>
	</fieldset>
	<div class="form-actions">
		<button class="btn btn-primary SubmitMessage" type="submit" name="insticket" value="submit">Добавить</button>
		<input class="btn btn-inverse BtnInsertMessage" type="button" value="Отменить">
	</div>
<?= form_close(); ?>