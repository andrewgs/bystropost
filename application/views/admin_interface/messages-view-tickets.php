<!DOCTYPE html>
<html lang="en">
<?php $this->load->view('admin_interface/includes/head');?>
<body>
	<?php $this->load->view('admin_interface/includes/header');?>
	<div class="container">
		<div class="row">
			<div class="span9">
				<ul class="breadcrumb">
					<li class="active">
						<?=anchor('admin-panel/messages/tickets','Тикеты');?> <span class="divider">/</span>
					</li>
					<li tnum="deactive">
						<?=anchor($this->uri->uri_string(),$tktitle);?>
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
							<td class="w195"><b><u><?=$tkmsgs[$i]['position'];?></u></b><br/><?=$tkmsgs[$i]['fio'];?><br/><i><b><?=$tkmsgs[$i]['login'];?></b></i><br/><?=$tkmsgs[$i]['date'];?></td>
						<?php if(($tkmsgs[$i]['recipient'] == $userinfo['uid']) OR !$tkmsgs[$i]['recipient']):?>
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
								<div id="params<?=$i;?>" style="display:none" data-mid="<?=$tkmsgs[$i]['id'];?>" data-uid="<?=$tkmsgs[$i]['uid'];?>" data-fio="<?=$tkmsgs[$i]['fio'];?>" data-login="<?=$tkmsgs[$i]['login'];?>"></div>
							<?php if($tkmsgs[$i]['owner'] == $tkmsgs[$i]['sender']):?>	
								<a class="btn btn-info mailUser" data-param="<?=$i;?>" data-toggle="modal" href="#mailUser" title="Ответить на сообщение"><nobr>&nbsp;&nbsp;<i class="icon-envelope icon-white"></i>&nbsp;&nbsp;</nobr></a>
							<?php else:?>
								<a class="btn btn-danger deleteMail" data-param="<?=$i;?>" data-toggle="modal" href="#deleteMail" title="Удалить сообщение"><nobr>&nbsp;&nbsp;<i class="icon-trash icon-white"></i>&nbsp;&nbsp;</nobr></a>
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
		<?php $this->load->view('admin_interface/includes/rightbar');?>
		<?php $this->load->view('admin_interface/modal/admin-mail-users');?>
		<?php $this->load->view('admin_interface/modal/admin-delete-mail');?>
		</div>
	</div>
	<?php $this->load->view('admin_interface/includes/scripts');?>
	<script type="text/javascript">
		$(document).ready(function(){
			var mID = 0;
			$("td[data-incoming='incoming']").each(function(e){
				$(this).addClass('alert alert-info'); $(this).siblings('td').addClass('alert alert-info');
			});
			var mID = 0;
			$(".mailUser").click(function(){
				var Param = $(this).attr('data-param'); mID = $("div[id = params"+Param+"]").attr("data-mid");uID = $("div[id = params"+Param+"]").attr("data-uid");
				var	uFIO = $("div[id = params"+Param+"]").attr("data-fio"); var	uLogin = $("div[id = params"+Param+"]").attr("data-login");
				$(".idMail").val(mID);$(".idUser").val(uID);$(".eFio").val(uFIO);$(".eLogin").val(uLogin);
			});
			$("#mtsend").click(function(event){
				var err = false;
				$(".control-group").removeClass('error');
				$(".help-inline").hide();
				if($("#mailText").val() == ''){
					$("#mailText").parents(".control-group").addClass('error');
					$("#mailText").siblings(".help-inline").html("Поле не может быть пустым").show();
					event.preventDefault();
				}
			});
			
			$(".deleteMail").click(function(){var Param = $(this).attr('data-param'); mID = $("div[id = params"+Param+"]").attr("data-mid");});
			$("#DelMail").click(function(){location.href='<?=$baseurl;?>admin-panel/messages/tickets/delete-mail/mail-id/'+mID;});
		});
	</script>
</body>
</html>