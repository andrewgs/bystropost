<div id="navigation">
	<a href="<?=$baseurl;?>" id="logo"></a>
	<div id="top_links">
		<?=anchor("regulations","Правила");?> &nbsp;&nbsp;&nbsp;
		<?=anchor("support","Поддержка");?> &nbsp;&nbsp;&nbsp;
		<?=anchor("faq","FAQ");?>
	</div>
	<?php if(!$loginstatus):?>
		<?php $this->load->view("forms/frmlogin");?>
	<?php else:?>
		<div class="UserPanel">
			<div class="top_status">
				<div class="top5"><?=anchor('users/cabinet','vkharseev@gmail.com');?><span style="float:right;">(<?=anchor('users/logoff','выход');?>)</span></div>
				<div class="top5">Баланс: <span style="float:right;"><?=anchor('users/balance','- 226.00 руб.');?></span></div>
				<div class="top5">
					<hr/>
					Заявки:
					<span style="float:right;">Всего: <?=anchor('users/orders/total','14');?></span><br/>
					<span style="float:right;">Оплаченые: <?=anchor('users/orders/paid','11');?></span>
				</div>
			</div>
		</div>
	<?php endif; ?>
	<div id="white_line">
		<span><i>Автоматизация работы в:</i></span>
		<table border=0>
			<tr>
				<td><img src="<?=$baseurl;?>images/markets/goget.jpg"><a href="http://GoGetLinks.ru/" target="_blank">GoGetLinks</a></td>
				<td><img src="<?=$baseurl;?>images/markets/miralinks.jpg"><a href="http://Miralinks.ru/" target="_blank">Miralinks</a></td>
				<td><img src="<?=$baseurl;?>images/markets/getgood.jpg"><a href="http://GetGoodLinks.ru/" target="_blank">GetGoodLinks</a></td>
				<td><img src="<?=$baseurl;?>images/markets/blogcash.jpg"><a href="http://blogcash.ru/" target="_blank">blogcash.ru</a></td>
			</tr>
			<tr>
				<td><img src="<?=$baseurl;?>images/markets/sape.jpg"><a href="http://pr.sape.ru/" target="_blank">pr.sape.ru</a></td>
				<td><img src="<?=$baseurl;?>images/markets/blogun.jpg"><a href="http://blogun.ru/" target="_blank">blogun.ru</a></td>
				<td><img src="<?=$baseurl;?>images/markets/rotapost.jpg"><a href="http://rotapost.ru/" target="_blank">rotapost.ru</a></td>
				<td><img src="<?=$baseurl;?>images/markets/other.jpg"><a href="#" target="_blank">и другие</a></td>
			</tr>
		</table>
	</div>
</div>