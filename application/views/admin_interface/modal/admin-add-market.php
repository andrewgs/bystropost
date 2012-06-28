<?=form_open_multipart($this->uri->uri_string(),array('class'=>'form-horizontal')); ?>
	<div id="addMarket" class="modal hide fade mkmodal">
		<div class="modal-header">
			<a class="close" data-dismiss="modal">×</a>
			<h3>Добавление биржи</h3>
		</div>
		<div class="modal-body">
			<fieldset>
				<div class="control-group">
					<label for="title" class="control-label">Название: </label>
					<div class="controls">
						<input type="text" class="input-xlarge aminput" name="title" value="">
						<span class="help-inline" style="display:none;">&nbsp;</span>
					</div>
				</div>
				<div class="control-group">
					<label for="url" class="control-label">URL: </label>
					<div class="controls">
						<input type="text"  class="input-xlarge aminput" name="url" value="">
						<span class="help-inline" style="display:none;">&nbsp;</span>
					</div>
				</div>
				<div class="control-group">
					<label for="icon" class="control-label">ICON: </label>
					<div class="controls">
						<input type="file" id="amIcon" class="input-file aminput" name="icon" size="30">
						<span class="help-inline" style="display:none;">&nbsp;</span>
						<p class="help-block">Поддерживаются форматы: JPG,PNG,GIF</p>
					</div>
				</div>
			</fieldset>
		</div>
		<div class="modal-footer">
			<button class="btn" data-dismiss="modal">Отменить</button>
			<button class="btn btn-success" type="submit" id="amsend" name="amsubmit" value="send">Добавить</button>
		</div>
	</div>
<?= form_close(); ?>