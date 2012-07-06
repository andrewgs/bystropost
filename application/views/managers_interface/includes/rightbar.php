<div class="span3">
	<div class="well sidebar-nav">
		<ul class="nav nav-list">
			<li class="nav-header">Управление</li>
			<li num="users"><?=anchor('manager-panel/management/users/all','Пользователи');?></li>
			<li num="platforms"><?=anchor('manager-panel/management/platforms','Площадки');?></li>
			<li num="markets"><?=anchor('manager-panel/management/markets','Биржи');?></li>
			<li class="nav-header">Сообщения</li>
			<li num="support"><?=anchor('manager-panel/messages/support','Поддержка');?></li>
			<li num="private-messages"><?=anchor('manager-panel/messages/private-messages','Сообщения');?></li>
			<li num="system-message"><?=anchor('manager-panel/messages/system-message','Системное сообщение');?></li>
			<li num="tickets"><?=anchor('manager-panel/messages/tickets','Тикеты');?></li>
			<li class="nav-header">Действия</li>
			<li><?=anchor('','Главная');?></li>
			<li num="control"><?=anchor('manager-panel/actions/control','Панель управления');?></li>
			<li num="cabinet"><?=anchor('manager-panel/actions/cabinet','Личный кабинет');?></li>
		</ul>
	</div>
</div>