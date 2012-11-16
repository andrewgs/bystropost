<!DOCTYPE html>
<html lang="en">
<?php $this->load->view('admin_interface/includes/head-datapicker');?>
<body>
	<?php $this->load->view('admin_interface/includes/header');?>
	<div class="container">
		<div class="row">
			<div class="span9">
				<ul class="breadcrumb">
					<li class="active">
						<?=anchor('admin-panel/management/promocode','Промокоды');?>
					</li>
				</ul>
				<?php $this->load->view('alert_messages/alert-error');?>
				<?php $this->load->view('alert_messages/alert-success');?>
				<table class="table table-striped table-bordered">
					<thead>
						<tr>
							<th class="w100"><center><nobr>Код</nobr></center></th>
							<th class="w400"><center><nobr>Сроки действия</nobr></center></th>
							<th>&nbsp;</th>
						</tr>
					</thead>
					<tbody>
					<?php for($i=0;$i<count($codes);$i++):?>
						<tr>
							<td class="w100"><?=$codes[$i]['code'];?></td>
							<td class="w400">c <?=$codes[$i]['datefrom'];?> по <?=$codes[$i]['dateto'];?></td>
							<td style="width:105px;">
								<div id="params<?=$i;?>" style="display:none" data-cid="<?=$codes[$i]['id'];?>" data-code="<?=$codes[$i]['code'];?>" data-datefrom="<?=$codes[$i]['datefrom'];?>" data-dateto="<?=$codes[$i]['dateto'];?>"></div>
								<a class="btn btn-success editCode" data-param="<?=$i;?>" data-toggle="modal" href="#editCode" title="Редактировать"><nobr>&nbsp;&nbsp;<i class="icon-pencil icon-white"></i>&nbsp;&nbsp;</nobr></a>
								<a class="btn btn-danger deleteCode" data-param="<?=$i;?>" data-toggle="modal" href="#deleteCode" title="Удалить"><nobr>&nbsp;&nbsp;<i class="icon-trash icon-white"></i>&nbsp;&nbsp;</nobr></a>
							</td>
						</tr>
					<?php endfor; ?>
					</tbody>
				</table>
				<a class="btn btn-info" data-toggle="modal" href="#addCode" title="Добавить промокод"><nobr>&nbsp;&nbsp;<i class="icon-plus icon-white"></i> Добавить&nbsp;&nbsp;</nobr></a>
			</div>
		<?php $this->load->view('admin_interface/includes/rightbar');?>
		<?php $this->load->view('admin_interface/modal/admin-edit-promocode');?>
		<?php $this->load->view('admin_interface/modal/admin-add-promocode');?>
		<?php $this->load->view('admin_interface/modal/admin-delete-promocode');?>
		</div>
	</div>
	<?php $this->load->view('admin_interface/includes/footer');?>
	<?php $this->load->view('admin_interface/includes/scripts');?>
	<?php $this->load->view('admin_interface/includes/datepacker');?>
	<script type="text/javascript">
		$(document).ready(function(){
			var cID = 0;
			
			$("input.calendar").datepicker($.datepicker.regional['ru']);
			
			$(".editCode").click(function(){
				var Param = $(this).attr('data-param'); cID = $("div[id = params"+Param+"]").attr("data-cid");
				var	cCode = $("div[id = params"+Param+"]").attr("data-code"); var cDFrom = $("div[id = params"+Param+"]").attr("data-datefrom");
				var cDTo = $("div[id = params"+Param+"]").attr("data-dateto");
				$(".idCode").val(cID);$("#ecCode").val(cCode);$("#ecDateFrom").val(cDFrom);$("#ecDateTo").val(cDTo);
			});
			
			$("#ecsend").click(function(event){
				var err = false;
				$(".control-group").removeClass('error');
				$(".help-inline").hide();
				$(".ecinput").each(function(i,element){
					if($(this).val()==''){
						$(this).parents(".control-group").addClass('error');
						$(this).siblings(".help-inline").html("Поле не может быть пустым").show();
						err = true;
					}
				});
				if(err){event.preventDefault();}
			});
			
			$("#acsend").click(function(event){
				var err = false;
				$(".control-group").removeClass('error');
				$(".help-inline").hide();
				$(".acinput").each(function(i,element){
					if($(this).val()==''){
						$(this).parents(".control-group").addClass('error');
						$(this).siblings(".help-inline").html("Поле не может быть пустым").show();
						err = true;
					}
				});
				if(err){event.preventDefault();}
			});
			
			$(".mkmodal").on("hidden",function(){$("#msgalert").remove();$(".control-group").removeClass('error');$(".help-inline").hide();});
			$(".deleteCode").click(function(){var Param = $(this).attr('data-param'); cID = $("div[id = params"+Param+"]").attr("data-cid");});
			$("#DelCode").click(function(){location.href='<?=$baseurl;?>admin-panel/management/promocode/codeid/'+cID;});
		});
	</script>
</body>
</html>
