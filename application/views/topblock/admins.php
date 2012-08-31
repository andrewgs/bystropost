<div class="user-info">
	<?=$userinfo['ulogin'];?> <span style="float:right;"><?=anchor('admin-panel/actions/profile','Мой профиль', array('class' => 'underline'));?> &nbsp;<?=anchor('admin-panel/actions/logoff','Завершить сеанс', array('class' => 'underline'));?></span>
</div>
<div>
	Баланс: <span style="float:right;"><?=anchor('admin-panel/actions/balance',$userinfo['balance'].' руб.');?></span>
</div>