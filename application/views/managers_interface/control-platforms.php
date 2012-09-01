<!DOCTYPE html>
<html lang="en">
<?php $this->load->view("managers_interface/includes/head");?>

<body>
	<?php $this->load->view("managers_interface/includes/header");?>
	<div class="container">
		<div class="row">
			<div class="span9">
				<ul class="breadcrumb">
					<li class="active">
						<?=anchor("manager-panel/actions/platforms","Все площадки");?>
					</li>
				<?php if($userinfo['uid'] == 2):?>
					<li style="float:right;">
						<?=anchor('manager-panel/actions/platforms/remote_deliver_work','Импортировать работы',array('class'=>'btn btn-info DLWorks','style'=>'margin-top: -5px;'));?>
					</li>
				<?php endif;?>
				</ul>
				<?php $this->load->view("alert_messages/alert-error");?>
				<?php $this->load->view("alert_messages/alert-success");?>
				<div class="alert alert-info" id="msdownloading" style="display:none;">
					<h3>Ожидайте!</h3>Производится импорт выполненных работ. Это может занять некоторое время...
				</div>
				<table class="table table-bordered">
					<thead>
						<tr>
							<th><center><nobr>URL площадки</nobr></center></th>
							<th><center><nobr>тиц / pr</nobr></center></th>
							<th><center>Дата</center></th>
							<th><center>Статус</center></th>
						</tr>
					</thead>
					<tbody>
					<?php for($i=0;$i<count($platforms);$i++):?>
						<tr>
							<td class="w275 ttpl">
							<?php if(!$platforms[$i]['locked'] && $platforms[$i]['status']):?>
								<?=anchor('manager-panel/actions/platforms/view-platform/'.$platforms[$i]['id'],$platforms[$i]['url'],array('title'=>'Просмотреть площадку'));?>
							<?php else:?>
								<?=$platforms[$i]['url'];?>
							<?php endif;?>
							</td>
							<td class="w85"><center><?=$platforms[$i]['tic'];?> / <?=$platforms[$i]['pr'];?></center></td>
						<?php if(!$platforms[$i]['status']):?>
							<td class="w85" data-status="noactive">
						<?php elseif($platforms[$i]['locked']):?>
							<td class="w85" data-locked="locked">
						<?php else:?>
							<td class="w85">
						<?php endif;?>
								<center><nobr><?=$platforms[$i]['date'];?></nobr></center>
							</td>
							<td class="w50" style="text-align: center; vertical-align: middle;">
							<?php if(!$platforms[$i]['status']):?>
								<i class="icon-exclamation-sign" title="Не активна"></i>
							<?php endif;?>
							<?php if($platforms[$i]['locked']):?>
								<i class="icon-lock" title="Заблокирована"></i>
							<?php endif;?>
							<?php if(!$platforms[$i]['locked'] && $platforms[$i]['status']):?>
								<?=anchor('manager-panel/actions/platforms/'.$platforms[$i]['id'].'/deliver-work','&nbsp;<i class="icon-briefcase"></i>&nbsp',array('class'=>'btn btn-info DeliverWork','title'=>'Сдать задание'));?>
							<?php endif;?>
							</td>
						</tr>
					<?php endfor; ?>
					</tbody>
				</table>
			</div>
		<?php $this->load->view("managers_interface/includes/rightbar");?>
		</div>
	</div>
	<?php $this->load->view("managers_interface/includes/footer");?>
	<?php $this->load->view("managers_interface/includes/scripts");?>
	<script type="text/javascript">
		$(document).ready(function(){
			$("td[data-status='noactive']").each(function(e){
				$(this).addClass('alert alert-message'); $(this).siblings('td').addClass('alert alert-message');
			});
			$("td[data-locked='locked']").each(function(e){
				$(this).addClass('alert alert-error'); $(this).siblings('td').addClass('alert alert-error');
			});
			$(".DLWorks").click(function(event){$("#msdownloading").show();});
		});
	</script>
</body>
</html>
