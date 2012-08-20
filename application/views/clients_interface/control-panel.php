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
				<div style="float:right;">
					<?=anchor('webmaster-panel/actions/logoff','Завершить сеанс');?>
				</div>
				<div class="clear"></div>
			<?php if($userinfo['locked']):?>
				<div class="alert alert-error">
					<center>Перед началом работы в системе Bystropost необходимо пополнить баланс минимум на 500 рублей.<br/>После пополнения, Вам будут доступны дополнительные разделы.<br/><?=anchor('webmaster-panel/actions/balance','Пополнить баланс');?></center>
				</div>
				<div class="clear"></div>
			<?php endif;?>
				<div id="stable">
					<div id="panel_segments">
					<?php if($cntunit['delivers']['total']):?>
						<div class="panel_segment">
							<big><?=anchor('webmaster-panel/actions/finished-jobs','Готовые задания (<font color="#0000ff"><b>'.$cntunit['delivers']['notpaid'].'</b></font>/'.$cntunit['delivers']['total'].')',array('title'=>$cntunit['delivers']['notpaid'].' не оплаченных'));?></big>
							<img src="<?=$baseurl;?>images/panel_pic1.jpg">
							<div class="text">
								Перед началом продвижения сайта мы тщательно исследуем как сам сайт, так и рыночный спрос в интересующей
							</div>
						</div>
					<?php endif;?>
					<?php if((($cntunit['platforms'] || $cntunit['markets']) && $userinfo['remote']) || (!$userinfo['locked'] && !$userinfo['remote'])):?>
						<div class="panel_segment">
							<big><?=anchor('webmaster-panel/actions/platforms','Площадки ('.$cntunit['platforms'].')');?></big>
							<img src="<?=$baseurl;?>images/panel_pic2.jpg">
							<div class="text">
								Перед началом продвижения сайта мы тщательно исследуем как сам сайт, так и рыночный спрос в интересующей
							</div>
						</div>
					<?php endif;?>
						<div class="panel_segment">
							<big><?=anchor('webmaster-panel/actions/tickets','Тикеты ('.$cntunit['tickets'].')');?></big>
							<img src="<?=$baseurl;?>images/panel_pic3.jpg">
							<div class="text">
								Перед началом продвижения сайта мы тщательно исследуем как сам сайт, так и рыночный спрос в интересующей
							</div>
						</div>
						<div class="panel_segment">
						<?php if($cntunit['mails']['new']):?>
							<big><?=anchor('webmaster-panel/actions/mails','Почта (<font color="#0000ff"><b>'.$cntunit['mails']['new'].'</b></font>/'.$cntunit['mails']['total'].')',array('title'=>$cntunit['mails']['new'].' новых сообщений'));?></big>
						<?php else:?>
							<big><?=anchor('webmaster-panel/actions/mails','Почта ('.$cntunit['mails']['total'].')',array('title'=>'Новых сообщений нет'));?></big>
						<?php endif;?>
							<img src="<?=$baseurl;?>images/panel_pic4.jpg">
							<div class="text">
								Перед началом продвижения сайта мы тщательно исследуем как сам сайт, так и рыночный спрос в интересующей
							</div>
						</div>
					<?php if(!$userinfo['locked'] && $cntunit['platforms']):?>
						<div class="panel_segment">
							<big><?=anchor('webmaster-panel/actions/services','Дополнительные услуги');?></big>
							<img src="<?=$baseurl;?>images/panel_pic5.jpg">
							<div class="text">
								Перед началом продвижения сайта мы тщательно исследуем как сам сайт, так и рыночный спрос в интересующей
							</div>
						</div>
					<?php endif;?>
					<?php if($userinfo['remote'] && !$userinfo['locked']):?>
						<div class="panel_segment">
							<big><?=anchor('webmaster-panel/actions/markets','Биржи');?></big>
							<img src="<?=$baseurl;?>images/panel_pic6.jpg">
							<div class="text">
								Перед началом продвижения сайта мы тщательно исследуем как сам сайт, так и рыночный спрос в интересующей
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