<?=form_open_multipart($this->uri->uri_string(),array('class'=>'form-horizontal')); ?>
	<div id="addRating" class="modal hide fade mkmodal">
		<div class="modal-header">
			<a class="close" data-dismiss="modal">×</a>
			<h3>Добавление отзыва</h3>
		</div>
		<div class="modal-body">
			<fieldset>
				<div class="control-group">
					<label for="title" class="control-label">Имя: </label>
					<div class="controls">
						<input type="text" class="input-xlarge arinput" name="title" value="">
						<span class="help-inline" style="display:none;">&nbsp;</span>
					</div>
				</div>
				<div class="control-group">
					<label for="text" class="control-label">Текст: </label>
					<div class="controls">
						<textarea rows="10" style="width:350px;" class="input-xlarge arinput" name="text"></textarea>
						<span class="help-inline" style="display:none;">&nbsp;</span>
					</div>
				</div>
				<div class="control-group">
					<label for="avatar" class="control-label">Аватар: </label>
					<div class="controls">
						<input type="file" class="input-xlarge" name="avatar" size="43">
						<span class="help-inline" style="display:none;">&nbsp;</span>
						<p class="help-block">Поддерживаются форматы: JPG,PNG,GIF</p>
					</div>
				</div>
			</fieldset>
		</div>
		<div class="modal-footer">
			<button class="btn" data-dismiss="modal">Отменить</button>
			<button class="btn btn-success" type="submit" id="arsend" name="arsubmit" value="send">Добавить</button>
		</div>
	</div>
<?= form_close(); ?>