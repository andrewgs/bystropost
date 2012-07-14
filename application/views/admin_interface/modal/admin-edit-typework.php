<?=form_open($this->uri->uri_string(),array('class'=>'form-horizontal')); ?>
	<input type="hidden" class="idTypeWork" value="" name="tpid" />
	<div id="editTypeWork" class="modal hide fade mkmodal">
		<div class="modal-header">
			<a class="close" data-dismiss="modal">×</a>
			<h3>Редактирование типа работ</h3>
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
					<label for="wprice" class="control-label">Цена (вебмастер): </label>
					<div class="controls">
						<input type="text" id="ewPrice" class="input-small eminput digital" name="wprice" value="">
						<span class="help-inline" style="display:none;">&nbsp;</span>
					</div>
				</div>
				<div class="control-group">
					<label for="mprice" class="control-label">Цена (менеджер): </label>
					<div class="controls">
						<input type="text" id="emPrice" class="input-small eminput digital" name="mprice" value="">
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