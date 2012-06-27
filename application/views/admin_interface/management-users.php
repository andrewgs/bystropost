<!DOCTYPE html>
<html lang="en">
<?php $this->load->view('admin_interface/includes/head');?>
<body>
	<?php $this->load->view('admin_interface/includes/header');?>
	<div class="container">
		<div class="row">
			<div class="span9">
				<ul class="breadcrumb">
					<li tnum="webmasters">
						<?=anchor('admin-panel/management/users/webmasters','Группа "Вебмастера"');?> <span class="divider">/</span>
					</li>
					<li tnum="optimizators">
						<?=anchor('admin-panel/management/users/optimizators','Группа "Оптимизаторы"');?> <span class="divider">/</span>
					</li>
					<li tnum="manegers">
						<?=anchor('admin-panel/management/users/manegers','Группа "Менеджеры"');?> <span class="divider">/</span>
					</li>
					<li tnum="admin">
						<?=anchor('admin-panel/management/users/admin','Группа "Администраторы"');?> <span class="divider">/</span>
					</li>
					<li tnum="all">
						<?=anchor('admin-panel/management/users/all','Все');?>
					</li>
				</ul>
				<?php $this->load->view('alert_messages/alert-error');?>
				<?php $this->load->view('alert_messages/alert-success');?>
				<table class="table table-striped table-bordered">
					<thead>
						<tr>
							<th><nobr>№ п\п</nobr></th>
							<th><center><nobr>Е-mail (Логин) / Ф.И.О.</nobr></center></th>
							<th><center><nobr>Баланс</nobr></center></th>
							<th><center><nobr>Заявки: всего /</nobr><br/><nobr>не оплаченых</nobr></center></th>
							<th><center>WMID</center></th>
							<th>Регистр.</th>
							<th>Действия</th>
						</tr>
					</thead>
					<tbody>
					<?php for($i=0,$num=1;$i<count($users);$i++):?>
						<tr class="align-center">
							<td class="short"><center><?=$num;?></center></td>
							<td style="min-width:110px;"><nobr><i><b><?=$users[$i]['login'];?></b></i></nobr><br/><?=$users[$i]['fio'];?></td>
							<td><center><nobr><?=$users[$i]['balance'];?> руб.</nobr></center></td>
							<td><center><nobr><?=$users[$i]['torders'];?> / <?=$users[$i]['uporders'];?></nobr></center></td>
							<td><nobr><?=$users[$i]['wmid'];?></nobr></td>
							<td><nobr><?=$users[$i]['signdate'];?></nobr></td>
							<td style="max-width:80px;">
								<a class="btn btn-success discbtn" data-userid="<?=$users[$i]['id'];?>" title="Редактировать пользователя"><nobr>&nbsp;&nbsp;&nbsp;<i class="icon-pencil icon-white"></i>&nbsp;&nbsp;&nbsp;</nobr></a>
								<a class="btn btn-info SendMail" data-userid="<?=$users[$i]['id'];?>" title="Отправить письмо пользователю"><nobr>&nbsp;&nbsp;&nbsp;<i class="icon-envelope icon-white"></i>&nbsp;&nbsp;&nbsp;</nobr></a>
								<a class="btn btn-danger deleteOrder" data-toggle="modal" href="#deleteUser" title="Удалить пользователя" data-userid="<?=$users[$i]['id'];?>"><nobr>&nbsp;&nbsp;&nbsp;<i class="icon-trash icon-white"></i>&nbsp;&nbsp;&nbsp;</nobr></a>
							</td>
						</tr>
						<?php $num++; ?>
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
	<?php $this->load->view('admin_interface/includes/scripts');?>
	<script type="text/javascript">
		$(document).ready(function(){
			$("li[tnum='<?=$this->uri->segment(4);?>']").addClass('active');
		});
	</script>
</body>
</html>
