<?=form_open($this->uri->uri_string(),array('class'=>'form-horizontal','id'=>'formExport')); ?>
	<fieldset>
		<div class="span2">
			<div class="control-group">
				<label for="begin" class="control-label">С:</label>
				<div class="controls">
					<input type="text" id="bdate" class="input-small calendar" name="bbegin" readonly="readonly" value="<?=date("d.m.Y",mktime(0,0,0,date("m"),date("d")-14,date("Y")));?>">
				</div>
			</div>
			<div class="control-group">
				<label for="bend" class="control-label">По:</label>
				<div class="controls">
					<input type="text" id="bend" class="input-small calendar" name="bend" readonly="readonly" value="<?=date("d.m.Y");?>">
				</div>
			</div>
		</div>
		<div class="span5">
			<div class="control-group">
				<div class="controls">
					<label class="checkbox">
						<input type="checkbox" value="1" class="chExport" id="paid" name="paid" checked="checked">
						Включить оплаченные работы
					</label>
				</div>
			</div>
			<div class="control-group">
				<div class="controls">
					<label class="checkbox">
						<input type="checkbox" value="1" class="chExport" id="notpaid" name="notpaid" checked="checked">
						Включить не оплаченные работы
					</label>
				</div>
			</div>
		</div>
		<div class="clear"></div>
		<div class="form-actions">
			<button class="btn btn-primary" type="submit" name="subexport" id="ExportCSV" value="subexport"><i class="icon-download-alt icon-white"></i> Экспортировать задания</button>
		</div>
	</fieldset>
<?= form_close(); ?>