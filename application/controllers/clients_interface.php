<?php if(!defined('BASEPATH')) exit('No direct script access allowed');

class Clients_interface extends CI_Controller{
	
	var $user = array('uid'=>0,'uname'=>'','ulogin'=>'','utype'=>'','lock'=>0,'signdate'=>'','balance'=>0);
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
		$this->load->model('mdservices');
		$this->load->model('mdattachedservices');
		
		$cookieuid = $this->session->userdata('logon');
		if(isset($cookieuid) and !empty($cookieuid)):
			$this->user['uid'] = $this->session->userdata('userid');
			if($this->user['uid']):
				$userinfo = $this->mdusers->read_record($this->user['uid']);
				if($userinfo['type'] == 1):
					$this->user['ulogin'] 			= $userinfo['login'];
					$this->user['uname'] 			= $userinfo['fio'];
					$this->user['utype'] 			= $userinfo['type'];
					$this->user['lock'] 			= $userinfo['locked'];
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
		
		if($this->session->userdata('regsuc')):
			$this->reg_successfull();
			return FALSE;
		endif;
		
		$pagevar = array(
					'description'	=> '',
					'author'		=> '',
					'title'			=> 'Кабинет Вебмастера | Панель управления',
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
		$pagevar['cntunit']['delivers']['notpaid'] = $this->mddelivesworks->count_records_by_webmaster_status($this->user['uid'],0);
		$pagevar['cntunit']['delivers']['total'] = $this->mddelivesworks->count_records_by_webmaster($this->user['uid']);
		$pagevar['cntunit']['platforms'] = $this->mdplatforms->count_records_by_webmaster($this->user['uid']);
		$pagevar['cntunit']['mails']['new'] = $this->mdmessages->count_records_by_recipient_new($this->user['uid']);
		$pagevar['cntunit']['mails']['total'] = $this->mdmessages->count_records_by_recipient($this->user['uid'],$this->user['utype'],$this->user['signdate']);
		$pagevar['cntunit']['tickets'] = $this->mdtickets->count_records_by_sender($this->user['uid']);
		
		$this->load->view("clients_interface/control-panel",$pagevar);
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
			'msgs'			=> $this->session->userdata('msgs'),
			'msgr'			=> $this->session->userdata('msgr'),
			'msgauth'		=> $this->session->userdata('msgauth')
		);
		$this->session->unset_userdata('msgauth');
		$this->session->unset_userdata('msgs');
		$this->session->unset_userdata('msgr');
		$this->session->unset_userdata('regsuc');
		
		$this->load->view("clients_interface/successfull",$pagevar);
	}
	
	public function control_profile(){
		
		$pagevar = array(
					'description'	=> '',
					'author'		=> '',
					'title'			=> 'Кабинет Вебмастера | Профиль',
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
				if(!$this->user['lock'] && isset($_POST['lockprofile'])):
					$result = $this->mdusers->update_field($this->user['uid'],'locked',1);
					if($result):
						$managers = $this->mdplatforms->read_managers_platform_online($this->user['uid']);
						$this->mdplatforms->platforms_status_offline($this->user['uid']);
						$msgs = 'Профиль заблокирован. Все площадки не активны.<br/>'.$this->session->userdata('msgs');
						$this->session->set_userdata('msgs',$msgs);
						$text = 'Вебмастер '.$this->user['ulogin'].' заблокировал свой профиль.';
						if(!empty($_POST['reason'])):
							$text .= ' Причина блокировки: '.$_POST['reason'];
						endif;
						$this->mdmessages->insert_record($this->user['uid'],0,$text);
						for($i=0;$i<count($managers);$i++):
							$text = 'Площадка '.$managers[$i]['url'].' перешла в состояние - не активна!';
							$this->mdmessages->insert_record($this->user['uid'],$managers[$i]['manager'],$text);
						endfor;
					endif;
				elseif($this->user['lock'] && isset($_POST['lockprofile'])):
					$this->mdusers->update_field($this->user['uid'],'locked',0);
//					$this->mdplatforms->platforms_status_online($this->user['uid']);
					$msgs = 'Профиль разблокирован.'.$this->session->userdata('msgs');
					$this->session->set_userdata('msgs',$msgs);
					$text = 'Вебмастер '.$this->user['ulogin'].' разблокировал свой профиль.';
					$this->mdmessages->insert_record($this->user['uid'],0,$text);
				endif;
				
				redirect($this->uri->uri_string());
			endif;
		endif;
		
		if($this->loginstatus['status']):
			if($this->user['utype'] == 1):
				$userdata = $this->mdunion->read_user_webmaster($this->user['uid']);
				$pagevar['userinfo']['balance'] = $userdata['balance'];
				$pagevar['userinfo']['torders'] = $userdata['torders'];
				$pagevar['userinfo']['uporders'] = $userdata['uporders'];
				unset($userdata);
			endif;
		endif;
		
		$pagevar['cntunit']['delivers']['notpaid'] = $this->mddelivesworks->count_records_by_webmaster_status($this->user['uid'],0);
		$pagevar['cntunit']['delivers']['total'] = $this->mddelivesworks->count_records_by_webmaster($this->user['uid']);
		$pagevar['cntunit']['platforms'] = $this->mdplatforms->count_records_by_webmaster($this->user['uid']);
		$pagevar['cntunit']['mails']['new'] = $this->mdmessages->count_records_by_recipient_new($this->user['uid']);
		$pagevar['cntunit']['mails']['total'] = $this->mdmessages->count_records_by_recipient($this->user['uid'],$this->user['utype'],$this->user['signdate']);
		$pagevar['cntunit']['tickets'] = $this->mdtickets->count_records_by_sender($this->user['uid']);
		
		$this->load->view("clients_interface/control-cabinet",$pagevar);
	}
	
	public function control_balance(){
		
		$pagevar = array(
					'description'	=> '',
					'author'		=> '',
					'title'			=> 'Кабинет Вебмастера | Баланс',
					'baseurl' 		=> base_url(),
					'loginstatus'	=> $this->loginstatus['status'],
					'userinfo'		=> $this->user,
					'msgs'			=> $this->session->userdata('msgs'),
					'msgr'			=> $this->session->userdata('msgr')
			);
		$this->session->unset_userdata('msgs');
		$this->session->unset_userdata('msgr');
		
		if($this->input->post('submit')):
			unset($_POST['submit']);
			$this->form_validation->set_rules('balance',' ','required|numeric|trim');
			if(!$this->form_validation->run()):
				$this->session->set_userdata('msgr','Ошибка. Неверно заполены необходимые поля<br/>');
				redirect($this->uri->uri_string());
			else:
				if(!$_POST['balance']):
					$this->session->set_userdata('msgr','Ошибка. Вносимая сумма должна быть больше нуля');
					redirect($this->uri->uri_string());
				endif;
				$this->session->set_userdata('balance',$_POST['balance']);
				redirect('webmaster-panel/actions/balance/paid');
			endif;
		endif;
		
		if($this->loginstatus['status']):
			if($this->user['utype'] == 1):
				$userdata = $this->mdunion->read_user_webmaster($this->user['uid']);
				$pagevar['userinfo']['balance'] = $userdata['balance'];
				$pagevar['userinfo']['torders'] = $userdata['torders'];
				$pagevar['userinfo']['uporders'] = $userdata['uporders'];
				unset($userdata);
			endif;
		endif;
		
		$this->session->set_userdata('rnd',rand(314159,9846980));
		$pagevar['valid'] = md5($this->session->userdata('logon').$this->session->userdata('rnd'));
		
		$pagevar['cntunit']['delivers']['notpaid'] = $this->mddelivesworks->count_records_by_webmaster_status($this->user['uid'],0);
		$pagevar['cntunit']['delivers']['total'] = $this->mddelivesworks->count_records_by_webmaster($this->user['uid']);
		$pagevar['cntunit']['platforms'] = $this->mdplatforms->count_records_by_webmaster($this->user['uid']);
		$pagevar['cntunit']['mails']['new'] = $this->mdmessages->count_records_by_recipient_new($this->user['uid']);
		$pagevar['cntunit']['mails']['total'] = $this->mdmessages->count_records_by_recipient($this->user['uid'],$this->user['utype'],$this->user['signdate']);
		$pagevar['cntunit']['tickets'] = $this->mdtickets->count_records_by_sender($this->user['uid']);
		$this->session->unset_userdata('balance');
		$this->load->view("clients_interface/control-balance",$pagevar);
	}
	
	public function control_balance_paid(){
		
		if(!$this->session->userdata('balance')):
			redirect('webmaster-panel/actions/balance');
		endif;
		
		$pagevar = array(
					'description'	=> '',
					'author'		=> '',
					'title'			=> 'Кабинет Вебмастера | Подтверждение перевода',
					'baseurl' 		=> base_url(),
					'loginstatus'	=> $this->loginstatus['status'],
					'userinfo'		=> $this->user,
					'msgs'			=> $this->session->userdata('msgs'),
					'msgr'			=> $this->session->userdata('msgr')
			);
		$this->session->unset_userdata('msgs');
		$this->session->unset_userdata('msgr');
		
		$pagevar['cntunit']['delivers']['notpaid'] = $this->mddelivesworks->count_records_by_webmaster_status($this->user['uid'],0);
		$pagevar['cntunit']['delivers']['total'] = $this->mddelivesworks->count_records_by_webmaster($this->user['uid']);
		$pagevar['cntunit']['platforms'] = $this->mdplatforms->count_records_by_webmaster($this->user['uid']);
		$pagevar['cntunit']['mails']['new'] = $this->mdmessages->count_records_by_recipient_new($this->user['uid']);
		$pagevar['cntunit']['mails']['total'] = $this->mdmessages->count_records_by_recipient($this->user['uid'],$this->user['utype'],$this->user['signdate']);
		$pagevar['cntunit']['tickets'] = $this->mdtickets->count_records_by_sender($this->user['uid']);
		
		$this->load->view("clients_interface/control-balance-paid",$pagevar);
	}
	
	public function control_balance_successfull(){
		
		if(!$this->session->userdata('balance')):
			redirect('webmaster-panel/actions/balance');
		endif;
		
		$pagevar = array(
					'description'	=> '',
					'author'		=> '',
					'title'			=> 'Кабинет Вебмастера | Успещное попление баланса',
					'baseurl' 		=> base_url(),
					'loginstatus'	=> $this->loginstatus['status'],
					'userinfo'		=> $this->user,
					'msgs'			=> $this->session->userdata('msgs'),
					'msgr'			=> $this->session->userdata('msgr')
			);
		$this->session->unset_userdata('msgs');
		$this->session->unset_userdata('msgr');
		
		$pagevar['cntunit']['delivers']['notpaid'] = $this->mddelivesworks->count_records_by_webmaster_status($this->user['uid'],0);
		$pagevar['cntunit']['delivers']['total'] = $this->mddelivesworks->count_records_by_webmaster($this->user['uid']);
		$pagevar['cntunit']['platforms'] = $this->mdplatforms->count_records_by_webmaster($this->user['uid']);
		$pagevar['cntunit']['mails']['new'] = $this->mdmessages->count_records_by_recipient_new($this->user['uid']);
		$pagevar['cntunit']['mails']['total'] = $this->mdmessages->count_records_by_recipient($this->user['uid'],$this->user['utype'],$this->user['signdate']);
		$pagevar['cntunit']['tickets'] = $this->mdtickets->count_records_by_sender($this->user['uid']);
		
		$this->load->view("clients_interface/control-balance-successful",$pagevar);
	}
	
	public function control_balance_failed(){
		
		if(!$this->session->userdata('balance')):
			redirect('webmaster-panel/actions/balance');
		endif;
		
		$pagevar = array(
					'description'	=> '',
					'author'		=> '',
					'title'			=> 'Кабинет Вебмастера | Пополение баланса не выполнено',
					'baseurl' 		=> base_url(),
					'loginstatus'	=> $this->loginstatus['status'],
					'userinfo'		=> $this->user,
					'msgs'			=> $this->session->userdata('msgs'),
					'msgr'			=> $this->session->userdata('msgr')
			);
		$this->session->unset_userdata('msgs');
		$this->session->unset_userdata('msgr');
		
		$pagevar['cntunit']['delivers']['notpaid'] = $this->mddelivesworks->count_records_by_webmaster_status($this->user['uid'],0);
		$pagevar['cntunit']['delivers']['total'] = $this->mddelivesworks->count_records_by_webmaster($this->user['uid']);
		$pagevar['cntunit']['platforms'] = $this->mdplatforms->count_records_by_webmaster($this->user['uid']);
		$pagevar['cntunit']['mails']['new'] = $this->mdmessages->count_records_by_recipient_new($this->user['uid']);
		$pagevar['cntunit']['mails']['total'] = $this->mdmessages->count_records_by_recipient($this->user['uid'],$this->user['utype'],$this->user['signdate']);
		$pagevar['cntunit']['tickets'] = $this->mdtickets->count_records_by_sender($this->user['uid']);
		
		$this->load->view("clients_interface/control-balance-failed",$pagevar);
	}
	
	public function control_services(){
		
		$pagevar = array(
					'description'	=> '',
					'author'		=> '',
					'title'			=> 'Кабинет Вебмастера | Дополнительные услуги',
					'baseurl' 		=> base_url(),
					'loginstatus'	=> $this->loginstatus['status'],
					'userinfo'		=> $this->user,
					'attached'		=> $this->mdunion->services_attached($this->user['uid']),
					'services'		=> $this->mdservices->read_records(),
					'msgs'			=> $this->session->userdata('msgs'),
					'msgr'			=> $this->session->userdata('msgr')
			);
		$this->session->unset_userdata('msgs');
		$this->session->unset_userdata('msgr');
		
		if($this->input->post('submit')):
			$_POST['submit'] = NULL;
			$this->form_validation->set_rules('balance',' ','required|numeric|trim');
			if(!$this->form_validation->run()):
				$this->session->set_userdata('msgr','Ошибка. Неверно заполены необходимые поля<br/>');
				redirect($this->uri->uri_string());
			else:
				print_r($_POST);exit;
				redirect($this->uri->uri_string());
			endif;
		endif;
		
		if($this->loginstatus['status']):
			if($this->user['utype'] == 1):
				$userdata = $this->mdunion->read_user_webmaster($this->user['uid']);
				$pagevar['userinfo']['balance'] = $userdata['balance'];
				$pagevar['userinfo']['torders'] = $userdata['torders'];
				$pagevar['userinfo']['uporders'] = $userdata['uporders'];
				unset($userdata);
			endif;
		endif;
		
		$values = array();
		
		for($i=0;$i<count($pagevar['services']);$i++):
			$values[$i]= $pagevar['services'][$i]['price'];
		endfor;
		$pagevar['minprice'] = min($values);
		unset($values);
		
		for($i=0;$i<count($pagevar['attached']);$i++):
			$pagevar['attached'][$i]['date'] = $this->operation_dot_date($pagevar['attached'][$i]['date']);
		endfor;
		
		$pagevar['cntunit']['delivers']['notpaid'] = $this->mddelivesworks->count_records_by_webmaster_status($this->user['uid'],0);
		$pagevar['cntunit']['delivers']['total'] = $this->mddelivesworks->count_records_by_webmaster($this->user['uid']);
		$pagevar['cntunit']['platforms'] = $this->mdplatforms->count_records_by_webmaster($this->user['uid']);
		$pagevar['cntunit']['mails']['new'] = $this->mdmessages->count_records_by_recipient_new($this->user['uid']);
		$pagevar['cntunit']['mails']['total'] = $this->mdmessages->count_records_by_recipient($this->user['uid'],$this->user['utype'],$this->user['signdate']);
		$pagevar['cntunit']['tickets'] = $this->mdtickets->count_records_by_sender($this->user['uid']);
		
		$this->load->view("clients_interface/control-services",$pagevar);
	}
	
	/******************************************************** mails *********************************************************/	
	
	public function control_mails(){
		
		$from = intval($this->uri->segment(5));
		$pagevar = array(
					'description'	=> '',
					'author'		=> '',
					'title'			=> 'Кабинет Вебмастера | Входящие сообщения',
					'baseurl' 		=> base_url(),
					'loginstatus'	=> $this->loginstatus['status'],
					'userinfo'		=> $this->user,
					'mails'			=> $this->mdunion->read_mails_by_recipient($this->user['uid'],$this->user['utype'],$this->user['signdate'],10,$from),
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
				redirect($this->uri->uri_string());
			else:
				$id = $this->mdmessages->insert_record($this->user['uid'],$_POST['recipient'],$_POST['text']);
				if($id):
					$this->session->set_userdata('msgs','Сообщение отправлено');
				endif;
				if(isset($_POST['sendmail'])):
					ob_start();
					?>
					<p><strong>Здравствуйте, <?=$this->mdusers->read_field($_POST['recipient'],'fio');?></strong></p>
					<p>У вас новое сообщение.</p>
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
			endif;
			redirect($this->uri->uri_string());
		endif;
		
		if($this->loginstatus['status']):
			if($this->user['utype'] == 1):
				$userdata = $this->mdunion->read_user_webmaster($this->user['uid']);
				$pagevar['userinfo']['balance'] = $userdata['balance'];
				$pagevar['userinfo']['torders'] = $userdata['torders'];
				$pagevar['userinfo']['uporders'] = $userdata['uporders'];
				unset($userdata);
			endif;
		endif;
		for($i=0;$i<count($pagevar['mails']);$i++):
			$pagevar['mails'][$i]['date'] = $this->operation_date($pagevar['mails'][$i]['date']);
		endfor;
		$this->mdmessages->set_read_mails_by_recipient($this->user['uid'],$this->user['utype']);
		$config['base_url'] 	= $pagevar['baseurl'].'webmaster-panel/actions/mails/from/';
		$config['uri_segment'] 	= 5;
		$config['total_rows'] 	= $this->mdunion->count_mails_by_recipient($this->user['uid'],$this->user['utype'],$this->user['signdate']);
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
		
		$pagevar['cntunit']['delivers']['notpaid'] = $this->mddelivesworks->count_records_by_webmaster_status($this->user['uid'],0);
		$pagevar['cntunit']['delivers']['total'] = $this->mddelivesworks->count_records_by_webmaster($this->user['uid']);
		$pagevar['cntunit']['platforms'] = $this->mdplatforms->count_records_by_webmaster($this->user['uid']);
		$pagevar['cntunit']['mails']['new'] = 0;
		$pagevar['cntunit']['mails']['total'] = $this->mdmessages->count_records_by_recipient($this->user['uid'],$this->user['utype'],$this->user['signdate']);
		$pagevar['cntunit']['tickets'] = $this->mdtickets->count_records_by_sender($this->user['uid']);
		
		$this->load->view("clients_interface/control-mails",$pagevar);
	}
	
	public function control_delete_mail(){
		
		$mid = $this->uri->segment(6);
		if($mid):
			if($this->mdmessages->is_system($mid)):
				redirect('webmaster-panel/actions/mails');
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

	/**************************************************** finished jobs ******************************************************/	
	
	public function control_finished_jobs(){
		
		$from = intval($this->uri->segment(5));
		$pagevar = array(
					'description'	=> '',
					'author'		=> '',
					'title'			=> 'Кабинет Вебмастера | Готовые задания',
					'baseurl' 		=> base_url(),
					'loginstatus'	=> $this->loginstatus['status'],
					'userinfo'		=> $this->user,
					'cntunit'		=> array(),
					'pages'			=> array(),
					'minprice'		=> 0,
					'delivers'		=> $this->mdunion->devivers_works_webmaster($this->user['uid'],10,$from),
					'msgs'			=> $this->session->userdata('msgs'),
					'msgr'			=> $this->session->userdata('msgr')
			);
		$this->session->unset_userdata('msgs');
		$this->session->unset_userdata('msgr');
		
		if($this->input->post('submit')):
			unset($_POST['submit']);
			$this->form_validation->set_rules('summa',' ','required|trim');
			$this->form_validation->set_rules('works[]',' ','required|trim');
			if(!$this->form_validation->run()):
				$this->session->set_userdata('msgr','Ошибка. Не указана сумма.');
				redirect($this->uri->uri_string());
			else:
				if($_POST['summa'] > $this->mdusers->read_field($this->user['uid'],'balance')):
					$this->session->set_userdata('msgr','Ошибка. Сумма превышает Ваш баланс.');
					redirect($this->uri->uri_string());
				elseif($_POST['summa'] !== $this->mddelivesworks->read_summ_by_webmaster($this->user['uid'],$_POST['works'])):
					$this->session->set_userdata('msgr','Ошибка. Не верны суммы!');
					redirect($this->uri->uri_string());
				else:
					//процедура оплаты
					$result = $this->mdusers->change_webmaster_balance($this->user['uid'],-$_POST['summa']);
					if($result):
						$this->mddelivesworks->update_status($this->user['uid'],$_POST['works']);
						$message = 'Спасибо за оплату.';
						if($this->mdusers->read_field($this->user['uid'],'balance') == 0):
							$message .= '<br/>Внимание! У вас нулевой баланс. Необходимо пополнить счет.';
						endif;
						$this->session->set_userdata('msgs',$message);
					else:
						$this->session->set_userdata('msgr','Ошибка. Не возможно списать средства!');
					endif;
					redirect($this->uri->uri_string());
				endif;
			endif;
		endif;
		
		$values = array();
		
		for($i=0;$i<count($pagevar['delivers']);$i++):
			$pagevar['delivers'][$i]['date'] = $this->operation_dot_date($pagevar['delivers'][$i]['date']);
			if(!$pagevar['delivers'][$i]['status']):
				$values[$i]= $pagevar['delivers'][$i]['wprice'];
			endif;
		endfor;
		$pagevar['minprice'] = min($values);
		unset($values);
		if($this->loginstatus['status']):
			if($this->user['utype'] == 1):
				$userdata = $this->mdunion->read_user_webmaster($this->user['uid']);
				$pagevar['userinfo']['balance'] = $userdata['balance'];
				$pagevar['userinfo']['torders'] = $userdata['torders'];
				$pagevar['userinfo']['uporders'] = $userdata['uporders'];
				unset($userdata);
			endif;
		endif;
		
		$config['base_url'] 	= $pagevar['baseurl'].'webmaster-panel/actions/finished-jobs/from/';
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
		
		$pagevar['cntunit']['delivers']['notpaid'] = $this->mddelivesworks->count_records_by_webmaster_status($this->user['uid'],0);
		$pagevar['cntunit']['delivers']['total'] = $this->mddelivesworks->count_records_by_webmaster($this->user['uid']);
		$pagevar['cntunit']['platforms'] = $this->mdplatforms->count_records_by_webmaster($this->user['uid']);
		$pagevar['cntunit']['mails']['new'] = $this->mdmessages->count_records_by_recipient_new($this->user['uid']);
		$pagevar['cntunit']['mails']['total'] = $this->mdmessages->count_records_by_recipient($this->user['uid'],$this->user['utype'],$this->user['signdate']);
		$pagevar['cntunit']['tickets'] = $this->mdtickets->count_records_by_sender($this->user['uid']);
		
		for($i=0;$i<count($pagevar['delivers']);$i++):
			if(mb_strlen($pagevar['delivers'][$i]['ulrlink'],'UTF-8') > 25):
				$pagevar['delivers'][$i]['link'] = mb_substr($pagevar['delivers'][$i]['ulrlink'],0,25,'UTF-8');	
			endif;
		endfor;
		
		$this->load->view("clients_interface/control-finished-jobs",$pagevar);
	}
	
	/******************************************************** platforms ******************************************************/	
	
	public function control_platforms(){
		
		$pagevar = array(
					'description'	=> '',
					'author'		=> '',
					'title'			=> 'Кабинет Вебмастера | Управление площадками',
					'baseurl' 		=> base_url(),
					'loginstatus'	=> $this->loginstatus['status'],
					'userinfo'		=> $this->user,
					'cntunit'		=> array(),
					'platforms'		=> $this->mdplatforms->read_records_by_webmaster($this->user['uid']),
					'markets'		=> $this->mdunion->read_mkplatform_by_webmaster($this->user['uid']),
					'msgs'			=> $this->session->userdata('msgs'),
					'msgr'			=> $this->session->userdata('msgr')
			);
		$this->session->unset_userdata('msgs');
		$this->session->unset_userdata('msgr');
		
		if($this->input->post('epsubmit')):
			$_POST['epsubmit'] = NULL;
			$this->form_validation->set_rules('pid',' ','required|trim');
			if(!$this->form_validation->run()):
				$this->session->set_userdata('msgr','Ошибка при сохранении. Не заполены необходимые поля.');
				redirect($this->uri->uri_string());
			else:
				$status = 0;
				if(isset($_POST['status'])):
					$status = $_POST['status'];
				endif;
				$result = $this->mdplatforms->update_status($_POST['pid'],$this->user['uid'],$status);
				if($result):
					if($status):
						$text = 'Площадка '.$this->mdplatforms->read_field($_POST['pid'],'url').' перешла в состояние - активна!';
					else:
						$text = 'Площадка '.$this->mdplatforms->read_field($_POST['pid'],'url').' перешла в состояние - не активна!';
					endif;
					$manager = $this->mdplatforms->read_field($_POST['pid'],'manager');
					if($manager):
						$this->mdmessages->insert_record($this->user['uid'],$manager,$text);
					endif;
					$this->mdmessages->insert_record($this->user['uid'],0,$text);
					
					$this->session->set_userdata('msgs','Информация успешно сохранена.');
				else:
					$this->session->set_userdata('msgr','Информация не изменилась.');
				endif;
			endif;
			redirect($this->uri->uri_string());
		endif;
		
		if($this->loginstatus['status']):
			if($this->user['utype'] == 1):
				$userdata = $this->mdunion->read_user_webmaster($this->user['uid']);
				$pagevar['userinfo']['balance'] = $userdata['balance'];
				$pagevar['userinfo']['torders'] = $userdata['torders'];
				$pagevar['userinfo']['uporders'] = $userdata['uporders'];
				unset($userdata);
			endif;
		endif;
		
		$pagevar['cntunit']['delivers']['notpaid'] = $this->mddelivesworks->count_records_by_webmaster_status($this->user['uid'],0);
		$pagevar['cntunit']['delivers']['total'] = $this->mddelivesworks->count_records_by_webmaster($this->user['uid']);
		$pagevar['cntunit']['platforms'] = $this->mdplatforms->count_records_by_webmaster($this->user['uid']);
		$pagevar['cntunit']['mails']['new'] = $this->mdmessages->count_records_by_recipient_new($this->user['uid']);
		$pagevar['cntunit']['mails']['total'] = $this->mdmessages->count_records_by_recipient($this->user['uid'],$this->user['utype'],$this->user['signdate']);
		$pagevar['cntunit']['tickets'] = $this->mdtickets->count_records_by_sender($this->user['uid']);
		
		for($i=0;$i<count($pagevar['platforms']);$i++):
			$pagevar['platforms'][$i]['date'] = $this->operation_dot_date($pagevar['platforms'][$i]['date']);
		endfor;
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
					'markets'		=> $this->mdmarkets->read_records(),
					'msginfo'		=> '<span class="alert-attention">Внимание!</span> Убедительная просьба тщательно заполнить все поля представленные ниже. Чем больше вы дадите нам информации, тем меньше ошибок будет совершено при публикации.',
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
		
		if($this->input->post('submit')):
			$_POST['submit'] = NULL;
			$this->form_validation->set_rules('url',' ','required|strtolower|trim');
			$this->form_validation->set_rules('subject',' ','required|trim');
			$this->form_validation->set_rules('cms',' ','required|trim');
			$this->form_validation->set_rules('adminpanel',' ','required|trim');
			$this->form_validation->set_rules('aplogin',' ','required|trim');
			$this->form_validation->set_rules('appassword',' ','required|trim');
			$this->form_validation->set_rules('amount',' ','trim');
			$this->form_validation->set_rules('reviews',' ','trim');
			$this->form_validation->set_rules('thematically',' ','trim');
			$this->form_validation->set_rules('illegal',' ','trim');
			$this->form_validation->set_rules('criteria',' ','trim');
			$this->form_validation->set_rules('requests',' ','trim');
			if(!$this->form_validation->run()):
				$this->session->set_userdata('msgr','Ошибка при сохранении. Не заполены необходимые поля.');
				redirect($this->uri->uri_string());
			else:
				$exist = $this->mdplatforms->exist_platform($_POST['url']);
				if($exist):
					$this->session->set_userdata('msgr','Площадка с URL-адресом '.$_POST['url'].' уже существует!');
					redirect($this->uri->uri_string());
				endif;
				$platform = $this->mdplatforms->insert_record($this->user['uid'],$_POST);
				if($platform):
					$sqlquery = "UPDATE platforms SET ";
					$works = $this->mdtypeswork->read_records();
					for($i=0;$i<count($works);$i++):
						$sqlquery .= 'c'.$works[$i]['nickname'].' = '.$works[$i]['wprice'].', m'.$works[$i]['nickname'].' = '.$works[$i]['mprice'];
						if(isset($works[$i+1])):
							$sqlquery .= ', ';
						endif;
					endfor;
					$sqlquery .= ' WHERE platforms.id = '.$platform;
					$this->mdplatforms->run_query($sqlquery);
				endif;
				if($platform && isset($_POST['markets'])):
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
						$this->mdmkplatform->group_insert($this->user['uid'],$platform,$marketslist);
					endif;
					$pr = $this->getpagerank($_POST['url']);
					$this->mdplatforms->update_field($platform,'pr',$pr);
					$tic = $this->getTIC('http://'.$_POST['url']);
					$this->mdplatforms->update_field($platform,'tic',$tic);
					$text = 'Добавлена новая площадка: '.$_POST['url'];
					$this->mdmessages->insert_record($this->user['uid'],0,$text);
					$this->session->set_userdata('msgs','Платформа успешно создана.');
				else:
					$this->session->set_userdata('msgr','Платформа не создана.');
				endif;
			endif;
			redirect($this->uri->uri_string());
		endif;
		$pagevar['cntunit']['delivers']['notpaid'] = $this->mddelivesworks->count_records_by_webmaster_status($this->user['uid'],0);
		$pagevar['cntunit']['delivers']['total'] = $this->mddelivesworks->count_records_by_webmaster($this->user['uid']);
		$this->load->view("clients_interface/control-add-platform",$pagevar);
	}
	
	public function control_edit_platform(){
		
		$platform = $this->uri->segment(5);
		if(!$this->mdplatforms->ownew_platform($this->user['uid'],$platform)):
			redirect($_SERVER['HTTP_REFERER']);
		endif;
		
		$pagevar = array(
					'description'	=> '',
					'author'		=> '',
					'title'			=> 'Кабинет Вебмастера | Управление площадками | Редактирование площадки',
					'baseurl' 		=> base_url(),
					'loginstatus'	=> $this->loginstatus['status'],
					'userinfo'		=> $this->user,
					'platform'		=> $this->mdplatforms->read_record($platform),
					'markets'		=> $this->mdmarkets->read_records(),
					'mymarkets'		=> $this->mdmkplatform->read_records_by_platform($platform,$this->user['uid']),
					'msginfo'		=> '<span class="alert-attention">Внимание!</span> Перед добавлением площадки убедитесь в наличии всех бирж в каталоге. Если необходимая биржа отсутствует в каталоге - обратитесь к администрации. Доступ к администрации сайта осуществляется через интерфейс технической поддержки.',
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
		
		if($this->input->post('submit')):
			$_POST['submit'] = NULL;
			$this->form_validation->set_rules('url',' ','required|trim');
			$this->form_validation->set_rules('subject',' ','required|trim');
			$this->form_validation->set_rules('cms',' ','required|trim');
			$this->form_validation->set_rules('adminpanel',' ','required|trim');
			$this->form_validation->set_rules('aplogin',' ','required|trim');
			$this->form_validation->set_rules('appassword',' ','required|trim');
			$this->form_validation->set_rules('amount',' ','trim');
			$this->form_validation->set_rules('reviews',' ','trim');
			$this->form_validation->set_rules('thematically',' ','trim');
			$this->form_validation->set_rules('illegal',' ','trim');
			$this->form_validation->set_rules('criteria',' ','trim');
			$this->form_validation->set_rules('requests',' ','trim');
			if(!$this->form_validation->run()):
				$this->session->set_userdata('msgr','Ошибка при сохранении. Не заполены необходимые поля.');
				redirect($this->uri->uri_string());
			else:
				$result = $this->mdplatforms->update_record($platform,$this->user['uid'],$_POST);
				$this->session->set_userdata('msgs','Платформа успешно сохранена.');
				$this->mdmkplatform->delete_records_by_platform($platform,$this->user['uid']);
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
						$this->mdmkplatform->group_insert($this->user['uid'],$platform,$marketslist);
					endif;
				endif;
			endif;
			redirect('webmaster-panel/actions/platforms');
		endif;
		$pagevar['cntunit']['delivers']['notpaid'] = $this->mddelivesworks->count_records_by_webmaster_status($this->user['uid'],0);
		$pagevar['cntunit']['delivers']['total'] = $this->mddelivesworks->count_records_by_webmaster($this->user['uid']);
		$this->load->view("clients_interface/control-edit-platform",$pagevar);
	}
	
	/******************************************************** tickets ******************************************************/	
	
	public function control_tickets(){
		
		$from = intval($this->uri->segment(5));
		$pagevar = array(
					'description'	=> '',
					'author'		=> '',
					'title'			=> 'Кабинет Вебмастера | Тикеты',
					'baseurl' 		=> base_url(),
					'loginstatus'	=> $this->loginstatus['status'],
					'userinfo'		=> $this->user,
					'tickets'		=> $this->mdunion->read_tickets_by_sender($this->user['uid'],5,$from),
					'platforms'		=> $this->mdplatforms->read_records_by_webmaster_nolock($this->user['uid']),
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
			$this->form_validation->set_rules('type',' ','required|trim');
			$this->form_validation->set_rules('platform',' ','required|trim');
			if(!$this->form_validation->run()):
				$this->session->set_userdata('msgr','Ошибка при сохранении. Не заполены необходимые поля.');
				redirect($this->uri->uri_string());
			else:
				$recipient = 0;
				if($_POST['type'] == 1):
					$recipient = $this->mdplatforms->read_field($_POST['platform'],'manager');
					if(!$recipient):
						$recipient = 0;
					else:
						ob_start();
						?>
						<p><strong>Здравствуйте, <?=$this->mdusers->read_field($recipient,'fio');?></strong></p>
						<p>У Вас новое сообщение через тикет-систему</p>
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
						$this->email->to($this->mdusers->read_field($recipient,'login'));
						$this->email->from('admin@bystropost.ru','Bystropost.ru - Система управления продажами');
						$this->email->bcc('');
						$this->email->subject('Noreply: Bystropost.ru - Новый тикет');
						$this->email->message($mailtext);	
						$this->email->send();
					endif;
				endif;
				$ticket = $this->mdtickets->insert_record($this->user['uid'],$recipient,$_POST);
				if($ticket):
					$this->mdtkmsgs->insert_record($this->user['uid'],$ticket,$this->user['uid'],$recipient,0,$_POST['text']);
					$this->session->set_userdata('msgs','Тикет успешно создан.');
				else:
					$this->session->set_userdata('msgr','Тикет не создан.');
				endif;
			endif;
			redirect($this->uri->uri_string());
		endif;
		
		$config['base_url'] 	= $pagevar['baseurl'].'webmaster-panel/actions/tickets/from/';
		$config['uri_segment'] 	= 5;
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
		
		if($this->loginstatus['status']):
			if($this->user['utype'] == 1):
				$userdata = $this->mdunion->read_user_webmaster($this->user['uid']);
				$pagevar['userinfo']['balance'] = $userdata['balance'];
				$pagevar['userinfo']['torders'] = $userdata['torders'];
				$pagevar['userinfo']['uporders'] = $userdata['uporders'];
				unset($userdata);
			endif;
		endif;
		
		$pagevar['cntunit']['delivers']['notpaid'] = $this->mddelivesworks->count_records_by_webmaster_status($this->user['uid'],0);
		$pagevar['cntunit']['delivers']['total'] = $this->mddelivesworks->count_records_by_webmaster($this->user['uid']);
		$pagevar['cntunit']['platforms'] = $this->mdplatforms->count_records_by_webmaster($this->user['uid']);
		$pagevar['cntunit']['mails']['new'] = $this->mdmessages->count_records_by_recipient_new($this->user['uid']);
		$pagevar['cntunit']['mails']['total'] = $this->mdmessages->count_records_by_recipient($this->user['uid'],$this->user['utype'],$this->user['signdate']);
		$pagevar['cntunit']['tickets'] = $this->mdtickets->count_records_by_sender($this->user['uid']);
		
		for($i=0;$i<count($pagevar['tickets']);$i++):
			$pagevar['tickets'][$i]['date'] = $this->operation_dot_date($pagevar['tickets'][$i]['date']);
			if($pagevar['tickets'][$i]['recipient']):
				$pagevar['tickets'][$i]['position'] = $this->mdusers->read_field($pagevar['tickets'][$i]['recipient'],'position');
			else:
				$pagevar['tickets'][$i]['position'] = 'Администратору';
			endif;
		endfor;
		$this->load->view("clients_interface/control-tickets",$pagevar);
	}
	
	public function control_view_ticket(){
		
		$ticket = $this->uri->segment(5);
		if(!$this->mdtickets->ownew_ticket($this->user['uid'],$ticket)):
			show_404();
		endif;
		$from = intval($this->uri->segment(7));
		$pagevar = array(
					'description'	=> '',
					'author'		=> '',
					'title'			=> 'Кабинет Вебмастера | Тикеты | Просмотр тикета',
					'baseurl' 		=> base_url(),
					'loginstatus'	=> $this->loginstatus['status'],
					'userinfo'		=> $this->user,
					'ticket'		=> $this->mdunion->view_ticket_info($ticket),
					'tkmsgs'		=> $this->mdtkmsgs->read_tkmsgs_by_owner_pages($this->user['uid'],$ticket,5,$from),
					'count'			=> $this->mdtkmsgs->count_tkmsgs_by_owner_pages($this->user['uid'],$ticket),
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
				redirect($this->uri->uri_string());
			else:
				$tuser = $this->mdusers->read_field($_POST['recipient'],'type');
				switch($tuser):
					case 1 : redirect($this->uri->uri_string()); break;
					case 2 : TRUE; break;
					case 3 : redirect($this->uri->uri_string()); break;
					case 4 : redirect($this->uri->uri_string()); break;
					case 5 : $_POST['recipient'] = 0; break;
				endswitch;
				if(isset($_POST['closeticket'])):
					$_POST['text'] .= '<br/><strong>Cпасибо за информацию. Тикет закрыт!</strong>';
					$this->mdtickets->update_field($ticket,'status',1);
				endif;
				$result = $this->mdtkmsgs->insert_record($this->user['uid'],$ticket,$this->user['uid'],$_POST['recipient'],$_POST['mid'],$_POST['text']);
				if($result):
					$this->session->set_userdata('msgs','Сообщение отправлено');
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
			endif;
		endfor;
		if($this->loginstatus['status']):
			if($this->user['utype'] == 1):
				$userdata = $this->mdunion->read_user_webmaster($this->user['uid']);
				$pagevar['userinfo']['balance'] = $userdata['balance'];
				$pagevar['userinfo']['torders'] = $userdata['torders'];
				$pagevar['userinfo']['uporders'] = $userdata['uporders'];
				unset($userdata);
			endif;
		endif;
		$this->session->set_userdata('backpath',$this->uri->uri_string());
		$config['base_url'] 	= $pagevar['baseurl'].'webmaster-panel/actions/tickets/view-ticket/'.$this->uri->segment(5).'/from/';
		$config['uri_segment'] 	= 7;
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
		
		$pagevar['cntunit']['delivers']['notpaid'] = $this->mddelivesworks->count_records_by_webmaster_status($this->user['uid'],0);
		$pagevar['cntunit']['delivers']['total'] = $this->mddelivesworks->count_records_by_webmaster($this->user['uid']);
		$pagevar['cntunit']['platforms'] = $this->mdplatforms->count_records_by_webmaster($this->user['uid']);
		$pagevar['cntunit']['mails']['new'] = $this->mdmessages->count_records_by_recipient_new($this->user['uid']);
		$pagevar['cntunit']['mails']['total'] = $this->mdmessages->count_records_by_recipient($this->user['uid'],$this->user['utype'],$this->user['signdate']);
		$pagevar['cntunit']['tickets'] = $this->mdtickets->count_records_by_sender($this->user['uid']);
		
		$this->load->view("clients_interface/control-view-ticket",$pagevar);
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
	
	/******************************************************** other ******************************************************/	
	
	function views(){
	
		$type = $this->uri->segment(2);
		switch ($type):
			case 'market-profile'	:	$pagevar = array('markets'=>$this->mdmarkets->read_records(),'baseurl'=>base_url());
										$this->load->view('clients_interface/includes/markets-profile',$pagevar);
										break;
					default 		:	show_404();
		endswitch;
	}

	public function actions_logoff(){
		
		$this->session->sess_destroy();
		redirect('');
	}

	function viewimage(){
		
		$section = $this->uri->segment(1);
		$id = $this->uri->segment(3);
		switch ($section):
			case 'markets'	:	$image = $this->mdmarkets->get_image($id); break;
			default			: 	show_404();break;
		endswitch;
		header('Content-type: image/gif');
		echo $image;
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
					$pagerank = substr($data, $pos + 9);
				endif;
			endwhile;
			fclose($fp);
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