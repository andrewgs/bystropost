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
						<?=anchor("webmaster-panel/actions/platforms","Все площадки");?>
					</li>
				<?php if($userinfo['remote']):?>
					<li style="float:right;">
						<?=anchor('webmaster-panel/actions/platforms/refresh-list','Обновить список',array('class'=>'btn btn-info refresh','style'=>'margin-top: -5px;'));?>
					</li>
				<?php endif;?>
				</ul>
				<?php $this->load->view("alert_messages/alert-error");?>
				<?php $this->load->view("alert_messages/alert-success");?>
			<?php if($userinfo['remote']):?>
				<div class="alert alert-info" id="msrefresh" style="display:none;">
					<h3>Ожидайте!</h3>Производится построение списка площадок. Это может занять некоторое время...
				</div>
			<?php endif;?>
				<table class="table table-bordered">
					<thead>
						<tr>
							<th><center><nobr>URL площадки</nobr></center></th>
							<th><center>Биржи</center></th>
							<th><center><nobr>тиц / pr</nobr></center></th>
							<th><center><nobr>Всего заданий<br/>Не опл.заданий</nobr></center></th>
							<th><center>Дата</center></th>
							<th>Управл.</th>
						</tr>
					</thead>
					<tbody>
					<?php for($i=0;$i<count($platforms);$i++):?>
						<tr class="align-center">
							<td class="w275 ttpl">
							<?php if(!$platforms[$i]['locked'] && !$userinfo['lock']):?>
								<div id="params<?=$i;?>" style="display:none" data-pid="<?=$platforms[$i]['id'];?>" data-status="<?=$platforms[$i]['status'];?>"></div>
								<a class="editPlatform" data-param="<?=$i;?>" data-toggle="modal" href="#editPlatform" title="Свойства"><?=$platforms[$i]['url'];?></a>
							<?php else:?>
								<?=$platforms[$i]['url'];?>
							<?php endif;?>
							</td>
						<?php if(!$platforms[$i]['status']):?>
							<td class="w85" data-status="noactive">
						<?php elseif($platforms[$i]['locked']):?>
							<td class="w85" data-locked="locked">
						<?php else:?>
							<td class="w85">
						<?php endif;?>
						<?php for($j=0,$cnt=0;$j<count($markets);$j++):?>
							<?php if($platforms[$i]['id'] == $markets[$j]['platform']):?>
								<?php if($cnt>=1):?>
									<?php if($markets[$j]['id'] == $markets[$j-1]['id']):?>
										<?php continue;?>
									<?php endif;?>
								<?php endif;?>
								<?=anchor($markets[$j]['url'],'<img src="'.$baseurl.'markets/viewimage/'.$markets[$j]['id'].'" alt="'.$markets[$j]['title'].'" title="'.$markets[$j]['title'].'"/>',array("class"=>'','target'=>'_blank'));?>
								<?php $cnt++;?>
							<?php endif;?>
						<?php endfor;?>
							</td>
							<td class="w85">
								<center>
									<?=$platforms[$i]['tic'];?> / <?=$platforms[$i]['pr'];?>
								</center>
							</td>
							<td class="w85"><center><nobr><?=anchor('webmaster-panel/actions/finished-jobs/platform/platformid/'.$platforms[$i]['id'],$platforms[$i]['torders'].' / <b>'.$platforms[$i]['uporders'].'</b>',array('style'=>'text-decoration:none;'));?></nobr></center></td>
							<td class="w85"><center><nobr><?=$platforms[$i]['date'];?></nobr></center></td>
							<td class="w50" style="text-align: center; vertical-align: middle;">
							<?php if(!$platforms[$i]['locked']):?>
								<?=anchor('webmaster-panel/actions/platforms/edit-platform/'.$platforms[$i]['id'],'<nobr>&nbsp;&nbsp;<i class="icon-tags icon-white"></i>&nbsp;&nbsp;</nobr>',array('title'=>'Редактировать площадку','class'=>'btn btn-success '));?>
							<?php endif;?>
							<?php if(!$platforms[$i]['status']):?>
								<i class="icon-exclamation-sign" title="Не активна" style="margin-top:10px;"></i>
							<?php endif;?>
							<?php if($platforms[$i]['locked']):?>
								<i class="icon-lock" title="Заблокирована" style="margin-top:10px;"></i>
							<?php endif;?>
							</td>
						</tr>
					<?php endfor; ?>
					</tbody>
				</table>
		<?php if(!$userinfo['remote'] && !$userinfo['lock']):
			echo anchor('webmaster-panel/actions/platforms/add-platform','Добавить площадку',array('class'=>'btn btn-info'));
		endif;?>
			</div>
		<?php $this->load->view("clients_interface/includes/rightbar");?>
		<?php $this->load->view('clients_interface/modal/clients-edit-platform');?>
		</div>
	</div>
	<?php $this->load->view("clients_interface/includes/footer");?>
	<?php $this->load->view("clients_interface/includes/scripts");?>
	<script type="text/javascript">
		$(document).ready(function(){
			var pID = 0;
			$("td[data-status='noactive']").each(function(e){
				$(this).addClass('alert alert-message'); $(this).siblings('td').addClass('alert alert-message');
			});
			$("td[data-locked='locked']").each(function(e){
				$(this).addClass('alert alert-error'); $(this).siblings('td').addClass('alert alert-error');
			});
		<?php if($userinfo['remote']):?>
			$(".refresh").click(function(){$("#msrefresh").show();});
		<?php endif;?>
			$(".editPlatform").click(function(){
				var Param = $(this).attr('data-param'); pID = $("div[id = params"+Param+"]").attr("data-pid");
				var	status = $("div[id = params"+Param+"]").attr("data-status");
				$(".idPlatform").val(pID);if(status == 1){$("#statusPlatform").attr('checked','checked');}else{$("#statusPlatform").removeAttr('checked');}
			});
			
			$(".deletePlatform").click(function(){var Param = $(this).attr('data-param'); pID = $("div[id = params"+Param+"]").attr("data-pid");});
			$("#DelPlatform").click(function(){location.href='<?=$baseurl;?>admin-panel/management/platforms/delete/platformid/'+pID;});
		});
	</script>
</body>
</html>
