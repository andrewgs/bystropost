<label class="label-input left w205">Название биржи</label>
<label class="label-input left w250">Логин</label>
<label class="label-input left w85">Пароль</label>
<div class="clear"></div>
<select class="reg-form-input w195 h30" name="markets[]" style="vertical-align:top;padding: 5px;">
<?php for($i=0;$i<count($markets);$i++): ?>
	<option value="<?=$markets[$i]['id'];?>"><?=$markets[$i]['title'];?></option>
<?php endfor; ?>
</select>
<input class="reg-form-input w230 jobs" name="markets[]" type="text" maxlength="100" value=""/>
<input class="reg-form-input w230 jobs" name="markets[]" type="text" maxlength="100" value=""/>

<div class="clear"></div>