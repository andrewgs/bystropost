<!DOCTYPE html>
<html>
<?php $this->load->view("users_interface/includes/head");?>

<body>
	<div class="container">
		<?php $this->load->view("users_interface/includes/navigation");?>
		<?php $this->load->view("users_interface/includes/header");?>
		
		<div class="row">
			<h1>Порядок оплаты</h1>
			<p>После регистрации в системе как вебмастера, вам необходимо указать свой действительный WMID (Webmoney Идентификатор) в личном профиле. После чего внести 500 рублей в счет аванса выполнения заявок. Оплата должна происходить с WMID указанного в вашем профиле.</p>
			<p>Зачисление происходит в автоматическом режиме через систему WebMoney Transfer.</p>
			<p>Наша система сама импортирует все площадки после указания вами данных от бирж(и). После чего вам станут доступны другие разделы интерфейса в т.ч и возможность подключения других дополнительных услуг.</p>
			<p>В любой момент вы можете запросить вывод неиспользованных средств, через тикет внутри системы.</p>
			<div><a href="http://bystropost.ru/prices" target="_blank">Текущий прейскурант цен</a></p>
		</div>
		<?php $this->load->view("users_interface/includes/social");?>
	</div>
	<?php $this->load->view("users_interface/includes/footer");?>
	<?php $this->load->view("users_interface/includes/scripts");?>
</body>
</html>