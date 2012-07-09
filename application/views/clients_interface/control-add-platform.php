<!DOCTYPE html>
<html>
<?php $this->load->view("clients_interface/includes/head");?>

<body>
	<?php $this->load->view("clients_interface/includes/header");?>
	
	<div class="container">
		<div class="row">
			<div class="span12">
				<ul class="breadcrumb">
					<li>
						<?=anchor("webmaster-panel/actions/platforms","Все площадки");?><span class="divider">/</span>
					</li>
					<li class="active">
						<?=anchor("webmaster-panel/actions/platforms/add-platform","Добавление площадки");?>
					</li>
				</ul>
				<?php $this->load->view("alert_messages/alert-error");?>
				<?php $this->load->view("alert_messages/alert-success");?>
			</div>
			<div class="span12">
				<?php $this->load->view("forms/frmaddplatform");?>
			</div>
		</div>
	</div>
	<?php $this->load->view("clients_interface/includes/footer");?>
	<?php $this->load->view("clients_interface/includes/scripts");?>
	<script src="<?=$baseurl;?>javascript/jquery.megaselectlist.js"></script>
	<script src="<?=$baseurl;?>javascript/redactor/redactor.js"></script>
	<script src="<?=$baseurl;?>javascript/scrollto-min.js"></script>
	<script type="text/javascript">
		$(document).ready(function(){
			$("#subject").megaselectlist({animate:true,animateevent:"click"});
			$(".redactor").redactor({toolbar:'default',lang: 'ru','fixed': true});
			
			$("#addMarket").click(function(event){
				var err = false;
				$(".ErrImg").remove();
				$(".inpval").each(function(i,element){
					if($(this).val()==''){
						$(this).css('border-color','#ff8080');
						$(this).after('<img class="ErrImg" src="<?=$baseurl;?>images/icons/exclamation.png" title="Поле не может быть пустым">');
						err = true;
					}
				});
				if($("#subject").val() == 0){
					$("#mgselect").css('background-color','#ff8080');
					err = true;
				}
				if(err){
					event.preventDefault();
					$.scrollTo(0,500);
				}
			});
			$("#mgselect").click(function(){$(this).css('background-color','#ffffff');})
			$("#reset").click(function(){window.location="<?=$baseurl;?>webmaster-panel/actions/platforms"});
			$("#btnAddMarketLine").click(function(){var lastObj = $("div[list='MarketLine']:last");$(lastObj).after('<div list="MarketLine"></div>');lastObj = $("div[list='MarketLine']:last");$(lastObj).load("<?=$baseurl;?>views/market-profile",function(){var cnt = $("div[list='MarketLine']").size();if(cnt > 1) $("#btnDelMarketLine").show();});});
			$("#btnDelMarketLine").click(function(){$("div[list='MarketLine']:last").remove();var cnt = $("div[list='MarketLine']").size();if(cnt <= 1) $("#btnDelMarketLine").hide();});
		});
	</script>
</body>
</html>