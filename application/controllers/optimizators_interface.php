<?php if(!defined('BASEPATH')) exit('No direct script access allowed');

class Optimizators_interface extends CI_Controller{
	
	var $user = array('uid'=>0,'uname'=>'','ulogin'=>'','utype'=>'','signdate'=>'','balance'=>0);
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
				if($userinfo['type'] == 3):
					$this->user['ulogin'] 			= $userinfo['login'];
					$this->user['uname'] 			= $userinfo['fio'];
					$this->user['utype'] 			= $userinfo['type'];
					$this->user['signdate'] 		= $userinfo['signdate'];
					$this->user['balance'] 			= $userinfo['balance'];
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
					'delivers'		=> $this->mdunion->delivers_works_manager($this->user['uid'],10,$from),
					'cntunit'		=> array(),
					'pages'			=> array(),
					'msgs'			=> $this->session->userdata('msgs'),
					'msgr'			=> $this->session->userdata('msgr')
			);
		$this->session->unset_userdata('msgs');
		$this->session->unset_userdata('msgr');
		
		for($i=0;$i<count($pagevar['delivers']);$i++):
			$pagevar['delivers'][$i]['date'] = $this->operation_dot_date($pagevar['delivers'][$i]['date']);
		endfor;
		
		$config['base_url'] 	= $pagevar['baseurl'].'manager-panel/actions/control/from/';
		$config['uri_segment'] 	= 5;
		$config['total_rows'] 	= $this->mdunion->count_delivers_works_manager($this->user['uid']);
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
			if(mb_strlen($pagevar['delivers'][$i]['ulrlink'],'UTF-8') > 25):
				$pagevar['delivers'][$i]['link'] = mb_substr($pagevar['delivers'][$i]['ulrlink'],0,25,'UTF-8');	
			endif;
		endfor;
		
		$pagevar['cntunit']['delivers']['paid'] = $this->mddelivesworks->count_records_by_manager_status($this->user['uid'],1);
		$pagevar['cntunit']['delivers']['notpaid'] = $this->mddelivesworks->count_records_by_manager_status($this->user['uid'],0);
		$pagevar['cntunit']['platforms'] = $this->mdplatforms->count_records_by_manager($this->user['uid']);
		$pagevar['cntunit']['mails']['new'] = $this->mdmessages->count_records_by_recipient_new($this->user['uid']);
		$pagevar['cntunit']['mails']['total'] = $this->mdmessages->count_records_by_recipient($this->user['uid'],$this->user['utype'],$this->user['signdate']);
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
				$wmid = $this->mdusers->read_by_wmid($_POST['wmid']);
				if($wmid && $wmid != $this->user['uid']):
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
		
		$pagevar['cntunit']['delivers']['paid'] = $this->mddelivesworks->count_records_by_manager_status($this->user['uid'],1);
		$pagevar['cntunit']['delivers']['notpaid'] = $this->mddelivesworks->count_records_by_manager_status($this->user['uid'],0);
		$pagevar['cntunit']['platforms'] = $this->mdplatforms->count_records_by_manager($this->user['uid']);
		$pagevar['cntunit']['mails']['new'] = $this->mdmessages->count_records_by_recipient_new($this->user['uid']);
		$pagevar['cntunit']['mails']['total'] = $this->mdmessages->count_records_by_recipient($this->user['uid'],$this->user['utype'],$this->user['signdate']);
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