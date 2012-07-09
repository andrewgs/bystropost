<div class="span3">
	<div class="well sidebar-nav">
		<ul class="nav nav-list">
			<li class="nav-header">Меню</li>
			<li num="already"><?=anchor('#','Задания (0)');?></li>
			<li num="platforms"><?=anchor('manager-panel/actions/platforms','Площадки ('.$cntunit['platforms'].')');?></li>
			<li num="mails"><?=anchor('manager-panel/actions/mails','Почта ('.$cntunit['mails'].')');?></li>
			<li num="tickets"><?=anchor('manager-panel/actions/tickets','Тикеты ('.$cntunit['tickets'].')');?></li>
			<li num="servises"><?=anchor('#','Дополнительные услуги');?></li>
			<li num="forum"><?=anchor('#','Форум');?></li>
			<li num="profile"><?=anchor('manager-panel/actions/profile','Мой профиль');?></li>
			<li><?=anchor('manager-panel/actions/logoff','Завершить сеанс');?></li>
		</ul>
	</div>
</div>