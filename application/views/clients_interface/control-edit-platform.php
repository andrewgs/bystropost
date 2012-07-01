<!DOCTYPE html>
<html>
<?php $this->load->view("clients_interface/includes/head");?>

<body>
	<?php $this->load->view("clients_interface/includes/header");?>
	<div id="main">
		<?php $this->load->view("clients_interface/includes/navigation");?>
		
		<div id="stable">
			<div id="panel_menu" style="margin-left:0;">
				<?=anchor('webmaster-panel/actions/platforms','&larr; Вернуться назад');?>
			</div>
			<?php $this->load->view('alert_messages/alert-error');?>
			<div class="clear"></div>
			<?php $this->load->view('alert_messages/alert-info');?>
			<?php $this->load->view("forms/frmeditplatform");?>
		</div>
	</div>
	<?php $this->load->view("clients_interface/includes/footer");?>
	<?php $this->load->view("clients_interface/includes/scripts");?>
	<script src="<?=$baseurl;?>javascript/redactor/redactor.js"></script>
	<script src="<?=$baseurl;?>javascript/scrollto-min.js"></script>
	<script type="text/javascript">
		$(document).ready(function(){
			$(".redactor").redactor({toolbar:'default',lang: 'ru','fixed': true});
			$("#subject [value='<?=$platform['subject'];?>']").attr("selected", "selected");
			$("#tsbj").html('<i><b>'+$("#subject option:selected").html()+'</b></i>');
			$("#amount [value='<?=$platform['amount'];?>']").attr("selected", "selected");
			$("#reviews [value='<?=$platform['reviews'];?>']").attr("selected", "selected");
			$("#thematically [value='<?=$platform['thematically'];?>']").attr("selected", "selected");
			$("#illegal [value='<?=$platform['illegal'];?>']").attr("selected", "selected");
			
		<?php if(count($mymarkets)):?>
			$("#btnDelMarketLine").show();
		<?php endif;?>
			
			$("#addMarket").click(function(event){
				var err = false;
				$(".ErrImg").remove();
				$(".inpval").each(function(i,element){
					if($(this).val()==''){
						$(this).after('<img class="ErrImg" src="<?=$baseurl;?>images/icons/exclamation.png" title="Поле не может быть пустым">');
						err = true;
					}
				});
				if($("#subject").val() == 0){
						alert("Не указана тематика");
						err = true;
				}
				if(err){
						event.preventDefault();
						$.scrollTo(400,500);
				}
			});
			
			$("#btnAddMarketLine").click(function(){var lastObj = $("div[list='MarketLine']:last");$(lastObj).after('<div list="MarketLine"></div>');lastObj = $("div[list='MarketLine']:last");$(lastObj).load("<?=$baseurl;?>views/market-profile",function(){var cnt = $("div[list='MarketLine']").size();if(cnt > 1) $("#btnDelMarketLine").show();});});
			$("#btnDelMarketLine").click(function(){$("div[list='MarketLine']:last").remove();var cnt = $("div[list='MarketLine']").size();if(cnt <= 1) $("#btnDelMarketLine").hide();});
			
		});
	</script>
</body>
</html>