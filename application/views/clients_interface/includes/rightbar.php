<div class="span3">
	<div class="well sidebar-nav">
		<ul class="nav nav-list">
			<li class="nav-header">Меню</li>
		<?php if($cntunit['delivers']['total']):?>
			<li num="finished-jobs"><?=anchor('webmaster-panel/actions/finished-jobs','Задания (<b>'.$cntunit['delivers']['notpaid'].'</b>/'.$cntunit['delivers']['total'].')');?></li>
		<?php endif;?>
		<?php if((($cntunit['platforms'] || $cntunit['markets']) && $userinfo['remote']) || (!$userinfo['locked'] && !$userinfo['remote'])):?>
			<li num="platforms"><?=anchor('webmaster-panel/actions/platforms','Площадки ('.$cntunit['platforms'].')');?></li>
		<?php endif;?>
			<li num="mails"><?=anchor('webmaster-panel/actions/mails','Почта (<b>'.$cntunit['mails']['new'].'</b>/'.$cntunit['mails']['total'].')');?></li>
			<li num="tickets"><?=anchor('webmaster-panel/actions/tickets','Тикеты ('.$cntunit['tickets'].')');?></li>
		<?php if($userinfo['remote'] && !$userinfo['locked']):?>
			<li num="markets"><?=anchor('webmaster-panel/actions/markets','Биржи ('.$cntunit['markets'].')');?></li>
		<?php endif;?>
		<?php if(!$userinfo['locked'] && $cntunit['platforms']):?>
			<li num="services"><?=anchor('webmaster-panel/actions/services','Дополнительные услуги');?></li>
		<?php endif;?>
			<!--<li num="forum"><?=anchor('#','Форум');?></li>-->
			<li num="balance"><?=anchor('webmaster-panel/actions/balance','Баланс');?></li>
		</ul>
	</div>
</div>