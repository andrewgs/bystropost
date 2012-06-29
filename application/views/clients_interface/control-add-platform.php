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
			<?php $this->load->view("forms/frmaddplatform");?>
		</div>
	</div>
	<?php $this->load->view("clients_interface/includes/footer");?>
	<?php $this->load->view("clients_interface/includes/scripts");?>
	<script src="<?=$baseurl;?>javascript/jquery.megaselectlist.js"></script>
	<script src="<?=$baseurl;?>javascript/redactor/redactor.js"></script>
	<script type="text/javascript">
		$(document).ready(function(){
			$("#thematically").megaselectlist({animate:true,animateevent:"click"});
			$(".redactor").redactor({toolbar:'default',lang: 'ru','fixed': true});
			
			$("#btnAddMarketLine").click(function(){var lastObj = $("div[list='MarketLine']:last");$(lastObj).after('<div list="MarketLine"></div>');lastObj = $("div[list='MarketLine']:last");$(lastObj).load("<?=$baseurl;?>views/market-profile",function(){var cnt = $("div[list='MarketLine']").size();if(cnt > 1) $("#btnDelMarketLine").show();});});
			$("#btnDelMarketLine").click(function(){$("div[list='MarketLine']:last").remove();var cnt = $("div[list='MarketLine']").size();if(cnt <= 1) $("#btnDelMarketLine").hide();});
			
		});
	</script>
</body>
</html>