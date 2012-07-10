<!DOCTYPE html>
<html lang="en">
<?php $this->load->view('admin_interface/includes/head');?>
<body>
	<?php $this->load->view('admin_interface/includes/header');?>
	<div class="container">
		<div class="row">
			<div class="span9">
				<ul class="breadcrumb">
					<li class="active">
						<?=anchor('admin-panel/messages/tickets','Тикеты');?>
					</li>
				</ul>
				<?php $this->load->view('alert_messages/alert-error');?>
				<?php $this->load->view('alert_messages/alert-success');?>
				<table class="table table-bordered">
					<thead>
						<tr>
							<th><center>ID</center></th>
							<th><center>Отправитель</center></th>
							<th><center>Название</center></th>
							<th><center>Статус</center></th>
						</tr>
					</thead>
					<tbody>
					<?php for($i=0;$i<count($tickets);$i++):?>
						<tr class="align-center">
							<td class="w50" style="text-align:center; vertical-align:middle;"><?=$tickets[$i]['id'];?></td>
							<td class="w195"><?=$tickets[$i]['fio'];?><br/><i><b><?=$tickets[$i]['login'];?></b></i><br/><?=$tickets[$i]['date'];?></td>
							<td class="w400">
								<?=anchor('admin-panel/messages/tickets/view-ticket/ticket-id/'.$tickets[$i]['id'],$tickets[$i]['title'].' (<i><b>'.$tickets[$i]['url'].'</b></i>)');?><br/>
								<u>Направлено на расмотрение:</u><br/>
								<nobr><?=$tickets[$i]['user'];?></nobr><br/>
								<nobr><i><b><?=$tickets[$i]['email'];?></b></i></nobr>
							</td>
							<td class="w50" style="text-align:center; vertical-align:middle;">
						<?php if(!$tickets[$i]['status']):?>
								<font style="color:#ff00ff">Активный</font>
						<?php else:?>
								<font style="color:#00ff00">Закрытый</font>
						<?php endif;?>
							</td>
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
	<?php $this->load->view('admin_interface/includes/scripts');?>
</body>
</html>
