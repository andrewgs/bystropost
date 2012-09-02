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
							<th class="w205"><center>Название</center></th>
							<th class="w275"><center>Значения (Стоимость)</center></th>
							<th class="w50">&nbsp;</th>
						</tr>
					</thead>
					<tbody>
					<?php for($i=0;$i<count($services);$i++):?>
						<tr>
							<td class="w205"><b><?=$services[$i]['title'];?></b></td>
							<td class="w275">
								<ul>
						<?php for($j=0;$j<count($valuesrv);$j++):?>
							<?php if($valuesrv[$j]['serveice'] == $services[$i]['id']):?>
									<div id="jprm<?=$j;?>" style="display:none" data-svid="<?=$valuesrv[$j]['id'];?>" data-title="<?=$valuesrv[$j]['title'];?>" data-price="<?=$valuesrv[$j]['price'];?>"></div>
									<li><?=$valuesrv[$j]['title'];?> (<?=$valuesrv[$j]['price'];?> руб.) [<a class="editSrvValue" data-jprm="<?=$j;?>" data-toggle="modal" href="#editSrvValue">Изменить</a>]</li>
							<?php endif;?>
						<?php endfor; ?>
								</ul>
							</td>
							<td class="w50" style="text-align:center;vertical-align:middle;">
								<div id="params<?=$i;?>" style="display:none" data-sid="<?=$services[$i]['id'];?>" data-title="<?=$services[$i]['title'];?>"></div>
								<a class="btn btn-info addSrvValue" data-param="<?=$i;?>" data-toggle="modal" href="#addSrvValue" title="Добавить значение"><nobr>&nbsp;&nbsp;<i class="icon-plus icon-white"></i>&nbsp;&nbsp;</nobr></a>
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
		<?php $this->load->view('admin_interface/modal/admin-add-srvvalue');?>
		<?php $this->load->view('admin_interface/modal/admin-edit-srvvalue');?>
		<?php $this->load->view('admin_interface/modal/admin-delete-service');?>
		</div>
	</div>
	<?php $this->load->view('admin_interface/includes/footer');?>
	<?php $this->load->view('admin_interface/includes/scripts');?>
	<script type="text/javascript">
		$(document).ready(function(){
			var sID = 0;
			$(".editService").click(function(){
				var Param = $(this).attr('data-param'); sID = $("div[id = params"+Param+"]").attr("data-sid");
				var sTitle = $("div[id = params"+Param+"]").attr("data-title");
				$(".idService").val(sID);$("#esTitle").val(sTitle);
			});
			$(".editSrvValue").click(function(){
				var Param = $(this).attr('data-jprm'); svID = $("div[id = jprm"+Param+"]").attr("data-svid");
				var svTitle = $("div[id = jprm"+Param+"]").attr("data-title");var svPrice = $("div[id = jprm"+Param+"]").attr("data-price");
				$(".idSrvValue").val(svID);$("#SVTitle").val(svTitle);$("#SVPrice").val(svPrice);
			});
			$(".addSrvValue").click(function(){
				var Param = $(this).attr('data-param'); sID = $("div[id = params"+Param+"]").attr("data-sid");$(".idService").val(sID);
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
			$("#asvsend").click(function(event){
				var err = false;
				$(".control-group").removeClass('error');
				$(".help-inline").hide();
				$(".asvinput").each(function(i,element){
					if($(this).val()==''){
						$(this).parents(".control-group").addClass('error');
						$(this).siblings(".help-inline").html("Поле не может быть пустым").show();
						err = true;
					}
				});
				if(err){event.preventDefault();}
			});
			$("#esvsend").click(function(event){
				var err = false;
				$(".control-group").removeClass('error');
				$(".help-inline").hide();
				$(".esvinput").each(function(i,element){
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
