<header class="admin">
	<div class="container">
		<div class="row">
			<div class="span7">
				<?=anchor("manager-panel/actions/control",' ',array('id'=>'logo'));?>
			</div>
			<div class="span5" style="padding-top:10px;">
				<div class="user-panel">
					<?php $this->load->view("topblock/managers");?>
				</div>
			</div>
		</div>
		<div class="row">
			<div class="span12">
				<h1>Система управления продажами <span>/ Профиль &laquo;Менеджера&raquo;</span></h1>
			</div>
		</div>
	</div>
</header>