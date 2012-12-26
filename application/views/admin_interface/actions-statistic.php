<!DOCTYPE html>
<html lang="en">
<?php $this->load->view('admin_interface/includes/head');?>
<body>
	<?php $this->load->view('admin_interface/includes/header');?>
	<div class="container">
		<div class="row">
			<div class="span9">
				<ul class="breadcrumb">
					<li class="active">
						<?=anchor('admin-panel/actions/statistic','Статистика долгов');?>
					</li>
					<li style="float:right;">
						<?=anchor('users-sending-mail','Уведомить должников',array('class'=>'btn btn-inverse'));?>
						<?=anchor('debitors-auto-blocking','Заблокировать должников',array('class'=>'btn btn-inverse'));?>
					</li>
				</ul>
				<?php $this->load->view('alert_messages/alert-error');?>
				<?php $this->load->view('alert_messages/alert-success');?>
				<table class="table table-striped table-bordered">
					<tbody>
						<tr>
							<td class="w400">Задолженность 3 дня:</td>
							<td class="w100"><?=$stat['to3days']['cnt'];?> записей</td>
							<td class="w85"><?=$stat['to3days']['sum'];?>.00 руб.</td>
						</tr>
						<tr>
							<td class="w400">Задолженность 4 дня:</td>
							<td class="w100"><?=$stat['to4days']['cnt'];?> записей</td>
							<td class="w85"><?=$stat['to4days']['sum'];?>.00 руб.</td>
						</tr>
						<tr>
							<td class="w400">Задолженность 5 дня:</td>
							<td class="w100"><?=$stat['to5days']['cnt'];?> записей</td>
							<td class="w85"><?=$stat['to5days']['sum'];?>.00 руб.</td>
						</tr>
						<tr>
							<td class="w400">Задолженность более 5-х дней:</td>
							<td class="w100"><?=$stat['from5days']['cnt'];?> записей</td>
							<td class="w85"><?=$stat['from5days']['sum'];?>.00 руб.</td>
						</tr>
					</tbody>
				</table>
				<div id="load"></div>
			</div>
		<?php $this->load->view('admin_interface/includes/rightbar');?>
		</div>
	</div>
	<?php $this->load->view('admin_interface/includes/footer');?>
	<?php $this->load->view('admin_interface/includes/scripts');?>
</body>
</html>