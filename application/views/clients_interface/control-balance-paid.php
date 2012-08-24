<!DOCTYPE html>
<html lang="en">
<?php $this->load->view('admin_interface/includes/head');?>
<body>
	<?php $this->load->view('clients_interface/includes/header');?>
	<div class="container">
		<div class="row">
			<div class="span9">
				<ul class="breadcrumb">
					<li class="active">
						<?=anchor('webmaster-panel/actions/balance','Баланс аккаунта');?>
					</li>
				</ul>
				<?php $this->load->view('alert_messages/alert-error');?>
				<?php $this->load->view('alert_messages/alert-success');?>
				
				<?=form_open('https://merchant.webmoney.ru/lmi/payment.asp',array('class'=>'form-horizontal')); ?>
					<input type="hidden" name="LMI_PAYMENT_AMOUNT" value="<?=$this->session->userdata('balance');?>">
					<input type="hidden" name="LMI_PAYMENT_DESC" value="Recharge to the balance Bystropost.ru (<?=$userinfo['ulogin'];?>)">
					<input type="hidden" name="LMI_PAYEE_PURSE" value="R135034329557">
					<input type="hidden" name="LMI_PAYER_WM" value="<?=$wmid;?>">
					<input type="hidden" name="LMI_RESULT_URL" value="<?=$baseurl;?>webmaster-panel/actions/balance/result">
					<input type="hidden" name="LMI_SUCCESS_URL" value="<?=$baseurl;?>webmaster-panel/actions/balance/successfull">
					<input type="hidden" name="LMI_FAIL_URL" value="<?=$baseurl;?>webmaster-panel/actions/balance/failed">
					<fieldset>
						<legend>Подтвердите перевод средств</legend>
						<div class="control-group">
							<label class="control-label"><strong>Сумма:</strong></label>
							<div class="controls">
								<div class="help-inline"><strong><?=$this->session->userdata('balance');?>,00 руб.</strong></div>
							</div>
						</div>
						<hr/>
						<div class="controls">
							<div class="help-block">Внимание! Дождитесь сообщения о состоянии оплаты. Не меняйте и не закрывайте браузер. Если деньги списались с Вашего кошелька, а на баланс не поступили - обратитесь к администрации сайта.</div>
						</div>
						<div class="form-actions">
							<button class="btn btn-primary" type="submit" name="submit" id="send" value="submit">Потверждаю</button>
						</div>
					</fieldset>
				<?= form_close(); ?>
				
			</div>
			<?php $this->load->view("clients_interface/includes/rightbar");?>
		</div>
	</div>
	<?php $this->load->view('clients_interface/includes/scripts');?>
</body>
</html>
