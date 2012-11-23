<div class="user-info">
	<?=$userinfo['ulogin'];?> <span style="float:right;"><?=anchor('webmaster-panel/actions/profile','Мой профиль', array('class' => 'underline'));?> &nbsp;<?=anchor('webmaster-panel/actions/logoff','Завершить сеанс', array('class' => 'underline'));?></span>
</div>
<div>
	Баланс:
	<span style="float:right;"><?=anchor('webmaster-panel/actions/balance/payment-history',$userinfo['balance'].' руб.');?></span><br/>
	<span style="float:right;font-size:80%;"><?=anchor('webmaster-panel/actions/balance','[пополнить баланс]');?></span>
</div>
<div style="margin-top:20px;">
	<span style="float:right;">Всего сделано заявок: <?=anchor('webmaster-panel/actions/finished-jobs',$cntunit['delivers']['total']);?></span><br/>
	<span style="float:right;">Из них не оплачено: <?=anchor('webmaster-panel/actions/finished-jobs',$cntunit['delivers']['notpaid']);?>, на сумму <?=$cntunit['delivers']['totalsum'];?> руб. </span>
</div><br/>
