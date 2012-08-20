<div id="navigation">
	<a href="<?=$baseurl;?>" id="logo"></a>
	<div id="top_links">
		<?=anchor("support","Поддержка");?> &nbsp;&nbsp;&nbsp;
		<?=anchor("faq","FAQ");?> &nbsp;&nbsp;&nbsp;
		<?=anchor('users-ratings/advertisers','Отзывы');?>
	</div>
	<?php $this->load->view("forms/frmlogin");?>
	<div id="white_line">
		<span><i>Автоматизация работы в:</i></span>
		<table border=0>
			<tr>
				<td><img src="<?=$baseurl;?>images/markets/goget.jpg"><?=anchor('markets-catalog/gogetlinks','gogetlinks');?></td>
				<td><img src="<?=$baseurl;?>images/markets/miralinks.jpg"><?=anchor('markets-catalog/miralinks','miralinks');?></td>
				<td><img src="<?=$baseurl;?>images/markets/getgood.jpg"><?=anchor('markets-catalog/getgoodlinks','getgoodlinks');?></td>
				<td><img src="<?=$baseurl;?>images/markets/blogcash.jpg"><?=anchor('markets-catalog/blogocash','blogocash');?></td>
			</tr>
			<tr>
				<td><img src="<?=$baseurl;?>images/markets/sape.jpg"><?=anchor('markets-catalog/prsape','prsape');?></td>
				<td><img src="<?=$baseurl;?>images/markets/blogun.jpg"><?=anchor('markets-catalog/blogun','blogun');?></td>
				<td><img src="<?=$baseurl;?>images/markets/rotapost.jpg"><?=anchor('markets-catalog/rotapost','rotapost');?></td>
				<td>Другие...</td>
			</tr>
		</table>
	</div>
</div>