<!DOCTYPE html>
<html lang="en">
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
						<?=anchor($this->uri->uri_string(),"Редактирование площадки");?>
					</li>
				</ul>
				<?php $this->load->view("alert_messages/alert-error");?>
				<?php $this->load->view("alert_messages/alert-success");?>
			</div>
			<div class="span12">
				<?php $this->load->view("forms/frmeditplatform");?>
			</div>
		</div>
	</div>
	<?php $this->load->view("clients_interface/includes/footer");?>
	<?php $this->load->view("clients_interface/includes/scripts");?>
	<script src="<?=$baseurl;?>javascript/scrollto-min.js"></script>
	<script type="text/javascript">
		$(document).ready(function(){
			$("#subject [value='<?=$platform['subject'];?>']").attr("selected", "selected");
			$("#cms [value='<?=$platform['cms'];?>']").attr("selected", "selected");
			$("#tsbj").html('<b>'+$("#subject option:selected").html()+'</b>');
			$("#reviews [value='<?=$platform['reviews'];?>']").attr("selected", "selected");
			$("#thematically [value='<?=$platform['thematically'];?>']").attr("selected", "selected");
			$("#illegal [value='<?=$platform['illegal'];?>']").attr("selected", "selected");
			$("#imgpos [value='<?=$platform['imgpos'];?>']").attr("selected", "selected");
			
		<?php if(count($mymarkets)):?>
			$("#btnDelMarketLine").show();
		<?php endif;?>
			
			$("#addMarket").click(function(event){
				var err = false; var top = 0;
				$(".reg-form-input").css("border-color","#D0D0D0");
				$(".ErrImg").remove();
				$(".inpval").each(function(i,element){
					if($(this).val()==''){
						$(this).css('border-color','#ff8080');
						$(this).after('<img class="ErrImg" src="<?=$baseurl;?>images/icons/exclamation.png" title="Поле не может быть пустым">');
						err = true;
					}
				});
				$(".imgsize").each(function(i,element){
					if($(this).val() === '0'){
						$(this).css('border-color','#ff8080');
						$(this).after('<img class="ErrImg" src="<?=$baseurl;?>images/icons/exclamation.png" title="Укажите точные размеры">');
						err = true;
						top = 600;
					}
				});
				if(err){
					event.preventDefault();
					$.scrollTo(top,500);
				}
			});
		<?php if(!$userinfo['remote'] || $platform['manager'] != 2):?>
			$("#btnAddMarketLine").click(function(){var lastObj = $("div[list='MarketLine']:last");$(lastObj).after('<div list="MarketLine"></div>');lastObj = $("div[list='MarketLine']:last");$(lastObj).load("<?=$baseurl;?>views/market-profile",function(){var cnt = $("div[list='MarketLine']").size();if(cnt > 1) $("#btnDelMarketLine").show();});});
			$("#btnDelMarketLine").click(function(){$("div[list='MarketLine']:last").remove();var cnt = $("div[list='MarketLine']").size();if(cnt <= 1) $("#btnDelMarketLine").hide();});
		<?php endif;?>
			$("#reset").click(function(){
				window.location="<?=$baseurl;?>webmaster-panel/actions/platforms"
			})
		});
	</script>
</body>
</html>