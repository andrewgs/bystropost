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
							<th class="w85"><center>Название</center></th>
							<th class="w85"><center>Логин</center></th>
							<th class="w85"><center>Пароль</center></th>
							<th class="w130">&nbsp;</th>
						</tr>
					</thead>
					<tbody>
					<?php for($i=0;$i<count($accounts);$i++):?>
						<tr>
							<td class="w85" style="text-align:center; vertical-align:middle;"><nobr><?=$accounts[$i]['mtitle'];?></nobr></td>
							<td class="w85"><?=$accounts[$i]['login'];?></td>
							<td class="w85"><?=$accounts[$i]['password'];?></td>
							<td class="w130" style="text-align:center; vertical-align:middle; max-width:0;">
								<nobr>
									<div id="params<?=$i;?>" style="display:none" data-mid="<?=$accounts[$i]['id'];?>" data-login="<?=$accounts[$i]['login'];?>" data-market="<?=$accounts[$i]['market'];?>"></div>
									<a class="btn btn-info updateMarket none" data-param="<?=$i;?>" href="#" title="Обновить список площадок">&nbsp;<i class="icon-refresh icon-white"></i>&nbsp;</a>
									<a class="btn btn-success editMarket none" data-param="<?=$i;?>" data-toggle="modal" href="#editMarket" title="Редактировать биржу">&nbsp;<i class="icon-pencil icon-white"></i>&nbsp;</a>
									<a class="btn btn-danger deleteMarket" data-param="<?=$i;?>" data-toggle="modal" href="#deleteMarket" title="Удалить">&nbsp;<i class="icon-trash icon-white"></i>&nbsp;</a>
									<span id="timer<?=$i;?>" style="display:none"></span>
								</nobr>
							</td>
						</tr>
					<?php endfor; ?>
					</tbody>
				</table>
				<a class="btn btn-info" data-toggle="modal" href="#addMarket" title="Добавить"><nobr>&nbsp;&nbsp;<i class="icon-plus icon-white"></i> Добавить&nbsp;&nbsp;</nobr></a>
			</div>
		<?php $this->load->view("clients_interface/includes/rightbar");?>
		<?php $this->load->view('clients_interface/modal/clients-add-market');?>
		<?php $this->load->view('clients_interface/modal/clients-edit-market');?>
		<?php $this->load->view('clients_interface/modal/clients-delete-market');?>
		</div>
	</div>
	<?php $this->load->view("clients_interface/includes/footer");?>
	<?php $this->load->view("clients_interface/includes/scripts");?>
	<script type="text/javascript">
		$(document).ready(function(){
			var mID = 0;
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
			
			$("#amsend").click(function(event){
				var err = false;
				$(".control-group").removeClass('error');
				$(".help-inline").hide();
				$(".ainpval").each(function(i,element){
					if($(this).val()==''){
						$(this).parents(".control-group").addClass('error');
						$(this).siblings(".help-inline").html("Поле не может быть пустым").show();
						err = true;
					}
				});
				if(err){event.preventDefault();}
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
			
			$("#MarketList").change(function(){
				if($(this).val() == 3){$("#prsape").slideDown(400);
				}else{$("#prsape").slideUp(400);}
			});
			
			function load_platforms(market,objSpan){
				$.post("<?=$baseurl;?>webmaster-panel/actions/markets/loading",{'market':market},function(data){
					if(data.status){
						$(objSpan).html('Загружено: '+data.plload+'<br/>Добавленно: '+data.plcnt);
					}},"json");
			}
			function parsing_platforms(market,objSpan,timer){
				$.post("<?=$baseurl;?>webmaster-panel/actions/markets/parsing",{'market':mID},function(data){
					if(data.status){
						var	intervalID = 0;
						intervalID = setInterval(function(){
							timer = timer - 1;
							$(objSpan).show().html('Обработка данных:<br/>'+timer+' cек.');
							if(timer == 0){
								clearInterval(intervalID);
								$(objSpan).html('Pагрузка данных! Ожидайте ...');
								load_platforms(market,objSpan);
							}
						}, 1000);
					}
				},"json");
			}
			$(".mkmodal").on("hidden",function(){$("#msgalert").remove();$(".control-group").removeClass('error');$(".help-inline").hide(); $("#MarketList").val(1);$(".inpval").val('');$("#loading").val('');$("#prsape").hide();});
			$(".deleteMarket").click(function(){var Param = $(this).attr('data-param'); mID = $("div[id = params"+Param+"]").attr("data-mid");});
			$("#DelMarket").click(function(){location.href='<?=$baseurl;?>webmaster-panel/actions/markets/delete/marketid/'+mID;});
		});
	</script>
</body>
</html>