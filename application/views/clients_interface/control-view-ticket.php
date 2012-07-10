<!DOCTYPE html>
<html lang="en">
<?php $this->load->view("clients_interface/includes/head");?>

<body>
	<?php $this->load->view("clients_interface/includes/header");?>
	<div class="container">
		<div class="row">
			<div class="span9">
				<ul class="breadcrumb">
					<li>
						<?=anchor("webmaster-panel/actions/tickets","Все сообщения");?><span class="divider">/</span>
					</li>
					<li class="active">
						<?=anchor($this->uri->uri_string(),$ticket['title'].' (<i><b>'.$ticket['url'].'</b></i>)');?>
					</li>
				</ul>
				<?php $this->load->view("alert_messages/alert-error");?>
				<?php $this->load->view("alert_messages/alert-success");?>
				<table class="table table-bordered" style="width: 700px;">
					<thead>
						<tr>
							<th class="w50"><center>№ ID</center></th>
							<th class="w100"><center>Дата:</center></th>
							<th class="w500"><center><nobr>Текст сообщения</nobr></center></th>
							<th class="w50">Управл.</th>
						</tr>
					</thead>
					<tbody>
					<?php for($i=0;$i<count($tkmsgs);$i++):?>
						<tr>
							<td style="text-align:center; vertical-align:middle;"><?=$tkmsgs[$i]['id'];?></td>
							<td style="text-align:center; vertical-align:middle;">
								<nobr><?=$tkmsgs[$i]['date'];?></nobr>
							<?php if(isset($tkmsgs[$i]['position'])):?>
								<br/><nobr><?=$tkmsgs[$i]['position'];?></nobr><br/>
							<?php endif;?>
							</td>
						<?php if($tkmsgs[$i]['sender'] != $userinfo['uid']):?>
							<td style="text-align:left;" data-incoming="incoming">
						<?php else:?>
							<td style="text-align:left;">
						<?php endif;?>
							<?php if($tkmsgs[$i]['reply']):?>
								<i><strong>Ответ на сообщение: №<?=$tkmsgs[$i]['reply'];?></strong></i><br/>
								<?=$tkmsgs[$i]['text'];?>
								<div class="clear"></div>
							<?php else:?>
								<?=$tkmsgs[$i]['text'];?>
							<?php endif;?>
							</td>	
							<td class="w50" style="text-align:center; vertical-align:middle;">
							<?php if($tkmsgs[$i]['sender'] != $userinfo['uid']):?>
								<div id="params<?=$i;?>" style="display:none" data-mid="<?=$tkmsgs[$i]['id'];?>" data-uid="<?=$tkmsgs[$i]['sender'];?>" data-position="<?=$tkmsgs[$i]['position'];?>"></div>
								<a class="btn btn-info mailTicket" data-param="<?=$i;?>" data-toggle="modal" href="#mailTicket" title="Ответить"><nobr>&nbsp;&nbsp;<i class="icon-envelope icon-white"></i>&nbsp;&nbsp;</nobr></a>
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
			<?php $this->load->view("clients_interface/includes/rightbar");?>
			<?php $this->load->view('clients_interface/modal/clients-ticket-message');?>
		</div>
	</div>
	<?php $this->load->view("clients_interface/includes/footer");?>
	<?php $this->load->view("clients_interface/includes/scripts");?>
	<script type="text/javascript">
		$(document).ready(function(){
			var mID = 0;
			$("td[data-incoming='incoming']").each(function(e){
				$(this).addClass('alert alert-message'); $(this).siblings('td').addClass('alert alert-message');
			});
			$(".mailTicket").click(function(){
				var Param = $(this).attr('data-param'); mID = $("div[id = params"+Param+"]").attr("data-mid");uID = $("div[id = params"+Param+"]").attr("data-uid");
				var	uPosition = $("div[id = params"+Param+"]").attr("data-position");
				$(".idMail").val(mID);$(".idUser").val(uID);$("#ePosition").val(uPosition);
			});
			$("#mtsend").click(function(event){
				$(".control-group").removeClass('error');
				$(".help-inline").hide();
				if($("#mailText").val()=='' && $("#closeTicket").attr("checked") == undefined){
					$("#mailText").parents(".control-group").addClass('error');
					$("#mailText").siblings(".help-inline").html("Поле не может быть пустым").show();
					event.preventDefault();
				}
			});
		});
	</script>
</body>
</html>