<!DOCTYPE html>
<html>
<?php $this->load->view("users_interface/includes/head");?>

<body>
	<div class="container">
		<?php $this->load->view("users_interface/includes/navigation");?>
		<?php $this->load->view("users_interface/includes/header");?>
		
		<div class="row">
			<div id="stable">
				<div id="recovery">
					<?=form_open($this->uri->uri_string()); ?>
						<h1>Забыли пароль? Мы напомним</h1>
						<input type="text" id="RestoreEmail" name="email" placeholder="Введите Ваш E-mail" />
						<?php if($msgr):?>
							<div id="msgdealert">
								<?=$msgr;?>
							</div>
						<?php endif; ?>
						<input type="submit" name="rsubmit" id="btnRestore" value="">
					<?= form_close(); ?>
				</div>
			</div>
		</div>
	</div>
	<?php $this->load->view("users_interface/includes/footer");?>
	<?php $this->load->view("users_interface/includes/scripts");?>
</body>
</html>