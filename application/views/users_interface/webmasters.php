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
					Минимальная стоимость обработки заданий для сайтов с тиц 10-20 составляет 50 рублей. Сюда входит абсолютно полная работа 
					под ключ, а именно: мониторинг биржи,общение с оптимизаторами,рассылка лимитов,выполнение заявок,написание текста от 1000 
					символов, поиск и публикация картинки с загрузкой на сервер,публикация контента,сдача заявки в биржу. Для сайтов с тиц от 
					30, стоимость возврастает на 5 рублей.
				</p>
				<p>
					Копирайт от 2000 символов, это тоже к нам, каждое задание будет сделано и вы получите за него свои легко заработанные деньги. 
					Количество символов для публикации можно менять исходя из ваших индивидуальных пожеланий, после регистрации вам будут доступны и 
					другие дополнительные услуги.
				</p>
				<p>
					Выполняем следующие типы заданий: контекстная ссылка(ggl/pr.sape), короткая заметка/постовой (rotapost/blogocash), ссылка-картинка(ggl), 
					ссылка в архиве(ggl/pr.sape), статья/обзор/пост(miralinks/rotapost/pr.sape), пресс-релиз(rotapost), Краткий и расширенный обзор(blogocash), 
					размещение готового задания (miralinks/pr.sape).... иными словами мы делаем всё!
				</p>
				<p>
					Теперь можно спокойно отдать сайты на аутсорсинг и заняться пассивной монетизацией благодаря нашему сервису.
				</p>
			</div>
		</div>
		<div id="register_tree">
			<?=anchor('users/registering/webmaster','<img src="'.$baseurl.'images/spacer.gif" width="500px" height="450px" border="0">');?>
		</div>
	</div>
	<?php $this->load->view("users_interface/includes/footer");?>
	<?php $this->load->view("users_interface/includes/scripts");?>
</body>
</html>