<!DOCTYPE html>
<html>
<?php $this->load->view("users_interface/includes/head");?>

<body>
	<div class="container">
		<?php $this->load->view("users_interface/includes/navigation");?>
		<?php $this->load->view("users_interface/includes/header");?>
		
		<div class="row">
			<div id="white_line" class="span12 no-margin">
				<span>Автоматизация работы в:</span>
				<table border=0>
					<tr>
						<td><img src="<?=$baseurl;?>images/markets/goget.jpg"><?=anchor('markets-catalog/gogetlinks','gogetlinks');?></td>
						<td><img src="<?=$baseurl;?>images/markets/miralinks.jpg"><?=anchor('markets-catalog/miralinks','miralinks');?></td>
						<td><img src="<?=$baseurl;?>images/markets/getgood.jpg"><?=anchor('markets-catalog/getgoodlinks','getgoodlinks');?></td>
						<td><img src="<?=$baseurl;?>images/markets/blogcash.jpg"><?=anchor('markets-catalog/blogocash','blogocash');?></td>
					</tr>
					<tr>
						<td><img src="<?=$baseurl;?>images/markets/sape.jpg"><?=anchor('markets-catalog/prsape','prsape');?></td>
						<td><img src="<?=$baseurl;?>images/markets/blogun.jpg"><?=anchor('markets-catalog/blogun','blogun');?></td>
						<td><img src="<?=$baseurl;?>images/markets/rotapost.jpg"><?=anchor('markets-catalog/rotapost','rotapost');?></td>
						<td>Другие...</td>
					</tr>
				</table>
			</div>
			<div id="stable">
				<h1 class="whiteline">&nbsp;</h1>
				<div id="birga_about">
					<img src="<?=$baseurl;?>images/markets/logo_blogocash.png" class="birga_logo">
					<p>Блогокеш</p>
					<p>Основан и убит в 2010 году, реинкорнация 2012 год.</p>
					<p>Самая молодая, но не менее перспективная биржа вечных ссылок Блогокеш.</p>
					<p>Официальный сайт: <a href="http://blogocash.ru/index.php">http://blogocash.ru/</a></p>
					<p>Представляет собой систему купли/продажи вечных ссылок на
					любых сайтах которые добавили в систему. Модерация напрочь отсутствует, но это
					абсолютно не мешает покупателяю найти продавца.</p>
					<img src="<?=$baseurl;?>images/markets/blogocash_screen.png" class="screen">
					<p>Основными типами заданий являются : <b>короткая заметка</b> (постовой), <b>краткий
					обзор</b> (копирайт от 400 символов) и <b>подробный
					обзор</b>, где вебмастеру придётся выложиться на все 1000 символов.</p>
					<p>Система допускает ТОРГ между вебмастером и оптимизатором, а
					также последнему позволяет продвигать абсолютно любые товары и услуги. Удобный
					интерфейс в котором легко переключится между блоггером и покупателем,&nbsp; вывод средств который занимает в среднем пару
					суток. Минимальный размер вывода составляет 69 рублей, с учетом комиссии. На
					данный момент комиссия системы блогокеш приравнивается к 15%. Которые взымаются
					с оптимизатора при подачи заявки, а у вебмастера вычитаются при выводе.</p>
					<p><b>Плюсы системы</b>
					можно перечислять долго, основные из них</p>
					<p>1.<span style="Times New Roman&quot;">  
					</span>Возврат денег в случае снятия обзора</p>
					<p>2.<span style="Times New Roman&quot;">  
					</span>Проверка индексации</p>
					<p>3.<span style="Times New Roman&quot;">  
					</span>Возможность торга</p>
					<p>4.<span style="Times New Roman&quot;">  
					</span>Саппорт по всем видам связи, включая icq</p>
					<p>5.<span style="Times New Roman&quot;">  
					</span>Ввести деньги можно многими способами. Вывод
					постоянно доступен в WebMoney</p>
					<p>6.<span style="Times New Roman&quot;">  
					</span>Отсутствие
					модерации для вебмастера</p>
					<p>7.<span style="Times New Roman&quot;">  
					</span>Перевод площадок в замороженный режим, если
					вебмастер не среагировал на новую заявку, сл-но всегда активная база сайтов</p>
					<p>Интересное</p>
					<p>В блогокеше можно заработать даже с тех сайтов, где
					владельцами вы не являетесь.</p>
					<p>Существуют постоянные акции для рекламодателей, которые
					увеличивают поток вливаемых средств в систему.</p>
					<p>Основное направление в плане развития сайта, это всегда было
					и есть юзабилити ресурса. Вы всегда можете повлиять на разивитие проекта,
					высказав своё мнение в терпиливую службу поддержки.</p>
					<p>20 мая 2012 года каждый зарегистрированный член системы
					блогокеш был поздравлен с победой сборной России по Хоккею. Знай наших J</p>
					<p><b>Итоги</b></p>
					<p>Блогокеш, собрал в себе многие сайты, различных тематик и
					различного качества. Цена весьма занижена даже по умолчанию, в сравнению с
					конкрентами. Всегда есть различные акции для рекламодателей. Всё делается для
					людей с целью, помочь вебмастеру заработать.</p>
					<p>Биржа отлично совмещается с другими биржами в комплексе и
					служит дополнительным источником дохода. Но в силу своего молодого возвраста,
					ещё не может позволить себе хорошего потока заявок, но человеческого отношения
					к человеку, будь он хоть вебмастером, хоть оптимизатором не убавлять.</p>
					<a href="" class="birga_rega"></a>
				</div>
			</div>
		</div>
		<?php $this->load->view("users_interface/includes/social");?>
	</div>
	<?php $this->load->view("users_interface/includes/footer");?>
	<?php $this->load->view("users_interface/includes/scripts");?>
</body>
</html>