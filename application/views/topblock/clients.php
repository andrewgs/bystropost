<div class="user-info">
	<?=$userinfo['ulogin'];?> <span style="float:right;"><?=anchor('webmaster-panel/actions/profile','Мой профиль', array('class' => 'underline'));?> &nbsp;<?=anchor('webmaster-panel/actions/logoff','Завершить сеанс', array('class' => 'underline'));?></span>
</div>
<div>
	Баланс: <span style="float:right;"><?=anchor('webmaster-panel/actions/balance',$userinfo['balance'].' руб.');?></span>
</div>
<div style="margin-top:10px;">
	Заявки:
	<span style="float:right;">Всего: <?=anchor('webmaster-panel/actions/finished-jobs',$cntunit['delivers']['total']);?></span><br/>
	<span style="float:right;">Не оплаченые: <?=anchor('webmaster-panel/actions/finished-jobs',$cntunit['delivers']['notpaid']);?></span>
</div><br/>
