<?=form_open($this->uri->uri_string(),array('id'=>'frmaddplatform')); ?>
	<input type="hidden" value="<?=$sender['id'];?>" name="recipient" />
	<table id="panel_table" cellpadding="0" cellspacing="1" class="sel">
		<tr>
			<td>Получатель:</td>
			<td style="text-align:left;"><span id="recipient"><?=$sender['fio'];?><br/><b><em><?=$sender['login'];?></em></b></span></td>
		</tr>
		<tr>
			<td><nobr>Текст сообщения</nobr></td>
			<td><textarea class="redactor" id="text" name="text" cols="79" rows="12">
				<b><u>Ответ:</u></b><br/><br/><br/><b><u>Вам писали:</u></b><br/><?=$mltext;?>
			</textarea></td>
		</tr>
		<tr>
			<td>&nbsp;</td>
			<td style="text-align:left;"><input id="sendMail" name="sendmail" type="checkbox" value="1" style="vertical-align:middle;"><span style="margin-left: 10px;">Установите если нужно уведомить по почте</span></td>
		</tr>
		<tr>
			<td>&nbsp;</td>
			<td><input name="submit" id="SendMail" style="height: 40px; cursor:pointer;" type="submit" value="Отправить"></td>
		</tr>
	</table>
<?= form_close(); ?>