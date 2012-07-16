<!DOCTYPE html>
<html>
<?php $this->load->view("users_interface/includes/head");?>

<body>
	<?php $this->load->view("users_interface/includes/header");?>
	<div id="main">
		<?php $this->load->view("users_interface/includes/navigation");?>
		<div id="stable" style="margin-top:180px;width: 1005px;">
			<h1 class="whiteline">Отзывы о системе</h1>
			<div id="who_reviews">
			<?php if($this->uri->segment(2) == 'advertisers'):?>
				<?=anchor('users-ratings/advertisers','Отзывы от рекламодателей',array('class'=>'active'));?>
				<?=anchor('users-ratings/webmasters','Отзывы от вебмастеров');?>
			<?php else:?>
				<?=anchor('users-ratings/advertisers','Отзывы от рекламодателей');?>
				<?=anchor('users-ratings/webmasters','Отзывы от вебмастеров',array('class'=>'active'));?>
			<?php endif;?>
			</div>
			<div id="where_reviews">
				Отзывы на:
				<p>
					<?=anchor('http://forum.searchengines.ru/','forum.searchengines.ru',array('class'=>'se'));?>
					<?=anchor('http://maultalk.com/','maultalk.com',array('class'=>'maultalk'));?>
					Оставить отзыв для отображения на сайте,можно через форумы выше
				</p>
			</div>
			<div class="review_post" style="width:875px;">
				<span class="avatar">
					<img src="<?=$baseurl;?>ratings/viewimage/<?=$rating['id'];?>" title="<?=$rating['title'];?>" alt="<?=$rating['title'];?>"/>
				</span>
				<i><?=$rating['title'];?></i>
				<br/>
				<?=$rating['text'];?>
			</div>
		</div>
	</div>
	<?php $this->load->view("users_interface/includes/footer");?>
	<?php $this->load->view("users_interface/includes/scripts");?>
</body>
</html>