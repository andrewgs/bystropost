<!DOCTYPE html>
<html lang="en">
<?php $this->load->view('admin_interface/includes/head');?>
<body>
	<?php $this->load->view('admin_interface/includes/header');?>
	<div class="container">
		<div class="row">
			<div class="span9">
				<ul class="breadcrumb">
					<li class="active">
						<?=anchor('admin-panel/actions/statistic','Статистика долгов');?>
					</li>
				</ul>
				<?php $this->load->view('alert_messages/alert-error');?>
				<?php $this->load->view('alert_messages/alert-success');?>
				<table class="table table-striped table-bordered">
					<tbody>
						<tr>
							<td class="w400">Задолженность до 3-х дней:<br/>(включительно)</td>
							<td class="w100"><?=$stat['to3days']['cnt'];?> записей</td>
							<td class="w85"><?=$stat['to3days']['sum'];?>.00 руб.</td>
							<td>
							<?php if($stat['to3days']['cnt']):?>
								<button class="btn btn-info AlertByDay" data-days="3" title="Уведомить о задолженности: 3 дня"><i class="icon-envelope icon-white"></i></button>
							<?php else:?>
								<button class="btn btn-success"><i class="icon-ok icon-white"></i></button>
							<?php endif;?>
							</td>
						</tr>
						<tr>
							<td class="w400">Задолженность до 4-х дней:<br/>(включительно)</td>
							<td class="w100"><?=$stat['to4days']['cnt'];?> записей</td>
							<td class="w85"><?=$stat['to4days']['sum'];?>.00 руб.</td>
							<td>
							<?php if($stat['to4days']['cnt']):?>
								<button class="btn btn-warning AlertByDay" data-days="4" title="Уведомить о задолженности: 4 дня"><i class="icon-envelope icon-white"></i></button>
							<?php else:?>
								<button class="btn btn-success"><i class="icon-ok icon-white"></i></button>
							<?php endif;?>
							</td>
						</tr>
						<tr>
							<td class="w400">Задолженность до 5-х дней:<br/>(включительно)</td>
							<td class="w100"><?=$stat['to5days']['cnt'];?> записей</td>
							<td class="w85"><?=$stat['to5days']['sum'];?>.00 руб.</td>
							<td>
							<?php if($stat['to5days']['cnt']):?>
								<button class="btn btn-danger AlertByDay" data-days="5" title="Уведомить о задолженности: 5 дня"><i class="icon-envelope icon-white"></i></button>
								<button class="btn btn-inverse Locked5Day" data-days="5" title="Заблокировать"><i class="icon-lock icon-white"></i></button>
							<?php else:?>
								<button class="btn btn-success"><i class="icon-ok icon-white"></i></button>
							<?php endif;?>
							</td>
						</tr>
						<tr>
							<td class="w400">Задолженность более 5-х дней:</td>
							<td class="w100"><?=$stat['from5days']['cnt'];?> записей</td>
							<td class="w85"><?=$stat['from5days']['sum'];?>.00 руб.</td>
							<td>
							<?php if($stat['from5days']['cnt']):?>
								<button class="btn btn-danger AlertByDay" data-days="6" title="Уведомить о задолженности более 5 дней"><i class="icon-envelope icon-white"></i></button>
								<button class="btn btn-inverse Locked5Day" data-days="6" title="Заблокировать"><i class="icon-lock icon-white"></i></button>
							<?php else:?>
								<button class="btn btn-success"><i class="icon-ok icon-white"></i></button>
							<?php endif;?>
							</td>
						</tr>
					</tbody>
				</table>
				<div id="load"></div>
			</div>
		<?php $this->load->view('admin_interface/includes/rightbar');?>
		</div>
	</div>
	<?php $this->load->view('admin_interface/includes/footer');?>
	<?php $this->load->view('admin_interface/includes/scripts');?>
	<script type="text/javascript">
		$(document).ready(function(){
			$("button").removeClass("disabled");
			$("button").removeAttr("disabled");
			
			$(".AlertByDay").click(function(){
				var days = $(this).attr("data-days");
				send_alert(days,this);
			});
			$(".Locked5Day").click(function(){
				var days = $(this).attr("data-days");
				send_locked(days,this);
			});
			
			function send_alert(days,object){
				$.post("<?=$baseurl;?>admin-panel/actions/alert-debet",{'days':days},function(data){
					if(data.status){
						$(object).html('<i class="icon-ok icon-white"></i>');
						$(object).addClass("disabled");
						$(object).attr("disabled","disabled");
					}
				},"json");
			}
			
			function send_locked(days,object){
				$.post("<?=$baseurl;?>admin-panel/actions/locked-debet",{'days':days},function(data){
					if(data.status){
						$(object).html('<i class="icon-ok icon-white"></i>');
						$(object).addClass("disabled");
						$(object).attr("disabled","disabled");
					}
				},"json");
			}
		});
	</script>
</body>
</html>