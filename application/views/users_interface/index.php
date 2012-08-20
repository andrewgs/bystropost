<!DOCTYPE html>
<html>
<?php $this->load->view("users_interface/includes/head");?>

<body>
	<?php $this->load->view("users_interface/includes/header");?>
	<div id="main">
		<?php $this->load->view("users_interface/includes/navigation");?>
		
		<table id="content" border="0">
			<tr>
				<td colspan="3" style="width:960px;text-align:center;">
					<span><big>Быстропост</big> - система автоматической монетизации</span>
				</td>
			</tr>
			<tr>
				<td width="315px" valign=top>
					Сайт должен приносить деньги и позволять вебмастеру оставлять свободное время, в том числе для создания новых сайтов. Наш сервис полностью берёт на себя всю рутинную работу, вам нужно лишь указать логин и пароль от биржи, после чего баланс на вашем счету всегда будет иметь хорошую динамику.
				</td>
				<td width="375px" valign=top>
					&nbsp;
				</td>
				<td width="315px" valign="top">
					В настоящее время найти хорошего донора, вопрос не только профессионализма, но и личного времени. Площадок множество, цены курируются в зависимости от бирж. Проанализировать каждого донора и выбрать эффективную с точки зрения сео площадку становится весьма трудоёмким занятием. А переписка с вебмастером порой просто убивает. Мы предлагаем единственно верный выход.
				</td>
			</tr>
			<tr>
				<td colspan=3 height="100%" valign="top">
					<div id="webmaster">
						<img src="<?=$baseurl;?>images/webmaster.png"><br>
						<big><i>Вебмастерам</i></big>
						<ul>
							<li>Простой старт</li>
							<li>Постоянный мониторинг и выполнение пришедших заявок в любое время дня и ночи</li>
							<li>Качественное наполнение контентом</li>
						</ul>
						<?=anchor('webmasters','&nbsp;',array('class'=>'more'));?>
					</div>
					<div id="optimizator">
						<img src="<?=$baseurl;?>images/optimizator.png"><br>
						<big><i>Оптимизаторам</i></big>
						<ul>
							<li>Размещение на проверенных площадках, с гарантией индексации</li>
							<li>Увеличение ТИЦ продвигаемого сайта</li>
							<li>Постоянные обновления донор-площадок</li>
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