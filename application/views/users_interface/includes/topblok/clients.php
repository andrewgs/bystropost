<div class="top5"><?=anchor('users/cabinet','Панель управления');?><span style="float:right;">(<?=anchor('users/logoff','выход');?>)</span></div>
<div class="top5">Баланс: <span style="float:right;"><?=anchor('users/balance',$userinfo['balance'].' руб.');?></span></div>
<div class="top5">
	<hr/>
	Заявки:
	<span style="float:right;">Всего: <?=anchor('users/orders/total',$userinfo['torders']);?></span><br/>
	<span style="float:right;">Оплаченые: <?=anchor('users/orders/paid',$userinfo['uporders']);?></span>
</div>