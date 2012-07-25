<?=form_open($this->uri->uri_string(),array('class'=>'form-horizontal')); ?>
	<div id="addService" class="modal hide fade mkmodal">
		<div class="modal-header">
			<a class="close" data-dismiss="modal">×</a>
			<h3>Добавление услуги</h3>
		</div>
		<div class="modal-body">
			<fieldset>
				<div class="control-group">
					<label for="title" class="control-label">Название: </label>
					<div class="controls">
						<input type="text" class="input-xlarge asinput" name="title" value="">
						<span class="help-inline" style="display:none;">&nbsp;</span>
					</div>
				</div>
				<div class="control-group">
					<label for="price" class="control-label">Цена: </label>
					<div class="controls">
						<div class="input-prepend input-append">
							<span class="add-on">руб</span><input type="text" class="input-small asinput digital" placeholder="0" name="price"><span class="add-on">.00</span>
						</div>
						<div class="help-inline" style="display:none;">&nbsp;</div>
					</div>
				</div>
			</fieldset>
		</div>
		<div class="modal-footer">
			<button class="btn" data-dismiss="modal">Отменить</button>
			<button class="btn btn-success" type="submit" id="assend" name="assubmit" value="send">Добавить</button>
		</div>
	</div>
<?= form_close(); ?>