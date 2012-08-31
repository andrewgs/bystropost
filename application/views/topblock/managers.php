<div class="user-info">
	<?=$userinfo['ulogin'];?> <span style="float:right;"><?=anchor('manager-panel/actions/profile','Мой профиль', array('class' => 'underline'));?> &nbsp;<?=anchor('manager-panel/actions/logoff','Завершить сеанс', array('class' => 'underline'));?></span>
</div>
<div>
	Баланс: <span style="float:right;"><?=$userinfo['balance'];?> руб.</span>
</div>
