<!DOCTYPE html>
<html>
<?php $this->load->view("users_interface/includes/head");?>

<body>
	<div class="container">
		<?php $this->load->view("users_interface/includes/navigation");?>
		<?php $this->load->view("users_interface/includes/header");?>
		<div class="clear"></div>
		<div class="row">
			<div class="span12">
				<p>
					BYSTROPOST (быстрый постовой) - занимается обработкой заявок с самого открытия сервиса вечных ссылок GoGetLinks, а 
					именно с 2009 года. Начаналось всё в частном порядке, но сейчас, благодаря слаженным действиям команды и большому 
					объёму работы на данный момент (о чём свидетельствет страница Отзывов), было решено разработать и запустить веб-интерфейс, 
					который упрастит взаимодействия между Вами и вашим личным менеджером Bystropost.ru
				</p>
				<p>
					Ранее всё функционировало путём отправки отчетов раз в неделю ввиде XLS файлов. Много писем попадало в спам или просто уходили 
					в тьме потока в архив. Задать вопросы можно было исключительно по э-мейлу или в аське, а счастливчики могли поймать и в скайпе.
					Теперь стало всё существенно проще и современней. И в первую очередь, подобная система увеличит скорость выполнения ваших заданий 
					и позволит держать более большие объёмы. Чему будите рады не только вы, но и мы. Контроль заявок осуществляется в режиме реального 
					времени. Сразу по выполнению задания, вам будет доступна полностя вся доступная нам из биржи информация, в том числе и кол-во символов, 
					в той статье, которую мы специально для вас написали!
				</p>
				<p>	
					На самом деле система очень проста и безупречна. Ведь чем больше будет сделано заявок, тем больше денег вы получите и соответственно 
					больше заплатите нам. Откровенная правда, которая заставляет работать на совесть. И расти вместе с вами вместе!
				</p>
				<p>
					Веб-версия в тестирование от 30 июля 2012 года
					Пожалуйста, сообщайте о всех возможных недочётах через наш форум или на почту <?= safe_mailto('support@bystropost.ru', 'support@bystropost.ru'); ?>
				</p>
				<p>	
					Если у вас остались дополнительные вопросы, обратите внимание на раздел F.A.Q., где мы собрали самые распространенные вопросы от А до Я.
				</p>
				<p>&nbsp;</p>
				<p>&nbsp;</p>
				<p>&nbsp;</p>
				<p>&nbsp;</p>
			</div>
		</div>
	</div>
	<?php $this->load->view("users_interface/includes/footer");?>
	<?php $this->load->view("users_interface/includes/scripts");?>
</body>
</html>