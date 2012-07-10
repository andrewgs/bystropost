<div class="span3">
	<div class="well sidebar-nav">
		<ul class="nav nav-list">
			<li class="nav-header">Списки</li>
			<li num="users"><?=anchor('admin-panel/management/users/all','Список пользователей');?></li>
			<li num="platforms"><?=anchor('admin-panel/management/platforms','Список площадок');?></li>
			<li num="markets"><?=anchor('admin-panel/management/markets','Список биржи');?></li>
			<li class="nav-header">Сообщения</li>
		<?php if($cntunit['mails']):?>
			<li num="mails"><?=anchor('admin-panel/management/mails','Почта (<font color="#0000ff"><b>'.$cntunit['mails'].'</b></font>)');?></li>
		<?php else:?>
			<li num="private-messages"><?=anchor('admin-panel/management/mails','Почта');?></li>
		<?php endif;?>
			<li num="tickets"><?=anchor('admin-panel/messages/tickets','Тикеты');?></li>
			<li num="system-message"><?=anchor('admin-panel/messages/system-message','Рассылка');?></li>
			<li class="nav-header">Действия</li>
			<li num="balance"><?=anchor('admin-panel/actions/balance','Баланс');?></li>
			<li num="profile"><?=anchor('admin-panel/actions/profile','Профиль');?></li>
			<li num="forum"><?=anchor('admin-panel/actions/forum','Форум');?></li>
			<li><?=anchor('admin-panel/actions/logoff','Завершить сеанс');?></li>
		</ul>
	</div>
</div>