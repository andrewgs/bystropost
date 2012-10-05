<!DOCTYPE html>
<html>
<?php $this->load->view("users_interface/includes/head");?>

<body>
	<div class="container">
		<?php $this->load->view("users_interface/includes/navigation");?>
		<?php $this->load->view("users_interface/includes/header");?>
		
		<div class="row">
			<div id="stable">
				<h1 class="whiteline">&nbsp;</h1>
				<div id="birga_about">
					<img src="<?=$baseurl;?>images/markets/blogunlogo.png" class="birga_logo">
					<p>Биржа постовых Blogun</p>
					<p>Дата основания: 2007 год</p>
					<p>Официальный сайт&nbsp;http://blogun.ru</p>
					<p>Blogun(он же Блогун ) является одной из первых бирж ручного размещения постовых (рекламный абзац под основным текстом статьи)</p>
					<iframe src="http://www.slideshare.net/slideshow/embed_code/1427755" width="427" height="356" frameborder="0" marginwidth="0" marginheight="0" scrolling="no" style="border:1px solid #CCC;border-width:1px 1px 0;margin-bottom:5px" allowfullscreen> </iframe>
					<br>
					Сервис Быстропост, в данный момент лишь принимает во внимания, Ваше желание работать с данной биржей. Пожалуйста, дайте <a href="http://bystropost.ru/contacts">нам знать</a>, если у вас есть нужда в обработке заявок в данной бирже.
					<a rel="nofollow" href="http://blogun.ru" class="birga_rega"></a>
				</div>
			</div>
		</div>
		<?php $this->load->view("users_interface/includes/social");?>
	</div>
	<?php $this->load->view("users_interface/includes/footer");?>
	<?php $this->load->view("users_interface/includes/scripts");?>
</body>
</html>