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
						<?=anchor("manager-panel/actions/tickets/inbox","Входящие тикеты");?><span class="divider">/</span>
					</li>
					<li>
						<?=anchor("manager-panel/actions/tickets/outbox","Исходящие тикеты");?>
					</li>
				</ul>
				<?php $this->load->view("alert_messages/alert-error");?>
				<?php $this->load->view("alert_messages/alert-success");?>
				<table class="table table-bordered" style="width: 700px;">
					<thead>
						<tr>
							<th class="w195"><center>Тема тикета</center></th>
							<th class="w500"><center><nobr>Последний ответ</nobr></center></th>
							<th class="w50">Управл.</th>
						</tr>
					</thead>
					<tbody>
					<?php for($i=0;$i<count($tickets);$i++):?>
						<tr>
							<td class="w195 ttpl">
								<?=anchor('manager-panel/actions/tickets/inbox/view-ticket/'.$tickets[$i]['id'],$tickets[$i]['title'],array('title'=>'Читать тикет'));?>
								(<?=anchor('manager-panel/actions/platforms/view-platform/'.$tickets[$i]['plid'],'<i><b>'.$tickets[$i]['url'].'</b></i>',array('title'=>'Просмотреть площадку'));?>)<br/><br/>
								от <?=$tickets[$i]['date'];?>
							</td>
							<td class="w500"><?=$tickets[$i]['text'];?></td>
							<td class="w50" style="text-align:center; vertical-align:middle;">
							<?php if($tickets[$i]['status']):?>
								<i class="icon-lock" title="Закрыт" style="margin-top:10px;"></i>
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
		<?php $this->load->view("managers_interface/includes/rightbar");?>
		</div>
	</div>
	<?php $this->load->view("managers_interface/includes/footer");?>
	<?php $this->load->view("managers_interface/includes/scripts");?>
	<script type="text/javascript">
		$(document).ready(function(){
			var tID = 0;
			$("td[data-closed='closed']").each(function(e){
				$(this).addClass('alert alert-info'); $(this).siblings('td').addClass('alert alert-info');
			});
		});
	</script>
</body>
</html>