<?=form_open($this->uri->uri_string(),array('id'=>'frmaddplatform')); ?>
	<table id="panel_table" cellpadding="0" cellspacing="1" class="sel">
		<tr>
			<td width="200">url (без http://):</td>
			<td><input name="url" class="reg-form-input w700 inpval" type="text" size="80" value="<?=$platform['url']?>" placeholder="Введите URL прощадки"></td>
		</tr>
		<tr>
			<td>Тематика платормы:<br/>(сменить не возможно)</td>
			<td style="text-align:left;"><span id="tsbj" style="margin-left: 20px;"></span></td>
			<div style="display:none;">
				<?php $this->load->view("clients_interface/includes/thematically");?>
			</div>
		</tr>
		<tr>
			<td>Укажите вашу CMS:</td>
			<td><input name="cms" class="reg-form-input w700 inpval" type="text" size="80" value="<?=$platform['cms']?>" placeholder="Укажите название CMS (если используете)"></td>
		</tr>
		<tr>
			<td>URL админки:</td>
			<td><input name="adminpanel" class="reg-form-input w700 inpval" type="text" size="80" value="<?=$platform['adminpanel']?>" placeholder="Укажите URL для доступа к панели администрирования"></td>
		</tr>
		<tr>
			<td>Доступ к админке:</td>
			<td>Логин: <input name="aplogin" class="reg-form-input inpval" type="text" size="30" value="<?=$platform['aplogin']?>" placeholder="Укажите логин"> Пароль <input name="appassword" type="text" class="reg-form-input inpval" size="30" value="<?=$platform['appassword']?>" placeholder="Укажите пароль"></td>
		</tr>
		<tr>
			<td>Биржи:</td>
			<td style="text-align: left;">
				<div id="exp">
					<div list="MarketLine"></div>
				<?php for($j=0;$j<count($mymarkets);$j++):?>
					<div list="MarketLine">
						<label class="label-input left w205">Название биржи</label>
						<label class="label-input left w275">Логин</label>
						<label class="label-input left w85">Пароль</label>
						<div class="clear"></div>
						<select class="reg-form-input w195" name="markets[]">
						<?php for($i=0;$i<count($markets);$i++):?>
							<option value="<?=$markets[$i]['id'];?>" <?php if($markets[$i]['id'] == $mymarkets[$j]['market']) echo 'selected="selected"';?>><?=$markets[$i]['title'];?></option>
						<?php endfor; ?>
						</select>
						<input class="reg-form-input w250 jobs" name="markets[]" type="text" maxlength="100" value="<?=$mymarkets[$j]['login'];?>"/>
						<input class="reg-form-input w250 jobs" name="markets[]" type="text" maxlength="100" value="<?=$mymarkets[$j]['password'];?>"/>
						<div class="clear"></div>
					</div>
				<?php endfor;?>
				</div>
				<div class="clear"></div>
				<input class="goog-button mt7 mb10" style="height: 30px;" id="btnAddMarketLine" type="button" value="Добавить аккаунт"/>
				<input class="goog-button mt7 mb10" style="height: 30px;" id="btnDelMarketLine" type="button" value="Удалить аккаунт"/>
			</td>
		</tr>
		<tr>
			<td>Объем знаков: </td>
			<td>
				<select name="amount" id="amount">
					<option value="1" >от 1000-1500 – 23 руб.</option>
					<option value="2" >от 1500-2000 – 30 руб.</option>
				</select>
			</td>
		</tr>
		<tr>
			<td>Обзоры:</td>
			<td>
				<select name="reviews" id="reviews">
					<option value="1">да</option>
					<option value="0">нет</option>
				</select>
			</td>
		</tr>
		<tr>
			<td>Тематичность:</td>
			<td>
				<select name="thematically" id="thematically">
					<option value="0">нет</option>
					<option value="1">да</option>
				</select>
			</td>
		</tr>
		<tr>
			<td width="200">Размещать задания которые противоречат законам РФ <nobr>(порно, казино и т.п)</nobr>: </td>
			<td>
				<select name="illegal" id="illegal">
					<option value="0">Нет, не размещать</option>
					<option value="1">Да, размещать</option>
				</select>
			</td>
		</tr>
		<tr>
				<td width="200">Критерии к публикации фотографий к контенту (если поле не заполнено – фотографии будут отсутствовать). Укажите размер фотографий, напишите желательно о всех ньансах при публикации фото. Если cms обычная, можете просто написать – стандарт.:</td>
				<td><textarea class="redactor" name="criteria" cols="79" rows="12"><?=$platform['criteria']?></textarea></td>
		</tr>
		<tr>
				<td width="200">Ваши пожелания по работе с площадкой (опциональное поле, не обязательное к заполнению)</td>
				<td><textarea class="redactor" name="requests" cols="79" rows="12"><?=$platform['requests']?></textarea></td>
		</tr>
		<tr>
				<td>&nbsp;</td>
				<td><input name="submit" id="addMarket" style="height: 40px; cursor:pointer;" type="submit" value="Сохранить"></td>
		</tr>
	</table>
<?= form_close(); ?>