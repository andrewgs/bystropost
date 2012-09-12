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
						<?=anchor("webmaster-panel/actions/services","Дополнительные услуги");?><span class="divider">/</span>
					</li>
					<li class="active">
						<?=anchor($this->uri->uri_string(),"Настройка свойств площадок");?>
					</li>
				</ul>
				<?php $this->load->view("alert_messages/alert-error");?>
				<?php $this->load->view("alert_messages/alert-success");?>
				<?=form_open($this->uri->uri_string()); ?>
				<table class="table table-striped table-bordered">
					<thead>
						<tr>
							<th class="w100"><center>Площадка</center></th>
							<th class="w100"><center><nobr>Значение услуги</nobr></center></th>
							<th class="w100"><center>Площадка</center></th>
							<th class="w100"><center><nobr>Значение услуги</nobr></center></th>
						</tr>
					</thead>
					<tbody>
					<?php for($i=0;$i<count($attached);$i+=2):?>
						<tr>
						<?php if(isset($attached[$i]['id'])):?>
							<td class="w100"><?=$attached[$i]['plurl'];?></td>
							<td class="w100">
								<select name="srvvalues[]" class="reg-form-input w205 h35">
								<?php for($j=0;$j<count($srvvalues);$j++):?>
									<option value="<?=$srvvalues[$j]['id'];?>" <?=($srvvalues[$j]['id'] == $attached[$i]['valuesrv'])?'selected	':'';?>><?=$srvvalues[$j]['title'];?></option>
								<?php endfor;?>
								</select>
							</td>
						<?php endif;?>
						<?php if(isset($attached[$i+1]['id'])):?>
							<td class="w100"><?=$attached[$i+1]['plurl'];?></td>
							<td class="w100">
								<select name="srvvalues[]" class="reg-form-input w205 h35">
								<?php for($j=0;$j<count($attached);$j++):?>
									<option value="<?=$srvvalues[$j]['id'];?>" <?=($srvvalues[$j]['id'] == $attached[$i+1]['valuesrv'])?'selected':'';?>><?=$srvvalues[$j]['title'];?></option>
								<?php endfor;?>
								</select>
							</td>
						<?php else:?>
							<td class="w85">&nbsp;</td>
							<td class="w85">&nbsp;</td>
						<?php endif;?>
						</tr>
					<?php endfor; ?>
					</tbody>
				</table>
				<hr/>
				<button class="btn btn-success" type="submit" id="sendSettings" name="submit" value="send">Сохранить</button>
			<?= form_close(); ?>
			</div>
		<?php $this->load->view("clients_interface/includes/rightbar");?>
		</div>
	</div>
	<?php $this->load->view("clients_interface/includes/footer");?>
	<?php $this->load->view("clients_interface/includes/scripts");?>
	<script type="text/javascript">
		$(document).ready(function(){
		
		});
	</script>
</body>
</html>