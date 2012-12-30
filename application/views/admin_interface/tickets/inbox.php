<!DOCTYPE html>
<html lang="en">
<?php $this->load->view('admin_interface/includes/head');?>
<body>
	<?php $this->load->view('admin_interface/includes/header');?>
	<div class="container">
		<div class="row">
			<div class="span9">
				<ul class="breadcrumb">
					<li class="pull-right">
						<button type="button" id="ClosedToggle" class="btn btn-info" data-toggle="buttons-checkbox">Показывать закрытые</button>
					</li>
					<div class="clear"></div>
				</ul>
				<?php $this->load->view('alert_messages/alert-error');?>
				<?php $this->load->view('alert_messages/alert-success');?>
				<div class="clear"></div>
				<table class="table table-bordered" style="width: 700px;">
					<thead>
						<tr>
							<th class="span1">ID</th>
							<th class="span1">&nbsp;</th>
							<th class="span7">Тема</th>
							<th class="span2">Посл. входящее</th>
							<th class="span1">&nbsp;</th>
						</tr>
					</thead>
					<tbody>
					<?php for($i=0;$i<count($tickets);$i++):?>
						<tr <?=($tickets[$i]['status'] && !$hideticket)?'style="display:none;"':''?>>
							<td><?=$tickets[$i]['id'];?></td>
							<td>
							<?php if($tickets[$i]['status']):?>
								<img src="<?=$baseurl;?>images/icons/message_lock.png" alt="Тикет зарыт" />
							<?php elseif($tickets[$i]['sender_answer'] && $tickets[$i]['sender_reading']):?>
								<img src="<?=$baseurl;?>images/icons/message_answer.png" alt="В тикете появился новый ответ" />
							<?php elseif(!$tickets[$i]['recipient_answer'] && !$tickets[$i]['recipient_reading']):?>
								<img src="<?=$baseurl;?>images/icons/message_noview.png" alt="Тикет не просмотрен Вами" />
							<?php elseif(!$tickets[$i]['recipient_answer'] && $tickets[$i]['recipient_reading']):?>
								<img src="<?=$baseurl;?>images/icons/message_in.png" alt="Тикет просмотрен Вами" />
							<?php elseif($tickets[$i]['recipient_answer'] && $tickets[$i]['recipient_reading']):?>
								<img src="<?=$baseurl;?>images/icons/message_out.png" alt="Тикет просмотрен и отвечен Вами" />
							<?php endif;?>
							</td>
							<td>
								<?=anchor('admin-panel/actions/tickets-inbox/read-ticket-id/'.$tickets[$i]['id'],$tickets[$i]['title'],array('title'=>'Читать тикет'));?>
								<br/>Площадка: <strong><?=$tickets[$i]['url'];?></strong><br/>
								От кого: <strong><?=$tickets[$i]['position_send'];?></strong> от <?=$tickets[$i]['date'];?>.
								Кому: <strong><?=$tickets[$i]['position_to'];?></strong>
								<div class="pull-right muted">
									Срочность:
								<?php if($tickets[$i]['importance'] == 1):?>
									<span class="label">НИЗКАЯ</span>
								<?php elseif($tickets[$i]['importance'] == 2):?>
									<span class="label label-warning">СРЕДНЯЯ</span>
								<?php elseif($tickets[$i]['importance'] == 3):?>
									<span class="label label-important">КРИТИЧНАЯ</span>
								<?php endif;?>
								</div>
							</td>
							<td><?=$tickets[$i]['msg_date'];?></td>
							<td style="text-align:center; vertical-align:middle;">
								<?=anchor('admin-panel/actions/tickets-inbox/read-ticket-id/'.$tickets[$i]['id'],'<i class="icon-comment icon-white"></i>',array('title'=>'Читать тикет','class'=>'btn btn-success'));?>
							<?php if($tickets[$i]['status']):?>
								<div style="height:3px;"> </div>
								<?=anchor('admin-panel/actions/tickets/open-ticket/'.$tickets[$i]['id'],'<img src="'.$baseurl.'images/icons/unlocked.png" alt="" />',array('title'=>'Читать тикет','class'=>"btn openTicket"));?>
							<?php endif;?>
							</td>
						</tr>
					<?php endfor; ?>
					</tbody>
				</table>
			<?php if($pages): ?>
				<?=$pages;?>
			<?php endif;?>
				<div class="clear"></div>
				<?=$this->load->view("alert_messages/icon-help");?>
			</div>
		<?php $this->load->view('admin_interface/includes/rightbar');?>
		</div>
	</div>
	<?php $this->load->view('admin_interface/includes/footer');?>
	<?php $this->load->view('admin_interface/includes/scripts');?>
	<script type="text/javascript">
		$(document).ready(function(){
			<?php if($hideticket):?>
			$("#ClosedToggle").button('toggle');
		<?php endif;?>
			$("#ClosedToggle").click(function(){
				$.post("<?=$baseurl;?>admin-panel/actions/tickets/hide-closed-tickets",
					{'toggle':true},function(data){window.location="<?=$baseurl;?>admin-panel/actions/tickets-inbox"},"json");
			});
			$(".openTicket").click(function(){if(!confirm("Открыть тикет?")) return false;});
			$(".BtnInsertTicket").click(function(){$("#frmInsTicket").slideToggle(200);$("#InsertTicket").tooltip('destroy').button('toggle'); $(".input-valid").tooltip('destroy');});
		});
	</script>
</body>
</html>
