<header class="admin">
	<div class="container">
		<div class="row">
			<div class="span7">
				<?=anchor("webmaster-panel/actions/control",' ',array('id'=>'logo'));?>
			</div>
			<div class="span5" style="padding-top:10px;">
				<div class="user-panel">
					<?php $this->load->view("topblock/clients");?>
					<?php if($userinfo['lock']):?>
						<i class="icon-lock" title="Профиль заблокирован" style="float:left; margin-top:-15px;"></i>
					<?php endif;?>
				</div>
			</div>
		</div>
		<div class="row">
			<div class="span12">
				<h1>Система управления продажами <span>/ Профиль &laquo;Вебмастера&raquo;</span></h1>
			</div>
		</div>
	</div>
</header>