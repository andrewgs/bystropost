<script src="//ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js"></script>
<script>window.jQuery || document.write('<script src="<?=$baseurl;?>javascript/libs/jquery-1.7.2.min.js"><\/script>')</script>
<script src="<?=$baseurl;?>javascript/bootstrap.js"></script>
<script src="<?=$baseurl;?>javascript/scripts.js"></script>
<script type="text/javascript">
	$("li[num='<?=$this->uri->segment(3);?>']").addClass('active');
	$(".ReadAllMessages").click(function(){
		var idUser = $(".idUser").val();
		if(idUser != undefined){location.href='<?=$baseurl;?>admin-panel/management/users/read-messages/userid/'+idUser;}
	});
</script>