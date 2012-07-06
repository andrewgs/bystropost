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
				<?=anchor('webmaster-panel/actions/tickets','Тикеты ('.$tickets.')');?>
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
					<td style="text-align:left; font-size: 12px;" data-system="<?=$mails[$i]['system'];?>" data-type="<?=$mails[$i]['type'];?>"><?=$mails[$i]['text'];?></td>
				<?php if(!$mails[$i]['system']):?>	
					<td style="text-align:center;">
						<?=anchor('webmaster-panel/actions/platforms/reply/mail-id/'.$mails[$i]['id'],'Ответить',array('class'=>'btn-action small'));?>
						<?=anchor('webmaster-panel/actions/platforms/delete/mail-id/'.$mails[$i]['id'],'Удалить',array('class'=>'btn-action small delMails'));?>
					</td>
				<?php else:?>
					<td style="text-align:center;" class="SysMsg">Системное сообщение</td>
				<?php endif;?>
				</tr>
			<?php endfor;?>
			</table>
		</div>
	</div>
	<?php $this->load->view("clients_interface/includes/footer");?>
	<?php $this->load->view("clients_interface/includes/scripts");?>
	<script type="text/javascript">
		$(document).ready(function(){
			var msgtype = 0;
			$("td[data-system = '1']").each(function(e){
				msgtype = $(this).attr('data-type');
				switch (msgtype){
					case '1': $(this).addClass('alert alert-error'); $(this).siblings('td').addClass('alert alert-error'); break
					case '2': $(this).addClass('alert alert-info'); $(this).siblings('td').addClass('alert alert-info'); break
					case '3': $(this).addClass('alert alert-block'); $(this).siblings('td').addClass('alert alert-block'); break
					case '4': $(this).addClass('alert alert-success'); $(this).siblings('td').addClass('alert alert-success'); break
				}
			});
			$(".delMails").click(function(event){
				if(!confirm("Удалить тикет?")){
					event.preventDefault();
				}
			});
		});
	</script>
</body>
</html>