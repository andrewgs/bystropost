<!DOCTYPE html>
<html lang="en">
<?php $this->load->view("clients_interface/includes/head");?>

<body>
	<?php $this->load->view("clients_interface/includes/header");?>
	
	<div class="container">
		<div class="row">
			<div class="span12">
				<?php $this->load->view("alert_messages/alert-error");?>
				<?php $this->load->view("alert_messages/alert-success");?>
				<div class="clear"></div>
			<?php if($userinfo['locked'] && !$userinfo['debetor']):?>
				<div class="alert alert-error">
					<center>Перед началом работы в системе Bystropost необходимо пополнить баланс минимум на 500 рублей.<br/>После пополнения, Вам будут доступны дополнительные разделы.<br/><?=anchor('webmaster-panel/actions/balance','Пополнить баланс');?></center>
				</div>
				<div class="clear"></div>
			<?php endif;?>
			<?php if($userinfo['debetor']):?>
				<div class="alert alert-error">
					<center>Внимание! Ваш аккаунт заблокирован по причине задолженности.<br/>Оплатите выполненные задания за 5 дней и более для разблокировки. Если не хватает средств  пополните Ваш баланс.<br/><?=anchor('webmaster-panel/actions/balance','Пополнить баланс');?></center>
				</div>
				<div class="clear"></div>
			<?php endif;?>
				<div id="stable">
					<div id="panel_segments">
					<?php if($cntunit['delivers']['total']):?>
						<div class="panel_segment">
							<big><?=anchor('webmaster-panel/actions/finished-jobs','Задания (<b>'.$cntunit['delivers']['notpaid'].'</b>/'.$cntunit['delivers']['total'].')',array('title'=>$cntunit['delivers']['notpaid'].' не оплаченных'));?></big>
							<img src="<?=$baseurl;?>images/panel_pic1.jpg">
							<div class="text">
								Отображение общего числа заявок и готовых заданий на оплату
							</div>
						</div>
					<?php endif;?>
					<?php if((($cntunit['platforms'] || $cntunit['markets']) && $userinfo['remote']) || (!$userinfo['locked'] && !$userinfo['remote'])):?>
						<div class="panel_segment">
							<big><?=anchor('webmaster-panel/actions/platforms','Площадки ('.$cntunit['platforms'].')');?></big>
							<img src="<?=$baseurl;?>images/panel_pic2.jpg">
							<div class="text">
							<?php if($userinfo['remote']):?>
								Список и настройка ваших площадок. Добавления площадок через раздел Биржи.
							<?php else:?>
								Список и настройка ваших площадок. Ручное добавление площадок.
							<?php endif;?>
							</div>
						</div>
					<?php endif;?>
						<div class="panel_segment">
							<big><?=anchor('webmaster-panel/actions/tickets','Тикеты ('.$cntunit['tickets'].')');?></big>
							<img src="<?=$baseurl;?>images/panel_pic3.jpg">
							<div class="text">
								Система взаимодействия между клиентом и системой быстропост. Обсуждение вопросов по работе.
							</div>
						</div>
						<div class="panel_segment">
							<big><?=anchor('webmaster-panel/actions/mails','Почта (<b>'.$cntunit['mails']['new'].'</b>/'.$cntunit['mails']['total'].')',array('title'=>$cntunit['mails']['new'].' новых сообщений'));?></big>
							<img src="<?=$baseurl;?>images/panel_pic4.jpg">
							<div class="text">
								Приём писем от менеджера или администратора. Системные сообщения. Для отправки письма, используйте тикеты.
							</div>
						</div>
					<?php if(!$userinfo['locked'] && $cntunit['platforms']):?>
						<div class="panel_segment">
							<big><?=anchor('webmaster-panel/actions/services','Дополнительные услуги');?></big>
							<img src="<?=$baseurl;?>images/panel_pic5.jpg">
							<div class="text">
								Управление дополнительными услугами. Услугу можно подключить отдельно к каждой площадке.
							</div>
						</div>
					<?php endif;?>
					<?php if($userinfo['remote'] && !$userinfo['locked']):?>
						<div class="panel_segment">
							<big><?=anchor('webmaster-panel/actions/markets','Биржи ('.$cntunit['markets'].')');?></big>
							<img src="<?=$baseurl;?>images/panel_pic6.jpg">
							<div class="text">
								Управление аккаунтами бирж. Настройка и добавления новых логинов. Автоматический импорт сайтов.
							</div>
						</div>
					<?php endif;?>
					</div>
					<div class="clear"></div>
				</div>
			</div>
		</div>
	</div>
	<?php $this->load->view("clients_interface/includes/footer");?>
	<?php $this->load->view("clients_interface/includes/scripts");?>
</body>
</html>