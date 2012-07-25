<?=form_open($this->uri->uri_string(),array('class'=>'form-horizontal')); ?>
	<div id="addService" class="modal hide fade mkmodal"  style="width:750px;left:45%;">
		<div class="modal-header">
			<a class="close" data-dismiss="modal">×</a>
			<h3>Подключение услуги</h3>
		</div>
		<div class="modal-body">
			<fieldset>
				<div class="control-group">
					<label for="service" class="control-label">Список услуг:</label>
					<div class="controls">
						<select id="ServicesList" name="service" class="span7">
						<?php for($i=0;$i<count($services);$i++):?>
							<option value="<?=$services[$i]['id'];?>" data-price="<?=$services[$i]['price'];?>" <?=($userinfo['balance'] < $services[$i]['price'])? 'disabled="disabled"': '' ;?>><?=$services[$i]['title'];?></option>
						<?php endfor;?>
						</select>
					</div>
				</div>
			<?php if($userinfo['balance'] < $minprice):?>
				<div class="control-group">
					<div class="controls">
						<h4 style="color:#ff0000;">У вас не достаточно средст для подключения услуг. Пополните Ваш баланс.</h4>
					</div>
				</div>
			<?php endif;?>
			</fieldset>
		</div>
		<div class="modal-footer">
		<?php if(isset($services[0]['id']) && $userinfo['balance'] >= $minprice):?>
			<h3 style="float:left;">Цена услуги: <span id="gonorar"><?=$services[0]['price'];?></span>.00 руб.</h3>
		<?php endif;?>
			<button class="btn" data-dismiss="modal">Отменить</button>
		<?php if($userinfo['balance'] >= $minprice):?>
			<button class="btn btn-success" type="submit" id="assend" name="assubmit" value="send">Добавить</button>
		<?php endif;?>
		</div>
	</div>
<?= form_close(); ?>