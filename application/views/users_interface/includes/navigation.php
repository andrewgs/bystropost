<div id="navigation" class="row">
	<div class="span7">
		<a href="<?=$baseurl;?>" id="top-logo"></a>	
	</div>
	<div id="login-form" class="span5">
		<div id="top-links">
			<?=anchor("support","Поддержка");?> &nbsp;&nbsp;&nbsp;
			<?=anchor("faq","FAQ");?> &nbsp;&nbsp;&nbsp;
			<?=anchor('users-ratings/advertisers','Отзывы');?>
		</div>
		<?php $this->load->view("forms/frmlogin");?>
	</div>
</div>
<div class="clearfix"> </div>