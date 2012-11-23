<!DOCTYPE html>
<html lang="en">
<?php $this->load->view('managers_interface/includes/head');?>

<body>
	<?php $this->load->view('managers_interface/includes/header');?>
	<div class="container">
		<div class="row">
			<div class="span9">
				<ul class="breadcrumb">
					<li class="active">
						<?=anchor('manager-panel/actions/control','Выполненные задания');?>
					</li>
				</ul>
				<?php $this->load->view('alert_messages/alert-error');?>
				<?php $this->load->view('alert_messages/alert-success');?>
				<div class="clear"></div>
				<div style="float:left;margin-bottom:10px;">
					<input type="checkbox" id="showPaid" class="filterJobs" name="showpaid" value="1" title="Показывать оплаченные работы" <?=($filter['fpaid'])?'checked="checked"':'';?>/> Оплаченные
					<input type="checkbox" id="showNoPaid" class="filterJobs" name="shownotpaid" value="0" title="Показывать не оплаченные работы" <?=($filter['fnotpaid'])?'checked="checked"':'';?>/> Не оплаченные
					<select name="countwork" id="SetCountWork" title="Количество работ на странице" class="span1" style="margin:2px 0 0 10px">
						<option value="10">10</option>
						<option value="25" selected="selected">25</option>
						<option value="50">50</option>
						<option value="100">100</option>
					</select>
				</div>
				<div style="float:right;">
				<?=form_open($this->uri->uri_string(),array('class'=>'bs-docs-example form-search')); ?>
					<input type="hidden" id="srdjid" name="srdjid" value="">
					<input type="text" class="span4 search-query" id="srdjurl" name="srdjurl" value="" autocomplete="off" placeholder="Поиск от 5-х символов">
					<div class="suggestionsBox" id="suggestions" style="display: none;"> <img src="<?=$baseurl;?>images/arrow.png" style="position: relative; top: -15px; left: 30px;" alt="upArrow" />
						<div class="suggestionList" id="suggestionsList"> &nbsp; </div>
					</div>
					<button type="submit" class="btn btn-primary" id="seacrh" name="scsubmit" value="seacrh"><i class="icon-search icon-white"></i> Найти</button>
					<?= form_close(); ?>
				</div>
				<table class="table table-bordered" style="width: 700px;">
					<thead>
						<tr>
							<th class="w50"><center>ID</center></th>
							<th class="w50"><center>Дата</center></th>
							<th class="w100"><center><nobr>Тип работы</nobr></center></th>
							<th class="w100"><center>Биржа</center></th>
							<!--<th class="w100"><center><nobr>Цена<br/>на бирже</nobr></center></th>-->
							<th class="w100"><center>URL-адрес</center></th>
							<!--<th class="w100"><center><nobr>Колич.<br/>символов</nobr></center></th>-->
							<th class="w50"><center>Стоим.</center></th>
						</tr>
					</thead>
					<tbody>
					<?php for($i=0,$num=$this->uri->segment(5)+1;$i<count($delivers);$i++,$num++):?>
						<tr>
							<td class="w50" data-status="<?=$delivers[$i]['status'];?>" style="text-align:center; vertical-align:middle;"><?=$delivers[$i]['remoteid'];?></td>
							<td class="w50" style="text-align:center; vertical-align:middle;"><nobr><b><?=$delivers[$i]['date'];?></b></nobr></td>
							<td class="w100" style="text-align:center; vertical-align:middle;"><?=$delivers[$i]['twtitle'];?></td>
							<td class="w100" style="text-align:center; vertical-align:middle;"><?=$delivers[$i]['mtitle'];?></td>
							<!--<td class="w100" style="text-align:center; vertical-align:middle;"><nobr><?=$delivers[$i]['mkprice'];?> руб.</nobr></td>-->
							<td class="w100" style="vertical-align:middle;"><?=anchor($delivers[$i]['ulrlink'],$delivers[$i]['link'],array('target'=>'_blank'));?></td>
							<!--<td class="w100" style="text-align:center; vertical-align:middle;"><nobr><?=$delivers[$i]['countchars'];?> шт.<nobr></td>-->
							<td class="w50" style="text-align:center; vertical-align:middle;"><nobr><?=$delivers[$i]['mprice'];?> руб.</nobr></td>
						</tr>
					<?php endfor; ?>
					</tbody>
				</table>
			<?php if($pages): ?>
				<?=$pages;?>
			<?php endif;?>
			</div>
		<?php $this->load->view('managers_interface/includes/rightbar');?>
		</div>
	</div>
	<?php $this->load->view("managers_interface/includes/footer");?>
	<?php $this->load->view('managers_interface/includes/scripts');?>
	<script type="text/javascript">
		$(document).ready(function(){
			
			$("#SetCountWork").val(<?=$cntwork;?>);
			
			$("#SetCountWork").change(function(){
				var CountWork = $(this).val();
				$.post("<?=$baseurl;?>manager-panel/actions/control/set-count-work",
					{'countwork':CountWork},function(data){
						window.location="<?=$baseurl;?>manager-panel/actions/control"
				},"json");
			});
			
		<?php if($filter['fpaid']):?>
			$("#showPaid").attr('checked','checked');
		<?php endif;?>
		<?php if($filter['fnotpaid']):?>
			$("#showNoPaid").attr('checked','checked');
		<?php endif;?>	
			$(".filterJobs").click(function(){
				var ShowJobs = $(".filterJobs").serialize();
				$.post("<?=$baseurl;?>manager-panel/actions/control/set-filter",{'showed':ShowJobs},
					function(data){window.location="<?=$baseurl;?>admin-panel/management/users/userid/<?=$this->uri->segment(5);?>/finished-jobs";}
				,"json");
			});
			
			function suggest(inputString){
				if(inputString.length < 5){
					$("#suggestions").fadeOut();
				}else{
					$("#srdjurl").addClass('load');
					$.post("<?=$baseurl;?>manager-panel/actions/finished-jobs/search",{squery: ""+inputString+""},
						function(data){
							if(data.status){
								$("#suggestions").fadeIn();
								$("#suggestionsList").html(data.retvalue);
								$(".djorg").live('click',function(){fill($(this).html(),$(this).attr("data-djid"));});
							}else{
								$('#suggestions').fadeOut();
							};
							$("#srdjurl").removeClass('load');
					},"json");
				}
			};
			
			function fill(url,plid){
				$("#srdjurl").val(url);
				$("#srdjid").val(plid);
				setTimeout("$('#suggestions').fadeOut();", 600);
			};
			
			$("#srdjurl").keyup(function(){$("#srdjid").val('');suggest(this.value)});
			$("#srdjurl").focusout(function(){setTimeout("$('#suggestions').fadeOut();",600);});
			
			$("#seacrh").click(function(event){if($("#srdjurl").val() == ''){event.preventDefault();}});
			
			$("td[data-status='0']").each(function(e){$(this).addClass("notpaid"); $(this).siblings('td').addClass("notpaid");});
			$("td[data-status='1']").each(function(e){$(this).addClass("paid"); $(this).siblings('td').addClass("paid");});
		});
	</script>
</body>
</html>
