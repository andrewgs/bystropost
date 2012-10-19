<div class="user-topblock">
	<?=$this->session->userdata('ulogin');?> <span style="float:right;"><?=anchor('','Профиль', array('class' => 'underline'));?> &nbsp;<?=anchor('logoff','Завершить сеанс', array('class' => 'underline'));?></span>
</div>