<?php if(!defined('BASEPATH')) exit('No direct script access allowed');

class Users_interface extends CI_Controller{
	
	function __construct(){
		
		parent::__construct();
		$this->load->model('mdusers');
		$this->load->model('mdunion');
		$this->load->model('mdmarkets');
		$this->load->model('mdratings');
		$this->load->model('mdlog');
		
		$cookieuid = $this->session->userdata('logon');
		if(isset($cookieuid) and !empty($cookieuid)):
			$uid = $this->session->userdata('userid');
			if($uid):
				$userinfo = $this->mdusers->read_record($uid);
				if($userinfo):
					switch ($this->uri->uri_string()):
						case '' : $this->access_cabinet($userinfo['id'],$userinfo['type']); break;
						case 'webmasters' : $this->access_cabinet($userinfo['id'],$userinfo['type']); break;
						case 'users/registering/webmaster' : $this->access_cabinet($userinfo['id'],$userinfo['type']); break;
						case 'optimizers' : $this->access_cabinet($userinfo['id'],$userinfo['type']); break;
						case 'users/registering/optimizer' : $this->access_cabinet($userinfo['id'],$userinfo['type']); break;
						case 'users/restore-password' : $this->access_cabinet($userinfo['id'],$userinfo['type']); break;
					endswitch;
				endif;
			endif;
		endif;
	}
	
	public function index(){
		
		$pagevar = array(
			'title'			=> 'Быстропост - система автоматической монетизации',
			'description'	=> '',
			'author'		=> '',
			'baseurl' 		=> base_url(),
			'msgauth'		=> $this->session->userdata('msgauth')
		);
		$this->session->unset_userdata('msgauth');
		$this->load->model('mdplatforms');
		
		$this->load->view("users_interface/index",$pagevar);
	}
	
	public function loginin(){
	
		if(isset($_POST['submit_x'])):
			if(!$_POST['login'] || !$_POST['password']):
				$this->session->set_userdata('msgauth','Не заполены необходимые поля');
				redirect($_SERVER['HTTP_REFERER']);
			else:
				$user = $this->mdusers->auth_user($_POST['login'],$_POST['password']);
				if(!$user):
					$this->session->set_userdata('msgauth','Не верные данные для авторизации');
					redirect($_SERVER['HTTP_REFERER']);
				else:
					if($user['type'] == 4 || $user['type'] == 3):
						$this->session->set_userdata('msgauth','Авторизация запрещена!');
						redirect($_SERVER['HTTP_REFERER']);
					endif;
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
			'title'			=> 'Быстропост - система автоматической монетизации | Восстановление пароля',
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
		
		if(isset($_POST['rsubmit'])):
			$_POST['rsubmit'] == NULL;
			$this->form_validation->set_rules('email',' ','required|valid_email|trim');
			if(!$this->form_validation->run()):
				$this->session->set_userdata('msgr','Ошибка не верно заполнены необходимые поля.');
			else:
				$user = $this->mdusers->read_email_record($_POST['email']);
				if(!$user):
					$this->session->set_userdata('msgr','Указаныый E-mail не зарегистрирован!');
					redirect('users/restore-password');
				else:
					ob_start();
					?>
					<p><strong>Здравствуйте,  <?=$user['fio'];?></strong></p>
					<p>Вами был произведен запрос на восстановления данных для аторизации:</p>
					<p><strong>Логин: <span style="font-size: 16px;"><?=$user['login'];?></span><br/>Пароль: <span style="font-size: 16px;"><?=$this->encrypt->decode($user['cryptpassword']);?></span></strong></p>
					<p>Спасибо что пользуетесь нашими услугами!</p> 
					<?
					$mailtext = ob_get_clean();
					
					$this->email->clear(TRUE);
					$config['smtp_host'] = 'localhost';
					$config['charset'] = 'utf-8';
					$config['wordwrap'] = TRUE;
					$config['mailtype'] = 'html';
					
					$this->email->initialize($config);
					$this->email->to($user['login']);
					$this->email->from('robot@bystropost.ru','Быстропост - система автоматической монетизации');
					$this->email->bcc('');
					$this->email->subject('Данные для доступа к профилю');
					$this->email->message($mailtext);	
					$this->email->send();
					$this->session->set_userdata('ressuc',TRUE);
					$this->mdlog->insert_record($user['id'],'Событие №3: Процедура восстановления пароля');
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
			'title'			=> 'Быстропост - система автоматической монетизации | Восстановление пароля',
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
	
	public function markets_catalog(){
		
		$pagevar = array(
			'title'			=> 'Быстропост - система автоматической монетизации | Каталог бирж | ',
			'description'	=> '',
			'author'		=> '',
			'baseurl' 		=> base_url(),
			'msgauth'		=> $this->session->userdata('msgauth')
		);
		$this->session->unset_userdata('msgauth');
		switch ($this->uri->segment(2)):
			case 'gogetlinks' : $pagevar['title'] .='gogetlinks'; $this->load->view("users_interface/markets/market-gogetlinks",$pagevar); break;
			case 'miralinks' : $pagevar['title'] .='miralinks'; $this->load->view("users_interface/markets/market-miralinks",$pagevar); break;
			case 'getgoodlinks' : $pagevar['title'] .='getgoodlinks'; $this->load->view("users_interface/markets/market-getgoodlinks",$pagevar); break;
			case 'blogocash' : $pagevar['title'] .='blogcash.ru'; $this->load->view("users_interface/markets/market-blogcash",$pagevar); break;
			case 'prsape' : $pagevar['title'] .='pr.sape.ru'; $this->load->view("users_interface/markets/market-prsaperu",$pagevar); break;
			case 'blogun' : $pagevar['title'] .='blogun.ru'; $this->load->view("users_interface/markets/market-blogun",$pagevar); break;
			case 'rotapost' : $pagevar['title'] .='rotapost.ru'; $this->load->view("users_interface/markets/market-rotapost",$pagevar); break;
			default : redirect('/');
		endswitch;
	}
	
	public function users_ratings(){
		
		switch($this->uri->segment(2)):
			case 'advertisers' 	: $rtype = 1; break;
			case 'webmasters' 	: $rtype = 2; break;
			default 			: redirect('/');
		endswitch;
		
		$pagevar = array(
			'title'			=> 'Быстропост - система автоматической монетизации | Отзывы о системе',
			'description'	=> '',
			'author'		=> '',
			'ratings'		=> $this->mdratings->read_records($rtype),
			'baseurl' 		=> base_url(),
			'msgauth'		=> $this->session->userdata('msgauth')
		);
		$this->session->unset_userdata('msgauth');
		$this->load->view("users_interface/users-ratings",$pagevar);
	}
	
	public function reading_rating(){
		
		$pagevar = array(
			'title'			=> 'Быстропост - система автоматической монетизации | Отзывы о системе',
			'description'	=> '',
			'author'		=> '',
			'rating'		=> $this->mdratings->read_record($this->uri->segment(4)),
			'baseurl' 		=> base_url(),
			'msgauth'		=> $this->session->userdata('msgauth')
		);
		$this->session->unset_userdata('msgauth');
		$this->load->view("users_interface/reading-rating",$pagevar);
	}
	
	public function idea(){
		
		$pagevar = array(
			'title'			=> 'Быстропост - система автоматической монетизации | Ваши идеи',
			'description'	=> '',
			'author'		=> '',
			'rating'		=> $this->mdratings->read_record($this->uri->segment(4)),
			'baseurl' 		=> base_url(),
			'msgauth'		=> $this->session->userdata('msgauth')
		);
		$this->session->unset_userdata('msgauth');
		$this->load->view("users_interface/idea",$pagevar);
	}
	
	public function about(){
		
		$pagevar = array(
			'title'			=> 'Быстропост - система автоматической монетизации | О проекте',
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
			'title'			=> 'Быстропост - система автоматической монетизации | Вебмастера',
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
			'title'			=> 'Быстропост - система автоматической монетизации | Оптимизаторам',
			'description'	=> '',
			'author'		=> '',
			'baseurl' 		=> base_url(),
			'msgauth'		=> $this->session->userdata('msgauth')
		);
		$this->session->unset_userdata('msgauth');
		$this->load->view("users_interface/optimizers",$pagevar);
	}
	
	public function faq(){
		
		$pagevar = array(
			'title'			=> 'Быстропост - система автоматической монетизации | Поддержка',
			'description'	=> '',
			'author'		=> '',
			'baseurl' 		=> base_url(),
			'msgauth'		=> $this->session->userdata('msgauth')
		);
		$this->session->unset_userdata('msgauth');
		$this->load->view("users_interface/faq",$pagevar);
	}
	
	public function manner_payment(){
		
		$pagevar = array(
			'title'			=> 'Быстропост - система автоматической монетизации | Порядок оплаты',
			'description'	=> '',
			'author'		=> '',
			'baseurl' 		=> base_url(),
			'msgauth'		=> $this->session->userdata('msgauth')
		);
		$this->session->unset_userdata('msgauth');
		$this->load->view("users_interface/manner-payment",$pagevar);
	}
	
	public function site_monetization(){
		
		$pagevar = array(
			'title'			=> 'Быстропост - система автоматической монетизации | Монетизация сайта',
			'description'	=> '',
			'author'		=> '',
			'baseurl' 		=> base_url(),
			'msgauth'		=> $this->session->userdata('msgauth')
		);
		$this->session->unset_userdata('msgauth');
		$this->load->view("users_interface/site-monetization",$pagevar);
	}
	
	public function additional_services(){
		
		$pagevar = array(
			'title'			=> 'Быстропост - система автоматической монетизации | Дополнительные услуги',
			'description'	=> '',
			'author'		=> '',
			'baseurl' 		=> base_url(),
			'msgauth'		=> $this->session->userdata('msgauth')
		);
		$this->session->unset_userdata('msgauth');
		$this->load->view("users_interface/additional-services",$pagevar);
	}
	
	public function disclaimer(){
		
		$pagevar = array(
			'title'			=> 'Быстропост - система автоматической монетизации | Уведомление об ответственности',
			'description'	=> '',
			'author'		=> '',
			'baseurl' 		=> base_url(),
			'msgauth'		=> $this->session->userdata('msgauth')
		);
		$this->session->unset_userdata('msgauth');
		$this->load->view("users_interface/disclaimer",$pagevar);
	}
	
	public function about_content(){
		
		$pagevar = array(
			'title'			=> 'Быстропост - система автоматической монетизации | О контенте',
			'description'	=> '',
			'author'		=> '',
			'baseurl' 		=> base_url(),
			'msgauth'		=> $this->session->userdata('msgauth')
		);
		$this->session->unset_userdata('msgauth');
		$this->load->view("users_interface/about-content",$pagevar);
	}
	
	public function capabilities(){
		
		$pagevar = array(
			'title'			=> 'Быстропост - система автоматической монетизации | Наши возможности',
			'description'	=> '',
			'author'		=> '',
			'baseurl' 		=> base_url(),
			'msgauth'		=> $this->session->userdata('msgauth')
		);
		$this->session->unset_userdata('msgauth');
		$this->load->view("users_interface/capabilities",$pagevar);
	}
	
	public function site_interface(){
		
		$pagevar = array(
			'title'			=> 'Быстропост - система автоматической монетизации | Интерфейс',
			'description'	=> '',
			'author'		=> '',
			'baseurl' 		=> base_url(),
			'msgauth'		=> $this->session->userdata('msgauth')
		);
		$this->session->unset_userdata('msgauth');
		$this->load->view("users_interface/interface",$pagevar);
	}
	
	public function news(){
		
		$pagevar = array(
			'title'			=> 'Быстропост - система автоматической монетизации | Новости',
			'description'	=> '',
			'author'		=> '',
			'baseurl' 		=> base_url(),
			'msgauth'		=> $this->session->userdata('msgauth')
		);
		$this->session->unset_userdata('msgauth');
		$this->load->view("users_interface/news",$pagevar);
	}
	
	public function contacts(){
		
		$pagevar = array(
			'title'			=> 'Быстропост - система автоматической монетизации | Контакты',
			'description'	=> '',
			'author'		=> '',
			'baseurl' 		=> base_url(),
			'msgauth'		=> $this->session->userdata('msgauth')
		);
		$this->session->unset_userdata('msgauth');
		$this->load->view("users_interface/contacts",$pagevar);
	}
	
	public function prices(){
		
		$pagevar = array(
			'title'			=> 'Быстропост - система автоматической монетизации | Цены',
			'description'	=> '',
			'author'		=> '',
			'baseurl' 		=> base_url(),
			'msgauth'		=> $this->session->userdata('msgauth')
		);
		$this->session->unset_userdata('msgauth');
		$this->load->view("users_interface/prices",$pagevar);
	}
	
	public function forum(){
		
		$pagevar = array(
			'title'			=> 'Быстропост - система автоматической монетизации | Форум',
			'description'	=> '',
			'author'		=> '',
			'baseurl' 		=> base_url(),
			'msgauth'		=> $this->session->userdata('msgauth')
		);
		$this->session->unset_userdata('msgauth');
		$this->load->view("users_interface/forum",$pagevar);
	}
	
	public function site_map(){
		
		$pagevar = array(
			'title'			=> 'Быстропост - система автоматической монетизации | Карта сайта',
			'description'	=> '',
			'author'		=> '',
			'baseurl' 		=> base_url(),
			'msgauth'		=> $this->session->userdata('msgauth')
		);
		$this->session->unset_userdata('msgauth');
		$this->load->view("users_interface/site-map",$pagevar);
	}
	
	public function access_cabinet($uid,$utype){
		
		if(!$uid || !$utype):
			show_404();
		endif;
		switch ($utype):
			case 1 : redirect('webmaster-panel/actions/control');break;
			case 2 : redirect('manager-panel/actions/control');break;
			case 3 : redirect('optimizator-panel/actions/control');break;
			case 4 : show_404();break;
			case 5 : redirect('admin-panel/management/users/all');break;
			default: show_404(); break;
		endswitch;
	}
	
	public function registering(){
		
		$redirect = $this->uri->segment(3).'s';
		
		if(isset($_SERVER['HTTP_REFERER'])):
			$redirect = $_SERVER['HTTP_REFERER'];
		endif;
		
		$usertype = $this->uri->segment(3);
		switch ($usertype):
			case 'webmaster': $tutype = 'вебмастера';$utype = 1; break;
			case 'optimizer': 	redirect($redirect);break;
//								$tutype = 'оптимизатора';$utype = 3; break;
			default			: redirect($redirect);break;
		endswitch;
		
		$pagevar = array(
				'title'			=> 'Быстропост - система автоматической монетизации | Регистрация пользователей',
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
			$this->form_validation->set_rules('wmid',' ','required|numeric|exact_length[12]|trim');
			$this->form_validation->set_rules('knowus',' ','trim');
			$this->form_validation->set_rules('promo',' ','trim');
			if(!$this->form_validation->run()):
				$this->session->set_userdata('msgr','Ошибка. Неверно заполены необходимые поля<br/>');
				redirect($this->uri->uri_string());
			else:
				if($this->mdusers->user_exist('login ',$_POST['login'])):
					$this->session->set_userdata('msgr','Ошибка. Ваш E-mail уже зарегистрирован!');
					redirect($this->uri->uri_string());
				endif;
				if($_POST['password']!=$_POST['confpass']):
					$this->session->set_userdata('msgr','Ошибка. Пароли не совпадают.');
					redirect($this->uri->uri_string());
				endif;
				if($this->mdusers->read_by_wmid($_POST['wmid'])):
					$this->session->set_userdata('msgr','Ошибка. WMID уже зарегистрирован!');
					redirect($this->uri->uri_string());
				endif;
				if(!isset($_POST['sendmail'])):
					$_POST['sendmail'] = 0;
				endif;
				$uid = $this->mdusers->insert_record($_POST,$utype);
				if($utype == 1):
					$this->mdlog->insert_record($uid,'Событие №1: Процедура регистрации вебмастера');
					if(intval($_POST['promo']) == 9846980):
						$this->mdusers->update_field($uid,'manager',0);
					else:
						$this->mdusers->update_field($uid,'manager',2);
						$param = 'user='.$_POST['fio'].'&email='.$_POST['login'];
						$remote_user = $this->API('AddNewUser',$param);
						if($remote_user['id']):
							$this->mdusers->update_field($uid,'remoteid',$remote_user['id']);
						endif;
					endif;
				elseif($utype == 3):
					$this->mdlog->insert_record($uid,'Событие №2: Процедура регистрации оптимизатора');
				endif;
				ob_start();
				?>
				<p><strong>Здравствуйте, <?=$_POST['fio'];?></strong></p>
				<p>Поздравляем! Вы успешно зарегистрировались в статусе вебмастера. Ваша работа будет осуществляться через личный кабинет пользователя. Для входа в личный кабинет используйте логин и пароль указанными при регистрации.</p>
				<p>Ваш логин: <?=$_POST['login'];?></p>
				<p>Ваш пароль: <?=$_POST['password'];?></p>
				<p>Авторизация в личный кабинет, осуществляется с главной страницы сайта.</p>
				<p><br/><br/>Желаем Вам удачи!</p>
				<p>С Уважением, Администрация сервиса www.BystroPost.ru</p> 
				<?
				$mailtext = ob_get_clean();
				
				$this->email->clear(TRUE);
				$config['smtp_host'] = 'localhost';
				$config['charset'] = 'utf-8';
				$config['wordwrap'] = TRUE;
				$config['mailtype'] = 'html';
				
				$this->email->initialize($config);
				$this->email->to($_POST['login']);
				$this->email->from('robot@bystropost.ru','Быстропост - система автоматической монетизации');
				$this->email->bcc('');
				$this->email->subject('Регистрация на Bystropost.ru');
				$this->email->message($mailtext);	
				$this->email->send();
			
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

	function viewimage(){
		
		$section = $this->uri->segment(1);
		$id = $this->uri->segment(3);
		switch ($section):
			case 'ratings'	:	$image = $this->mdratings->get_image($id); break;
			default			: 	show_404();break;
		endswitch;
		header('Content-type: image/gif');
		echo $image;
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
}