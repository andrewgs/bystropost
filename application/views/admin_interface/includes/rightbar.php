<div class="span3">
	<div class="well sidebar-nav">
		<ul class="nav nav-list">
			<li class="nav-header">Управление</li>
			<li num="users"><?=anchor('admin-panel/management/users/all','Пользователи');?></li>
			<li num="platforms"><?=anchor('admin-panel/management/platforms','Площадки');?></li>
			<li class="nav-header">Сообщения</li>
			<li num="support"><?=anchor('admin-panel/messages/support','Поддержка');?></li>
			<li num="tickets"><?=anchor('admin-panel/messages/tickets','	Тикеты');?></li>
			<li class="nav-header">Действия</li>
			<li><?=anchor('','Главная');?></li>
			<li num="control"><?=anchor('admin-panel/actions/control','Панель управления');?></li>
			<li num="cabinet"><?=anchor('admin-panel/actions/cabinet','Личный кабинет');?></li>
		</ul>
	</div>
</div>