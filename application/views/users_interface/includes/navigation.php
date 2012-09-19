<div id="navigation" class="row">
	<div class="span6">
		<a href="<?=$baseurl;?>" id="top-logo"></a>	
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
			<?=anchor("site-map","Карта сайта");?>
		</div>
		<?php $this->load->view("forms/frmlogin");?>
	</div>
	<div class="top-main-nav">
		<?=anchor("manner-of-payment","Порядок оплаты");?> <br />
		<?=anchor("about-content","О контенте");?> <br />
		<?=anchor("capabilities","Наши возможности");?> <br />
		<?=anchor("interface","Интерфейс");?>
	</div>
</div>
<div class="clearfix"> </div>