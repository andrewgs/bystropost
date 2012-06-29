<label class="label-input left w205">Название биржи</label>
<label class="label-input left w270">Логин</label>
<label class="label-input left w85">Пароль</label>
<div class="clear"></div>
<select class="reg-form-input w195" name="exp[]">
<?php for($i=0;$i<count($markets);$i++): ?>
	<option value="<?=$markets[$i]['id'];?>"><?=$markets[$i]['title'];?></option>
<?php endfor; ?>
</select>
<input class="reg-form-input w250 jobs" name="exp[]" type="text" maxlength="100" value=""/>
<input class="reg-form-input w250 jobs" name="exp[]" type="text" maxlength="100" value=""/>

<div class="clear"></div>