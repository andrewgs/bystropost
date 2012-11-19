<?=form_open($this->uri->uri_string(),array('class'=>'form-horizontal')); ?>
	<input type="hidden" class="idCode" value="" name="cid" />
	<div id="editCode" class="modal hide fade mkmodal">
		<div class="modal-header">
			<a class="close" data-dismiss="modal">×</a>
			<h3>Редактирование промокода</h3>
		</div>
		<div class="modal-body">
			<fieldset>
				<div class="control-group">
					<label for="code" class="control-label">Код: </label>
					<div class="controls">
						<input type="text" id="ecCode" class="span3 eсinput" name="code" value="">
						<span class="help-inline" style="display:none;">&nbsp;</span>
					</div>
				</div>
				<div class="control-group">
					<label for="price" class="control-label">Цена: </label>
					<div class="controls">
						<input type="text" id="ecPrice" class="span3 eacinput" name="price" value="">
						<span class="help-inline" style="display:none;">&nbsp;</span>
					</div>
				</div>
				<div class="control-group">
					<label for="count" class="control-label">Количество: </label>
					<div class="controls">
						<input type="text" id="ecCount" class="span3 ecinput" name="count" value="">
						<span class="help-inline" style="display:none;">&nbsp;</span>
					</div>
				</div>
				<div class="control-group">
					<label for="birzid" class="control-label">Биржи: </label>
					<div class="controls">
						<select multiple="multiple" id="SMarkets" name="birzid[]" class="span3 acinput" size="5">
						<?php for($i=0;$i<count($markets);$i++):?>
							<option value="<?=$markets[$i]['id'];?>"><?=$markets[$i]['title'];?></option>
						<?php endfor;?>
						</select>
					</div>
				</div>
				<div class="control-group">
					<label for="datefrom" class="control-label">Дата с: </label>
					<div class="controls">
						<input type="text" id="ecDateFrom" class="input-small eсinput calendar" readonly="readonly" name="datefrom" value="">
						<span class="help-inline" style="display:none;">&nbsp;</span>
					</div>
				</div>
				<div class="control-group">
					<label for="dateto" class="control-label">Дата по: </label>
					<div class="controls">
						<input type="text" id="ecDateTo" class="input-small eсinput calendar" readonly="readonly" name="dateto" size="30">
						<span class="help-inline" style="display:none;">&nbsp;</span>
					</div>
				</div>
			</fieldset>
		</div>
		<div class="modal-footer">
			<button class="btn" data-dismiss="modal">Отменить</button>
			<button class="btn btn-success" type="submit" id="ecsend" name="ecsubmit" value="send">Сохранить</button>
		</div>
	</div>
<?= form_close(); ?>