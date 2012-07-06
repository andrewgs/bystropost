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
						<?=anchor('admin-panel/messages/private-messages','Личные сообщения');?>
					</li>
				</ul>
				<?php $this->load->view('alert_messages/alert-error');?>
				<?php $this->load->view('alert_messages/alert-success');?>
				<table class="table table-bordered">
					<thead>
						<tr>
							<th><center>Отправитель</center></th>
							<th><center><nobr>Текс сообщения</nobr></center></th>
							<th><center>Действия</center></th>
						</tr>
					</thead>
					<tbody>
					<?php for($i=0;$i<count($mails);$i++):?>
						<tr class="align-center">
							<td class="w100"><?=$mails[$i]['fio'];?><br/><i><b><?=$mails[$i]['login'];?></b></i></td>
							<td class="w400"><?=$mails[$i]['text'];?></td>
							<td class="w50" style="text-align:center; vertical-align:middle;">
								<div id="params<?=$i;?>" style="display:none" data-mid="<?=$mails[$i]['id'];?>" data-uid="<?=$mails[$i]['uid'];?>" data-fio="<?=$mails[$i]['fio'];?>" data-login="<?=$mails[$i]['login'];?>"></div>
								<a class="btn btn-info mailUser" data-param="<?=$i;?>" data-toggle="modal" href="#mailUser" title="Отправить письмо пользователю"><nobr>&nbsp;&nbsp;<i class="icon-envelope icon-white"></i>&nbsp;&nbsp;</nobr></a>
								<a class="btn btn-danger deleteMail" data-param="<?=$i;?>" data-toggle="modal" href="#deleteMail" title="Удалить сообщение"><nobr>&nbsp;&nbsp;<i class="icon-trash icon-white"></i>&nbsp;&nbsp;</nobr></a>
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
			$("#mailUser").on("hidden",function(){$("#msgalert").remove();$(".control-group").removeClass('error');$(".help-inline").hide();$("#mailText").val('');$("#sendMail").removeAttr('checked');});
			
			$(".deleteMail").click(function(){var Param = $(this).attr('data-param'); mID = $("div[id = params"+Param+"]").attr("data-mid");});
			$("#DelMail").click(function(){location.href='<?=$baseurl;?>admin-panel/messages/private-messages/delete-mail/mailid/'+mID;});
		});
	</script>
	
</body>
</html>
