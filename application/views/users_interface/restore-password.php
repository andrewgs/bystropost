<!DOCTYPE html>
<html>
<?php $this->load->view("users_interface/includes/head");?>

<body>
	<?php $this->load->view("users_interface/includes/header");?>
	<div id="main">
		<?php $this->load->view("users_interface/includes/navigation");?>
		
		<div id="stable">
			<div id="recovery">
				<?=form_open($this->uri->uri_string()); ?>
					<h1>Забыли пароль? Мы напомним</h1>
					<input type="text" id="email" name="email" placeholder="Введите Ваш E-mail" />
					<input type="submit" name="rsubmit" value="">
				<?= form_close(); ?>
			</div>
		</div>
		<?php $this->load->view("users_interface/includes/social");?>
	</div>
	<?php $this->load->view("users_interface/includes/footer");?>
	<?php $this->load->view("users_interface/includes/scripts");?>
</body>
</html>