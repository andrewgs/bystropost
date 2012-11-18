<?=form_open($this->uri->uri_string(),array('class'=>'form-horizontal')); ?>
	<div id="addCode" class="modal hide fade mkmodal">
		<div class="modal-header">
			<a class="close" data-dismiss="modal">×</a>
			<h3>Добавление промокода</h3>
		</div>
		<div class="modal-body">
			<fieldset>
				<div class="control-group">
					<label for="code" class="control-label">Код: </label>
					<div class="controls">
						<input type="text" class="span3 acinput" name="code" value="">
						<span class="help-inline" style="display:none;">&nbsp;</span>
					</div>
				</div>
				<div class="control-group">
					<label for="price" class="control-label">Цена: </label>
					<div class="controls">
						<input type="text" class="span3 acinput" name="price" value="">
						<span class="help-inline" style="display:none;">&nbsp;</span>
					</div>
				</div>
				<div class="control-group">
					<label for="count" class="control-label">Количество: </label>
					<div class="controls">
						<input type="text" class="span3 acinput" name="count" value="">
						<span class="help-inline" style="display:none;">&nbsp;</span>
					</div>
				</div>
				<div class="control-group">
					<label for="birzid" class="control-label">Биржи: </label>
					<div class="controls">
						<input type="text" class="span3 acinput" name="birzid" value="">
						<span class="help-inline" style="display:none;">&nbsp;</span>
					</div>
				</div>
				<div class="control-group">
					<label for="datefrom" class="control-label">Дата с: </label>
					<div class="controls">
						<input type="text" class="input-small acinput calendar" readonly="readonly" name="datefrom" value="">
						<span class="help-inline" style="display:none;">&nbsp;</span>
					</div>
				</div>
				<div class="control-group">
					<label for="dateto" class="control-label">Дата по: </label>
					<div class="controls">
						<input type="text" class="input-small acinput calendar" readonly="readonly" name="dateto" size="30">
						<span class="help-inline" style="display:none;">&nbsp;</span>
					</div>
				</div>
			</fieldset>
		</div>
		<div class="modal-footer">
			<button class="btn" data-dismiss="modal">Отменить</button>
			<button class="btn btn-success" type="submit" id="acsend" name="acsubmit" value="send">Добавить</button>
		</div>
	</div>
<?= form_close(); ?>