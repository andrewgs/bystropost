<?php if(!defined('BASEPATH')) exit('No direct script access allowed');

class Users_interface extends CI_Controller{
	
	function __construct(){
		
		parent::__construct();
		$this->load->model('mdusers');
		$this->load->model('mdunion');
		$this->load->model('mdmarkets');
		
		$cookieuid = $this->session->userdata('logon');
		if(isset($cookieuid) and !empty($cookieuid)):
			$uid = $this->session->userdata('userid');
			if($uid):
				$userinfo = $this->mdusers->read_record($uid);
				if($userinfo):
					$this->access_cabinet($userinfo['id'],$userinfo['type']);
				endif;
			endif;
		endif;
	}
	
	public function index(){
		
		$pagevar = array(
			'title'			=> 'Bystropost.ru - Система управления продажами',
			'description'	=> '',
			'author'		=> '',
			'baseurl' 		=> base_url(),
			'msgauth'		=> $this->session->userdata('msgauth')
		);
		$this->session->unset_userdata('msgauth');
		$this->load->view("users_interface/index",$pagevar);
	}
	
	public function loginin(){
		
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
					redirect($_SERVER['HTTP_REFERER']);
				else:
					$this->session->set_userdata(array('logon'=>md5($user['login'].$user['password']),'userid'=>$user['id']));
					$this->mdusers->update_field($user['id'],'lastlogin',date("Y-m-d"));
					$this->access_cabinet($user['id'],$user['type']);
				endif;
			endif;
		endif;
		show_404();
	}
	
	public function about(){
		
		$pagevar = array(
			'title'			=> 'Bystropost.ru - Система управления продажами | О проекте',
			'description'	=> '',
			'author'		=> '',
			'baseurl' 		=> base_url(),
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
			'msgauth'		=> $this->session->userdata('msgauth')
		);
		$this->session->unset_userdata('msgauth');
		$this->load->view("users_interface/faq",$pagevar);
	}
	
	public function access_cabinet($uid,$utype){
		
		if(!$uid || !$utype):
			show_404();
		endif;
		
		switch ($utype):
			case 1 : redirect('webmaster-panel/actions/control');break;
			case 2 : redirect('manager-panel/actions/control');break;
			case 3 : redirect('');break;
			case 4 : redirect('');break;
			case 5 : redirect('admin-panel/management/users/all');break;
			default: show_404(); break;
		endswitch;
	}
	
	public function registering(){
		
		$usertype = $this->uri->segment(3);
		switch ($usertype):
			case 'webmaster': $tutype = 'вебмастера';$utype = 1; break;
			case 'optimizer': $tutype = 'оптимизатора';$utype = 3; break;
			default			: redirect($_SERVER['HTTP_REFERER']);break;
		endswitch;
		
		$pagevar = array(
				'title'			=> 'Bystropost.ru - Система управления продажами | Регистрация пользователей',
				'description'	=> '',
				'author'		=> '',
				'baseurl' 		=> base_url(),
				'usertype'		=> $tutype,
				'msgs'			=> $this->session->userdata('msgs'),
				'msgr'			=> $this->session->userdata('msgr'),
				'msgauth'		=> $this->session->userdata('msgauth')
		);
		$this->session->unset_userdata('msgauth');
		$this->session->unset_userdata('msgs');
		$this->session->unset_userdata('msgr');
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
				
				$this->mdusers->insert_record($_POST,$utype);
				$user = $this->mdusers->auth_user($_POST['login'],$_POST['password']);
				if(!$user):
					redirect('');
				endif;
				$this->session->set_userdata(array('logon'=>md5($user['login'].$user['password']),'userid'=>$user['id']));
				$this->session->set_userdata('regsuc',TRUE);
				$this->access_cabinet($user['id'],$user['type']);
			endif;
		endif;
		$this->load->view("users_interface/registering",$pagevar);
	}
}