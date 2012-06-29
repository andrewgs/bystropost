<?=form_open($this->uri->uri_string(),array('id'=>'frmaddplatform')); ?>
	<table id="panel_table" cellpadding="0" cellspacing="1" class="sel">
		<tr>
			<td width="200">url (без http://):</td>
			<td><input name="url" class="reg-form-input w700 inpval" type="text" size="80" value="" placeholder="Введите URL прощадки"></td>
		</tr>
	</table>
	<?php $this->load->view("clients_interface/includes/thematically");?>
	
	<table id="panel_table" cellpadding="0" cellspacing="1" class="sel">
		<tr>
			<td>Укажите вашу CMS:</td>
			<td><input name="cms" class="reg-form-input w700 inpval" type="text" size="80" value="" placeholder="Укажите название CMS (если используете)"></td>
		</tr>
		<tr>
			<td>URL админки:</td>
			<td><input name="adminpanel" class="reg-form-input w700 inpval" type="text" size="80" value="" placeholder="Укажите URL для доступа к панели администрирования"></td>
		</tr>
		<tr>
			<td>Доступ к админке:</td>
			<td>Логин: <input name="aplogin" class="reg-form-input inpval" type="text" size="30" value="" placeholder="Укажите логин"> Пароль <input name="appassword" type="text" class="reg-form-input inpval" size="30" value="" placeholder="Укажите пароль"></td>
		</tr>
		<tr>
			<td>Биржи:</td>
			<td style="text-align: left;">
				<div id="exp">
					<div list="MarketLine">
						<?php $this->load->view('clients_interface/includes/markets-profile'); ?>
					</div>
				</div>
				<div class="clear"></div>
				<input class="goog-button mt7 mb10" style="height: 30px;" id="btnAddMarketLine" type="button" value="Добавить аккаунт"/>
				<input class="goog-button mt7 mb10" style="height: 30px;" id="btnDelMarketLine" type="button" value="Удалить аккаунт"/>
			</td>
		</tr>
		<tr>
			<td>Объем знаков: </td>
			<td>
				<select name="amount">
					<option value="1" >от 1000-1500 – 23 руб.</option>
					<option value="2" >от 1500-2000 – 30 руб.</option>
				</select>
			</td>
		</tr>
		<tr>
			<td>Обзоры:</td>
			<td>
				<select name="reviews">
					<option value="1">да</option>
					<option value="0">нет</option>
				</select>
			</td>
		</tr>
		<tr>
			<td>Тематичность:</td>
			<td>
				<select name="thematically">
					<option value="0">нет</option>
					<option value="1">да</option>
				</select>
			</td>
		</tr>
		<tr>
			<td width="200">Размещать задания которые противоречат законам РФ <nobr>(порно, казино и т.п)</nobr>: </td>
			<td>
				<select name="illegal">
					<option value="0">Нет, не размещать</option>
					<option value="1">Да, размещать</option>
				</select>
			</td>
		</tr>
		<tr>
				<td width="200">Критерии к публикации фотографий к контенту (если поле не заполнено – фотографии будут отсутствовать). Укажите размер фотографий, напишите желательно о всех ньансах при публикации фото. Если cms обычная, можете просто написать – стандарт.:</td>
				<td><textarea class="redactor" name="criteria" cols="79" rows="12"></textarea></td>
		</tr>
		<tr>
				<td width="200">Ваши пожелания по работе с площадкой (опциональное поле, не обязательное к заполнению)</td>
				<td><textarea class="redactor" name="requests" cols="79" rows="12"></textarea></td>
		</tr>
		<tr>
				<td>&nbsp;</td>
				<td><input name="submit" id="addMarket" style="height: 40px; cursor:pointer;" type="submit" value="Добавить площадку"></td>
		</tr>
	</table>
<?= form_close(); ?>