<!DOCTYPE html>
<html lang="en">
<?php $this->load->view('managers_interface/includes/head');?>
<body>
	<?php $this->load->view('managers_interface/includes/header');?>
	<div class="container">
		<div class="row">
			<div class="span9">
				<ul class="breadcrumb">
					<li class="active">
						<?=anchor('manager-panel/actions/tickets/inbox','Входящие тикеты');?> <span class="divider">/</span>
					</li>
					<li tnum="deactive">
						<?=anchor($this->uri->uri_string(),$ticket['title'].' (<i><b>'.$ticket['url'].'</b></i>)');?>
					</li>
				</ul>
				<?php $this->load->view('alert_messages/alert-error');?>
				<?php $this->load->view('alert_messages/alert-success');?>
				<table class="table table-bordered">
					<thead>
						<tr>
							<th><center>ID</center></th>
							<th><center>Отправитель</center></th>
							<th><center>Название</center></th>
							<th><center>Действия</center></th>
						</tr>
					</thead>
					<tbody>
					<?php for($i=0;$i<count($tkmsgs);$i++):?>
						<tr class="align-center">
							<td class="w50" style="text-align:center; vertical-align:middle;"><?=$tkmsgs[$i]['id'];?></td>
							<td class="w100" style="text-align:center; vertical-align:middle;">
								<b><u><?=$tkmsgs[$i]['position'];?></u></b><br/><?=$tkmsgs[$i]['date'];?>
							</td>
						<?php if($tkmsgs[$i]['sender'] != $userinfo['uid']):?>
							<td class="w400" data-incoming="incoming">
						<?php else:?>
							<td class="w400">
						<?php endif;?>
							<?php if($tkmsgs[$i]['reply']):?>
								<i><strong>Ответ на сообщение: №<?=$tkmsgs[$i]['reply'];?></strong></i><br/>
							<?php endif;?>
								<?=$tkmsgs[$i]['text'];?>
							</td>
							<td class="w50" style="text-align:center; vertical-align:middle;">
								<div id="params<?=$i;?>" style="display:none" data-mid="<?=$tkmsgs[$i]['id'];?>" data-uid="<?=$tkmsgs[$i]['sender'];?>" data-position="<?=$tkmsgs[$i]['position'];?>"></div>
							<?php if($tkmsgs[$i]['sender'] != $userinfo['uid']):?>
								<a class="btn btn-info mailTicket" data-param="<?=$i;?>" data-toggle="modal" href="#mailTicket" title="Ответить"><nobr>&nbsp;&nbsp;<i class="icon-envelope icon-white"></i>&nbsp;&nbsp;</nobr></a>
							<?php endif;?>
							</td>
						</tr>
					<?php endfor; ?>
					</tbody>
				</table>
				<?php if($pages): ?>
					<?=$pages;?>
				<?php endif;?>
			</div>
		<?php $this->load->view('managers_interface/includes/rightbar');?>
		<?php $this->load->view('managers_interface/modal/manager-ticket-message');?>
		<?php $this->load->view('admin_interface/modal/admin-delete-mail');?>
		</div>
	</div>
	<?php $this->load->view('admin_interface/includes/scripts');?>
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