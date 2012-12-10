<div id="main-nav-wrapper" class="row">
	<div class="span12">
		<ul id="main-nav">
		<?php $none = FALSE;?>
		<?php if($this->session->userdata('logon')):?>
			<?php $none = TRUE;?>
		<?php endif;?>
			<li><?=anchor('','Главная',array('class'=>($none)?'none':'mnav'));?></li>
			<li><?=anchor('webmasters','Вебмастерам',array('class'=>($none)?'none':'mnav'));?></li>
			<!--<li><?=anchor('optimizers','Оптимизаторам',array('class'=>($none)?'none':'mnav'));?></li>-->
			<li><?=anchor('partners-program','Партнерская программа',array('class'=>($none)?'none':'mnav'));?></li>
		
			<li><?=anchor('prices','Цены');?></li>
			<div class="banner-gogetlinks">
				<a rel="nofollow" target="_blank" href="http://gogetlinks.net/?inv=gdjd3t"><img src="<?=$baseurl;?>images/banner.png" alt="GoGetLinks" /></a>
			</div>
		</ul>
	</div>
	<div class="span12" id="white_line">
		<span>Автоматизация работы в:</span>
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
				<td><?=anchor('#','и другие ...',array('class'=>'none'));?></td>
			</tr>
		</table>
	</div>
</div>