<!DOCTYPE html>
<html>
<?php $this->load->view("users_interface/includes/head");?>

<body>
	<div class="container">
		<?php $this->load->view("users_interface/includes/navigation");?>
		<?php $this->load->view("users_interface/includes/header");?>
		
		<div class="row">
			<div class="span12">
				<h1>Карта сайта</h1>
				<br/>
				<ul>
					<li><?=anchor('','Главная');?></li>
					<li><?=anchor('webmasters','Вебмастерам');?></li>
					<li><?=anchor('optimizers','Оптимизаторам');?></li>
					<li><?=anchor('prices','Цены');?></li>
					<li><?=anchor('about-content','О контенте');?></li>
					<li><?=anchor('capabilities','Наши возможности');?></li>
					<li><?=anchor('interface','Интерфейс');?></li>
					<li><?=anchor('news','Новости');?></li>
					<li><?=anchor('about','О проекте');?></li>
					<li><?=anchor('support','Поддержка');?></li>
					<li><?=anchor('faq','FAQ');?></li>
					<li><?=anchor('forum','Форум');?></li>
					<li><?=anchor('users-ratings/webmasters','Отзывы');?></li>
					<li><?=anchor('contacts','Контакты');?></li>
					<li><?=anchor('site-map','Карта сайта');?></li>
					<li><?=anchor('site-monetization','Монетизация сайта');?></li>
					<li><?=anchor('additional-services','Дополнительные услуги');?></li>
					<!--<li><?=anchor('disclaimer','Уведомление об ответственности');?></li>-->
				</ul>
			</div>
		</div>
		
		<?php $this->load->view("users_interface/includes/social");?>
	</div>
	<?php $this->load->view("users_interface/includes/footer");?>
	<?php $this->load->view("users_interface/includes/scripts");?>
</body>
</html>