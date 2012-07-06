<!DOCTYPE html>
<html lang="en">
<?php $this->load->view('managers_interface/includes/head');?>
<body>
	<?php $this->load->view('managers_interface/includes/header');?>
	<div class="container">
		<div class="row">
			<div class="span9">
				<ul class="breadcrumb">
					<li class="active">
						<?=anchor('manager-panel/actions/control','Панель управления');?>
					</li>
				</ul>
				<?php $this->load->view('alert_messages/alert-error');?>
				<?php $this->load->view('alert_messages/alert-success');?>
				
			</div>
		<?php $this->load->view('managers_interface/includes/rightbar');?>
		</div>
	</div>
	<?php $this->load->view('managers_interface/includes/scripts');?>
</body>
</html>
