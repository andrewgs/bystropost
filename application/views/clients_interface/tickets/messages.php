<!DOCTYPE html>
<html lang="en">
<?php $this->load->view("clients_interface/includes/head");?>

<body>
	<?php $this->load->view("clients_interface/includes/header");?>
	<div class="container">
		<div class="row">
			<div class="span9">
				<ul class="breadcrumb">
					<li><?=anchor($this->session->userdata('backpath'),'<i class="icon-arrow-left"></i>&nbsp;&nbsp;Вернуться к списку');?></li>
					<li class="pull-right muted">
						Тикет №<?=$this->uri->segment(5);?>. Статус <?=(!$ticket['status'])?'<span class="label label-success" style="font-size: 13px;">ОТКРЫТ</span>':'<span class="label label-inverse" style="font-size: 13px;">ЗАКРЫТ</span>'?>
					</li>
				</ul>
				<div class="clear"></div>
				<?php $this->load->view("alert_messages/alert-error");?>
				<?php $this->load->view("alert_messages/alert-success");?>
				<div class="well">
					<div class="page-header">
						<h2 style="margin:0;">Тема: <small><?=$ticket['title'];?></small></h2>
					</div>
					<div class="pull-left" title="<?=$ticket['message']['position'];?>">
						<?=$ticket['message']['ico'];?>
					</div>
					<div class="media-body">
						<div class="media">
							<?=$ticket['message']['text'];?>
						</div>
						<div style="height:15px;"></div>
						<div class="pull-right muted">
							Срочность:
						<?php if($ticket['importance'] == 1):?>
							<span class="label">НИЗКАЯ</span>
						<?php elseif($ticket['importance'] == 2):?>
							<span class="label label-warning">СРЕДНЯЯ</span>
						<?php elseif($ticket['importance'] == 3):?>
							<span class="label label-important">КРИТИЧНАЯ</span>
						<?php endif;?>
							<span class="label label-info"><?=$ticket['message']['date'];?></span>
						</div>
					</div>
					<div class="clear"></div>
				</div>
				<div class="clear"></div>
			<?php if(!$ticket['status']):?>
				<div class="span7 nomargin green-block">&nbsp;</div>
				<div class="span2">
					<button type="button" class="btn btn-info none BtnInsertMessage" id="InsMeg" ><i class="icon-comment icon-white"></i> Добавить ответ</button>
				</div>
				<div class="clear"></div>
				<div id="frmInsMessage" style="display:none; margin-top:15px;">
					<?php $this->load->view("forms/addtiketmessage")?>
				</div>
			<?php endif;?>
				<div class="media">
			<?php if($messages):?>
				<?php for($i=0;$i<count($messages);$i++):?>
					<div class="well">
						<div class="page-header">
							<span class="label pull-right"><?=$messages[$i]['date'];?></span>
							<h2 style="margin:0;">От: <small><?=$messages[$i]['email'];?></small></h2>
						</div>
						<div class="pull-left" title="<?=$messages[$i]['position'];?>">
							<?=$messages[$i]['ico'];?>
						</div>
						<div class="media-body">
							<div class="media">
								<?=$messages[$i]['text'];?>
							</div>
						</div>
						<div style="height:15px;"></div>
						<div class="pull-right rating_block">
					<?php if($this->user['uid'] == $messages[$i]['recipient']):?>
						<?php if(!$messages[$i]['rating']):?>
							<div class="badge pull-right">оцените ответ</div>
							<ul class="message_rating" data-message="<?=$messages[$i]['id'];?>">
								<li><a title="Очень плохо: оценка 1" href="#">1</a></li>
								<li><a title="Плохо: оценка 2" href="#">2</a></li>
								<li><a title="Средненько: оценка 3" href="#">3</a></li>
								<li><a title="Хорошо: оценка 4" href="#">4</a></li>
								<li><a title="Очень хорошо: оценка 5" href="#">5</a></li>
							</ul>
						<?php else:?>
						<?=($messages[$i]['rating']>3)?'<span class="label label-success">Ваша оценка: '.$messages[$i]['rating'].'</span>':'<span class="label label-inverse">Ваша оценка: '.$messages[$i]['rating'].'</span>'?>
						<?php endif;?>
					<?php endif;?>
						</div>
						<div class="clear"></div>
					</div>
				<?php endfor;?>
			<?php else:?>
					<div class="well">
						<div class="pull-left">
							<img class="img-polaroid" src="<?=$baseurl;?>images/icons/no-mail.png" alt="" />
						</div>
						<div class="media-body">
							<div class="media">
								<h1>Пока ответов нет</h1>
							</div>
						</div>
					</div>
			<?php endif;?>
				</div>
				<div class="clear"></div>
				<div style="height:15px;"></div>
			<?php if($pages): ?>
				<?=$pages;?>
			<?php endif;?>
			</div>
			<?php $this->load->view("clients_interface/includes/rightbar");?>
		</div>
	</div>
	<?php $this->load->view("clients_interface/includes/footer");?>
	<?php $this->load->view("clients_interface/includes/scripts");?>
	<script type="text/javascript" src="<?=$baseurl;?>javascript/rating/jquery.color.js"></script>
	<script type="text/javascript">
		$(document).ready(function(){
			var animationTime = 200;
			var colours = ["bd2c33", "e49420", "ecdb00", "3bad54", "1b7db9"];
			var colourizeRatings = function(msgRtBlock,nrOfRatings){$(msgRtBlock).find('a').each(function(){if($(this).parent().index() <= nrOfRatings) {
				$(this).stop().animate({ backgroundColor : "#" + colours[nrOfRatings] } , animationTime);}});
			};
			$(".message_rating li a").hover(function(){
				colourizeRatings($(this).parents("ul"),$(this).parent().index());
				},function(){$(".message_rating li a").stop().animate({ backgroundColor : "#999" } , animationTime);
			});
			$(".message_rating li a").click(function(event){
				event.preventDefault();
				var RtBlock = $(this).parents('.rating_block');
				var msgID = $(this).parents('ul').attr('data-message'); var msgRaring = $(this).parent().index()+1;
				$.post("<?=$baseurl;?>webmaster-panel/actions/tickets/rating-ticket-message",
					{'message_id':msgID,'message_rating':msgRaring},
					function(data){if(data.status){$(RtBlock).html(data.newlink);}},"json");
			});
			$(".SubmitMessage").click(function(event){
				if($("#closeTicket:checked").size() == 1) return true;
				var parentFrom = $(this).parents("form");
				var err = false; $(parentFrom).find(".control-group").removeClass('error');$(parentFrom).find(".help-inline").hide();
				$(parentFrom).find(".input-valid").each(function(i,element){if($(this).val()==''){$(this).parents(".control-group").addClass('error');$(this).attr('data-original-title','Поле не должно быть пустым').tooltip('show');err = true;}});
				if(err){event.preventDefault();}
			});
			$(".BtnInsertMessage").click(function(){$("#frmInsMessage").slideToggle(200); $("#InsMeg").button('toggle'); $(".input-valid").tooltip('destroy');});
		});
	</script>
</body>
</html>