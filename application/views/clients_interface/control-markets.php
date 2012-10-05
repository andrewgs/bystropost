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
							<th class="w100"><center>Название</center></th>
							<th class="w85"><center>Логин</center></th>
							<th class="w85"><center>Пароль</center></th>
							<th class="w50">&nbsp;</th>
						</tr>
					</thead>
					<tbody>
					<?php for($i=0;$i<count($accounts);$i++):?>
						<tr>
							<td class="w100" style="text-align:center; vertical-align:middle;"><nobr><?=$accounts[$i]['mtitle'];?></nobr></td>
							<td class="w85"><?=$accounts[$i]['login'];?></td>
							<td class="w85"><?=$accounts[$i]['password'];?></td>
							<td class="w50" style="text-align:center; vertical-align:middle;">
								<div id="params<?=$i;?>" style="display:none" data-mid="<?=$accounts[$i]['id'];?>"></div>
								<a class="btn btn-danger deleteMarket" data-param="<?=$i;?>" data-toggle="modal" href="#deleteMarket" title="Удалить"><nobr>&nbsp;&nbsp;<i class="icon-trash icon-white"></i>&nbsp;&nbsp;</nobr></a>
							</td>
						</tr>
					<?php endfor; ?>
					</tbody>
				</table>
				<a class="btn btn-info" data-toggle="modal" href="#addMarket" title="Добавить"><nobr>&nbsp;&nbsp;<i class="icon-plus icon-white"></i> Добавить&nbsp;&nbsp;</nobr></a>
			</div>
		<?php $this->load->view("clients_interface/includes/rightbar");?>
		<?php $this->load->view('clients_interface/modal/clients-add-market');?>
		<?php $this->load->view('clients_interface/modal/clients-delete-market');?>
		</div>
	</div>
	<?php $this->load->view("clients_interface/includes/footer");?>
	<?php $this->load->view("clients_interface/includes/scripts");?>
	<script type="text/javascript">
		$(document).ready(function(){
			var mID = 0;
			$(".mkmodal").on("hidden",function(){$("#msgalert").remove();$(".control-group").removeClass('error');$(".help-inline").hide(); $("#MarketList").val(1);$(".inpval").val('');$("#loading").val('');});
			$(".deleteMarket").click(function(){var Param = $(this).attr('data-param'); mID = $("div[id = params"+Param+"]").attr("data-mid");});
			$("#DelMarket").click(function(){location.href='<?=$baseurl;?>webmaster-panel/actions/markets/delete/marketid/'+mID;});
		});
	</script>
</body>
</html>