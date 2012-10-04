<!DOCTYPE html>
<html>
<?php $this->load->view("users_interface/includes/head");?>

<body>
	<div class="container">
		<?php $this->load->view("users_interface/includes/navigation");?>
		<?php $this->load->view("users_interface/includes/header");?>
		
		<div class="row">
			<div class="span12">
			По вопросам работы сервиса Быстропост, используйте следующий почтовый ящик для связи
				<img src="http://2ip.ru/spambot/117114046116115111112111114116115121098064114101110119111_1_1__0_9_ffffff_6495ed.png" alt="Связь с администрацией"/><br>
				Альтернативный вариант: создайте тикет или напишите письмо, используя внутренний интерфейс пользователя. Функция доступна после регистрации.
			</div>
		</div>
		
		<?php $this->load->view("users_interface/includes/social");?>
	</div>
	<?php $this->load->view("users_interface/includes/footer");?>
	<?php $this->load->view("users_interface/includes/scripts");?>
</body>
</html>