<!DOCTYPE html>
<html lang="en">
<?php $this->load->view('admin_interface/includes/head');?>
<body>
	<?php $this->load->view('clients_interface/includes/header');?>
	<div class="container">
		<div class="row">
			<div class="span9">
				<ul class="breadcrumb">
					<li>
						<?=anchor('webmaster-panel/actions/balance','Баланс аккаунта');?><span class="divider">/</span>
					</li>
					<li class="active">
						<?=anchor($this->uri->uri_string(),'История платежей');?>
					</li>
				</ul>
				<?php $this->load->view('alert_messages/alert-error');?>
				<?php $this->load->view('alert_messages/alert-success');?>
			<?php if($this->uri->total_segments() < 6):?>
				<table class="table table-striped table-bordered">
					<thead>
						<tr>
							<th><center>Период</center></th>
							<th><center>Пополнено</center></th>
							<th><center>Потрачено</center></th>
						</tr>
					</thead>
					<tbody>
						<tr>
							<td class="w400">За неделю:</td>
							<td class="w100"><?=$statistic['week'][0]['summa'];?>.00 руб.</td>
							<td class="w85"><?=$statistic['week'][1]['summa'];?>.00 руб.</td>
						</tr>
						<tr>
							<td class="w400">За месяц потрачено (до сегодня):</td>
							<td class="w100"><?=$statistic['bymonth'][0]['summa'];?>.00 руб.</td>
							<td class="w85"><?=$statistic['bymonth'][1]['summa'];?>.00 руб.</td>
						</tr>
						<tr>
							<td class="w400">За прошлый месяц:</td>
							<td class="w100"><?=$statistic['month'][0]['summa'];?>.00 руб.</td>
							<td class="w85"><?=$statistic['month'][1]['summa'];?>.00 руб.</td>
						</tr>
						<tr>
							<td class="w400">За все время:</td>
							<td class="w100"><?=$statistic['total'][0]['summa'];?>.00 руб.</td>
							<td class="w85"><?=$statistic['total'][1]['summa'];?>.00 руб.</td>
						</tr>
					</tbody>
				</table>
				<button id="ShowHistoryList">Полная история платежей</button>
			<?php endif;?>
			<?php if($this->uri->total_segments() < 6):?>
				<div id="HistoryList" style="display:none">
			<?php else:?>
				<div id="HistoryList">
			<?php endif;?>
					<table class="table table-bordered">
						<thead>
							<tr>
								<th><center>Вид платежа:</center></th>
								<th><center>Дата</center></th>
								<th><center>Сумма</center></th>
							</tr>
						</thead>
						<tbody>
					<?php for($i=0;$i<count($history);$i++):?>
							<tr class="<?=($history[$i]['status'])?'addmoney':'rmmoney'?>">
								<td class="w230"><?=$history[$i]['result']?></td>
								<td class="w50"><center><?=$history[$i]['date']?></center></td>
								<td class="w50"><?=$history[$i]['summa']?>.00 руб.</td>
							</tr>
					<?php endfor;?>
						</tbody>
					</table>
					<?php if($pages): ?>
						<?=$pages;?>
					<?php endif;?>
				</div>
			</div>
			<?php $this->load->view("clients_interface/includes/rightbar");?>
		</div>
	</div>
	<?php $this->load->view("clients_interface/includes/footer");?>
	<?php $this->load->view('clients_interface/includes/scripts');?>
	<?php if($this->uri->total_segments() < 6):?>
	<script type="text/javascript">
		$(document).ready(function(){
			$("tr.addmoney").each(function(e){$(this).addClass('paid');});
			$("tr.rmmoney").each(function(e){$(this).addClass('notpaid');});
			$("#ShowHistoryList").click(function(){$("#HistoryList").fadeToggle("200");});
		});
	</script>
	<?php endif;?>
</body>
</html>
