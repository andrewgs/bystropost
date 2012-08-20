<p style="margin:0px;" id="cmsselect">
	<select name="cms" id="cms" class="reg-form-input w400 h35">
	<?php for($i=0;$i<count($cms);$i++):?>
		<option value="<?=$cms[$i]['id'];?>" ><?=$cms[$i]['title'];?></option>
	<?php endfor;?>
	</select>
</p>