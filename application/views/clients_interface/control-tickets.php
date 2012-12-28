<!DOCTYPE html>
<html lang="en">
<?php $this->load->view("clients_interface/includes/head");?>

<body>
	<?php $this->load->view("clients_interface/includes/header");?>
	<div class="container">
		<div class="row">
			<div class="span9">
				<ul class="breadcrumb">
					<li><a href="" class="none BtnInsertTicket" id="InsertTicket" role="button tooltip" data-original-title="CОЗДАТЬ НОВЫЙ ТИКЕТ">НОВЫЙ ТИКЕТ</a></li>
					<li style="float:right; margin-top:-5px;">
						<button type="button" id="ClosedToggle" class="btn btn-info" data-toggle="buttons-checkbox">Показывать закрытые</button>
					</li>
				</ul>
				<?php $this->load->view("alert_messages/alert-error");?>
				<?php $this->load->view("alert_messages/alert-success");?>
				<div class="clear"></div>
				<div id="frmInsTicket" style="display:none;">
					<?php $this->load->view('forms/frmaddticket');?>
				</div>
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
								<img src="<?=$baseurl;?>images/icons/message_lock.png" alt="" />
							<?php elseif($tickets[$i]['answer'] && $tickets[$i]['reading']):?>
								<img src="<?=$baseurl;?>images/icons/message_answer.png" alt="" />
							<?php elseif(!$tickets[$i]['answer'] && !$tickets[$i]['reading']):?>
								<img src="<?=$baseurl;?>images/icons/message_nowiew.png" alt="" />
							<?php elseif(!$tickets[$i]['answer'] && $tickets[$i]['reading']):?>
								<img src="<?=$baseurl;?>images/icons/message_noanswer.png" alt="" />
							<?php endif;?>
								
							</td>
							<td>
								<?=anchor('webmaster-panel/actions/tickets-outbox/read-ticket-id/'.$tickets[$i]['id'],$tickets[$i]['title'],array('title'=>'Читать тикет'));?>
								<br/>Площадка: <strong><?=$tickets[$i]['url'];?></strong><br/>
								Направлено: <strong><?=$tickets[$i]['position'];?></strong> от <?=$tickets[$i]['date'];?>
							</td>
							<td><?=$tickets[$i]['msg_date'];?></td>
							<td style="text-align:center; vertical-align:middle;">
								<?=anchor('webmaster-panel/actions/tickets-outbox/read-ticket-id/'.$tickets[$i]['id'],'<i class="icon-comment icon-white"></i>',array('title'=>'Читать тикет','class'=>"btn btn-success"));?>
							<?php if($tickets[$i]['status']):?>
								<div style="height:3px;"> </div>
								<?=anchor('webmaster-panel/actions/tickets/open-ticket/'.$tickets[$i]['id'],'<img src="'.$baseurl.'images/icons/unlocked.png" alt="" />',array('title'=>'Читать тикет','class'=>"btn openTicket"));?>
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
			<?php $this->load->view("clients_interface/includes/rightbar");?>
		</div>
	</div>
	<?php $this->load->view("clients_interface/includes/footer");?>
	<?php $this->load->view("clients_interface/includes/scripts");?>
	<script type="text/javascript">
		$(document).ready(function(){
			var platforms = [<?php for($i=0;$i<count($platforms);$i++): ?>'<?=$platforms[$i];?>'<?php if(isset($platforms[$i+1])):?><?=',';?><?php endif;?><?php endfor; ?>];
		<?php if($hideticket):?>
			$("#ClosedToggle").button('toggle');
		<?php endif;?>
			$("#ClosedToggle").click(function(){
				$.post("<?=$baseurl;?>webmaster-panel/actions/tickets/hide-closed-tickets",
					{'toggle':true},function(data){window.location="<?=$baseurl;?>webmaster-panel/actions/tickets-outbox"},"json");
			});
			$("#InsertTicket").tooltip('show');
			$(".btn-types").eq(0).button('toggle');
			$(".btn-types").click(function(){$("#TicketType").val($(this).attr("data-value"));})
			$("#PlatformTicket").typeahead({source: platforms});
			$(".openTicket").click(function(){if(!confirm("Открыть тикет?")) return false;});
			
			$(".SubmitTicket").click(function(event){
				var parentFrom = $(this).parents("form");
				var err = false; $(parentFrom).find(".control-group").removeClass('error');$(parentFrom).find(".help-inline").hide();
				$(parentFrom).find(".input-valid").each(function(i,element){if($(this).val()==''){$(this).parents(".control-group").addClass('error');$(this).attr('data-original-title','Поле не должно быть пустым').tooltip('show');err = true;}});
				var platform = $.inArray($("#PlatformTicket").val(),platforms);
				if((platform < 0) && !err){$("#PlatformTicket").attr('data-original-title','Не верно указана площадка').tooltip('show');err = true;}
				if(err){event.preventDefault();}
			});
			
			$(".BtnInsertTicket").click(function(){$("#frmInsTicket").slideToggle(200);$("#InsertTicket").tooltip('destroy'); $(".input-valid").tooltip('destroy');});
		});
	</script>
</body>
</html>