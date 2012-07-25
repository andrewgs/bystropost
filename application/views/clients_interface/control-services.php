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
							<th class="w500"><center>Название услуги</center></th>
							<th class="w85"><center>Цена</center></th>
							<th class="w50">&nbsp;</th>
						</tr>
					</thead>
					<tbody>
					<?php for($i=0;$i<count($attached);$i++):?>
						<tr>
							<td class="w100" style="text-align:center; vertical-align:middle;"><nobr><?=$attached[$i]['date'];?></nobr></td>
							<td class="w500"><?=$attached[$i]['stitle'];?></td>
							<td class="w85" style="text-align:center; vertical-align:middle;"><?=$attached[$i]['price'];?></td>
							<td class="w50" style="text-align:center; vertical-align:middle;">
								<div id="params<?=$i;?>" style="display:none" data-sid="<?=$attached[$i]['id'];?>" data-uid="<?=$mails[$i]['sender'];?>" data-position="<?=$mails[$i]['position'];?>"></div>
								<a class="btn btn-danger deleteService" data-param="<?=$i;?>" data-toggle="modal" href="#deleteService" title="Удалить"><nobr>&nbsp;&nbsp;<i class="icon-trash icon-white"></i>&nbsp;&nbsp;</nobr></a>
							</td>
						</tr>
					<?php endfor; ?>
					</tbody>
				</table>
				<a class="btn btn-info" data-toggle="modal" href="#addService" title="Добавить"><nobr>&nbsp;&nbsp;<i class="icon-plus icon-white"></i> Добавить&nbsp;&nbsp;</nobr></a>
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
			var balance = <?=$userinfo['balance'];?>;
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
		<?php if($userinfo['balance'] >= $minprice):?>
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
			$("#ServicesList").change(function(){
				var price = $("#ServicesList option:selected").attr('data-price');
				$("#gonorar").html(price);
			});
		<?php endif;?>
			$(".mkmodal").on("hidden",function(){$("#msgalert").remove();$(".control-group").removeClass('error');$(".help-inline").hide(); $("#ServicesList").val(1);});
			$(".deleteService").click(function(){var Param = $(this).attr('data-param'); sID = $("div[id = params"+Param+"]").attr("data-sid");});
			$("#DelService").click(function(){location.href='<?=$baseurl;?>admin-panel/management/services/serviceid/'+sID;});
		});
	</script>
</body>
</html>