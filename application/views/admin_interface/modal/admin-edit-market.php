<?=form_open_multipart($this->uri->uri_string(),array('class'=>'form-horizontal')); ?>
	<input type="hidden" class="idMarket" value="" name="mid" />
	<div id="editMarket" class="modal hide fade mkmodal">
		<div class="modal-header">
			<a class="close" data-dismiss="modal">×</a>
			<h3>Добавление биржи</h3>
		</div>
		<div class="modal-body">
			<fieldset>
				<div class="control-group">
					<label for="title" class="control-label">Название: </label>
					<div class="controls">
						<input type="text" id="emTitle" class="input-xlarge eminput" name="title" value="">
						<span class="help-inline" style="display:none;">&nbsp;</span>
					</div>
				</div>
				<div class="control-group">
					<label for="url" class="control-label">URL: </label>
					<div class="controls">
						<input type="text" id="emURL" class="input-xlarge eminput" name="url" value="">
						<span class="help-inline" style="display:none;">&nbsp;</span>
					</div>
				</div>
				<div class="control-group">
					<label for="icon" class="control-label">ICON: </label>
					<div class="controls">
						<input type="file" id="amIcon" class="input-file" name="icon" size="30">
						<span class="help-inline" style="display:none;">&nbsp;</span>
					</div>
				</div>
			</fieldset>
		</div>
		<div class="modal-footer">
			<button class="btn" data-dismiss="modal">Отменить</button>
			<button class="btn btn-success" type="submit" id="emsend" name="emsubmit" value="send">Сохранить</button>
		</div>
	</div>
<?= form_close(); ?>