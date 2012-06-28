<?php if(!defined('BASEPATH')) exit('No direct script access allowed');

class Clients_interface extends CI_Controller{
	
	var $user = array('uid'=>0,'uname'=>'','ulogin'=>'','utype'=>'');
	var $loginstatus = array('status'=>FALSE);
	var $months = array("01"=>"января","02"=>"февраля","03"=>"марта","04"=>"апреля","05"=>"мая","06"=>"июня","07"=>"июля","08"=>"августа","09"=>"сентября","10"=>"октября","11"=>"ноября","12"=>"декабря");
	
	function __construct(){
		
		parent::__construct();
		$this->load->model('mdusers');
		$this->load->model('mdunion');
		$this->load->model('mdmessages');
		
		$cookieuid = $this->session->userdata('logon');
		if(isset($cookieuid) and !empty($cookieuid)):
			$this->user['uid'] = $this->session->userdata('userid');
			if($this->user['uid']):
				$userinfo = $this->mdusers->read_record($this->user['uid']);
				if($userinfo['type'] == 1):
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
					'title'			=> 'Кабинет Вебмастера | Панель управления',
					'baseurl' 		=> base_url(),
					'loginstatus'	=> $this->loginstatus['status'],
					'userinfo'		=> $this->user,
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
		
		$this->load->view("clients_interface/control-panel",$pagevar);
	}
	
	public function control_platforms(){
		
		$pagevar = array(
					'description'	=> '',
					'author'		=> '',
					'title'			=> 'Кабинет Вебмастера | Управление площадками',
					'baseurl' 		=> base_url(),
					'loginstatus'	=> $this->loginstatus['status'],
					'userinfo'		=> $this->user,
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
		
		$this->load->view("clients_interface/control-platforms",$pagevar);
	}
	
	public function control_add_platform(){
		
		$pagevar = array(
					'description'	=> '',
					'author'		=> '',
					'title'			=> 'Кабинет Вебмастера | Управление площадками | Добавление площадки',
					'baseurl' 		=> base_url(),
					'loginstatus'	=> $this->loginstatus['status'],
					'userinfo'		=> $this->user,
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
		
		$this->load->view("clients_interface/control-add-platform",$pagevar);
	}
	
	public function actions_cabinet(){
		
		$pagevar = array(
					'description'	=> '',
					'author'		=> '',
					'title'			=> 'Администрирование | Личный кабинет',
					'baseurl' 		=> base_url(),
					'userinfo'		=> $this->user,
					'msgs'			=> $this->session->userdata('msgs'),
					'msgr'			=> $this->session->userdata('msgr')
			);
		$this->session->unset_userdata('msgs');
		$this->session->unset_userdata('msgr');
		$this->load->view("admin_interface/admin-cabinet",$pagevar);
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