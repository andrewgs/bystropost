<!DOCTYPE html>
<html>
<?php $this->load->view("clients_interface/includes/head");?>

<body>
	<?php $this->load->view("clients_interface/includes/header");?>
	<div id="main">
		<?php $this->load->view("clients_interface/includes/navigation");?>
		
		<div id="stable">
			<div id="panel_menu" style="margin-left:0;">
				<?=anchor('webmaster-panel/actions/mails','&larr; Вернуться назад');?>
			</div>
			<?php $this->load->view('alert_messages/alert-error');?>
			<div class="clear"></div>
			<?php $this->load->view("forms/frmreplymail");?>
		</div>
	</div>
	<?php $this->load->view("clients_interface/includes/footer");?>
	<?php $this->load->view("clients_interface/includes/scripts");?>
	<script src="<?=$baseurl;?>javascript/scrollto-min.js"></script>
	<script type="text/javascript">
		$(document).ready(function(){
			$("#SendMail").click(function(event){
				var err = false;
				$(".ErrImg").remove();
				if($("#text").val()==''){
					$("#SendMail").after('<img class="ErrImg" src="<?=$baseurl;?>images/icons/exclamation.png" title="Текс сообщения не может быть пустым">');
					event.preventDefault();
				}
			});
		});
	</script>
</body>
</html>