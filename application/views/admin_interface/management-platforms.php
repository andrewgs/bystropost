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
					<li style="float:right;">
						<a class="btn btn-info calc none" data-calc="pr" style="margin-top: -5px;" href="#" title="Пересчет PageRank"><i class="icon-retweet icon-white"></i> PageRank</a>
						<a class="btn btn-info calc none" data-calc="tic" style="margin-top: -5px;" href="#" title="Пересчет Яндекс тИЦ"><i class="icon-retweet icon-white"></i> Яндекс тИЦ</a>
						<span id="SpCalculate" class="btn btn-warning" style="display:none;margin-top: -5px;"></span>
					</li>
				</ul>
				<?php $this->load->view('alert_messages/alert-error');?>
				<?php $this->load->view('alert_messages/alert-success');?>
				<div style="float:right;">
				<?=form_open($this->uri->uri_string(),array('class'=>'bs-docs-example form-search')); ?>
					<input type="hidden" id="srplid" name="srplid" value="">
					<input type="text" class="span4 search-query" id="srplurl" name="srplurl" value="" autocomplete="off" placeholder="Поиск от 2-х символов">
					<div class="suggestionsBox" id="suggestions" style="display: none;"> <img src="<?=$baseurl;?>images/arrow.png" style="position: relative; top: -15px; left: 30px;" alt="upArrow" />
						<div class="suggestionList" id="suggestionsList"> &nbsp; </div>
					</div>
					<button type="submit" class="btn btn-primary" id="seacrh" name="scsubmit" value="seacrh"><i class="icon-search icon-white"></i> Найти</button>
				<?= form_close(); ?>
				</div>
				<table class="table table-bordered">
					<thead>
						<tr>
							<th><center><nobr>URL / CMS<br/>Дата создания / Владелец<br/>Закрепленный менеджер</nobr></center></th>
							<th><center>Биржи</center></th>
							<th><center><nobr>Заявки: всего /</nobr><br/><nobr>не оплаченых</nobr><br/><nobr>тИЦ / PR</nobr></center></th>
							<th><center>Цены:<br/><nobr>вебмастер /<br/>менеджер</nobr></center></th>
							<th>Управл.</th>
						</tr>
					</thead>
					<tbody>
					<?php for($i=0;$i<count($platforms);$i++):?>
						<tr>
						<?php if(empty($platforms[$i]['fio'])):?>
							<td class="w275" data-noowner="noowner">
						<?php elseif(empty($platforms[$i]['manemail'])):?>
							<td class="w275" data-nomanager="nomanager">
						<?php else:?>
							<td class="w275">
						<?php endif;?>
								<nobr>
								<?=anchor('admin-panel/management/platforms/edit-platform/'.$platforms[$i]['id'],'<b>'.$platforms[$i]['url'].'</b>',array('title'=>'Просмотреть площадку'));?>
								<br/>CMS: <?=$platforms[$i]['cms'];?><br/>Дата: <?=$platforms[$i]['date'];?><br/>
								<?=$platforms[$i]['fio'];?><br/><b><?=anchor('admin-panel/management/users/profile/id/'.$platforms[$i]['uid'],$platforms[$i]['login']);?></b><br/><br/>
								<?=$platforms[$i]['manfio'];?>
							<?php if($platforms[$i]['manager']):?>
								<br/><b><?=anchor('admin-panel/management/users/profile/id/'.$platforms[$i]['manager'],$platforms[$i]['manemail']);?></b>
							<?php endif;?>
								</nobr>
							</td>
							<td class="w85" style="text-align:center; vertical-align:middle;">
							<?php if(count($platforms[$i]['markets'])):?>
								<?php for($j=0,$cnt=0;$j<count($platforms[$i]['markets']);$j++):?>
									<img src="<?=$baseurl;?>markets/viewimage/<?=$platforms[$i]['markets'][$j]['market'];?>" alt="" title=""/>
								<?php endfor;?>
							<?php endif;?>
							</td>
							<td class="w85" style="text-align:center; vertical-align:middle;">
								<nobr>
									<?=anchor('admin-panel/management/platforms/platformid/'.$platforms[$i]['id'].'/finished-jobs',$platforms[$i]['torders'].' / <b>'.$platforms[$i]['uporders'].'</b>',array('style'=>'text-decoration:none;'));?><br/><br/>
									<?=$platforms[$i]['tic'];?> / <?=$platforms[$i]['pr'];?>
								</nobr>
							</td>
							<td class="w85">
								<center>
									<nobr><?=$platforms[$i]['cnotice'];?> руб. / <?=$platforms[$i]['mnotice'];?> руб.</nobr><br/>
									<nobr><?=$platforms[$i]['ccontext'];?> руб. / <?=$platforms[$i]['mcontext'];?> руб.</nobr><br/>
									<nobr><?=$platforms[$i]['creview'];?> руб. / <?=$platforms[$i]['mreview'];?> руб.</nobr>
									<nobr><?=$platforms[$i]['clinkpic'];?> руб. / <?=$platforms[$i]['mlinkpic'];?> руб.</nobr>
									<nobr><?=$platforms[$i]['cpressrel'];?> руб. / <?=$platforms[$i]['mpressrel'];?> руб.</nobr>
									<nobr><?=$platforms[$i]['clinkarh'];?> руб. / <?=$platforms[$i]['mlinkarh'];?> руб.</nobr>
									<nobr><?=$platforms[$i]['cnews'];?> руб. / <?=$platforms[$i]['mnews'];?> руб.</nobr>
								</center>
							</td>
							<td class="w50" style="text-align: center; vertical-align: middle;">
								<div id="params<?=$i;?>" style="display:none" data-pid="<?=$platforms[$i]['id'];?>" data-uid="<?=$platforms[$i]['uid'];?>" data-fio="<?=$platforms[$i]['fio'];?>" data-login="<?=$platforms[$i]['login'];?>" data-ccontext="<?=$platforms[$i]['ccontext'];?>" data-mcontext="<?=$platforms[$i]['mcontext'];?>" data-cnotice="<?=$platforms[$i]['cnotice'];?>" data-mnotice="<?=$platforms[$i]['mnotice'];?>" data-creview="<?=$platforms[$i]['creview'];?>" data-mreview="<?=$platforms[$i]['mreview'];?>" data-cnews="<?=$platforms[$i]['cnews'];?>" data-mnews="<?=$platforms[$i]['mnews'];?>" data-clinkpic="<?=$platforms[$i]['clinkpic'];?>" data-mlinkpic="<?=$platforms[$i]['mlinkpic'];?>" data-cpressrel="<?=$platforms[$i]['cpressrel'];?>" data-mpressrel="<?=$platforms[$i]['mpressrel'];?>" data-clinkarh="<?=$platforms[$i]['clinkarh'];?>" data-mlinkarh="<?=$platforms[$i]['mlinkarh'];?>" data-locked="<?=$platforms[$i]['locked'];?>" data-status="<?=$platforms[$i]['status'];?>" data-noticpr="<?=$platforms[$i]['noticpr'];?>" data-manager="<?=$platforms[$i]['manager'];?>"></div>
							<?php if(!empty($platforms[$i]['fio'])):?>
								<a class="btn btn-success editPlatform" data-param="<?=$i;?>" data-toggle="modal" href="#editPlatform" title="Редактировать данные"><nobr>&nbsp;&nbsp;<i class="icon-pencil icon-white"></i>&nbsp;&nbsp;</nobr></a>
								<a class="btn btn-info mailUser" data-param="<?=$i;?>" data-toggle="modal" href="#mailUser" title="Отправить письмо владельцу"><nobr>&nbsp;&nbsp;<i class="icon-envelope icon-white"></i>&nbsp;&nbsp;</nobr></a>
							<?php endif;?>	
								<a class="btn btn-danger deletePlatform" data-param="<?=$i;?>" data-toggle="modal" href="#deletePlatform" title="Удалить площадку"><nobr>&nbsp;&nbsp;<i class="icon-trash icon-white"></i>&nbsp;&nbsp;</nobr></a>
							<?php if($platforms[$i]['locked']):?>
								<i class="icon-lock" title="Заблокировано" style="margin-top:10px;"></i>
							<?php endif;?>
							<?php if(!$platforms[$i]['status']):?>
								<i class="icon-exclamation-sign" title="Не активна" style="margin-top:10px;"></i>
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
	<?php $this->load->view('admin_interface/includes/footer');?>
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
				
				var	clp = $("div[id = params"+Param+"]").attr("data-clinkpic");var mlp = $("div[id = params"+Param+"]").attr("data-mlinkpic");
				var	cpr = $("div[id = params"+Param+"]").attr("data-cpressrel");var mpr = $("div[id = params"+Param+"]").attr("data-mpressrel");
				var	cla = $("div[id = params"+Param+"]").attr("data-clinkarh");var mla = $("div[id = params"+Param+"]").attr("data-mlinkarh");
				
				var	locked = $("div[id = params"+Param+"]").attr("data-locked"); var manager = $("div[id = params"+Param+"]").attr("data-manager");
				var plstatus = $("div[id = params"+Param+"]").attr("data-status");
				var noticpr = $("div[id = params"+Param+"]").attr("data-noticpr");
				
				$(".idPlatform").val(pID);$(".idUser").val(uID);$(".eFio").val(uFIO);$(".eLogin").val(uLogin);
				$("#ecContext").val(cc); $("#emContext").val(mc);$("#ecNotice").val(cn);$("#emNotice").val(mn);
				$("#ecReview").val(cr); $("#emReview").val(mr);$("#ecNews").val(cnews); $("#emNews").val(mnews);
				
				$("#ecLinkPic").val(clp); $("#emLinkPic").val(mlp);$("#ecPressRel").val(cpr); $("#emPressRel").val(mpr);
				$("#ecLinkArh").val(cla); $("#emLinkArh").val(mla);
				
				$("#uManager").val(manager);
				if(locked == 1){$("#lockPlatform").attr('checked','checked');}else{$("#lockPlatform").removeAttr('checked');}
				if(plstatus == 0){$("#StatusPlatform").removeAttr('checked').removeAttr('disabled');}else{$("#StatusPlatform").attr('checked','checked').attr('disabled','disabled');}
				if(noticpr == 0){$("#NoTicPr").removeAttr('disabled');}else{$("#NoTicPr").attr('checked','checked');}
			});
			
			var stopRequest = false;
			var stopScript = false;
			var countPL = 0;
			$(".calc").click(function(){
				if(!confirm("Начать пересчет?")) return false;
				var objSpan = $("#SpCalculate");
				var calc = $(this).attr('data-calc');
				var intervalID; var plcount = <?=$allplatforms;?>;
				var from=0;var count = <?=($allplatforms<25)?$allplatforms:25;?>;
				$(objSpan).siblings('a').remove();
				ajaxRequest(calc,count,from);
				$(objSpan).show().html('Обработка площадок: '+parseInt(from+count)+' из '+plcount);
				intervalID = setInterval(
					function(){
						if(stopRequest){
							if(stopScript || (from+1) >=plcount){
								$(objSpan).show().html('Обработка завершена!');
								console.log(countPL);
								clearInterval(intervalID);
							}else{
								from = from + count;
								ajaxRequest(calc,count,from);
								if((from+count) <=plcount){
									$(objSpan).show().html('Обработка площадок: '+parseInt(from+count)+' из '+plcount);
								}else{
									$(objSpan).show().html('Обработка площадок: '+plcount+' из '+plcount);
								}
								
							}
						}
					}
				,1000);
			});
			function ajaxRequest(calc,count,from){
			
				stopRequest = false;
				stopScript = false;
				$.ajax({
					url: "<?=$baseurl;?>admin-panel/management/platforms/calculate",
					data: ({'count':count,'calc':calc,'from':from}),
					type: "POST",
					dataType: "JSON",
					success: function(data){
						stopRequest = true;
						if(!data.nextstep){stopScript = true;}
						countPL = countPL+data.plcount;
					}
				});
			}
			
			function suggest(inputString){
				if(inputString.length < 2){
					$("#suggestions").fadeOut();
				}else{
					$("#srplurl").addClass('load');
					$.post("<?=$baseurl;?>admin-panel/management/platforms/search",{squery: ""+inputString+""},
						function(data){
							if(data.status){
								$("#suggestions").fadeIn();
								$("#suggestionsList").html(data.retvalue);
								$(".plorg").live('click',function(){fill($(this).html(),$(this).attr("data-plid"));});
							}else{
								$('#suggestions').fadeOut();
							};
							$("#srplurl").removeClass('load');
					},"json");
				}
			};
			
			function fill(url,plid){
				$("#srplurl").val(url);
				$("#srplid").val(plid);
				setTimeout("$('#suggestions').fadeOut();", 600);
			};
			
			$("#srplurl").keyup(function(){$("#srplid").val('');suggest(this.value)});
			$("#srplurl").focusout(function(){setTimeout("$('#suggestions').fadeOut();",600);});
			
			$("#seacrh").click(function(event){if($("#srplurl").val() == ''){event.preventDefault();}});
			
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
