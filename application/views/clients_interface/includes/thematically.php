<p style="margin:0px;" id="mgselect">
	<select name="subject" id="subject" class="reg-form-input w400 h35">
	<?php for($i=0;$i<count($thematic);$i++):?>
		<option value="<?=$thematic[$i]['id'];?>" ><?=$thematic[$i]['title'];?></option>
	<?php endfor;?>
	</select>
</p>