<!DOCTYPE html>
<html>
<?php $this->load->view("users_interface/includes/head");?>

<body>
	<div class="container">
		<?php $this->load->view("users_interface/includes/navigation");?>
		<?php $this->load->view("users_interface/includes/header");?>
		
		<div class="row">
		<?php for($i=0;$i<count($events);$i++):?>
			<h1><?=$events[$i]['title'];?></h1>
			<div class="news-date"><?=$events[$i]['date'];?></div>
		<?php if(!$events[$i]['noimage']):?>
			<img class="news-image" src="<?=$baseurl;?>news/viewimage/<?=$events[$i]['id'];?>" alt="" />
		<?php endif;?>
			<p class="news-annotation"><?=$events[$i]['announcement'];?></p>
			<?=anchor('news/view/'.$events[$i]['translit'],'Читать далее &rarr;')?>
		<?php endfor;?>
		<?php if($pages): ?>
			<?=$pages;?>
		<?php endif;?>
		</div>
		<?php $this->load->view("users_interface/includes/social");?>
	</div>
	<?php $this->load->view("users_interface/includes/footer");?>
	<?php $this->load->view("users_interface/includes/scripts");?>
</body>
</html>