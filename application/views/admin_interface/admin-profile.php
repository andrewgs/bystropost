<!DOCTYPE html>
<html lang="en">
<?php $this->load->view('admin_interface/includes/head');?>
<body>
	<?php $this->load->view('admin_interface/includes/header');?>
	<div class="container">
		<div class="row">
			<div class="span9">
				<ul class="breadcrumb">
				<?php if($this->uri->segment(2)=='management'):?>
					<li>
						<?=anchor($this->session->userdata('backpath'),'Список площадок');?><span class="divider">/</span>
					</li>
				<?php endif;?>
					<li class="active">
						<?=anchor($this->uri->uri_string(),'Профиль');?>
					</li>
				</ul>
				<?php $this->load->view('alert_messages/alert-error');?>
				<?php $this->load->view('alert_messages/alert-success');?>
				<?php $this->load->view('forms/frmuserprofile');?>
			<?php if($this->uri->segment(3) == 'users'):?>
				<?=form_open($this->uri->uri_string(),array('class'=>'form-horizontal')); ?>
					<fieldset>
						<div class="control-group">
							<label for="text" class="control-label">Откуда узнал: </label>
							<div class="controls">
								<textarea id="knowus" name="text" class="span6" rows="3" disabled="disabled"><?=$user['knowus'];?></textarea>
							</div>
						</div>
					</fieldset>
				<?= form_close(); ?>
			<?php endif;?>
			</div>
		<?php $this->load->view('admin_interface/includes/rightbar');?>
		</div>
	</div>
	<?php $this->load->view('admin_interface/includes/footer');?>
	<?php $this->load->view('admin_interface/includes/scripts');?>
	<script type="text/javascript">
		$(document).ready(function(){
			$("#submit").click(function(event){
				var err = false;
				$(".control-group").removeClass('error');
				$(".help-inline").hide();
				if($("#fio").val()==''){
					$("#fio").parents(".control-group").addClass('error');
					$("#fio").siblings(".help-inline").html("Поле не может быть пустым").show();
					event.preventDefault();
				}
				if($("#wmid").val()==''){
					$("#wmid").parents(".control-group").addClass('error');
					$("#wmid").siblings(".help-inline").html("Поле не может быть пустым").show();
					event.preventDefault();
				}else if($("#wmid").val().length != 12){
					$("#wmid").parents(".control-group").addClass('error');
					$("#wmid").siblings(".help-inline").html("Должно быть 12 цифр").show();
					event.preventDefault();
				}
				if($("#password").val() != ''){
					if($("#oldpas").val() == ''){
						$("#oldpas").parents(".control-group").addClass('error');
						$("#oldpas").siblings(".help-inline").html("Поле не может быть пустым").show();
						event.preventDefault();
					}else if($("#confpass").val() == ''){
						$("#confpass").parents(".control-group").addClass('error');
						$("#confpass").siblings(".help-inline").html("Поле не может быть пустым").show();
						event.preventDefault();
					}else{
						if($("#password").val() != $("#confpass").val()){
							$("#password").parents(".control-group").addClass('error');
							$("#confpass").parents(".control-group").addClass('error');
							$("#password").siblings(".help-inline").html("Пароли не совпадают").show();
							event.preventDefault();
						}
					}
				}
			});
			$("#reset").click(function(){window.location="<?=$baseurl;?><?=$this->session->userdata('backpath');?>"});
		});
	</script>
</body>
</html>
