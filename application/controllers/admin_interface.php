<?php if(!defined('BASEPATH')) exit('No direct script access allowed');

class Admin_interface extends CI_Controller{

	var $user = array('uid'=>0,'uname'=>'','ulogin'=>'','utype'=>'','balance'=>0);
	var $loginstatus = array('status'=>FALSE);
	var $months = array("01"=>"января","02"=>"февраля","03"=>"марта","04"=>"апреля","05"=>"мая","06"=>"июня","07"=>"июля","08"=>"августа","09"=>"сентября","10"=>"октября","11"=>"ноября","12"=>"декабря");
	
	function __construct(){
		
		parent::__construct();
		$this->load->model('mdusers');
		$this->load->model('mdunion');
		$this->load->model('mdmessages');
		$this->load->model('mdmarkets');
		$this->load->model('mdtkmsgs');
		$this->load->model('mdtickets');
		$this->load->model('mdplatforms');
		$this->load->model('mdmkplatform');
		$this->load->model('mdtypeswork');
		$this->load->model('mdratings');
		$this->load->model('mddelivesworks');
		$this->load->model('mdservices');
		$this->load->model('mdlog');
		$this->load->model('mdthematic');
		$this->load->model('mdcms');
		$this->load->model('mdvaluesrv');
		$this->load->model('mdwebmarkets');
		$this->load->model('mdattachedservices');

		$cookieuid = $this->session->userdata('logon');
		if(isset($cookieuid) and !empty($cookieuid)):
			$this->user['uid'] = $this->session->userdata('userid');
			if($this->user['uid']):
				$userinfo = $this->mdusers->read_record($this->user['uid']);
				if($userinfo['type'] == 5):
					$this->user['ulogin'] 		= $userinfo['login'];
					$this->user['uname'] 		= $userinfo['fio'];
					$this->user['utype'] 		= $userinfo['type'];
					$this->user['balance'] 		= $userinfo['balance'];
					$this->loginstatus['status']= TRUE;
				else:
					redirect('');
				endif;
			endif;
			if($this->session->userdata('logon') != md5($userinfo['login'].$userinfo['password'])):
				$this->loginstatus['status'] = FALSE;
				redirect('');
			endif;
		else:
			redirect('');
		endif;
	}
	
	public function control_panel(){
		
		$pagevar = array(
					'description'	=> '',
					'author'		=> '',
					'title'			=> 'Администрирование | Панель управления',
					'baseurl' 		=> base_url(),
					'userinfo'		=> $this->user,
					'msgs'			=> $this->session->userdata('msgs'),
					'msgr'			=> $this->session->userdata('msgr')
			);
		$this->session->unset_userdata('msgs');
		$this->session->unset_userdata('msgr');
		$pagevar['cntunit']['users'] = $this->mdusers->count_all();
		$pagevar['cntunit']['platforms'] = $this->mdplatforms->count_all();
		$pagevar['cntunit']['markets'] = $this->mdmarkets->count_all();
		$pagevar['cntunit']['services'] = $this->mdservices->count_all();
		$pagevar['cntunit']['twork'] = $this->mdtypeswork->count_all();
		$pagevar['cntunit']['mails'] = $this->mdmessages->count_records_by_admin_new($this->user['uid']);
		$this->load->view("admin_interface/control-panel",$pagevar);
	}
	
	public function actions_events(){
		
		$from = intval($this->uri->segment(5));
		$pagevar = array(
					'description'	=> '',
					'author'		=> '',
					'title'			=> 'Администрирование | События',
					'baseurl' 		=> base_url(),
					'userinfo'		=> $this->user,
					'events'		=> $this->mdunion->read_events(25,$from),
					'pages'			=> array(),
					'msgs'			=> $this->session->userdata('msgs'),
					'msgr'			=> $this->session->userdata('msgr')
			);
		$this->session->unset_userdata('msgs');
		$this->session->unset_userdata('msgr');
		
		for($i=0;$i<count($pagevar['events']);$i++):
			$pagevar['events'][$i]['date'] = $this->operation_dot_date($pagevar['events'][$i]['date']);
		endfor;
		$config['base_url'] 		= $pagevar['baseurl'].'admin-panel/actions/events/from/';
		$config['uri_segment'] 		= 5;
		$config['total_rows'] 		= $this->mdlog->count_records();
		$config['per_page'] 		= 25;
		$config['num_links'] 		= 4;
		$config['first_link']		= 'В начало';
		$config['last_link'] 		= 'В конец';
		$config['next_link'] 		= 'Далее &raquo;';
		$config['prev_link'] 		= '&laquo; Назад';
		$config['cur_tag_open']		= '<span class="actpage">';
		$config['cur_tag_close'] 	= '</span>';
		
		$this->pagination->initialize($config);
		$pagevar['pages'] = $this->pagination->create_links();
		
		$pagevar['cntunit']['users'] = $this->mdusers->count_all();
		$pagevar['cntunit']['platforms'] = $this->mdplatforms->count_all();
		$pagevar['cntunit']['markets'] = $this->mdmarkets->count_all();
		$pagevar['cntunit']['services'] = $this->mdservices->count_all();
		$pagevar['cntunit']['twork'] = $this->mdtypeswork->count_all();
		$pagevar['cntunit']['mails'] = $this->mdmessages->count_records_by_admin_new($this->user['uid']);
		$this->load->view("admin_interface/control-events",$pagevar);
	}
	
	public function actions_events_clear(){
		
		$this->mdlog->delete_records();
		redirect($_SERVER['HTTP_REFERER']);
	}
	
	public function messages_system_clear(){
		
		$this->mdmessages->delete_system();
		redirect($_SERVER['HTTP_REFERER']);
	}
	
	public function actions_profile(){
		
		$pagevar = array(
					'description'	=> '',
					'author'		=> '',
					'title'			=> 'Администрирование | Личный кабинет',
					'baseurl' 		=> base_url(),
					'userinfo'		=> $this->user,
					'user'			=> $this->mdusers->read_record($this->user['uid']),
					'cntunit'		=> array(),
					'msgs'			=> $this->session->userdata('msgs'),
					'msgr'			=> $this->session->userdata('msgr')
			);
		$this->session->unset_userdata('msgs');
		$this->session->unset_userdata('msgr');
		
		if($this->input->post('submit')):
			$_POST['submit'] = NULL;
			$this->form_validation->set_rules('fio',' ','required|trim');
			$this->form_validation->set_rules('oldpas',' ','trim');
			$this->form_validation->set_rules('password',' ','trim');
			$this->form_validation->set_rules('confpass',' ','trim');
			$this->form_validation->set_rules('wmid',' ','trim');
			$this->form_validation->set_rules('phones',' ','trim');
			$this->form_validation->set_rules('icq',' ','trim');
			$this->form_validation->set_rules('skype',' ','trim');
			if(!$this->form_validation->run()):
				$this->session->set_userdata('msgr','Ошибка. Неверно заполены необходимые поля<br/>');
				redirect($this->uri->uri_string());
			else:
				if(!empty($_POST['oldpas']) && !empty($_POST['password']) && !empty($_POST['confpass'])):
					if(!$this->mdusers->user_exist('password',md5($_POST['oldpas']))):
						$this->session->set_userdata('msgr',' Не верный старый пароль!');
					elseif($_POST['password']!=$_POST['confpass']):
						$this->session->set_userdata('msgr',' Пароли не совпадают.');
					else:
						$this->mdusers->update_field($this->user['uid'],'password',md5($_POST['password']));
						$this->mdusers->update_field($this->user['uid'],'cryptpassword',$this->encrypt->encode($_POST['password']));
						$this->session->set_userdata('msgs',' Пароль успешно изменен');
						$this->session->set_userdata('logon',md5($this->user['ulogin'].md5($_POST['password'])));
					endif;
				endif;
				if(!isset($_POST['sendmail'])):
					$_POST['sendmail'] = 0;
				endif;
				unset($_POST['password']);unset($_POST['login']);
				$_POST['uid'] = $this->user['uid'];
				$result = $this->mdusers->update_record($_POST);
				if($result):
					$msgs = 'Личные данные успешно сохранены.<br/>'.$this->session->userdata('msgs');
					$this->session->set_userdata('msgs',$msgs);
				endif;
				redirect($this->uri->uri_string());
			endif;
		endif;
		
		$pagevar['cntunit']['users'] = $this->mdusers->count_all();
		$pagevar['cntunit']['platforms'] = $this->mdplatforms->count_all();
		$pagevar['cntunit']['markets'] = $this->mdmarkets->count_all();
		$pagevar['cntunit']['services'] = $this->mdservices->count_all();
		$pagevar['cntunit']['twork'] = $this->mdtypeswork->count_all();
		$pagevar['user']['signdate'] = $this->operation_date($pagevar['user']['signdate']);
		$pagevar['cntunit']['mails'] = $this->mdmessages->count_records_by_admin_new($this->user['uid']);
		
		$this->load->view("admin_interface/admin-profile",$pagevar);
	}
	
	/******************************************************** users ******************************************************/
	
	public function management_users(){
		
		$pagevar = array(
					'description'	=> '',
					'author'		=> '',
					'title'			=> 'Администрирование | Пользователи | ',
					'baseurl' 		=> base_url(),
					'userinfo'		=> $this->user,
					'users'			=> array(),
					'count'			=> 0,
					'pages'			=> array(),
					'cntunit'		=> array(),
					'managers'		=> $this->mdusers->read_users_by_type(2),
					'msgs'			=> $this->session->userdata('msgs'),
					'msgr'			=> $this->session->userdata('msgr')
			);
		$this->session->unset_userdata('msgs');
		$this->session->unset_userdata('msgr');
		
		if($this->input->post('eusubmit')):
			$_POST['eusubmit'] = NULL;
			$this->form_validation->set_rules('uid',' ','required|trim');
			$this->form_validation->set_rules('wmid',' ','required|trim');
			$this->form_validation->set_rules('balance',' ','required|trim');
			$this->form_validation->set_rules('type',' ','required|trim');
			$this->form_validation->set_rules('manager',' ','required|trim');
			if(!$this->form_validation->run()):
				$this->session->set_userdata('msgr','Ошибка при сохранении. Не заполены необходимые поля.');
			else:
				$result = $this->mdusers->update_record($_POST);
				if($result):
					if($_POST['type'] != 1):
						$this->mdusers->update_field($_POST['uid'],'manager',0);
						$_POST['manager'] = 0;
					endif;
					if($_POST['manager']):
						$platforms = $this->mdplatforms->read_managers_platform_online($_POST['uid']);
						for($i=0;$i<count($platforms);$i++):
							$text = 'Здравствуйте! За Вами закреплена новая площадка '.$platforms[$i]['url'];
							$this->mdmessages->send_noreply_message($this->user['uid'],$_POST['manager'],4,2,$text);
							if($platforms[$i]['manager']):
								$text = 'Здравствуйте! С Ваc снята площадка '.$platforms[$i]['url'];
								$this->mdmessages->send_noreply_message($this->user['uid'],$platforms[$i]['manager'],1,2,$text);
							endif;
						endfor;
						$this->mdplatforms->update_managers($_POST['uid'],$_POST['manager']);
					endif;
					$this->session->set_userdata('msgs','Информация успешно сохранена.');
				else:
					$this->session->set_userdata('msgr','Информация не изменилась.');
				endif;
			endif;
			redirect($this->uri->uri_string());
		endif;
		
		if($this->input->post('mtsubmit')):
			$_POST['mtsubmit'] = NULL;
			$this->form_validation->set_rules('recipient',' ','required|trim');
			$this->form_validation->set_rules('text',' ','required|trim');
			if(!$this->form_validation->run()):
				$this->session->set_userdata('msgr','Ошибка при сохранении. Не заполены необходимые поля.');
			else:
				$id = $this->mdmessages->insert_record($this->user['uid'],$_POST['recipient'],$_POST['text']);
				if($id):
					if($this->mdusers->read_field($_POST['recipient'],'sendmail')):
						ob_start();
						?>
						<p><strong>Здравствуйте, <?=$this->mdusers->read_field($_POST['recipient'],'fio');?></strong></p>
						<p>У Вас новое сообщение</p>
						<p>Что бы прочитать его вводите в личный кабинет и перейдите в раздел тикеты</p>
						<p>Желаем Вам удачи!</p> 
						<?
						$mailtext = ob_get_clean();
						
						$this->email->clear(TRUE);
						$config['smtp_host'] = 'localhost';
						$config['charset'] = 'utf-8';
						$config['wordwrap'] = TRUE;
						$config['mailtype'] = 'html';
						
						$this->email->initialize($config);
						$this->email->to($this->mdusers->read_field($_POST['recipient'],'login'));
						$this->email->from('admin@bystropost.ru','Bystropost.ru - Система управления продажами');
						$this->email->bcc('');
						$this->email->subject('Noreply: Bystropost.ru - Почта. Новое сообщение');
						$this->email->message($mailtext);	
						$this->email->send();
					endif;
					$this->session->set_userdata('msgs','Сообщение отправлено');
				endif;
				if(isset($_POST['sendmail'])):
					
				endif;
			endif;
			redirect($this->uri->uri_string());
		endif;
		
		$from = intval($this->uri->segment(6));
		switch ($this->uri->segment(4)):
			case 'webmasters' 	:	$pagevar['title'] .= 'Группа "Вебмастера"';
									$pagevar['users'] = $this->mdunion->read_users_group_webmasters(10,$from);
									$pagevar['count'] = $this->mdunion->count_users_group_webmasters();
									break;
			case 'optimizators' :	$pagevar['title'] .= 'Группа "Оптимизаторы"';
									$pagevar['users'] = $this->mdunion->read_users_group_optimizators(10,$from);
									$pagevar['count'] = $this->mdunion->count_users_group_optimizators();
									break;
			case 'manegers' 	:	$pagevar['title'] .= 'Группа "Менеджеры"';
									$pagevar['users'] = $this->mdunion->read_users_group_manegers(10,$from);
									$pagevar['count'] = $this->mdunion->count_users_group_manegers();
									break;
			case 'admin' 		:	$pagevar['title'] .= 'Группа "Администраторы"';
									$pagevar['users'] = $this->mdunion->read_users_group_admin(10,$from);
									$pagevar['count'] = $this->mdunion->count_users_group_admin();
									break;
			default 			:	$pagevar['title'] .= 'Все группы';
									$pagevar['users'] = $this->mdunion->read_users_group_all(10,$from);
									$pagevar['count'] = $this->mdunion->count_users_group_all();
									break;
		endswitch;
		
		$config['base_url'] 		= $pagevar['baseurl'].'admin-panel/management/users/'.$this->uri->segment(4).'/from/';
		$config['uri_segment'] 		= 6;
		$config['total_rows'] 		= $pagevar['count']; 
		$config['per_page'] 		= 10;
		$config['num_links'] 		= 4;
		$config['first_link']		= 'В начало';
		$config['last_link'] 		= 'В конец';
		$config['next_link'] 		= 'Далее &raquo;';
		$config['prev_link'] 		= '&laquo; Назад';
		$config['cur_tag_open']		= '<span class="actpage">';
		$config['cur_tag_close'] 	= '</span>';
		
		$this->pagination->initialize($config);
		$pagevar['pages'] = $this->pagination->create_links();
		
		if($this->input->post('scsubmit')):
			$_POST['scsubmit'] = NULL;
			$this->form_validation->set_rules('srusrid',' ','required|numeric|trim');
			if(!$this->form_validation->run()):
				redirect($this->uri->uri_string());
			else:
				$result = $this->mdusers->read_users($_POST['srusrid']);
				if($result):
					$pagevar['title'] .= 'Поиск выполнен';
					$pagevar['users'] = $result;
					$pagevar['pages'] = NULL;
				endif;
			endif;
		endif;
		
		for($i=0;$i<count($pagevar['users']);$i++):
			$pagevar['users'][$i]['signdate'] = $this->operation_dot_date($pagevar['users'][$i]['signdate']);
			if($pagevar['users'][$i]['lastlogin'] != '0000-00-00'):
				$pagevar['users'][$i]['lastlogin'] = $this->operation_dot_date($pagevar['users'][$i]['lastlogin']);
			else:
				$pagevar['users'][$i]['lastlogin'] = '';
			endif;
			if($pagevar['users'][$i]['type'] == 1):
				if($pagevar['users'][$i]['manager']):
					$pagevar['users'][$i]['manfio'] = $this->mdusers->read_field($pagevar['users'][$i]['manager'],'fio');
					$pagevar['users'][$i]['manemail'] = $this->mdusers->read_field($pagevar['users'][$i]['manager'],'login');
				endif;
				$pagevar['users'][$i]['platforms'] = $this->mdplatforms->count_records_by_webmaster($pagevar['users'][$i]['id']);
				$pagevar['users'][$i]['uporders'] = $this->mddelivesworks->count_records_by_webmaster_status($pagevar['users'][$i]['id'],0);
				$pagevar['users'][$i]['torders'] = $this->mddelivesworks->count_records_by_webmaster($pagevar['users'][$i]['id']);
			endif;
		endfor;
		$pagevar['cntunit']['users'] = $this->mdusers->count_all();
		$pagevar['cntunit']['platforms'] = $this->mdplatforms->count_all();
		$pagevar['cntunit']['markets'] = $this->mdmarkets->count_all();
		$pagevar['cntunit']['services'] = $this->mdservices->count_all();
		$pagevar['cntunit']['twork'] = $this->mdtypeswork->count_all();
		$pagevar['cntunit']['mails'] = $this->mdmessages->count_records_by_admin_new($this->user['uid']);
		$this->session->set_userdata('backpath',$this->uri->uri_string());
		$this->load->view("admin_interface/management-users",$pagevar);
	}
	
	public function search_users(){
		
		$statusval = array('status'=>FALSE,'retvalue'=>'');
		$search = $this->input->post('squery');
		if(!$search) show_404();
		$users = $this->mdusers->search_users($search);
		if($users):
			$statusval['retvalue'] = '<ul>';
			for($i=0;$i<count($users);$i++):
				$statusval['retvalue'] .= '<li class="usrorg" data-usrid="'.$users[$i]['id'].'">'.$users[$i]['login'].'</li>';
			endfor;
			$statusval['retvalue'] .= '</ul>';
			$statusval['status'] = TRUE;
		endif;
		echo json_encode($statusval);
	}
	
	public function management_users_profile(){
		
		$user = $this->uri->segment(6);
		$pagevar = array(
					'description'	=> '',
					'author'		=> '',
					'title'			=> 'Администрирование | Личный кабинет',
					'baseurl' 		=> base_url(),
					'userinfo'		=> $this->user,
					'user'			=> $this->mdusers->read_record($user),
					'cntunit'		=> array(),
					'msgs'			=> $this->session->userdata('msgs'),
					'msgr'			=> $this->session->userdata('msgr')
			);
		$this->session->unset_userdata('msgs');
		$this->session->unset_userdata('msgr');
		
		if($this->input->post('submit')):
			$_POST['submit'] = NULL;
			$this->form_validation->set_rules('fio',' ','required|trim');
			$this->form_validation->set_rules('oldpas',' ','trim');
			$this->form_validation->set_rules('password',' ','trim');
			$this->form_validation->set_rules('confpass',' ','trim');
			$this->form_validation->set_rules('wmid',' ','required|trim');
			$this->form_validation->set_rules('phones',' ','trim');
			$this->form_validation->set_rules('icq',' ','trim');
			$this->form_validation->set_rules('skype',' ','trim');
			if(!$this->form_validation->run()):
				$this->session->set_userdata('msgr','Ошибка. Неверно заполены необходимые поля<br/>');
				redirect($this->uri->uri_string());
			else:
				if(!empty($_POST['oldpas']) && !empty($_POST['password']) && !empty($_POST['confpass'])):
					if(!$this->mdusers->user_exist('password',md5($_POST['oldpas']))):
						$this->session->set_userdata('msgr',' Не верный старый пароль!');
					elseif($_POST['password']!=$_POST['confpass']):
						$this->session->set_userdata('msgr',' Пароли не совпадают.');
					else:
						$this->mdusers->update_field($user,'password',md5($_POST['password']));
						$this->mdusers->update_field($user,'cryptpassword',$this->encrypt->encode($_POST['password']));
						$this->session->set_userdata('msgs',' Пароль успешно изменен');
					endif;
				endif;
				if(!isset($_POST['sendmail'])):
					$_POST['sendmail'] = 0;
				endif;
				unset($_POST['password']);unset($_POST['login']);
				$_POST['uid'] = $user;
				$wmid = $this->mdusers->read_by_wmid($_POST['wmid']);
				if($wmid && $wmid != $user):
					$this->session->set_userdata('msgr','Ошибка. WMID уже зареристрирован!');
					redirect($this->uri->uri_string());
				endif;
				$result = $this->mdusers->update_record($_POST);
				if($result):
					$msgs = 'Личные данные успешно сохранены.<br/>'.$this->session->userdata('msgs');
					$this->session->set_userdata('msgs',$msgs);
				endif;
				redirect($this->uri->uri_string());
			endif;
		endif;
		
		$pagevar['cntunit']['users'] = $this->mdusers->count_all();
		$pagevar['cntunit']['platforms'] = $this->mdplatforms->count_all();
		$pagevar['cntunit']['markets'] = $this->mdmarkets->count_all();
		$pagevar['cntunit']['services'] = $this->mdservices->count_all();
		$pagevar['cntunit']['twork'] = $this->mdtypeswork->count_all();
		$pagevar['user']['signdate'] = $this->operation_date($pagevar['user']['signdate']);
		$pagevar['user']['oldpassword'] = $this->encrypt->decode($pagevar['user']['cryptpassword']);
		$pagevar['cntunit']['mails'] = $this->mdmessages->count_records_by_admin_new($this->user['uid']);
		
		$this->load->view("admin_interface/admin-profile",$pagevar);
	}
	
	public function management_users_deleting(){
		
		$uid = $this->uri->segment(5);
		if($uid):
			$user = $this->mdusers->read_record($uid);
			$result = $this->mdusers->delete_record($uid);
			if($result):
				$this->mdmessages->delete_records_by_user($uid);
				$this->mdtickets->delete_records_by_user($uid);
				$this->mdtkmsgs->delete_records_by_user($uid);
				$this->mdplatforms->close_platform_by_user_delete($uid);
				ob_start();
				?>
				<p><strong>Здравствуйте, <?=$user['fio'];?></strong></p>
				<p>Ваша учетная запись удалена Администратором</p>
				<p>Желаем Вам удачи!</p> 
				<?
				$mailtext = ob_get_clean();
				
				$this->email->clear(TRUE);
				$config['smtp_host'] = 'localhost';
				$config['charset'] = 'utf-8';
				$config['wordwrap'] = TRUE;
				$config['mailtype'] = 'html';
				
				$this->email->initialize($config);
				$this->email->to($user['login']);
				$this->email->from('admin@bystropost.ru','Bystropost.ru - Система управления продажами');
				$this->email->bcc('');
				$this->email->subject('Noreply: Bystropost.ru - Учетная запись удалена');
				$this->email->message($mailtext);	
				$this->email->send();
				$this->session->set_userdata('msgs','Пользователь удален успешно.');
			else:
				$this->session->set_userdata('msgr','Пользователь не удален.');
			endif;
			redirect($_SERVER['HTTP_REFERER']);
		else:
			show_404();
		endif;
	}
	
	/******************************************************** platforms ******************************************************/
	
	public function search_platforms(){
		
		$statusval = array('status'=>FALSE,'retvalue'=>'');
		$search = $this->input->post('squery');
		if(!$search) show_404();
		$platforms = $this->mdplatforms->search_platforms($search);
		if($platforms):
			$statusval['retvalue'] = '<ul>';
			for($i=0;$i<count($platforms);$i++):
				$statusval['retvalue'] .= '<li class="plorg" data-plid="'.$platforms[$i]['id'].'">'.$platforms[$i]['url'].'</li>';
			endfor;
			$statusval['retvalue'] .= '</ul>';
			$statusval['status'] = TRUE;
		endif;
		echo json_encode($statusval);
	}
	
	public function user_platforms_list(){
		
		$user = $this->uri->segment(5);
		$utype = $this->mdusers->read_field($user,'type');
		if($utype != 1):
			redirect('admin-panel/management/users/all');
		endif;
		$pagevar = array(
					'description'	=> '',
					'author'		=> '',
					'title'			=> 'Администрирование | Список площадок',
					'baseurl' 		=> base_url(),
					'userinfo'		=> $this->user,
					'platforms'		=> $this->mdplatforms->read_records_by_webmaster($user),
					'cntunit'		=> array(),
					'managers'		=> $this->mdusers->read_users_by_type(2),
					'markets'		=> $this->mdunion->read_mkplatform_by_webmaster($user),
					'msgs'			=> $this->session->userdata('msgs'),
					'msgr'			=> $this->session->userdata('msgr')
			);
		$this->session->unset_userdata('msgs');
		$this->session->unset_userdata('msgr');
		
		if($this->input->post('epsubmit')):
			$_POST['epsubmit'] = NULL;
			$this->form_validation->set_rules('pid',' ','required|trim');
			$this->form_validation->set_rules('uid',' ','required|trim');
			$this->form_validation->set_rules('ccontext',' ','required|trim');
			$this->form_validation->set_rules('mcontext',' ','required|trim');
			$this->form_validation->set_rules('cnotice',' ','required|trim');
			$this->form_validation->set_rules('mnotice',' ','required|trim');
			$this->form_validation->set_rules('creview',' ','required|trim');
			$this->form_validation->set_rules('mreview',' ','required|trim');
			$this->form_validation->set_rules('cnews',' ','required|trim');
			$this->form_validation->set_rules('mnews',' ','required|trim');
			$this->form_validation->set_rules('manager',' ','required|trim');
			$this->form_validation->set_rules('clinkpic',' ','required|trim');
			$this->form_validation->set_rules('mlinkpic',' ','required|trim');
			$this->form_validation->set_rules('cpressrel',' ','required|trim');
			$this->form_validation->set_rules('mpressrel',' ','required|trim');
			$this->form_validation->set_rules('clinkarh',' ','required|trim');
			$this->form_validation->set_rules('mlinkarh',' ','required|trim');
			
			if(!$this->form_validation->run()):
				$this->session->set_userdata('msgr','Ошибка при сохранении. Не заполены необходимые поля.');
			else:
				if(!isset($_POST['locked'])):
					$_POST['locked'] = 0;
				endif;
				$prevman = $this->mdplatforms->read_field($_POST['pid'],'manager');
				$prevlock = $this->mdplatforms->read_field($_POST['pid'],'locked');
				$result1 = $this->mdplatforms->update_lock($_POST['pid'],$_POST['uid'],$_POST['locked']);
				$result2 = $this->mdplatforms->update_manager($_POST['pid'],$_POST['uid'],$_POST['manager']);
				$result3 = $this->mdplatforms->update_price($_POST['pid'],$_POST['uid'],$_POST);
				if($result1 || $result2 || $result3):
					$platform = $this->mdplatforms->read_field($_POST['pid'],'url');
					if(!$prevman && $_POST['manager']):
						$text = 'Здравствуйте! Ваша площадка '.$platform.' принята к работе';
						$this->mdmessages->send_noreply_message($this->user['uid'],$_POST['uid'],4,1,$text);
						$text = 'Здравствуйте! За Вами закреплена площадка '.$platform;
						$this->mdmessages->send_noreply_message($this->user['uid'],$_POST['manager'],4,2,$text);
						if($this->mdusers->read_field($_POST['uid'],'sendmail')):
							ob_start();
							?>
							<p><strong>Здравствуйте, <?=$this->mdusers->read_field($_POST['uid'],'fio');?></strong></p>
							<p>Ваша площадка <?=$platform;?> принята к работе</p>
							<p>Желаем Вам удачи!</p> 
							<?
							$mailtext = ob_get_clean();
							
							$this->email->clear(TRUE);
							$config['smtp_host'] = 'localhost';
							$config['charset'] = 'utf-8';
							$config['wordwrap'] = TRUE;
							$config['mailtype'] = 'html';
							
							$this->email->initialize($config);
							$this->email->to($this->mdusers->read_field($_POST['uid'],'login'));
							$this->email->from('admin@bystropost.ru','Bystropost.ru - Система управления продажами');
							$this->email->bcc('');
							$this->email->subject('Noreply: Bystropost.ru - Площадка в работе');
							$this->email->message($mailtext);	
							$this->email->send();
						endif;
						if($this->mdusers->read_field($_POST['manager'],'sendmail')):
							ob_start();
							?>
							<p><strong>Здравствуйте, <?=$this->mdusers->read_field($_POST['manager'],'fio');?></strong></p>
							<p>За Вами закреплена площадка  <?=$platform;?></p>
							<p>Желаем Вам удачи!</p> 
							<?
							$mailtext = ob_get_clean();
							
							$this->email->clear(TRUE);
							$config['smtp_host'] = 'localhost';
							$config['charset'] = 'utf-8';
							$config['wordwrap'] = TRUE;
							$config['mailtype'] = 'html';
							
							$this->email->initialize($config);
							$this->email->to($this->mdusers->read_field($_POST['manager'],'login'));
							$this->email->from('admin@bystropost.ru','Bystropost.ru - Система управления продажами');
							$this->email->bcc('');
							$this->email->subject('Noreply: Bystropost.ru - Новая площадка');
							$this->email->message($mailtext);	
							$this->email->send();
						endif;
					elseif($prevman && !$_POST['manager']):
						$text = 'Здравствуйте! Ваша площадка '.$platform.' снята с работы';
						$this->mdmessages->send_noreply_message($this->user['uid'],$_POST['uid'],1,1,$text);
						$text = 'Здравствуйте! С Ваc снята площадка '.$platform;
						$this->mdmessages->send_noreply_message($this->user['uid'],$prevman,1,2,$text);
						if($this->mdusers->read_field($_POST['uid'],'sendmail')):
							//Высылать письмо-уведомление
						endif;
						if($this->mdusers->read_field($_POST['manager'],'sendmail')):
							//Высылать письмо-уведомление
						endif;
					elseif($prevman != $_POST['manager']):
						$text = 'Здравствуйте! За Вами закреплена новая площадка '.$platform;
						$this->mdmessages->send_noreply_message($this->user['uid'],$_POST['manager'],4,2,$text);
						$text = 'Здравствуйте! С Ваc снята площадка '.$platform;
						$this->mdmessages->send_noreply_message($this->user['uid'],$prevman,1,2,$text);
						
						$this->mdtickets->change_sender_recipient_by_new_manager($_POST['manager'],$prevman,$_POST['pid']);
						$this->mddelivesworks->change_managers($_POST['manager'],$prevman,$_POST['pid']);
						
						if($this->mdusers->read_field($prevman,'sendmail')):
							//Высылать письмо-уведомление
						endif;
						if($this->mdusers->read_field($_POST['manager'],'sendmail')):
							//Высылать письмо-уведомление
						endif;
					endif;
					$manager = $this->mdplatforms->read_field($platform,'manager');
					$remote_id = $this->mdplatforms->read_field($platform,'remoteid');
					if(!$prevlock && $_POST['locked']):
						$text = 'Здравствуйте! Ваша площадка '.$platform.' заблокирована администратором';
						$this->mdmessages->send_noreply_message($this->user['uid'],$_POST['uid'],1,1,$text);
						if($_POST['manager']):
							$text = 'Здравствуйте! Закреплення за Вами площадка '.$platform.' заблокирована администратором';
							$this->mdmessages->send_noreply_message($this->user['uid'],$_POST['manager'],1,2,$text);
							if($this->mdusers->read_field($_POST['manager'],'sendmail')):
								//Высылать письмо-уведомление
							endif;
						endif;
						if($manager == 2 && $remote_id):
							$param = 'siteid='.$remote_id.'&value=1';
							$res = $this->API('SetSiteActive',$param);
						endif;
						if($this->mdusers->read_field($_POST['uid'],'sendmail')):
							//Высылать письмо-уведомление
						endif;
					elseif($prevlock && !$_POST['locked']):
						$text = 'Здравствуйте! Ваша площадка '.$platform.' разблокирована администратором';
						$this->mdmessages->send_noreply_message($this->user['uid'],$_POST['uid'],4,1,$text);
						if($_POST['manager']):
							$text = 'Здравствуйте! Закреплення за Вами площадка '.$platform.' разблокирована администратором';
							$this->mdmessages->send_noreply_message($this->user['uid'],$_POST['manager'],4,2,$text);
							if($this->mdusers->read_field($_POST['manager'],'sendmail')):
								//Высылать письмо-уведомление
							endif;
						endif;
						if($manager == 2 && $remote_id):
							$param = 'siteid='.$remote_id.'&value=0';
							$res = $this->API('SetSiteActive',$param);
						endif;
						if($this->mdusers->read_field($_POST['uid'],'sendmail')):
							//Высылать письмо-уведомление
						endif;
					endif;
					$this->session->set_userdata('msgs','Информация успешно сохранена.');
				endif;
			endif;
			redirect($this->uri->uri_string());
		endif;
		
		$ufio = $this->mdusers->read_field($user,'fio');
		$ulogin = $this->mdusers->read_field($user,'login');
		for($i=0;$i<count($pagevar['platforms']);$i++):
			$pagevar['platforms'][$i]['date'] = $this->operation_dot_date($pagevar['platforms'][$i]['date']);
			$pagevar['platforms'][$i]['fio'] = $ufio;
			$pagevar['platforms'][$i]['login'] = $ulogin;
			$pagevar['platforms'][$i]['uid'] = $user;
			if($pagevar['platforms'][$i]['manager']):
				$pagevar['platforms'][$i]['manfio'] = $this->mdusers->read_field($pagevar['platforms'][$i]['manager'],'fio');
				$pagevar['platforms'][$i]['manemail'] = $this->mdusers->read_field($pagevar['platforms'][$i]['manager'],'login');
			else:
				$pagevar['platforms'][$i]['manfio'] = '<font style="color:#ff0000;">Менеджер не закреплен</font>';
				$pagevar['platforms'][$i]['manemail'] = '';
			endif;
			$pagevar['platforms'][$i]['uporders'] = $this->mddelivesworks->count_records_by_platform_status($pagevar['platforms'][$i]['id'],0);
			$pagevar['platforms'][$i]['torders'] = $this->mddelivesworks->count_records_by_platform($pagevar['platforms'][$i]['id']);
		endfor;
		
		$pagevar['cntunit']['users'] = $this->mdusers->count_all();
		$pagevar['cntunit']['platforms'] = $this->mdplatforms->count_all();
		$pagevar['cntunit']['markets'] = $this->mdmarkets->count_all();
		$pagevar['cntunit']['services'] = $this->mdservices->count_all();
		$pagevar['cntunit']['twork'] = $this->mdtypeswork->count_all();
		$pagevar['cntunit']['mails'] = $this->mdmessages->count_records_by_admin_new($this->user['uid']);
		$this->load->view("admin_interface/webmaster-platforms-list",$pagevar);
	}
	
	public function management_platforms(){
		
		$from = intval($this->uri->segment(5));
		$pagevar = array(
					'description'	=> '',
					'author'		=> '',
					'title'			=> 'Администрирование | Площадки',
					'baseurl' 		=> base_url(),
					'userinfo'		=> $this->user,
					'platforms'		=> $this->mdunion->read_platforms_by_owners_pages(5,$from),
					'count'			=> $this->mdunion->count_platforms_by_owners(),
					'pages'			=> array(),
					'cntunit'		=> array(),
					'managers'		=> $this->mdusers->read_users_by_type(2),
					'msgs'			=> $this->session->userdata('msgs'),
					'msgr'			=> $this->session->userdata('msgr')
			);
		$this->session->unset_userdata('msgs');
		$this->session->unset_userdata('msgr');
		
		if($this->input->post('mtsubmit')):
			$_POST['mtsubmit'] = NULL;
			$this->form_validation->set_rules('recipient',' ','required|trim');
			$this->form_validation->set_rules('text',' ','required|trim');
			if(!$this->form_validation->run()):
				$this->session->set_userdata('msgr','Ошибка при сохранении. Не заполены необходимые поля.');
			else:
				$id = $this->mdmessages->insert_record($this->user['uid'],$_POST['recipient'],$_POST['text']);
				if($id):
					$this->session->set_userdata('msgs','Сообщение отправлено');
				endif;
				if(isset($_POST['sendmail'])):
					//Высылать письмо-уведомление
				endif;
			endif;
			redirect($this->uri->uri_string());
		endif;
		
		if($this->input->post('epsubmit')):
			$_POST['epsubmit'] = NULL;
			$this->form_validation->set_rules('pid',' ','required|trim');
			$this->form_validation->set_rules('uid',' ','required|trim');
			$this->form_validation->set_rules('ccontext',' ','required|trim');
			$this->form_validation->set_rules('mcontext',' ','required|trim');
			$this->form_validation->set_rules('cnotice',' ','required|trim');
			$this->form_validation->set_rules('mnotice',' ','required|trim');
			$this->form_validation->set_rules('creview',' ','required|trim');
			$this->form_validation->set_rules('mreview',' ','required|trim');
			$this->form_validation->set_rules('cnews',' ','required|trim');
			$this->form_validation->set_rules('mnews',' ','required|trim');
			$this->form_validation->set_rules('manager',' ','required|trim');
			$this->form_validation->set_rules('clinkpic',' ','required|trim');
			$this->form_validation->set_rules('mlinkpic',' ','required|trim');
			$this->form_validation->set_rules('cpressrel',' ','required|trim');
			$this->form_validation->set_rules('mpressrel',' ','required|trim');
			$this->form_validation->set_rules('clinkarh',' ','required|trim');
			$this->form_validation->set_rules('mlinkarh',' ','required|trim');
			
			if(!$this->form_validation->run()):
				$this->session->set_userdata('msgr','Ошибка при сохранении. Не заполены необходимые поля.');
			else:
				if(!isset($_POST['locked'])):
					$_POST['locked'] = 0;
				endif;
				$prevman = $this->mdplatforms->read_field($_POST['pid'],'manager');
				$prevlock = $this->mdplatforms->read_field($_POST['pid'],'locked');
				$result1 = $this->mdplatforms->update_lock($_POST['pid'],$_POST['uid'],$_POST['locked']);
				$result2 = $this->mdplatforms->update_manager($_POST['pid'],$_POST['uid'],$_POST['manager']);
				$result3 = $this->mdplatforms->update_price($_POST['pid'],$_POST['uid'],$_POST);
				if($result1 || $result2 || $result3):
					$platform = $this->mdplatforms->read_field($_POST['pid'],'url');
					if(!$prevman && $_POST['manager']):
						$text = 'Здравствуйте! Ваша площадка '.$platform.' принята к работе';
						$this->mdmessages->send_noreply_message($this->user['uid'],$_POST['uid'],4,1,$text);
						$text = 'Здравствуйте! За Вами закреплена площадка '.$platform;
						$this->mdmessages->send_noreply_message($this->user['uid'],$_POST['manager'],4,2,$text);
						if($this->mdusers->read_field($_POST['uid'],'sendmail')):
							ob_start();
							?>
							<p><strong>Здравствуйте, <?=$this->mdusers->read_field($_POST['uid'],'fio');?></strong></p>
							<p>Ваша площадка <?=$platform;?> принята к работе</p>
							<p>Желаем Вам удачи!</p> 
							<?
							$mailtext = ob_get_clean();
							
							$this->email->clear(TRUE);
							$config['smtp_host'] = 'localhost';
							$config['charset'] = 'utf-8';
							$config['wordwrap'] = TRUE;
							$config['mailtype'] = 'html';
							
							$this->email->initialize($config);
							$this->email->to($this->mdusers->read_field($_POST['uid'],'login'));
							$this->email->from('admin@bystropost.ru','Bystropost.ru - Система управления продажами');
							$this->email->bcc('');
							$this->email->subject('Noreply: Bystropost.ru - Площадка в работе');
							$this->email->message($mailtext);	
							$this->email->send();
						endif;
						if($this->mdusers->read_field($_POST['manager'],'sendmail')):
							ob_start();
							?>
							<p><strong>Здравствуйте, <?=$this->mdusers->read_field($_POST['manager'],'fio');?></strong></p>
							<p>За Вами закреплена площадка  <?=$platform;?></p>
							<p>Желаем Вам удачи!</p> 
							<?
							$mailtext = ob_get_clean();
							
							$this->email->clear(TRUE);
							$config['smtp_host'] = 'localhost';
							$config['charset'] = 'utf-8';
							$config['wordwrap'] = TRUE;
							$config['mailtype'] = 'html';
							
							$this->email->initialize($config);
							$this->email->to($this->mdusers->read_field($_POST['manager'],'login'));
							$this->email->from('admin@bystropost.ru','Bystropost.ru - Система управления продажами');
							$this->email->bcc('');
							$this->email->subject('Noreply: Bystropost.ru - Новая площадка');
							$this->email->message($mailtext);	
							$this->email->send();
						endif;
					elseif($prevman && !$_POST['manager']):
						$text = 'Здравствуйте! Ваша площадка '.$platform.' снята с работы';
						$this->mdmessages->send_noreply_message($this->user['uid'],$_POST['uid'],1,1,$text);
						$text = 'Здравствуйте! С Ваc снята площадка '.$platform;
						$this->mdmessages->send_noreply_message($this->user['uid'],$prevman,1,2,$text);
						if($this->mdusers->read_field($_POST['uid'],'sendmail')):
							//Высылать письмо-уведомление
						endif;
						if($this->mdusers->read_field($_POST['manager'],'sendmail')):
							//Высылать письмо-уведомление
						endif;
					elseif($prevman != $_POST['manager']):
						$text = 'Здравствуйте! За Вами закреплена новая площадка '.$platform;
						$this->mdmessages->send_noreply_message($this->user['uid'],$_POST['manager'],4,2,$text);
						$text = 'Здравствуйте! С Ваc снята площадка '.$platform;
						$this->mdmessages->send_noreply_message($this->user['uid'],$prevman,1,2,$text);
						
						$this->mdtickets->change_sender_recipient_by_new_manager($_POST['manager'],$prevman,$_POST['pid']);
						$this->mddelivesworks->change_managers($_POST['manager'],$prevman,$_POST['pid']);
						
						if($this->mdusers->read_field($prevman,'sendmail')):
							//Высылать письмо-уведомление
						endif;
						if($this->mdusers->read_field($_POST['manager'],'sendmail')):
							//Высылать письмо-уведомление
						endif;
					endif;
					$manager = $this->mdplatforms->read_field($_POST['pid'],'manager');
					$remote_id = $this->mdplatforms->read_field($_POST['pid'],'remoteid');
					if(!$prevlock && $_POST['locked']):
						$text = 'Здравствуйте! Ваша площадка '.$platform.' заблокирована администратором';
						$this->mdmessages->send_noreply_message($this->user['uid'],$_POST['uid'],1,1,$text);
						if($_POST['manager']):
							$text = 'Здравствуйте! Закреплення за Вами площадка '.$platform.' заблокирована администратором';
							$this->mdmessages->send_noreply_message($this->user['uid'],$_POST['manager'],1,2,$text);
							if($this->mdusers->read_field($_POST['manager'],'sendmail')):
								//Высылать письмо-уведомление
							endif;
						endif;
						
						if($manager == 2 && $remote_id):
							$param = 'siteid='.$remote_id.'&value=1';
							$res = $this->API('SetSiteActive',$param);
						endif;
						if($this->mdusers->read_field($_POST['uid'],'sendmail')):
							//Высылать письмо-уведомление
						endif;
					elseif($prevlock && !$_POST['locked']):
						$text = 'Здравствуйте! Ваша площадка '.$platform.' разблокирована администратором';
						$this->mdmessages->send_noreply_message($this->user['uid'],$_POST['uid'],4,1,$text);
						if($_POST['manager']):
							$text = 'Здравствуйте! Закреплення за Вами площадка '.$platform.' разблокирована администратором';
							$this->mdmessages->send_noreply_message($this->user['uid'],$_POST['manager'],4,2,$text);
							if($this->mdusers->read_field($_POST['manager'],'sendmail')):
								//Высылать письмо-уведомление
							endif;
						endif;
						if($manager == 2 && $remote_id):
							$param = 'siteid='.$remote_id.'&value=0';
							$res = $this->API('SetSiteActive',$param);
						endif;
						if($this->mdusers->read_field($_POST['uid'],'sendmail')):
							//Высылать письмо-уведомление
						endif;
					endif;
					$this->session->set_userdata('msgs','Информация успешно сохранена.');
				endif;
			endif;
			redirect($this->uri->uri_string());
		endif;
		
		$config['base_url'] 		= $pagevar['baseurl'].'admin-panel/management/platforms/from/';
		$config['uri_segment'] 		= 5;
		$config['total_rows'] 		= $pagevar['count']; 
		$config['per_page'] 		= 5;
		$config['num_links'] 		= 4;
		$config['first_link']		= 'В начало';
		$config['last_link'] 		= 'В конец';
		$config['next_link'] 		= 'Далее &raquo;';
		$config['prev_link'] 		= '&laquo; Назад';
		$config['cur_tag_open']		= '<span class="actpage">';
		$config['cur_tag_close'] 	= '</span>';
		
		$this->pagination->initialize($config);
		$pagevar['pages'] = $this->pagination->create_links();
		
		if($this->input->post('scsubmit')):
			$_POST['scsubmit'] = NULL;
			$this->form_validation->set_rules('srplid',' ','required|numeric|trim');
			if(!$this->form_validation->run()):
				redirect($this->uri->uri_string());
			else:
				$result = $this->mdunion->read_platform_by_id($_POST['srplid']);
				if($result):
					$pagevar['title'] .= 'Администрирование | Площадки | Поиск выполнен';
					$pagevar['platforms'] = $result;
					$pagevar['pages'] = NULL;
				endif;
			endif;
		endif;
		
		for($i=0;$i<count($pagevar['platforms']);$i++):
			$pagevar['platforms'][$i]['markets'] = $this->mdmkplatform->read_records_platform($pagevar['platforms'][$i]['id']);
			$pagevar['platforms'][$i]['date'] = $this->operation_dot_date($pagevar['platforms'][$i]['date']);
			if(empty($pagevar['platforms'][$i]['fio'])):
				$pagevar['platforms'][$i]['fio'] = '';
				$pagevar['platforms'][$i]['login'] = '<font style="color:#ff0000;">Владелец не определен</font>';
			endif;
			if($pagevar['platforms'][$i]['manager']):
				$pagevar['platforms'][$i]['manfio'] = $this->mdusers->read_field($pagevar['platforms'][$i]['manager'],'fio');
				$pagevar['platforms'][$i]['manemail'] = $this->mdusers->read_field($pagevar['platforms'][$i]['manager'],'login');
			else:
				$pagevar['platforms'][$i]['manfio'] = '<font style="color:#ff0000;">Менеджер не закреплен</font>';
				$pagevar['platforms'][$i]['manemail'] = '';
			endif;
			$pagevar['platforms'][$i]['uporders'] = $this->mddelivesworks->count_records_by_platform_status($pagevar['platforms'][$i]['id'],0);
			$pagevar['platforms'][$i]['torders'] = $this->mddelivesworks->count_records_by_platform($pagevar['platforms'][$i]['id']);
		endfor;
		$pagevar['cntunit']['users'] = $this->mdusers->count_all();
		$pagevar['cntunit']['platforms'] = $this->mdplatforms->count_all();
		$pagevar['cntunit']['markets'] = $this->mdmarkets->count_all();
		$pagevar['cntunit']['services'] = $this->mdservices->count_all();
		$pagevar['cntunit']['twork'] = $this->mdtypeswork->count_all();
		$pagevar['cntunit']['mails'] = $this->mdmessages->count_records_by_admin_new($this->user['uid']);
		$this->session->set_userdata('backpath',$this->uri->uri_string());
		$this->load->view("admin_interface/management-platforms",$pagevar);
	}

	public function management_delete_platform(){
		
		$pid = $this->uri->segment(6);
		if($pid):
			$info = $this->mdplatforms->read_record($pid);
			$result = $this->mdplatforms->delete_record($pid);
			if($result):
				$text = 'Площадка '.$info['url'].'. Удалена администратором';
				if($info['webmaster']):
					$this->mdmkplatform->delete_records_by_platform($pid,$info['webmaster']);
					$this->mddelivesworks->delete_records_by_platform($pid,$info['webmaster']);
					$this->mdattachedservices->delete_records_by_platform($pid,$info['webmaster']);
//					$this->mdtickets->delete_records_by_platform($pid);
					$this->mdmessages->send_noreply_message($this->user['uid'],$info['webmaster'],1,1,$text);
					ob_start();
					?>
					<p><strong>Здравствуйте, <?=$this->mdusers->read_field($info['webmaster'],'fio');?></strong></p>
					<p>Ваша площадка <?=$info['url'];?> удалена администратором</p>
					<p>Желаем Вам удачи!</p> 
					<?
					$mailtext = ob_get_clean();
					
					$this->email->clear(TRUE);
					$config['smtp_host'] = 'localhost';
					$config['charset'] = 'utf-8';
					$config['wordwrap'] = TRUE;
					$config['mailtype'] = 'html';
					
					$this->email->initialize($config);
					$this->email->to($this->mdusers->read_field($info['webmaster'],'login'));
					$this->email->from('admin@bystropost.ru','Bystropost.ru - Система управления продажами');
					$this->email->bcc('');
					$this->email->subject('Noreply: Bystropost.ru - Площадка удалена');
					$this->email->message($mailtext);	
					$this->email->send();
				endif;
				if($info['manager']):
					$this->mdmessages->send_noreply_message($this->user['uid'],$info['manager'],1,2,$text);
				endif;
				$this->session->set_userdata('msgs','Площадка удалена успешно.');
				if($info['manager'] == 2 && $info['remoteid']):
					$param = 'siteid='.$info['remoteid'].'&value=1';
					$res = $this->API('SetSiteActive',$param);
				endif;
			else:
				$this->session->set_userdata('msgr','Площадка не удалена.');
			endif;
			redirect($_SERVER['HTTP_REFERER']);
		else:
			show_404();
		endif;
	}
	
	public function control_edit_platform(){
		
		$platform = $this->uri->segment(5);
		$webmaster = $this->mdplatforms->read_field($platform,'webmaster');
		
		$pagevar = array(
					'description'	=> '',
					'author'		=> '',
					'title'			=> 'Кабинет Вебмастера | Площадки | Редактирование площадки',
					'baseurl' 		=> base_url(),
					'loginstatus'	=> $this->loginstatus['status'],
					'userinfo'		=> $this->user,
					'platform'		=> $this->mdplatforms->read_record($platform),
					'markets'		=> $this->mdmarkets->read_records(),
					'thematic'		=> $this->mdthematic->read_records(),
					'cms'			=> $this->mdcms->read_records(),
					'mymarkets'		=> $this->mdmkplatform->read_records_by_platform($platform,$webmaster),
					'services'		=> array(),
					'msgs'			=> $this->session->userdata('msgs'),
					'msgr'			=> $this->session->userdata('msgr')
			);
		$this->session->unset_userdata('msgs');
		$this->session->unset_userdata('msgr');
		
		$pagevar['userinfo']['remote'] = TRUE;
		
		$attached = $this->mdunion->services_attached_list($webmaster);
		for($i=0;$i<count($attached);$i++):
			$pagevar['services'][$i] = $this->mdunion->read_srvvalue_service_platform($attached[$i]['service'],$platform,$webmaster);
		endfor;
		if($this->input->post('submit')):
			$_POST['submit'] = NULL;
			$this->form_validation->set_rules('url',' ','required|trim');
			$this->form_validation->set_rules('subject',' ','required|trim');
			$this->form_validation->set_rules('cms',' ','required|trim');
			$this->form_validation->set_rules('adminpanel',' ','required|trim');
			$this->form_validation->set_rules('aplogin',' ','required|trim');
			$this->form_validation->set_rules('appassword',' ','required|trim');
			$this->form_validation->set_rules('tematcustom',' ','trim');
			$this->form_validation->set_rules('reviews',' ','trim');
			$this->form_validation->set_rules('thematically',' ','trim');
			$this->form_validation->set_rules('illegal',' ','trim');
			$this->form_validation->set_rules('imgstatus',' ','trim');
			$this->form_validation->set_rules('imgwidth',' ','trim');
			$this->form_validation->set_rules('imgheight',' ','trim');
			$this->form_validation->set_rules('imgpos',' ','trim');
			$this->form_validation->set_rules('requests',' ','trim');
			if(!$this->form_validation->run()):
				$this->session->set_userdata('msgr','Ошибка при сохранении. Не заполены необходимые поля.');
				redirect($this->uri->uri_string());
			else:
				if($_POST['imgwidth'] && $_POST['imgheight']):
					$_POST['imgstatus'] = 1;
				else:
					$_POST['imgstatus'] = 0;
					$_POST['imgpos'] = 'right';
				endif;
				$result = $this->mdplatforms->update_record($platform,$webmaster,$_POST);
				if($result):
					if($pagevar['platform']['manager']):
						/********************************************************************/
						if($pagevar['platform']['manager'] == 2):
							$new_platform = $this->mdplatforms->read_record($platform);
							$pl_data = array();
							$pl_data['adminurl'] = $new_platform['adminpanel'];
							$pl_data['cms'] = $new_platform['cms'];
							$pl_data['cms_login'] = $new_platform['aplogin'];
							$pl_data['cms_pass'] = $new_platform['appassword'];
							$pl_data['tematic'] = $new_platform['subject'];
							$pl_data['filter'] = $new_platform['illegal'];
							$pl_data['subjects'] = $new_platform['thematically'];
							$pl_data['review'] = $new_platform['reviews'];
							$pl_data['review'] = $new_platform['reviews'];
							$pl_data['param'] = array();
							$pl_data['param']['image'] = array();
							$pl_data['param']['image']['status'] = $new_platform['imgstatus'];
							$pl_data['param']['image']['imgwidth'] = $new_platform['imgwidth'];
							$pl_data['param']['image']['imgheight'] = $new_platform['imgheight'];
							$pl_data['param']['image']['imgpos'] = $new_platform['imgpos'];
							$pl_data['info'] = $new_platform['requests'];
							$param = 'siteid='.$new_platform['remoteid'].'&conf='.json_encode($pl_data);
							$this->API('UpdateSiteOptions',$param);
						endif;
						/********************************************************************/
					endif;
					$this->mdlog->insert_record($webmaster,'Событие №16: Состояние площадки - изменена');
					$this->session->set_userdata('msgs','Платформа успешно сохранена.');
				endif;
				$this->mdmkplatform->delete_records_by_platform($platform,$webmaster);
				if(isset($_POST['markets'])):
					$cntmarkets = count($_POST['markets']);
					$marketslist = array();
					if($cntmarkets > 0):
						for($i=0,$j=0;$i<$cntmarkets;$i+=3):
							if(empty($_POST['markets'][$i+1]) || empty($_POST['markets'][$i+2])) continue;
							$marketslist[$j]['mkid'] 	= $_POST['markets'][$i];
							$marketslist[$j]['mklogin'] = $_POST['markets'][$i+1];
							$marketslist[$j]['mkpass'] 	= $_POST['markets'][$i+2];
							$j++;
						endfor;
					endif;
					if(count($marketslist)):
						$this->mdmkplatform->group_insert($webmaster,$platform,$marketslist);
					endif;
				endif;
			endif;
			redirect($this->session->userdata('backpath'));
		endif;
		for($i=0;$i<count($pagevar['mymarkets']);$i++):
			$pagevar['mymarkets'][$i]['password'] = $this->encrypt->decode($pagevar['mymarkets'][$i]['cryptpassword']);
		endfor;
		if(!$pagevar['platform']['imgwidth'] && !$pagevar['platform']['imgheight']):
			$pagevar['platform']['imgstatus'] = 0;
			$pagevar['platform']['imgwidth'] = '';
			$pagevar['platform']['imgheight'] = '';
		endif;
		
		$this->load->view("admin_interface/management-edit-platform",$pagevar);
	}
	
	public function management_view_platform(){
		
		$platform = $this->uri->segment(5);
		$pagevar = array(
					'description'	=> '',
					'author'		=> '',
					'title'			=> 'Администрирование | Площадки | Просмотр площадки',
					'baseurl' 		=> base_url(),
					'loginstatus'	=> $this->loginstatus['status'],
					'userinfo'		=> $this->user,
					'platform'		=> $this->mdplatforms->read_record($platform),
					'markets'		=> $this->mdmarkets->read_records(),
					'mymarkets'		=> array(),
					'thematic'		=> $this->mdthematic->read_records(),
					'services'		=> array(),
					'cms'			=> $this->mdcms->read_records(),
					'msgs'			=> $this->session->userdata('msgs'),
					'msgr'			=> $this->session->userdata('msgr')
			);
		$this->session->unset_userdata('msgs');
		$this->session->unset_userdata('msgr');
		
		$pagevar['mymarkets'] = $this->mdmkplatform->read_records_by_platform($platform,$pagevar['platform']['webmaster']);
		$attached = $this->mdunion->services_attached_list($pagevar['platform']['webmaster']);
		for($i=0;$i<count($attached);$i++):
			$pagevar['services'][$i] = $this->mdunion->read_srvvalue_service_platform($attached[$i]['service'],$platform,$pagevar['platform']['webmaster']);
		endfor;
		for($i=0;$i<count($pagevar['mymarkets']);$i++):
			$pagevar['mymarkets'][$i]['password'] = $this->encrypt->decode($pagevar['mymarkets'][$i]['cryptpassword']);
		endfor;
		if(!$pagevar['platform']['imgwidth'] && !$pagevar['platform']['imgheight']):
			$pagevar['platform']['imgstatus'] = 0;
			$pagevar['platform']['imgwidth'] = '-';
			$pagevar['platform']['imgheight'] = '-';
		endif;
		
		$this->load->view("admin_interface/management-view-platform",$pagevar);
	}

	/******************************************************** markets ******************************************************/

	public function management_markets(){
		
		$pagevar = array(
					'description'	=> '',
					'author'		=> '',
					'title'			=> 'Администрирование | Биржи',
					'baseurl' 		=> base_url(),
					'userinfo'		=> $this->user,
					'cntunit'		=> array(),
					'markets'		=> $this->mdmarkets->read_records(),
					'msgs'			=> $this->session->userdata('msgs'),
					'msgr'			=> $this->session->userdata('msgr')
			);
		$this->session->unset_userdata('msgs');
		$this->session->unset_userdata('msgr');
		
		if($this->input->post('amsubmit')):
			$_POST['amsubmit'] = NULL;
			$this->form_validation->set_rules('title',' ','required|trim');
			$this->form_validation->set_rules('url',' ','required|prep_url|trim');
			if(!$this->form_validation->run()):
				$this->session->set_userdata('msgr','Ошибка при сохранении. Не заполены необходимые поля.');
			else:
				if($_FILES['icon']['error'] != 4):
					$_POST['icon'] = file_get_contents($_FILES['icon']['tmp_name']);
				else:
					$_POST['icon'] = file_get_contents(base_url().'images/noimages/no_news.png');
				endif;
				$result = $this->mdmarkets->insert_record($_POST);
				if($result):
					$this->session->set_userdata('msgs','Биржа добавлена успешно');
				endif;
			endif;
			redirect($this->uri->uri_string());
		endif;
		
		if($this->input->post('emsubmit')):
			$_POST['emsubmit'] = NULL;
			$this->form_validation->set_rules('title',' ','required|trim');
			$this->form_validation->set_rules('url',' ','required|prep_url|trim');
			if(!$this->form_validation->run()):
				$this->session->set_userdata('msgr','Ошибка при сохранении. Не заполены необходимые поля.');
			else:
				if($_FILES['icon']['error'] != 4):
					$_POST['icon'] = file_get_contents($_FILES['icon']['tmp_name']);
				endif;
				$result = $this->mdmarkets->update_record($_POST);
				if($result):
					$this->session->set_userdata('msgs','Биржа изменена успешно');
				endif;
			endif;
			redirect($this->uri->uri_string());
		endif;
		$pagevar['cntunit']['users'] = $this->mdusers->count_all();
		$pagevar['cntunit']['platforms'] = $this->mdplatforms->count_all();
		$pagevar['cntunit']['markets'] = $this->mdmarkets->count_all();
		$pagevar['cntunit']['services'] = $this->mdservices->count_all();
		$pagevar['cntunit']['twork'] = $this->mdtypeswork->count_all();
		$pagevar['cntunit']['mails'] = $this->mdmessages->count_records_by_admin_new($this->user['uid']);
		$this->load->view("admin_interface/management-markets",$pagevar);
	}
	
	public function management_markets_deleting(){
		
		$mid = $this->uri->segment(5);
		if($mid):
			$result = $this->mdmarkets->delete_record($mid);
			if($result):
				$this->session->set_userdata('msgs','Биржа удалена успешно');
			else:
				$this->session->set_userdata('msgr','Биржа не удалена');
			endif;
			redirect($_SERVER['HTTP_REFERER']);
		else:
			show_404();
		endif;
	}
	
	/******************************************************** jobs ******************************************************/
	
	public function user_finished_jobs(){
		
		$from = intval($this->uri->segment(8));
		$pagevar = array(
					'description'	=> '',
					'author'		=> '',
					'title'			=> 'Администрирование | Выполненные задания для вебмастера',
					'baseurl' 		=> base_url(),
					'userinfo'		=> $this->user,
					'cntunit'		=> array(),
					'delivers'		=> $this->mdunion->delivers_works_webmaster($this->uri->segment(5),10,$from),
					'msgs'			=> $this->session->userdata('msgs'),
					'msgr'			=> $this->session->userdata('msgr')
			);
		$this->session->unset_userdata('msgs');
		$this->session->unset_userdata('msgr');
		
		$config['base_url'] 	= $pagevar['baseurl'].'admin-panel/management/users/userid/'.$this->uri->segment(5).'/finished-jobs/from/';
		$config['uri_segment'] 	= 8;
		$config['total_rows'] 	= $this->mdunion->count_delivers_works_webmaster($this->uri->segment(5));
		$config['per_page'] 	= 10;
		$config['num_links'] 	= 4;
		$config['first_link']	= 'В начало';
		$config['last_link'] 	= 'В конец';
		$config['next_link'] 	= 'Далее &raquo;';
		$config['prev_link'] 	= '&laquo; Назад';
		$config['cur_tag_open']	= '<span class="actpage">';
		$config['cur_tag_close'] = '</span>';
		
		$this->pagination->initialize($config);
		$pagevar['pages'] = $this->pagination->create_links();
		
		for($i=0;$i<count($pagevar['delivers']);$i++):
			$pagevar['delivers'][$i]['date'] = $this->operation_dot_date($pagevar['delivers'][$i]['date']);
			if(mb_strlen($pagevar['delivers'][$i]['ulrlink'],'UTF-8') > 15):
				$pagevar['delivers'][$i]['link'] = mb_substr($pagevar['delivers'][$i]['ulrlink'],0,15,'UTF-8');
				$pagevar['delivers'][$i]['link'] .= ' ... '.mb_substr($pagevar['delivers'][$i]['ulrlink'],strlen($pagevar['delivers'][$i]['ulrlink'])-10,10,'UTF-8');;
			else:
				$pagevar['delivers'][$i]['link'] = $pagevar['delivers'][$i]['ulrlink'];
			endif;
		endfor;
		$pagevar['cntunit']['users'] = $this->mdusers->count_all();
		$pagevar['cntunit']['platforms'] = $this->mdplatforms->count_all();
		$pagevar['cntunit']['markets'] = $this->mdmarkets->count_all();
		$pagevar['cntunit']['services'] = $this->mdservices->count_all();
		$pagevar['cntunit']['twork'] = $this->mdtypeswork->count_all();
		$pagevar['cntunit']['mails'] = $this->mdmessages->count_records_by_admin_new($this->user['uid']);
		$this->load->view("admin_interface/user-finished-jobs",$pagevar);
	}
	
	public function platform_finished_jobs(){
		
		$from = intval($this->uri->segment(8));
		$pagevar = array(
					'description'	=> '',
					'author'		=> '',
					'title'			=> 'Администрирование | Выполненные задания по площадке',
					'baseurl' 		=> base_url(),
					'userinfo'		=> $this->user,
					'cntunit'		=> array(),
					'delivers'		=> $this->mdunion->delivers_works_platform($this->uri->segment(5),10,$from),
					'msgs'			=> $this->session->userdata('msgs'),
					'msgr'			=> $this->session->userdata('msgr')
			);
		$this->session->unset_userdata('msgs');
		$this->session->unset_userdata('msgr');
		
		$config['base_url'] 	= $pagevar['baseurl'].'admin-panel/management/platforms/platformid/'.$this->uri->segment(5).'/finished-jobs/from/';
		$config['uri_segment'] 	= 8;
		$config['total_rows'] 	= $this->mdunion->count_delivers_works_platform($this->uri->segment(5));
		$config['per_page'] 	= 10;
		$config['num_links'] 	= 4;
		$config['first_link']	= 'В начало';
		$config['last_link'] 	= 'В конец';
		$config['next_link'] 	= 'Далее &raquo;';
		$config['prev_link'] 	= '&laquo; Назад';
		$config['cur_tag_open']	= '<span class="actpage">';
		$config['cur_tag_close'] = '</span>';
		
		$this->pagination->initialize($config);
		$pagevar['pages'] = $this->pagination->create_links();
		
		for($i=0;$i<count($pagevar['delivers']);$i++):
			$pagevar['delivers'][$i]['date'] = $this->operation_dot_date($pagevar['delivers'][$i]['date']);
			if(mb_strlen($pagevar['delivers'][$i]['ulrlink'],'UTF-8') > 15):
				$pagevar['delivers'][$i]['link'] = mb_substr($pagevar['delivers'][$i]['ulrlink'],0,15,'UTF-8');
				$pagevar['delivers'][$i]['link'] .= ' ... '.mb_substr($pagevar['delivers'][$i]['ulrlink'],strlen($pagevar['delivers'][$i]['ulrlink'])-10,10,'UTF-8');;
			else:
				$pagevar['delivers'][$i]['link'] = $pagevar['delivers'][$i]['ulrlink'];
			endif;
		endfor;
		$pagevar['cntunit']['users'] = $this->mdusers->count_all();
		$pagevar['cntunit']['platforms'] = $this->mdplatforms->count_all();
		$pagevar['cntunit']['markets'] = $this->mdmarkets->count_all();
		$pagevar['cntunit']['services'] = $this->mdservices->count_all();
		$pagevar['cntunit']['twork'] = $this->mdtypeswork->count_all();
		$pagevar['cntunit']['mails'] = $this->mdmessages->count_records_by_admin_new($this->user['uid']);
		$this->load->view("admin_interface/platform-finished-jobs",$pagevar);
	}
	
	/******************************************************** works ******************************************************/
	
	public function management_types_work(){
		
		$pagevar = array(
					'description'	=> '',
					'author'		=> '',
					'title'			=> 'Администрирование | Типы работ',
					'baseurl' 		=> base_url(),
					'userinfo'		=> $this->user,
					'cntunit'		=> array(),
					'tpswork'		=> $this->mdtypeswork->read_records(),
					'msgs'			=> $this->session->userdata('msgs'),
					'msgr'			=> $this->session->userdata('msgr')
			);
		$this->session->unset_userdata('msgs');
		$this->session->unset_userdata('msgr');
		
		if($this->input->post('amsubmit')):
			$_POST['amsubmit'] = NULL;
			$this->form_validation->set_rules('title',' ','required|trim');
			$this->form_validation->set_rules('wprice',' ','required|trim');
			$this->form_validation->set_rules('mprice',' ','required|trim');
			if(!$this->form_validation->run()):
				$this->session->set_userdata('msgr','Ошибка при сохранении. Не заполены необходимые поля.');
			else:
				$result = $this->mdtypeswork->insert_record($_POST);
				if($result):
					$this->session->set_userdata('msgs','Тип работ добавлен успешно');
				endif;
			endif;
			redirect($this->uri->uri_string());
		endif;
		
		if($this->input->post('emsubmit')):
			$_POST['emsubmit'] = NULL;
			$this->form_validation->set_rules('title',' ','required|trim');
			$this->form_validation->set_rules('wprice',' ','required|trim');
			$this->form_validation->set_rules('mprice',' ','required|trim');
			if(!$this->form_validation->run()):
				$this->session->set_userdata('msgr','Ошибка при сохранении. Не заполены необходимые поля.');
			else:
				$result = $this->mdtypeswork->update_record($_POST);
				if($result):
					$this->session->set_userdata('msgs','Тип работ изменен успешно');
				endif;
			endif;
			redirect($this->uri->uri_string());
		endif;
		$pagevar['cntunit']['users'] = $this->mdusers->count_all();
		$pagevar['cntunit']['platforms'] = $this->mdplatforms->count_all();
		$pagevar['cntunit']['markets'] = $this->mdmarkets->count_all();
		$pagevar['cntunit']['services'] = $this->mdservices->count_all();
		$pagevar['cntunit']['twork'] = $this->mdtypeswork->count_all();
		$pagevar['cntunit']['mails'] = $this->mdmessages->count_records_by_admin_new($this->user['uid']);
		$this->load->view("admin_interface/management-types-work",$pagevar);
	}
	
	public function management_types_work_deleting(){
		
		$wid = $this->uri->segment(5);
		if($wid):
			$result = $this->mdtypeswork->delete_record($wid);
			if($result):
				$this->session->set_userdata('msgs','Тип работ удален успешно');
			else:
				$this->session->set_userdata('msgr','Тип работ не удален');
			endif;
			redirect($_SERVER['HTTP_REFERER']);
		else:
			show_404();
		endif;
	}
	
	/******************************************************** ratings ******************************************************/
	
	public function management_ratings(){
		
		switch($this->uri->segment(4)):
			case 'advertisers' 	: $rtype = 1; break;
			case 'webmasters' 	: $rtype = 2; break;
			default 			: redirect('/');
		endswitch;
		
		$pagevar = array(
					'description'	=> '',
					'author'		=> '',
					'title'			=> 'Администрирование | Отзывы о системе',
					'baseurl' 		=> base_url(),
					'userinfo'		=> $this->user,
					'cntunit'		=> array(),
					'ratings'		=> $this->mdratings->read_records($rtype),
					'msgs'			=> $this->session->userdata('msgs'),
					'msgr'			=> $this->session->userdata('msgr')
			);
		$this->session->unset_userdata('msgs');
		$this->session->unset_userdata('msgr');
		
		if($this->input->post('arsubmit')):
			$_POST['arsubmit'] = NULL;
			$this->form_validation->set_rules('title',' ','required|trim');
			$this->form_validation->set_rules('text',' ','required|trim');
			$this->form_validation->set_rules('resource',' ','prep_url|trim');
			if(!$this->form_validation->run()):
				$this->session->set_userdata('msgr','Ошибка при сохранении. Не заполены необходимые поля.');
			else:
				if($_FILES['avatar']['error'] != 4):
					$_POST['avatar'] = file_get_contents($_FILES['avatar']['tmp_name']);
				else:
					$_POST['avatar'] = file_get_contents(base_url().'images/no-avatar.gif');
				endif;
				$result = $this->mdratings->insert_record($_POST,$rtype);
				if($result):
					$this->session->set_userdata('msgs','Отзыв добавлен успешно');
				endif;
			endif;
			redirect($this->uri->uri_string());
		endif;
		$pagevar['cntunit']['users'] = $this->mdusers->count_all();
		$pagevar['cntunit']['platforms'] = $this->mdplatforms->count_all();
		$pagevar['cntunit']['markets'] = $this->mdmarkets->count_all();
		$pagevar['cntunit']['services'] = $this->mdservices->count_all();
		$pagevar['cntunit']['twork'] = $this->mdtypeswork->count_all();
		$pagevar['cntunit']['mails'] = $this->mdmessages->count_records_by_admin_new($this->user['uid']);
		$this->load->view("admin_interface/management-ratings",$pagevar);
	}
	
	public function management_rating_deleting(){
		
		$rid = $this->uri->segment(5);
		if($rid):
			$result = $this->mdratings->delete_record($rid);
			if($result):
				$this->session->set_userdata('msgs','Отзыв удален успешно');
			else:
				$this->session->set_userdata('msgr','Отзыв не удален');
			endif;
			redirect($_SERVER['HTTP_REFERER']);
		else:
			show_404();
		endif;
	}
	
	/******************************************************** other ******************************************************/
	
	public function actions_forum(){
		
		$pagevar = array(
					'description'	=> '',
					'author'		=> '',
					'title'			=> 'Администрирование | Форум',
					'baseurl' 		=> base_url(),
					'userinfo'		=> $this->user,
					'cntunit'		=> array(),
					'msgs'			=> $this->session->userdata('msgs'),
					'msgr'			=> $this->session->userdata('msgr')
			);
		$this->session->unset_userdata('msgs');
		$this->session->unset_userdata('msgr');
		$pagevar['cntunit']['users'] = $this->mdusers->count_all();
		$pagevar['cntunit']['platforms'] = $this->mdplatforms->count_all();
		$pagevar['cntunit']['markets'] = $this->mdmarkets->count_all();
		$pagevar['cntunit']['services'] = $this->mdservices->count_all();
		$pagevar['cntunit']['twork'] = $this->mdtypeswork->count_all();
		$pagevar['cntunit']['mails'] = $this->mdmessages->count_records_by_admin_new($this->user['uid']);
		$this->load->view("admin_interface/actions-forum",$pagevar);
	}
	
	public function actions_balance(){
		
		$pagevar = array(
					'description'	=> '',
					'author'		=> '',
					'title'			=> 'Администрирование | Баланс',
					'baseurl' 		=> base_url(),
					'userinfo'		=> $this->user,
					'cntunit'		=> array(),
					'income'		=> array(),
					'msgs'			=> $this->session->userdata('msgs'),
					'msgr'			=> $this->session->userdata('msgr')
			);
		$this->session->unset_userdata('msgs');
		$this->session->unset_userdata('msgr');
		
		$pagevar['income']['total'] = $this->mddelivesworks->calc_summ('wprice','2012-01-01',1);
		$pagevar['income']['ten'] = $this->mddelivesworks->calc_summ('wprice',date("Y-m-d",mktime(0,0,0,date("m"),date("d")-10,date("Y"))),1);
		$pagevar['income']['managers'] = $this->mddelivesworks->calc_summ('wprice-mprice','2012-01-01',1);
		$pagevar['income']['debt'] = $this->mddelivesworks->calc_summ('wprice','2012-01-01',0);
		
		$pagevar['cntunit']['users'] = $this->mdusers->count_all();
		$pagevar['cntunit']['platforms'] = $this->mdplatforms->count_all();
		$pagevar['cntunit']['markets'] = $this->mdmarkets->count_all();
		$pagevar['cntunit']['services'] = $this->mdservices->count_all();
		$pagevar['cntunit']['twork'] = $this->mdtypeswork->count_all();
		$pagevar['cntunit']['mails'] = $this->mdmessages->count_records_by_admin_new($this->user['uid']);
		$this->load->view("admin_interface/actions-balance",$pagevar);
	}
	
	public function messages_system(){
		
		$from = intval($this->uri->segment(5));
		
		$pagevar = array(
					'description'	=> '',
					'author'		=> '',
					'title'			=> 'Администрирование | Отправка системного сообщения сообщения',
					'baseurl' 		=> base_url(),
					'userinfo'		=> $this->user,
					'cntunit'		=> array(),
					'msgs'			=> $this->session->userdata('msgs'),
					'msgr'			=> $this->session->userdata('msgr')
			);
		$this->session->unset_userdata('msgs');
		$this->session->unset_userdata('msgr');
		
		if($this->input->post('submit')):
			$_POST['submit'] = NULL;
			$this->form_validation->set_rules('group',' ','required|trim');
			$this->form_validation->set_rules('type',' ','required|trim');
			$this->form_validation->set_rules('text',' ','required|trim');
			if(!$this->form_validation->run()):
				$this->session->set_userdata('msgr','Ошибка при сохранении. Не заполены необходимые поля.');
			else:
				$id = $this->mdmessages->send_system_message($this->user['uid'],$_POST);
				if($id):
					$this->session->set_userdata('msgs','Сообщение отправлено');
				endif;
				if(isset($_POST['sendmail'])):
					
				endif;
			endif;
			redirect($this->uri->uri_string());
		endif;
		$pagevar['cntunit']['users'] = $this->mdusers->count_all();
		$pagevar['cntunit']['platforms'] = $this->mdplatforms->count_all();
		$pagevar['cntunit']['markets'] = $this->mdmarkets->count_all();
		$pagevar['cntunit']['services'] = $this->mdservices->count_all();
		$pagevar['cntunit']['twork'] = $this->mdtypeswork->count_all();
		$pagevar['cntunit']['mails'] = $this->mdmessages->count_records_by_admin_new($this->user['uid']);
		$this->load->view("admin_interface/messages-system",$pagevar);
	}
	
	public function management_mails(){
		
		$from = intval($this->uri->segment(5));
		$pagevar = array(
					'description'	=> '',
					'author'		=> '',
					'title'			=> 'Администрирование | Личные сообщения',
					'baseurl' 		=> base_url(),
					'userinfo'		=> $this->user,
					'mails'			=> $this->mdunion->read_mails_admin_pages($this->user['uid'],5,$from),
					'count'			=> $this->mdunion->count_mails_admin_pages($this->user['uid']),
					'pages'			=> array(),
					'cntunit'		=> array('mails'=>0),
					'msgs'			=> $this->session->userdata('msgs'),
					'msgr'			=> $this->session->userdata('msgr')
			);
		$this->session->unset_userdata('msgs');
		$this->session->unset_userdata('msgr');
		if($this->input->post('mtsubmit')):
			$_POST['mtsubmit'] = NULL;
			$this->form_validation->set_rules('recipient',' ','required|trim');
			$this->form_validation->set_rules('text',' ','required|trim');
			if(!$this->form_validation->run()):
				$this->session->set_userdata('msgr','Ошибка при сохранении. Не заполены необходимые поля.');
			else:
				$id = $this->mdmessages->insert_record($this->user['uid'],$_POST['recipient'],$_POST['text']);
				if($id):
					if($this->mdusers->read_field($_POST['recipient'],'sendmail')):
						ob_start();
						?>
						<p><strong>Здравствуйте, <?=$this->mdusers->read_field($_POST['recipient'],'fio');?></strong></p>
						<p>У Вас новое сообщение</p>
						<p>Что бы прочитать его вводите в личный кабинет и перейдите в раздел Почта</p>
						<p>Желаем Вам удачи!</p> 
						<?
						$mailtext = ob_get_clean();
						
						$this->email->clear(TRUE);
						$config['smtp_host'] = 'localhost';
						$config['charset'] = 'utf-8';
						$config['wordwrap'] = TRUE;
						$config['mailtype'] = 'html';
						
						$this->email->initialize($config);
						$this->email->to($this->mdusers->read_field($_POST['recipient'],'login'));
						$this->email->from('admin@bystropost.ru','Bystropost.ru - Система управления продажами');
						$this->email->bcc('');
						$this->email->subject('Noreply: Bystropost.ru - Почта. Новое сообщение');
						$this->email->message($mailtext);
						$this->email->send();
					endif;
					$this->session->set_userdata('msgs','Сообщение отправлено');
				endif;
				if(isset($_POST['sendmail'])):
					
				endif;
			endif;
			redirect($this->uri->uri_string());
		endif;
		for($i=0;$i<count($pagevar['mails']);$i++):
			$pagevar['mails'][$i]['date'] = $this->operation_dot_date($pagevar['mails'][$i]['date']);
		endfor;
		$config['base_url'] 	= $pagevar['baseurl'].'admin-panel/management/mails/from/';
		$config['uri_segment'] 	= 5;
		$config['total_rows'] 	= $pagevar['count'];
		$config['per_page'] 	= 5;
		$config['num_links'] 	= 4;
		$config['first_link']	= 'В начало';
		$config['last_link'] 	= 'В конец';
		$config['next_link'] 	= 'Далее &raquo;';
		$config['prev_link'] 	= '&laquo; Назад';
		$config['cur_tag_open']	= '<span class="actpage">';
		$config['cur_tag_close'] = '</span>';
		
		$this->pagination->initialize($config);
		$pagevar['pages'] = $this->pagination->create_links();
		$this->mdmessages->set_read_mails_by_admin($this->user['uid']);
		$pagevar['cntunit']['users'] = $this->mdusers->count_all();
		$pagevar['cntunit']['platforms'] = $this->mdplatforms->count_all();
		$pagevar['cntunit']['markets'] = $this->mdmarkets->count_all();
		$pagevar['cntunit']['services'] = $this->mdservices->count_all();
		$pagevar['cntunit']['twork'] = $this->mdtypeswork->count_all();
		$this->load->view("admin_interface/messages-private",$pagevar);
	}
	
	public function messages_private_delete(){
		
		$mid = $this->uri->segment(6);
		if($mid):
			$result = $this->mdmessages->delete_record($mid);
			if($result):
				$this->session->set_userdata('msgs','Сообшение удалено успешно');
			else:
				$this->session->set_userdata('msgr','Сообшение не удалено');
			endif;
			redirect($_SERVER['HTTP_REFERER']);
		else:
			show_404();
		endif;
	}
	
	public function messages_tickets(){
		
		$from = intval($this->uri->segment(5));
		$pagevar = array(
					'description'	=> '',
					'author'		=> '',
					'title'			=> 'Администрирование | Тикеты',
					'baseurl' 		=> base_url(),
					'userinfo'		=> $this->user,
					'tickets'		=> $this->mdunion->read_all_tickets(10,$from),
					'count'			=> $this->mdunion->count_all_tickets(),
					'pages'			=> array(),
					'cntunit'		=> array(),
					'msgs'			=> $this->session->userdata('msgs'),
					'msgr'			=> $this->session->userdata('msgr')
			);
		$this->session->unset_userdata('msgs');
		$this->session->unset_userdata('msgr');
		
		for($i=0;$i<count($pagevar['tickets']);$i++):
			$pagevar['tickets'][$i]['date'] = $this->operation_date($pagevar['tickets'][$i]['date']);
			if($pagevar['tickets'][$i]['recipient']):
				$pagevar['tickets'][$i]['user'] = $this->mdusers->read_field($pagevar['tickets'][$i]['recipient'],'fio');
				$pagevar['tickets'][$i]['email'] = $this->mdusers->read_field($pagevar['tickets'][$i]['recipient'],'login');
			else:
				$pagevar['tickets'][$i]['user'] = '<em><strong><font style="color:#ff0000;">Администратору</font></strong></em>';
				$pagevar['tickets'][$i]['email'] = '';
			endif;
		endfor;
		
		$config['base_url'] 	= $pagevar['baseurl'].'admin-panel/messages/tickets/from/';
		$config['uri_segment'] 	= 5;
		$config['total_rows'] 	= $pagevar['count'];
		$config['per_page'] 	= 5;
		$config['num_links'] 	= 4;
		$config['first_link']	= 'В начало';
		$config['last_link'] 	= 'В конец';
		$config['next_link'] 	= 'Далее &raquo;';
		$config['prev_link'] 	= '&laquo; Назад';
		$config['cur_tag_open']	= '<span class="actpage">';
		$config['cur_tag_close'] = '</span>';
		
		$this->pagination->initialize($config);
		$pagevar['pages'] = $this->pagination->create_links();
		$pagevar['cntunit']['users'] = $this->mdusers->count_all();
		$pagevar['cntunit']['platforms'] = $this->mdplatforms->count_all();
		$pagevar['cntunit']['markets'] = $this->mdmarkets->count_all();
		$pagevar['cntunit']['services'] = $this->mdservices->count_all();
		$pagevar['cntunit']['twork'] = $this->mdtypeswork->count_all();
		$pagevar['cntunit']['mails'] = $this->mdmessages->count_records_by_admin_new($this->user['uid']);
		$this->load->view("admin_interface/messages-tickets",$pagevar);
	}
	
	public function messages_view_ticket(){
		
		$ticket = $this->uri->segment(6);
		$from = intval($this->uri->segment(8));
		$pagevar = array(
					'description'	=> '',
					'author'		=> '',
					'title'			=> 'Администрирование | Тикеты | Просмотр',
					'baseurl' 		=> base_url(),
					'loginstatus'	=> $this->loginstatus['status'],
					'userinfo'		=> $this->user,
					'ticket'		=> $this->mdunion->view_ticket_info($ticket),
					'tkmsgs'		=> $this->mdunion->read_messages_by_ticket_pages($ticket,5,$from),
					'count'			=> $this->mdunion->count_messages_by_ticket($ticket),
					'pages'			=> array(),
					'cntunit'		=> array(),
					'msgs'			=> $this->session->userdata('msgs'),
					'msgr'			=> $this->session->userdata('msgr')
			);
		$this->session->unset_userdata('msgs');
		$this->session->unset_userdata('msgr');
		if($this->input->post('mtsubmit')):
			$_POST['mtsubmit'] = NULL;
			$this->form_validation->set_rules('mid',' ','required|trim');
			$this->form_validation->set_rules('recipient',' ','required|trim');
			$this->form_validation->set_rules('text',' ','required|trim');
			if(!$this->form_validation->run()):
				$this->session->set_userdata('msgr','Ошибка при сохранении. Не заполены необходимые поля.');
			else:
				$id = $this->mdtkmsgs->insert_record($_POST['recipient'],$ticket,$this->user['uid'],$_POST['recipient'],$_POST['mid'],$_POST['text']);
				if($id):
					$this->session->set_userdata('msgs','Сообщение отправлено');
				endif;
				if(isset($_POST['sendmail'])):
					ob_start();
					?>
					<p><strong>Здравствуйте, <?=$this->mdusers->read_field($_POST['recipient'],'fio');?></strong></p>
					<p>Получен ответ на Ваше сообщение. в тикет-системе.</p>
					<p>Что бы прочитать его вводите в личный кабинет и перейдите в раздел тикеты</p>
					<p>Желаем Вам удачи!</p> 
					<?
					$mailtext = ob_get_clean();
					
					$this->email->clear(TRUE);
					$config['smtp_host'] = 'localhost';
					$config['charset'] = 'utf-8';
					$config['wordwrap'] = TRUE;
					$config['mailtype'] = 'html';
					
					$this->email->initialize($config);
					$this->email->to($this->mdusers->read_field($_POST['recipient'],'login'));
					$this->email->from('admin@bystropost.ru','Bystropost.ru - Система управления продажами');
					$this->email->bcc('');
					$this->email->subject('Noreply: Bystropost.ru - Тикеты. Новое сообщение');
					$this->email->message($mailtext);	
					$this->email->send();
				endif;
			endif;
			redirect($this->uri->uri_string());
		endif;
		
		$config['base_url'] 	= $pagevar['baseurl'].'admin-panel/messages/tickets/view-ticket/ticket-id/'.$this->uri->segment(6).'/from/';
		$config['uri_segment'] 	= 8;
		$config['total_rows'] 	= $pagevar['count'];
		$config['per_page'] 	= 5;
		$config['num_links'] 	= 4;
		$config['first_link']	= 'В начало';
		$config['last_link'] 	= 'В конец';
		$config['next_link'] 	= 'Далее &raquo;';
		$config['prev_link'] 	= '&laquo; Назад';
		$config['cur_tag_open']	= '<span class="actpage">';
		$config['cur_tag_close'] = '</span>';
		
		$this->pagination->initialize($config);
		$pagevar['pages'] = $this->pagination->create_links();
		for($i=0;$i<count($pagevar['tkmsgs']);$i++):
			$pagevar['tkmsgs'][$i]['date'] = $this->operation_date($pagevar['tkmsgs'][$i]['date']);
		endfor;
		$pagevar['cntunit']['users'] = $this->mdusers->count_all();
		$pagevar['cntunit']['platforms'] = $this->mdplatforms->count_all();
		$pagevar['cntunit']['markets'] = $this->mdmarkets->count_all();
		$pagevar['cntunit']['services'] = $this->mdservices->count_all();
		$pagevar['cntunit']['twork'] = $this->mdtypeswork->count_all();
		$pagevar['cntunit']['mails'] = $this->mdmessages->count_records_by_admin_new($this->user['uid']);
		$this->load->view("admin_interface/messages-view-tickets",$pagevar);
	}

	public function control_delete_msg_ticket(){
		
		$message = $this->uri->segment(6);
		if($message):
			$result = $this->mdtkmsgs->delete_record($message);
			if($result):
				$this->session->set_userdata('msgs','Сообшение удалено успешно');
			else:
				$this->session->set_userdata('msgr','Сообшение не удалено');
			endif;
			redirect($_SERVER['HTTP_REFERER']);
		else:
			show_404();
		endif;
	}
	
	public function actions_logoff(){
		
		$this->session->sess_destroy();
		redirect('');
	}
	
	public function reading_users_messages(){
		
		$from = intval($this->uri->segment(8));
		$user = intval($this->uri->segment(6));
		$pagevar = array(
					'description'	=> '',
					'author'		=> '',
					'title'			=> 'Администрирование | Просмотр сообщений',
					'baseurl' 		=> base_url(),
					'userinfo'		=> $this->user,
					'owner'			=> $this->mdusers->read_small_info($user),
					'mails'			=> $this->mdmessages->read_mails_user_pages($user,10,$from),
					'count'			=> $this->mdmessages->count_mails_user_pages($user),
					'pages'			=> array(),
					'cntunit'		=> array('mails'=>0),
					'msgs'			=> $this->session->userdata('msgs'),
					'msgr'			=> $this->session->userdata('msgr')
			);
		$this->session->unset_userdata('msgs');
		$this->session->unset_userdata('msgr');
		for($i=0;$i<count($pagevar['mails']);$i++):
			$pagevar['mails'][$i]['date'] = $this->operation_dot_date($pagevar['mails'][$i]['date']);
		endfor;
		
		$config['base_url'] 	= $pagevar['baseurl'].'admin-panel/management/users/read-messages/userid/'.$user.'/from/';
		$config['uri_segment'] 	= 8;
		$config['total_rows'] 	= $pagevar['count'];
		$config['per_page'] 	= 10;
		$config['num_links'] 	= 4;
		$config['first_link']	= 'В начало';
		$config['last_link'] 	= 'В конец';
		$config['next_link'] 	= 'Далее &raquo;';
		$config['prev_link'] 	= '&laquo; Назад';
		$config['cur_tag_open']	= '<span class="actpage">';
		$config['cur_tag_close'] = '</span>';
		
		$this->pagination->initialize($config);
		$pagevar['pages'] = $this->pagination->create_links();
		$this->mdmessages->set_read_mails_by_admin($this->user['uid']);
		
		$pagevar['cntunit']['users'] = $this->mdusers->count_all();
		$pagevar['cntunit']['platforms'] = $this->mdplatforms->count_all();
		$pagevar['cntunit']['markets'] = $this->mdmarkets->count_all();
		$pagevar['cntunit']['services'] = $this->mdservices->count_all();
		$pagevar['cntunit']['twork'] = $this->mdtypeswork->count_all();
		$pagevar['cntunit']['mails'] = $this->mdmessages->count_records_by_admin_new($this->user['uid']);
		
		$this->load->view("admin_interface/reading-users-messages",$pagevar);
	}
	
	public function calculate_tic(){
		
		$platforms = $this->mdplatforms->read_urls();
		for($i=0;$i<count($platforms);$i++):
			$oldtic = $this->mdplatforms->read_field($platforms[$i]['id'],'tic');
			$tic = $this->getTIC('http://'.$platforms[$i]['url']);
			$this->mdplatforms->update_field($platforms[$i]['id'],'tic',$tic);
			if($oldtic != $tic):
				$addwtic = 5; $addmtic = 2;
				if($oldtic < 30 AND $tic >= 30):
					$sqlquery = "UPDATE platforms SET ccontext=ccontext+$addwtic, mcontext=mcontext+$addmtic,cnotice=cnotice+$addwtic,mnotice=mnotice+$addmtic,clinkpic=clinkpic+$addwtic,mlinkpic=mlinkpic+$addmtic,cpressrel=cpressrel+$addwtic,mpressrel=mpressrel+$addmtic,clinkarh=clinkarh+$addwtic,mlinkarh=mlinkarh+$addmtic WHERE platforms.id = ".$platforms[$i]['id'];
					$this->mdplatforms->run_query($sqlquery);
				elseif($oldtic >= 30 AND $tic < 30):
					$sqlquery = "UPDATE platforms SET ccontext=ccontext-$addwtic, mcontext=mcontext-$addmtic,cnotice=cnotice-$addwtic,mnotice=mnotice-$addmtic,clinkpic=clinkpic-$addwtic,mlinkpic=mlinkpic-$addmtic,cpressrel=cpressrel-$addwtic,mpressrel=mpressrel-$addmtic,clinkarh=clinkarh-$addwtic,mlinkarh=mlinkarh-$addmtic WHERE platforms.id = ".$platforms[$i]['id'];
					$this->mdplatforms->run_query($sqlquery);
				endif;
			endif;
		endfor;
		$this->session->set_userdata('msgs','Яндекс тИЦ успешно вычислен');
		redirect('admin-panel/management/platforms');
	}
	
	public function calculate_pr(){
	
		$platforms = $this->mdplatforms->read_urls();
		for($i=0;$i<count($platforms);$i++):
			$this->mdplatforms->update_field($platforms[$i]['id'],'pr',$this->getpagerank($platforms[$i]['url']));
		endfor;
		$this->session->set_userdata('msgs','Google PageRank успешно вычислен');
		redirect('admin-panel/management/platforms');
	}

	public function management_services(){
		
		$pagevar = array(
					'description'	=> '',
					'author'		=> '',
					'title'			=> 'Администрирование | Дополнительные услуги',
					'baseurl' 		=> base_url(),
					'userinfo'		=> $this->user,
					'cntunit'		=> array(),
					'services'		=> $this->mdservices->read_records(),
					'valuesrv'		=> $this->mdvaluesrv->read_records(),
					'msgs'			=> $this->session->userdata('msgs'),
					'msgr'			=> $this->session->userdata('msgr')
			);
		$this->session->unset_userdata('msgs');
		$this->session->unset_userdata('msgr');
		if($this->input->post('assubmit')):
			$_POST['amsubmit'] = NULL;
			$this->form_validation->set_rules('title',' ','required|trim');
			if(!$this->form_validation->run()):
				$this->session->set_userdata('msgr','Ошибка при сохранении. Не заполены необходимые поля.');
			else:
				$result = $this->mdservices->insert_record($_POST);
				if($result):
					$this->session->set_userdata('msgs','Услуга добавлена успешно');
				endif;
			endif;
			redirect($this->uri->uri_string());
		endif;
		if($this->input->post('asvsubmit')):
			$_POST['asvsubmit'] = NULL;
			$this->form_validation->set_rules('title',' ','required|trim');
			$this->form_validation->set_rules('sid',' ','required|trim');
			$this->form_validation->set_rules('wprice',' ','required|trim');
			$this->form_validation->set_rules('mprice',' ','required|trim');
			if(!$this->form_validation->run()):
				$this->session->set_userdata('msgr','Ошибка при сохранении. Не заполены необходимые поля.');
			else:
				$result = $this->mdvaluesrv->insert_record($_POST);
				if($result):
					$this->session->set_userdata('msgs','Значение услуги добавлено успешно');
				endif;
			endif;
			redirect($this->uri->uri_string());
		endif;
		
		if($this->input->post('essubmit')):
			$_POST['essubmit'] = NULL;
			$this->form_validation->set_rules('sid',' ','required|trim');
			$this->form_validation->set_rules('title',' ','required|trim');
			if(!$this->form_validation->run()):
				$this->session->set_userdata('msgr','Ошибка при сохранении. Не заполены необходимые поля.');
			else:
				$result = $this->mdservices->update_record($_POST);
				if($result):
					$this->session->set_userdata('msgs','Услуга изменена успешно');
				endif;
			endif;
			redirect($this->uri->uri_string());
		endif;
		if($this->input->post('esvsubmit')):
			$_POST['esvsubmit'] = NULL;
			$this->form_validation->set_rules('svid',' ','required|trim');
			$this->form_validation->set_rules('title',' ','required|trim');
			$this->form_validation->set_rules('wprice',' ','required|numeric|trim');
			$this->form_validation->set_rules('mprice',' ','required|numeric|trim');
			if(!$this->form_validation->run()):
				$this->session->set_userdata('msgr','Ошибка при сохранении. Не заполены необходимые поля.');
			else:
				if(!isset($_POST['delsrvvalue'])):
					$result = $this->mdvaluesrv->update_record($_POST);
					if($result):
						$this->session->set_userdata('msgs','Значение услуги сохранено успешно');
					endif;
				else:
					$result = $this->mdvaluesrv->delete_record($_POST['svid']);
					if($result):
						$this->session->set_userdata('msgs','Значение услуги удалено успешно');
					endif;
				endif;
			endif;
			redirect($this->uri->uri_string());
		endif;
		$pagevar['cntunit']['users'] = $this->mdusers->count_all();
		$pagevar['cntunit']['platforms'] = $this->mdplatforms->count_all();
		$pagevar['cntunit']['markets'] = $this->mdmarkets->count_all();
		$pagevar['cntunit']['services'] = $this->mdservices->count_all();
		$pagevar['cntunit']['twork'] = $this->mdtypeswork->count_all();
		$pagevar['cntunit']['mails'] = $this->mdmessages->count_records_by_admin_new($this->user['uid']);
		$this->load->view("admin_interface/management-services",$pagevar);
	}
	
	public function management_services_deleting(){
		
		$sid = $this->uri->segment(5);
		if($sid):
			$attched = $this->mdattachedservices->service_admin_exist($sid);
			if(!$attched):
				$result = $this->mdservices->delete_record($sid);
				if($result):
					$this->mdvaluesrv->delete_records($sid);
					$this->session->set_userdata('msgs','Услуга удалена успешно');
				else:
					$this->session->set_userdata('msgr','Услуга не удалена');
				endif;
			else:
				$this->session->set_userdata('msgr','Услуга используется. Удалить не возможно.');
			endif;
			redirect('admin-panel/management/services');
		else:
			show_404();
		endif;
	}
	
	/******************************************************** API ******************************************************/
	
	function actions_api(){
		$mass_data = array();
		/*======================== Загрузка вебмастеров ============================*/
//		$post = array('hash'=>'fe162efb2429ef9e83e42e43f8195148','action'=>'GetAllUser','param'=>'');
	/*======================== Загрузка аккаунтов на биржах ========================*/
//		$post = array('hash'=>'fe162efb2429ef9e83e42e43f8195148','action'=>'GetAccount','param'=>'');
		$post = array('hash'=>'fe162efb2429ef9e83e42e43f8195148','action'=>'GetSitesFromAccount','param'=>'birzid=5&accid=418');
		
		$ch = curl_init();
		curl_setopt($ch,CURLOPT_URL,'http://megaopen.ru/api.php');
		curl_setopt($ch,CURLOPT_POST,1);
		curl_setopt($ch,CURLOPT_POSTFIELDS,$post);
		curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);
		curl_setopt($ch,CURLOPT_TIMEOUT,30);
		$data = curl_exec($ch);
		curl_close($ch);
		 if($data!==false):
			$res = json_decode($data, true);
			if((int)$res['status']==1):
				$mass_data = $res['data'];
			else:
				print_r($res['error']);
			endif;
		else:
			print_r('Нет данных для загрузки!');
		endif;
		print_r($mass_data);
		echo '<br/>'.count($mass_data);
	/*======================== Загрузка вебмастеров начало ============================ */
		/*$data = array(); $cnt = 0;
		foreach($mass_data AS $key => $value):
			if($key):
				if($mass_data[$key]['email'] == 'Sacred3'):
					continue;
				endif;
				switch($mass_data[$key]['email']):
					case 'Sacred3' :continue; break;
					case 'chistyakoveo' :$mass_data[$key]['email'] = 'chistyakoveo@yandex.ru'; break;
					case 'dokmaster' :$mass_data[$key]['email'] = 'lingon@inbox.ru'; break;
					case 'Dolgoff' :$mass_data[$key]['email'] = 'ilya.dolgoff@gmail.com'; break;
					case 'Dolgoff' :$mass_data[$key]['email'] = 'ilya.dolgoff@gmail.com'; break;
					case 'TigerV' :$mass_data[$key]['email'] = 'wwwwizard@mail.ru'; break;
				endswitch;
				$data['login'] = $mass_data[$key]['email'];
				$data['password'] = $this->randomPassword(8);
				$data['fio'] = 'Имя не указанно';
				$data['wmid'] = '';
				$data['knowus'] = 'Загружен через API';
				$data['sendmail'] = 1;
				print_r('Обработка: '.$key.' Email: '.$data['login']);
				if(!$this->mdusers->user_exist('login ',$data['login'])):
					print_r(' Статус: Не уществует!');
					$uid = $this->mdusers->insert_record($data,1);
					if($uid):
						$this->mdusers->update_field($uid,'manager',2);
						$this->mdusers->update_field($uid,'remoteid',$key);
						$cnt++;
						print_r(' Добавлен. ID = '.$uid.'<br/>');
					else:
						print_r(' Не добавлен.<br/>');
					endif;
				else:
					print_r(' Статус: Существует! Не добавлен.<br/>');
				endif;
			endif;
		endforeach;
		print_r('Импортировнно: '.$cnt.' вебмастеров');*/
		/*=============================== Загрузка вебмастеров конец ============================*/
		/*======================== Загрузка аккаунтов на биржах начало ======================== */
		/*$data = array(); $cnt = 0;
		foreach($mass_data AS $key => $value):
			if($key):
				$data['id'] = $key;
				$data['market'] = $mass_data[$key]['bizhid'];
				$data['login'] = $mass_data[$key]['login'];
				$data['password'] = $mass_data[$key]['pass'];
				$data['webmaster'] = $mass_data[$key]['userid'];
				if($data['webmaster'] && $data['market']):
					$this->mdwebmarkets->insert_record($data['id'],$data['webmaster'],$data);
					$cnt++;
				endif;
			endif;
		endforeach;
		print_r('Импортировнно: '.$cnt.' аккаунтов');*/
		/*======================== Загрузка аккаунтов на биржах конец ========================*/
	}
	
	private function API($action,$param){
	
		$post = array('hash'=>'fe162efb2429ef9e83e42e43f8195148','action'=>$action,'param'=>$param);
		$ch = curl_init();
		curl_setopt($ch,CURLOPT_URL,'http://megaopen.ru/api.php');
		curl_setopt($ch,CURLOPT_POST,1);
		curl_setopt($ch,CURLOPT_POSTFIELDS,$post);
		curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);
		curl_setopt($ch,CURLOPT_TIMEOUT,30);
		$data = curl_exec($ch);
		curl_close($ch);
		 if($data!==false):
			$res = json_decode($data, true);
			if((int)$res['status']==1):
				return $res['data'];
			else:
				return FALSE;
			endif;
		else:
			return FALSE;
		endif;
	}
	
	/******************************************************** statistic ******************************************************/	
	
	public function actions_statistic(){
		
		$pagevar = array(
					'description'	=> '',
					'author'		=> '',
					'title'			=> 'Администрирование | Статистика',
					'baseurl' 		=> base_url(),
					'userinfo'		=> $this->user,
					'cntunit'		=> array(),
					'stat'			=> array(),
					'msgs'			=> $this->session->userdata('msgs'),
					'msgr'			=> $this->session->userdata('msgr')
			);
		$this->session->unset_userdata('msgs');
		$this->session->unset_userdata('msgr');
		
		$pagevar['stat']['to3days'] = $this->mddelivesworks->calc_debet('wprice',date("Y-m-d",mktime(0,0,0,date("m"),date("d")-3,date("Y"))),'=');
		$pagevar['stat']['to4days'] = $this->mddelivesworks->calc_debet('wprice',date("Y-m-d",mktime(0,0,0,date("m"),date("d")-4,date("Y"))),'=');
		$pagevar['stat']['to5days'] = $this->mddelivesworks->calc_debet('wprice-mprice',date("Y-m-d",mktime(0,0,0,date("m"),date("d")-5,date("Y"))),'=');
		$pagevar['stat']['from5days'] = $this->mddelivesworks->calc_debet('wprice',date("Y-m-d",mktime(0,0,0,date("m"),date("d")-5,date("Y"))),"<");
		
		$pagevar['cntunit']['users'] = $this->mdusers->count_all();
		$pagevar['cntunit']['platforms'] = $this->mdplatforms->count_all();
		$pagevar['cntunit']['markets'] = $this->mdmarkets->count_all();
		$pagevar['cntunit']['services'] = $this->mdservices->count_all();
		$pagevar['cntunit']['twork'] = $this->mdtypeswork->count_all();
		$pagevar['cntunit']['mails'] = $this->mdmessages->count_records_by_admin_new($this->user['uid']);
		$this->load->view("admin_interface/actions-statistic",$pagevar);
	}

	public function alert_debet(){
		
		$statusval = array('status'=>TRUE,);
		$days = trim($this->input->post('days'));
		if(!$days):
			show_404();
		endif;
		if($days<=5):
			$znak = '=';
		else:
			$znak = '<';
			$days = 5;
		endif;
		$date = date("Y-m-d",mktime(0,0,0,date("m"),date("d")-$days,date("Y")));
		$debetors = $this->mdunion->read_debetors_list($date,$znak);
		for($i=0;$i<count($debetors);$i++):
			if($debetors[$i]['cnt']):
				ob_start();
				?>
				<p><strong>Здравствуйте, <?=$debetors[$i]['ufio'];?></strong></p>
				<p>У Вас есть неоплаченные заявки <?=($days<=5)? 'за '.$days: 'старше 5' ;?> дня(-ей).</p>
				<p>Напоминаем Вам. Если у Вас будут неоплаченные заявки старше 5 дней (включительно) то Ваш аккаун будет заблокирован до полного погашения задолженности.</p>
				<?php if($days>=5):?>
				<p>ВНИМАНИЕ! Ваш аккаунт заблокирован через задолженность. Оплатите завершенные работы от 5 дней (включительно) для разблокировки.</p>
				<?php endif;?>
				<p>Спасибо, что пользуетесь нашим сайтом!</p>
				<?
				$mailtext = ob_get_clean();
				
				$this->email->clear(TRUE);
				$config['smtp_host'] = 'localhost';
				$config['charset'] = 'utf-8';
				$config['wordwrap'] = TRUE;
				$config['mailtype'] = 'html';
				
				$this->email->initialize($config);
				$this->email->to($debetors[$i]['ulogin']);
				$this->email->from('admin@bystropost.ru','Bystropost.ru - Система управления продажами');
				$this->email->bcc('');
				$this->email->subject('Noreply: Bystropost.ru - Уведомление о задолженности');
				$this->email->message($mailtext);
				$this->email->send();
			endif;
		endfor;
		echo json_encode($statusval);
	}
	
	public function locked_debet(){
		
		$statusval = array('status'=>TRUE,);
		$days = trim($this->input->post('days'));
		if(!$days):
			show_404();
		endif;
		if($days<=5):
			$znak = '=';
		elseif($days>5):
			$znak = '<';
		endif;
		$date = date("Y-m-d",mktime(0,0,0,date("m"),date("d")-5,date("Y")));
		$this->mdunion->update_debetors_status($date,$znak,1);
		$debetors = $this->mdunion->debetors_webmarkets();
		for($i=0;$i<count($debetors);$i++):
			$param = 'accid='.$debetors[$i]['id'].'&birzid='.$debetors[$i]['market'].'&login='.$debetors[$i]['login'].'&pass='.$this->encrypt->decode($debetors[$i]['cryptpassword']).'&act=2';
			$this->API('UpdateAccount',$param);
		endfor;
		echo json_encode($statusval);
	}

	/******************************************************** functions ******************************************************/	
	
	public function fileupload($userfile,$overwrite,$catalog){
		
		$config['upload_path'] 		= './documents/'.$catalog.'/';
		$config['allowed_types'] 	= 'doc|docx|xls|xlsx|txt|pdf';
		$config['remove_spaces'] 	= TRUE;
		$config['overwrite'] 		= $overwrite;
		
		$this->load->library('upload',$config);
		
		if(!$this->upload->do_upload($userfile)):
			return FALSE;
		endif;
		
		return TRUE;
	}

	public function filedelete($file){
		
		if(is_file($file)):
			@unlink($file);
			return TRUE;
		else:
			return FALSE;
		endif;
	}

	public function operation_date($field){
			
		$list = preg_split("/-/",$field);
		$nmonth = $this->months[$list[1]];
		$pattern = "/(\d+)(-)(\w+)(-)(\d+)/i";
		$replacement = "\$5 $nmonth \$1 г."; 
		return preg_replace($pattern, $replacement,$field);
	}
	
	public function split_date($field){
			
		$list = preg_split("/-/",$field);
		$nmonth = $this->months[$list[1]];
		$pattern = "/(\d+)(-)(\w+)(-)(\d+)/i";
		$replacement = "\$5 $nmonth \$1"; 
		return preg_replace($pattern, $replacement,$field);
	}
	
	public function split_dot_date($field){
			
		$list = preg_split("/\./",$field);
		$nmonth = $this->months[$list[1]];
		$pattern = "/(\d+)(\.)(\w+)(\.)(\d+)/i";
		$replacement = "\$1 $nmonth \$5"; 
		return preg_replace($pattern, $replacement,$field);
	}
	
	public function operation_dot_date($field){
			
		$list = preg_split("/-/",$field);
		$pattern = "/(\d+)(-)(\w+)(-)(\d+)/i";
		$replacement = "\$5.$3.\$1"; 
		return preg_replace($pattern, $replacement,$field);
	}
	
	public function randomPassword($length,$allow="abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPRSTUVWXYZ0123456789"){
	
		$i = 1;
		$ret = '';
		while($i<=$length):
			$max   = strlen($allow)-1;
			$num   = rand(0, $max);
			$temp  = substr($allow, $num, 1);
			$ret  .= $temp;
			$i++;
		endwhile;
		return $ret;
	}
	
	/******************************************************** Расчет парсинга ПР и ТИЦ******************************************************/
	
	public function StrToNum($Str, $Check, $Magic){
	
		$Int32Unit = 4294967296;
		$length = strlen($Str);
		for($i=0;$i<$length;$i++):
			$Check *= $Magic;
			if($Check>=$Int32Unit):
				$Check = ($Check-$Int32Unit*(int)($Check/$Int32Unit));
				$Check = ($Check<-2147483648)?($Check+$Int32Unit):$Check;
			endif;
			$Check += ord($Str{$i});
		endfor;
		return $Check;
	}

	public function HashURL($String){
	
		$Check1 = $this->StrToNum($String,0x1505,0x21);
		$Check2 = $this->StrToNum($String,0,0x1003F);
		$Check1 >>= 2;
		$Check1 = (($Check1 >> 4) & 0x3FFFFC0 ) | ($Check1 & 0x3F);
		$Check1 = (($Check1 >> 4) & 0x3FFC00 ) | ($Check1 & 0x3FF);
		$Check1 = (($Check1 >> 4) & 0x3C000 ) | ($Check1 & 0x3FFF);
		
		$T1 = (((($Check1 & 0x3C0) << 4) | ($Check1 & 0x3C)) <<2 ) | ($Check2 &	0xF0F );
		$T2 = (((($Check1 & 0xFFFFC000) << 4) | ($Check1 & 0x3C00)) << 0xA) | ($Check2 & 0xF0F0000 );
		return ($T1 | $T2);
	}
	
	public function CheckHash($Hashnum){
	
		$CheckByte = 0;
		$Flag = 0;
		$HashStr = sprintf('%u', $Hashnum) ;
		$length = strlen($HashStr);
		for($i=$length-1;$i>=0;$i--):
			$Re = $HashStr{$i};
			if(1===($Flag % 2)):
				$Re += $Re;
				$Re = (int)($Re/10)+($Re%10);
			endif;
			$CheckByte += $Re;
			$Flag++;
		endfor;
		$CheckByte %= 10;
		if(0!== $CheckByte):
			$CheckByte = 10 - $CheckByte;
			if(1===($Flag%2)):
				if(1===($CheckByte%2)):
					$CheckByte += 9;
				endif;
				$CheckByte >>= 1;
			endif;
		endif;
		
		return '7'.$CheckByte.$HashStr;
	}
	
	public function getpagerank($url){

		$fp = fsockopen("toolbarqueries.google.com", 80, $errno, $errstr, 30);
		if(!$fp):
			
		else:
			$out = "GET /tbr?features=Rank&sourceid=navclient-ff&client=navclient-auto-ff&ch=" .$this->CheckHash($this->HashURL($url)) . "&q=info:" . $url . " HTTP/1.1\r\n";
			$out .= "Host: toolbarqueries.google.com\r\n";
			$out .= "User-Agent: Mozilla/4.0 (compatible; GoogleToolbar 2.0.114-big;
			Windows XP 5.1)\r\n";
			$out .= "Connection: Close\r\n\r\n";
			fwrite($fp, $out);
			while(!feof($fp)):
				$data = fgets($fp, 128);
				$pos = strpos($data, "Rank_");
				if($pos === false):
				
				else:
					$pagerank = substr($data,$pos+9);
				endif;
			endwhile;
			fclose($fp);
		endif;
		if(!isset($pagerank)):
			$pagerank = 0;
		endif;
		return $pagerank;
	}
	
	public function getTIC($url){
		
		$str = @file("http://bar-navig.yandex.ru/u?ver=2&show=32&url=".$url);
		if($str == false):
			$cy = false;
		else:
			$result = preg_match("/value=\"(.\d*)\"/", join("",$str), $tic);
			if($result<1):
				$cy = 0;
			else:
				$cy = $tic[1];
			endif;
		endif;
		return $cy;
	}
}