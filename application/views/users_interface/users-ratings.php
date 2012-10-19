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
						<?=anchor('http://forum.searchengines.ru/showthread.php?t=628588','Forum.Searchengines.ru',array('class'=>'se'));?>
						<?=anchor('http://www.maultalk.com/topic88216.html','Maultalk.com',array('class'=>'maultalk'));?>
						<?=anchor('http://mastertalk.ru/discussion/145163/monetizaciya-vashih-saytov-gogetlinksmiralinksrotapost-etc','MasterTalk.ru',array('class'=>'mastertalk'));?>
						<?=anchor('http://webmasters.ru/forum/f114/rabotayu-za-vas-v-gogetlinks-advego-prosto-poluchai-passivnyi-dohod-13860/','WebMasters.ru',array('class'=>'webmasters'));?>
					</p>
				</div>
				<div class="span5">
					<?php for($i=0;$i < round(count($ratings)/2);$i++):?>
					<div class="review_post">
						<div class="left avatar-wpapper">
							<img class="avatar" src="<?=$baseurl;?>ratings/viewimage/<?=$ratings[$i]['id'];?>" title="<?=$ratings[$i]['title'];?>" alt="<?=$ratings[$i]['title'];?>"/>
						</div>
						<div class="comment-body">
							<p>
								<span class="comment-title"><?=$ratings[$i]['title'];?></span><br />
								<?= mb_substr($ratings[$i]['text'],0,450);?><br />
							<?php if(!empty($ratings[$i]['resource'])):?>
								<?=anchor($ratings[$i]['resource'],'Читать полностью &raquo;',array('target'=>'_blank'));?>
							<?php endif;?>
							</p>
						</div>
					</div>		
					<div class="clear"> </div>
					<?php endfor;?>		
				</div>
				<div class="span5">
					<?php for($i=round(count($ratings)/2); $i < count($ratings);$i++):?>
					<div class="review_post">
						<div class="left avatar-wpapper">
							<img class="avatar" src="<?=$baseurl;?>ratings/viewimage/<?=$ratings[$i]['id'];?>" title="<?=$ratings[$i]['title'];?>" alt="<?=$ratings[$i]['title'];?>"/>
						</div>
						<div class="comment-body">
							<p>
								<span class="comment-title"><?=$ratings[$i]['title'];?></span><br />
								<?= mb_substr($ratings[$i]['text'],0,450);?><br /> 
							<?php if(!empty($ratings[$i]['resource'])):?>
								<?=anchor($ratings[$i]['resource'],'Читать полностью &raquo;',array('target'=>'_blank'));?>
							<?php endif;?>
							</p>
						</div>
					</div>		
					<div class="clear"> </div>
					<?php endfor;?>
				</div>
			</div>
		</div>
		<?php $this->load->view("users_interface/includes/social");?>
	</div>
	<?php $this->load->view("users_interface/includes/footer");?>
	<?php $this->load->view("users_interface/includes/scripts");?>
</body>
</html>