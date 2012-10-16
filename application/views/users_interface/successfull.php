<!DOCTYPE html>
<html>
<?php $this->load->view("users_interface/includes/head");?>

<body>
	<div class="container">
		<?php $this->load->view("users_interface/includes/navigation");?>
		<?php $this->load->view("users_interface/includes/header");?>
		
		<div class="row">
			<table id="content" border="0" style="background-image:none;">
				<tr>
					<td width="100%" valign=top>
						<big><center>На указанный Вами адрес высланы данные регистрации. Спасибо что пользуетесь нашим сайтом.</center></big>
					</td>
				</tr>
				<tr>
					<td width="100%" valign="top">&nbsp;</td>
				</tr>
				<tr>
					<td width="100%" valign="top">
						<big><center>Через несколько секунд, Вас автоматически перенаправит на главную страницу</center></big>
					</td>
				</tr>
			</table>
		</div>
	</div>
	<?php $this->load->view("users_interface/includes/footer");?>
	<?php $this->load->view("users_interface/includes/scripts");?>
	<script type="text/javascript">
		$(document).ready(function(){
			window.setTimeout("window.location='<?=$baseurl;?>'",7000);
		});
	</script>
</body>
</html>