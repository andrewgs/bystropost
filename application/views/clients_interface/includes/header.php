<header class="admin">
	<div class="container">
		<div class="row header-subnav">
			<div class="span8 offset4">
				<?=anchor("news","Новости");?>
				<?=anchor("faq","FAQ");?>
				<?=anchor("manner-of-payment","Порядок оплаты");?>
				<?=anchor('prices','Цены');?>
				<?=anchor("about-content","О контенте");?>
				<?=anchor("capabilities","Наши возможности");?>
				<?=anchor("interface","Интерфейс");?>
				<?=anchor("contacts","Контакты");?>  
			</div>
		</div>
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
					<?php if($userinfo['debetor']):?>
						<i class="icon-warning-sign" title="Профиль заблокирован по задолженности" style="float:left; margin:-15px 0 0 25px"></i>
					<?php endif;?>
					<?php if($userinfo['autopaid']):?>
						<i class="icon-star" title="Режим &quot;Беззаботный&quot;" style="float:left; margin:-15px 0 0 50px"></i>
					<?php endif;?>
				</div>
			</div>
		</div>
	</div>		
</header>
<div class="container">
	<div class="row">
		<div class="span12">
			<h1 class="admin-h1">Система мониторинга и управления <span>/ Профиль &laquo;Вебмастера&raquo;</span></h1>
		</div>
	</div>
</div>