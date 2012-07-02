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
				<?=anchor('webmaster-panel/actions/platforms','Площадки ('.count($platforms).')');?>
				<?=anchor('webmaster-panel/actions/mails','Почта ('.$mails.')');?>
				<?=anchor('webmaster-panel/actions/tickets','Тикеты ('.$tickets.')');?>
				<?=anchor('#','Дополнительные услуги');?>
				<?=anchor('#','Форум');?>
			</div>
			<table id="panel_table" cellpadding="0" cellspacing="1">
				<tr id="titles">
					<td class="w100">URL площадки</td>
					<td class="w100">Биржи</td>
					<td class="50">тиц / pr</td>
					<td class="w100">Задания:<br/> Всего / <nobr>Не оплаченые</nobr></td>
					<td class="w50"><nobr>Дата</nobr></td>
				</tr>
			<?php for($i=0;$i<count($platforms);$i++):?>
				<tr>
					<td><?=anchor('webmaster-panel/actions/platforms/edit-platform/'.$platforms[$i]['id'],$platforms[$i]['url']);?></td>
					<td class="platform_td">
				<?php for($j=0,$cnt=0;$j<count($markets);$j++):?>
					<?php if($platforms[$i]['id'] == $markets[$j]['platform']):?>
						<?php if($cnt>=1):?>
							<?php if($markets[$j]['id'] == $markets[$j-1]['id']):?>
								<?php continue;?>
							<?php endif;?>
						<?php endif;?>
						<?=anchor($markets[$j]['url'],'<img src="'.$baseurl.'markets/viewimage/'.$markets[$j]['id'].'" alt="'.$markets[$j]['title'].'" title="'.$markets[$j]['title'].'"/>',array("class"=>'','target'=>'_blank'));?>
						<?php $cnt++;?>
					<?php endif;?>
				<?php endfor;?>
					</td>
					<td class="platform_td"><?=$platforms[$i]['tic'];?> / <?=$platforms[$i]['pr'];?></td>
					<td class="platform_td"><?=anchor('#','0 / 0');?></td>
					<td class="platform_td"><?=$platforms[$i]['date'];?></td>
				</tr>
			<?php endfor;?>
			</table>
			<?=anchor('webmaster-panel/actions/platforms/add-platform','Добавить платформу',array('class'=>'btn-action'));?>
		</div>
	</div>
	<?php $this->load->view("clients_interface/includes/footer");?>
	<?php $this->load->view("clients_interface/includes/scripts");?>
</body>
</html>