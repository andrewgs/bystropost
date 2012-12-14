<div id="contact-info" class="row">
	<div class="span5 offset3 support">
		<span>(863) 295-51-11</span> <br/>
		Служба технической поддержки (с 9:00 до 17:00) 
	</div>
	<div class="span3 offset1 consultant">
		Онлайн косультант: <span>ICQ 810160</span>
	</div>	
</div>
<div id="navigation" class="row">
	<div class="span6">
		<a href="<?=$baseurl;?>" id="top-logo"></a>
		<div class="clear"> </div>
	</div>
	<div id="login-form" class="span6">
		<div class="top-links">
			<?=anchor("news","Новости");?> &nbsp;&nbsp;
			<?=anchor('about','О проекте');?> &nbsp;&nbsp; 
			<!--<?=anchor("support","Поддержка");?> &nbsp;&nbsp;-->
			<?=anchor("faq","FAQ");?> &nbsp;&nbsp;
			<?=anchor('forum','Форум');?> &nbsp;&nbsp;
			<?=anchor('users-ratings/webmasters','Отзывы');?> &nbsp;&nbsp;
			<?=anchor("contacts","Контакты");?>&nbsp;&nbsp;
			<?=anchor("idea","Ваши идеи");?>
		</div>
		<?php if(!$this->session->userdata('logon')):
			$this->load->view("forms/frmlogin");
		else:?>
			<div class="span5" style="padding-left:50px;">
				<div class="user-panel">
					<?php $this->load->view("topblock/users");?>
				</div>
			</div>
		<?php endif;?>
	</div>
	<div class="top-main-nav">
		<?=anchor("manner-of-payment","Порядок оплаты");?> <br />
		<?=anchor("about-content","О контенте");?> <br />
		<?=anchor("capabilities","Наши возможности");?> <br />
		<?=anchor("interface","Интерфейс");?>
	</div>
</div>
<div class="clearfix"> </div>