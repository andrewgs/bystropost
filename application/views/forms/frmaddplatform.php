<?=form_open($this->uri->uri_string(),array('id'=>'frmaddplatform')); ?>
	<input type="hidden" name="imgstatus" value="0"/>
	<table id="panel_table" cellpadding="0" cellspacing="1" class="sel">
		<tr>
			<td width="200">URL площадки:</td>
			<td><input name="url" class="reg-form-input w660 inpval" id="domen" type="text" size="80" value="" placeholder="Введите URL прощадки"></td>
		</tr>
		<tr>
			<td width="200">Выберите тематику:</td>
			<td><?php $this->load->view("clients_interface/includes/thematically");?></td>
		</tr>
		<tr>
			<td>Укажите вашу CMS:</td>
			<td><?php $this->load->view("clients_interface/includes/cms");?></td>
		</tr>
		<tr>
			<td>URL админки:</td>
			<td><input name="adminpanel" class="reg-form-input w660 inpval" type="text" size="80" value="" placeholder="Укажите URL для доступа к панели администрирования"></td>
		</tr>
		<tr>
			<td>Доступ к админке:</td>
			<td style="text-align: left;">
				<span style="margin-left:10px;">Логин:</span><input name="aplogin" class="reg-form-input w230 inpval" type="text" value="" placeholder="Укажите логин" style="margin-left:10px;">
				<span style="margin-left:15px;">Пароль:</span><input name="appassword" type="text" class="reg-form-input w230 inpval" value="" placeholder="Укажите пароль" style="margin-left:10px;">
			</td>
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
			<td>Обзоры:</td>
			<td>
				<select name="reviews" class="reg-form-input w195 h35">
					<option value="1">да</option>
					<option value="0">нет</option>
				</select>
			</td>
		</tr>
		<tr>
			<td>Тематичность:</td>
			<td>
				<select name="thematically" class="reg-form-input w195 h35">
					<option value="0">нет</option>
					<option value="1">да</option>
				</select>
			</td>
		</tr>
		<tr>
			<td width="200">Размещать задания которые противоречат законам РФ <nobr>(порно, казино и т.п)</nobr>: </td>
			<td>
				<select name="illegal" class="reg-form-input w195 h35">
					<option value="0">Нет, не размещать</option>
					<option value="1">Да, размещать</option>
				</select>
			</td>
		</tr>
		<tr>
				<td width="200">Критерии к публикации фотографий к контенту (если поля не заполнены – фотографии будут отсутствовать)</td>
				<td>
					<span style="margin-left:10px;">Ширина изображения:</span><input name="imgwidth" class="reg-form-input w50 digital" type="text" value="" style="margin-left:10px;"><br/>
					<span style="margin-left:15px;">Высота изображения:</span><input name="imgheight" type="text" class="reg-form-input w50 digital" value="" style="margin-left:10px;"><br/>
					<span style="margin-left:15px;">Позиция изображения:</span>
					<select name="imgpos" class="reg-form-input w195 h35">
						<option value="left">Слева</option>
						<option value="center">По центру</option>
						<option value="right">Справа</option>
					</select>
				</td>
		</tr>
		<tr>
				<td width="200">Ваши пожелания по работе с площадкой (опциональное поле, не обязательное к заполнению)</td>
				<td><textarea class="redactor" name="requests" cols="79" rows="12"></textarea></td>
		</tr>
		<tr>
				<td>&nbsp;</td>
				<td>
					<input name="submit" class="btn btn-primary" id="addMarket" style="height: 40px; cursor:pointer;" type="submit" value="Добавить площадку">
					<input class="btn btn-inverse" id="reset" style="height: 40px; cursor:pointer;" type="button" value="Отменить">
				</td>
		</tr>
	</table>
<?= form_close(); ?>