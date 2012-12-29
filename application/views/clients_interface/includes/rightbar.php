<div class="span3">
	<div class="well sidebar-nav">
		<ul class="nav nav-list">
			<li class="nav-header">Управление</li>
		<?php if($cntunit['delivers']['total']):?>
			<li num="finished-jobs"><?=anchor('webmaster-panel/actions/finished-jobs','Задания (<b>'.$cntunit['delivers']['notpaid'].'</b>/'.$cntunit['delivers']['total'].')');?></li>
		<?php endif;?>
		<?php if((($cntunit['platforms'] || $cntunit['markets']) && $userinfo['remote']) || (!$userinfo['locked'] && !$userinfo['remote'])):?>
			<li num="platforms"><?=anchor('webmaster-panel/actions/platforms','Площадки ('.$cntunit['platforms'].')');?></li>
		<?php endif;?>
		<?php if($userinfo['remote'] && !$userinfo['locked']):?>
			<li num="markets"><?=anchor('webmaster-panel/actions/markets','Биржи ('.$cntunit['markets'].')');?></li>
		<?php endif;?>
		<?php if(!$userinfo['locked'] && $cntunit['platforms']):?>
			<li num="services"><?=anchor('webmaster-panel/actions/services','Дополнительные услуги');?></li>
		<?php endif;?>
			<!--<li num="forum"><?=anchor('#','Форум');?></li>-->
			<li class="nav-header">Связь</li>
			<li num="tickets-outbox"><?=anchor('webmaster-panel/actions/tickets-outbox','Исходящие тикеты ('.$cntunit['tickets_outbox'].')');?></li>
			<li num="tickets-inbox"><?=anchor('webmaster-panel/actions/tickets-inbox','Входящие тикеты ('.$cntunit['tickets_inbox'].')');?></li>
			<li num="mails"><?=anchor('webmaster-panel/actions/mails','Почта (<b>'.$cntunit['mails']['new'].'</b>/'.$cntunit['mails']['total'].')');?></li>
			<li class="nav-header">Финансы</li>
			<li num="partner-program"><?=anchor('webmaster-panel/actions/partner-program','Партнерская программа');?></li>
			<li num="balance"><?=anchor('webmaster-panel/actions/balance','Баланс');?></li>
		</ul>
	</div>
</div>