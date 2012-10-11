<!DOCTYPE html>
<html lang="en">
<?php $this->load->view("admin_interface/includes/head");?>

<body>
	<?php $this->load->view("admin_interface/includes/header");?>
	<div class="container">
		<div class="row">
			<div class="span9">
				<ul class="breadcrumb">
					<li class="active">
						<?=anchor("admin-panel/actions/control","Дополнительные возможности");?>
					</li>
				</ul>
				<?php $this->load->view("alert_messages/alert-error");?>
				<?php $this->load->view("alert_messages/alert-success");?>
				<table class="table table-bordered">
					<thead>
						<tr>
							<th class="w50"><center><nobr>№ п.п</nobr></center></th>
							<th class="w275"><center>Название</center></th>
							<th class="w205">&nbsp;</th>
						</tr>
					</thead>
					<tr>
						<td><center>1</center></td>
						<td>Рассылка регистрационных данных</td>
						<td>
							<a class="btn btn-info SendMails none" style="margin-top: -5px;" href="#" title="Выполнить рассылку"><i class="icon-envelope icon-white"></i> Выполнить рассылку</a>
							<span id="SpSendMails" class="btn btn-warning" style="display:none;margin-top: -5px;"></span>
						</td>
					</tr>
					<tbody>
					
					</tbody>
				</table>
			</div>
		<?php $this->load->view("admin_interface/includes/rightbar");?>
		</div>
	</div>
	<?php $this->load->view('admin_interface/includes/footer');?>
	<?php $this->load->view("admin_interface/includes/scripts");?>
	<script type="text/javascript">
		$(document).ready(function(){
			var stopRequest = false;
			var stopScript = false;
			$(".SendMails").click(function(){
				if(confirm("Начать рассылку?") == false) return false;
				var objSpan = $("#SpSendMails");
				 var intervalID; var plcount = <?=$webmasters;?>;
				var from=0;var count = 1;
				$(objSpan).siblings('a').remove();
				ajaxRequest(count,from);
				$(objSpan).show().html('Обработка пользователей: '+parseInt(from+count)+' из '+plcount);
				intervalID = setInterval(
					function(){
						if(stopRequest){
							if(stopScript || (from+1) >=plcount){
								$(objSpan).show().html('Отправка завершена!');
								clearInterval(intervalID);
							}else{
								from = from + count;
								ajaxRequest(count,from);
								if((from+count) <=plcount){
									$(objSpan).show().html('Обработка пользователей: '+parseInt(from+count)+' из '+plcount);
								}else{
									$(objSpan).show().html('Обработка пользователей: '+plcount+' из '+plcount);
								}
								
							}
						}
					}
				,1000);
			});
			function ajaxRequest(count,from){
			
				stopRequest = false;
				stopScript = false;
				$.ajax({
					url: "<?=$baseurl;?>admin-panel/actions/control/sendind-registering-info",
					data: ({'count':count,'from':from}),
					type: "POST",
					dataType: "JSON",
					success: function(data){
						stopRequest = true;
						if(!data.nextstep){stopScript = true;}
					}
				});
			}
		});
	</script>
</body>
</html>
