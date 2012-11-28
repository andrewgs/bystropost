<!DOCTYPE html>
<html lang="en">
<?php $this->load->view('admin_interface/includes/head');?>
<body>
	<?php $this->load->view('admin_interface/includes/header');?>
	<div class="container">
		<div class="row">
			<div class="span9">
				<ul class="breadcrumb">
					<li>
						<?=anchor($this->session->userdata('backpath'),'Список вебмастеров');?><span class="divider">/</span>
					</li>
					<li class="active">
						<?=anchor($this->uri->uri_string(),'Список биржевых аккаунтов');?>
					</li>
				</ul>
				<?php $this->load->view('alert_messages/alert-error');?>
				<?php $this->load->view('alert_messages/alert-success');?>
				<table class="table table-bordered" style="width: 700px;">
					<thead>
						<tr>
							<th class="w85"><center>Название</center></th>
							<th class="w85"><center>Логин</center></th>
							<th class="w100"><center>Пароль</center></th>
							<th class="w100">&nbsp;</th>
						</tr>
					</thead>
					<tbody>
					<?php for($i=0;$i<count($accounts);$i++):?>
						<tr>
							<td class="w85" style="text-align:center; vertical-align:middle;"><nobr><?=$accounts[$i]['mtitle'];?></nobr></td>
							<td class="w85"><?=$accounts[$i]['login'];?></td>
							<td class="w100"><?=$accounts[$i]['password'];?></td>
							<td class="w100" style="text-align:center; vertical-align:middle; max-width:0;">
								<nobr>
									<div id="params<?=$i;?>" style="display:none" data-mid="<?=$accounts[$i]['id'];?>" data-login="<?=$accounts[$i]['login'];?>" data-market="<?=$accounts[$i]['market'];?>"></div>
								<?php if($accounts[$i]['status']):?>
									<a class="btn btn-success editMarket none" data-param="<?=$i;?>" data-toggle="modal" href="#editMarket" title="Редактировать биржу">&nbsp;<i class="icon-pencil icon-white"></i>&nbsp;</a>
									<a class="btn btn-warning makeMarket" data-param="<?=$i;?>" data-toggle="modal" href="#disabledMarket" title="Отключить">&nbsp;<i class="icon-off icon-white"></i>&nbsp;</a>
								<?php else:?>
									<a class="btn makeMarket" data-param="<?=$i;?>" data-toggle="modal" href="#enabledMarket" title="Включить">&nbsp;<i class="icon-off"></i>&nbsp;</a>
								<?php endif;?>
									<a class="btn btn-danger makeMarket" data-param="<?=$i;?>" data-toggle="modal" href="#deleteMarket" title="Удалить">&nbsp;<i class="icon-trash icon-white"></i>&nbsp;</a>
									<span id="timer<?=$i;?>" style="display:none"></span>
								</nobr>
							</td>
						</tr>
					<?php endfor; ?>
					</tbody>
				</table>
			</div>
		<?php $this->load->view('admin_interface/includes/rightbar');?>
		<?php $this->load->view('admin_interface/modal/admin-edit-market');?>
		<?php $this->load->view('clients_interface/modal/clients-delete-market');?>
		<?php $this->load->view('clients_interface/modal/clients-disabled-market');?>
		<?php $this->load->view('clients_interface/modal/clients-enabled-market');?>
		</div>
	</div>
	<?php $this->load->view('admin_interface/includes/footer');?>
	<?php $this->load->view('admin_interface/includes/scripts');?>
	<script type="text/javascript">
		$(document).ready(function(){
			$("#MarketList").val(1);
			$(".updateMarket").click(function(){
				var Param = $(this).attr('data-param');
				var objSpan = $("span[id = timer"+Param+"]");
				mID = $("div[id = params"+Param+"]").attr("data-mid");
				var timer = 40;
				$("div[id = params"+Param+"]").parents('tr').addClass('alert alert-info');
				$(objSpan).siblings('a').hide();
				$(objSpan).show().html('Запуск. Ожидайте...');
				parsing_platforms(mID,objSpan,timer);
			});
			$(".editMarket").click(function(){
				var Param = $(this).attr('data-param'); mID = $("div[id = params"+Param+"]").attr("data-mid");
				var login = $("div[id = params"+Param+"]").attr("data-login");
				var	market = $("div[id = params"+Param+"]").attr("data-market");
				$(".idMarket").val(mID);$("#elogin").val(login);$("#eMarketList").val(market);
			});
			$("#smsend").click(function(event){
				var err = false;
				$(".control-group").removeClass('error');
				$(".help-inline").hide();
				$(".einpval").each(function(i,element){
					if($(this).val()==''){
						$(this).parents(".control-group").addClass('error');
						$(this).siblings(".help-inline").html("Поле не может быть пустым").show();
						err = true;
					}
				});
				if(err){event.preventDefault();}
			});
			$("#MarketList").change(function(){if($(this).val() == 3){$("#prsape").slideDown(400);}else{$("#prsape").slideUp(400);}});
			$(".mkmodal").on("hidden",function(){$("#msgalert").remove();$(".control-group").removeClass('error');$(".help-inline").hide(); $("#MarketList").val(1);$(".inpval").val('');$("#loading").val('');$("#prsape").hide();});
			$(".makeMarket").click(function(){var Param = $(this).attr('data-param'); mID = $("div[id = params"+Param+"]").attr("data-mid");});
			$("#DelMarket").click(function(){location.href='<?=$baseurl;?>admin-panel/management/users/remoteid/<?=$this->uri->segment(5);?>/delete/marketid/'+mID;});
			$("#DisMarket").click(function(){location.href='<?=$baseurl;?>admin-panel/management/users/remoteid/<?=$this->uri->segment(5);?>/disabled/marketid/'+mID;});
			$("#EnbMarket").click(function(){location.href='<?=$baseurl;?>admin-panel/management/users/remoteid/<?=$this->uri->segment(5);?>/enabled/marketid/'+mID;});
		});
	</script>
</body>
</html>
