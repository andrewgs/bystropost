<!DOCTYPE html>
<html lang="en">
<?php $this->load->view('admin_interface/includes/head');?>
<body>
	<?php $this->load->view('admin_interface/includes/header');?>
	<div class="container">
		<div class="row">
			<div class="span9">
				<ul class="breadcrumb">
					<li class="active">
						Пользователь: <?=$owner['fio'].' ['.$owner['login'].'] <strong>('.$owner['position'].')</strong>';?> 
					</li>
				</ul>
				<?php $this->load->view('alert_messages/alert-error');?>
				<?php $this->load->view('alert_messages/alert-success');?>
				<table class="table table-bordered">
					<thead>
						<tr>
							<th class="w500"><center><nobr>Текс сообщения</nobr></center></th>
							<th class="w50"><center>Действия</center></th>
						</tr>
					</thead>
					<tbody>
					<?php for($i=0;$i<count($mails);$i++):?>
						<tr class="align-center">
							<td class="w500"><?=$mails[$i]['text'];?></td>
							<td class="w50" style="text-align:center; vertical-align:middle;">
								<div id="params<?=$i;?>" style="display:none" data-mid="<?=$mails[$i]['id'];?>"></div>
								<a class="btn btn-danger deleteMail" data-param="<?=$i;?>" data-toggle="modal" href="#deleteMail" title="Удалить сообщение"><nobr>&nbsp;&nbsp;<i class="icon-trash icon-white"></i>&nbsp;&nbsp;</nobr></a>
							</td>
						</tr>
					<?php endfor; ?>
					</tbody>
				</table>
				<?php if($pages): ?>
					<?=$pages;?>
				<?php endif;?>
			</div>
		<?php $this->load->view('admin_interface/includes/rightbar');?>
		<?php $this->load->view('admin_interface/modal/admin-delete-mail');?>
		</div>
	</div>
	<?php $this->load->view('admin_interface/includes/scripts');?>
	
	<script type="text/javascript">
		$(document).ready(function(){
			var mID = 0;
			$(".deleteMail").click(function(){var Param = $(this).attr('data-param'); mID = $("div[id = params"+Param+"]").attr("data-mid");});
			$("#DelMail").click(function(){location.href='<?=$baseurl;?>admin-panel/messages/private-messages/delete-mail/mailid/'+mID;});
		});
	</script>
	
</body>
</html>
