<!DOCTYPE html>
<html>
<?php $this->load->view("users_interface/includes/head");?>

<body>
	<?php $this->load->view("clients_interface/includes/header");?>
	<div id="main">
		<?php $this->load->view("clients_interface/includes/navigation");?>
		<div id="stable">
			<div id="panel_segments">
				<div class="panel_segment">
					<big><?=anchor('#','Готовые задания (6)');?></big>
					<img src="<?=$baseurl;?>images/panel_pic1.jpg">
					<div class="text">
						Перед началом продвижения сайта мы тщательно исследуем как сам сайт, так и рыночный спрос в интересующей
					</div>
				</div>
				<div class="panel_segment">
					<big><?=anchor('#','Площадки (0)');?></big>
					<img src="<?=$baseurl;?>images/panel_pic2.jpg">
					<div class="text">
						Перед началом продвижения сайта мы тщательно исследуем как сам сайт, так и рыночный спрос в интересующей
					</div>
				</div>
				<div class="panel_segment">
					<big><?=anchor('#','Тикеты (0)');?></big>
					<img src="<?=$baseurl;?>images/panel_pic3.jpg">
					<div class="text">
						Перед началом продвижения сайта мы тщательно исследуем как сам сайт, так и рыночный спрос в интересующей
					</div>
				</div>
				<div class="panel_segment">
					<big><?=anchor('#','Почта (0)');?></big>
					<img src="<?=$baseurl;?>images/panel_pic4.jpg">
					<div class="text">
						Перед началом продвижения сайта мы тщательно исследуем как сам сайт, так и рыночный спрос в интересующей
					</div>
				</div>
				<div class="panel_segment">
					<big><?=anchor('#','Дополнительные услуги');?></big>
					<img src="<?=$baseurl;?>images/panel_pic5.jpg">
					<div class="text">
						Перед началом продвижения сайта мы тщательно исследуем как сам сайт, так и рыночный спрос в интересующей
					</div>
				</div>
				<div class="panel_segment">
					<big><?=anchor('#','Форум');?></big>
					<img src="<?=$baseurl;?>images/panel_pic6.jpg">
					<div class="text">
						Перед началом продвижения сайта мы тщательно исследуем как сам сайт, так и рыночный спрос в интересующей
					</div>
				</div>
			</div>
		</div>
	</div>
	<?php $this->load->view("clients_interface/includes/footer");?>
	<?php $this->load->view("clients_interface/includes/scripts");?>
</body>
</html>