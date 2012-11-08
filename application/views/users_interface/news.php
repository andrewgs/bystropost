<!DOCTYPE html>
<html>
<?php $this->load->view("users_interface/includes/head");?>

<body>
	<div class="container">
		<?php $this->load->view("users_interface/includes/navigation");?>
		<?php $this->load->view("users_interface/includes/header");?>
		
		<div class="row">
			<div class="span12">
				<h1>Новости</h1>
				<?php for($i=0;$i<count($events);$i++):?>
				<h2 class="news-title"><?=$events[$i]['title'];?></h2>
				<div class="news-date"><?=$events[$i]['date'];?></div>
				<?php if(!$events[$i]['noimage']):?>
				<img class="news-image" src="<?=$baseurl;?>news/viewimage/<?=$events[$i]['id'];?>" alt="" />
				<?php endif;?>
				<p class="news-annotation"><?=$events[$i]['announcement'];?></p>
				<?=anchor('news/view/'.$events[$i]['translit'],'Читать далее &rarr;')?>
				
				<div class="news-share">
					<a href="https://twitter.com/share" class="twitter-share-button" data-count="none" data-lang="ru" data-url="<?= base_url() ?>news/view/<?= $events[$i]['translit'] ?>" data-text="">Твитнуть</a>
					<script>
						! function(d, s, id) {
							var js, fjs = d.getElementsByTagName(s)[0];
							if (!d.getElementById(id)) {
								js = d.createElement(s);
								js.id = id;
								js.src = "https://platform.twitter.com/widgets.js";
								fjs.parentNode.insertBefore(js, fjs);
							}
						}(document, "script", "twitter-wjs");
					</script>
					<script src="//pip.qip.ru/js/pip_button.js" type="text/javascript" charset="utf-8" data-type="1"></script>
				</div>
				
				<?php endfor;?>
				<?php if($pages): ?>
				<?=$pages;?>
				<?php endif;?>
			</div>
		</div>
		<?php $this->load->view("users_interface/includes/social");?>
	</div>
	<?php $this->load->view("users_interface/includes/footer");?>
	<?php $this->load->view("users_interface/includes/scripts");?>
</body>
</html>