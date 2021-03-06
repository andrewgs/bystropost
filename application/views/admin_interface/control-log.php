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
						<?=anchor($this->uri->uri_string(),"События");?>
					</li>
					<li style="float:right;">
						<?=anchor('admin-panel/actions/events/clear','Очистить',array('class'=>'btn btn-info','style'=>'margin-top: -5px;'));?>
					</li>
				</ul>
				<?php $this->load->view('alert_messages/alert-error');?>
				<?php $this->load->view('alert_messages/alert-success');?>
				<table class="table table-striped table-bordered">
					<thead>
						<tr>
							<th class="w100"><center>Пользователь</center></th>
							<th class="w50"><center>Дата</center></th>
							<th class="w500"><center>Текст события</center></th>
						</tr>
					</thead>
					<tbody>
					<?php for($i=0;$i<count($events);$i++):?>
						<tr>
							<td class="w100" style="vertical-align:middle;">
								<nobr><?=$events[$i]['ufio'];?><br/><b><?=$events[$i]['ulogin'];?></b><br/><?=$events[$i]['uposition'];?></nobr>
							</td>
							<td style="vertical-align:middle; text-align:center;"><nobr><b><?=$events[$i]['date'];?></b></nobr></td>
							<td class="w500"><?=$events[$i]['note'];?></td>
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
