<!DOCTYPE html>
<html lang="en">
<?php $this->load->view('admin_interface/includes/head');?>
<body>
	<?php $this->load->view('clients_interface/includes/header');?>
	<div class="container">
		<div class="row">
			<div class="span9">
				<ul class="breadcrumb">
					<li class="active">
						<?=anchor($this->uri->uri_string(),'Баланс аккаунта');?>
					</li>
				</ul>
				<?php $this->load->view('alert_messages/alert-error');?>
				<?php $this->load->view('alert_messages/alert-success');?>
				<?php $this->load->view('forms/frmuserbalanse');?>
				
			</div>
			<?php $this->load->view("clients_interface/includes/rightbar");?>
		</div>
	</div>
	<?php $this->load->view("clients_interface/includes/footer");?>
	<?php $this->load->view('clients_interface/includes/scripts');?>
	<script type="text/javascript">
		$(document).ready(function(){
		
			$("#send").click(function(event){
				$(".control-group").removeClass('error');
				$(".help-inline").hide();
				if($("#balance").val()==''){
					$("#balance").parents(".control-group").addClass('error');
					$("#hlbalance").html("Поле не может быть пустым").show();
					event.preventDefault();
				}else if($("#balance").val() == 0){
					$("#balance").parents(".control-group").addClass('error');
					$("#hlbalance").html("Cумма должна быть больше нуля").show();
					event.preventDefault();
				};;
				if($("#purse").val()==''){
					$("#purse").parents(".control-group").addClass('error');
					$("#hlpurse").html("Поле не может быть пустым").show();
					event.preventDefault();
				}else if($("#purse").val().length != 12){
					$("#purse").parents(".control-group").addClass('error');
					$("#hlpurse").html("Должно быть 12 цифр").show();
					event.preventDefault();
				}
			});
		});
	</script>
</body>
</html>
