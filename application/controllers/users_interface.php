<?php if(!defined('BASEPATH')) exit('No direct script access allowed');

class Users_interface extends CI_Controller{
	
	var $user = array('uid'=>0,'uname'=>'','ulogin'=>'','utype'=>'');
	var $loginstatus = array('status'=>FALSE);
	var $months = array("01"=>"января","02"=>"февраля","03"=>"марта","04"=>"апреля","05"=>"мая","06"=>"июня","07"=>"июля","08"=>"августа","09"=>"сентября","10"=>"октября","11"=>"ноября","12"=>"декабря");
	
	function __construct(){
		
		parent::__construct();
		$this->load->model('mdusers');
		
		$cookieuid = $this->session->userdata('logon');
		if(isset($cookieuid) and !empty($cookieuid)):
			$this->user['uid'] = $this->session->userdata('userid');
			if($this->user['uid']):
				$userinfo = $this->mdusers->read_record($this->user['uid']);
				if($userinfo):
					$this->user['ulogin'] 			= $userinfo['login'];
					$this->user['uname'] 			= $userinfo['fio'];
					$this->user['utype'] 			= $userinfo['type'];
					$this->loginstatus['status'] 	= TRUE;
				endif;
			endif;
			if($this->session->userdata('logon') != md5($userinfo['login'].$userinfo['password'])):
				$this->loginstatus['status'] = FALSE;
				$this->user = array();
			endif;
		endif;
	}
	
	public function index(){
		
		$pagevar = array(
			'title'			=> 'Bystropost.ru - Система управления продажами',
			'description'	=> '',
			'author'		=> '',
			'baseurl' 		=> base_url(),
			'loginstatus'	=> $this->loginstatus['status'],
			'userinfo'		=> $this->user,
			'msgauth'		=> $this->session->userdata('msgauth')
		);
		$this->session->unset_userdata('msgauth');
		if($this->input->post('submit_x')):
			$login = trim($this->input->post('login'));
			$pass = trim($this->input->post('password'));
			if(!$login || !$pass):
				$this->session->set_userdata('msgauth','Не заполены необходимые поля');
				redirect($_SERVER['HTTP_REFERER']);
			else:
				$user = $this->mdusers->auth_user($login,$pass);
				if(!$user):
					$this->session->set_userdata('msgauth','Не верные данные для авторизации');
				else:
					$this->session->set_userdata(array('logon'=>md5($user['login'].$user['password']),'userid'=>$user['id']));
				endif;
				redirect($_SERVER['HTTP_REFERER']);
			endif;
		endif;
		$this->load->view("users_interface/index",$pagevar);
	}
	
	public function about(){
		
		$pagevar = array(
			'title'			=> 'Bystropost.ru - Система управления продажами | О проекте',
			'description'	=> '',
			'author'		=> '',
			'baseurl' 		=> base_url(),
			'loginstatus'	=> $this->loginstatus['status'],
			'userinfo'		=> $this->user,
			'msgauth'		=> $this->session->userdata('msgauth')
		);
		$this->session->unset_userdata('msgauth');
		
		$this->load->view("users_interface/about",$pagevar);
	}
	
	public function webmasters(){
		
		$pagevar = array(
			'title'			=> 'Bystropost.ru - Система управления продажами | Вебмастера',
			'description'	=> '',
			'author'		=> '',
			'baseurl' 		=> base_url(),
			'loginstatus'	=> $this->loginstatus['status'],
			'userinfo'		=> $this->user,
			'msgauth'		=> $this->session->userdata('msgauth')
		);
		$this->session->unset_userdata('msgauth');
		
		$this->load->view("users_interface/webmasters",$pagevar);
	}
	
	public function optimizers(){
		
		$pagevar = array(
			'title'			=> 'Bystropost.ru - Система управления продажами | Оптимизаторам',
			'description'	=> '',
			'author'		=> '',
			'baseurl' 		=> base_url(),
			'loginstatus'	=> $this->loginstatus['status'],
			'userinfo'		=> $this->user,
			'msgauth'		=> $this->session->userdata('msgauth')
		);
		$this->session->unset_userdata('msgauth');
		
		$this->load->view("users_interface/optimizers",$pagevar);
	}
	
	public function regulations(){
		
		$pagevar = array(
			'title'			=> 'Bystropost.ru - Система управления продажами | Правила',
			'description'	=> '',
			'author'		=> '',
			'baseurl' 		=> base_url(),
			'loginstatus'	=> $this->loginstatus['status'],
			'userinfo'		=> $this->user,
			'msgauth'		=> $this->session->userdata('msgauth')
		);
		$this->session->unset_userdata('msgauth');
		
		$this->load->view("users_interface/regulations",$pagevar);
	}
	
	public function support(){
		
		$pagevar = array(
			'title'			=> 'Bystropost.ru - Система управления продажами | Поддержка',
			'description'	=> '',
			'author'		=> '',
			'baseurl' 		=> base_url(),
			'loginstatus'	=> $this->loginstatus['status'],
			'userinfo'		=> $this->user,
			'msgauth'		=> $this->session->userdata('msgauth')
		);
		$this->session->unset_userdata('msgauth');
		
		$this->load->view("users_interface/support",$pagevar);
	}
	
	public function faq(){
		
		$pagevar = array(
			'title'			=> 'Bystropost.ru - Система управления продажами | Поддержка',
			'description'	=> '',
			'author'		=> '',
			'baseurl' 		=> base_url(),
			'loginstatus'	=> $this->loginstatus['status'],
			'userinfo'		=> $this->user,
			'msgauth'		=> $this->session->userdata('msgauth')
		);
		$this->session->unset_userdata('msgauth');
		
		$this->load->view("users_interface/faq",$pagevar);
	}
	
	public function access_cabinet(){
		
		if(!$this->user['uid']):
			show_404();
		endif;
		
		switch ($this->user['utype']):
			case 1 : 	redirect('');
						break;
			case 2 : 	redirect('');
						break;
			case 3 : 	redirect('');
						break;
			case 4 : 	redirect('');
						break;
			case 5 : 	redirect('admin-panel/actions/control');
						break;
			default: 	show_404(); break;
		endswitch;
	}
	
	public function registering(){
		
		$pagevar = array(
			'title'			=> 'Bystropost.ru - Система управления продажами | Регистрация пользователей',
			'description'	=> '',
			'author'		=> '',
			'baseurl' 		=> base_url(),
			'loginstatus'	=> $this->loginstatus['status'],
			'userinfo'		=> $this->user,
			'msgs'			=> $this->session->userdata('msgs'),
			'msgr'			=> $this->session->userdata('msgr')
		);
		$this->session->unset_userdata('msgs');
		$this->session->unset_userdata('msgr');
		if($pagevar['loginstatus']):
			redirect('');
		endif;
		if($this->input->post('submit')):
			$_POST['submit'] = NULL;
			$this->form_validation->set_rules('fio',' ','required|trim');
			$this->form_validation->set_rules('login',' ','required|valid_email|trim');
			$this->form_validation->set_rules('password',' ','required|trim');
			$this->form_validation->set_rules('confpass',' ','required|trim');
			$this->form_validation->set_rules('wmid',' ','required|trim');
			$this->form_validation->set_rules('knowus',' ','trim');
			if(!$this->form_validation->run()):
				$this->session->set_userdata('msgr','Ошибка. Неверно заполены необходимые поля<br/>');
				redirect($this->uri->uri_string());
			else:
				if($this->mdusers->user_exist('login ',$_POST['login'])):
					$this->session->set_userdata('msgr','Ошибка. Ваш E-mail уже зареристрирован!');
					redirect($this->uri->uri_string());
				endif;
				if($_POST['password']!=$_POST['confpass']):
					$this->session->set_userdata('msgr','Ошибка. Пароли не совпадают.');
					redirect($this->uri->uri_string());
				endif;
				if(!isset($_POST['sendmail'])):
					$_POST['sendmail'] = 0;
				endif;
				
				$this->mdusers->insert_record($_POST);
				
				$user = $this->mdusers->auth_user($_POST['login'],$_POST['password']);
				if(!$user):
					redirect('');
				endif;
				$this->session->set_userdata(array('logon'=>md5($user['login'].$user['password']),'userid'=>$user['id']));
				$this->session->set_userdata('regsuc',TRUE);
				redirect('users/registering/successfull');
			endif;
		endif;
		$this->load->view("users_interface/registering",$pagevar);
	}
	
	public function reg_successfull(){
		
		if(!$this->session->userdata('regsuc')):
			show_404();
		endif;
		
		$pagevar = array(
			'title'			=> 'Bystropost.ru - Система управления продажами | Регистрация завершена',
			'description'	=> '',
			'author'		=> '',
			'baseurl' 		=> base_url(),
			'loginstatus'	=> $this->loginstatus['status'],
			'userinfo'		=> $this->user,
			'msgs'			=> $this->session->userdata('msgs'),
			'msgr'			=> $this->session->userdata('msgr')
		);
		$this->session->unset_userdata('msgs');
		$this->session->unset_userdata('msgr');
		$this->session->unset_userdata('regsuc');
		
		$this->load->view("users_interface/registering-successfull",$pagevar);
	}
	
	public function contacts(){
		
		$pagevar = array(
					'description'	=> '',
					'author'		=> '',
					'title'			=> '',
					'baseurl' 		=> base_url(),
					'loginstatus'	=> $this->loginstatus['status'],
					'userinfo'		=> $this->user,
					'msgs'			=> $this->session->userdata('msgs'),
					'msgr'			=> $this->session->userdata('msgr'),
			);
		$this->session->unset_userdata('msgs');
		$this->session->unset_userdata('msgr');
		
		if($this->input->post('submit')):
			$_POST['submit'] = NULL;
			$this->form_validation->set_rules('name',' ','required|trim');
			$this->form_validation->set_rules('email',' ','required|valid_email|trim');
			$this->form_validation->set_rules('phone',' ','required|trim');
			$this->form_validation->set_rules('text',' ','required|trim');
			if(!$this->form_validation->run()):
				$this->session->set_userdata('msgr','Ошибка. Повторите ввод.');
			else:
				ob_start();
				?>
				<p>Сообщение от <?=$_POST['name'];?></p>
				<p>Email <?=$_POST['email'];?></p>
				<p>Телефон <?=$_POST['phone'];?></p>
				<p>
					<?=$_POST['text'];?>
				</p>
				<?
				$mailtext = ob_get_clean();
				
				$this->email->clear(TRUE);
				$config['smtp_host'] = 'localhost';
				$config['charset'] = 'utf-8';
				$config['wordwrap'] = TRUE;
				$config['mailtype'] = 'html';
				
				$this->email->initialize($config);
				$this->email->to('info@newyork-bar.ru');
				$this->email->from($_POST['email'],$_POST['name']);
				$this->email->bcc('');
				$this->email->subject('Форма обратной связи Newyork Bar');
				$this->email->message($mailtext);	
				$this->email->send();
			endif;
			redirect($this->uri->uri_string());
		endif;
		
		$this->load->view("users_interface/contacts",$pagevar);
	}
	
	public function logoff(){
		
		$this->session->sess_destroy();
		redirect('');
	}	
}