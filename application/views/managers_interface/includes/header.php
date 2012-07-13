<header class="admin">
	<div class="container">
		<div class="row">
			<div class="span7">
				<?=anchor("webmaster-panel/actions/control",'<span>Система управления продажами<br/><br/>Профиль "Менеждера"</span>',array('id'=>'logo'));?>
			</div>
			<div class="span5" style="padding-top:10px;">
				<div class="alert alert-info">
					<?php $this->load->view("topblock/managers");?>
				</div>
			</div>
		</div>
	</div>
</header>