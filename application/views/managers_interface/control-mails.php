<!DOCTYPE html>
<html lang="en">
<?php $this->load->view("managers_interface/includes/head");?>

<body>
	<?php $this->load->view("managers_interface/includes/header");?>
	
	<div class="container">
		<div class="row">
			<div class="span9">
				<ul class="breadcrumb">
					<li class="active">
						<?=anchor("manager-panel/actions/mails","Все сообщения");?>
					</li>
				</ul>
				<?php $this->load->view("alert_messages/alert-error");?>
				<?php $this->load->view("alert_messages/alert-success");?>
				<table class="table table-bordered" style="width: 700px;">
					<thead>
						<tr>
							<th class="w100"><center>Отправитель</center></th>
							<th class="w500"><center><nobr>Текс сообщения</nobr></center></th>
							<th class="w50"><center>Управ.</center></th>
						</tr>
					</thead>
					<tbody>
					<?php for($i=0;$i<count($mails);$i++):?>
						<tr>
							<td class="w100" style="text-align:center; vertical-align:middle;">
								<?=$mails[$i]['position'];?><br/>
								<b><?=$mails[$i]['login'];?></b><br/>
								<nobr><?=$mails[$i]['date'];?></nobr>
							</td>
							<td class="w500" data-system="<?=$mails[$i]['system'];?>" data-type="<?=$mails[$i]['type'];?>"><?=$mails[$i]['text'];?></td>
						<?php if(!$mails[$i]['system']):?>
							<td class="w50" style="text-align:center; vertical-align:middle;">
								<div id="params<?=$i;?>" style="display:none" data-mid="<?=$mails[$i]['id'];?>" data-uid="<?=$mails[$i]['sender'];?>" data-position="<?=$mails[$i]['position'];?>"></div>
								<a class="btn btn-info mailUser" data-param="<?=$i;?>" data-toggle="modal" href="#mailUser" title="Ответить"><nobr>&nbsp;&nbsp;<i class="icon-envelope icon-white"></i>&nbsp;&nbsp;</nobr></a>
								<a class="btn btn-danger deleteMail" data-param="<?=$i;?>" data-toggle="modal" href="#deleteMail" title="Удалить сообщение"><nobr>&nbsp;&nbsp;<i class="icon-trash icon-white"></i>&nbsp;&nbsp;</nobr></a>
							<?php else:?>
								<td class="SysMsg" style="text-align:center; vertical-align:middle;">Системное сообщение</td>
							</td>
						<?php endif;?>
						</tr>
					<?php endfor; ?>
					</tbody>
				</table>
			<?php if($pages): ?>
				<?=$pages;?>
			<?php endif;?>
			</div>
		<?php $this->load->view("managers_interface/includes/rightbar");?>
		<?php $this->load->view('clients_interface/modal/clients-mail-users');?>
		<?php $this->load->view('admin_interface/modal/admin-delete-mail');?>
		</div>
	</div>
	<?php $this->load->view("managers_interface/includes/footer");?>
	<?php $this->load->view("managers_interface/includes/scripts");?>
	<script type="text/javascript">
		$(document).ready(function(){
			var msgtype = 0;
			var mID = 0;
			$("td[data-system = '1']").each(function(e){
				msgtype = $(this).attr('data-type');
				switch (msgtype){
					case '1': $(this).addClass('alert alert-error'); $(this).siblings('td').addClass('alert alert-error'); break
					case '2': $(this).addClass('alert alert-info'); $(this).siblings('td').addClass('alert alert-info'); break
					case '3': $(this).addClass('alert alert-block'); $(this).siblings('td').addClass('alert alert-block'); break
					case '4': $(this).addClass('alert alert-success'); $(this).siblings('td').addClass('alert alert-success'); break
				}
			});
			$(".mailUser").click(function(){
				var Param = $(this).attr('data-param'); mID = $("div[id = params"+Param+"]").attr("data-mid");uID = $("div[id = params"+Param+"]").attr("data-uid");
				var	uPosition = $("div[id = params"+Param+"]").attr("data-position");
				$(".idMail").val(mID);$(".idUser").val(uID);$("#ePosition").val(uPosition);
			});
			$("#mtsend").click(function(event){
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
			$("#DelMail").click(function(){location.href='<?=$baseurl;?>manager-panel/actions/mails/delete/mail-id/'+mID;});
		});
	</script>
</body>
</html>