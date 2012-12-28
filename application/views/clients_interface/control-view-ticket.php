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
						Тикет №<?=$this->uri->segment(5);?>. Статус <span class="label label-success" style="font-size: 13px;"><?=($ticket['status'])?'ЗАКРЫТ':'ОТКРЫТ'?></span>
					</li>
				</ul>
				<div class="clear"></div>
				<?php $this->load->view("alert_messages/alert-error");?>
				<?php $this->load->view("alert_messages/alert-success");?>
				<div class="well">
					<div class="page-header">
						<span class="label label-info pull-right">Дата создания: <?=$ticket['message']['date'];?></span>
						<h2 style="margin:0;">Тема: <small><?=$ticket['title'];?></small></h2>
					</div>
					<div class="pull-left" title="<?=$ticket['message']['position'];?>">
						<?=$ticket['message']['ico'];?>
					</div>
					<div class="media-body">
						<div class="media">
							<?=$ticket['message']['text'];?>
						</div>
					</div>
				</div>
				<div class="clearfix"></div>
				<ul id="ActionTab" class="nav nav-tabs">
					<li class="active"><a href="#all-messages" data-toggle="tab"><strong>Сообщения</strong></a></li>
					<li><a href="#new-message" data-toggle="tab"><strong>Добавить сообщение</strong></a></li>
				</ul>
				<div id="ActinTabContent" class="tab-content">
					<div class="tab-pane fade in active" id="all-messages">
						<div class="media">
					<?php if($messages):?>
						<?php for($i=0;$i<count($messages);$i++):?>
							<div class="well">
								<div class="page-header">
									<span class="label label-info pull-right">Дата создания: <?=$ticket['message']['date'];?></span>
									<h2 style="margin:0;">Тема: <small><?=$ticket['title'];?></small></h2>
								</div>
								<div class="pull-left" title="<?=$ticket['message']['position'];?>">
									<?=$ticket['message']['ico'];?>
								</div>
								<div class="media-body">
									<div class="media">
										<?=$ticket['message']['text'];?>
									</div>
								</div>
							</div>
						<?php endfor;?>
					<?php else:?>
							<div class="pull-left" title="<?=$ticket['message']['position'];?>">
								<img class="img-polaroid" src="<?=$baseurl;?>images/icons/no-mail.png" alt="" />
							</div>
							<div class="media-body">
								<div class="media">
									<h1>Пока ответов нет</h1>
								</div>
							</div>
					<?php endif;?>
						</div>
					</div>
					<div class="tab-pane fade in" id="new-message">
						<?php $this->load->view("forms/addtiketmessage")?>
					</div>
				</div>
			<?php if($pages): ?>
				<?=$pages;?>
			<?php endif;?>
			</div>
			<?php $this->load->view("clients_interface/includes/rightbar");?>
			<?php $this->load->view('clients_interface/modal/clients-ticket-message');?>
		</div>
	</div>
	<?php $this->load->view("clients_interface/includes/footer");?>
	<?php $this->load->view("clients_interface/includes/scripts");?>
	<script type="text/javascript">
		$(document).ready(function(){
			var mID = 0;
			$("td[data-incoming='incoming']").each(function(e){
				$(this).addClass('alert alert-message'); $(this).siblings('td').addClass('alert alert-message');
			});
			$(".mailTicket").click(function(){
				var Param = $(this).attr('data-param'); mID = $("div[id = params"+Param+"]").attr("data-mid");uID = $("div[id = params"+Param+"]").attr("data-uid");
				var	uPosition = $("div[id = params"+Param+"]").attr("data-position");
				$(".idMail").val(mID);$(".idUser").val(uID);$("#ePosition").val(uPosition);
			});
			$("#mtsend").click(function(event){
				$(".control-group").removeClass('error');
				$(".help-inline").hide();
				if($("#mailText").val()=='' && $("#closeTicket").attr("checked") == undefined){
					$("#mailText").parents(".control-group").addClass('error');
					$("#mailText").siblings(".help-inline").html("Поле не может быть пустым").show();
					event.preventDefault();
				}
			});
		});
	</script>
</body>
</html>