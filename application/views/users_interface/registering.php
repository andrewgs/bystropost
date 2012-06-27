<!DOCTYPE html>
<html>
<?php $this->load->view("users_interface/includes/head");?>

<body>
	<?php $this->load->view("users_interface/includes/header");?>
	
	<div id="main">
		<?php $this->load->view("users_interface/includes/navigation-no-reg");?>
		<?php $this->load->view('alert_messages/alert-success');?>
		<?php $this->load->view("forms/frmregistering");?>
		
		<?php $this->load->view("users_interface/includes/social");?>
	</div>
	<?php $this->load->view("users_interface/includes/footer");?>
	<?php $this->load->view("users_interface/includes/scripts");?>
</body>
</html>