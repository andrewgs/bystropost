<!DOCTYPE html>
<html>
<?php $this->load->view("users_interface/includes/head");?>

<body>
	<div class="container">
		<?php $this->load->view("users_interface/includes/navigation");?>
		<?php $this->load->view("users_interface/includes/header");?>
		
		<div class="row">
			<?=anchor('news','Все новости')?>
			<hr/>
			<h1><?=$event['title'];?></h1>
			<div class="news-date"><?=$event['date'];?></div>
			<img class="news-image" src="<?=$baseurl;?>news/viewimage/<?=$event['id'];?>" alt="" />
			<p><?=$event['announcement'];?></p>
			<p><?=$event['text'];?></p>
		</div>
		<?php $this->load->view("users_interface/includes/social");?>
	</div>
	<?php $this->load->view("users_interface/includes/footer");?>
	<?php $this->load->view("users_interface/includes/scripts");?>
</body>
</html>