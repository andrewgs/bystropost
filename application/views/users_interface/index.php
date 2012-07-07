<!DOCTYPE html>
<html>
<?php $this->load->view("users_interface/includes/head");?>

<body>
	<?php $this->load->view("users_interface/includes/header");?>
	<div id="main">
		<?php $this->load->view("users_interface/includes/navigation");?>
		
		<table id="content" border="0">
			<tr>
				<td width="315px" valign=top>
					<span><big>bystropost.ru</big><br>— Система <br>управления продажами</span><br><br>Сайт должен приносить доход – именно из этого принципа мы исходили при разработке сервиса GoGetLinks. Сервис позволяет зарабатывать на собственных сайтах, размещая на них заметки и контекстные ссылки.
				</td>
				<td width="375px" valign=top>
					&nbsp;
				</td>
				<td width="315px" valign="top">
					<span>Статистика</span><br><br>Сайтов:<br>9998<hr>Сайтов в каталоге DMOZ:<br>1915<hr>Сайтов в каталоге Яндекса:<br>2056<hr>
					Пользователей:<br>92056
				</td>
			</tr>
			<tr>
				<td colspan=3 height="100%" valign="top">
					<div id="webmaster">
						<img src="<?=$baseurl;?>images/webmaster.png"><br>
						<big><i>Вебмастерам</i></big>
						<ul>
							<li>Публикуйте заметки об интернет-проектах и контекстные ссылки на страницах своих сайтов</li>
							<li>Получайте за это деньги</li>
						</ul>
						<?=anchor('webmasters','&nbsp;',array('class'=>'more'));?>
					</div>
					<div id="optimizator">
						<img src="<?=$baseurl;?>images/optimizator.png"><br>
						<big><i>Оптимизаторам</i></big>
						<ul>
							<li>Публикуйте заметки об интернет-проектах и контекстные ссылки на страницах своих сайтов</li>
							<li>Получайте за это деньги</li>
						</ul>
						<?=anchor('optimizers','&nbsp;',array('class'=>'more'));?>
					</div>
				</td>
			</tr>
		</table>
		<?php $this->load->view("users_interface/includes/social");?>
	</div>
	<?php $this->load->view("users_interface/includes/footer");?>
	<?php $this->load->view("users_interface/includes/scripts");?>
</body>
</html>