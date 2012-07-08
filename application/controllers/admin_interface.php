<?php if(!defined('BASEPATH')) exit('No direct script access allowed');

class Admin_interface extends CI_Controller{
	
	var $user = array('uid'=>0,'uname'=>'','ulogin'=>'','utype'=>'');
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

		$cookieuid = $this->session->userdata('logon');
		if(isset($cookieuid) and !empty($cookieuid)):
			$this->user['uid'] = $this->session->userdata('userid');
			if($this->user['uid']):
				$userinfo = $this->mdusers->read_record($this->user['uid']);
				if($userinfo['type'] == 5):
					$this->user['ulogin'] 		= $userinfo['login'];
					$this->user['uname'] 		= $userinfo['fio'];
					$this->user['utype'] 		= $userinfo['type'];
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
		
		$this->load->view("admin_interface/control-panel",$pagevar);
	}
	
	public function actions_profile(){
		
		$pagevar = array(
					'description'	=> '',
					'author'		=> '',
					'title'			=> 'Администрирование | Личный кабинет',
					'baseurl' 		=> base_url(),
					'userinfo'		=> $this->user,
					'user'			=> $this->mdusers->read_record($this->user['uid']),
					'msgs'			=> $this->session->userdata('msgs'),
					'msgr'			=> $this->session->userdata('msgr')
			);
		$this->session->unset_userdata('msgs');
		$this->session->unset_userdata('msgr');
		$pagevar['user']['signdate'] = $this->operation_date($pagevar['user']['signdate']);
		
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
		
		$this->load->view("admin_interface/admin-profile",$pagevar);
	}
	
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
					'msgs'			=> $this->session->userdata('msgs'),
					'msgr'			=> $this->session->userdata('msgr')
			);
		$this->session->unset_userdata('msgs');
		$this->session->unset_userdata('msgr');
		
		if($this->input->post('eusubmit')):
			$_POST['eusubmit'] = NULL;
			$this->form_validation->set_rules('wmid',' ','required|trim');
			$this->form_validation->set_rules('balance',' ','required|trim');
			$this->form_validation->set_rules('type',' ','required|trim');
			if(!$this->form_validation->run()):
				$this->session->set_userdata('msgr','Ошибка при сохранении. Не заполены необходимые поля.');
			else:
				$result = $this->mdusers->update_record($_POST);
				if($result):
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
									$pagevar['users'] = $this->mdunion->read_users_group_webmasters(5,$from);
									$pagevar['count'] = $this->mdunion->count_users_group_webmasters();
									break;
			case 'optimizators' :	$pagevar['title'] .= 'Группа "Оптимизаторы"';
									$pagevar['users'] = $this->mdunion->read_users_group_optimizators(5,$from);
									$pagevar['count'] = $this->mdunion->count_users_group_optimizators();
									break;
			case 'manegers' 	:	$pagevar['title'] .= 'Группа "Менеджеры"';
									$pagevar['users'] = $this->mdunion->read_users_group_manegers(5,$from);
									$pagevar['count'] = $this->mdunion->count_users_group_manegers();
									break;
			case 'admin' 		:	$pagevar['title'] .= 'Группа "Администраторы"';
									$pagevar['users'] = $this->mdunion->read_users_group_admin(5,$from);
									$pagevar['count'] = $this->mdunion->count_users_group_admin();
									break;
			default 			:	$pagevar['title'] .= 'Все группы';
									$pagevar['users'] = $this->mdunion->read_users_group_all(5,$from);
									$pagevar['count'] = $this->mdunion->count_users_group_all();
									break;
		endswitch;
		
		$config['base_url'] 		= $pagevar['baseurl'].'admin-panel/management/users/'.$this->uri->segment(4).'/from/';
		$config['uri_segment'] 		= 6;
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
		
		for($i=0;$i<count($pagevar['users']);$i++):
			$pagevar['users'][$i]['signdate'] = $this->operation_dot_date($pagevar['users'][$i]['signdate']);
			if($pagevar['users'][$i]['lastlogin'] != '0000-00-00'):
				$pagevar['users'][$i]['lastlogin'] = $this->operation_dot_date($pagevar['users'][$i]['lastlogin']);
			else:
				$pagevar['users'][$i]['lastlogin'] = '';
			endif;
		endfor;
		
		$this->load->view("admin_interface/management-users",$pagevar);
	}
	
	public function management_users_deleting(){
		
		$uid = $this->uri->segment(5);
		if($uid):
			$result = $this->mdusers->delete_record($uid);
			if($result):
				$this->mdmessages->delete_records_by_user($uid);
				$this->mdtickets->delete_records_by_user($uid);
				$this->mdtkmsgs->delete_records_by_user($uid);
				$this->mdplatforms->close_platform_by_user($uid);
				$this->session->set_userdata('msgs','Пользователь удален успешно.');
			else:
				$this->session->set_userdata('msgr','Пользователь не удален.');
			endif;
			redirect($_SERVER['HTTP_REFERER']);
		else:
			show_404();
		endif;
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
			if(!$this->form_validation->run()):
				$this->session->set_userdata('msgr','Ошибка при сохранении. Не заполены необходимые поля.');
			else:
				if(!isset($_POST['locked'])):
					$_POST['locked'] = 0;
				endif;
				$prevman = $this->mdplatforms->read_field($_POST['pid'],'manager');
				$prevlock = $this->mdplatforms->read_field($_POST['pid'],'locked');
				$result = $this->mdplatforms->update_price($_POST['pid'],$_POST['uid'],$_POST);
				if($result):
					$fio = $this->mdusers->read_field($_POST['manager'],'fio');
					$email = $this->mdusers->read_field($_POST['manager'],'login');
					$platform = $this->mdplatforms->read_field($_POST['pid'],'url');
					$curdate = $this->operation_date(date("Y-m-d"));
					if(!$prevman && $_POST['manager']):
						$text = 'Здравствуйте! За Вами закреплена новая площадка '.$platform.'<br/>Дата закрепления: '.$curdate;
						$this->mdmessages->insert_record($this->user['uid'],$_POST['manager'],$text);
						if($this->mdusers->read_field($_POST['manager'],'sendmail')):
							//Высылать письмо-уведомление
						endif;
					elseif($prevman && !$_POST['manager']):
						$text = 'Здравствуйте! С Ваc снята площадка '.$platform.'<br/>Дата снятия: '.$curdate;
						$this->mdmessages->insert_record($this->user['uid'],$_POST['manager'],$text);
						if($this->mdusers->read_field($_POST['manager'],'sendmail')):
							//Высылать письмо-уведомление
						endif;
					elseif($prevman != $_POST['manager']):
						$text = 'Здравствуйте! За Вами закреплена новая площадка '.$platform.'<br/>Дата закрепления: '.$curdate;
						$this->mdmessages->insert_record($this->user['uid'],$_POST['manager'],$text);
						$text = 'Здравствуйте! С Ваc снята площадка '.$platform.'<br/>Дата снятия: '.$curdate;
						$this->mdmessages->insert_record($this->user['uid'],$prevman,$text);
						if($this->mdusers->read_field($prevman,'sendmail')):
							//Высылать письмо-уведомление
						endif;
						if($this->mdusers->read_field($_POST['manager'],'sendmail')):
							//Высылать письмо-уведомление
						endif;
					endif;
					if(!$prevlock && $_POST['locked']):
						$text = 'Здравствуйте! Ваша площадка '.$platform.' заблокирована администратором. Дата блокировки: '.$curdate;
						$this->mdmessages->insert_record($this->user['uid'],$_POST['uid'],$text);
						if($_POST['manager']):
							$text = 'Здравствуйте! Закреплення за Вами площадка '.$platform.' заблокирована администратором. Дата блокировки: '.$curdate;
							$this->mdmessages->insert_record($this->user['uid'],$_POST['manager'],$text);
							if($this->mdusers->read_field($_POST['manager'],'sendmail')):
								//Высылать письмо-уведомление
							endif;
						endif;
						if($this->mdusers->read_field($_POST['uid'],'sendmail')):
							//Высылать письмо-уведомление
						endif;
					elseif($prevlock && !$_POST['locked']):
						$text = 'Здравствуйте! Ваша площадка '.$platform.' разблокирована администратором. Дата разблокировки: '.$curdate;
						$this->mdmessages->insert_record($this->user['uid'],$_POST['uid'],$text);
						if($_POST['manager']):
							$text = 'Здравствуйте! Закреплення за Вами площадка '.$platform.' разблокирована администратором. Дата разблокировки: '.$curdate;
							$this->mdmessages->insert_record($this->user['uid'],$_POST['manager'],$text);
							if($this->mdusers->read_field($_POST['manager'],'sendmail')):
								//Высылать письмо-уведомление
							endif;
						endif;
						if($this->mdusers->read_field($_POST['uid'],'sendmail')):
							//Высылать письмо-уведомление
						endif;
					endif;
					$this->session->set_userdata('msgs','Информация успешно сохранена.');
				else:
					$this->session->set_userdata('msgr','Информация не изменилась.');
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
		
		for($i=0;$i<count($pagevar['platforms']);$i++):
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
		endfor;
		
		$this->load->view("admin_interface/management-platforms",$pagevar);
	}

	public function management_delete_platform(){
		
		$pid = $this->uri->segment(6);
		if($pid):
			$info = $this->mdplatforms->read_record($pid);
			$fio = $this->mdusers->read_field($info['webmaster'],'fio');
			$email = $this->mdusers->read_field($info['webmaster'],'login');
			if(empty($fio)):
				$email = $fio = 'не определен';
			endif;
			$result = $this->mdplatforms->delete_record($pid);
			if($result):
				$text = 'Площадка '.$info['url'].'. Владелец: '.$fio.' ('.$email.'). Удалена администратором. Дата удаления: '.$this->operation_date(date("Y-m-d"));
				if($info['webmaster']):
					$this->mdmessages->insert_record($this->user['uid'],$info['webmaster'],$text);
				endif;
				if($info['manager']):
					$this->mdmessages->insert_record($this->user['uid'],$info['manager'],$text);
				endif;
				$this->session->set_userdata('msgs','Площадка удалена успешно.');
			else:
				$this->session->set_userdata('msgr','Площадка не удалена.');
			endif;
			redirect($_SERVER['HTTP_REFERER']);
		else:
			show_404();
		endif;
	}

	public function management_markets(){
		
		$pagevar = array(
					'description'	=> '',
					'author'		=> '',
					'title'			=> 'Администрирование | Биржи',
					'baseurl' 		=> base_url(),
					'userinfo'		=> $this->user,
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
					$_POST['icon'] = file_get_contents(base_url().'images/markets/other.jpg');
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
	
	public function actions_forum(){
		
		$pagevar = array(
					'description'	=> '',
					'author'		=> '',
					'title'			=> 'Администрирование | Форум',
					'baseurl' 		=> base_url(),
					'userinfo'		=> $this->user,
					'msgs'			=> $this->session->userdata('msgs'),
					'msgr'			=> $this->session->userdata('msgr')
			);
		$this->session->unset_userdata('msgs');
		$this->session->unset_userdata('msgr');
		$this->load->view("admin_interface/actions-forum",$pagevar);
	}
	
	public function actions_balance(){
		
		$pagevar = array(
					'description'	=> '',
					'author'		=> '',
					'title'			=> 'Администрирование | Баланс',
					'baseurl' 		=> base_url(),
					'userinfo'		=> $this->user,
					'msgs'			=> $this->session->userdata('msgs'),
					'msgr'			=> $this->session->userdata('msgr')
			);
		$this->session->unset_userdata('msgs');
		$this->session->unset_userdata('msgr');
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
					'msgs'			=> $this->session->userdata('msgs'),
					'msgr'			=> $this->session->userdata('msgr')
			);
		$this->session->unset_userdata('msgs');
		$this->session->unset_userdata('msgr');
		
		if($this->input->post('submit')):
			$_POST['submit'] = NULL;
//			print_r($_POST);exit;
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
		$this->load->view("admin_interface/messages-system",$pagevar);
	}
	
	public function messages_private(){
		
		$from = intval($this->uri->segment(5));
		$pagevar = array(
					'description'	=> '',
					'author'		=> '',
					'title'			=> 'Администрирование | Личные сообщения',
					'baseurl' 		=> base_url(),
					'userinfo'		=> $this->user,
					'mails'			=> $this->mdunion->read_mails_by_recipient_pages($this->user['uid'],5,$from),
					'count'			=> $this->mdunion->count_mails_by_recipient_pages($this->user['uid']),
					'pages'			=> array(),
					'msgs'			=> $this->session->userdata('msgs'),
					'msgr'			=> $this->session->userdata('msgr')
			);
		$this->session->unset_userdata('msgs');
		$this->session->unset_userdata('msgr');
		for($i=0;$i<count($pagevar['mails']);$i++):
			$pagevar['mails'][$i]['date'] = $this->operation_date($pagevar['mails'][$i]['date']);
		endfor;
		
		$config['base_url'] 	= $pagevar['baseurl'].'admin-panel/messages/private-messages/from/';
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
					
				endif;
			endif;
			redirect($this->uri->uri_string());
		endif;
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
					'msgs'			=> $this->session->userdata('msgs'),
					'msgr'			=> $this->session->userdata('msgr')
			);
		$this->session->unset_userdata('msgs');
		$this->session->unset_userdata('msgr');
		
		for($i=0;$i<count($pagevar['tickets']);$i++):
			$pagevar['tickets'][$i]['date'] = $this->operation_date($pagevar['tickets'][$i]['date']);
			if($pagevar['tickets'][$i]['recipient']):
				$pagevar['tickets'][$i]['user'] = $this->mdusers->read_field($pagevar['tkmsgs'][$i]['recipient'],'fio');
				$pagevar['tickets'][$i]['email'] = $this->mdusers->read_field($pagevar['tkmsgs'][$i]['recipient'],'login');
			else:
				$pagevar['tickets'][$i]['user'] = '<em><strong><font style="color:#ff0000;">Администратору</font></strong></em>';
				$pagevar['tickets'][$i]['email'] = '';
			endif;
		endfor;
		
		$config['base_url'] 	= $pagevar['baseurl'].'admin-panel/messages/private-messages/from/';
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
					'tktitle'		=> $this->mdtickets->read_field($ticket,'title'),
					'tkmsgs'		=> $this->mdunion->read_messages_by_ticket_pages($ticket,5,$from),
					'count'			=> $this->mdunion->count_messages_by_ticket($ticket),
					'pages'			=> array(),
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
					//уведомление по почте
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
		$this->load->view("admin_interface/messages-view-tickets",$pagevar);
	}

	public function control_delete_msg_ticket(){
		
		$message = $this->uri->segment(6);
		if($message):
			if(!$this->mdtkmsgs->ownew_message($this->user['uid'])):
				redirect($_SERVER['HTTP_REFERER']);
			endif;
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
}