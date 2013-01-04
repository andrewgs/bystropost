<?=form_open($this->uri->uri_string(),array('id'=>'frmaddticket','class'=>'form-horizontal')); ?>
	<input type="hidden" id="TicketType" name="type" value="1" />
	<input type="hidden" id="TicketImportance" name="importance" value="1" />
	<fieldset>
		<legend>Воспользуйтесь формой для создания тикета</legend>
			<div class="control-group">
				<label for="login" class="control-label">Тема тикета:</label>
				<div class="controls">
					<input type="text" name="title" class="span6 input-valid" data-placement="bottom" role="tooltip" data-original-title="Поле не должно быть пустым" value="" autocomplete="off" placeholder="Введите тему тикета">
				</div>
			</div>
			<div class="control-group">
				<label for="login" class="control-label">Тип тикета:</label>
				<div class="controls">
					<div class="btn-group" data-toggle="buttons-radio">
						<button type="button" class="btn btn-types" data-value="1" autocomplete="off" title="Тикет будет создан для вебмастера">Для вебмастера</button>
						<button type="button" class="btn btn-types" data-value="2" autocomplete="off" title="Тикет будет создан для менеджера">Для менеджера</button>
					</div>
				</div>
			</div>
			<div class="control-group">
				<label for="login" class="control-label">Площадка:</label>
				<div class="controls">
					<input type="text" class="span6 input-valid" name="platform" data-provide="typeahead" id="PlatformTicket" autocomplete="off" data-placement="bottom" role="tooltip" data-original-title="Поле не должно быть пустым" placeholder="Введите URL площадки" value="<?=(isset($create_ticket))?$create_ticket:''?>" >
					<br/><span class="label label-info">Внимание! Нужно ввести полный URL площадки или "без площадки"</span>
				</div>
			</div>
			<div class="control-group">
				<label for="importance" class="control-label">Срочность:</label>
				<div class="controls">
					<div class="btn-group" data-toggle="buttons-radio">
						<button type="button" class="btn btn-importance" data-value="1" autocomplete="off">Низкая</button>
						<button type="button" class="btn btn-warning btn-importance" data-value="2" autocomplete="off">Средняя</button>
						<button type="button" class="btn btn-danger btn-importance" data-value="3" autocomplete="off">Критичная</button>
					</div>
				</div>
			</div>
			<div class="control-group">
				<label for="login" class="control-label">Сообщение:</label>
				<div class="controls">
					<textarea class="span7 input-valid" data-provide="typeahead" autocomplete="off" data-placement="bottom" role="tooltip" data-original-title="Поле не должно быть пустым" name="text" rows="4"></textarea>
				</div>
			</div>
	</fieldset>
	<div class="form-actions">
		<button class="btn btn-primary SubmitTicket" type="submit" name="insticket" value="submit">Создать</button>
		<input class="btn btn-inverse BtnInsertTicket" type="button" value="Отменить">
	</div>
<?= form_close(); ?>