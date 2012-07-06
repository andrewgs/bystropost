<header class="admin">
	<div class="container">
		<div class="row">
			<div class="span7">
				<?=anchor($this->uri->uri_string(),'<span>Система управления продажами<br/><br/>Кибинет менеджера</span>',array("id"=>"logo"));?>
			</div>
			<div class="span5">
				<p class="authorized-user">
					<i><?= $userinfo['ulogin']; ?></i>
					<?=anchor('users/logoff','Завершить сеанс', array('class'=>'auth-link'));?> 
				</p>
			</div>
		</div>
	</div>
</header>