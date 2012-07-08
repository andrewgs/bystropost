<header class="admin">
	<div class="container">
		<div class="row">
			<div class="span7">
				<?=anchor("webmaster-panel/actions/control",'<span>Система управления продажами<br/><br/>Профиль "Вебмастера"</span>',array('id'=>'logo'));?>
			</div>
			<div class="span5">
				<div class="authorized-user">
					<?php $this->load->view("topblock/clients");?>
				</div>
			</div>
		</div>
	</div>
</header>