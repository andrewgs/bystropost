<!DOCTYPE html>
<html lang="en">
<?php $this->load->view('admin_interface/includes/head');?>
<body>
	<?php $this->load->view('admin_interface/includes/header');?>
	<div class="container">
		<div class="row">
			<div class="span9">
				<ul class="breadcrumb">
					<li class="active"><?=anchor($this->uri->uri_string(),'Партнерская программа');?></li>
				</ul>
				<?php $this->load->view('alert_messages/alert-error');?>
				<table class="table table-striped table-bordered">
					<thead>
						<tr>
							<th><center>ID</center></th>
							<th><center>Логин</center></th>
							<th><center>Площадки</center></th>
							<th><center>Заявки</center></th>
							<th><center>Заработано</center></th>
							<th>&nbsp;</th>
						</tr>
					</thead>
					<tbody>
					<?php for($i=0;$i<count($webmasters);$i++):?>
						<tr>
							<td><?=$webmasters[$i]['id'];?></td>
							<td><?=anchor('admin-panel/management/users/profile/id/'.$webmasters[$i]['id'],$webmasters[$i]['login']);?></td>
							<td><?=$webmasters[$i]['platforms'];?></td>
							<td><?=$webmasters[$i]['works'];?></td>
							<td><?=$webmasters[$i]['summa'];?> руб.</td>
							<td class="span1"><?=anchor('admin-panel/actions/partner-program/partner/'.$webmasters[$i]['id'],'<i class="icon-list-alt icon-white"></i>',array('class'=>'btn btn-success'));?></td>
						</tr>
					<?php endfor; ?>
					</tbody>
				</table>
				<?php if($pages): ?>
					<?=$pages;?>
				<?php endif;?>
			</div>
		<?php $this->load->view('admin_interface/includes/rightbar');?>
		</div>
	</div>
	<?php $this->load->view('admin_interface/includes/footer');?>
	<?php $this->load->view('admin_interface/includes/scripts');?>
</body>
</html>
