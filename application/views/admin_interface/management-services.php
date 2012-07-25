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
						<?=anchor('admin-panel/management/services','Дополнительные услуги');?>
					</li>
				</ul>
				<?php $this->load->view('alert_messages/alert-error');?>
				<?php $this->load->view('alert_messages/alert-success');?>
				<table class="table table-striped table-bordered">
					<thead>
						<tr>
							<th class="w400"><center><nobr>Название</nobr></center></th>
							<th class="w85"><center><nobr>Сотоимость</nobr></center></th>
							<th class="w50">&nbsp;</th>
						</tr>
					</thead>
					<tbody>
					<?php for($i=0;$i<count($services);$i++):?>
						<tr class="align-center">
							<td class="w400"><i><b><?=$services[$i]['title'];?></b></i></td>
							<td class="w85" style="text-align:center;vertical-align:middle;"><?=$services[$i]['price'];?> руб.</td>
							<td class="w50" style="text-align:center;vertical-align:middle;">
								<div id="params<?=$i;?>" style="display:none" data-sid="<?=$services[$i]['id'];?>" data-title="<?=$services[$i]['title'];?>" data-price="<?=$services[$i]['price'];?>"></div>
								<a class="btn btn-success editService" data-param="<?=$i;?>" data-toggle="modal" href="#editService" title="Редактировать"><nobr>&nbsp;&nbsp;<i class="icon-pencil icon-white"></i>&nbsp;&nbsp;</nobr></a>
								<a class="btn btn-danger deleteService" data-param="<?=$i;?>" data-toggle="modal" href="#deleteService" title="Удалить"><nobr>&nbsp;&nbsp;<i class="icon-trash icon-white"></i>&nbsp;&nbsp;</nobr></a>
							</td>
						</tr>
					<?php endfor; ?>
					</tbody>
				</table>
				<a class="btn btn-info" data-toggle="modal" href="#addService" title="Добавить"><nobr>&nbsp;&nbsp;<i class="icon-plus icon-white"></i> Добавить&nbsp;&nbsp;</nobr></a>
			</div>
		<?php $this->load->view('admin_interface/includes/rightbar');?>
		<?php $this->load->view('admin_interface/modal/admin-edit-service');?>
		<?php $this->load->view('admin_interface/modal/admin-add-service');?>
		<?php $this->load->view('admin_interface/modal/admin-delete-service');?>
		</div>
	</div>
	<?php $this->load->view('admin_interface/includes/scripts');?>
	<script type="text/javascript">
		$(document).ready(function(){
			var sID = 0;
			$(".editService").click(function(){
				var Param = $(this).attr('data-param'); sID = $("div[id = params"+Param+"]").attr("data-sid");
				var sTitle = $("div[id = params"+Param+"]").attr("data-title"); var sPrice = $("div[id = params"+Param+"]").attr("data-price");
				$(".idService").val(sID);$("#esTitle").val(sTitle);$("#esPrice").val(sPrice);
			});
			
			$("#essend").click(function(event){
				var err = false;
				$(".control-group").removeClass('error');
				$(".help-inline").hide();
				$(".esinput").each(function(i,element){
					if($(this).val()==''){
						$(this).parents(".control-group").addClass('error');
						$(this).siblings(".help-inline").html("Поле не может быть пустым").show();
						err = true;
					}
				});
				if(err){event.preventDefault();}
			});
			
			$("#assend").click(function(event){
				var err = false;
				$(".control-group").removeClass('error');
				$(".help-inline").hide();
				$(".asinput").each(function(i,element){
					if($(this).val()==''){
						$(this).parents(".control-group").addClass('error');
						$(this).siblings(".help-inline").html("Поле не может быть пустым").show();
						err = true;
					}
				});
				if(err){event.preventDefault();}
			});
			
			$(".mkmodal").on("hidden",function(){$("#msgalert").remove();$(".control-group").removeClass('error');$(".help-inline").hide();});
			$(".deleteService").click(function(){var Param = $(this).attr('data-param'); sID = $("div[id = params"+Param+"]").attr("data-sid");});
			$("#DelService").click(function(){location.href='<?=$baseurl;?>admin-panel/management/services/serviceid/'+sID;});
		});
	</script>
</body>
</html>
