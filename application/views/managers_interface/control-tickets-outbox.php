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
						<?=anchor("manager-panel/actions/tickets/inbox","Входящие тикеты");?><span class="divider">/</span>
					</li>
					<li class="active">
						<?=anchor("manager-panel/actions/tickets/outbox","Исходящие тикеты");?>
					</li>
				</ul>
				<?php $this->load->view("alert_messages/alert-error");?>
				<?php $this->load->view("alert_messages/alert-success");?>
				<div class="clear"></div>
				<div style="float: right;margin-bottom:10px;">
					<input type="checkbox" id="hideClosed" name="hideticket" value="1" title="Скрыть закрытые тикеты" <?=($hidetikets)?'checked="checked"':'';?>/> Скрыть закрытые
				</div>
				<table class="table table-bordered" style="width: 700px;">
					<thead>
						<tr>
							<th class="w195"><center>Тема тикета</center></th>
							<th class="w500"><center><nobr>Последний ответ</nobr></center></th>
							<th class="w50">Состояние</th>
						</tr>
					</thead>
					<tbody>
					<?php for($i=0;$i<count($tickets);$i++):?>
						<tr>
							<td class="w195 ttpl">
								<?=anchor('manager-panel/actions/tickets/outbox/view-ticket/'.$tickets[$i]['id'],$tickets[$i]['title'],array('title'=>'Читать тикет'));?><br/><br/>
								от <?=$tickets[$i]['date'];?>
							</td>
							<td class="w500"><?=$tickets[$i]['text'];?></td>
							<td class="w50" style="text-align:center; vertical-align:middle;">
								<div id="params<?=$i;?>" style="display:none" data-tid="<?=$tickets[$i]['id'];?>"></div>
								<?=anchor('manager-panel/actions/tickets/outbox/view-ticket/'.$tickets[$i]['id'],'<nobr>&nbsp;&nbsp;<i class="icon-list-alt icon-white"></i>&nbsp;&nbsp;</nobr>',array('title'=>'Читать тикет','class'=>"btn btn-success"));?><br/>
								<a class="btn btn-danger deleteTicket" data-param="<?=$i;?>" data-toggle="modal" href="#deleteTicket" title="Удалить тикет"><nobr>&nbsp;&nbsp;<i class="icon-trash icon-white"></i>&nbsp;&nbsp;</nobr></a>
							<?php if($tickets[$i]['status']):?>
								<br/><i class="icon-lock" title="Закрыт" style="margin-top:10px;"></i>
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
			<div class="clear"></div>
			<div class="span12">
				<div id="frmInsTicket">
					<?php $this->load->view('forms/frmaddticket');?>
				</div>
				<?=anchor($this->uri->uri_string(),'Создать',array('class'=>'btn btn-primary none','id'=>'InsTicket','style'=>'margin-top:20px;'));?>
			</div>
		<?php $this->load->view('clients_interface/modal/clients-mail-users');?>
		<?php $this->load->view('clients_interface/modal/clients-delete-tickets');?>
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
			
			$("#hideClosed").click(function(){
				var hideTicket = 0;
				if($(this).attr('checked') == 'checked'){hideTicket = 1;}
				$.post("<?=$baseurl;?>manager-panel/actions/tickets/outbox/hide-closed-tickets",
					{'hide':hideTicket},function(data){
						window.location="<?=$baseurl;?>manager-panel/actions/tickets/outbox"
				},"json");
			});
			
			$("#InsTicket").click(function(){
				$(".ErrImg").remove();
				if($("#frmInsTicket").is(":hidden")){
					$("#InsTicket").html('Отменить');
					$("#InsTicket").removeClass('btn-primary');
					$("#InsTicket").addClass('btn-inverse');
					$("#frmInsTicket").slideDown("slow");
					$("html, body").animate({scrollTop:'1500px'},"slow");
					return false;
				}else{
					$("#frmInsTicket").slideUp("slow",function(){
						$("#frmInsTicket").hide();
						$("#InsTicket").html('Создать');
						$("#InsTicket").removeClass('btn-inverse');
						$("#InsTicket").addClass('btn-primary');
						$("#TitleTicket").val('');
						return false;
					 });
				}
			});
			$("#CreateTicket").click(function(event){
				var err = false;
				$("#TitleTicket").css('border-color','#CCCCCC');
				$(".ErrImg").remove();
				if($("#TitleTicket").val()==''){
					$("#CreateTicket").after('<img class="ErrImg" src="<?=$baseurl;?>images/icons/exclamation.png" title="Тема тикета не может быть пуста">');
					$("#TitleTicket").css('border-color','#ff8080');
					$("#TitleTicket").focus();
					event.preventDefault();
				}
				if($("#TextTicket").val()==''){
					$("#CreateTicket").after('<img class="ErrImg" src="<?=$baseurl;?>images/icons/exclamation.png" title="Текс сообщения не может быть пустым">');
					$("#TextTicket").focus();
					event.preventDefault();
				}
			});
			$(".delTicket").click(function(event){
				if(!confirm("Удалить тикет?")){
					event.preventDefault();
				}
			});
			$("#InsTicket").click();
			
			$(".deleteTicket").click(function(){var Param = $(this).attr('data-param'); tID = $("div[id = params"+Param+"]").attr("data-tid");});
			$("#DelTicket").click(function(){location.href='<?=$baseurl;?>manager-panel/actions/tickets/delete/ticket-id/'+tID;});
		});
	</script>
</body>
</html>