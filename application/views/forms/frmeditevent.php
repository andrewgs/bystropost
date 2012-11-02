<?=form_open_multipart($this->uri->uri_string(),array('class'=>'form-horizontal')); ?>
	<legend>Форма редактирования новости</legend>
	<fieldset>
		<div class="control-group">
			<label for="title" class="control-label">Название: </label>
			<div class="controls">
				<input type="text" class="span7 eeinput" name="title" value="<?=$event['title'];?>">
				<span class="help-inline" style="display:none;">&nbsp;</span>
			</div>
		</div>
		<div class="control-group">
			<label for="announcement" class="control-label">Анонс: </label>
			<div class="controls">
				<textarea rows="3" class="span7 eeinput" name="announcement"><?=$event['announcement'];?></textarea>
				<span class="help-inline" style="display:none;">&nbsp;</span>
			</div>
		</div>
		<div class="control-group">
			<label for="content" class="control-label">Содержание: </label>
			<div class="controls">
				<textarea rows="10" class="span7 eeinput" name="text"><?=$event['text'];?></textarea>
				<span class="help-inline" style="display:none;">&nbsp;</span>
			</div>
		</div>
		<div class="control-group">
			<label for="image" class="control-label">Изображение: </label>
			<div class="controls">
				<input type="file" class="input-file" name="image" size="50">
				<span class="help-inline" style="display:none;">&nbsp;</span>
				<p class="help-block">Поддерживаются форматы: JPG,PNG,GIF</p>
			</div>
		</div>
		<div class="control-group">
			<label for="image" class="control-label">Текущее изображение: </label>
			<div class="controls">
				<img src="<?=$baseurl;?>news/viewimage/<?=$event['id'];?>" alt="" width="150"/><br/><br/>
			</div>
		</div>
		<div class="control-group">
			<label for="showitem" class="control-label">&nbsp;</label>
			<div class="controls">
				<label class="checkbox">
					<input type="checkbox" value="1" id="noimage" name="noimage" <?=($event['noimage'])? 'checked="checked"' : '';?>>
					Не показывать изображение</label>
			</div>
		</div>
	</fieldset>
	<div class="form-actions">
		<button class="btn btn-success" type="submit" id="send" name="submit" value="send">Сохранить</button>
		<button class="btn btn-inverse" id="reset" type="button">Отменить</button>
	</div>
<?= form_close(); ?>