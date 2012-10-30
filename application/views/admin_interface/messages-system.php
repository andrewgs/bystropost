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
						<?=anchor('admin-panel/messages/system-message','Системное сообщение');?>
					</li>
				</ul>
				<?php $this->load->view('alert_messages/alert-error');?>
				<?php $this->load->view('alert_messages/alert-success');?>
				<?php $this->load->view('forms/frmsystemmail');?>
			</div>
		<?php $this->load->view('admin_interface/includes/rightbar');?>
		</div>
	</div>
	<?php $this->load->view('admin_interface/includes/footer');?>
	<?php $this->load->view('admin_interface/includes/scripts');?>
	<script type="text/javascript">
		$(document).ready(function(){
			var mID = 0;
			$(".mailUser").click(function(){
				var Param = $(this).attr('data-param'); mID = $("div[id = params"+Param+"]").attr("data-mid");
				var	uFIO = $("div[id = params"+Param+"]").attr("data-fio"); var	uLogin = $("div[id = params"+Param+"]").attr("data-login");
				$(".idUser").val(mID);$(".eFio").val(uFIO);$(".eLogin").val(uLogin);
			});
			$("#send").click(function(event){
				var err = false;
				$(".control-group").removeClass('error');
				$(".help-inline").hide();
				if($("#text").val() == ''){
					$("#text").parents(".control-group").addClass('error');
					$("#text").siblings(".help-inline").html("Поле не может быть пустым").show();
					event.preventDefault();
				}else{
					$(this).html("Отправка писем. Ожидайте...");
				}
			});
			$("#mailUser").on("hidden",function(){$("#msgalert").remove();$(".control-group").removeClass('error');$(".help-inline").hide();$("#mailText").val('');$("#sendMail").removeAttr('checked');});
			
			$(".deleteMail").click(function(){var Param = $(this).attr('data-param'); mID = $("div[id = params"+Param+"]").attr("data-mid");});
			$("#DelMail").click(function(){location.href='<?=$baseurl;?>admin-panel/messages/private-messages/delete-mail/mailid/'+mID;});
		});
	</script>
	
</body>
</html>
