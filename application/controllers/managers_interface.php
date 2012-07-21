<?php if(!defined('BASEPATH')) exit('No direct script access allowed');

class Managers_interface extends CI_Controller{
	
	var $user = array('uid'=>0,'uname'=>'','ulogin'=>'','utype'=>'');
	var $loginstatus = array('status'=>FALSE);
	var $months = array("01"=>"января","02"=>"февраля","03"=>"марта","04"=>"апреля","05"=>"мая","06"=>"июня","07"=>"июля","08"=>"августа","09"=>"сентября","10"=>"октября","11"=>"ноября","12"=>"декабря");
	
	function __construct(){
		
		parent::__construct();
		$this->load->model('mdusers');
		$this->load->model('mdunion');
		$this->load->model('mdmessages');
		$this->load->model('mdmarkets');
		$this->load->model('mdplatforms');
		$this->load->model('mdmkplatform');
		$this->load->model('mdtickets');
		$this->load->model('mdtkmsgs');
		$this->load->model('mdtypeswork');
		$this->load->model('mddelivesworks');
		
		$cookieuid = $this->session->userdata('logon');
		if(isset($cookieuid) and !empty($cookieuid)):
			$this->user['uid'] = $this->session->userdata('userid');
			if($this->user['uid']):
				$userinfo = $this->mdusers->read_record($this->user['uid']);
				if($userinfo['type'] == 2):
					$this->user['ulogin'] 			= $userinfo['login'];
					$this->user['uname'] 			= $userinfo['fio'];
					$this->user['utype'] 			= $userinfo['type'];
					$this->loginstatus['status'] 	= TRUE;
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
		
		$from = intval($this->uri->segment(5));
		$pagevar = array(
					'description'	=> '',
					'author'		=> '',
					'title'			=> 'Кабинет Менеджера | Выполненные задания',
					'baseurl' 		=> base_url(),
					'loginstatus'	=> $this->loginstatus['status'],
					'userinfo'		=> $this->user,
					'delivers'		=> $this->mdunion->devivers_works_manager($this->user['uid'],10,$from),
					'cntunit'		=> array(),
					'pages'			=> array(),
					'msgs'			=> $this->session->userdata('msgs'),
					'msgr'			=> $this->session->userdata('msgr')
			);
		$this->session->unset_userdata('msgs');
		$this->session->unset_userdata('msgr');
		
		if($this->loginstatus['status']):
			if($this->user['utype'] == 1):
				$userdata = $this->mdunion->read_user_webmaster($this->user['uid']);
				$pagevar['userinfo']['balance'] = $userdata['balance'];
				$pagevar['userinfo']['torders'] = $userdata['torders'];
				$pagevar['userinfo']['uporders'] = $userdata['uporders'];
				unset($userdata);
			endif;
		endif;
		
		for($i=0;$i<count($pagevar['delivers']);$i++):
			$pagevar['delivers'][$i]['date'] = $this->operation_dot_date($pagevar['delivers'][$i]['date']);
		endfor;
		
		$config['base_url'] 	= $pagevar['baseurl'].'manager-panel/actions/control/from/';
		$config['uri_segment'] 	= 5;
		$config['total_rows'] 	= $this->mdunion->count_devivers_works_manager($this->user['uid']);
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
		
		$pagevar['cntunit']['delivers']['paid'] = $this->mddelivesworks->count_records_by_manager_status($this->user['uid'],1);
		$pagevar['cntunit']['delivers']['notpaid'] = $this->mddelivesworks->count_records_by_manager_status($this->user['uid'],0);
		$pagevar['cntunit']['platforms'] = $this->mdplatforms->count_records_by_manager($this->user['uid']);
		$pagevar['cntunit']['mails']['new'] = $this->mdmessages->count_records_by_recipient_new($this->user['uid']);
		$pagevar['cntunit']['mails']['total'] = $this->mdmessages->count_records_by_recipient($this->user['uid'],$this->user['utype']);
		$pagevar['cntunit']['tickets']['inbox'] = $this->mdtickets->count_records_by_recipient($this->user['uid']);
		$pagevar['cntunit']['tickets']['outbox'] = $this->mdtickets->count_records_by_sender($this->user['uid']);
		
		$this->load->view("managers_interface/control-panel",$pagevar);
	}
	
	public function control_profile(){
		
		$pagevar = array(
					'description'	=> '',
					'author'		=> '',
					'title'			=> 'Кабинет Менеджера | Профиль',
					'baseurl' 		=> base_url(),
					'loginstatus'	=> $this->loginstatus['status'],
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
		
		$pagevar['cntunit']['delivers']['paid'] = $this->mddelivesworks->count_records_by_manager_status($this->user['uid'],1);
		$pagevar['cntunit']['delivers']['notpaid'] = $this->mddelivesworks->count_records_by_manager_status($this->user['uid'],0);
		$pagevar['cntunit']['platforms'] = $this->mdplatforms->count_records_by_manager($this->user['uid']);
		$pagevar['cntunit']['mails']['new'] = $this->mdmessages->count_records_by_recipient_new($this->user['uid']);
		$pagevar['cntunit']['mails']['total'] = $this->mdmessages->count_records_by_recipient($this->user['uid'],$this->user['utype']);
		$pagevar['cntunit']['tickets']['inbox'] = $this->mdtickets->count_records_by_recipient($this->user['uid']);
		$pagevar['cntunit']['tickets']['outbox'] = $this->mdtickets->count_records_by_sender($this->user['uid']);
		
		$this->load->view("managers_interface/manager-profile",$pagevar);
	}
	
	/******************************************************** other ******************************************************/	
	
	function views(){
	
		$type = $this->uri->segment(2);
		switch ($type):
			case 'market-profile'	:	$pagevar = array('markets'=>$this->mdmarkets->read_records(),'baseurl'=>base_url());
										$this->load->view('managers_interface/includes/markets-profile',$pagevar);
										break;
					default 		:	show_404();
		endswitch;
	}
	
	public function actions_logoff(){
		
		$this->session->sess_destroy();
		redirect('');
	}
	
	/***************************************************** platforms ******************************************************/	
	
	public function control_platforms(){
		
		$pagevar = array(
					'description'	=> '',
					'author'		=> '',
					'title'			=> 'Кабинет Менеджера | Назначенные площадки',
					'baseurl' 		=> base_url(),
					'loginstatus'	=> $this->loginstatus['status'],
					'userinfo'		=> $this->user,
					'cntunit'		=> array(),
					'platforms'		=> $this->mdplatforms->read_records_by_manager($this->user['uid']),
					'msgs'			=> $this->session->userdata('msgs'),
					'msgr'			=> $this->session->userdata('msgr')
			);
		$this->session->unset_userdata('msgs');
		$this->session->unset_userdata('msgr');
		
		$pagevar['cntunit']['delivers']['paid'] = $this->mddelivesworks->count_records_by_manager_status($this->user['uid'],1);
		$pagevar['cntunit']['delivers']['notpaid'] = $this->mddelivesworks->count_records_by_manager_status($this->user['uid'],0);
		$pagevar['cntunit']['platforms'] = $this->mdplatforms->count_records_by_manager($this->user['uid']);
		$pagevar['cntunit']['mails']['new'] = $this->mdmessages->count_records_by_recipient_new($this->user['uid']);
		$pagevar['cntunit']['mails']['total'] = $this->mdmessages->count_records_by_recipient($this->user['uid'],$this->user['utype']);
		$pagevar['cntunit']['tickets']['inbox'] = $this->mdtickets->count_records_by_recipient($this->user['uid']);
		$pagevar['cntunit']['tickets']['outbox'] = $this->mdtickets->count_records_by_sender($this->user['uid']);
		
		for($i=0;$i<count($pagevar['platforms']);$i++):
			$pagevar['platforms'][$i]['date'] = $this->operation_dot_date($pagevar['platforms'][$i]['date']);
		endfor;
		$this->load->view("managers_interface/control-platforms",$pagevar);
	}
	
	public function control_view_platform(){
		
		$platform = $this->uri->segment(5);
		if(!$this->mdplatforms->ownew_manager_platform($this->user['uid'],$platform)):
			show_404();
		endif;
		$pagevar = array(
					'description'	=> '',
					'author'		=> '',
					'title'			=> 'Кабинет Менеджера | Назначенные площадки | Просмотр площадки',
					'baseurl' 		=> base_url(),
					'loginstatus'	=> $this->loginstatus['status'],
					'userinfo'		=> $this->user,
					'platform'		=> $this->mdplatforms->read_record($platform),
					'markets'		=> $this->mdmarkets->read_records(),
					'mymarkets'		=> array(),
					'msgs'			=> $this->session->userdata('msgs'),
					'msgr'			=> $this->session->userdata('msgr')
			);
		$this->session->unset_userdata('msgs');
		$this->session->unset_userdata('msgr');
		
		$pagevar['mymarkets'] = $this->mdmkplatform->read_records_by_platform($platform,$pagevar['platform']['webmaster']);
		
		$this->load->view("managers_interface/control-view-platform",$pagevar);
	}
	
	/**************************************************** deliver work ****************************************************/	
	
	public function deliver_work(){
		
		$platform = $this->uri->segment(4);
		
		if(!$this->mdplatforms->ownew_manager_platform($this->user['uid'],$platform)):
			redirect('manager-panel/actions/platforms');
		endif;
		
		$pagevar = array(
					'description'	=> '',
					'author'		=> '',
					'title'			=> 'Кабинет Менеджера | Назначенные площадки',
					'baseurl' 		=> base_url(),
					'loginstatus'	=> $this->loginstatus['status'],
					'userinfo'		=> $this->user,
					'cntunit'		=> array(),
					'typeswork'		=> $this->mdtypeswork->read_records(),
					'markets'		=> $this->mdmarkets->read_records(),
					'platform'		=> $this->mdplatforms->read_record($platform),
					'msgs'			=> $this->session->userdata('msgs'),
					'msgr'			=> $this->session->userdata('msgr')
			);
		$this->session->unset_userdata('msgs');
		$this->session->unset_userdata('msgr');
		
		if($this->input->post('submit')):
			$_POST['submit'] = NULL;
			$this->form_validation->set_rules('typework',' ','required|trim');
			$this->form_validation->set_rules('market',' ','required|trim');
			$this->form_validation->set_rules('mkprice',' ','required|trim');
			$this->form_validation->set_rules('ulrlink',' ','required|prep_url|trim');
			$this->form_validation->set_rules('countchars',' ','required|trim');
			if(!$this->form_validation->run()):
				$this->session->set_userdata('msgr','Ошибка при сохранении. Не заполены необходимые поля.');
			else:
				$webmaster = $this->mdplatforms->read_field($platform,'webmaster');
				$prices = $this->mdtypeswork->read_prices($_POST['typework']);
				if($webmaster):
					$this->mddelivesworks->insert_record($webmaster,$platform,$this->user['uid'],$prices['wprice'],$prices['mprice'],$_POST);
					$this->session->set_userdata('msgs','Отчет о выполенной работе создан');
				else:
					$this->session->set_userdata('msgr','Отчет о выполенной работе не создан');
				endif;
			endif;
			redirect($this->uri->uri_string());
		endif;
		
		$pagevar['cntunit']['delivers']['paid'] = $this->mddelivesworks->count_records_by_manager_status($this->user['uid'],1);
		$pagevar['cntunit']['delivers']['notpaid'] = $this->mddelivesworks->count_records_by_manager_status($this->user['uid'],0);
		$pagevar['cntunit']['platforms'] = $this->mdplatforms->count_records_by_manager($this->user['uid']);
		$pagevar['cntunit']['mails']['new'] = $this->mdmessages->count_records_by_recipient_new($this->user['uid']);
		$pagevar['cntunit']['mails']['total'] = $this->mdmessages->count_records_by_recipient($this->user['uid'],$this->user['utype']);
		$pagevar['cntunit']['tickets']['inbox'] = $this->mdtickets->count_records_by_recipient($this->user['uid']);
		$pagevar['cntunit']['tickets']['outbox'] = $this->mdtickets->count_records_by_sender($this->user['uid']);
		
		$this->load->view("managers_interface/deliver-work",$pagevar);
	}

	/******************************************************* mails *********************************************************/	
	
	public function control_mails(){
		
		$from = intval($this->uri->segment(5));
		$pagevar = array(
					'description'	=> '',
					'author'		=> '',
					'title'			=> 'Кабинет Менеджера | Входящие сообщениями',
					'baseurl' 		=> base_url(),
					'loginstatus'	=> $this->loginstatus['status'],
					'userinfo'		=> $this->user,
					'mails'			=> $this->mdunion->read_mails_by_recipient($this->user['uid'],$this->user['utype'],10,$from),
					'cntunit'		=> array(),
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
					//Отослать письмо подьзователю
				endif;
			endif;
			redirect($this->uri->uri_string());
		endif;
		
		for($i=0;$i<count($pagevar['mails']);$i++):
			$pagevar['mails'][$i]['date'] = $this->operation_date($pagevar['mails'][$i]['date']);
		endfor;
		
		$config['base_url'] 	= $pagevar['baseurl'].'manager-panel/actions/mails/from/';
		$config['uri_segment'] 	= 5;
		$config['total_rows'] 	= $this->mdunion->count_mails_by_recipient($this->user['uid'],$this->user['utype']);
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
		$this->mdmessages->set_read_mails_by_recipient($this->user['uid'],$this->user['utype']);
		
		$pagevar['cntunit']['delivers']['paid'] = $this->mddelivesworks->count_records_by_manager_status($this->user['uid'],1);
		$pagevar['cntunit']['delivers']['notpaid'] = $this->mddelivesworks->count_records_by_manager_status($this->user['uid'],0);
		$pagevar['cntunit']['platforms'] = $this->mdplatforms->count_records_by_manager($this->user['uid']);
		$pagevar['cntunit']['mails']['new'] = $this->mdmessages->count_records_by_recipient_new($this->user['uid']);
		$pagevar['cntunit']['mails']['total'] = $this->mdmessages->count_records_by_recipient($this->user['uid'],$this->user['utype']);
		$pagevar['cntunit']['tickets']['inbox'] = $this->mdtickets->count_records_by_recipient($this->user['uid']);
		$pagevar['cntunit']['tickets']['outbox'] = $this->mdtickets->count_records_by_sender($this->user['uid']);
		
		$this->load->view("managers_interface/control-mails",$pagevar);
	}
	
	public function control_delete_mail(){
		
		$mid = $this->uri->segment(6);
		if($mid):
			if($this->mdmessages->is_system($mid)):
				redirect('manager-panel/actions/mails');
			endif;
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
	
	/****************************************************** tickets ******************************************************/	
	
	public function control_tickets_outbox(){
		
		$from = intval($this->uri->segment(6));
		$pagevar = array(
					'description'	=> '',
					'author'		=> '',
					'title'			=> 'Кабинет Менеджера | Исходящие тикеты',
					'baseurl' 		=> base_url(),
					'loginstatus'	=> $this->loginstatus['status'],
					'userinfo'		=> $this->user,
					'tickets'		=> $this->mdunion->read_tickets_by_sender($this->user['uid'],5,$from),
					'pages'			=> array(),
					'cntunit'		=> array(),
					'msgs'			=> $this->session->userdata('msgs'),
					'msgr'			=> $this->session->userdata('msgr')
			);
		$this->session->unset_userdata('msgs');
		$this->session->unset_userdata('msgr');
		
		if($this->input->post('submit')):
			$_POST['submit'] = NULL;
			$this->form_validation->set_rules('title',' ','required|trim');
			$this->form_validation->set_rules('text',' ','required|trim');
			if(!$this->form_validation->run()):
				$this->session->set_userdata('msgr','Ошибка при сохранении. Не заполены необходимые поля.');
			else:
				$_POST['type'] = 2; $_POST['platform'] = 0;
				$ticket = $this->mdtickets->insert_record($this->user['uid'],0,$_POST);
				if($ticket):
					$this->mdtkmsgs->insert_record($this->user['uid'],$ticket,$this->user['uid'],0,0,$_POST['text']);
					$this->session->set_userdata('msgs','Тикет успешно создан.');
				else:
					$this->session->set_userdata('msgr','Тикет не создан.');
				endif;
			endif;
			redirect($this->uri->uri_string());
		endif;
		
		$config['base_url'] 	= $pagevar['baseurl'].'manager-panel/actions/tickets/outbox/from/';
		$config['uri_segment'] 	= 6;
		$config['total_rows'] 	= $this->mdunion->count_tickets_by_sender($this->user['uid']);
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
		
		$pagevar['cntunit']['delivers']['paid'] = $this->mddelivesworks->count_records_by_manager_status($this->user['uid'],1);
		$pagevar['cntunit']['delivers']['notpaid'] = $this->mddelivesworks->count_records_by_manager_status($this->user['uid'],0);
		$pagevar['cntunit']['platforms'] = $this->mdplatforms->count_records_by_manager($this->user['uid']);
		$pagevar['cntunit']['mails']['new'] = $this->mdmessages->count_records_by_recipient_new($this->user['uid']);
		$pagevar['cntunit']['mails']['total'] = $this->mdmessages->count_records_by_recipient($this->user['uid'],$this->user['utype']);
		$pagevar['cntunit']['tickets']['inbox'] = $this->mdtickets->count_records_by_recipient($this->user['uid']);
		$pagevar['cntunit']['tickets']['outbox'] = $this->mdtickets->count_records_by_sender($this->user['uid']);
		
		for($i=0;$i<count($pagevar['tickets']);$i++):
			$pagevar['tickets'][$i]['date'] = $this->operation_dot_date($pagevar['tickets'][$i]['date']);
		endfor;
		$this->load->view("managers_interface/control-tickets-outbox",$pagevar);
	}
	
	public function control_tickets_inbox(){
		
		$from = intval($this->uri->segment(6));
		$pagevar = array(
					'description'	=> '',
					'author'		=> '',
					'title'			=> 'Кабинет Менеджера | Исходящие тикеты',
					'baseurl' 		=> base_url(),
					'loginstatus'	=> $this->loginstatus['status'],
					'userinfo'		=> $this->user,
					'tickets'		=> $this->mdunion->read_tickets_by_recipient($this->user['uid'],5,$from),
					'pages'			=> array(),
					'cntunit'		=> array(),
					'msgs'			=> $this->session->userdata('msgs'),
					'msgr'			=> $this->session->userdata('msgr')
			);
		$this->session->unset_userdata('msgs');
		$this->session->unset_userdata('msgr');
		
		$config['base_url'] 	= $pagevar['baseurl'].'manager-panel/actions/tickets/inbox/from/';
		$config['uri_segment'] 	= 6;
		$config['total_rows'] 	= $this->mdunion->count_tickets_by_sender($this->user['uid']);
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
		
		$pagevar['cntunit']['delivers']['paid'] = $this->mddelivesworks->count_records_by_manager_status($this->user['uid'],1);
		$pagevar['cntunit']['delivers']['notpaid'] = $this->mddelivesworks->count_records_by_manager_status($this->user['uid'],0);
		$pagevar['cntunit']['platforms'] = $this->mdplatforms->count_records_by_manager($this->user['uid']);
		$pagevar['cntunit']['mails']['new'] = $this->mdmessages->count_records_by_recipient_new($this->user['uid']);
		$pagevar['cntunit']['mails']['total'] = $this->mdmessages->count_records_by_recipient($this->user['uid'],$this->user['utype']);
		$pagevar['cntunit']['tickets']['inbox'] = $this->mdtickets->count_records_by_recipient($this->user['uid']);
		$pagevar['cntunit']['tickets']['outbox'] = $this->mdtickets->count_records_by_sender($this->user['uid']);
		
		for($i=0;$i<count($pagevar['tickets']);$i++):
			$pagevar['tickets'][$i]['date'] = $this->operation_dot_date($pagevar['tickets'][$i]['date']);
		endfor;
		$this->load->view("managers_interface/control-tickets-inbox",$pagevar);
	}
	
	public function control_view_inbox_ticket(){
		
		$ticket = $this->uri->segment(6);
		if(!$this->mdtickets->ownew_ticket_or_recipient($this->user['uid'],$ticket)):
			show_404();
		endif;
		$from = intval($this->uri->segment(8));
		$pagevar = array(
					'description'	=> '',
					'author'		=> '',
					'title'			=> 'Кабинет Менеджера | Входящие тикеты | Просмотр тикета',
					'baseurl' 		=> base_url(),
					'loginstatus'	=> $this->loginstatus['status'],
					'userinfo'		=> $this->user,
					'ticket'		=> $this->mdunion->view_ticket_info($ticket),
					'tkmsgs'		=> $this->mdtkmsgs->read_tkmsgs_by_manager_pages($this->user['uid'],$ticket,5,$from),
					'count'			=> $this->mdtkmsgs->count_tkmsgs_by_manager_pages($this->user['uid'],$ticket),
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
			$this->form_validation->set_rules('text',' ','trim');
			if(!$this->form_validation->run()):
				$this->session->set_userdata('msgr','Не заполены необходимые поля.');
			else:
				$result = $this->mdtkmsgs->insert_record($pagevar['ticket']['sender'],$ticket,$this->user['uid'],$_POST['recipient'],$_POST['mid'],$_POST['text']);
				if($result):
					$this->session->set_userdata('msgs','Сообщение отправлено');
				endif;
			endif;
			redirect($this->uri->uri_string());
		endif;
		for($i=0;$i<count($pagevar['tkmsgs']);$i++):
			$pagevar['tkmsgs'][$i]['date'] = $this->operation_date($pagevar['tkmsgs'][$i]['date']);
			if($pagevar['tkmsgs'][$i]['sender'] != $this->user['uid']):
				if($pagevar['tkmsgs'][$i]['sender']):
					$pagevar['tkmsgs'][$i]['position'] = $this->mdusers->read_field($pagevar['tkmsgs'][$i]['sender'],'position');
				else:
					$pagevar['tkmsgs'][$i]['position'] = '<b>Администратор</b>';
				endif;
			else:
				$pagevar['tkmsgs'][$i]['position'] = 'Вы';
			endif;
		endfor;
		$config['base_url'] 	= $pagevar['baseurl'].'manager-panel/actions/tickets/'.$this->uri->segment(4).'/view-ticket/'.$this->uri->segment(6).'/from/';
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
		
		$pagevar['cntunit']['delivers']['paid'] = $this->mddelivesworks->count_records_by_manager_status($this->user['uid'],1);
		$pagevar['cntunit']['delivers']['notpaid'] = $this->mddelivesworks->count_records_by_manager_status($this->user['uid'],0);
		$pagevar['cntunit']['platforms'] = $this->mdplatforms->count_records_by_manager($this->user['uid']);
		$pagevar['cntunit']['mails']['new'] = $this->mdmessages->count_records_by_recipient_new($this->user['uid']);
		$pagevar['cntunit']['mails']['total'] = $this->mdmessages->count_records_by_recipient($this->user['uid'],$this->user['utype']);
		$pagevar['cntunit']['tickets']['inbox'] = $this->mdtickets->count_records_by_recipient($this->user['uid']);
		$pagevar['cntunit']['tickets']['outbox'] = $this->mdtickets->count_records_by_sender($this->user['uid']);
		
		$this->load->view("managers_interface/view-inbox-ticket",$pagevar);
	}
	
	public function control_view_outbox_ticket(){
		
		$ticket = $this->uri->segment(6);
		if(!$this->mdtickets->ownew_ticket($this->user['uid'],$ticket)):
			show_404();
		endif;
		$from = intval($this->uri->segment(8));
		$pagevar = array(
					'description'	=> '',
					'author'		=> '',
					'title'			=> 'Кабинет Менеджера | Исходящие тикеты | Просмотр тикета',
					'baseurl' 		=> base_url(),
					'loginstatus'	=> $this->loginstatus['status'],
					'userinfo'		=> $this->user,
					'ticket'		=> $this->mdtickets->read_field($ticket,'title'),
					'tkmsgs'		=> $this->mdtkmsgs->read_tkmsgs_by_manager_pages($this->user['uid'],$ticket,5,$from),
					'count'			=> $this->mdtkmsgs->count_tkmsgs_by_manager_pages($this->user['uid'],$ticket),
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
			$this->form_validation->set_rules('text',' ','trim');
			if(!$this->form_validation->run()):
				$this->session->set_userdata('msgr','Не заполены необходимые поля.');
			else:
				if(isset($_POST['closeticket'])):
					$_POST['text'] .= '<br/><strong>Cпасибо за информацию. Тикет закрыт!</strong>';
					$this->mdtickets->update_field($ticket,'status',1);
				endif;
				$result = $this->mdtkmsgs->insert_record($pagevar['ticket']['sender'],$ticket,$this->user['uid'],$_POST['recipient'],$_POST['mid'],$_POST['text']);
				if($result):
					$this->session->set_userdata('msgs','Сообщение отправлено');
				endif;
			endif;
			redirect($this->uri->uri_string());
		endif;
		for($i=0;$i<count($pagevar['tkmsgs']);$i++):
			$pagevar['tkmsgs'][$i]['date'] = $this->operation_date($pagevar['tkmsgs'][$i]['date']);
			if($pagevar['tkmsgs'][$i]['sender'] != $this->user['uid']):
				if($pagevar['tkmsgs'][$i]['sender']):
					$pagevar['tkmsgs'][$i]['position'] = $this->mdusers->read_field($pagevar['tkmsgs'][$i]['sender'],'position');
				else:
					$pagevar['tkmsgs'][$i]['position'] = '<b>Администратор</b>';
				endif;
			else:
				$pagevar['tkmsgs'][$i]['position'] = 'Вы';
			endif;
		endfor;
		$config['base_url'] 	= $pagevar['baseurl'].'manager-panel/actions/tickets/'.$this->uri->segment(4).'/view-ticket/'.$this->uri->segment(6).'/from/';
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
		
		$pagevar['cntunit']['delivers']['paid'] = $this->mddelivesworks->count_records_by_manager_status($this->user['uid'],1);
		$pagevar['cntunit']['delivers']['notpaid'] = $this->mddelivesworks->count_records_by_manager_status($this->user['uid'],0);
		$pagevar['cntunit']['platforms'] = $this->mdplatforms->count_records_by_manager($this->user['uid']);
		$pagevar['cntunit']['mails']['new'] = $this->mdmessages->count_records_by_recipient_new($this->user['uid']);
		$pagevar['cntunit']['mails']['total'] = $this->mdmessages->count_records_by_recipient($this->user['uid'],$this->user['utype']);
		$pagevar['cntunit']['tickets']['inbox'] = $this->mdtickets->count_records_by_recipient($this->user['uid']);
		$pagevar['cntunit']['tickets']['outbox'] = $this->mdtickets->count_records_by_sender($this->user['uid']);
		
		$this->load->view("managers_interface/view-outbox-ticket",$pagevar);
	}
	
	public function control_delete_tickets(){
		
		$ticket = $this->uri->segment(6);
		if($ticket):
			if(!$this->mdtickets->ownew_ticket($this->user['uid'],$ticket)):
				redirect($_SERVER['HTTP_REFERER']);
			endif;
			$result = $this->mdtickets->delete_record($ticket);
			if($result):
				$this->mdtkmsgs->delete_records($ticket);
				$this->session->set_userdata('msgs','Сообшение удалено успешно');
			else:
				$this->session->set_userdata('msgr','Сообшение не удалено');
			endif;
			redirect($_SERVER['HTTP_REFERER']);
		else:
			show_404();
		endif;
	}
	
	/**************************************************** functions ******************************************************/	
	
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