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
						<?=anchor('admin-panel/management/platforms','Площадки');?>
					</li>
				</ul>
				<?php $this->load->view('alert_messages/alert-error');?>
				<?php $this->load->view('alert_messages/alert-success');?>
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
		<?php $this->load->view('admin_interface/includes/rightbar');?>
		<?php $this->load->view('admin_interface/modal/admin-edit-platform');?>
		<?php $this->load->view('admin_interface/modal/admin-mail-users');?>
		<?php $this->load->view('admin_interface/modal/admin-delete-platform');?>
		</div>
	</div>
	<?php $this->load->view('admin_interface/includes/scripts');?>
	<script type="text/javascript">
		$(document).ready(function(){
			var pID = 0;
			$("td[data-nomanager='nomanager']").each(function(e){
				$(this).addClass('alert alert-info'); $(this).siblings('td').addClass('alert alert-info');
			});
			$("td[data-noowner='noowner']").each(function(e){
				$(this).addClass('alert alert-error'); $(this).siblings('td').addClass('alert alert-error');
			});
			
			$(".editPlatform").click(function(){
				var Param = $(this).attr('data-param'); pID = $("div[id = params"+Param+"]").attr("data-pid");uID = $("div[id = params"+Param+"]").attr("data-uid");
				var	uFIO = $("div[id = params"+Param+"]").attr("data-fio"); var	uLogin = $("div[id = params"+Param+"]").attr("data-login");
				
				var	cc = $("div[id = params"+Param+"]").attr("data-ccontext"); var mc = $("div[id = params"+Param+"]").attr("data-mcontext");
				var	cn = $("div[id = params"+Param+"]").attr("data-cnotice"); var mn = $("div[id = params"+Param+"]").attr("data-mnotice");
				var	cr = $("div[id = params"+Param+"]").attr("data-creview"); var mr = $("div[id = params"+Param+"]").attr("data-mreview");
				var	cnews = $("div[id = params"+Param+"]").attr("data-cnews");var mnews = $("div[id = params"+Param+"]").attr("data-mnews");
				var	locked = $("div[id = params"+Param+"]").attr("data-locked"); var manager = $("div[id = params"+Param+"]").attr("data-manager");
				
				$(".idPlatform").val(pID);$(".idUser").val(uID);$(".eFio").val(uFIO);$(".eLogin").val(uLogin);
				$("#ecContext").val(cc); $("#emContext").val(mc);$("#ecNotice").val(cn);$("#emNotice").val(mn);
				$("#ecReview").val(cr); $("#emReview").val(mr);$("#ecNews").val(cnews); $("#emNews").val(mnews);$("#uManager").val(manager);
				if(locked == 1){$("#lockPlatform").attr('checked','checked');}else{$("#lockPlatform").removeAttr('checked');}
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
			$(".deletePlatform").click(function(){var Param = $(this).attr('data-param'); pID = $("div[id = params"+Param+"]").attr("data-pid");});
			$("#DelPlatform").click(function(){location.href='<?=$baseurl;?>admin-panel/management/platforms/delete/platformid/'+pID;});
		});
	</script>
</body>
</html>
