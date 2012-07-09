<?=anchor('webmaster-panel/actions/profile','<i>Мой профиль</i>');?><span style="float:right;">(<?=$userinfo['ulogin'];?>)</span>
<hr style="margin: 5px 0;"/>
<div>
	Баланс: <span style="float:right;"><?=anchor('users/balance','<i>'.$userinfo['balance'].' руб.</i>');?></span>
</div>
<div style="margin-top:10px;">
	Заявки:
	<span style="float:right;">Всего: <b><?=anchor('users/orders/total',$userinfo['torders']);?></b></span><br/>
	<span style="float:right;">Оплаченые: <b><?=anchor('users/orders/paid',$userinfo['uporders']);?></b></span>
</div><br/>
