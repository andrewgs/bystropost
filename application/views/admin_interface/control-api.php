<!DOCTYPE html>
<html lang="en">
<?php $this->load->view("admin_interface/includes/head");?>

<body>
	<?php $this->load->view("admin_interface/includes/header");?>
	<div class="container">
		<div class="row">
			<div class="span9">
				<ul class="breadcrumb">
					<li class="active">
						<?=anchor("admin-panel/actions/api","API");?>
					</li>
				</ul>
				<?php $this->load->view("alert_messages/alert-error");?>
				<?php $this->load->view("alert_messages/alert-success");?>
				<table class="table table-striped table-bordered">
					<tbody>
						<tr>
							<td class="short" style="width: 5px;">1</td>
							<td style="width:160px;"><i>Единоразовый приём</i></td>
							<td>
								<div class="alert alert-info">
									<h4 class="alert-heading">Будет загружено:</h4>
									<ol>
										<li>URL Площадки</li>
										<li>Логин биржи</li>
										<li>Пароль биржи</li>
										<li>Логин от админки площадки</li>
										<li>Пароль от админки площадки</li>
										<li>Тематика площадки</li>
									</ol>
								</div>
								<div style="float:right;">
									<a class="btn btn-success ExecAPI" data-toggle="modal" href="#ExecScript">Запустить</a>
								</div>
							</td>
						</tr>
					</tbody>
				</table>
			</div>
		<?php $this->load->view("admin_interface/includes/rightbar");?>
		<?php $this->load->view('admin_interface/modal/admin-exec-script');?>
		</div>
	</div>
	<?php $this->load->view("admin_interface/includes/scripts");?>
	<script type="text/javascript">
		$(document).ready(function(){
//			$(".ExecAPI").click(function(){});
			$("#continue").click(function(){location.href='<?=$baseurl;?>admin-panel/actions/api/executing-ones-api'});
		});
	</script>
</body>
</html>
