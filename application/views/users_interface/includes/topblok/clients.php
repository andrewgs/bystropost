<div class="top5"><?=anchor('users/cabinet','Личный кабинет');?><span style="float:right;">(<?=anchor('users/logoff','выход');?>)</span></div>
<div class="top5">Баланс: <span style="float:right;"><?=anchor('users/balance','- 226.00 руб.');?></span></div>
<div class="top5">
	<hr/>
	Заявки:
	<span style="float:right;">Всего: <?=anchor('users/orders/total','14');?></span><br/>
	<span style="float:right;">Оплаченые: <?=anchor('users/orders/paid','11');?></span>
</div>