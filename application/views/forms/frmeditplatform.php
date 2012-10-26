<?=form_open($this->uri->uri_string(),array('id'=>'frmaddplatform')); ?>
	<input type="hidden" name="imgstatus" value="<?=$platform['imgstatus'];?>"/>
	<input type="hidden" name="url" value="<?=$platform['url'];?>"/>
	<table id="panel_table" cellpadding="0" cellspacing="1" class="sel">
		<tr>
			<td width="200">URL площадки:</td>
			<td><input name="durl" class="reg-form-input w660 inpval disabled" disabled="disabled" type="text" size="80" value="<?=$platform['url'];?>" placeholder="Введите URL прощадки"></td>
		</tr>
		<tr>
			<td>Тематика площадки:</td>
			<td><?php $this->load->view("clients_interface/includes/thematically");?></td>
		</tr>
		<tr>
			<td>Уточнение тематики:</td>
			<td><input name="tematcustom" class="reg-form-input w230" id="tematcustom" maxlength="20" type="text" size="80" value="<?=$platform['tematcustom'];?>"></td>
		</tr>
		<tr>
			<td>Укажите вашу CMS:</td>
			<td><?php $this->load->view("clients_interface/includes/cms");?></td>
		</tr>
		<tr>
			<td>URL админки:</td>
			<td><input name="adminpanel" class="reg-form-input w660 inpval" type="text" size="80" value="<?=$platform['adminpanel'];?>" placeholder="Укажите URL для доступа к панели администрирования"></td>
		</tr>
		<tr>
			<td>Доступ к админке:</td>
			<td style="text-align: left;">
				<span style="margin-left:10px;">Логин:</span><input name="aplogin" class="reg-form-input w230 inpval" type="text" value="<?=$platform['aplogin'];?>" placeholder="Укажите логин" style="margin-left:10px;">
				<span style="margin-left:15px;">Пароль:</span><input name="appassword" type="text" class="reg-form-input w230 inpval" value="<?=$platform['appassword'];?>" placeholder="Укажите пароль" style="margin-left:10px;">
			</td>
		</tr>
		<tr>
			<?php if($userinfo['remote'] || $platform['manager'] == 2):
				$disabled = TRUE;
			else:
				$disabled = FALSE;
			endif;?>
			<td>Биржи:</td>
			<td style="text-align: left;">
				<div id="exp">
					<div list="MarketLine"></div>
				<?php for($j=0;$j<count($mymarkets);$j++):?>
					<div list="MarketLine">
						<label class="label-input left w160">Название биржи</label>
						<label class="label-input left w160">Логин</label>
						<label class="label-input left w165">Пароль</label>
						<label class="label-input left w160">Раздел для публикации</label>
						<div class="clear"></div>
					<?php if($platform['remoteid']):?>
						<input name="markets[]" type="hidden" maxlength="100" value="<?=$mymarkets[$j]['market'];?>" <?=($disabled)?'readonly="readonly"':'';?>/>
						<select class="input-medium h30" name="market" style="vertical-align:top;padding: 5px;" <?=($disabled)?'disabled="disabled"':'';?>>
					<?php else:?>
						<select class="input-medium h30" name="markets[]" style="vertical-align:top;padding: 5px;" <?=($disabled)?'disabled="disabled"':'';?>>
					<?php endif;?>
						<?php for($i=0;$i<count($markets);$i++):?>
							<option value="<?=$markets[$i]['id'];?>" <?php if($markets[$i]['id'] == $mymarkets[$j]['market']) echo 'selected="selected"';?>><?=$markets[$i]['title'];?></option>
						<?php endfor; ?>
						</select>
						<input class="input-medium jobs" name="markets[]" type="text" maxlength="100" value="<?=$mymarkets[$j]['login'];?>" <?=($disabled)?'readonly="readonly"':'';?>/>
						<input class="input-medium jobs" name="markets[]" type="text" maxlength="100" value="<?=$mymarkets[$j]['password'];?>" <?=($disabled)?'readonly="readonly"':'';?>/>
						<input class="input-large jobs" name="markets[]" type="text" maxlength="100" value="<?=$mymarkets[$j]['publication'];?>" placeholder="Введите раздел публикации"/>
						<div class="clear"></div>
					</div>
				<?php endfor;?>
				</div>
				<div class="clear"></div>
			<?php if(!$disabled):?>
				<input class="goog-button mt7 mb10" style="height: 30px;" id="btnAddMarketLine" type="button" value="Добавить аккаунт"/>
				<input class="goog-button mt7 mb10" style="height: 30px;" id="btnDelMarketLine" type="button" value="Удалить аккаунт"/>
			<?php endif;?>
			<?php if(!count($mymarkets)):?>
				<div class="alert alert-info" style="margin:0;">
					Отсутствуют биржи. Работа с площадкой прекращена.<br/>Если информация не верна, обратитесь к администрации сайта.
				</div>
			<?php endif;?>
			</td>
		</tr>
	<?php for($i=0;$i<count($services);$i++):?>
		<tr>
			<td><?=$services[$i]['tservice']?>:</td>
			<td>
				<select name="data<?=$services[$i]['id']?>" class="reg-form-input w205 h35 disabled" disabled="disabled">
					<option value="<?=$services[$i]['valuesrv']?>" ><?=$services[$i]['tsrvval']?></option>
				</select>
			</td>
		</tr>
	<?php endfor;?>
		<tr>
			<td>Обзоры:</td>
			<td>
				<select name="reviews" id="reviews" class="reg-form-input w195 h35">
					<option value="1">да</option>
					<option value="0">нет</option>
				</select>
			</td>
		</tr>
		<tr>
			<td>Тематичность:</td>
			<td>
				<select name="thematically" id="thematically" class="reg-form-input w195 h35">
					<option value="0">нет</option>
					<option value="1">да</option>
				</select>
			</td>
		</tr>
		<tr>
			<td width="200">Размещать задания которые противоречат законам РФ <nobr>(порно, казино и т.п)</nobr>: </td>
			<td>
				<select name="illegal" id="illegal" class="reg-form-input w195 h35">
					<option value="0">Нет, не размещать</option>
					<option value="1">Да, размещать</option>
				</select>
			</td>
		</tr>
		<tr>
			<td width="200">Критерии к публикации фотографий к контенту <strong>(если все поля не заполнены – фотографии будут отсутствовать)</strong></td>
			<td>
				<span style="margin-left:10px;"><strong>Ширина изображения:</strong></span><input name="imgwidth" class="reg-form-input w50 digital" type="text" value="<?=$platform['imgwidth']?>" style="margin-left:10px; color:#00ca00"><br/>
				<span style="margin-left:15px;"><strong>Высота изображения:</strong></span><input name="imgheight" type="text" class="reg-form-input w50 digital" value="<?=$platform['imgheight']?>" style="margin-left:10px; color:#00ca00"><br/>
				<span class="help-block">[Например: 250х200]</span>
				<span style="margin-left:15px;">Позиция изображения:</span>
				<select name="imgpos" id="imgpos" class="reg-form-input w195 h35">
					<option value="left">Слева</option>
					<option value="center">По центру</option>
					<option value="right">Справа</option>
				</select>
			</td>
		</tr>
		<tr>
			<td width="200">Ваши пожелания по работе с площадкой (опциональное поле, не обязательное к заполнению)</td>
			<td><textarea class="redactor" name="requests" cols="79" rows="12"><?=$platform['requests'];?></textarea></td>
		</tr>
		<tr>
			<td>&nbsp;</td>
			<td>
				<input name="submit" class="btn btn-primary" id="addMarket" style="height: 40px; cursor:pointer;" type="submit" value="Сохранить">
				<input class="btn btn-inverse" id="reset" style="height: 40px; cursor:pointer;" type="button" value="Отменить">
			</td>
		</tr>
	</table>
<?= form_close(); ?>