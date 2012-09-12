<?=form_open($this->uri->uri_string(),array('class'=>'form-horizontal')); ?>
	<div id="addService" class="modal hide fade mkmodal"  style="width:600px;">
		<div class="modal-header">
			<a class="close" data-dismiss="modal">×</a>
			<h3>Подключение услуги</h3>
		</div>
		<div class="modal-body">
			<fieldset>
				<div class="control-group">
					<label for="service" class="control-label">Список услуг:</label>
					<div class="controls">
						<select id="ServicesList" name="service" class="span5">
						<?php for($i=0;$i<count($services);$i++):?>
							<option value="<?=$services[$i]['id'];?>" <?=($services[$i]['attached'])? 'disabled="disabled"': '' ;?>><?=$services[$i]['title'];?></option>
						<?php endfor;?>
						</select>
					</div>
				</div>
			</fieldset>
		</div>
		<div class="modal-footer">
		<button class="btn" data-dismiss="modal">Отменить</button>
		<button class="btn btn-success" type="submit" id="send" name="submit" value="send">Добавить</button>
		</div>
	</div>
<?= form_close(); ?>