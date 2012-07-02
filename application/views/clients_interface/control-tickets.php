<!DOCTYPE html>
<html>
<?php $this->load->view("clients_interface/includes/head");?>

<body>
	<?php $this->load->view("clients_interface/includes/header");?>
	<div id="main">
		<?php $this->load->view("clients_interface/includes/navigation");?>
		<div id="stable">
			<div id="panel_menu">
				<?=anchor('#','Готовые задания (0)');?>
				<?=anchor('webmaster-panel/actions/platforms','Площадки ('.$platforms.')');?>
				<?=anchor('webmaster-panel/actions/mails','Почта ('.$mails.')');?>
				<?=anchor('webmaster-panel/actions/tickets','Тикеты ('.count($tickets).')');?>
				<?=anchor('#','Дополнительные услуги');?>
				<?=anchor('#','Форум');?>
			</div>
			<table id="panel_table" cellpadding="0" cellspacing="1">
				<tr id="titles">
					<td>Тема тикета</td>
					<td>Последний ответ</td>
					<td><nobr>Действия</nobr></td>
				</tr>
			<?php for($i=0;$i<count($tickets);$i++):?>
				<tr>
					<td style="text-align:left; font-size: 10px;" class="w275">
						<nobr><?=anchor('webmaster-panel/actions/tickets/view-ticket/'.$tickets[$i]['id'],$tickets[$i]['title']);?></nobr><br/><br/>
						<u>Направлено на расмотрение:</u><br/>
						<nobr><?=$tickets[$i]['fio'];?></nobr><br/>
						<nobr><i><b><?=$tickets[$i]['login'];?></b></i></nobr><br/><br/>
						Дата создания:<br/>
						<nobr><?=$tickets[$i]['date'];?></nobr>
					</td>
					<td style="text-align:left; font-size: 12px;" class="w700"><?=$tickets[$i]['text'];?></td>
					<td class="w100">
						<?=anchor('webmaster-panel/actions/tickets/delete/ticket-id/'.$tickets[$i]['id'],'Удалить',array('class'=>'btn-action small delTicket'));?>
					</td>
				</tr>
			<?php endfor;?>
			</table>
			<div id="frmInsTicket">
				<?php $this->load->view('forms/frmaddticket');?>
			</div>
			<?=anchor($this->uri->uri_string(),'Создать тикет',array('class'=>'btn-action none','id'=>'InsTicket','style'=>'text-decoration:none;'));?>
		</div>
	</div>
	<?php $this->load->view("clients_interface/includes/footer");?>
	<?php $this->load->view("clients_interface/includes/scripts");?>
	<script src="<?=$baseurl;?>javascript/redactor/redactor.js"></script>
	<script type="text/javascript">
		$(document).ready(function(){
			$(".redactor").redactor({toolbar:'default',lang: 'ru','fixed': true});
			$("#InsTicket").click(function(){
				$(".ErrImg").remove();
				if($("#frmInsTicket").is(":hidden")){
					$("#InsTicket").html('Отменить');
					$("#frmInsTicket").slideDown("slow");
					$("html, body").animate({scrollTop:'1000px'},"slow");
					return false;
				}else{
					$("#frmInsTicket").slideUp("slow",function(){
						$("#frmInsTicket").hide();
						$("#InsTicket").html('Создать тикет');
						$("#TitleTicket").val('');
						$("html, body").animate({scrollTop:'0'},"slow");
						return false;
					 });
				}
			});
			$("#CreateTicket").click(function(event){
				var err = false;
				$(".ErrImg").remove();
				if($("#TitleTicket").val()==''){
					$("#CreateTicket").after('<img class="ErrImg" src="<?=$baseurl;?>images/icons/exclamation.png" title="Тема тикета не может быть пуста">');
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
		});
	</script>
</body>
</html>