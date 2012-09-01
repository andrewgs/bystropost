<!DOCTYPE html>
<html lang="en">
<?php $this->load->view("admin_interface/includes/head");?>

<body>
	<?php $this->load->view("admin_interface/includes/header");?>
	
	<div class="container">
		<div class="row">
			<div class="span12">
				<ul class="breadcrumb">
					<li>
						<?=anchor("admin-panel/management/platforms","Все площадки");?><span class="divider">/</span>
					</li>
					<li class="active">
						<?=anchor($this->uri->uri_string(),$platform['url']);?>
					</li>
				</ul>
				<?php $this->load->view("alert_messages/alert-error");?>
				<?php $this->load->view("alert_messages/alert-success");?>
			</div>
			<div class="span12">
				<table id="panel_table" cellpadding="0" cellspacing="1" class="sel">
					<tr>
						<td width="200">URL площадки:</td>
						<td style="text-align:left;"><?=$platform['url'];?></td>
					</tr>
					<tr>
						<td>Тематика площадки:</td>
						<td><?php $this->load->view("clients_interface/includes/thematically");?></td>
					</tr>
					<tr>
						<td>CMS:</td>
						<td><?php $this->load->view("clients_interface/includes/cms");?></td>
					</tr>
					<tr>
						<td>URL админки:</td>
						<td style="text-align:left;"><?=$platform['adminpanel']?></td>
					</tr>
					<tr>
						<td>Доступ к админке:</td>
						<td style="text-align: left;">
							<span style="margin-left:10px;"><strong>Логин:</strong> &nbsp;&nbsp;&nbsp;</span><?=$platform['aplogin'];?><br/>
							<span style="margin-left:10px;"><strong>Пароль:</strong> </span><?=$platform['appassword'];?>
						</td>
					</tr>
					<tr>
						<td>Биржи:</td>
						<td style="text-align: left;">
							<div id="exp">
								<div list="MarketLine"></div>
							<?php for($j=0;$j<count($mymarkets);$j++):?>
								<div list="MarketLine">
									<label class="label-input left w205">Название биржи</label>
									<label class="label-input left w205">Логин</label>
									<label class="label-input left w85">Пароль</label>
									<div class="clear"></div>
								<?php for($i=0;$i<count($markets);$i++):?>
									<?php if($markets[$i]['id'] == $mymarkets[$j]['market']):?>
										<label class="left w205"><?=$markets[$i]['title'];?></label>
									<?php endif;?>
								<?php endfor; ?>
									<label class="left w205"><?=$mymarkets[$j]['login'];?></label>
									<label class="left w85"><?=$mymarkets[$j]['password'];?></label>
									<div class="clear"></div>
								</div>
								<hr style="margin:0"/>
							<?php endfor;?>
							</div>
						</td>
					</tr>
					<tr>
						<td>Объем знаков: </td>
						<td>
							<select name="amount" id="amount" class="reg-form-input w195 h35" disabled="disabled">
								<option value="1" >от 1000 до 1499</option>
								<option value="2" >от 1500 до 1999</option>
								<option value="3" >от 2000 до 2500</option>
							</select>
						</td>
					</tr>
					<tr>
						<td>Обзоры:</td>
						<td>
							<select name="reviews" id="reviews" class="reg-form-input w195 h35" disabled="disabled">
								<option value="1">да</option>
								<option value="0">нет</option>
							</select>
						</td>
					</tr>
					<tr>
						<td>Тематичность:</td>
						<td>
							<select name="thematically" id="thematically" class="reg-form-input w195 h35" disabled="disabled">
								<option value="0">нет</option>
								<option value="1">да</option>
							</select>
						</td>
					</tr>
					<tr>
						<td width="200">Размещать задания которые противоречат законам РФ <nobr>(порно, казино и т.п)</nobr>: </td>
						<td>
							<select name="illegal" id="illegal" class="reg-form-input w195 h35" disabled="disabled">
								<option value="0">Нет, не размещать</option>
								<option value="1">Да, размещать</option>
							</select>
						</td>
					</tr>
					<tr>
						<td width="200">Критерии к публикации фотографий к контенту (если поля не заполнены – фотографии будут отсутствовать)</td>
						<td>
							<span style="margin-left:15px;">Размер изображения: </span><?=$platform['imgwidth'];?> на <?=$platform['imgheight'];?><br/>
							<span style="margin-left:15px;">Позиция изображения:</span>
							<select name="imgpos" id="imgpos" class="reg-form-input w195 h35" disabled="disabled">
								<option value="left">Слева</option>
								<option value="center">По центру</option>
								<option value="right">Справа</option>
							</select>
						</td>
					</tr>
					<tr>
						<td width="200">Ваши пожелания по работе с площадкой (опциональное поле, не обязательное к заполнению)</td>
						<td style="text-align:left;"><?=$platform['requests'];?></td>
					</tr>
					<tr>
						<td>&nbsp;</td>
						<td>
							<input class="btn btn-inverse" id="reset" style="height: 40px; cursor:pointer;" type="button" value="Вернутся">
						</td>
					</tr>
				</table>
			</div>
		</div>
	</div>
	<?php $this->load->view("clients_interface/includes/footer");?>
	<?php $this->load->view("clients_interface/includes/scripts");?>
	<script src="<?=$baseurl;?>javascript/redactor/redactor.js"></script>
	<script src="<?=$baseurl;?>javascript/scrollto-min.js"></script>
	<script type="text/javascript">
		$(document).ready(function(){
			$("#subject [value='<?=$platform['subject'];?>']").attr("selected", "selected");
			$("#subject").attr("disabled","disabled");
			$("#cms [value='<?=$platform['cms'];?>']").attr("selected", "selected");
			$("#cms").attr("disabled","disabled");
			$("#tsbj").html('<b>'+$("#subject option:selected").html()+'</b>');
			$("#tsbj").html('<b>'+$("#subject option:selected").html()+'</b>');
			$("#amount [value='<?=$platform['amount'];?>']").attr("selected", "selected");
			$("#reviews [value='<?=$platform['reviews'];?>']").attr("selected", "selected");
			$("#thematically [value='<?=$platform['thematically'];?>']").attr("selected", "selected");
			$("#illegal [value='<?=$platform['illegal'];?>']").attr("selected", "selected");
			$("#reset").click(function(){
				window.history.back();
			})
		});
	</script>
</body>
</html>