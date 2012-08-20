<?=anchor('admin-panel/actions/profile','<i>Мой профиль</i>');?><span style="float:right;">(<?=$userinfo['ulogin'];?>)</span>
<hr style="margin: 5px 0;"/>
<div>
	Баланс: <span style="float:right;"><?=anchor('admin-panel/actions/balance','<i>'.$userinfo['balance'].' руб.</i>');?></span>
</div>