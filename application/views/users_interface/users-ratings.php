<!DOCTYPE html>
<html>
<?php $this->load->view("users_interface/includes/head");?>

<body>
	<div class="container">
		<?php $this->load->view("users_interface/includes/navigation");?>
		<?php $this->load->view("users_interface/includes/header");?>
		
		<div id="stable" class="row">
			<div class="span12">
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
					<p>
						Вы можете оставить свой отзыв для отображения на сайте на следующих форумах:
					</p>
					<p>
						<?=anchor('http://forum.searchengines.ru/','forum.searchengines.ru',array('class'=>'se'));?>
						<?=anchor('http://maultalk.com/','maultalk.com',array('class'=>'maultalk'));?>
					</p>
				</div>
				<?php for($i=0;$i<count($ratings);$i++):?>
				<div class="review_post">
					<div class="left avatar-wpapper">
						<img class="avatar" src="<?=$baseurl;?>ratings/viewimage/<?=$ratings[$i]['id'];?>" title="<?=$ratings[$i]['title'];?>" alt="<?=$ratings[$i]['title'];?>"/>
					</div>
					<div class="left comment-body">
						<p>
							<?=$ratings[$i]['text'];?> <?=anchor($this->uri->uri_string().'/reading-rating/'.$ratings[$i]['id'],'Читать полностью &raquo;');?>
						</p>
						<p>
							<?=$ratings[$i]['title'];?>
						</p>
					</div>
					<div class="clear"></div>
				</div>
				<?php endfor;?>
			</div>
		</div>
		<?php $this->load->view("users_interface/includes/social");?>
	</div>
	<?php $this->load->view("users_interface/includes/footer");?>
	<?php $this->load->view("users_interface/includes/scripts");?>
</body>
</html>