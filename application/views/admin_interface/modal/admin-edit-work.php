<?=form_open($this->uri->uri_string(),array('class'=>'form-horizontal')); ?>
	<input type="hidden" class="idWork" value="" name="wid" />
	<div id="editWork" class="modal hide fade dmodal" style="top: 40%;">
		<div class="modal-header">
			<a class="close" data-dismiss="modal">×</a>
			<h3>Редактирование работы</h3>
		</div>
		<div class="modal-body" style="min-height: 350px;">
			<fieldset>
				<div class="control-group">
					<label for="typework" class="control-label">Тип работы</label>
					<div class="controls">
						<select id="TypesWork" name="typework" class="input-xlarge">
						<?php for($i=0;$i<count($typeswork);$i++):?>
							<option value="<?=$typeswork[$i]['id'];?>"><?=$typeswork[$i]['title'];?></option>
						<?php endfor;?>
						</select>
					</div>
				</div>
				<div class="control-group">
					<label for="market" class="control-label">Биржа</label>
					<div class="controls">
						<select id="Market" name="market" class="input-xlarge">
						<?php for($i=0;$i<count($markets);$i++):?>
							<option value="<?=$markets[$i]['id'];?>"><?=$markets[$i]['title'];?></option>
						<?php endfor;?>
						</select>
					</div>
				</div>
				<div class="control-group">
					<label for="mkprice" class="control-label">Цена на бирже:</label>
					<div class="controls">
						<div class="input-prepend input-append">
							<span class="add-on">руб</span><input type="text" class="input-small digital inpval" id="mkprice" placeholder="0" name="mkprice"><span class="add-on">.00</span>
						</div>
					</div>
				</div>
				<div class="control-group">
					<label for="ulrlink" class="control-label">URL-адрес:</label>
					<div class="controls">
						<input type="text" id="UlrLink" class="span4 inpval" name="ulrlink" value="">
						<span class="help-inline" style="display:none;">&nbsp;</span>
						<p class="help-block">Конечный URL-адрес где можно проверить работу</p>
					</div>
				</div>
				<div class="control-group">
					<label for="countchars" class="control-label">Символов:</label>
					<div class="controls">
						<div class="input-prepend input-append">
							<span class="add-on">шт</span><input type="text" class="input-small digital inpval" id="CountChars" placeholder="0" name="countchars">
						</div>
						<span class="help-inline" style="display:none;">&nbsp;</span>
						<p class="help-block">Количество символов в статье </p>
					</div>
				</div>
				<div class="control-group" style="margin-bottom: 5px;">
					<div class="controls">
						<div style="margin-left: 30px;display: inline-block;">
							<span class="help-block">Для вебмастера</span>
						</div>
						<div style="margin-left: 55px;display: inline-block;">
							<span class="help-block">Для менеджера</span>
						</div>
					</div>
				</div>
				<div class="control-group" style="margin-bottom: 5px;">
					<label for="notice" class="control-label">Стоимость: </label>
					<div class="controls">
						<div class="input-prepend input-append">
							<span class="add-on">руб</span><input type="text" id="PriceWM" class="input-small inpval digital" placeholder="0" name="wprice"><span class="add-on">.00</span>
						</div>
						<div class="input-prepend input-append">
							<span class="add-on">руб</span><input type="text" id="PriceM" class="input-small inpval digital" placeholder="0" name="mprice"><span class="add-on">.00</span>
						</div>
					</div>
				</div>
			</fieldset>
		</div>
		<div class="modal-footer">
			<button class="btn" data-dismiss="modal">Отменить</button>
			<button class="btn btn-success" type="submit" id="send" name="submit" value="send">Сохранить</button>
		</div>
	</div>
<?= form_close(); ?>