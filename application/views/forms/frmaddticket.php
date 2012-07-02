<?=form_open($this->uri->uri_string(),array('id'=>'frmaddticket')); ?>

	<table id="panel_table" cellpadding="0" cellspacing="1" class="sel">
		<tr>
			<td width="200">Тема тикета:</td>
			<td><input name="title" class="reg-form-input w700" id="TitleTicket" type="text" size="80" value="" placeholder="Введите тему тикета"></td>
		</tr>
		<tr>
			<td><nobr>Тип тикета</nobr></td>
			<td style="text-align:left;">
				<input type="radio" name="type" value="1" checked="ckecked" style="vertical-align:middle;"><span style="margin-left: 10px;">Размещение заявок - вопросы, замечания по выполнению текущих заявок</span><br/>
				<input type="radio" name="type" value="2" style="vertical-align:middle;"><span style="margin-left: 10px;">Вопросы по работе с <?=$_SERVER['SERVER_NAME'];?>. Связь с администрацией</span>
			</td>
		</tr>
		<tr>
			<td><nobr>Текст сообщения</nobr></td>
			<td><textarea class="redactor" id="TextTicket" name="text" cols="79" rows="12"></textarea></td>
		</tr>
		<tr>
			<td>&nbsp;</td>
			<td><input name="submit" id="CreateTicket" style="height: 40px; cursor:pointer;" type="submit" value="Создать"></td>
		</tr>
	</table>
<?= form_close(); ?>