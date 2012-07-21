<?=form_open($this->uri->uri_string(),array('class'=>'form-horizontal')); ?>
	<fieldset>
		<legend>Отчет о выполненной работе<div style="float:right;">Зароботок: <span id="gonorar">0</span>.00 руб.</div></legend>
		<div class="control-group">
			<label for="typework" class="control-label">Тип работы</label>
			<div class="controls">
				<select id="TypesWork" name="typework" class="input-xlarge">
				<?php for($i=0;$i<count($typeswork);$i++):?>
					<option value="<?=$typeswork[$i]['id'];?>" data-gonorar="<?=$typeswork[$i]['mprice'];?>"><?=$typeswork[$i]['title'];?></option>
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
					<span class="add-on">руб</span><input type="text" class="input-small digital inpval" placeholder="0" name="mkprice"><span class="add-on">.00</span>
				</div>
			</div>
		</div>
		<div class="control-group">
			<label for="ulrlink" class="control-label">URL-адрес:</label>
			<div class="controls">
				<input type="text" id="UlrLink" class="span7 inpval" name="ulrlink" value="">
				<span class="help-inline" style="display:none;">&nbsp;</span>
				<p class="help-block">Конечный URL-адрес где можно проверить работу</p>
			</div>
		</div>
		<div class="control-group">
			<label for="countchars" class="control-label">Символов:</label>
			<div class="controls">
				<div class="input-prepend input-append">
					<span class="add-on">шт</span><input type="text" class="input-small digital inpval" placeholder="0" name="countchars">
				</div>
				<span class="help-inline" style="display:none;">&nbsp;</span>
				<p class="help-block">Количество символов в статье </p>
			</div>
		</div>
		<div class="form-actions">
			<label class="checkbox"><input type="checkbox" value="valid" id="ValidWork">&nbsp;Данные верны (Изменить задание не возможно)</label>
			<button class="btn btn-primary disabled" type="submit" name="submit" id="send" value="submit">Сохранить</button>
			<input class="btn btn-inverse" id="reset" style="height: 28px; cursor:pointer;" type="button" value="Отменить">
		</div>
	</fieldset>
<?= form_close(); ?>