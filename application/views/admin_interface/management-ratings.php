<!DOCTYPE html>
<html lang="en">
<?php $this->load->view('admin_interface/includes/head');?>
<body>
	<?php $this->load->view('admin_interface/includes/header');?>
	<div class="container">
		<div class="row">
			<div class="span9">
				<ul class="breadcrumb">
				<?= ($this->uri->segment(4) == 'advertisers')?'<li class="active">':'<li>';?>
						<?=anchor("admin-panel/management/ratings/advertisers","Отзывы от рекламодателей");?><span class="divider">/</span>
					</li>
				<?= ($this->uri->segment(4) == 'webmasters')?'<li class="active">':'<li>';?>
						<?=anchor("admin-panel/management/ratings/webmasters","Отзывы от вебмастеров");?>
					</li>
				</ul>
				<?php $this->load->view('alert_messages/alert-error');?>
				<?php $this->load->view('alert_messages/alert-success');?>
				<table class="table table-striped table-bordered">
					<thead>
						<tr>
							<th class="w100"><center><nobr>Имя</nobr></center></th>
							<th class="w500"><center><nobr>Текст</nobr></center></th>
							<th class="w50">&nbsp;</th>
						</tr>
					</thead>
					<tbody>
					<?php for($i=0;$i<count($ratings);$i++):?>
						<tr class="align-center">
							<td class="w100" style="vertical-align:middle;"><nobr><i><b><?=$ratings[$i]['title'];?></b></i></nobr></td>
							<td class="w500"><i><?=$ratings[$i]['text'];?></i></td>
							<td class="w50" style="vertical-align:middle; text-align:center;">
								<div id="params<?=$i;?>" style="display:none" data-rid="<?=$ratings[$i]['id'];?>"></div>
								<a class="btn btn-danger deleteRating" data-param="<?=$i;?>" data-toggle="modal" href="#deleteRating" title="Удалить отзыв"><nobr>&nbsp;&nbsp;<i class="icon-trash icon-white"></i>&nbsp;&nbsp;</nobr></a>
							</td>
						</tr>
					<?php endfor; ?>
					</tbody>
				</table>
				<a class="btn btn-info" data-toggle="modal" href="#addRating" title="Добавить отзыв"><nobr>&nbsp;&nbsp;<i class="icon-plus icon-white"></i> Добавить отзыв&nbsp;&nbsp;</nobr></a>
			</div>
		<?php $this->load->view('admin_interface/includes/rightbar');?>
		<?php $this->load->view('admin_interface/modal/admin-add-rating');?>
		<?php $this->load->view('admin_interface/modal/admin-delete-rating');?>
		</div>
	</div>
	<?php $this->load->view('admin_interface/includes/footer');?>
	<?php $this->load->view('admin_interface/includes/scripts');?>
	<script type="text/javascript">
		$(document).ready(function(){
			var rID = 0;
			
			$("#arsend").click(function(event){
				var err = false;
				$(".control-group").removeClass('error');
				$(".help-inline").hide();
				$(".arinput").each(function(i,element){
					if($(this).val()==''){
						$(this).parents(".control-group").addClass('error');
						$(this).siblings(".help-inline").html("Поле не может быть пустым").show();
						err = true;
					}
				});
				if(err){event.preventDefault();}
			});
			
			$(".mkmodal").on("hidden",function(){$("#msgalert").remove();$(".control-group").removeClass('error');$(".help-inline").hide();});
			$(".deleteRating").click(function(){var Param = $(this).attr('data-param'); rID = $("div[id = params"+Param+"]").attr("data-rid");});
			$("#DelRating").click(function(){location.href='<?=$baseurl;?>admin-panel/management/ratings/ratingid/'+rID;});
		});
	</script>
</body>
</html>
