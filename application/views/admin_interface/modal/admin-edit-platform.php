<?=form_open($this->uri->uri_string(),array('class'=>'form-horizontal')); ?>
	<input type="hidden" class="idPlatform" value="" name="pid" />
	<input type="hidden" class="idUser" value="" name="uid" />
	<div id="editUser" class="modal hide fade dmodal">
		<div class="modal-header">
			<a class="close" data-dismiss="modal">×</a>
			<h3>Редактирование информации о площадке</h3>
		</div>
		<div class="modal-body" style="max-height: 500px;">
			<fieldset>
				<div class="control-group">
					<label for="fio" class="control-label">Имя владельца: </label>
					<div class="controls">
						<input type="text" disabled="disabled" class="input-xlarge epinput eFio" name="fio">
						<span class="help-inline" style="display:none;">&nbsp;</span>
					</div>
				</div>
				<div class="control-group">
					<label for="login" class="control-label">Логин владельца: </label>
					<div class="controls">
						<input type="text" disabled="disabled" class="input-xlarge epinput eLogin" name="login">
						<span class="help-inline" style="display:none;">&nbsp;</span>
					</div>
				</div>
				<div class="control-group">
					<div class="controls">
						<div style="margin-left: 30px;display: inline-block;">
							<span class="help-inline">Для вебмастера</span>
						</div>
						<div style="margin-left: 55px;display: inline-block;">
							<span class="help-inline">Для менеджера</span>
						</div>
					</div>
				</div>
				<div class="control-group">
					<label for="сontext" class="control-label">Контекстная ссылка: </label>
					<div class="controls">
						<div class="input-prepend input-append">
							<span class="add-on">руб</span><input type="text" id="ecContext" class="input-small epinput digital" placeholder="0" name="ccontext"><span class="add-on">.00</span>
						</div>
						<div class="input-prepend input-append">
							<span class="add-on">руб</span><input type="text" id="emContext" class="input-small epinput digital" placeholder="0" name="mcontext"><span class="add-on">.00</span>
						</div>
					</div>
				</div>
				<div class="control-group">
					<label for="notice" class="control-label">Заметка(постовой): </label>
					<div class="controls">
						<div class="input-prepend input-append">
							<span class="add-on">руб</span><input type="text" id="ecNotice" class="input-small epinput digital" placeholder="0" name="cnotice"><span class="add-on">.00</span>
						</div>
						<div class="input-prepend input-append">
							<span class="add-on">руб</span><input type="text" id="emNotice" class="input-small epinput digital" placeholder="0" name="mnotice"><span class="add-on">.00</span>
						</div>
					</div>
				</div>
				<div class="control-group">
					<label for="review" class="control-label">Обзор: </label>
					<div class="controls">
						<div class="input-prepend input-append">
							<span class="add-on">руб</span><input type="text" id="ecReview" class="input-small epinput digital" placeholder="0" name="creview"><span class="add-on">.00</span>
						</div>
						<div class="input-prepend input-append">
							<span class="add-on">руб</span><input type="text" id="emReview" class="input-small epinput digital" placeholder="0" name="mreview"><span class="add-on">.00</span>
						</div>
					</div>
				</div>
				<div class="control-group">
					<label for="news" class="control-label">Новость: </label>
					<div class="controls">
						<div class="input-prepend input-append">
							<span class="add-on">руб</span><input type="text" id="ecNews" class="input-small epinput digital" placeholder="0" name="cnews"><span class="add-on">.00</span>
						</div>
						<div class="input-prepend input-append">
							<span class="add-on">руб</span><input type="text" id="emNews" class="input-small epinput digital" placeholder="0" name="mnews"><span class="add-on">.00</span>
						</div>
					</div>
				</div>
				<hr/>
				<div class="control-group">
					<label for="manager" class="control-label">Менеджеры</label>
					<div class="controls">
						<select id="uManager" name="manager" class="input-xlarge">
							<option value="0">Менеджер не назначен</option>
						<?php for($i=0;$i<count($managers);$i++):?>
							<option value="<?=$managers[$i]['id'];?>"><?=$managers[$i]['fio'];?></option>
						<?php endfor;?>
						</select>
					</div>
				</div>
				<div class="control-group">
					<label class="control-label" for="locked"></label>
					<div class="controls">
						<label class="checkbox">
						<input id="lockPlatform" name="locked" type="checkbox" value="1">
							Заблокировать площадку
						</label>
					</div>
				</div>
			</fieldset>
		</div>
		<div class="modal-footer">
			<button class="btn" data-dismiss="modal">Отменить</button>
			<button class="btn btn-success" type="submit" id="epsend" name="epsubmit" value="send">Сохранить</button>
		</div>
	</div>
<?= form_close(); ?>