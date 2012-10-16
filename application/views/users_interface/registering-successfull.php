<!DOCTYPE html>
<html>
<?php $this->load->view("users_interface/includes/head");?>

<body>
	<?php $this->load->view("users_interface/includes/header");?>
	
	<div id="main">
		<?php $this->load->view("users_interface/includes/navigation-no-reg");?>
		<table id="content" border="0" style="background-image:none;">
			<tr>
				<td width="100%" valign=top>
					<big><center>Вы удачно зарегестировались на сайте.<br/>На почту высланы данные рагистрации. Спасибо что пользуетесь нашим сайтом.</center></big>
				</td>
			</tr>
			<tr>
				<td width="100%" valign="top">&nbsp;</td>
			</tr>
			<tr>
				<td width="100%" valign="top">&nbsp;</td>
			</tr>
			<tr>
				<td width="100%" valign="top">
					<big><center>Через несколько секунд, Вас автоматически перенаправит на главную страницу</center></big>
					<br>
					<big><center><?=anchor('users/cabinet','Панель управления');?></center></big>
				</td>
			</tr>
		</table>
	</div>
	<?php $this->load->view("users_interface/includes/footer");?>
	<?php $this->load->view("users_interface/includes/scripts");?>
	<script type="text/javascript">
		$(document).ready(function(){
			window.setTimeout("window.location='<?=$baseurl;?>/users/cabinet'",5000);
		});
	</script>
</body>
</html>