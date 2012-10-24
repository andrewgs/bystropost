<!DOCTYPE html>
<html lang="en">
<?php $this->load->view("clients_interface/includes/head");?>

<body>
	<?php $this->load->view("clients_interface/includes/header");?>
	<div class="container">
		<div class="row">
			<div class="span9">
				<ul class="breadcrumb">
					<li class="active">
						<?=anchor("webmaster-panel/actions/finished-jobs","Готовые задания");?>
					</li>
				<?php if($userinfo['balance'] >= $minprice):?>
					<li style="float:right;">
						<?=anchor('webmaster-panel/actions/finished-jobs/pay-all','Оплатить все',array('class'=>'btn btn-info payall','style'=>'margin-top: -5px;'));?>
					</li>
				<?php endif;?>
					<li style="float:right;">
						Сумма к оплате: <?=$total['sum'];?>.00 руб&nbsp;&nbsp;
					</li>
				</ul>
				<?php $this->load->view("alert_messages/alert-error");?>
				<?php $this->load->view("alert_messages/alert-success");?>
				<div class="alert alert-info" id="mspayall" style="display:none;">
					<h3>Ожидайте!</h3>Производится оплата. Это может занять некоторое время...
				</div>
				<div style="float:right;margin-bottom:10px;">
					<button class="btn btn-primary" id="exportCSV"><i class="icon-download-alt icon-white"></i> Экспорт заявок</button>
				</div>
			<?php if($cntunit['delivers']['notpaid'] && $userinfo['balance'] >= $minprice):?>
				<?=form_open($this->uri->uri_string(),array('class'=>'form-horizontal')); ?>
					<input type="hidden" id="summa" value="" name="summa" />
			<?php endif;?>
				<table class="table table-bordered" style="width: 700px;">
					<thead>
						<tr>
							<th class="w100"><center>№ п.п</center></th>
							<th class="w100"><center>Дата</center></th>
							<th class="w100"><center><nobr>Тип работы</nobr></center></th>
							<th class="w100"><center>Биржа</center></th>
							<th class="w100"><center><nobr>Цена<br/>на бирже</nobr></center></th>
							<th class="w100"><center>URL-адрес</center></th>
							<th class="w100"><center><nobr>Колич.<br/>символов</nobr></center></th>
							<th class="w100"><center>Стоим.</center></th>
						</tr>
					</thead>
					<tbody>
					<?php for($i=0,$num=$this->uri->segment(5)+1;$i<count($delivers);$i++,$num++):?>
						<tr>
							<td class="w100" data-status="<?=$delivers[$i]['status'];?>" style="text-align:center; vertical-align:middle;"><?=$num;?></td>
							<td class="w100" style="text-align:center; vertical-align:middle;"><nobr><b><?=$delivers[$i]['date'];?></b></nobr></td>
							<td class="w100" style="text-align:center; vertical-align:middle;"><?=$delivers[$i]['twtitle'];?></td>
							<td class="w100" style="text-align:center; vertical-align:middle;"><?=$delivers[$i]['mtitle'];?></td>
							<td class="w100" style="text-align:center; vertical-align:middle;"><nobr><?=$delivers[$i]['mkprice'];?> руб.</nobr></td>
							<td class="w100" style="vertical-align:middle;"><?=anchor($delivers[$i]['ulrlink'],$delivers[$i]['link'],array('target'=>'_blank'));?></td>
							<td class="w100" style="text-align:center; vertical-align:middle;"><nobr><?=$delivers[$i]['countchars'];?> шт.</nobr></td>
						<?php if(!$delivers[$i]['status']):?>
							<?php if($userinfo['balance'] >= $delivers[$i]['wprice']):?>
							<td class="w100" style="text-align:center; vertical-align:middle;" data-status="notpaid">
								<input type="checkbox" class="chPrice" name="works[]" value="<?=$delivers[$i]['id'];?>" data-price="<?=$delivers[$i]['wprice'];?>" title="Оплатить" /><br/><nobr><?=$delivers[$i]['wprice'];?> руб.</nobr>
							</td>
							<?php else:?>
								<td class="w100" style="text-align:center; vertical-align:middle;" data-status="notpaid">
									<nobr><?=$delivers[$i]['wprice'];?> руб.</nobr><br/>
									<nobr><small>Нет средств</small></nobr>
								</td>
							<?php endif;?>
						<?php else:?>
							<td class="w100" style="text-align:center; vertical-align:middle;" data-status="paid">
								<nobr><?=$delivers[$i]['wprice'];?> руб.</nobr>
							</td>
						<?php endif;?>
						</tr>
					<?php endfor; ?>
					<?php if($cntunit['delivers']['notpaid'] && ($userinfo['balance'] >= $minprice)):?>
						<tr>
							<td colspan="7" style="text-align:right; vertical-align:middle;">
								<?=anchor($this->uri->uri_string(),'Инвертировать',array('class'=>'none','id'=>'inverse','style'=>'text-decoration:none;'));?>
							</td>
							<td style="text-align:center; vertical-align:middle;"><input type="checkbox" id="changeAll" title="Снять / Установить" /></td>
						</tr>
					<?php endif;?>
					</tbody>
				</table>
				<div class="clear"></div>
		<?php if($cntunit['delivers']['notpaid']):?>
			<?php if($userinfo['balance'] >= $minprice):?>
					<fieldset>
						<legend><div id="dTotalSumma" style="float:right;">Сумма к оплате: <span id="TotalSumma">0</span>.00 руб.</div></legend>
						<div class="control-group">
							<div class="controls">
								<label class="checkbox"><input type="checkbox" disabled="disabled" value="valid" id="ValidWork">&nbsp;Подтвердите выбор</label>
							</div>
						</div>
						<div class="form-actions">
							<button class="btn btn-primary disabled" type="submit" name="submit" id="send" value="submit">Оплатить</button>
						</div>
					</fieldset>
				<?= form_close(); ?>
			<?php else:?>
				<div class="">Мало средств. Вы не можете заплатить за выполненную работу. Пожалуйста пополните свой денежный баланс.</div>
			<?php endif;?>
		<?php endif;?>
				<hr/>
			<?php if($pages): ?>
				<?=$pages;?>
			<?php endif;?>
			</div>
		<?php $this->load->view("clients_interface/includes/rightbar");?>
		<?php $this->load->view('clients_interface/modal/clients-edit-platform');?>
		</div>
	</div>
	<?php $this->load->view("clients_interface/includes/footer");?>
	<?php $this->load->view("clients_interface/includes/scripts");?>
	<script type="text/javascript">
		$(document).ready(function(){
			$("td[data-status='notpaid']").each(function(e){$(this).addClass('notpaid'); $(this).siblings('td').addClass('notpaid');});
			$("td[data-status='paid']").each(function(e){$(this).addClass('paid'); $(this).siblings('td').addClass('paid');});
		<?php if($userinfo['balance'] >= $minprice):?>
			$(".payall").click(function(){if(!confirm("Оплатить задания?")) return false; $(".alert ").hide();$("#mspayall").show();});
			var balance = <?=$userinfo['balance'];?>;
			var tprice = 0;
			$("#ValidWork").removeAttr('checked').attr('disabled','disabled');
			$("#send").addClass('disabled');
			$("#changeAll").click(function(){
				if($(this).attr("checked") == 'checked'){
					$(".chPrice").attr('checked','checked');
					tprice = calculate(1,balance);
				}else{
					$(".chPrice").removeAttr('checked');
					tprice = calculate(0,balance);
				}
			});
			$("input[type='checkbox']").removeAttr('checked');
			$(".chPrice").click(function(){
				$("#changeAll").removeAttr('checked');
				var price = parseInt($("#TotalSumma").html());
				if($(this).attr("checked") == 'checked'){
					price = price+parseInt($(this).attr('data-price'));
					$("#TotalSumma").html(price);
				}else{
					price = price-parseInt($(this).attr('data-price'));
					$("#TotalSumma").html(price)
				}
				if(price>balance){
					$("#dTotalSumma").css('color','#ff0000');
					$("#ValidWork").removeAttr('checked').attr('disabled','disabled');
					$("#send").addClass('disabled');
				}else{
					$("#dTotalSumma").css('color','#000000');
					$("#ValidWork").removeAttr('checked').removeAttr('disabled');
				}
				if(price == 0){
					$("#ValidWork").removeAttr('checked').attr('disabled','disabled');
					$("#send").addClass('disabled');
				}
				tprice = price;
			});
			$("#inverse").click(function(){tprice = calculate(2,balance);$("#changeAll").removeAttr('checked');});
			$("#ValidWork").removeAttr('checked');
			$("#ValidWork").click(function(){
				if($(this).attr("checked") == 'checked'){$("#send").removeClass('disabled');$("#summa").val(tprice);}else{$("#send").addClass('disabled');};
			});
			$("#send").click(function(event){
				if($("#ValidWork").attr("checked") != "checked"){event.preventDefault();return false;}
			});
			
			function calculate(check,balance){
				var price = 0;
				if(check != 1){price = parseInt($("#TotalSumma").html());}
				$(".chPrice").each(function(i, element){
					if(check == 1){
						price = price+parseInt($(element).attr('data-price'));
					}else if(check == 0){
						price = price-parseInt($(element).attr('data-price'));
					}else if(check == 2){
						if($(this).attr("checked") == 'checked'){
							$(this).removeAttr("checked");
							price = price-parseInt($(element).attr('data-price'));
						}else{
							$(this).attr("checked","checked");
							price = price+parseInt($(element).attr('data-price'));
						}
					}
					$("#TotalSumma").html(price);
				});
				if(price>balance){
					$("#dTotalSumma").css('color','#ff0000');
					$("#ValidWork").removeAttr('checked').attr('disabled','disabled');
					$("#send").addClass('disabled');
				}else{
					$("#dTotalSumma").css('color','#000000');
					$("#dTotalSumma").css('color','#000000');
					$("#ValidWork").removeAttr('checked').removeAttr('disabled');
				}
				if(price == 0){
					$("#ValidWork").removeAttr('checked').attr('disabled','disabled');
					$("#send").addClass('disabled');
				}
				return price;
			}
		<?php endif;?>
		});
		$("#exportCSV").click(function(event){
			window.open("<?=$baseurl;?>webmaster-panel/actions/finished-jobs/export-csv");
			event.preventDefault();
		});
	</script>
</body>
</html>