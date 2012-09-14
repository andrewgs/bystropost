<?=form_open($this->uri->uri_string(),array('class'=>'form-horizontal')); ?>
	<input type="hidden" class="idService" value="" name="sid" />
	<div id="editService" class="modal hide fade mkmodal">
		<div class="modal-header">
			<a class="close" data-dismiss="modal">×</a>
			<h3>Редактирование услуги</h3>
		</div>
		<div class="modal-body">
			<fieldset>
				<div class="control-group">
					<label for="title" class="control-label">Название: </label>
					<div class="controls">
						<input type="text" id="esTitle" class="input-xlarge esinput" name="title" value="">
						<span class="help-inline" style="display:none;">&nbsp;</span>
					</div>
				</div>
				<div class="control-group">
					<label for="types_works" class="control-label">Типы работ: </label>
					<div class="controls">
						<input type="text" id="esTypes" class="input-xlarge esinput" name="types_works" value="">
						<p class="help-block">Например: 1,2,4,5</p>
						<span class="help-inline" style="display:none;">&nbsp;</span>
					</div>
				</div>
			</fieldset>
		</div>
		<div class="modal-footer">
			<button class="btn" data-dismiss="modal">Отменить</button>
			<button class="btn btn-success" type="submit" id="essend" name="essubmit" value="send">Сохранить</button>
		</div>
	</div>
<?= form_close(); ?>