<?=anchor('webmaster-panel/actions/profile','<i>Мой профиль</i>');?><span style="float:right;">(<?=$userinfo['ulogin'];?>)</span>
<hr style="margin: 5px 0;"/>
<div>
	Баланс: <span style="float:right;"><?=anchor('users/balance','<i>'.$userinfo['balance'].' руб.</i>');?></span>
</div>
<div style="margin-top:10px;">
	Заявки:
	<span style="float:right;">Всего: <b><?=anchor('webmaster-panel/actions/finished-jobs',$cntunit['delivers']['total']);?></b></span><br/>
	<span style="float:right;">Не оплаченые: <b><?=anchor('webmaster-panel/actions/finished-jobs',$cntunit['delivers']['notpaid']);?></b></span>
</div><br/>
