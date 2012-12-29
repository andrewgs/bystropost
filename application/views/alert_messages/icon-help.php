<div class="">
	<table class="table table-condensed">
		<caption>Условные обозначения</caption>
		<tr class="info">
			<td class="span1"><img src="<?=$baseurl;?>images/icons/message_lock.png" alt="" /></td>
			<td class="span11"> &mdash; Тикет закрыт</td>
		</tr>
		<tr class="info">
			<td class="span1"><img src="<?=$baseurl;?>images/icons/message_answer.png" alt="" /></td>
			<td class="span11"> &mdash; Получен ответ от получателя</td>
		</tr>
	<?php if($this->uri->segment(3) == 'tickets-outbox'):?>
		<tr class="info">
			<td class="span1"><img src="<?=$baseurl;?>images/icons/message_noview.png" alt="" /></td>
			<td class="span11"> &mdash; Тикет не просмотрен получателем</td>
		</tr>
		<tr class="info">
			<td class="span1"><img src="<?=$baseurl;?>images/icons/message_noanswer.png" alt="" /></td>
			<td class="span11"> &mdash; Тикет просмотрен получателем</td>
		</tr>
	<?php endif;?>
	<?php if($this->uri->segment(3) == 'tickets-inbox'):?>
		<tr class="info">
			<td class="span1"><img src="<?=$baseurl;?>images/icons/message_noview.png" alt="" /></td>
			<td class="span11"> &mdash; Тикет не просмотрен Вами</td>
		</tr>
		<tr class="info">
			<td class="span1"><img src="<?=$baseurl;?>images/icons/message_in.png" alt="" /></td>
			<td class="span11"> &mdash; Тикет просмотрен Вами</td>
		</tr>
		<tr class="info">
			<td class="span1"><img src="<?=$baseurl;?>images/icons/message_out.png" alt="" /></td>
			<td class="span11"> &mdash; Тикет просмотрен и отвечен Вами</td>
		</tr>
	<?php endif;?>
	</table>
</div>