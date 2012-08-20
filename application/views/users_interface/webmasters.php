<!DOCTYPE html>
<html>
<?php $this->load->view("users_interface/includes/head");?>

<body>
	<?php $this->load->view("users_interface/includes/header");?>
	<div id="main">
		<?php $this->load->view("users_interface/includes/navigation");?>
		<div class="clear"></div>
		<div id="register_tree">
			<?=anchor('users/registering/webmaster','<img src="'.$baseurl.'images/spacer.gif" width="500px" height="450px" border="0">');?>
		</div>
	</div>
	<?php $this->load->view("users_interface/includes/footer");?>
	<?php $this->load->view("users_interface/includes/scripts");?>
</body>
</html>