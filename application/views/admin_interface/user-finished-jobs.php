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
						<?=anchor($this->session->userdata('backpath'),'Список пользователей');?> <span class="divider">/</span>
					</li>
					<li class="active">
						<?=anchor($this->uri->uri_string(),'Выполненные задания');?>
					</li>
				</ul>
				<?php $this->load->view('alert_messages/alert-error');?>
				<?php $this->load->view('alert_messages/alert-success');?>
				<table class="table table-bordered" style="width: 700px;">
					<thead>
						<tr>
							<th class="w100"><center>№ п.п</center></th>
							<th class="w100"><center>Дата</center></th>
							<th class="w100"><center><nobr>Тип работы</nobr></center></th>
							<th class="w100"><center>Биржа</center></th>
							<th class="w100"><center><nobr>Цена<br/>на бирже</nobr></center></th>
							<th class="w100"><center>URL-адрес</center></th>
							<th class="w100"><center><nobr>Колич.<br/>символов</nobr></center></th>
							<th class="w100"><center>Стоим.</center></th>
						</tr>
					</thead>
					<tbody>
					<?php for($i=0,$num=$this->uri->segment(8)+1;$i<count($delivers);$i++,$num++):?>
						<tr>
							<td class="w100" data-status="<?=$delivers[$i]['status'];?>" style="text-align:center; vertical-align:middle;"><?=$num;?></td>
							<td class="w100" style="text-align:center; vertical-align:middle;"><nobr><b><?=$delivers[$i]['date'];?></b></nobr></td>
							<td class="w100" style="text-align:center; vertical-align:middle;"><?=$delivers[$i]['twtitle'];?></td>
							<td class="w100" style="text-align:center; vertical-align:middle;"><?=$delivers[$i]['mtitle'];?></td>
							<td class="w100" style="text-align:center; vertical-align:middle;"><nobr><?=$delivers[$i]['mkprice'];?> руб.</nobr></td>
							<td class="w100" style="vertical-align:middle;"><?=anchor($delivers[$i]['ulrlink'],$delivers[$i]['link'],array('target'=>'_blank'));?></td>
							<td class="w100" style="text-align:center; vertical-align:middle;"><nobr><?=$delivers[$i]['countchars'];?> шт.<nobr></td>
							<td class="w100" style="text-align:center; vertical-align:middle;"><nobr><?=$delivers[$i]['mprice'];?> руб.</nobr></td>
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
	<script type="text/javascript">
		$(document).ready(function(){
			$("td[data-status='0']").each(function(e){
				$(this).addClass('alert alert-error'); $(this).siblings('td').addClass('alert alert-error');
			});
			$("td[data-status='1']").each(function(e){
				$(this).addClass('alert alert-info'); $(this).siblings('td').addClass('alert alert-info');
			});
		});
	</script>
</body>
</html>
