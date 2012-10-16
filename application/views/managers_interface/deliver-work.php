<!DOCTYPE html>
<html lang="en">
<?php $this->load->view("managers_interface/includes/head");?>

<body>
	<?php $this->load->view("managers_interface/includes/header");?>

	<div class="container">
		<div class="row">
			<div class="span9">
				<ul class="breadcrumb">
					<li>
						<?=anchor("manager-panel/actions/platforms","Все площадки");?><span class="divider">/</span>
					</li>
					<li class="active">
						<?=anchor($this->uri->uri_string(),$platform['url'].' от '.$platform['date']);?>
					</li>
				</ul>
				<?php $this->load->view("alert_messages/alert-error");?>
				<?php $this->load->view("alert_messages/alert-success");?>
				<?php $this->load->view('forms/frmdeliverwork');?>
			</div>
			<?php $this->load->view('managers_interface/includes/rightbar');?>
		</div>
	</div>
	<?php $this->load->view("clients_interface/includes/footer");?>
	<?php $this->load->view("clients_interface/includes/scripts");?>
	<script type="text/javascript">
		$(document).ready(function(){
			$("#TypesWork").val(1);
			$("#Market").val(1);
		<?php if(isset($typeswork[0]['mprice'])):?>
			$("#gonorar").html("<?=$typeswork[0]['mprice'];?>")
		<?php endif;?>
			$("#ValidWork").removeAttr('checked');
			$("#ValidWork").click(function(){
				if($(this).attr("checked") == 'checked'){$("#send").removeClass('disabled');}else{$("#send").addClass('disabled');};
			});
			$("#send").click(function(event){
			
				if($("#ValidWork").attr("checked") != "checked"){event.preventDefault();return false;}
				var err = false;
				$(".control-group").removeClass('error');
				$(".help-inline").hide();
				$(".inpval").each(function(i,element){
					if($(this).val()==''){
						$(this).parents(".control-group").addClass('error');
						$(this).siblings(".help-inline").html("Поле не может быть пустым").show();
						err = true;
					}
				});
				if(!err && !isFindDomenToURL("<?=$platform['url'];?>",$("#UlrLink").val())){
					$("#UlrLink").parents(".control-group").addClass('error');
					$("#UlrLink").siblings(".help-inline").html("URL не пренадлежит площадке").show();
					err = true;
				};
				if(err){event.preventDefault();}
			});
			$("#TypesWork").change(function(){
				$("#gonorar").html($("#TypesWork option:selected").attr("data-gonorar"));
			});
			$("#reset").click(function(){window.location="<?=$baseurl?>manager-panel/actions/platforms";})
		});
	</script>
</body>
</html>