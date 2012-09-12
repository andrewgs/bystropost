<!DOCTYPE html>
<html lang="en">
<?php $this->load->view("clients_interface/includes/head");?>

<body>
	<?php $this->load->view("clients_interface/includes/header");?>
	<div class="container">
		<div class="row">
			<div class="span9">
				<ul class="breadcrumb">
					<li class="active">
						<?=anchor("webmaster-panel/actions/services","Дополнительные услуги");?>
					</li>
				</ul>
				<?php $this->load->view("alert_messages/alert-error");?>
				<?php $this->load->view("alert_messages/alert-success");?>
				<table class="table table-bordered" style="width: 700px;">
					<thead>
						<tr>
							<th class="w100"><center>Подключена</center></th>
							<th class="w400"><center>Название услуги</center></th>
							<th class="w100">&nbsp;</th>
						</tr>
					</thead>
					<tbody>
					<?php for($i=0;$i<count($attached);$i++):?>
						<tr>
							<td class="w100"><nobr><?=$attached[$i]['date'];?></nobr></td>
							<td class="w400"><?=$attached[$i]['stitle'];?></td>
							<td class="w100">
								<div id="params<?=$i;?>" style="display:none" data-sid="<?=$attached[$i]['service'];?>"></div>
								<?=anchor('webmaster-panel/actions/services/serviceid/'.$attached[$i]['service'].'/platforms','&nbsp;&nbsp;<i class="icon-tasks icon-white"></i>&nbsp;&nbsp;',array('class'=>'btn btn-info','title'=>'Настройка свойств площадок'));?>
								<a class="btn btn-danger deleteService" data-param="<?=$i;?>" data-toggle="modal" href="#deleteService" title="Удалить"><nobr>&nbsp;&nbsp;<i class="icon-trash icon-white"></i>&nbsp;&nbsp;</nobr></a>
							</td>
						</tr>
					<?php endfor; ?>
					</tbody>
				</table>
				<?php if($addservice):?>
				<a class="btn btn-info" data-toggle="modal" href="#addService" title="Добавить"><nobr>&nbsp;&nbsp;<i class="icon-plus icon-white"></i> Добавить&nbsp;&nbsp;</nobr></a>
				<?php endif;?>
			</div>
		<?php $this->load->view("clients_interface/includes/rightbar");?>
		<?php $this->load->view('clients_interface/modal/clients-add-service');?>
		<?php $this->load->view('clients_interface/modal/clients-delete-service');?>
		</div>
	</div>
	<?php $this->load->view("clients_interface/includes/footer");?>
	<?php $this->load->view("clients_interface/includes/scripts");?>
	<script type="text/javascript">
		$(document).ready(function(){
			var sID = 0;
			$(".deleteService").click(function(){var Param = $(this).attr('data-param'); sID = $("div[id = params"+Param+"]").attr("data-sid");});
			$("#DelService").click(function(){location.href='<?=$baseurl;?>admin-panel/management/services/delete/serviceid/'+sID;});
		});
	</script>
</body>
</html>