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
						<?=anchor('admin-panel/actions/balance','Баланс');?>
					</li>
				</ul>
				<?php $this->load->view('alert_messages/alert-error');?>
				<?php $this->load->view('alert_messages/alert-success');?>
				<table class="table table-striped table-bordered">
					<tbody>
						<tr class="align-center">
							<td class="w400">Заработано всего: </td>
							<td class="w100"><?=$income['total']['cnt'];?> записей</td>
							<td class="w85"><?=$income['total']['sum'];?>.00 руб.</td>
						</tr>
						<tr class="align-center">
							<td class="w400">Заработано за последние 10 дней: </td>
							<td class="w100"><?=$income['ten']['cnt'];?> записей</td>
							<td class="w85"><?=$income['ten']['sum'];?>.00 руб.</td>
						</tr>
						<tr class="align-center">
							<td class="w400">Заработано с учетом вычетов менеджерам: </td>
							<td class="w100"><?=$income['managers']['cnt'];?> записей</td>
							<td class="w85"><?=$income['managers']['sum'];?>.00 руб.</td>
						</tr>
						<tr class="align-center">
							<td class="w400">Общий долг по оплате заявок у клиентов на данный момент: </td>
							<td class="w100"><?=$income['debt']['cnt'];?> записей</td>
							<td class="w85"><?=$income['debt']['sum'];?>.00 руб.</td>
						</tr>
					</tbody>
				</table>
			</div>
		<?php $this->load->view('admin_interface/includes/rightbar');?>
		</div>
	</div>
	<?php $this->load->view('admin_interface/includes/footer');?>
	<?php $this->load->view('admin_interface/includes/scripts');?>
</body>
</html>