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
					<big><?=anchor('#','Готовые задания (0)');?></big>
					<img src="<?=$baseurl;?>images/panel_pic1.jpg">
					<div class="text">
						Перед началом продвижения сайта мы тщательно исследуем как сам сайт, так и рыночный спрос в интересующей
					</div>
				</div>
				<div class="panel_segment">
					<big><?=anchor('webmaster-panel/actions/platforms','Площадки ('.$cntunit['platforms'].')');?></big>
					<img src="<?=$baseurl;?>images/panel_pic2.jpg">
					<div class="text">
						Перед началом продвижения сайта мы тщательно исследуем как сам сайт, так и рыночный спрос в интересующей
					</div>
				</div>
				<div class="panel_segment">
					<big><?=anchor('webmaster-panel/actions/tickets','Тикеты ('.$cntunit['tickets'].')');?></big>
					<img src="<?=$baseurl;?>images/panel_pic3.jpg">
					<div class="text">
						Перед началом продвижения сайта мы тщательно исследуем как сам сайт, так и рыночный спрос в интересующей
					</div>
				</div>
				<div class="panel_segment">
					<big><?=anchor('webmaster-panel/actions/mails','Почта ('.$cntunit['mails'].')');?></big>
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
			<div class="clear"></div>
			<div style="float: right; margin-right: 100px;">
				<?=anchor('webmaster-panel/actions/cabinet','Профиль',array('class'=>'btn-action'));?>
			</div>
		</div>
	</div>
	<?php $this->load->view("clients_interface/includes/footer");?>
	<?php $this->load->view("clients_interface/includes/scripts");?>
</body>
</html>