<?=form_open($this->uri->uri_string(),array('id'=>'frmaddticket')); ?>

	<table id="panel_table" cellpadding="0" cellspacing="1" class="sel">
		<tr>
			<td class="w100">Тема тикета:</td>
			<td><input name="title" class="reg-form-input w775" id="TitleTicket" type="text" size="80" value="" placeholder="Введите тему тикета"></td>
		</tr>
	<?php if($userinfo['utype'] == 1):?>
		<tr>
			<td class="w100"><nobr>Тип тикета</nobr></td>
			<td style="text-align:left;">
				<input type="radio" name="type" value="1" checked="ckecked" style="vertical-align:middle;"><span style="margin-left: 10px;">Размещение заявок - вопросы, замечания по выполнению текущих заявок</span><br/>
				<input type="radio" name="type" value="2" style="vertical-align:middle;"><span style="margin-left: 10px;">Вопросы по работе с <?=$_SERVER['SERVER_NAME'];?>. Связь с администрацией</span>
			</td>
		</tr>
		<tr>
			<td class="w100">Площадка</td>
			<td style="text-align:left;">
				<select class="reg-form-input w400 h30" name="platform" style="vertical-align:top;padding: 5px;">
				<?php for($i=0;$i<count($platforms);$i++): ?>
					<option value="<?=$platforms[$i]['id'];?>"><?=$platforms[$i]['url'];?></option>
				<?php endfor; ?>
				</select>
			</td>
		</tr>
	<?php endif;?>
		<tr>
			<td class="w100"><nobr>Текст сообщения</nobr></td>
			<td><textarea class="redactor" id="TextTicket" name="text" cols="79" rows="12"></textarea></td>
		</tr>
		<tr>
			<td>&nbsp;</td>
			<td>
				<input name="submit" class="btn btn-primary" id="CreateTicket" style="height: 40px; cursor:pointer;" type="submit" value="Создать">
			</td>
		</tr>
	</table>
<?= form_close(); ?>