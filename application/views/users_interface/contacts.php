<!DOCTYPE html>
<html>
<?php $this->load->view("users_interface/includes/head");?>

<body>
	<div class="container">
		<?php $this->load->view("users_interface/includes/navigation");?>
		<?php $this->load->view("users_interface/includes/header");?>
		
		<div class="row">
			<div class="span12">
			<h1>Контакты</h1>
			По вопросам работы сервиса Быстропост, используйте следующий почтовый ящик для связи
				<img src="http://2ip.ru/spambot/117114046116115111112111114116115121098064114101110119111_1_1__0_9_ffffff_6495ed.png" alt="Связь с администрацией"/><br>
				Альтернативный вариант: создайте тикет или напишите письмо, используя внутренний интерфейс пользователя. Функция доступна после регистрации.
			</div>
		</div>
		<div class="row">
			<div class="span12">
				<h2 class="special">Схема проезда</h2>
				<!-- Этот блок кода нужно вставить в ту часть страницы, где вы хотите разместить карту (начало) -->
				<div id="ymaps-map-id_135552490965357152470" style="width: 940px; height: 350px; margin: 1em 0 0.5em; "></div>
				<script type="text/javascript">
				function fid_135552490965357152470(ymaps) {
				    var map = new ymaps.Map("ymaps-map-id_135552490965357152470", {
				        center: [39.75855068915379, 47.2322351569164],
				        zoom: 16,
				        type: "yandex#map"
				    });
				    map.controls
				        .add("zoomControl")
				        .add("mapTools")
				        .add(new ymaps.control.TypeSelector(["yandex#map", "yandex#satellite", "yandex#hybrid", "yandex#publicMap"]));
				    map.geoObjects
				        .add(new ymaps.Placemark([39.75833, 47.23204], {
				            balloonContent: ""
				        }, {
				            preset: "twirl#lightblueDotIcon"
				        }));
				};
				</script>
				<script type="text/javascript" src="http://api-maps.yandex.ru/2.0-stable/?lang=ru-RU&coordorder=longlat&load=package.full&wizard=constructor&onload=fid_135552490965357152470"></script>
				<!-- Этот блок кода нужно вставить в ту часть страницы, где вы хотите разместить карту (конец) -->
				<h2>Контактная информация</h2>
				<p>
					ООО "Быстропост" – www.bystropost.ru <br/> 
					344032, г.Ростов-на-Дону <br/>
					ул.16 линия, 16 «Б»
				</p>				
				<p>
					Телефон: (863) 295-51-11 <br/>
					Skype: sacuredo <br/>
					ICQ: 810160
				</p>
			</div>
		</div>
		<?php $this->load->view("users_interface/includes/social");?>
	</div>
	<?php $this->load->view("users_interface/includes/footer");?>
	<?php $this->load->view("users_interface/includes/scripts");?>
</body>
</html>