<!DOCTYPE html>
<html>
<?php $this->load->view("users_interface/includes/head");?>

<body>
	<div class="container">
		<?php $this->load->view("users_interface/includes/navigation");?>
		<?php $this->load->view("users_interface/includes/header");?>
		
		<div class="row">
			<div class="span12">
				<?=anchor('news','&laquo; Список всех новостей')?>
				<h1 class="news-title"><?=$event['title'];?></h1>
				<div class="news-date"><?=$event['date'];?></div>
				<?php if(!$event['noimage']):?>
				<img class="news-image" src="<?=$baseurl;?>news/viewimage/<?=$event['id'];?>" alt="" />
				<?php endif;?>
				<p><?=$event['announcement'];?></p>
				<p><?=$event['text'];?></p>
			</div>
		</div>
		
		<div id="news-social-activity" class="row">
			<div class="span12">
				<script src="//pip.qip.ru/js/pip_button.js" type="text/javascript" charset="utf-8" data-type="1"></script>
				<div id="fb-root"></div>
				<script>(function(d, s, id) {
				  var js, fjs = d.getElementsByTagName(s)[0];
				  if (d.getElementById(id)) return;
				  js = d.createElement(s); js.id = id;
				  js.src = "//connect.facebook.net/ru_RU/all.js#xfbml=1";
				  fjs.parentNode.insertBefore(js, fjs);
				}(document, 'script', 'facebook-jssdk'));</script>
				<div class="fb-comments" data-href="http://bystropost.ru" data-num-posts="2" data-width="470"></div>
			</div>			
		</div>
		
		<?php $this->load->view("users_interface/includes/social");?>
	</div>
	<?php $this->load->view("users_interface/includes/footer");?>
	<?php $this->load->view("users_interface/includes/scripts");?>
</body>
</html>