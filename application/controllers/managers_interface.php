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
		
		$pagevar = array(
					'description'	=> '',
					'author'		=> '',
					'title'			=> 'Кабинет Менеджера | Панель управления',
					'baseurl' 		=> base_url(),
					'loginstatus'	=> $this->loginstatus['status'],
					'userinfo'		=> $this->user,
					'cntunit'		=> array(),
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
		
		$pagevar['cntunit']['platforms'] = $this->mdplatforms->count_records_by_manager($this->user['uid']);
		$pagevar['cntunit']['mails'] = $this->mdmessages->count_records_by_recipient($this->user['uid'],$this->user['utype']);
		$pagevar['cntunit']['tickets'] = $this->mdtickets->count_records_by_recipient($this->user['uid']);
		
		$this->load->view("managers_interface/control-panel",$pagevar);
	}
	
	/******************************************************** other ******************************************************/	
	
	public function actions_cabinet(){
		
		$pagevar = array(
					'description'	=> '',
					'author'		=> '',
					'title'			=> 'Кабинет Менеджера | Личный кабинет',
					'baseurl' 		=> base_url(),
					'userinfo'		=> $this->user,
					'msgs'			=> $this->session->userdata('msgs'),
					'msgr'			=> $this->session->userdata('msgr')
			);
		$this->session->unset_userdata('msgs');
		$this->session->unset_userdata('msgr');
		$this->load->view("managers_interface/admin-cabinet",$pagevar);
	}
	
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
	
	/******************************************************** platforms ******************************************************/	
	
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
		
		if($this->loginstatus['status']):
			if($this->user['utype'] == 1):
				$userdata = $this->mdunion->read_user_webmaster($this->user['uid']);
				$pagevar['userinfo']['balance'] = $userdata['balance'];
				$pagevar['userinfo']['torders'] = $userdata['torders'];
				$pagevar['userinfo']['uporders'] = $userdata['uporders'];
				unset($userdata);
			endif;
		endif;
		
		$pagevar['cntunit']['platforms'] = $this->mdplatforms->count_records_by_manager($this->user['uid']);
		$pagevar['cntunit']['mails'] = $this->mdmessages->count_records_by_recipient($this->user['uid'],$this->user['utype']);
		$pagevar['cntunit']['tickets'] = $this->mdtickets->count_records_by_recipient($this->user['uid']);
		
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
					'msginfo'		=> '<span class="alert-attention">Внимание!</span> Перед добавлением площадки убедитесь в наличии всех бирж в каталоге. Если необходимая биржа отсутствует в каталоге - обратитесь к администрации. Доступ к администрации сайта осуществляется через интерфейс технической поддержки.',
					'msgs'			=> $this->session->userdata('msgs'),
					'msgr'			=> $this->session->userdata('msgr')
			);
		$this->session->unset_userdata('msgs');
		$this->session->unset_userdata('msgr');
		
		$pagevar['mymarkets'] = $this->mdmkplatform->read_records_by_platform($platform,$pagevar['platform']['webmaster']);
		
		$this->load->view("managers_interface/control-view-platform",$pagevar);
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