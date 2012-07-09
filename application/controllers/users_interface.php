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
	
	public function restore_password(){
		
		if($this->session->userdata('ressuc')):
			$this->restore_successfull();
			return FALSE;
		endif;
		
		$pagevar = array(
			'title'			=> 'Bystropost.ru - Система управления продажами | Восстановление пароля',
			'description'	=> '',
			'author'		=> '',
			'baseurl' 		=> base_url(),
			'msgauth'		=> $this->session->userdata('msgauth')
		);
		$this->session->unset_userdata('msgauth');
		if(isset($_POST['rsubmit'])):
			$_POST['rsubmit'] == NULL;
			$this->form_validation->set_rules('email',' ','required|valid_email|trim');
			if(!$this->form_validation->run()):
				$this->session->set_userdata('msgr','Ошибка не верно заполнены необходимые поля.');
			else:
				$user = $this->mdusers->read_email_record($_POST['email']);
				if(!$user):
					if(isset($_SERVER['HTTP_REFERER'])):
						redirect($_SERVER['HTTP_REFERER']);
					else:
						redirect('/');
					endif;
				else:
					ob_start();
					?>
					<p><strong>Здравствуйте,  <?=$user['fio'];?></strong></p>
					<p>Вами был произведен запрос на восстановления данных для аторизации:</p>
					<p><strong>Логин: <span style="font-size: 18px;"><?=$user['login'];?></span><br/>Пароль: <span style="font-size: 18px;"><?=$this->encrypt->decode($user['cryptpassword']);?></span></strong></p>
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
					$this->email->subject('Данные для доступа к профилю');
					$this->email->message($mailtext);	
					$this->email->send();
					$this->session->set_userdata('ressuc',TRUE);
				endif;
			endif;
			redirect($this->uri->uri_string());
		endif;
		
		$this->load->view("users_interface/restore-password",$pagevar);
	}
	
	public function restore_successfull(){
		
		if(!$this->session->userdata('ressuc')):
			show_404();
		endif;
		
		$pagevar = array(
			'title'			=> 'Bystropost.ru - Система управления продажами | Восстановление пароля',
			'description'	=> '',
			'author'		=> '',
			'baseurl' 		=> base_url(),
			'msgs'			=> $this->session->userdata('msgs'),
			'msgr'			=> $this->session->userdata('msgr'),
			'msgauth'		=> $this->session->userdata('msgauth')
		);
		$this->session->unset_userdata('msgauth');
		$this->session->unset_userdata('msgs');
		$this->session->unset_userdata('msgr');
		$this->session->unset_userdata('ressuc');
		
		$this->load->view("users_interface/successfull",$pagevar);
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