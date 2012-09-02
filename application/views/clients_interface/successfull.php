<!DOCTYPE html>
<html>
<?php $this->load->view("clients_interface/includes/head");?>

<body>
	<div class="container">
		<?php $this->load->view("clients_interface/includes/header");?>
		
		<div class="row">
			<div id="white_line" class="span12">
				<div class="alert alert-info">
					<big><center>Вы удачно зарегистировались в системе Быстропост!<br/>На почту вам были отправлены регистрационные данные.<br/>Спасибо что решили воспользоваться нашими услугами!</center></big>
				</div>
				<div class="alert alert-saccess" style="margin-top:100px;">
					<big><center>Через несколько секунд, Вас автоматически перенаправит в <?=anchor('webmaster-panel/actions/control','"Профиль «Вебмастера»"');?></center></big>
				</div>
			</div>
		</div>
	</div>
	<?php $this->load->view("clients_interface/includes/footer");?>
	<?php $this->load->view("clients_interface/includes/scripts");?>
</body>
</html>