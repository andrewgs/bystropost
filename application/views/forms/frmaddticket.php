<?=form_open($this->uri->uri_string(),array('id'=>'frmaddticket','class'=>'form-horizontal')); ?>
	<input type="hidden" id="TicketType" name="type" value="1" />
	<fieldset>
		<legend>Воспользуйтесь формой для создания тикета</legend>
		<ul id="ProductTab" class="nav nav-tabs">
			<li class="active"><a href="#parameters" data-toggle="tab"><strong>Параметры тикета</strong></a></li>
			<li><a href="#message" data-toggle="tab"><strong>ТЕКСТ ТИКЕТА</strong></a></li>
		</ul>
		<div id="ProductTabContent" class="tab-content">
			<div class="tab-pane fade in active" id="parameters">
				<div class="control-group warning">
					<label for="login" class="control-label">Тема тикета:</label>
					<div class="controls">
						<input type="text" name="title" class="span6 input-valid" data-placement="bottom" role="tooltip" data-original-title="Поле не должно быть пустым" value="" autocomplete="off" placeholder="Введите тему тикета">
					</div>
				</div>
				<div class="control-group">
					<label for="login" class="control-label">Тип тикета:</label>
					<div class="controls">
						<div class="btn-group" data-toggle="buttons-radio">
							<button type="button" class="btn btn-types" data-value="1" autocomplete="off" title="Вопросы, замечания по выполнению текущих заявок">Размещение заявок</button>
							<button type="button" class="btn btn-types" data-value="2" autocomplete="off" title="Связь с администрацией">Вопрос по работе с сервисом</button>
						</div>
					</div>
				</div>
				<div class="control-group warning">
					<label for="login" class="control-label">Площадка:</label>
					<div class="controls">
						<input type="text" class="span6 input-valid" name="platform" data-provide="typeahead" id="PlatformTicket" autocomplete="off" data-placement="bottom" role="tooltip" data-original-title="Поле не должно быть пустым" placeholder="Введите URL площадки" >
						<br/><span class="label label-info">Внимание! Нужно ввести полный URL площадки или "Без площадки".</span>
					</div>
				</div>
			</div>
			<div class="tab-pane fade in" id="message">
				<div class="control-group warning">
					<label for="login" class="control-label">Сообщение:</label>
					<div class="controls">
						<textarea class="span7 input-valid" data-provide="typeahead" autocomplete="off" data-placement="bottom" role="tooltip" data-original-title="Поле не должно быть пустым" name="text" rows="6"></textarea>
					</div>
				</div>
			</div>
		</div>
	</fieldset>
	<div class="form-actions">
		<button class="btn btn-primary SubmitTicket" type="submit" name="insticket" value="submit">Создать</button>
		<input class="btn btn-inverse BtnInsertTicket" type="button" value="Отменить">
	</div>
<?= form_close(); ?>