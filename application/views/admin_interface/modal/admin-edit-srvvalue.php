<?=form_open($this->uri->uri_string(),array('class'=>'form-horizontal')); ?>
	<input type="hidden" class="idSrvValue" value="" name="svid" />
	<div id="editSrvValue" class="modal hide fade mkmodal">
		<div class="modal-header">
			<a class="close" data-dismiss="modal">×</a>
			<h3>Редактирование значения услуги</h3>
		</div>
		<div class="modal-body">
			<fieldset>
				<div class="control-group">
					<label for="title" class="control-label">Название: </label>
					<div class="controls">
						<input type="text" class="input-xlarge esvinput" id="SVTitle" name="title" value="">
						<span class="help-inline" style="display:none;">&nbsp;</span>
					</div>
				</div>
				<div class="control-group">
					<label for="wprice" class="control-label">Цена (вебмастер): </label>
					<div class="controls">
						<div class="input-prepend input-append">
							<span class="add-on">руб</span><input type="text" class="input-small esvinput digital" id="SVwPrice" placeholder="" name="wprice"><span class="add-on">.00</span>
						</div>
						<div class="help-inline" style="display:none;">&nbsp;</div>
					</div>
				</div>
				<div class="control-group">
					<label for="mprice" class="control-label">Цена (менеджер): </label>
					<div class="controls">
						<div class="input-prepend input-append">
							<span class="add-on">руб</span><input type="text" class="input-small esvinput digital" id="SVmPrice" placeholder="" name="mprice"><span class="add-on">.00</span>
						</div>
						<div class="help-inline" style="display:none;">&nbsp;</div>
					</div>
				</div>
				<div class="control-group">
					<div class="controls">
						<label class="checkbox">
							<input type="checkbox" value="delsrvvalue" id="deleteSrvValue" name="delsrvvalue">
							Удалить значение услуги
						</label>
						<p class="help-block">(Удалять не рекомендуется)</p>
					</div>
				</div>
			</fieldset>
		</div>
		<div class="modal-footer">
			<button class="btn" data-dismiss="modal">Отменить</button>
			<button class="btn btn-success" type="submit" id="esvsend" name="esvsubmit" value="send">Сохранить</button>
		</div>
	</div>
<?= form_close(); ?>