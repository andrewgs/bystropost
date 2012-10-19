<?=form_open($this->uri->uri_string(),array('class'=>'form-horizontal')); ?>
	<input type="hidden" class="idPlatform" value="" name="pid" />
	<div id="attrPlatform" class="modal hide fade dmodal">
		<div class="modal-header">
			<a class="close" data-dismiss="modal">×</a>
			<h3>Свойства площадки</h3>
		</div>
		<div class="modal-body" style="max-height: 500px;">
			<fieldset>
				<div class="control-group">
					<label class="control-label" for="status"></label>
					<div class="controls">
						<label class="checkbox">
						<input id="statusPlatform" name="status" type="checkbox" value="1">
							Активная площадка
						</label>
					</div>
				</div>
			</fieldset>
		</div>
		<div class="modal-footer">
			<button class="btn" data-dismiss="modal">Отменить</button>
			<button class="btn btn-success" type="submit" id="epsend" name="epsubmit" value="send">Сохранить</button>
		</div>
	</div>
<?= form_close(); ?>