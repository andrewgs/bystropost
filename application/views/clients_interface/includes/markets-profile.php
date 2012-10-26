<label class="label-input left w160">Название биржи</label>
<label class="label-input left w160">Логин</label>
<label class="label-input left w165">Пароль</label>
<label class="label-input left w160">Раздел для публикации</label>
<div class="clear"></div>
<select class="input-medium h30" name="markets[]" style="vertical-align:top;padding: 5px;">
<?php for($i=0;$i<count($markets);$i++): ?>
	<option value="<?=$markets[$i]['id'];?>"><?=$markets[$i]['title'];?></option>
<?php endfor; ?>
</select>
<input class="input-medium jobs" name="markets[]" type="text" maxlength="100" value=""/>
<input class="input-medium jobs" name="markets[]" type="text" maxlength="100" value=""/>
<input class="input-large jobs" name="markets[]" type="text" maxlength="100" value="" placeholder="Введите раздел публикации"/>

<div class="clear"></div>