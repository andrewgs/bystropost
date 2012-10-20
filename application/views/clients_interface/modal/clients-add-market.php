<?=form_open($this->uri->uri_string(),array('class'=>'form-horizontal')); ?>
	<div id="addMarket" class="modal hide fade mkmodal">
		<div class="modal-header">
			<a class="close" data-dismiss="modal">×</a>
			<h3>Добавление учетной записи на бирже</h3>
		</div>
		<div class="modal-body">
			<fieldset>
				<div class="control-group">
					<label for="market" class="control-label">Биржа:</label>
					<div class="controls">
						<select id="MarketList" name="market" class="input-medium">
						<?php for($i=0;$i<count($markets);$i++):?>
							<option value="<?=$markets[$i]['id'];?>"><?=$markets[$i]['title'];?></option>
						<?php endfor;?>
						</select>
					</div>
				</div>
				<div class="control-group">
					<label class="control-label" for="login">Логин:</label>
					<div class="controls">
						<input type="text" id="login" class="input-xlarge ainpval" name="login">
						<span class="help-inline" style="display:none;">&nbsp;</span>
					</div>
				</div>
				<div class="control-group">
					<label class="control-label" for="login">Пароль:</label>
					<div class="controls">
						<input type="text" id="password" class="input-xlarge ainpval" name="password">
						<span class="help-inline" style="display:none;">&nbsp;</span>
					</div>
				</div>
				<span class="help-block" id="prsape" style="display:none;">
						<hr/>
					Для работы с Pr.Sape, нам необходимо чтобы вы предоставили данные от вашего второго аккаунта в pr.sape,
					на который вы бы могли повесить гостевой доступ используя ваш основной аккаунт.<br/>
					Т.е вы должны зарегистрироваться повторно, узнать свой номер договора -
					<a href="http://help.sape.ru/pr/faq/1239">http://help.sape.ru/pr/faq/1239</a>
					и закрепить основной аккаунт, за новым. После чего заполнить в системе быстропост поля Логин и Пароль.<br/>
					Внимание, при указание гостевого доступа от pr.sape, к логину добавляйте приставку _old. Например: moyasapa_old<br/>
					При работе с основным доступом, просто введите всё как есть. Это самый быстрый и оптимальный вариант.
				</span>
			</fieldset>
		</div>
		<div class="modal-footer">
			<button class="btn" data-dismiss="modal">Отменить</button>
			<button class="btn btn-success" type="submit" id="amsend" name="amsubmit" value="send">Добавить</button>
		</div>
	</div>
<?= form_close(); ?>