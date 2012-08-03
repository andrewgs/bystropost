<div id="navigation">
	<a href="<?=$baseurl;?>" id="logo"></a>
	<div id="top_links">
		<?=anchor("regulations","Правила");?> &nbsp;&nbsp;&nbsp;
		<?=anchor("support","Поддержка");?> &nbsp;&nbsp;&nbsp;
		<?=anchor("faq","FAQ");?>
	</div>
	<?php $this->load->view("forms/frmlogin");?>
	<div id="white_line">
		<span><i>Автоматизация работы в:</i></span>
		<table border=0>
			<tr>
				<td><img src="<?=$baseurl;?>images/markets/goget.jpg"><?=anchor('markets-catalog/gogetlinks','GoGetLinks');?></td>
				<td><img src="<?=$baseurl;?>images/markets/miralinks.jpg"><?=anchor('markets-catalog/miralinks','Miralinks');?></td>
				<td><img src="<?=$baseurl;?>images/markets/getgood.jpg"><?=anchor('markets-catalog/getgoodlinks','GetGoodLinks');?></td>
				<td><img src="<?=$baseurl;?>images/markets/blogcash.jpg"><?=anchor('markets-catalog/blogcash','blogcash');?></td>
			</tr>
			<tr>
				<td><img src="<?=$baseurl;?>images/markets/sape.jpg"><?=anchor('markets-catalog/pr-sape-ru','pr.sape.ru');?></td>
				<td><img src="<?=$baseurl;?>images/markets/blogun.jpg"><?=anchor('markets-catalog/blogun','blogun');?></td>
				<td><img src="<?=$baseurl;?>images/markets/rotapost.jpg"><?=anchor('markets-catalog/rotapost','rotapost');?></td>
			</tr>
		</table>
	</div>
</div>