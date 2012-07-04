<!DOCTYPE html>
<html>
<?php $this->load->view("clients_interface/includes/head");?>

<body>
	<?php $this->load->view("clients_interface/includes/header");?>
	<div id="main">
		<?php $this->load->view("clients_interface/includes/navigation");?>
		
		<div id="stable">
			<div id="panel_menu" style="margin-left:0;">
				<?=anchor('webmaster-panel/actions/tickets','&larr; Вернуться назад');?>
			</div>
			<?php $this->load->view('alert_messages/alert-error');?>
			<div class="clear"></div>
			<table id="panel_table" cellpadding="0" cellspacing="1" class="sel">
				<tr>
					<td><nobr>№ ID</nobr></td>
					<td>Дата:</td>
					<td>Текст сообщения</td>
				</tr>
			<?php for($i=0;$i<count($tkmsgs);$i++):?>
				<tr>
					<td><nobr><?=$tkmsgs[$i]['id'];?></nobr></td>
					<td><nobr><?=$tkmsgs[$i]['date'];?></nobr></td>
				<?php if($tkmsgs[$i]['sender'] != $userinfo['uid']):?>
					<td style="text-align:left;" data-incoming="incoming">
				<?php else:?>
					<td style="text-align:left;">
				<?php endif;?>
					<?php if($tkmsgs[$i]['reply']):?>
						<i><strong>Ответ на сообщение: №<?=$tkmsgs[$i]['reply'];?></strong></i><br/>
					<?php endif;?>
						<?=$tkmsgs[$i]['text'];?>
						<div class="clear"></div>
					<?php if(isset($tkmsgs[$i]['fio'])):?>
						<div style="float:left; margin-top:10px;" >
							<?=anchor('webmaster-panel/actions/tickets/ticket-id/'.$this->uri->segment(5).'/reply/message-id/'.$tkmsgs[$i]['id'],'Ответить',array('class'=>'btn-action small'));?>
						</div>
						<div style="float:right;margin-top:10px;">
							<nobr><?=$tkmsgs[$i]['fio'];?></nobr><br/>
							<nobr><i><b><?=$tkmsgs[$i]['email'];?></b></i></nobr><br/>
						</div>
					<?php endif;?>
					</td>
				</tr>
			<?php endfor;?>	
			</table>
			<?php if($pages): ?>
				<?=$pages;?>
			<?php endif;?>
		</div>
	</div>
	<?php $this->load->view("clients_interface/includes/footer");?>
	<?php $this->load->view("clients_interface/includes/scripts");?>
	<script src="<?=$baseurl;?>javascript/redactor/redactor.js"></script>
	<script type="text/javascript">
		$(document).ready(function(){
			$("td[data-incoming='incoming']").each(function(e){
				$(this).addClass('alert alert-error'); $(this).siblings('td').addClass('alert alert-error');
			});
			$(".redactor").redactor({toolbar:'default',lang: 'ru','fixed': true});
			$("#SendMail").click(function(event){
				var err = false;
				$(".ErrImg").remove();
				if($("#text").val()==''){
					$("#SendMail").after('<img class="ErrImg" src="<?=$baseurl;?>images/icons/exclamation.png" title="Текс сообщения не может быть пустым">');
					event.preventDefault();
				}
			});
		});
	</script>
</body>
</html>