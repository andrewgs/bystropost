<!DOCTYPE html>
<html>
<?php $this->load->view("users_interface/includes/head");?>

<body>
	<?php $this->load->view("clients_interface/includes/header");?>
	<div id="main">
		<?php $this->load->view("clients_interface/includes/navigation");?>
		<div id="stable">
			<div id="panel_menu">
				<?=anchor('#','Готовые задания (0)');?>
				<?=anchor('webmaster-panel/actions/platforms','Площадки ('.$platforms.')');?>
				<?=anchor('webmaster-panel/actions/mails','Почта ('.count($mails).')');?>
				<?=anchor('#','Тикеты (0)');?>
				<?=anchor('#','Дополнительные услуги');?>
				<?=anchor('#','Форум');?>
			</div>
			<table id="panel_table" cellpadding="0" cellspacing="1">
				<tr id="titles">
					<td>Отправитель</td>
					<td>Текс сообщения</td>
					<td><nobr>Возможные действия</nobr></td>
				</tr>
			<?php for($i=0;$i<count($mails);$i++):?>
				<tr>
					<td style="text-align:left; font-size: 10px;">
						<nobr><?=$mails[$i]['fio'];?></nobr><br/>
						<nobr><i><b><?=$mails[$i]['login'];?></b></i></nobr><br/>
						<nobr><?=$mails[$i]['date'];?></nobr>
					</td>
					<td style="text-align:left; font-size: 12px;"><?=$mails[$i]['text'];?></td>
					<td style="text-align:left;">
						<?=anchor('webmaster-panel/actions/platforms/reply/mail-id/'.$mails[$i]['id'],'Ответить',array('class'=>'btn-action small'));?>
						<?=anchor('webmaster-panel/actions/platforms/delete/mail-id/'.$mails[$i]['id'],'Удалить',array('class'=>'btn-action small'));?>
					</td>
				</tr>
			<?php endfor;?>
			</table>
		</div>
	</div>
	<?php $this->load->view("clients_interface/includes/footer");?>
	<?php $this->load->view("clients_interface/includes/scripts");?>
</body>
</html>