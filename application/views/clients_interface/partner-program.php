<!DOCTYPE html>
<html lang="en">
<?php $this->load->view("clients_interface/includes/head");?>

<body>
	<?php $this->load->view("clients_interface/includes/header");?>
	<div class="container">
		<div class="row">
			<div class="span9">
				<ul class="breadcrumb">
					<li class="active">
						<?=anchor("webmaster-panel/actions/partner-program","Партнёрская программа");?>
					</li>
				</ul>
				<?php $this->load->view("alert_messages/alert-error");?>
				<?php $this->load->view("alert_messages/alert-success");?>
				<div class="alert alert-info" id="msginfoalert">
					<strong>Зарабатывай 5% с заявки, привлекай новых пользователей!</strong><br/><br/>
					Ваша индивидуальная ссылка для привлечение пользователей:<br/><strong><a href="http://bystropost.ru/partner/<?=$userinfo['uid'];?>">http://bystropost.ru/partner/<?=$userinfo['uid'];?></a></strong>
				</div>
				<table class="table table-bordered">
					<tbody>
						<tr>
							<td class="w500">Привлеченных вебмастеров:</td>
							<td class="w100" style="text-align:center; vertical-align:middle;"><?=$cnt['webmasters'];?></td>
						</tr>
						<tr>
							<td class="w500">Добавили сайтов:</td>
							<td class="w100" style="text-align:center; vertical-align:middle;"><?=$cnt['platforms'];?></td>
						</tr>
						<tr>
							<td class="w500">Оплатили заявок:</td>
							<td class="w100" style="text-align:center; vertical-align:middle;"><?=$cnt['works'];?></td>
						</tr>
						<tr>
							<td class="w500">Заработано:</td>
							<td class="w100" style="text-align:center; vertical-align:middle;"><?=$cnt['summa'];?> руб.</td>
						</tr>
					</tbody>
				</table>
			<?php if(count($partners)):?>
				<table class="table table-bordered" style="width: 700px;">
					<thead>
						<tr>
							<th class="w400"><center>Логин</center></th>
							<th class="w100"><center>Дата регистрации</center></th>
							<th class="w50"><center>Заявок</center></th>
						</tr>
					</thead>
					<tbody>
					<?php for($i=0;$i<count($partners);$i++):?>
						<tr>
							<td><?=$partners[$i]['login'];?></td>
							<td><?=$partners[$i]['signdate'];?></td>
							<td><?=$partners[$i]['works'];?></td>
						</tr>
					<?php endfor; ?>
					</tbody>
				</table>
			<?php endif;?>
			</div>
		<?php $this->load->view("clients_interface/includes/rightbar");?>
		</div>
	</div>
	<?php $this->load->view("clients_interface/includes/footer");?>
	<?php $this->load->view("clients_interface/includes/scripts");?>
</body>
</html>