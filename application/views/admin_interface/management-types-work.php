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
						<?=anchor('admin-panel/management/types-of-work','Типы работ	');?>
					</li>
				</ul>
				<?php $this->load->view('alert_messages/alert-error');?>
				<?php $this->load->view('alert_messages/alert-success');?>
				<table class="table table-striped table-bordered">
					<thead>
						<tr>
							<th class="w85"><center><nobr>Название</nobr></center></th>
							<th class="w85"><center><nobr>Цена (веб/мен)</nobr></center></th>
							<th class="w85">&nbsp;</th>
							<th style="width:20px;">&nbsp;</th>
							<th class="w85"><center><nobr>Название</nobr></center></th>
							<th class="w85"><center><nobr>Цена (веб/мен)</nobr></center></th>
							<th class="w85">&nbsp;</th>
						</tr>
					</thead>
					<tbody>
					<?php for($i=0;$i<count($tpswork);$i+=2):?>
						<tr>
						<?php if(isset($tpswork[$i]['id'])):?>
							<td class="w85"><nobr><i><b><?=$tpswork[$i]['title'];?></b></i></nobr></td>
							<td class="w85"><i><b><?=$tpswork[$i]['wprice'];?> руб.<br/><?=$tpswork[$i]['mprice'];?> руб.</b></i></td>
							<td class="w85">
								<div id="params<?=$i;?>" style="display:none" data-wid="<?=$tpswork[$i]['id'];?>" data-title="<?=$tpswork[$i]['title'];?>" data-wprice="<?=$tpswork[$i]['wprice'];?>" data-mprice="<?=$tpswork[$i]['mprice'];?>"></div>
								<a class="btn btn-success editTypeWork" data-param="<?=$i;?>" data-toggle="modal" href="#editTypeWork" title="Редактировать тип"><nobr>&nbsp;&nbsp;<i class="icon-pencil icon-white"></i>&nbsp;&nbsp;</nobr></a>
								<a class="btn btn-danger deleteTypeWork" data-param="<?=$i;?>" data-toggle="modal" href="#deleteTypeWork" title="Удалить тип"><nobr>&nbsp;&nbsp;<i class="icon-trash icon-white"></i>&nbsp;&nbsp;</nobr></a>
							</td>
						<?php endif;?>
						<td style="width:20px;">&nbsp;</td>
						<?php if(isset($tpswork[$i+1]['id'])):?>
							<td class="w85">
								<nobr>
									<i><b><?=$tpswork[$i+1]['title'];?></b></i>
								</nobr>
							</td>
							<td class="w85"><i><b><?=$tpswork[$i+1]['wprice'];?> руб.<br/><?=$tpswork[$i+1]['mprice'];?> руб.</b></i></td>
							<td class="w85">
								<div id="params<?=$i+1;?>" style="display:none" data-wid="<?=$tpswork[$i+1]['id'];?>" data-title="<?=$tpswork[$i+1]['title'];?>" data-wprice="<?=$tpswork[$i+1]['wprice'];?>" data-mprice="<?=$tpswork[$i+1]['mprice'];?>"></div>
								<a class="btn btn-success editTypeWork" data-param="<?=$i+1;?>" data-toggle="modal" href="#editTypeWork" title="Редактировать тип"><nobr>&nbsp;&nbsp;<i class="icon-pencil icon-white"></i>&nbsp;&nbsp;</nobr></a>
								<a class="btn btn-danger deleteTypeWork" data-param="<?=$i+1;?>" data-toggle="modal" href="#deleteTypeWork" title="Удалить тип"><nobr>&nbsp;&nbsp;<i class="icon-trash icon-white"></i>&nbsp;&nbsp;</nobr></a>
							</td>
						<?php else:?>
							<td class="w85">&nbsp;</td>
							<td class="w85">&nbsp;</td>
							<td class="w85">&nbsp;</td>
						<?php endif;?>
						</tr>
					<?php endfor; ?>
					</tbody>
				</table>
				<!--<a class="btn btn-info deleteMarket" data-toggle="modal" href="#addTypeWork" title="Добавить тип"><nobr>&nbsp;&nbsp;<i class="icon-plus icon-white"></i> Добавить тип в каталог&nbsp;&nbsp;</nobr></a>-->
			</div>
		<?php $this->load->view('admin_interface/includes/rightbar');?>
		<?php $this->load->view('admin_interface/modal/admin-edit-typework');?>
		<?php $this->load->view('admin_interface/modal/admin-add-typework');?>
		<?php $this->load->view('admin_interface/modal/admin-delete-typework');?>
		</div>
	</div>
	<?php $this->load->view('admin_interface/includes/footer');?>
	<?php $this->load->view('admin_interface/includes/scripts');?>
	<script type="text/javascript">
		$(document).ready(function(){
			var wID = 0;
			$(".editTypeWork").click(function(){
				var Param = $(this).attr('data-param'); wID = $("div[id = params"+Param+"]").attr("data-wid");
				var	mTitle = $("div[id = params"+Param+"]").attr("data-title"); var	wPrice = $("div[id = params"+Param+"]").attr("data-wprice");
				var	mPrice = $("div[id = params"+Param+"]").attr("data-mprice");
				$(".idTypeWork").val(wID);$("#emTitle").val(mTitle);$("#ewPrice").val(wPrice);$("#emPrice").val(mPrice);
			});
			
			$("#emsend").click(function(event){
				var err = false;
				$(".control-group").removeClass('error');
				$(".help-inline").hide();
				$(".eminput").each(function(i,element){
					if($(this).val()==''){
						$(this).parents(".control-group").addClass('error');
						$(this).siblings(".help-inline").html("Поле не может быть пустым").show();
						err = true;
					}
				});
				if(err){event.preventDefault();}
			});
			
			$("#amsend").click(function(event){
				var err = false;
				$(".control-group").removeClass('error');
				$(".help-inline").hide();
				$(".aminput").each(function(i,element){
					if($(this).val()==''){
						$(this).parents(".control-group").addClass('error');
						$(this).siblings(".help-inline").html("Поле не может быть пустым").show();
						err = true;
					}
				});
				if(err){event.preventDefault();}
			});
			
			$(".mkmodal").on("hidden",function(){$("#msgalert").remove();$(".control-group").removeClass('error');$(".help-inline").hide();});
			$(".deleteTypeWork").click(function(){var Param = $(this).attr('data-param'); wID = $("div[id = params"+Param+"]").attr("data-wid");});
			$("#DelTypeWork").click(function(){location.href='<?=$baseurl;?>admin-panel/management/types-of-work/workid/'+wID;});
		});
	</script>
</body>
</html>
