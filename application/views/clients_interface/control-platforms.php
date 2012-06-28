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
				<?=anchor('webmaster-panel/actions/platforms','Площадки (0)');?>
				<?=anchor('#','Почта (0)');?>
				<?=anchor('#','Тикеты (0)');?>
				<?=anchor('#','Дополнительные услуги');?>
				<?=anchor('#','Форум');?>
			</div>
			<table id="panel_table" cellpadding="0" cellspacing="1">
				<tr id="titles">
					<td class="w100">URL площадки</td>
					<td class="w100">Биржи</td>
					<td class="50">тиц/pr</td>
					<td class="w100">Заявки:<br/> Всего / <nobr>Не оплаченые</nobr></td>
					<td class="w50"><nobr>Дата</nobr></td>
				</tr>
				<tr>
					<td><a href="">tester.ru</a></td>
					<td class="platform_td"><img src="/images/gogetlinks.ico" width="16" heigth="16" alt="gogetlinks" title="gogetlinks"> <img src="/images/rotapost.ico" width="16" heigth="16" alt="rotapost" title="rotapost"> </td>
					<td class="platform_td">0/0</td>
					<td class="platform_td"><a href="#">0/0</a></td>
				</tr>
				<tr>
					<td><a href="site.php?id=8">divnom.ru</a></td>
					<td class="platform_td"><img src="/images/gogetlinks.ico" width="16" heigth="16" alt="gogetlinks" title="gogetlinks"> <img src="/images/rotapost.ico" width="16" heigth="16" alt="rotapost" title="rotapost"> </td>
					<td class="platform_td">0/0</td>
					<td class="platform_td"><a href="#">0/0</a></td>
				</tr>
				<tr>
					<td><a href="site.php?id=7">hideme.ru</a></td>
					<td class="platform_td"><img src="/images/gogetlinks.ico" width="16" heigth="16" alt="gogetlinks" title="gogetlinks"> <img src="/images/rotapost.ico" width="16" heigth="16" alt="rotapost" title="rotapost"> </td>
					<td class="platform_td">0/0</td>
					<td class="platform_td"><a href="#">0/0</a></td>
				</tr>
			</table>
			<?=anchor('webmaster-panel/actions/platforms/add-platform','Добавить платформу',array('id'=>'new_platform'));?>
		</div>
	</div>
	<?php $this->load->view("clients_interface/includes/footer");?>
	<?php $this->load->view("clients_interface/includes/scripts");?>
</body>
</html>