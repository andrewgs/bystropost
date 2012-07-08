<div class="span3">
	<div class="well sidebar-nav">
		<ul class="nav nav-list">
			<li class="nav-header">Меню</li>
			<li num="already"><?=anchor('#','Готовые задания (0)');?></li>
			<li num="platforms"><?=anchor('webmaster-panel/actions/platforms','Площадки ('.$cntunit['platforms'].')');?></li>
			<li num="mails"><?=anchor('webmaster-panel/actions/mails','Почта ('.$cntunit['mails'].')');?></li>
			<li num="tickets"><?=anchor('webmaster-panel/actions/tickets','Тикеты ('.$cntunit['tickets'].')');?></li>
			<li num="servises"><?=anchor('#','Дополнительные услуги');?></li>
			<li num="forum"><?=anchor('#','Форум');?></li>
			<li num="profile"><?=anchor('webmaster-panel/actions/profile','Мой профиль');?></li>
			<li><?=anchor('webmaster-panel/actions/logoff','Завершить сеанс');?></li>
		</ul>
	</div>
</div>