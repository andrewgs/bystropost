<!DOCTYPE html>
<html lang="en">
<?php $this->load->view('admin_interface/includes/head');?>
<body>
	<?php $this->load->view('admin_interface/includes/header');?>
	<div class="container">
		<div class="row">
			<div class="span9">
				<ul class="breadcrumb">
					<li tnum="webmasters">
						<?=anchor('admin-panel/management/users/webmasters','Группа "Вебмастера"');?> <span class="divider">/</span>
					</li>
					<li tnum="optimizators">
						<?=anchor('admin-panel/management/users/optimizators','Группа "Оптимизаторы"');?> <span class="divider">/</span>
					</li>
					<li tnum="manegers">
						<?=anchor('admin-panel/management/users/manegers','Группа "Менеджеры"');?> <span class="divider">/</span>
					</li>
					<li tnum="admin">
						<?=anchor('admin-panel/management/users/admin','Группа "Администраторы"');?> <span class="divider">/</span>
					</li>
					<li tnum="all">
						<?=anchor('admin-panel/management/users/all','Все');?>
					</li>
				</ul>
				<?php $this->load->view('alert_messages/alert-error');?>
				<?php $this->load->view('alert_messages/alert-success');?>
				<table class="table table-striped table-bordered">
					<thead>
						<tr>
							<th><center><nobr>Е-mail (Логин) / Ф.И.О.</nobr></center></th>
							<th><center><nobr>Баланс</nobr></center></th>
							<th><center><nobr>Заявки: всего /</nobr><br/><nobr>не оплаченых</nobr></center></th>
							<th><center>WMID</center></th>
							<th><center><nobr>Дата рег.<br/>Посл.вход</nobr></center></th>
							<th>Управл.</th>
						</tr>
					</thead>
					<tbody>
					<?php for($i=0;$i<count($users);$i++):?>
						<tr class="align-center">
							<td style="min-width:185px;">
								<nobr><i><b><?=anchor('admin-panel/management/users/profile/id/'.$users[$i]['id'],$users[$i]['login']);?></b></i><br/>
								<?=$users[$i]['fio'];?><br/>
								<strong>(<?=$users[$i]['position'];?>)</strong><br/>
							<?php if($users[$i]['manager']):?>	
								<br/><?=$users[$i]['manfio'];?><br/><i><b><?=$users[$i]['manemail'];?></b></i>
							<?php endif;?>
								</nobr>
							</td>
							<td style="min-width:50px;"><center><nobr><?=$users[$i]['balance'];?> руб.</nobr></center></td>
							<td style="min-width:100px;">
								<center><nobr>
							<?php if($users[$i]['type'] == 1):?>
								<?=anchor('admin-panel/management/users/userid/'.$users[$i]['id'].'/finished-jobs',$users[$i]['torders'].' / <b>'.$users[$i]['uporders'].'</b>',array('style'=>'text-decoration:none;'));?><br/><br/>
							<?php else:?>
								&mdash; / &mdash;
							<?php endif;?>
								</nobr></center>
							</td>
							<td style="min-width:85px;"><nobr><?=$users[$i]['wmid'];?></nobr></td>
							<td style="min-width:65px;"><nobr><?=$users[$i]['signdate'];?><br/><font style="color:#2fec13"><?=$users[$i]['lastlogin'];?></font></nobr></td>
							<td style="max-width:58px;">
							<?php if($userinfo['uid']!=$users[$i]['id']):?>
								<div id="params<?=$i;?>" style="display:none" data-uid="<?=$users[$i]['id'];?>" data-fio="<?=$users[$i]['fio'];?>" data-login="<?=$users[$i]['login'];?>" data-balance="<?=$users[$i]['balance'];?>" data-wmid="<?=$users[$i]['wmid'];?>" data-utype="<?=$users[$i]['type'];?>" data-manager="<?=$users[$i]['manager'];?>"></div>
								<a class="btn btn-success editUser" data-param="<?=$i;?>" data-toggle="modal" href="#editUser" title="Редактировать пользователя"><nobr>&nbsp;&nbsp;<i class="icon-pencil icon-white"></i>&nbsp;&nbsp;</nobr></a>
								<a class="btn btn-info mailUser" data-param="<?=$i;?>" data-toggle="modal" href="#mailUser" title="Отправить письмо пользователю"><nobr>&nbsp;&nbsp;<i class="icon-envelope icon-white"></i>&nbsp;&nbsp;</nobr></a>
								<a class="btn btn-danger deleteUser" data-param="<?=$i;?>" data-toggle="modal" href="#deleteUser" title="Удалить пользователя"><nobr>&nbsp;&nbsp;<i class="icon-trash icon-white"></i>&nbsp;&nbsp;</nobr></a>
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
		<?php $this->load->view('admin_interface/includes/rightbar');?>
		<?php $this->load->view('admin_interface/modal/admin-edit-users');?>
		<?php $this->load->view('admin_interface/modal/admin-mail-users');?>
		<?php $this->load->view('admin_interface/modal/admin-delete-users');?>
		</div>
	</div>
	<?php $this->load->view('admin_interface/includes/scripts');?>
	<script type="text/javascript">
		$(document).ready(function(){
			var uID = 0; var uType = 0;
			$("li[tnum='<?=$this->uri->segment(4);?>']").addClass('active');
			
			$(".editUser").click(function(){
				var Param = $(this).attr('data-param'); uID = $("div[id = params"+Param+"]").attr("data-uid");
				var	uFIO = $("div[id = params"+Param+"]").attr("data-fio"); var	uLogin = $("div[id = params"+Param+"]").attr("data-login");
				var	uBalance = $("div[id = params"+Param+"]").attr("data-balance"); var	uWmid = $("div[id = params"+Param+"]").attr("data-wmid");
				uType = $("div[id = params"+Param+"]").attr("data-utype");uManager = $("div[id = params"+Param+"]").attr("data-manager");
				$(".idUser").val(uID);$(".eFio").val(uFIO);$(".eLogin").val(uLogin);$("#eBalance").val(uBalance);$("#eWMID").val(uWmid);$("#eType").val(uType);$("#uManager").val(uManager);
			});
			$("#eusend").click(function(event){
				var err = false;
				$(".control-group").removeClass('error');
				$(".help-inline").hide();
				$(".euinput").each(function(i,element){
					if($(this).val()==''){
						$(this).parents(".control-group").addClass('error');
						$(this).siblings(".help-inline").html("Поле не может быть пустым").show();
						err = true;
					}
				});
				if(!err){
					if($("#eWMID").val().length != 12){
						$("#eWMID").parents(".control-group").addClass('error');
						$("#eWMID").siblings(".help-inline").html("Должно быть 12 цифр").show();
						err = true;
					}
				};
				if(err){event.preventDefault();}
			});
			$(".mailUser").click(function(){
				var Param = $(this).attr('data-param'); uID = $("div[id = params"+Param+"]").attr("data-uid");
				var	uFIO = $("div[id = params"+Param+"]").attr("data-fio"); var	uLogin = $("div[id = params"+Param+"]").attr("data-login");
				$(".idUser").val(uID);$(".eFio").val(uFIO);$(".eLogin").val(uLogin);
			});
			$("#mtsend").click(function(event){
				var err = false;
				$(".control-group").removeClass('error');
				$(".help-inline").hide();
				if($("#mailText").val() == ''){
					$("#mailText").parents(".control-group").addClass('error');
					$("#mailText").siblings(".help-inline").html("Поле не может быть пустым").show();
					event.preventDefault();
				}
			});
			$("#editUser").on("hidden",function(){$("#msgalert").remove();$(".control-group").removeClass('error');$(".help-inline").hide();});
			$("#mailUser").on("hidden",function(){$("#msgalert").remove();$(".control-group").removeClass('error');$(".help-inline").hide();$("#mailText").val('');$("#sendMail").removeAttr('checked');});
			$("#eType").change(function(){
				if(uType != $(this).val()){
					$(this).parents(".control-group").addClass('error');
					$(this).siblings(".help-inline").html("Тип пользователя изменился!").show();
				}else{
					$(this).parents(".control-group").removeClass('error');
					$(this).siblings(".help-inline").html('').hide();
				}
			});
			$(".deleteUser").click(function(){var Param = $(this).attr('data-param'); uID = $("div[id = params"+Param+"]").attr("data-uid");});
			$("#DelUser").click(function(){location.href='<?=$baseurl;?>admin-panel/management/users/userid/'+uID;});
		});
	</script>
</body>
</html>
