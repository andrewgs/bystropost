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
						<?=anchor("admin-panel/actions/control","Панель управления");?>
					</li>
				</ul>
				<?php $this->load->view("alert_messages/alert-error");?>
				<?php $this->load->view("alert_messages/alert-success");?>
				<table class="table table-bordered">
					<thead>
						<tr>
							<th><center><nobr>URL / CMS<br/>Дата создания / Владелец<br/>Закрепленный менеджер</nobr></center></th>
							<th><center><nobr>Заявки: всего /</nobr><br/><nobr>не оплаченых</nobr></center></th>
							<th><center>Цены:<br/><nobr>вебмастер /<br/>менеджер</nobr></center></th>
							<th><center>Сумма</center></th>
							<th>Управл.</th>
						</tr>
					</thead>
					<tbody>
					<?php for($i=0;$i<count($platforms);$i++):?>
						<tr class="align-center">
						<?php if(empty($platforms[$i]['fio'])):?>
							<td class="w275" data-noowner="noowner">
						<?php elseif(empty($platforms[$i]['manemail'])):?>
							<td class="w275" data-nomanager="nomanager">
						<?php else:?>
							<td class="w275">
						<?php endif;?>
								<nobr><i><b><?=$platforms[$i]['url'];?></b></i><br/>CMS: <?=$platforms[$i]['cms'];?><br/>Дата: <?=$platforms[$i]['date'];?><br/>
								<?=$platforms[$i]['fio'];?><br/><i><b><?=$platforms[$i]['login'];?></b></i><br/><br/>
								<?=$platforms[$i]['manfio'];?><br/><i><b><?=$platforms[$i]['manemail'];?></b></i></nobr>
							</td>
							<td class="w85"><center><nobr><?=$platforms[$i]['torders'];?> / <?=$platforms[$i]['uporders'];?></nobr></center></td>
							<td class="w85">
								<center>
									<nobr><?=$platforms[$i]['ccontext'];?> руб. / <?=$platforms[$i]['mcontext'];?> руб.</nobr><br/>
									<nobr><?=$platforms[$i]['cnotice'];?> руб. / <?=$platforms[$i]['mnotice'];?> руб.</nobr><br/>
									<nobr><?=$platforms[$i]['creview'];?> руб. / <?=$platforms[$i]['mreview'];?> руб.</nobr>
									<nobr><?=$platforms[$i]['cnews'];?> руб. / <?=$platforms[$i]['mnews'];?> руб.</nobr>
								</center>
							</td>
							<td class="w85"><center><nobr><?=$platforms[$i]['price'];?> руб.</nobr></center></td>
							<td class="w50" style="text-align: center; vertical-align: middle;">
								<div id="params<?=$i;?>" style="display:none" data-pid="<?=$platforms[$i]['id'];?>" data-uid="<?=$platforms[$i]['uid'];?>" data-fio="<?=$platforms[$i]['fio'];?>" data-login="<?=$platforms[$i]['login'];?>" data-ccontext="<?=$platforms[$i]['ccontext'];?>" data-mcontext="<?=$platforms[$i]['mcontext'];?>" data-cnotice="<?=$platforms[$i]['cnotice'];?>" data-mnotice="<?=$platforms[$i]['mnotice'];?>" data-creview="<?=$platforms[$i]['creview'];?>" data-mreview="<?=$platforms[$i]['mreview'];?>" data-cnews="<?=$platforms[$i]['cnews'];?>" data-mnews="<?=$platforms[$i]['mnews'];?>" data-locked="<?=$platforms[$i]['locked'];?>" data-manager="<?=$platforms[$i]['manager'];?>"></div>
							<?php if(!empty($platforms[$i]['fio'])):?>
								<a class="btn btn-success editPlatform" data-param="<?=$i;?>" data-toggle="modal" href="#editUser" title="Редактировать данные"><nobr>&nbsp;&nbsp;<i class="icon-pencil icon-white"></i>&nbsp;&nbsp;</nobr></a>
								<a class="btn btn-info mailUser" data-param="<?=$i;?>" data-toggle="modal" href="#mailUser" title="Отправить письмо владельцу"><nobr>&nbsp;&nbsp;<i class="icon-envelope icon-white"></i>&nbsp;&nbsp;</nobr></a>
							<?php endif;?>	
								<a class="btn btn-danger deletePlatform" data-param="<?=$i;?>" data-toggle="modal" href="#deletePlatform" title="Удалить площадку"><nobr>&nbsp;&nbsp;<i class="icon-trash icon-white"></i>&nbsp;&nbsp;</nobr></a>
							<?php if($platforms[$i]['locked']):?>
								<i class="icon-lock" title="Заблокировано" style="margin-top:10px;"></i>
							<?php endif;?>
							</td>
						</tr>
					<?php endfor; ?>
					</tbody>
				</table>
				<?php if($pages): ?>
					<?=$pages;?>
				<?php endif;?>
			</div>
		<?php $this->load->view("admin_interface/includes/rightbar");?>
		</div>
	</div>
	<?php $this->load->view("admin_interface/includes/scripts");?>
</body>
</html>
