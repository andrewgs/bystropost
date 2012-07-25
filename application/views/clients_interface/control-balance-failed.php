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
						<?=anchor('webmaster-panel/actions/balance','Баланс аккаунта');?>
					</li>
				</ul>
				<?php $this->load->view('alert_messages/alert-error');?>
				<?php $this->load->view('alert_messages/alert-success');?>
				<h3>Операция не выполнена.</h3>
				<h3>Баланс не пополнен. Попробуйте снова.</h3>
				
			</div>
			<?php $this->load->view("clients_interface/includes/rightbar");?>
		</div>
	</div>
	<?php $this->load->view('clients_interface/includes/scripts');?>
</body>
</html>
