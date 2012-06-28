<!DOCTYPE html>
<html>
<?php $this->load->view("clients_interface/includes/head");?>

<body>
	<?php $this->load->view("clients_interface/includes/header");?>
	<div id="main">
		<?php $this->load->view("clients_interface/includes/navigation");?>
		
		<div id="stable">
			<div id="panel_menu">
				<?=anchor('webmaster-panel/actions/platforms','&larr; Вернуться назад');?>
			</div>
			<?php $this->load->view("forms/frmaddplatform");?>
		</div>
	</div>
	<?php $this->load->view("clients_interface/includes/footer");?>
	<?php $this->load->view("clients_interface/includes/scripts");?>
	<script src="<?=$baseurl;?>javascript/jquery.megaselectlist.js"></script>
	<script src="<?=$baseurl;?>javascript/redactor/redactor.js"></script>
	<script type="text/javascript">
		$(document).ready(function(){
			$("#thematically").megaselectlist({animate:true,animateevent:"click"});
			$(".redactor").redactor({toolbar:'default',lang: 'ru','fixed': true});
		});
	</script>
</body>
</html>