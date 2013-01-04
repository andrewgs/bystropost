<?php if(!defined('BASEPATH')) exit('No direct script access allowed');

class Clients_interface extends MY_Controller{

	var $user = array('uid'=>0,'uname'=>'','ulogin'=>'','utype'=>'','lock'=>0,'signdate'=>'','balance'=>0,'locked'=>FALSE,'debetor'=>FALSE,'remote'=>FALSE,'remoteid'=>0,'autopaid'=>FALSE,'antihold'=>FALSE,'partner'=>0);
	var $loginstatus = array('status'=>FALSE);
	
	function __construct(){
		
		parent::__construct();

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
					$this->user['debetor'] 			= $userinfo['debetor'];
					$this->user['signdate'] 		= $userinfo['signdate'];
					$this->user['balance'] 			= $userinfo['balance'];
					$this->user['autopaid'] 		= $userinfo['autopaid'];
					$this->user['antihold'] 		= $userinfo['antihold'];
					$this->user['partner'] 			= $userinfo['partner_id'];
					if($userinfo['manager'] == 2):
						$this->user['remote'] = TRUE;
						$this->user['remoteid'] = $userinfo['remoteid'];
					endif;
					if($this->user['debetor']):
						$this->user['locked'] = TRUE;
					endif;
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
		$segment = $this->uri->segment(3);
		if($segment != 'profile' && $segment != 'logoff'):
			if(empty($userinfo['wmid'])):
				$this->session->set_userdata('wmid',1);
				redirect('webmaster-panel/actions/profile');
			endif;
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
		
		$pagevar['cntunit']['delivers']['notpaid'] = $this->mddelivesworks->count_records_by_webmaster_status($this->user['uid'],0);
		$pagevar['cntunit']['delivers']['total'] = $this->mddelivesworks->count_records_by_webmaster($this->user['uid']);
		$totalsum = $this->mddelivesworks->calc_webmaster_summ($this->user['uid'],'2012-01-01',0);
		$pagevar['cntunit']['delivers']['totalsum'] = $totalsum['sum'];
		$pagevar['cntunit']['platforms'] = $this->mdplatforms->count_records_by_webmaster($this->user['uid']);
		$pagevar['cntunit']['markets'] = $this->mdwebmarkets->count_records($this->user['remoteid']);
		$pagevar['cntunit']['mails']['new'] = $this->mdmessages->count_records_by_recipient_new($this->user['uid'],$this->user['utype']);
		$pagevar['cntunit']['mails']['total'] = $this->mdmessages->count_records_by_recipient($this->user['uid'],$this->user['utype'],$this->user['signdate']);
		$pagevar['cntunit']['tickets_outbox'] = $this->mdtickets->count_records_by_sender($this->user['uid']);
		$pagevar['cntunit']['tickets_inbox'] = $this->mdtickets->count_records_by_recipient($this->user['uid']);
		if($pagevar['userinfo']['remote']):
			if(intval($pagevar['userinfo']['balance'])<500 && !$pagevar['cntunit']['markets'] && !$pagevar['cntunit']['platforms']):
				$pagevar['userinfo']['locked'] = TRUE;
			endif;
		else:
			if(intval($pagevar['userinfo']['balance'])<500 && !$pagevar['cntunit']['platforms']):
				$pagevar['userinfo']['locked'] = TRUE;
			endif;
		endif;
		$this->load->view("clients_interface/control-panel",$pagevar);
	}
	
	public function reg_successfull(){
		
		if(!$this->session->userdata('regsuc')):
			show_404();
		endif;
		
		$pagevar = array(
			'title'			=> 'Bystropost.ru - Система мониторинга и управления | Регистрация завершена',
			'description'	=> '',
			'author'		=> '',
			'baseurl' 		=> base_url(),
			'loginstatus'	=> $this->loginstatus['status'],
			'userinfo'		=> $this->user,
			'cntunit'		=> array(),
			'msgs'			=> $this->session->userdata('msgs'),
			'msgr'			=> $this->session->userdata('msgr'),
			'msgauth'		=> $this->session->userdata('msgauth')
		);
		$this->session->unset_userdata('msgauth');
		$this->session->unset_userdata('msgs');
		$this->session->unset_userdata('msgr');
		$this->session->unset_userdata('regsuc');
		
		$pagevar['cntunit']['delivers']['totalsum'] = 0;
		$pagevar['cntunit']['delivers']['notpaid'] = $this->mddelivesworks->count_records_by_webmaster_status($this->user['uid'],0);
		$pagevar['cntunit']['delivers']['total'] = $this->mddelivesworks->count_records_by_webmaster($this->user['uid']);
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
		
		$pagevar['user']['signdate'] = $this->operation_dot_date_on_time($pagevar['user']['signdate']);
		
		if($this->input->post('submit')):
			$_POST['submit'] = NULL;
			$this->form_validation->set_rules('fio',' ','required|trim');
			$this->form_validation->set_rules('oldpas',' ','trim');
			$this->form_validation->set_rules('password',' ','trim');
			$this->form_validation->set_rules('confpass',' ','trim');
			$this->form_validation->set_rules('wmid',' ','required|integer|exact_length[12]|trim');
			$this->form_validation->set_rules('phones',' ','trim');
			$this->form_validation->set_rules('icq',' ','trim');
			$this->form_validation->set_rules('skype',' ','trim');
			if(!$this->form_validation->run()):
				$this->session->set_userdata('msgr','Ошибка. Поля "Полное имя" и "WMID" должны быть заполнены');
				redirect($this->uri->uri_string());
			else:
				$this->session->unset_userdata('wmid');
				if(!empty($_POST['oldpas']) && !empty($_POST['password']) && !empty($_POST['confpass'])):
					if(!$this->mdusers->user_exist('password',md5($_POST['oldpas']))):
						$this->session->set_userdata('msgr',' Не верный старый пароль!');
					elseif($_POST['password']!=$_POST['confpass']):
						$this->session->set_userdata('msgr',' Пароли не совпадают.');
					else:
						$this->mdusers->update_field($this->user['uid'],'password',md5($_POST['password']));
						$this->mdusers->update_field($this->user['uid'],'cryptpassword',$this->encrypt->encode($_POST['password']));
						$this->session->set_userdata('msgs',' Пароль успешно изменен');
						ob_start();
						?>
						<img src="<?=base_url();?>images/logo.png" alt="" />
						<p><strong>Здравствуйте, <?=$pagevar['user']['fio'];?></strong></p>
						<p>Был сменен пароль для доступа к системе Быстропост.</p>
						<p>Ваш логин: <?=$this->user['ulogin'];?></p>
						<p>Новый пароль: <?=$_POST['password'];?></p>
						<br/><br/><p><a href="http://www.bystropost.ru/">С уважением, www.Bystropost.ru</a></p>
						<?
						$mailtext = ob_get_clean();
						
						$this->email->clear(TRUE);
						$config['smtp_host'] = 'localhost';
						$config['charset'] = 'utf-8';
						$config['wordwrap'] = TRUE;
						$config['mailtype'] = 'html';
						
						$this->email->initialize($config);
						$this->email->to($this->user['ulogin']);
						$this->email->from('admin@bystropost.ru','Bystropost.ru - Система мониторинга и управления');
						$this->email->bcc('');
						$this->email->subject('Смена пароля в системе Bystropost.ru');
						$this->email->message($mailtext);	
						$this->email->send();
						$this->session->set_userdata('logon',md5($this->user['ulogin'].md5($_POST['password'])));
					endif;
				endif;
				if(!isset($_POST['sendmail'])):
					$_POST['sendmail'] = 0;
				endif;
				if(!isset($_POST['autopaid'])):
					$_POST['autopaid'] = 0;
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
					if($_POST['autopaid']):
						$msgs .= 'Включен режим "Беззаботный".<br/>';
					endif;
					$this->session->set_userdata('msgs',$msgs);
				endif;
				if(!$this->user['lock'] && isset($_POST['lockprofile'])):
					$result = $this->mdusers->update_field($this->user['uid'],'locked',1);
					if($result):
						$this->mdusers->update_field($this->user['uid'],'autopaid',0);
						$managers = $this->mdplatforms->read_managers_platform_online($this->user['uid']);
						$this->mdplatforms->platforms_status_offline($this->user['uid']);
						$platforms = $this->mdplatforms->read_records_by_webmaster($this->user['uid']);
						for($i=0;$i<count($platforms);$i++):
							if($platforms[$i]['manager'] == 2 && $platforms[$i]['remoteid']):
								$param = 'siteid='.$platforms[$i]['remoteid'].'&value=1';
								$this->API('SetSiteActive',$param);
							endif;
						endfor;
						$this->mdlog->insert_record($this->user['uid'],'Событие №4: Профиль заблокирован');
						$msgs = 'Профиль заблокирован. Все площадки не активны.<br/>'.$this->session->userdata('msgs');
						$this->session->set_userdata('msgs',$msgs);
						$text = 'Вебмастер '.$this->user['ulogin'].' заблокировал свой профиль.';
						if(!empty($_POST['reason'])):
							$text .= ' Причина блокировки: '.$_POST['reason'];
						endif;
						$this->mdmessages->send_noreply_message($this->user['uid'],0,2,5,$text);
						for($i=0;$i<count($managers);$i++):
							$text = 'Площадка '.$managers[$i]['url'].' перешла в состояние - не активна!';
							$this->mdmessages->send_noreply_message($this->user['uid'],$managers[$i]['manager'],2,2,$text);
						endfor;
					endif;
				elseif($this->user['lock'] && isset($_POST['lockprofile'])):
					$this->mdusers->update_field($this->user['uid'],'locked',0);
					$this->mdlog->insert_record($this->user['uid'],'Событие №5: Профиль разблокирован');
//					$this->mdplatforms->platforms_status_online($this->user['uid']);
					$msgs = 'Профиль разблокирован.'.$this->session->userdata('msgs');
					$this->session->set_userdata('msgs',$msgs);
					$text = 'Вебмастер '.$this->user['ulogin'].' разблокировал свой профиль.';
					$this->mdmessages->send_noreply_message($this->user['uid'],0,2,5,$text);
				endif;
				
				redirect($this->uri->uri_string());
			endif;
		endif;
		
		$pagevar['cntunit']['delivers']['notpaid'] = $this->mddelivesworks->count_records_by_webmaster_status($this->user['uid'],0);
		$pagevar['cntunit']['delivers']['total'] = $this->mddelivesworks->count_records_by_webmaster($this->user['uid']);
		$totalsum = $this->mddelivesworks->calc_webmaster_summ($this->user['uid'],'2012-01-01',0);
		$pagevar['cntunit']['delivers']['totalsum'] = $totalsum['sum'];
		$pagevar['cntunit']['platforms'] = $this->mdplatforms->count_records_by_webmaster($this->user['uid']);
		$pagevar['cntunit']['markets'] = $this->mdwebmarkets->count_records($this->user['remoteid']);
		$pagevar['cntunit']['mails']['new'] = $this->mdmessages->count_records_by_recipient_new($this->user['uid'],$this->user['utype']);
		$pagevar['cntunit']['mails']['total'] = $this->mdmessages->count_records_by_recipient($this->user['uid'],$this->user['utype'],$this->user['signdate']);
		$pagevar['cntunit']['tickets_outbox'] = $this->mdtickets->count_records_by_sender($this->user['uid']);
		$pagevar['cntunit']['tickets_inbox'] = $this->mdtickets->count_records_by_recipient($this->user['uid']);
		
		if($pagevar['userinfo']['remote']):
			if(intval($pagevar['userinfo']['balance'])<500 && !$pagevar['cntunit']['markets'] && !$pagevar['cntunit']['platforms']):
				$pagevar['userinfo']['locked'] = TRUE;
			endif;
		else:
			if(intval($pagevar['userinfo']['balance'])<500 && !$pagevar['cntunit']['platforms']):
				$pagevar['userinfo']['locked'] = TRUE;
			endif;
		endif;
		
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
					'wmid'			=> $this->mdusers->read_field($this->user['uid'],'wmid'),
					'msgs'			=> $this->session->userdata('msgs'),
					'msgr'			=> $this->session->userdata('msgr')
			);
		$this->session->unset_userdata('msgs');
		$this->session->unset_userdata('msgr');
		
		if($this->input->post('submit')):
			$this->form_validation->set_rules('balance',' ','required|numeric|trim');
			if(!$this->form_validation->run()):
				$this->session->set_userdata('msgr','Ошибка. Неверно заполнены необходимые поля<br/>');
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
		
		$pagevar['cntunit']['delivers']['notpaid'] = $this->mddelivesworks->count_records_by_webmaster_status($this->user['uid'],0);
		$pagevar['cntunit']['delivers']['total'] = $this->mddelivesworks->count_records_by_webmaster($this->user['uid']);
		$totalsum = $this->mddelivesworks->calc_webmaster_summ($this->user['uid'],'2012-01-01',0);
		$pagevar['cntunit']['delivers']['totalsum'] = $totalsum['sum'];
		$pagevar['cntunit']['platforms'] = $this->mdplatforms->count_records_by_webmaster($this->user['uid']);
		$pagevar['cntunit']['markets'] = $this->mdwebmarkets->count_records($this->user['remoteid']);
		$pagevar['cntunit']['mails']['new'] = $this->mdmessages->count_records_by_recipient_new($this->user['uid'],$this->user['utype']);
		$pagevar['cntunit']['mails']['total'] = $this->mdmessages->count_records_by_recipient($this->user['uid'],$this->user['utype'],$this->user['signdate']);
		$pagevar['cntunit']['tickets_outbox'] = $this->mdtickets->count_records_by_sender($this->user['uid']);
		$pagevar['cntunit']['tickets_inbox'] = $this->mdtickets->count_records_by_recipient($this->user['uid']);
		
		if($pagevar['userinfo']['remote']):
			if(intval($pagevar['userinfo']['balance'])<500 && !$pagevar['cntunit']['markets'] && !$pagevar['cntunit']['platforms']):
				$pagevar['userinfo']['locked'] = TRUE;
			endif;
		else:
			if(intval($pagevar['userinfo']['balance'])<500 && !$pagevar['cntunit']['platforms']):
				$pagevar['userinfo']['locked'] = TRUE;
			endif;
		endif;
		
		$this->session->unset_userdata('balance');
		$this->load->view("clients_interface/control-balance",$pagevar);
	}
	
	public function control_payment_history(){
		
		$from = intval($this->uri->segment(6));
		$pagevar = array(
					'description'	=> '',
					'author'		=> '',
					'title'			=> 'Кабинет Вебмастера | Баланс | История платежей',
					'baseurl' 		=> base_url(),
					'loginstatus'	=> $this->loginstatus['status'],
					'userinfo'		=> $this->user,
					'history'		=> $this->mdfillup->read_records_by_user($this->user['uid'],50,$from),
					'count'			=> $this->mdfillup->count_records_by_user($this->user['uid']),
					'pages'			=> array(),
					'statistic'		=> array(),
					'msgs'			=> $this->session->userdata('msgs'),
					'msgr'			=> $this->session->userdata('msgr')
			);
		$this->session->unset_userdata('msgs');
		$this->session->unset_userdata('msgr');
		
		$pagevar['statistic']['week'] = $this->mdfillup->week_statistic($this->user['uid']);
		$pagevar['statistic']['bymonth'] = $this->mdfillup->bymonth_statistic($this->user['uid']);
		$pagevar['statistic']['month'] = $this->mdfillup->month_statistic($this->user['uid']);
		$pagevar['statistic']['total'] = $this->mdfillup->total_statistic($this->user['uid']);
		$pagevar['statistic']['partners'] = 0;
		$works = $this->mdunion->count_summa_works_partners($this->user['uid']);
		if($works):
			$pagevar['statistic']['partners'] = round($works['summa']*0.05,2);
		endif;
		for($i=0;$i<count($pagevar['history']);$i++):
			$pagevar['history'][$i]['date'] = $this->operation_dot_date_on_time($pagevar['history'][$i]['date']);
		endfor;
		
		$config['base_url'] 		= $pagevar['baseurl'].'webmaster-panel/actions/balance/payment-history/from/';
		$config['uri_segment'] 		= 6;
		$config['total_rows'] 		= $pagevar['count']; 
		$config['per_page'] 		= 50;
		$config['num_links'] 		= 4;
		$config['first_link']		= 'В начало';
		$config['last_link'] 		= 'В конец';
		$config['next_link'] 		= 'Далее &raquo;';
		$config['prev_link'] 		= '&laquo; Назад';
		$config['cur_tag_open']		= '<li class="active"><a href="#">';
		$config['cur_tag_close'] 	= '</a></li>';
		$config['full_tag_open'] 	= '<div class="pagination"><ul>';
		$config['full_tag_close'] 	= '</ul></div>';
		$config['first_tag_open'] 	= '<li>';
		$config['first_tag_close'] 	= '</li>';
		$config['last_tag_open'] 	= '<li>';
		$config['last_tag_close'] 	= '</li>';
		$config['next_tag_open'] 	= '<li>';
		$config['next_tag_close'] 	= '</li>';
		$config['prev_tag_open'] 	= '<li>';
		$config['prev_tag_close'] 	= '</li>';
		$config['num_tag_open'] 	= '<li>';
		$config['num_tag_close'] 	= '</li>';
		
		$this->pagination->initialize($config);
		$pagevar['pages'] = $this->pagination->create_links();
		
		$pagevar['cntunit']['delivers']['notpaid'] = $this->mddelivesworks->count_records_by_webmaster_status($this->user['uid'],0);
		$pagevar['cntunit']['delivers']['total'] = $this->mddelivesworks->count_records_by_webmaster($this->user['uid']);
		$totalsum = $this->mddelivesworks->calc_webmaster_summ($this->user['uid'],'2012-01-01',0);
		$pagevar['cntunit']['delivers']['totalsum'] = $totalsum['sum'];
		$pagevar['cntunit']['platforms'] = $this->mdplatforms->count_records_by_webmaster($this->user['uid']);
		$pagevar['cntunit']['markets'] = $this->mdwebmarkets->count_records($this->user['remoteid']);
		$pagevar['cntunit']['mails']['new'] = $this->mdmessages->count_records_by_recipient_new($this->user['uid'],$this->user['utype']);
		$pagevar['cntunit']['mails']['total'] = $this->mdmessages->count_records_by_recipient($this->user['uid'],$this->user['utype'],$this->user['signdate']);
		$pagevar['cntunit']['tickets_outbox'] = $this->mdtickets->count_records_by_sender($this->user['uid']);
		$pagevar['cntunit']['tickets_inbox'] = $this->mdtickets->count_records_by_recipient($this->user['uid']);
		
		if($pagevar['userinfo']['remote']):
			if(intval($pagevar['userinfo']['balance'])<500 && !$pagevar['cntunit']['markets'] && !$pagevar['cntunit']['platforms']):
				$pagevar['userinfo']['locked'] = TRUE;
			endif;
		else:
			if(intval($pagevar['userinfo']['balance'])<500 && !$pagevar['cntunit']['platforms']):
				$pagevar['userinfo']['locked'] = TRUE;
			endif;
		endif;
		
		$this->session->unset_userdata('balance');
		$this->load->view("clients_interface/control-payment-history",$pagevar);
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
					'wmid'			=> $this->mdusers->read_field($this->user['uid'],'wmid'),
					'msgs'			=> $this->session->userdata('msgs'),
					'msgr'			=> $this->session->userdata('msgr')
			);
		$this->session->unset_userdata('msgs');
		$this->session->unset_userdata('msgr');
		
		$pagevar['cntunit']['delivers']['notpaid'] = $this->mddelivesworks->count_records_by_webmaster_status($this->user['uid'],0);
		$pagevar['cntunit']['delivers']['total'] = $this->mddelivesworks->count_records_by_webmaster($this->user['uid']);
		$totalsum = $this->mddelivesworks->calc_webmaster_summ($this->user['uid'],'2012-01-01',0);
		$pagevar['cntunit']['delivers']['totalsum'] = $totalsum['sum'];
		$pagevar['cntunit']['platforms'] = $this->mdplatforms->count_records_by_webmaster($this->user['uid']);
		$pagevar['cntunit']['markets'] = $this->mdwebmarkets->count_records($this->user['remoteid']);
		$pagevar['cntunit']['mails']['new'] = $this->mdmessages->count_records_by_recipient_new($this->user['uid'],$this->user['utype']);
		$pagevar['cntunit']['mails']['total'] = $this->mdmessages->count_records_by_recipient($this->user['uid'],$this->user['utype'],$this->user['signdate']);
		$pagevar['cntunit']['tickets_outbox'] = $this->mdtickets->count_records_by_sender($this->user['uid']);
		$pagevar['cntunit']['tickets_inbox'] = $this->mdtickets->count_records_by_recipient($this->user['uid']);
		
		if($pagevar['userinfo']['remote']):
			if(intval($pagevar['userinfo']['balance'])<500 && !$pagevar['cntunit']['markets'] && !$pagevar['cntunit']['platforms']):
				$pagevar['userinfo']['locked'] = TRUE;
			endif;
		else:
			if(intval($pagevar['userinfo']['balance'])<500 && !$pagevar['cntunit']['platforms']):
				$pagevar['userinfo']['locked'] = TRUE;
			endif;
		endif;
		
		$this->load->view("clients_interface/control-balance-paid",$pagevar);
	}
	
	public function control_balance_successfull(){
		
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
		
		$this->session->unset_userdata('balance');
		$this->session->unset_userdata('purse');
		
		$pagevar['cntunit']['delivers']['notpaid'] = $this->mddelivesworks->count_records_by_webmaster_status($this->user['uid'],0);
		$pagevar['cntunit']['delivers']['total'] = $this->mddelivesworks->count_records_by_webmaster($this->user['uid']);
		$totalsum = $this->mddelivesworks->calc_webmaster_summ($this->user['uid'],'2012-01-01',0);
		$pagevar['cntunit']['delivers']['totalsum'] = $totalsum['sum'];
		$pagevar['cntunit']['platforms'] = $this->mdplatforms->count_records_by_webmaster($this->user['uid']);
		$pagevar['cntunit']['markets'] = $this->mdwebmarkets->count_records($this->user['remoteid']);
		$pagevar['cntunit']['mails']['new'] = $this->mdmessages->count_records_by_recipient_new($this->user['uid'],$this->user['utype']);
		$pagevar['cntunit']['mails']['total'] = $this->mdmessages->count_records_by_recipient($this->user['uid'],$this->user['utype'],$this->user['signdate']);
		$pagevar['cntunit']['tickets_outbox'] = $this->mdtickets->count_records_by_sender($this->user['uid']);
		$pagevar['cntunit']['tickets_inbox'] = $this->mdtickets->count_records_by_recipient($this->user['uid']);
		
		if($pagevar['userinfo']['remote']):
			if(intval($pagevar['userinfo']['balance'])<500 && !$pagevar['cntunit']['markets'] && !$pagevar['cntunit']['platforms']):
				$pagevar['userinfo']['locked'] = TRUE;
			endif;
		else:
			if(intval($pagevar['userinfo']['balance'])<500 && !$pagevar['cntunit']['platforms']):
				$pagevar['userinfo']['locked'] = TRUE;
			endif;
		endif;
		
		$this->load->view("clients_interface/control-balance-successful",$pagevar);
	}
	
	public function control_balance_failed(){
		
		/*if(isset($_SERVER['HTTP_REFERER'])):
			$pos = stristr($_SERVER['HTTP_REFERER'],'webmoney.ru');
			if(!$pos):
				redirect('webmaster-panel/actions/balance');
			endif;
		else:
			redirect('webmaster-panel/actions/balance');
		endif;*/
		
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
		
		$this->session->unset_userdata('balance');
		$this->session->unset_userdata('purse');
		$this->mdlog->insert_record($this->user['uid'],'Событие №7: Баланс не пополнен');
		$pagevar['cntunit']['delivers']['notpaid'] = $this->mddelivesworks->count_records_by_webmaster_status($this->user['uid'],0);
		$pagevar['cntunit']['delivers']['total'] = $this->mddelivesworks->count_records_by_webmaster($this->user['uid']);
		$totalsum = $this->mddelivesworks->calc_webmaster_summ($this->user['uid'],'2012-01-01',0);
		$pagevar['cntunit']['delivers']['totalsum'] = $totalsum['sum'];
		$pagevar['cntunit']['platforms'] = $this->mdplatforms->count_records_by_webmaster($this->user['uid']);
		$pagevar['cntunit']['markets'] = $this->mdwebmarkets->count_records($this->user['remoteid']);
		$pagevar['cntunit']['mails']['new'] = $this->mdmessages->count_records_by_recipient_new($this->user['uid'],$this->user['utype']);
		$pagevar['cntunit']['mails']['total'] = $this->mdmessages->count_records_by_recipient($this->user['uid'],$this->user['utype'],$this->user['signdate']);
		$pagevar['cntunit']['tickets_outbox'] = $this->mdtickets->count_records_by_sender($this->user['uid']);
		$pagevar['cntunit']['tickets_inbox'] = $this->mdtickets->count_records_by_recipient($this->user['uid']);
		
		if($pagevar['userinfo']['remote']):
			if(intval($pagevar['userinfo']['balance'])<500 && !$pagevar['cntunit']['markets'] && !$pagevar['cntunit']['platforms']):
				$pagevar['userinfo']['locked'] = TRUE;
			endif;
		else:
			if(intval($pagevar['userinfo']['balance'])<500 && !$pagevar['cntunit']['platforms']):
				$pagevar['userinfo']['locked'] = TRUE;
			endif;
		endif;
		
		$this->load->view("clients_interface/control-balance-failed",$pagevar);
	}
	
	public function control_services(){
		
		if($this->user['debetor']):
			redirect('webmaster-panel/actions/control');
		endif;
		
		$pagevar = array(
					'description'	=> '',
					'author'		=> '',
					'title'			=> 'Кабинет Вебмастера | Дополнительные услуги',
					'baseurl' 		=> base_url(),
					'loginstatus'	=> $this->loginstatus['status'],
					'userinfo'		=> $this->user,
					'attached'		=> $this->mdunion->services_attached_list($this->user['uid']),
					'addservice'	=> TRUE,
					'services'		=> $this->mdservices->read_records(),
					'msgs'			=> $this->session->userdata('msgs'),
					'msgr'			=> $this->session->userdata('msgr')
			);
		$this->session->unset_userdata('msgs');
		$this->session->unset_userdata('msgr');
		
		if($this->input->post('submit')):
			unset($_POST['submit']);
			$this->form_validation->set_rules('service',' ','required|trim');
			if(!$this->form_validation->run()):
				$this->session->set_userdata('msgr','Ошибка. Неверно заполнены необходимые поля<br/>');
				redirect($this->uri->uri_string());
			else:
				if(!$this->mdattachedservices->service_exist($this->user['uid'],$_POST['service'])):
					$platforms = $this->mdplatforms->read_ids_by_webmaster($this->user['uid']);
					$valuesrv = $this->mdvaluesrv->read_zero_price($_POST['service']);
					if(!$valuesrv):
						$valuesrv = $this->mdvaluesrv->read_record_service($_POST['service']);
					endif;
					$pllist = '';
					for($i=0;$i<count($platforms);$i++):
						if($platforms[$i]['remoteid']):
							$pllist .= $platforms[$i]['remoteid'];
							if($i+1<count($platforms)):
								$pllist .=',';
							endif;
						endif;
					endfor;
					if(!empty($pllist)):
//						$param = "siteid=$pllist&birzid=0&optionid=".$_POST['service']."&value=$valuesrv";
						$param = "siteid=$pllist&optionid=".$_POST['service']."&value=$valuesrv";
						$this->API('UpdateAdditionalService',$param);
					endif;
					$this->mdattachedservices->group_insert($this->user['uid'],$_POST['service'],$valuesrv,$platforms,0,0);
					$this->mdlog->insert_record($this->user['uid'],'Событие №8: Подключил дополнительную услугу');
				endif;
				redirect($this->uri->uri_string());
			endif;
		endif;
		
		$cnt = 0;
		for($j=0;$j<count($pagevar['services']);$j++):
			$pagevar['services'][$j]['attached'] = 0;
			for($i=0;$i<count($pagevar['attached']);$i++):
				if($pagevar['services'][$j]['id'] == $pagevar['attached'][$i]['service']):
					$pagevar['services'][$j]['attached'] = 1;
					$cnt++;
				endif;
			endfor;
		endfor;
		if($cnt == count($pagevar['services'])):
			$pagevar['addservice']  =FALSE;
		endif;
		for($i=0;$i<count($pagevar['attached']);$i++):
			$pagevar['attached'][$i]['date'] = $this->operation_dot_date($pagevar['attached'][$i]['date']);
		endfor;
		$pagevar['cntunit']['delivers']['notpaid'] = $this->mddelivesworks->count_records_by_webmaster_status($this->user['uid'],0);
		$pagevar['cntunit']['delivers']['total'] = $this->mddelivesworks->count_records_by_webmaster($this->user['uid']);
		$totalsum = $this->mddelivesworks->calc_webmaster_summ($this->user['uid'],'2012-01-01',0);
		$pagevar['cntunit']['delivers']['totalsum'] = $totalsum['sum'];
		$pagevar['cntunit']['platforms'] = $this->mdplatforms->count_records_by_webmaster($this->user['uid']);
		$pagevar['cntunit']['markets'] = $this->mdwebmarkets->count_records($this->user['remoteid']);
		$pagevar['cntunit']['mails']['new'] = $this->mdmessages->count_records_by_recipient_new($this->user['uid'],$this->user['utype']);
		$pagevar['cntunit']['mails']['total'] = $this->mdmessages->count_records_by_recipient($this->user['uid'],$this->user['utype'],$this->user['signdate']);
		$pagevar['cntunit']['tickets_outbox'] = $this->mdtickets->count_records_by_sender($this->user['uid']);
		$pagevar['cntunit']['tickets_inbox'] = $this->mdtickets->count_records_by_recipient($this->user['uid']);
		
		if(!$pagevar['cntunit']['platforms']):
			redirect('webmaster-panel/actions/control');
		endif;
		
		$this->load->view("clients_interface/control-services",$pagevar);
	}
	
	public function control_services_platforms(){
		
		if($this->user['lock']):
			redirect('webmaster-panel/actions/services');
		endif;
		if($this->user['debetor']):
			redirect('webmaster-panel/actions/control');
		endif;
		$service = $this->uri->segment(5);
		if(!$this->mdattachedservices->service_exist($this->user['uid'],$service)):
			redirect('webmaster-panel/actions/services');
		endif;
		
		$pagevar = array(
					'description'	=> '',
					'author'		=> '',
					'title'			=> 'Кабинет Вебмастера | Дополнительные услуги | Площадки',
					'baseurl' 		=> base_url(),
					'loginstatus'	=> $this->loginstatus['status'],
					'userinfo'		=> $this->user,
					'attached'		=> $this->mdunion->services_attached($service,$this->user['uid']),
					'srvvalues'		=> $this->mdvaluesrv->read_records_service($service),
					'msgs'			=> $this->session->userdata('msgs'),
					'msgr'			=> $this->session->userdata('msgr')
			);
		$this->session->unset_userdata('msgs');
		$this->session->unset_userdata('msgr');
		
		if($this->input->post('submit')):
			unset($_POST['submit']);
			$this->form_validation->set_rules('srvvalues[]',' ','required|trim');
			if(!$this->form_validation->run()):
				$this->session->set_userdata('msgr','Ошибка. Неверно заполнены необходимые поля<br/>');
				redirect($this->uri->uri_string());
			else:
				foreach($_POST['srvvalues'] AS $srvvalues):
					$params = preg_split('/-/',$srvvalues);
					$attached = $this->mdattachedservices->read_record($params[0],$this->user['uid']);
					if($attached):
						$sqlquery = "UPDATE platforms SET ";
						$works = $this->mdtypeswork->read_records();
						$arr_works = $this->mdservices->read_field($attached['service'],'types_works');
						$arr_works = preg_split('/,/',$arr_works);
						for($i=0;$i<count($works);$i++):
							$wadd=$madd=0;
							if(in_array($works[$i]['id'],$arr_works)):
								$wadd = $attached['wprice'];
								$madd = $attached['mprice'];
							endif;
							$sqlquery .= 'c'.$works[$i]['nickname'].' = c'.$works[$i]['nickname'].'-'.$wadd.', m'.$works[$i]['nickname'].' = m'.$works[$i]['nickname'].'-'.$madd;
							if(isset($works[$i+1])):
								$sqlquery .= ', ';
							endif;
						endfor;
						$sqlquery .= ' WHERE platforms.id = '.$attached['platform'];
						$this->mdplatforms->run_query($sqlquery);
						$this->mdattachedservices->update_field($params[0],'valuesrv',$params[1]);
						$wprice = $this->mdvaluesrv->read_field($params[1],'wprice');
						$mprice = $this->mdvaluesrv->read_field($params[1],'mprice');
						$this->mdattachedservices->update_field($params[0],'wprice',$wprice);
						$this->mdattachedservices->update_field($params[0],'mprice',$mprice);
						$attached = $this->mdattachedservices->read_record($params[0],$this->user['uid']);
						$sqlquery = "UPDATE platforms SET ";
						for($i=0;$i<count($works);$i++):
							$wadd=$madd=0;
							if(in_array($works[$i]['id'],$arr_works)):
								$wadd = $attached['wprice'];
								$madd = $attached['mprice'];
							endif;
							$sqlquery .= 'c'.$works[$i]['nickname'].' = c'.$works[$i]['nickname'].'+'.$wadd.', m'.$works[$i]['nickname'].' = m'.$works[$i]['nickname'].'+'.$madd;
							if(isset($works[$i+1])):
								$sqlquery .= ', ';
							endif;
						endfor;
						$sqlquery .= ' WHERE platforms.id = '.$attached['platform'];
						$this->mdplatforms->run_query($sqlquery);
						$plmid = $this->mdplatforms->read_field($attached['platform'],'remoteid');
						if($plmid):
							$param = "siteid=".$plmid."&optionid=".$attached['service']."&value=".$params[1];
//							$param = "siteid=".$plmid."&birzid=0&optionid=".$attached['service']."&value=".$params[1];
							$this->API('UpdateAdditionalService',$param);
						endif;
					endif;
				endforeach;
				$this->session->set_userdata('msgs','Свойства сохранены');
				redirect('webmaster-panel/actions/services');
			endif;
		endif;
		
		$pagevar['cntunit']['delivers']['notpaid'] = $this->mddelivesworks->count_records_by_webmaster_status($this->user['uid'],0);
		$pagevar['cntunit']['delivers']['total'] = $this->mddelivesworks->count_records_by_webmaster($this->user['uid']);
		$totalsum = $this->mddelivesworks->calc_webmaster_summ($this->user['uid'],'2012-01-01',0);
		$pagevar['cntunit']['delivers']['totalsum'] = $totalsum['sum'];
		$pagevar['cntunit']['platforms'] = $this->mdplatforms->count_records_by_webmaster($this->user['uid']);
		$pagevar['cntunit']['markets'] = $this->mdwebmarkets->count_records($this->user['remoteid']);
		$pagevar['cntunit']['mails']['new'] = $this->mdmessages->count_records_by_recipient_new($this->user['uid'],$this->user['utype']);
		$pagevar['cntunit']['mails']['total'] = $this->mdmessages->count_records_by_recipient($this->user['uid'],$this->user['utype'],$this->user['signdate']);
		$pagevar['cntunit']['tickets_outbox'] = $this->mdtickets->count_records_by_sender($this->user['uid']);
		$pagevar['cntunit']['tickets_inbox'] = $this->mdtickets->count_records_by_recipient($this->user['uid']);
		
		if(!$pagevar['cntunit']['platforms']):
			redirect('webmaster-panel/actions/control');
		endif;
		
		$this->load->view("clients_interface/control-services-platforms",$pagevar);
	}
	
	public function control_services_delete(){
		
		if($this->user['lock']):
			redirect('webmaster-panel/actions/services');
		endif;
		if($this->user['debetor']):
			redirect('webmaster-panel/actions/control');
		endif;
		$sid = $this->uri->segment(6);
		if($sid):
			$attached = $this->mdattachedservices->read_records_service($sid,$this->user['uid']);
			$works = $this->mdtypeswork->read_records();
			for($i=0;$i<count($attached);$i++):
				$sqlquery = "UPDATE platforms SET ";
				$arr_works = $this->mdservices->read_field($attached[$i]['service'],'types_works');
				$arr_works = preg_split('/,/',$arr_works);
				for($j=0;$j<count($works);$j++):
					$wadd=$madd=0;
					if(in_array($works[$j]['id'],$arr_works)):
						$wadd = $attached[$i]['wprice'];
						$madd = $attached[$i]['mprice'];
					endif;
					$sqlquery .= 'c'.$works[$j]['nickname'].' = c'.$works[$j]['nickname'].'-'.$wadd.', m'.$works[$j]['nickname'].' = m'.$works[$j]['nickname'].'-'.$madd;
					if(isset($works[$j+1])):
						$sqlquery .= ', ';
					endif;
				endfor;
				$sqlquery .= ' WHERE platforms.id = '.$attached[$i]['platform'];
				$this->mdplatforms->run_query($sqlquery);
			endfor;
			$result = $this->mdattachedservices->delete_records($sid,$this->user['uid']);
			if($result):
				$this->mdlog->insert_record($this->user['uid'],'Событие №25: Отключил дополнительную услугу');
				$this->session->set_userdata('msgs','Дополнительна услуга отключена успешно');
			else:
				$this->session->set_userdata('msgr','Дополнительна услуга не отключена');
			endif;
			redirect('webmaster-panel/actions/services');
		else:
			show_404();
		endif;
	}
	
	/*************************************************** markets *********************************************************/	
	
	public function control_markets(){

		if(!$this->user['remote']):
			show_404();
		endif;
		if($this->user['debetor']):
			redirect('webmaster-panel/actions/control');
		endif;
		$pagevar = array(
					'description'	=> '',
					'author'		=> '',
					'title'			=> 'Кабинет Вебмастера | Биржи',
					'baseurl' 		=> base_url(),
					'loginstatus'	=> $this->loginstatus['status'],
					'userinfo'		=> $this->user,
					'accounts'		=> $this->mdunion->read_markets_by_webmaster($this->user['remoteid']),
					'markets'		=> $this->mdmarkets->read_records(),
					'cntunit'		=> array(),
					'msgs'			=> $this->session->userdata('msgs'),
					'msgr'			=> $this->session->userdata('msgr')
			);
		$this->session->unset_userdata('msgs');
		$this->session->unset_userdata('msgr');
		
		if($this->input->post('amsubmit')):
			$_POST['mtsubmit'] = NULL;
			$this->form_validation->set_rules('market',' ','required|trim');
			$this->form_validation->set_rules('login',' ','required|trim');
			$this->form_validation->set_rules('password',' ','required|trim');
			if(!$this->form_validation->run()):
				$this->session->set_userdata('msgr','Ошибка при сохранении. Не заполены необходимые поля.');
				redirect($this->uri->uri_string());
			else:
				$param = 'birzid='.$_POST['market'].'&login='.$_POST['login'].'&pass='.base64_encode($_POST['password']);
				$market_id = $this->API('AddNewAccount',$param);
				if($market_id['id']):
					$param = 'userid='.$this->user['remoteid'].'&accid='.$market_id['id'];
					$res = $this->API('AddAccountToUser',$param);
					if(!$this->mdwebmarkets->exist_market($market_id['id'])):
						$id = $this->mdwebmarkets->insert_record($market_id['id'],$this->user['remoteid'],$_POST);
						if($id):
							$this->mdlog->insert_record($this->user['uid'],'Событие №9: Добавлена учетная запись на бирже');
							$this->session->set_userdata('msgs','Запись создана успешно');
						endif;
					endif;
					//Получить список сайтов аккаунта с настройками сайтов
					$param = 'birzid='.$_POST['market'].'&accid='.$market_id['id'];
					$platforms = $this->API('GetSitesFromAccount',$param);
					$cntpl = 0;
					$cntpl = $this->load_platforms($platforms,$_POST);
					$msgs = $this->session->userdata('msgs');
					if($cntpl['import']):
						$this->session->set_userdata('msgs',$msgs.'<br/>Площадки импортированы. Количество: '.$cntpl['import']);
						if(count($cntpl['alien'])):
							$msgs = $this->session->userdata('msgs');
							$msgs .= '<br/>На созданном биржевом аккаунте были обнаружены сайты, которые нам не удалось импортировать в систему. Пожалуйста, '.anchor('webmaster-panel/actions/tickets','создайте тикет').' с данным вопросом.<br/>Cписок не импортированных сайтов: <br/>';
							for($site = 0;$site<count($cntpl['alien']);$site++):
								$msgs .= $cntpl['alien'][$site].'<br/>';
							endfor;
							$this->session->set_userdata('msgs',$msgs);
						endif;
					else:
						$this->session->set_userdata('msgs',$msgs.'<br/><span class="red">Внимание! Площадки отсутствуют!<br/>Воспользуйтесь кнопкой <span class="btn btn-info"><i class="icon-refresh icon-white"></i></span> для импорта Ваших площадок.</span>');
					endif;
				else:
					$this->session->set_userdata('msgr','Ошибка. Невозможно импортировать аккаунт биржи');
				endif;
			endif;
			redirect($this->uri->uri_string());
		endif;
		
		if($this->input->post('smsubmit')):
			unset($_POST['smsubmit']);

			$this->form_validation->set_rules('mid',' ','required|trim');
			$this->form_validation->set_rules('market',' ','trim');
			$this->form_validation->set_rules('login',' ','trim');
			$this->form_validation->set_rules('password',' ','required|trim');
			if(!$this->form_validation->run()):
				$this->session->set_userdata('msgr','Ошибка при сохранении. Не заполены необходимые поля.');
				redirect($this->uri->uri_string());
			else:
				$account = $this->mdwebmarkets->read_record($_POST['mid']);
				$param = 'accid='.$_POST['mid'].'&birzid='.$account['market'].'&login='.$_POST['login'].'&pass='.base64_encode($_POST['password']).'&act=1';
				$this->API('UpdateAccount',$param);
				$this->mdwebmarkets->update_record($_POST['mid'],$this->user['remoteid'],$_POST);
				$this->mdmkplatform->update_records($this->user['uid'],$account['login'],$account['market'],$account['password'],$_POST['password'],$_POST['login'],NULL);
				$this->mdlog->insert_record($this->user['uid'],'Событие №26: Изменена учетная запись на бирже');
				
				ob_start();
				?>
				<img src="<?=base_url();?>images/logo.png" alt="" />
				<p><strong>Здравствуйте, <?=$this->mdusers->read_field(2,'fio');?></strong></p>
				<p>Вебмастер изменил аккаунт на бирже: <?=$pagevar['markets'][$account['market']-1]['title'];?><br/>
				Данные:</p>
				<p>Вебмастер: <?=$this->user['ulogin'];?><br/>
				Биржа: <?=$pagevar['markets'][$account['market']-1]['title'];?><br/>
				Логин: <?=$_POST['login'];?><br/>
				Пароль: <?=$_POST['password'];?><br/></p>
				<br/><br/><p><a href="http://www.bystropost.ru/">С уважением, www.Bystropost.ru</a></p>
				<?
				$mailtext = ob_get_clean();
				
				$this->email->clear(TRUE);
				$config['smtp_host'] = 'localhost';
				$config['charset'] = 'utf-8';
				$config['wordwrap'] = TRUE;
				$config['mailtype'] = 'html';
				
				$this->email->initialize($config);
				$this->email->to($this->mdusers->read_field(2,'login'));
				$this->email->from('admin@bystropost.ru','Bystropost.ru - Система мониторинга и управления');
				$this->email->bcc('');
				$this->email->subject('Bystropost.ru - Изменения по аккаунту на бирже.');
				$this->email->message($mailtext);
				$this->email->send();
				
				$this->session->set_userdata('msgs','Аккаунт успешно сохранен');
			endif;
			redirect($this->uri->uri_string());
		endif;
		
		$pagevar['cntunit']['delivers']['notpaid'] = $this->mddelivesworks->count_records_by_webmaster_status($this->user['uid'],0);
		$pagevar['cntunit']['delivers']['total'] = $this->mddelivesworks->count_records_by_webmaster($this->user['uid']);
		$totalsum = $this->mddelivesworks->calc_webmaster_summ($this->user['uid'],'2012-01-01',0);
		$pagevar['cntunit']['delivers']['totalsum'] = $totalsum['sum'];
		$pagevar['cntunit']['platforms'] = $this->mdplatforms->count_records_by_webmaster($this->user['uid']);
		$pagevar['cntunit']['markets'] = $this->mdwebmarkets->count_records($this->user['remoteid']);
		$pagevar['cntunit']['mails']['new'] = $this->mdmessages->count_records_by_recipient_new($this->user['uid'],$this->user['utype']);;
		$pagevar['cntunit']['mails']['total'] = $this->mdmessages->count_records_by_recipient($this->user['uid'],$this->user['utype'],$this->user['signdate']);
		$pagevar['cntunit']['tickets_outbox'] = $this->mdtickets->count_records_by_sender($this->user['uid']);
		$pagevar['cntunit']['tickets_inbox'] = $this->mdtickets->count_records_by_recipient($this->user['uid']);
		
		if(intval($pagevar['userinfo']['balance'])<500 && !$pagevar['cntunit']['markets'] && !$pagevar['cntunit']['platforms']):
			redirect('webmaster-panel/actions/control');
		endif;
		for($i=0;$i<count($pagevar['accounts']);$i++):
			$pagevar['accounts'][$i]['password'] = $this->encrypt->decode($pagevar['accounts'][$i]['cryptpassword']);
		endfor;
		$this->load->view("clients_interface/control-markets",$pagevar);
	}
	
	public function control_delete_markets(){
		
		if(!$this->user['remote']):
			show_404();
		endif;
		if($this->user['debetor']):
			redirect('webmaster-panel/actions/control');
		endif;
		$mid = $this->uri->segment(6);
		if($mid):
			$info = $this->mdwebmarkets->read_record($mid);
			$result = $this->mdwebmarkets->delete_record($this->user['remoteid'],$mid);
			if($result):
				$param = 'accid='.$info['id'].'&birzid='.$info['market'].'&login='.$info['login'].'&password='.base64_encode($this->encrypt->decode($info['cryptpassword'])).'&act=0';
				$this->API('UpdateAccount',$param);
				$this->mdmkplatform->delete_records_by_webmarket($this->user['uid'],$info['market'],$info['login'],$info['password']);
				$plmarkets = $this->mdunion->free_platforms($this->user['uid']);
				for($i=0;$i<count($plmarkets);$i++):
					if(is_null($plmarkets[$i]['mkid'])):
						$param = 'siteid='.$plmarkets[$i]['remoteid'].'&value=1';
						$this->API('SetSiteActive',$param);
					endif;
				endfor;
				$this->mdlog->insert_record($this->user['uid'],'Событие №10: Удалена учетная запись на бирже');
				$this->session->set_userdata('msgs','Запись удалена успешно');
			else:
				$this->session->set_userdata('msgr','Запись не удалено');
			endif;
			redirect('webmaster-panel/actions/markets');
		else:
			show_404();
		endif;
	}
	
	public function control_disabled_markets(){
		
		if(!$this->user['remote']):
			show_404();
		endif;
		if($this->user['debetor']):
			redirect('webmaster-panel/actions/control');
		endif;
		$mid = $this->uri->segment(6);
		if($mid):
			$info = $this->mdwebmarkets->read_record($mid);
			$result = $this->mdwebmarkets->update_status($this->user['remoteid'],$mid,0);
			if($result):
				$param = 'accid='.$info['id'].'&birzid='.$info['market'].'&login='.$info['login'].'&password='.base64_encode($this->encrypt->decode($info['cryptpassword'])).'&act=0';
				$this->API('UpdateAccount',$param);
				$this->mdmkplatform->delete_records_by_webmarket($this->user['uid'],$info['market'],$info['login'],$info['password']);
				$plmarkets = $this->mdunion->free_platforms($this->user['uid']);
				for($i=0;$i<count($plmarkets);$i++):
					if(is_null($plmarkets[$i]['mkid'])):
						$param = 'siteid='.$plmarkets[$i]['remoteid'].'&value=1';
						$this->API('SetSiteActive',$param);
					endif;
				endfor;
				$this->mdlog->insert_record($this->user['uid'],'Событие №10: Отключена учетная запись на бирже');
				$this->session->set_userdata('msgs','Запись отключена успешно');
			else:
				$this->session->set_userdata('msgr','Запись не отключена');
			endif;
			redirect('webmaster-panel/actions/markets');
		else:
			show_404();
		endif;
	}
	
	public function control_enabled_markets(){
		
		if(!$this->user['remote']):
			show_404();
		endif;
		if($this->user['debetor']):
			redirect('webmaster-panel/actions/control');
		endif;
		$mid = $this->uri->segment(6);
		if($mid):
			$info = $this->mdwebmarkets->read_record($mid);
			$result = $this->mdwebmarkets->update_status($this->user['remoteid'],$mid,1);
			if($result):
				$param = 'accid='.$info['id'].'&birzid='.$info['market'].'&login='.$info['login'].'&password='.base64_encode($this->encrypt->decode($info['cryptpassword'])).'&act=1';
				$this->API('UpdateAccount',$param);
				$this->mdlog->insert_record($this->user['uid'],'Событие №27: Включена учетная запись на бирже');
				$this->session->set_userdata('msgs','Запись включена успешно');
			else:
				$this->session->set_userdata('msgr','Запись не включена');
			endif;
			redirect('webmaster-panel/actions/markets');
		else:
			show_404();
		endif;
	}
	
	public function control_market_parsing(){
		
		$statusval = array('status'=>TRUE,'import'=>0);
		$market = trim($this->input->post('market'));
		if(!$market):
			show_404();
		endif;
		$info = $this->mdwebmarkets->read_owner_market($market,$this->user['remoteid']);
		$param = 'accid='.$info['id'].'&birzid='.$info['market'];
		$statusval['import'] = $this->API('ImportSitesFromAccount',$param);
		echo json_encode($statusval);
	}
	
	public function control_market_loading(){
		
		$statusval = array('status'=>TRUE,'plcnt'=>0,'plload'=>0,'alien'=>0);
		$webmarket = trim($this->input->post('market'));
		if(!$webmarket):
			show_404();
		endif;
		$market = $this->mdwebmarkets->read_owner_market($webmarket,$this->user['remoteid']);
		$market['password'] = $this->encrypt->decode($market['cryptpassword']);
		$param = 'birzid='.$market['market'].'&accid='.$webmarket;
		$platforms = $this->API('GetSitesFromAccount',$param);
		if(!isset($platforms['error'])):
			$statusval['plload'] = count($platforms);
			$imported = $this->load_platforms($platforms,$market);
			$statusval['plcnt'] = $imported['import'];
			$statusval['alien'] = $imported['alien'];
		endif;
		echo json_encode($statusval);
	}
	
/************************************************************ mails *********************************************************/	
	
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
					if($this->mdusers->read_field($_POST['recipient'],'type') == 2):
						$this->mdmessages->send_noreply_message($this->user['uid'],0,2,5,'Вебмастер '.$this->user['ulogin'].' написал письмо менеджеру '.$this->mdusers->read_field($_POST['recipient'],'login').'<br/><br/>'.$_POST['text']);
					endif;
					$this->session->set_userdata('msgs','Сообщение отправлено');
				endif;
				if(isset($_POST['sendmail'])):
					ob_start();
					?>
					<img src="<?=base_url();?>images/logo.png" alt="" />
					<p><strong>Здравствуйте, <?=$this->mdusers->read_field($_POST['recipient'],'fio');?></strong></p>
					<p>У вас новое сообщение.</p>
					<p>Что бы прочитать его войдите в <?=$this->link_cabinet($_POST['recipient']);?> и перейдите в раздел "Тикеты"</p>
					<p><br/><?=$this->sub_mailtext($_POST['text'],$_POST['recipient']);?><br/></p>
					<br/><br/><p><a href="http://www.bystropost.ru/">С уважением, www.Bystropost.ru</a></p>
					<?
					$mailtext = ob_get_clean();
					
					$this->email->clear(TRUE);
					$config['smtp_host'] = 'localhost';
					$config['charset'] = 'utf-8';
					$config['wordwrap'] = TRUE;
					$config['mailtype'] = 'html';
					
					$this->email->initialize($config);
					$this->email->to($this->mdusers->read_field($_POST['recipient'],'login'));
					$this->email->from('admin@bystropost.ru','Bystropost.ru - Система мониторинга и управления');
					$this->email->bcc('');
					$this->email->subject('Bystropost.ru - Почта. Новое сообщение');
					$this->email->message($mailtext);	
					$this->email->send();
				endif;
			endif;
			redirect($this->uri->uri_string());
		endif;
		
		for($i=0;$i<count($pagevar['mails']);$i++):
			$pagevar['mails'][$i]['date'] = $this->operation_dot_date_on_time($pagevar['mails'][$i]['date']);
		endfor;
		$this->mdmessages->set_read_mails_by_recipient($this->user['uid'],$this->user['utype']);
		$config['base_url'] 	= $pagevar['baseurl'].'webmaster-panel/actions/mails/from/';
		$config['uri_segment'] 	= 5;
		$config['total_rows'] 	= $this->mdunion->count_mails_by_recipient($this->user['uid'],$this->user['utype'],$this->user['signdate']);
		$config['per_page'] 	= 10;
		$config['num_links'] 	= 4;
		$config['first_link']		= 'В начало';
		$config['last_link'] 		= 'В конец';
		$config['next_link'] 		= 'Далее &raquo;';
		$config['prev_link'] 		= '&laquo; Назад';
		$config['cur_tag_open']		= '<li class="active"><a href="#">';
		$config['cur_tag_close'] 	= '</a></li>';
		$config['full_tag_open'] 	= '<div class="pagination"><ul>';
		$config['full_tag_close'] 	= '</ul></div>';
		$config['first_tag_open'] 	= '<li>';
		$config['first_tag_close'] 	= '</li>';
		$config['last_tag_open'] 	= '<li>';
		$config['last_tag_close'] 	= '</li>';
		$config['next_tag_open'] 	= '<li>';
		$config['next_tag_close'] 	= '</li>';
		$config['prev_tag_open'] 	= '<li>';
		$config['prev_tag_close'] 	= '</li>';
		$config['num_tag_open'] 	= '<li>';
		$config['num_tag_close'] 	= '</li>';
		
		$this->pagination->initialize($config);
		$pagevar['pages'] = $this->pagination->create_links();
		
		$pagevar['cntunit']['delivers']['notpaid'] = $this->mddelivesworks->count_records_by_webmaster_status($this->user['uid'],0);
		$pagevar['cntunit']['delivers']['total'] = $this->mddelivesworks->count_records_by_webmaster($this->user['uid']);
		$totalsum = $this->mddelivesworks->calc_webmaster_summ($this->user['uid'],'2012-01-01',0);
		$pagevar['cntunit']['delivers']['totalsum'] = $totalsum['sum'];
		$pagevar['cntunit']['platforms'] = $this->mdplatforms->count_records_by_webmaster($this->user['uid']);
		$pagevar['cntunit']['markets'] = $this->mdwebmarkets->count_records($this->user['remoteid']);
		$pagevar['cntunit']['mails']['new'] = 0;
		$pagevar['cntunit']['mails']['total'] = $this->mdmessages->count_records_by_recipient($this->user['uid'],$this->user['utype'],$this->user['signdate']);
		$pagevar['cntunit']['tickets_outbox'] = $this->mdtickets->count_records_by_sender($this->user['uid']);
		$pagevar['cntunit']['tickets_inbox'] = $this->mdtickets->count_records_by_recipient($this->user['uid']);
		
		if($pagevar['userinfo']['remote']):
			if(intval($pagevar['userinfo']['balance'])<500 && !$pagevar['cntunit']['markets'] && !$pagevar['cntunit']['platforms']):
				$pagevar['userinfo']['locked'] = TRUE;
			endif;
		else:
			if(intval($pagevar['userinfo']['balance'])<500 && !$pagevar['cntunit']['platforms']):
				$pagevar['userinfo']['locked'] = TRUE;
			endif;
		endif;
		
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
					'total'			=> $this->mddelivesworks->calc_webmaster_summ($this->user['uid'],'2012-01-01',0),
					'delivers'		=> array(),
					'view'			=> FALSE,
					'filter'		=> array('fpaid'=>1,'fnotpaid'=>1),
					'cntwork'		=> 25,
					'msgs'			=> $this->session->userdata('msgs'),
					'msgr'			=> $this->session->userdata('msgr')
			);
		$this->session->unset_userdata('msgs');
		$this->session->unset_userdata('msgr');
		
		if($this->session->userdata('jobsfilter') != ''):
			$filter = preg_split("/,/",$this->session->userdata('jobsfilter'));
			if(count($filter) == 1):
				$pagevar['filter']['fpaid'] = ($filter[0])?1:0;
				$pagevar['filter']['fnotpaid'] = (!$filter[0])?1:0;
			endif;
		else:
			$this->session->set_userdata('jobsfilter','0,1');
		endif;
		if($this->session->userdata('jobscount') != ''):
			$pagevar['cntwork'] = $this->session->userdata('jobscount');
		else:
			$this->session->set_userdata('jobscount',25);
		endif;
		if($this->uri->segment(6)):
			if(!$this->mdplatforms->ownew_all_platform($this->user['uid'],$this->uri->segment(6))):
				redirect($_SERVER['HTTP_REFERER']);
			endif;
			$from = intval($this->uri->segment(8));
			$pagevar['delivers'] = $this->mdunion->delivers_works_platform($this->uri->segment(6),$this->session->userdata('jobscount'),intval($this->uri->segment(8)),$this->session->userdata('jobsfilter'));
			$count = $this->mdunion->count_delivers_works_platform($this->uri->segment(6),$this->session->userdata('jobsfilter'));
			$config['base_url'] 	= $pagevar['baseurl'].'webmaster-panel/actions/finished-jobs/platform/platformid/'.$this->uri->segment(6).'/from/';
			$config['uri_segment'] 	= 8;
		else:
			$from = $this->uri->segment(5);
			$pagevar['delivers'] = $this->mdunion->delivers_works_webmaster($this->user['uid'],$this->session->userdata('jobscount'),intval($this->uri->segment(5)),$this->session->userdata('jobsfilter'));
			$count = $this->mdunion->count_delivers_works_webmaster($this->user['uid'],$this->session->userdata('jobsfilter'));
			$config['base_url'] 	= $pagevar['baseurl'].'webmaster-panel/actions/finished-jobs/from/';
			$config['uri_segment'] 	= 5;
		endif;
		if(!$pagevar['delivers'] && !isset($filter)):
			redirect('webmaster-panel/actions/control');
		endif;
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
					$bresult = $this->mdusers->change_user_balance($this->user['uid'],-$_POST['summa']);
					if($bresult):
						$this->mddelivesworks->update_status($this->user['uid'],$_POST['works']);
						$this->mdlog->insert_record($this->user['uid'],'Событие №11: Произведена оплата за выполненные работы');
						$mprice = 0;
						for($i=0;$i<count($_POST['works']);$i++):
							$wprice = $this->mddelivesworks->read_field($_POST['works'][$i],'wprice');
							$mprice = $this->mddelivesworks->read_field($_POST['works'][$i],'mprice');
							$manager = $this->mddelivesworks->read_field($_POST['works'][$i],'manager');
							if($manager):
								$this->mdusers->change_user_balance($manager,$mprice);
							endif;
							$this->mdusers->change_admins_balance($wprice-$mprice);
							$this->mdfillup->insert_record($this->user['uid'],$wprice,'Оплата за выполненное задание ID='.$_POST['works'][$i],0,0);
							if($this->user['partner'] > 0):
								$pprice = round($wprice*0.05,2);
								$this->mdusers->change_user_balance($this->user['partner'],$pprice);
								$this->mdfillup->insert_record($this->user['partner'],$pprice,'Средства по партнерской программе',0,1);
							endif;
						endfor;
						
						$message = 'Спасибо за оплату.';
						
						$date = date("Y-m-d",mktime(0,0,0,date("m"),date("d")-5,date("Y")));
						$debetor = $this->mddelivesworks->calc_user_debet($this->user['uid'],$date,'<=');
						if(!$debetor):
							$this->mdusers->update_field($this->user['uid'],'debetor',0);
							if($this->user['remoteid']):
								$markets = $this->mdwebmarkets->read_records($this->user['remoteid']);
								for($i=0;$i<count($markets);$i++):
									$param = 'accid='.$markets[$i]['id'].'&birzid='.$markets[$i]['market'].'&login='.$markets[$i]['login'].'&pass='.base64_encode($this->encrypt->decode($markets[$i]['cryptpassword'])).'&act=1';
									$this->API('UpdateAccount',$param);
								endfor;
							endif;
							$message .= '<br/>Внимание! Ваш аккаунт разблокирован.';
						endif;
						
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
		
		if($this->input->post('subexport')):
			unset($_POST['subexport']);
			$this->form_validation->set_rules('bbegin',' ','required|trim');
			$this->form_validation->set_rules('bend',' ','required|trim');
			if(!$this->form_validation->run()):
				$this->session->set_userdata('msgr','Ошибка. Повторите ввод.');
				redirect($this->uri->uri_string());
			else:
				$paid = $notpaid = 0;
				if(isset($_POST['notpaid'])):
					$notpaid = 1;
				endif;
				if(isset($_POST['paid'])):
					$paid = 1;
				endif;
				if(!$paid && !$notpaid):
					$paid = $notpaid = 1;
				endif;
				$pattern = "/(\d+)\.(\w+)\.(\d+)/i";
				$replacement = "\$3-\$2-\$1";
				$_POST['bbegin'] = preg_replace($pattern,$replacement,$_POST['bbegin']);
				
				$pattern = "/(\d+)\.(\w+)\.(\d+)/i";
				$replacement = "\$3-\$2-\$1";
				$_POST['bend'] = preg_replace($pattern,$replacement,$_POST['bend']);
				if($_POST['bend'] < $_POST['bbegin']):
					$begin = $_POST['bbegin'];
					$_POST['bbegin'] = $_POST['bend'];
					$_POST['bend'] = $begin;
				endif;
				$delivers = $this->mdunion->delivers_works_webmaster_all($this->user['uid'],$_POST['bbegin'],$_POST['bend'],$paid,$notpaid);
				if(count($delivers)):
					for($i=0;$i<count($delivers);$i++):
						$delivers[$i]['date'] = $this->operation_dot_date($delivers[$i]['date']);
						if($delivers[$i]['status']):
							$delivers[$i]['datepaid'] = $this->operation_dot_date($delivers[$i]['datepaid']);
						else:
							$delivers[$i]['datepaid'] = mb_convert_encoding('не оплачено','Windows-1251','utf-8');
						endif;
					endfor;
					$file_name = getcwd().'/documents/works'.$this->user['uid'].date("Ymd").'.csv';
					$fp = fopen($file_name,'w');
					$this->load->helper('download');
					$mass[0] = array(
						'num'=>mb_convert_encoding('№ п/п','Windows-1251','utf-8'),
						'date'=>mb_convert_encoding('Дата сдачи','Windows-1251','utf-8'),
						'datepaid'=>mb_convert_encoding('Дата оплаты','Windows-1251','utf-8'),
						'ptitle'=>mb_convert_encoding('Площадка','Windows-1251','utf-8'),
						'twtitle'=>mb_convert_encoding('Тип работы','Windows-1251','utf-8'),
						'mtitle'=>mb_convert_encoding('Биржа','Windows-1251','utf-8'),
						'countchars'=>mb_convert_encoding('Количество символов','Windows-1251','utf-8'),
						'ulrlink'=>mb_convert_encoding('URL работы','Windows-1251','utf-8'),
						'mkprice'=>mb_convert_encoding('Цена на бирже','Windows-1251','utf-8'),
						'wprice'=>mb_convert_encoding('Цена за работу','Windows-1251','utf-8')
					);
					for($i=1;$i<count($delivers);$i++):
						$mass[$i]['num'] = $i;
						$mass[$i]['date'] = $delivers[$i]['date'];
						$mass[$i]['datepaid'] = $delivers[$i]['datepaid'];
						$mass[$i]['ptitle'] = mb_convert_encoding($delivers[$i]['ptitle'],'Windows-1251','utf-8');
						$mass[$i]['twtitle'] = mb_convert_encoding($delivers[$i]['twtitle'],'Windows-1251','utf-8');
						$mass[$i]['mtitle'] = mb_convert_encoding($delivers[$i]['mtitle'],'Windows-1251','utf-8');
						$mass[$i]['countchars'] = $delivers[$i]['countchars'];
						$mass[$i]['ulrlink'] = mb_convert_encoding($delivers[$i]['ulrlink'],'Windows-1251','utf-8');
						$mass[$i]['mkprice'] = $delivers[$i]['mkprice'];
						$mass[$i]['wprice'] = $delivers[$i]['wprice'];
					endfor;
					foreach($mass AS $mas):
						fputcsv($fp,$mas,';');
					endforeach;
					fclose($fp);
					$fdata = file_get_contents($file_name);
					unlink($file_name);
					if($fdata && $file_name):
						force_download('works'.$this->user['uid'].date("Ymd").'.csv',$fdata);
					endif;
				endif;
			endif;
		endif;
		
		if($this->input->post('scsubmit')):
			unset($_POST['scsubmit']);
			$result = $this->mdunion->read_webmaster_jobs($this->user['uid'],$_POST['srdjid'],$_POST['srdjurl']);
			$pagevar['title'] .= 'Поиск выполнен';
			$pagevar['delivers'] = $result;
			$pagevar['pages'] = NULL;
		endif;
		
		$values = array();
		for($i=0;$i<count($pagevar['delivers']);$i++):
			$pagevar['delivers'][$i]['date'] = $this->operation_dot_date($pagevar['delivers'][$i]['date']);
			if(!$pagevar['delivers'][$i]['status']):
				$values[$i]= $pagevar['delivers'][$i]['wprice'];
			endif;
		endfor;
		if(count($values)):
			$pagevar['minprice'] = min($values);
		else:
			$pagevar['minprice'] = 0;
		endif;
		unset($values);

		$config['total_rows'] 		= $count;
		$config['per_page'] 		= $this->session->userdata('jobscount');
		$config['num_links'] 		= 4;
		$config['first_link']		= 'В начало';
		$config['last_link'] 		= 'В конец';
		$config['next_link'] 		= 'Далее &raquo;';
		$config['prev_link'] 		= '&laquo; Назад';
		$config['cur_tag_open']		= '<li class="active"><a href="#">';
		$config['cur_tag_close'] 	= '</a></li>';
		$config['full_tag_open'] 	= '<div class="pagination"><ul>';
		$config['full_tag_close'] 	= '</ul></div>';
		$config['first_tag_open'] 	= '<li>';
		$config['first_tag_close'] 	= '</li>';
		$config['last_tag_open'] 	= '<li>';
		$config['last_tag_close'] 	= '</li>';
		$config['next_tag_open'] 	= '<li>';
		$config['next_tag_close'] 	= '</li>';
		$config['prev_tag_open'] 	= '<li>';
		$config['prev_tag_close'] 	= '</li>';
		$config['num_tag_open'] 	= '<li>';
		$config['num_tag_close'] 	= '</li>';
		
		$this->pagination->initialize($config);
		$pagevar['pages'] = $this->pagination->create_links();
		
		$pagevar['cntunit']['delivers']['notpaid'] = $this->mddelivesworks->count_records_by_webmaster_status($this->user['uid'],0);
		$pagevar['cntunit']['delivers']['total'] = $this->mddelivesworks->count_records_by_webmaster($this->user['uid']);
		$totalsum = $this->mddelivesworks->calc_webmaster_summ($this->user['uid'],'2012-01-01',0);
		$pagevar['cntunit']['delivers']['totalsum'] = $totalsum['sum'];
		$pagevar['cntunit']['platforms'] = $this->mdplatforms->count_records_by_webmaster($this->user['uid']);
		$pagevar['cntunit']['markets'] = $this->mdwebmarkets->count_records($this->user['remoteid']);
		$pagevar['cntunit']['mails']['new'] = $this->mdmessages->count_records_by_recipient_new($this->user['uid'],$this->user['utype']);
		$pagevar['cntunit']['mails']['total'] = $this->mdmessages->count_records_by_recipient($this->user['uid'],$this->user['utype'],$this->user['signdate']);
		$pagevar['cntunit']['tickets_outbox'] = $this->mdtickets->count_records_by_sender($this->user['uid']);
		$pagevar['cntunit']['tickets_inbox'] = $this->mdtickets->count_records_by_recipient($this->user['uid']);
		
		for($i=0;$i<count($pagevar['delivers']);$i++):
			if(mb_strlen($pagevar['delivers'][$i]['ulrlink'],'UTF-8') > 15):
				$pagevar['delivers'][$i]['link'] = mb_substr($pagevar['delivers'][$i]['ulrlink'],0,15,'UTF-8');
				$pagevar['delivers'][$i]['link'] .= ' ... '.mb_substr($pagevar['delivers'][$i]['ulrlink'],strlen($pagevar['delivers'][$i]['ulrlink'])-10,10,'UTF-8');;
			else:
				$pagevar['delivers'][$i]['link'] = $pagevar['delivers'][$i]['ulrlink'];
			endif;
		endfor;
		
		$this->load->view("clients_interface/control-finished-jobs",$pagevar);
	}
	
	public function control_task_in_work(){

		$pagevar = array(
				'description'	=> '',
				'author'		=> '',
				'title'			=> 'Кабинет Вебмастера | Задания в работе',
				'baseurl' 		=> base_url(),
				'loginstatus'	=> $this->loginstatus['status'],
				'userinfo'		=> $this->user,
				'cntunit'		=> array(),
				'pages'			=> array(),
				'minprice'		=> 0,
				'delivers'		=> array(),
				'view'			=> FALSE,
				'filter'		=> array('fpaid'=>1,'fnotpaid'=>1),
				'cntwork'		=> 25,
				'msgs'			=> $this->session->userdata('msgs'),
				'msgr'			=> $this->session->userdata('msgr')
			);
		$this->session->unset_userdata('msgs');
		$this->session->unset_userdata('msgr');
		// Получаем задния в работе
		$param = 'userid='.$this->user['remoteid'];
		$data = $this->API('GetTaskInWork',$param);
		if(isset($data['status']) && (int)$data['status']==0) $pagevar['items'] = array();
		else $pagevar['items'] = $data;
		$pagevar['cntunit']['delivers']['notpaid'] = $this->mddelivesworks->count_records_by_webmaster_status($this->user['uid'],0);
		$pagevar['cntunit']['delivers']['total'] = $this->mddelivesworks->count_records_by_webmaster($this->user['uid']);
		$totalsum = $this->mddelivesworks->calc_webmaster_summ($this->user['uid'],'2012-01-01',0);
		$pagevar['cntunit']['delivers']['totalsum'] = $totalsum['sum'];
		$pagevar['cntunit']['platforms'] = $this->mdplatforms->count_records_by_webmaster($this->user['uid']);
		$pagevar['cntunit']['markets'] = $this->mdwebmarkets->count_records($this->user['remoteid']);
		$pagevar['cntunit']['mails']['new'] = $this->mdmessages->count_records_by_recipient_new($this->user['uid'],$this->user['utype']);
		$pagevar['cntunit']['mails']['total'] = $this->mdmessages->count_records_by_recipient($this->user['uid'],$this->user['utype'],$this->user['signdate']);
		$pagevar['cntunit']['tickets_outbox'] = $this->mdtickets->count_records_by_sender($this->user['uid']);
		$pagevar['cntunit']['tickets_inbox'] = $this->mdtickets->count_records_by_recipient($this->user['uid']);
		$this->load->view("clients_interface/control-task-in-work",$pagevar);
	}
	
	public function control_pay_all(){
		
		$balance = $this->mdusers->read_field($this->user['uid'],'balance');
		if($balance == 0):
			$this->session->set_userdata('msgr','Ошибка. У Вас нулевой баланс. Необходимо пополнить счет');
			redirect('webmaster-panel/actions/finished-jobs');
		endif;
		$works = $this->mddelivesworks->read_records_webmaster_status($this->user['uid'],0);
		if(!$works):
			redirect('webmaster-panel/actions/control');
		endif;
		for($i=0;$i<count($works);$i++):
			if($balance < $works[$i]['wprice']):
				continue;
			endif;
			$result = $this->mdusers->change_user_balance($this->user['uid'],-$works[$i]['wprice']);
			if($result):
				$balance = $balance - $works[$i]['wprice'];
				$this->mddelivesworks->update_status_ones($this->user['uid'],$works[$i]['id']);
				if($works[$i]['manager']):
					$this->mdusers->change_user_balance($works[$i]['manager'],$works[$i]['mprice']);
				endif;
				$this->mdusers->change_admins_balance($works[$i]['wprice']-$works[$i]['mprice']);
				$this->mdfillup->insert_record($this->user['uid'],$works[$i]['wprice'],'Оплата за выполненное задание ID='.$works[$i]['id'],0,0);
				if($this->user['partner'] > 0):
					$pprice = round($works[$i]['wprice']*0.05,2);
					$this->mdusers->change_user_balance($this->user['partner'],$pprice);
					$this->mdfillup->insert_record($this->user['partner'],$pprice,'Средства по партнерской программе',0,1);
				endif;
			endif;
		endfor;
		$this->mdlog->insert_record($this->user['uid'],'Событие №11: Произведена оплата за выполненные работы');
		$message = 'Спасибо за оплату.';
		
		$date = date("Y-m-d",mktime(0,0,0,date("m"),date("d")-5,date("Y")));
		$debetor = $this->mddelivesworks->calc_user_debet($this->user['uid'],$date,'<=');
		if(!$debetor):
			$this->mdusers->update_field($this->user['uid'],'debetor',0);
			if($this->user['remoteid']):
				$markets = $this->mdwebmarkets->read_records($this->user['remoteid']);
				for($i=0;$i<count($markets);$i++):
					$param = 'accid='.$markets[$i]['id'].'&birzid='.$markets[$i]['market'].'&login='.$markets[$i]['login'].'&pass='.base64_encode($this->encrypt->decode($markets[$i]['cryptpassword'])).'&act=1';
					$this->API('UpdateAccount',$param);
				endfor;
			endif;
			$message .= '<br/>Внимание! Ваш аккаунт разблокирован.';
		endif;
		
		if($this->mdusers->read_field($this->user['uid'],'balance') == 0):
			$message .= '<br/>Внимание! У вас нулевой баланс. Необходимо пополнить счет.';
		endif;
		redirect('webmaster-panel/actions/finished-jobs');
	}
	
	public function finished_jobs_filter(){
		
		$statusval = array('status'=>TRUE,'filter'=>'','paid'=>-1,'notpaid'=>-1);
		$showed = trim($this->input->post('showed'));
		$this->session->set_userdata('jobsfilter','0,1');
		if(!$showed):
			$this->session->set_userdata('jobsfilter','');
		else:
			$filter = preg_split("/&/",$showed);
			for($i=0;$i<count($filter);$i++):
				$fparam[$i] = preg_split("/=/",$filter[$i]);
			endfor;
			if(count($fparam)==1):
				$this->session->set_userdata('jobsfilter',$fparam[0][1]);
				if($fparam[0][1]):
					$statusval['paid'] = 1;$statusval['notpaid'] = 0;
				else:
					$statusval['paid'] = 0;$statusval['notpaid'] = 1;
				endif;
			else:
				$this->session->set_userdata('jobsfilter',$fparam[0][1].','.$fparam[1][1]);
				$statusval['paid'] = 1;$statusval['notpaid'] = 1;
			endif;
		endif;
		$statusval['filter'] = $this->session->userdata('jobsfilter');
		echo json_encode($statusval);
	}
	
	public function finished_jobs_count_page(){
		
		$statusval = array('status'=>TRUE,'countwork'=>25);
		$countwork = trim($this->input->post('countwork'));
		$this->session->set_userdata('jobscount',$statusval['countwork']);
		if(!$countwork):
			$this->session->set_userdata('jobscount','');
		else:
			$this->session->set_userdata('jobscount',$countwork);
			$statusval['countwork'] = $countwork;
		endif;
		echo json_encode($statusval);
	}
	
	public function finished_jobs_search(){
		
		$statusval = array('status'=>FALSE,'retvalue'=>'');
		$search = $this->input->post('squery');
		if(!$search) show_404();
		$jworks = $this->mddelivesworks->search_webmaster_jobs($this->user['uid'],$search);
		if($jworks):
			$statusval['retvalue'] = '<ul>';
			for($i=0;$i<count($jworks);$i++):
				$statusval['retvalue'] .= '<li class="djorg" data-djid="'.$jworks[$i]['id'].'">'.$jworks[$i]['ulrlink'].'</li>';
			endfor;
			$statusval['retvalue'] .= '</ul>';
			$statusval['status'] = TRUE;
		endif;
		echo json_encode($statusval);
	}
	
	/******************************************************** platforms ******************************************************/
	
	public function control_platforms(){
		
		if($this->user['debetor']):
			redirect('webmaster-panel/actions/control');
		endif;
		
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
					$pl_data = $this->mdplatforms->read_record($_POST['pid']);
					if(!$pl_data['cms'] || empty($pl_data['adminpanel']) || empty($pl_data['aplogin']) || empty($pl_data['appassword'])):
						$this->session->set_userdata('msgr','Ошибка при активации площадки.<br/>Не заполены необходимые поля в карточке площадке.');
						redirect($this->uri->uri_string());
					endif;
				endif;
				$result = $this->mdplatforms->update_status($_POST['pid'],$this->user['uid'],$status);
				if($result):
					if($status):
						$this->mdlog->insert_record($this->user['uid'],'Событие №12: Состояние площадки - активная');
						$text = 'Площадка '.$this->mdplatforms->read_field($_POST['pid'],'url').' перешла в состояние - активная!';
					else:
						$this->mdlog->insert_record($this->user['uid'],'Событие №13: Состояние площадки - не активная');
						$text = 'Площадка '.$this->mdplatforms->read_field($_POST['pid'],'url').' перешла в состояние - не активная!';
					endif;
					$manager = $this->mdplatforms->read_field($_POST['pid'],'manager');
					if($manager):
						$this->mdmessages->send_noreply_message($this->user['uid'],$manager,2,2,$text);
					endif;
					$remote_id = $this->mdplatforms->read_field($_POST['pid'],'remoteid');
					if($manager == 2 && $remote_id):
						if($status):
							$status = 0;
						else:
							$status = 1;
						endif;
						$param = 'siteid='.$remote_id.'&value='.$status;
						$res =  $this->API('SetSiteActive',$param);
					endif;
					if(!$status):
						$this->session->set_userdata('msgs','Площадка включена.');
					else:
						$this->session->set_userdata('msgs','Площадка выключена.');
					endif;
				endif;
			endif;
			redirect($this->uri->uri_string());
		endif;
		
		$pagevar['cntunit']['delivers']['notpaid'] = $this->mddelivesworks->count_records_by_webmaster_status($this->user['uid'],0);
		$pagevar['cntunit']['delivers']['total'] = $this->mddelivesworks->count_records_by_webmaster($this->user['uid']);
		$totalsum = $this->mddelivesworks->calc_webmaster_summ($this->user['uid'],'2012-01-01',0);
		$pagevar['cntunit']['delivers']['totalsum'] = $totalsum['sum'];
		$pagevar['cntunit']['platforms'] = $this->mdplatforms->count_records_by_webmaster($this->user['uid']);
		$pagevar['cntunit']['markets'] = $this->mdwebmarkets->count_records($this->user['remoteid']);
		$pagevar['cntunit']['mails']['new'] = $this->mdmessages->count_records_by_recipient_new($this->user['uid'],$this->user['utype']);
		$pagevar['cntunit']['mails']['total'] = $this->mdmessages->count_records_by_recipient($this->user['uid'],$this->user['utype'],$this->user['signdate']);
		$pagevar['cntunit']['tickets_outbox'] = $this->mdtickets->count_records_by_sender($this->user['uid']);
		$pagevar['cntunit']['tickets_inbox'] = $this->mdtickets->count_records_by_recipient($this->user['uid']);
		
		if($pagevar['userinfo']['remote']):
			if(!$pagevar['cntunit']['markets'] && !$pagevar['cntunit']['platforms']):
				redirect('webmaster-panel/actions/control');
			endif;
		else:
			if(intval($pagevar['userinfo']['balance'])<500 && !$pagevar['cntunit']['platforms']):
				redirect('webmaster-panel/actions/control');
			endif;
		endif;
		
		for($i=0;$i<count($pagevar['platforms']);$i++):
			$pagevar['platforms'][$i]['edit'] = TRUE;
			$pagevar['platforms'][$i]['date'] = $this->operation_dot_date($pagevar['platforms'][$i]['date']);
			$pagevar['platforms'][$i]['uporders'] = $this->mddelivesworks->count_records_by_platform_status($pagevar['platforms'][$i]['id'],0);
			$pagevar['platforms'][$i]['torders'] = $this->mddelivesworks->count_records_by_platform($pagevar['platforms'][$i]['id']);
			if($pagevar['platforms'][$i]['locked'] || !$pagevar['platforms'][$i]['status']):
				$pagevar['platforms'][$i]['edit'] = FALSE;
				if($this->mdplatforms->empty_fields($pagevar['platforms'][$i]['id'])):
					$pagevar['platforms'][$i]['edit'] = TRUE;
				endif;
			endif;
		endfor;
		$this->session->set_userdata('backpath',$this->uri->uri_string());
		$this->load->view("clients_interface/control-platforms",$pagevar);
	}
	
	public function control_add_platform(){
		
		if($this->user['remote'] || $this->user['lock']):
			redirect('webmaster-panel/actions/platforms');
		endif;
		if($this->user['debetor']):
			redirect('webmaster-panel/actions/control');
		endif;
		$pagevar = array(
					'description'	=> '',
					'author'		=> '',
					'title'			=> 'Кабинет Вебмастера | Управление площадками | Добавление площадки',
					'baseurl' 		=> base_url(),
					'loginstatus'	=> $this->loginstatus['status'],
					'userinfo'		=> $this->user,
					'markets'		=> $this->mdmarkets->read_records(),
					'thematic'		=> $this->mdthematic->read_records(),
					'cms'			=> $this->mdcms->read_records(),
					'msginfo'		=> '<span class="alert-attention">Внимание!</span> Убедительная просьба тщательно заполнить все поля представленные ниже. Чем больше вы дадите нам информации, тем меньше ошибок будет совершено при публикации.',
					'msgs'			=> $this->session->userdata('msgs'),
					'msgr'			=> $this->session->userdata('msgr')
			);
		$this->session->unset_userdata('msgs');
		$this->session->unset_userdata('msgr');
		
		if($this->input->post('submit')):
			$_POST['submit'] = NULL;
			$this->form_validation->set_rules('url',' ','required|strtolower|trim');
			$this->form_validation->set_rules('subject',' ','required|trim');
			$this->form_validation->set_rules('cms',' ','required|trim');
			$this->form_validation->set_rules('adminpanel',' ','required|trim');
			$this->form_validation->set_rules('aplogin',' ','required|trim');
			$this->form_validation->set_rules('appassword',' ','required|trim');
			$this->form_validation->set_rules('tematcustom',' ','trim');
			$this->form_validation->set_rules('reviews',' ','trim');
			$this->form_validation->set_rules('thematically',' ','trim');
			$this->form_validation->set_rules('illegal',' ','trim');
			$this->form_validation->set_rules('imgstatus',' ','trim');
			$this->form_validation->set_rules('imgwidth',' ','trim');
			$this->form_validation->set_rules('imgheight',' ','trim');
			$this->form_validation->set_rules('imgpos',' ','trim');
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
				if($_POST['imgwidth'] && $_POST['imgheight']):
					$_POST['imgstatus'] = 1;
				endif;
				$_POST['status'] = 1;
				$platform = $this->mdplatforms->insert_record($this->user['uid'],$_POST);
				$attached = $this->mdunion->services_attached_list($this->user['uid']);
				for($i=0;$i<count($attached);$i++):
					$wprice = $mprice = 0;
					$valuesrv = $this->mdvaluesrv->read_zero_price($attached[$i]['service']);
					if(!$valuesrv):
						$valuesrv = $this->mdvaluesrv->read_record_service($attached[$i]['service']);
					endif;
					$wprice = $this->mdvaluesrv->read_field($valuesrv,'wprice');
					$mprice = $this->mdvaluesrv->read_field($valuesrv,'mprice');
					$this->mdattachedservices->insert_record($this->user['uid'],$attached[$i]['service'],$valuesrv,$platform,$wprice,$mprice);
				endfor;
				$manager = $this->mdusers->read_field($this->user['uid'],'manager');
				if($manager):
					$this->mdplatforms->update_field($platform,'manager',$manager);
				endif;
				if($platform):
					$addwtic = $addmtic = 0;
					$pr = $this->getpagerank($_POST['url']);
					$this->mdplatforms->update_field($platform,'pr',$pr);
					$tic = $this->getTIC('http://'.$_POST['url']);
					$this->mdplatforms->update_field($platform,'tic',$tic);
					$sqlquery = $this->SQL_TIC_PR($tic,$platform);
					$this->mdplatforms->run_query($sqlquery);
				endif;
				if($platform && isset($_POST['markets'])):
					$cntmarkets = count($_POST['markets']);
					$marketslist = array();
					if($cntmarkets > 0):
						for($i=0,$j=0;$i<$cntmarkets;$i+=4):
							if(empty($_POST['markets'][$i+1]) || empty($_POST['markets'][$i+2])) continue;
							$marketslist[$j]['mkid'] 	= $_POST['markets'][$i];
							$marketslist[$j]['mklogin'] = $_POST['markets'][$i+1];
							$marketslist[$j]['mkpass'] 	= $_POST['markets'][$i+2];
							$marketslist[$j]['mkpub'] 	= $_POST['markets'][$i+3];
							$j++;
						endfor;
					endif;
						if(count($marketslist)):
						$this->mdmkplatform->group_insert($this->user['uid'],$platform,$marketslist);
					endif;
					$this->mdlog->insert_record($this->user['uid'],'Событие №15: Состояние площадки - создана');
					if($manager):
						$this->mdmessages->send_noreply_message($this->user['uid'],$manager,2,2,'Назначена новая площадка: '.$_POST['url']);
					else:
						$this->mdmessages->send_noreply_message($this->user['uid'],0,2,2,'Добавлена новая площадка: '.$_POST['url']);
					endif;
					$this->session->set_userdata('msgs','Платформа успешно создана.');
				else:
					$this->session->set_userdata('msgr','Платформа не создана.');
				endif;
			endif;
			redirect($this->uri->uri_string());
		endif;
		$pagevar['cntunit']['delivers']['notpaid'] = $this->mddelivesworks->count_records_by_webmaster_status($this->user['uid'],0);
		$pagevar['cntunit']['delivers']['total'] = $this->mddelivesworks->count_records_by_webmaster($this->user['uid']);
		$totalsum = $this->mddelivesworks->calc_webmaster_summ($this->user['uid'],'2012-01-01',0);
		$pagevar['cntunit']['delivers']['totalsum'] = $totalsum['sum'];
		$pagevar['cntunit']['markets'] = $this->mdwebmarkets->count_records($this->user['remoteid']);
		$pagevar['cntunit']['platforms'] = $this->mdplatforms->count_records_by_webmaster($this->user['uid']);
		if($pagevar['userinfo']['remote']):
			if(intval($pagevar['userinfo']['balance'])<500 ||!$pagevar['cntunit']['platforms']):
				redirect('webmaster-panel/actions/control');
			endif;
		endif;
		
		$this->load->view("clients_interface/control-add-platform",$pagevar);
	}
	
	public function control_edit_platform(){
		
		$platform = $this->uri->segment(5);
		if(!$this->mdplatforms->ownew_platform($this->user['uid'],$platform)):
			$pl_data = $this->mdplatforms->read_record($platform);
			if($pl_data['cms'] && !empty($pl_data['adminpanel']) && !empty($pl_data['aplogin']) && !empty($pl_data['appassword'])):
				if(isset($_SERVER['HTTP_REFERER'])):
					redirect($_SERVER['HTTP_REFERER']);
				else:
					redirect('webmaster-panel/actions/control');
				endif;
			endif;
			$this->session->set_userdata('msgr','Есть не заполненные обязательные поля!<br/>Внимание! После заполнения необходимых полей сохраните и активируйте площадку.');
		endif;
		if($this->user['debetor']):
			redirect('webmaster-panel/actions/control');
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
					'thematic'		=> $this->mdthematic->read_records(),
					'cms'			=> $this->mdcms->read_records(),
					'mymarkets'		=> $this->mdmkplatform->read_records_by_platform($platform,$this->user['uid']),
					'services'		=> array(),
					'msginfo'		=> '<span class="alert-attention">Внимание!</span> Перед добавлением площадки убедитесь в наличии всех бирж в каталоге. Если необходимая биржа отсутствует в каталоге - обратитесь к администрации. Доступ к администрации сайта осуществляется через интерфейс технической поддержки.',
					'msgs'			=> $this->session->userdata('msgs'),
					'msgr'			=> $this->session->userdata('msgr')
			);
		$this->session->unset_userdata('msgs');
		$this->session->unset_userdata('msgr');
		
		$attached = $this->mdunion->services_attached_list($this->user['uid']);
		for($i=0;$i<count($attached);$i++):
			$pagevar['services'][$i] = $this->mdunion->read_srvvalue_service_platform($attached[$i]['service'],$platform,$this->user['uid']);
		endfor;
		if($this->input->post('submit')):
			$_POST['submit'] = NULL;
			$this->form_validation->set_rules('url',' ','required|trim');
			$this->form_validation->set_rules('subject',' ','required|trim');
			$this->form_validation->set_rules('cms',' ','required|trim');
			$this->form_validation->set_rules('adminpanel',' ','required|trim');
			$this->form_validation->set_rules('aplogin',' ','required|trim');
			$this->form_validation->set_rules('appassword',' ','required|trim');
			$this->form_validation->set_rules('tematcustom',' ','trim');
			$this->form_validation->set_rules('reviews',' ','trim');
			$this->form_validation->set_rules('thematically',' ','trim');
			$this->form_validation->set_rules('illegal',' ','trim');
			$this->form_validation->set_rules('imgstatus',' ','trim');
			$this->form_validation->set_rules('imgwidth',' ','trim');
			$this->form_validation->set_rules('imgheight',' ','trim');
			$this->form_validation->set_rules('imgpos',' ','trim');
			$this->form_validation->set_rules('requests',' ','trim');
			$this->form_validation->set_rules('writer',' ','trim');
			if(!$this->form_validation->run()):
				$this->session->set_userdata('msgr','Ошибка при сохранении. Не заполены необходимые поля.');
				redirect($this->uri->uri_string());
			else:
				if($_POST['imgwidth'] && $_POST['imgheight']):
					$_POST['imgstatus'] = 1;
				else:
					$_POST['imgstatus'] = 0;
					$_POST['imgwidth'] = $_POST['imgheight'] = '';
					$_POST['imgpos'] = 'left';
				endif;
				$result = $this->mdplatforms->update_record($platform,$this->user['uid'],$_POST);
				if($pagevar['platform']['manager']):
					/********************************************************************/
					if($pagevar['platform']['manager'] == 2):
						$new_platform = $this->mdplatforms->read_record($platform);
						if($new_platform['remoteid']):
							$pl_data = array();
							$marketslist = array();
							if(count($_POST['markets']) > 0):
								for($i=0,$j=0;$i<count($_POST['markets']);$i+=4):
									if(empty($_POST['markets'][$i+1]) || empty($_POST['markets'][$i+2])) continue;
									$marketslist[$j]['mkid'] 	= $_POST['markets'][$i];
									$marketslist[$j]['mkpub'] 	= $_POST['markets'][$i+3];
									$j++;
								endfor;
							endif;
							$pl_data['adminurl'] = $new_platform['adminpanel'];
							$pl_data['cms'] = $new_platform['cms'];
							$pl_data['cms_login'] = $new_platform['aplogin'];
							$pl_data['cms_pass'] = $new_platform['appassword'];
							$pl_data['tematic'] = $new_platform['subject'];
							$pl_data['tematcustom'] = $new_platform['tematcustom'];
							$pl_data['filter'] = $new_platform['illegal'];
							$pl_data['subjects'] = $new_platform['thematically'];
							$pl_data['review'] = $new_platform['reviews'];
							$pl_data['param'] = array();
							$pl_data['param']['image'] = array();
							$pl_data['param']['image']['status'] = $new_platform['imgstatus'];
							$pl_data['param']['image']['imgwidth'] = $new_platform['imgwidth'];
							$pl_data['param']['image']['imgheight'] = $new_platform['imgheight'];
							$pl_data['param']['image']['imgpos'] = $new_platform['imgpos'];
							if(count($marketslist) > 0):
								for($i=0;$i<count($marketslist);$i++):
									$pl_data['param']['category'][$marketslist[$i]['mkid']] = $marketslist[$i]['mkpub'];
								endfor;
							else:
								$pl_data['param']['category'] = array();
							endif;
							$pl_data['writer'] = $new_platform['writer'];
							$pl_data['info'] = $new_platform['requests'];
							$pl_data['size'] = 0;
							$param = 'siteid='.$new_platform['remoteid'].'&conf='.base64_encode(json_encode($pl_data));
							$res = $this->API('UpdateSiteOptions',$param);
							/*if(!$pagevar['platform']['status']):
								$this->mdplatforms->update_field($platform,'status',1);
								$param = 'siteid='.$pagevar['platform']['remoteid'].'&value=0';
								$this->API('SetSiteActive',$param);
							endif;*/
						endif;
					endif;
					/********************************************************************/
					if($result):
						$text = "Информация о площадке ".$pagevar['platform']['url']." изменена.<br/>Проверьте свой E-mail что бы увидеть изменения";
						$this->mdmessages->send_noreply_message($this->user['uid'],$pagevar['platform']['manager'],2,2,$text);
						
						ob_start();
						?>
						<img src="<?=base_url();?>images/logo.png" alt="" />
						<p><strong>Здравствуйте, <?=$this->mdusers->read_field($pagevar['platform']['manager'],'fio');?></strong></p>
						<p>Вебмастер изменил информацию о площадке: <?=$this->mdplatforms->read_field($platform,'url');?><br/>
						Что изменилось (Было - Сейчас):</p>
						<p>URL: <?=$pagevar['platform']['url'].' - '.$_POST['url'];?><br/>
						Тематика: <?=$pagevar['platform']['subject'].' - '.$_POST['subject'];?><br/>
						CMS: <?=$pagevar['platform']['cms'].' - '.$_POST['cms'];?><br/>
						URL админки: <?=$pagevar['platform']['adminpanel'].' - '.$_POST['adminpanel'];?><br/>
						Логин к админке: <?=$pagevar['platform']['aplogin'].' - '.$_POST['aplogin'];?><br/>
						Пароль к админке: <?=$pagevar['platform']['appassword'].' - '.$_POST['appassword'];?><br/>
						Уточнение тематики: <?=$pagevar['platform']['tematcustom'].' - '.$_POST['tematcustom'];?><br/>
						Обзоры: <?=($pagevar['platform']['reviews'] == 1)?'да':'нет';?> - <?=($_POST['reviews'] == 1)?'да':'нет';?><br/>
						Тематичность: <?=($pagevar['platform']['thematically'] == 1)?'да':'нет';?> - <?=($_POST['thematically'] == 1)?'да':'нет';?><br/>
						Размещать задания которые противоречат законам РФ: <?=($pagevar['platform']['illegal'] == 1)?'Да, размещать':'Нет, не размещать';?> - <?=($_POST['illegal'] == 1)?'Да, размещать':'Нет, не размещать';?></p>
						<p>Критерии к публикации:<br/><br/>
							Ширина изображения:<?=$pagevar['platform']['imgwidth'].' - '.$_POST['imgwidth'];?>
							Высота изображения:<?=$pagevar['platform']['imgheight'].' - '.$_POST['imgheight'];?>
							Позиция изображения:<?=$pagevar['platform']['imgpos'].' - '.$_POST['imgpos'];?>
						</p>
						<p>Пожелания :<br/> <br/><?=$pagevar['platform']['requests'].'<br/><br/>'.$_POST['requests'];?></p>
						<br/><br/><p><a href="http://www.bystropost.ru/">С уважением, www.Bystropost.ru</a></p>
						<?
						$mailtext = ob_get_clean();
						
						$this->email->clear(TRUE);
						$config['smtp_host'] = 'localhost';
						$config['charset'] = 'utf-8';
						$config['wordwrap'] = TRUE;
						$config['mailtype'] = 'html';
						
						$this->email->initialize($config);
						$this->email->to($this->mdusers->read_field($pagevar['platform']['manager'],'login'));
						$this->email->from('admin@bystropost.ru','Bystropost.ru - Система мониторинга и управления');
						$this->email->bcc('');
						$this->email->subject('Bystropost.ru - Изменения по площадке.');
						$this->email->message($mailtext);	
						$this->email->send();
						$this->mdlog->insert_record($this->user['uid'],'Событие №16: Состояние площадки - изменена');
						$this->session->set_userdata('msgs','Платформа успешно сохранена.');
					endif;
				endif;
				if(isset($_POST['markets'])):
					$cntmarkets = count($_POST['markets']);
					$marketslist = array();
					if($cntmarkets > 0):
						for($i=0,$j=0;$i<$cntmarkets;$i+=4):
							if(empty($_POST['markets'][$i+1]) || empty($_POST['markets'][$i+2])) continue;
							$marketslist[$j]['mkid'] 	= $_POST['markets'][$i];
							$marketslist[$j]['mklogin'] = $_POST['markets'][$i+1];
							$marketslist[$j]['mkpass'] 	= $_POST['markets'][$i+2];
							$marketslist[$j]['mkpub'] 	= $_POST['markets'][$i+3];
							$j++;
						endfor;
					endif;
					if(count($marketslist)):
						$this->mdmkplatform->delete_records_by_platform($platform,$this->user['uid']);
						$this->mdmkplatform->group_insert($this->user['uid'],$platform,$marketslist);
					endif;
				endif;
			endif;
			redirect('webmaster-panel/actions/platforms');
		endif;
		$pagevar['cntunit']['delivers']['notpaid'] = $this->mddelivesworks->count_records_by_webmaster_status($this->user['uid'],0);
		$pagevar['cntunit']['delivers']['total'] = $this->mddelivesworks->count_records_by_webmaster($this->user['uid']);
		$totalsum = $this->mddelivesworks->calc_webmaster_summ($this->user['uid'],'2012-01-01',0);
		$pagevar['cntunit']['delivers']['totalsum'] = $totalsum['sum'];
		$pagevar['cntunit']['markets'] = $this->mdwebmarkets->count_records($this->user['remoteid']);
		$pagevar['cntunit']['platforms'] = $this->mdplatforms->count_records_by_webmaster($this->user['uid']);
		for($i=0;$i<count($pagevar['mymarkets']);$i++):
			$pagevar['mymarkets'][$i]['password'] = $this->encrypt->decode($pagevar['mymarkets'][$i]['cryptpassword']);
		endfor;
		if(!$pagevar['platform']['imgwidth'] && !$pagevar['platform']['imgheight']):
			$pagevar['platform']['imgstatus'] = 0;
			$pagevar['platform']['imgwidth'] = '';
			$pagevar['platform']['imgheight'] = '';
		endif;
		
		$this->load->view("clients_interface/control-edit-platform",$pagevar);
	}
	
	/******************************************************** tickets ******************************************************/	
	
	public function tickets_outbox(){
		
		$from = intval($this->uri->segment(5));
		$hideticket = FALSE;
		if($this->session->userdata('hideticket')):
			$hideticket = TRUE;
		endif;
		$pagevar = array(
					'description'	=> '',
					'author'		=> '',
					'title'			=> 'Кабинет Вебмастера | Исходящие тикеты',
					'baseurl' 		=> base_url(),
					'loginstatus'	=> $this->loginstatus['status'],
					'userinfo'		=> $this->user,
					'tickets'		=> $this->mdunion->read_tickets_by_sender($this->user['uid'],5,$from,$hideticket),
					'platforms'		=> array(),
					'hideticket'	=> $hideticket,
					'pages'			=> $this->pagination('webmaster-panel/actions/tickets-outbox',5,$this->mdunion->count_tickets_by_sender($this->user['uid'],$hideticket),5),
					'cntunit'		=> array(),
					'msgs'			=> $this->session->userdata('msgs'),
					'msgr'			=> $this->session->userdata('msgr')
			);
		$this->session->unset_userdata('msgs');
		$this->session->unset_userdata('msgr');
		$platforms = $this->mdplatforms->read_records_by_webmaster_nolock($this->user['uid'],'id,url','id');
		for($i=0;$i<count($platforms);$i++):
			$pagevar['platforms'][] = mb_strtolower($platforms[$i]['url'],'UTF-8');
		endfor;
		if($this->input->post('insticket')):
			unset($_POST['insticket']);
			$this->form_validation->set_rules('title',' ','required|trim');
			$this->form_validation->set_rules('text',' ','required|trim');
			$this->form_validation->set_rules('type',' ','required|trim');
			$this->form_validation->set_rules('platform',' ','required|trim');
			$this->form_validation->set_rules('importance',' ','required|trim');
			if(!$this->form_validation->run()):
				$this->session->set_userdata('msgr','Ошибка. Не заполены необходимые поля.');
				redirect($this->uri->uri_string());
			else:
				$ticket_data = $this->input->post();
				$recipient = 0;
				$platform_id = $this->mdplatforms->exist_platform($ticket_data['platform']);
				if($this->mdplatforms->ownew_platform($this->user['uid'],$platform_id)):
					if($ticket_data['type'] == 1):
						$manager = $this->webmaster_manager($this->user['uid'],$platform_id);
						if($manager):
							$recipient = $manager;
						endif;
					endif;
					if($recipient):
						ob_start();
						?><img src="<?=base_url();?>images/logo.png" alt="" />
						<p><strong>Здравствуйте, <?=$this->mdusers->read_field($recipient,'fio');?></strong></p>
						<p>У Вас новое сообщение через тикет-систему</p>
						<p>Что бы прочитать его войдите в <?=$this->link_cabinet($recipient);?> и перейдите в раздел "Тикеты"</p>
						<p><br/><?=$this->sub_tickettext($ticket_data['text'],$recipient);?><br/></p>
						<br/><br/><p><a href="http://www.bystropost.ru/">С уважением, www.Bystropost.ru</a></p><?
						$mailtext = ob_get_clean();
						$this->send_mail($this->mdusers->read_field($recipient,'login'),'admin@bystropost.ru','Bystropost.ru - Система мониторинга и управления','Bystropost.ru - Новый тикет',$mailtext);
					endif;
					$ticket_data['platform'] = $platform_id;
					$ticket = $this->mdtickets->insert_record($this->user['uid'],$recipient,$ticket_data);
					if($ticket):
						$this->admin_ticket_mail($this->user['ulogin'],$ticket);
						$this->mdtkmsgs->insert_record($this->user['uid'],$ticket,$this->user['uid'],$recipient,1,$this->replace_href_tag($ticket_data['text']));
						$this->mdlog->insert_record($this->user['uid'],'Событие №17: Состояние тикета - создан');
						$this->session->set_userdata('msgs','Тикет успешно создан.');
						$this->mdmessages->send_noreply_message($this->user['uid'],$recipient,2,$this->mdusers->read_field($recipient,'type'),'Новое сообщение через тикет-систему. Тикет ID: '.$ticket);
						if($recipient):
							$this->mdmessages->send_noreply_message($this->user['uid'],0,2,5,'Вебмастер создал тикет для менеджера');
						else:
							$this->mdmessages->send_noreply_message($this->user['uid'],0,2,5,'Новое сообщение через тикет-систему');
						endif;
					endif;
				else:
					$this->session->set_userdata('msgr','Ошибка. Не верно указана площадка.');
				endif;
				redirect($this->uri->uri_string());
			endif;
		endif;
		
		$pagevar['cntunit']['delivers']['notpaid'] = $this->mddelivesworks->count_records_by_webmaster_status($this->user['uid'],0);
		$pagevar['cntunit']['delivers']['total'] = $this->mddelivesworks->count_records_by_webmaster($this->user['uid']);
		$totalsum = $this->mddelivesworks->calc_webmaster_summ($this->user['uid'],'2012-01-01',0);
		$pagevar['cntunit']['delivers']['totalsum'] = $totalsum['sum'];
		$pagevar['cntunit']['platforms'] = $this->mdplatforms->count_records_by_webmaster($this->user['uid']);
		$pagevar['cntunit']['markets'] = $this->mdwebmarkets->count_records($this->user['remoteid']);
		$pagevar['cntunit']['mails']['new'] = $this->mdmessages->count_records_by_recipient_new($this->user['uid'],$this->user['utype']);
		$pagevar['cntunit']['mails']['total'] = $this->mdmessages->count_records_by_recipient($this->user['uid'],$this->user['utype'],$this->user['signdate']);
		$pagevar['cntunit']['tickets_outbox'] = $this->mdtickets->count_records_by_sender($this->user['uid']);
		$pagevar['cntunit']['tickets_inbox'] = $this->mdtickets->count_records_by_recipient($this->user['uid']);
		
		if($pagevar['userinfo']['remote']):
			if(intval($pagevar['userinfo']['balance'])<500 && !$pagevar['cntunit']['markets'] && !$pagevar['cntunit']['platforms']):
				$pagevar['userinfo']['locked'] = TRUE;
			endif;
		else:
			if(intval($pagevar['userinfo']['balance'])<500 && !$pagevar['cntunit']['platforms']):
				$pagevar['userinfo']['locked'] = TRUE;
			endif;
		endif;
		
		for($i=0;$i<count($pagevar['tickets']);$i++):
			$pagevar['tickets'][$i]['date'] = $this->operation_dot_date_on_time($pagevar['tickets'][$i]['date']);
			$pagevar['tickets'][$i]['msg_date'] = $this->operation_dot_date_on_time($this->mdtkmsgs->in_finish_message_date($this->user['uid'],$pagevar['tickets'][$i]['id']));
			$finish_sender = $this->mdtkmsgs->in_finish_message_sender($this->user['uid'],$pagevar['tickets'][$i]['id']);
			if($finish_sender):
				$pagevar['tickets'][$i]['msg_sender'] = $this->mdusers->read_field($finish_sender,'position');
			elseif($finish_sender == '0'):
				$pagevar['tickets'][$i]['msg_sender'] = 'Администратор';
			else:
				$pagevar['tickets'][$i]['msg_sender'] = 'Без ответа';
			endif;
			if($pagevar['tickets'][$i]['recipient']):
				$pagevar['tickets'][$i]['position'] = $this->mdusers->read_field($pagevar['tickets'][$i]['recipient'],'position');
				$pagevar['tickets'][$i]['position'] .='у';
			else:
				$pagevar['tickets'][$i]['position'] = 'Администратору';
			endif;
		endfor;
		$this->session->set_userdata('backpath',$this->uri->uri_string());
		$this->load->view("clients_interface/tickets/outbox",$pagevar);
	}
	
	public function tickets_inbox(){
		
		$from = intval($this->uri->segment(5));
		$hideticket = FALSE;
		if($this->session->userdata('hideticket')):
			$hideticket = TRUE;
		endif;
		$pagevar = array(
					'description'	=> '',
					'author'		=> '',
					'title'			=> 'Кабинет Вебмастера | Входящие тикеты',
					'baseurl' 		=> base_url(),
					'loginstatus'	=> $this->loginstatus['status'],
					'userinfo'		=> $this->user,
					'tickets'		=> $this->mdunion->read_tickets_by_recipient($this->user['uid'],5,$from,$hideticket),
					'hideticket'	=> $hideticket,
					'pages'			=> $this->pagination('webmaster-panel/actions/tickets-inbox',5,$this->mdunion->count_tickets_by_recipient($this->user['uid'],$hideticket),5),
					'cntunit'		=> array(),
					'msgs'			=> $this->session->userdata('msgs'),
					'msgr'			=> $this->session->userdata('msgr')
			);
		$this->session->unset_userdata('msgs');
		$this->session->unset_userdata('msgr');
		
		$pagevar['cntunit']['delivers']['notpaid'] = $this->mddelivesworks->count_records_by_webmaster_status($this->user['uid'],0);
		$pagevar['cntunit']['delivers']['total'] = $this->mddelivesworks->count_records_by_webmaster($this->user['uid']);
		$totalsum = $this->mddelivesworks->calc_webmaster_summ($this->user['uid'],'2012-01-01',0);
		$pagevar['cntunit']['delivers']['totalsum'] = $totalsum['sum'];
		$pagevar['cntunit']['platforms'] = $this->mdplatforms->count_records_by_webmaster($this->user['uid']);
		$pagevar['cntunit']['markets'] = $this->mdwebmarkets->count_records($this->user['remoteid']);
		$pagevar['cntunit']['mails']['new'] = $this->mdmessages->count_records_by_recipient_new($this->user['uid'],$this->user['utype']);
		$pagevar['cntunit']['mails']['total'] = $this->mdmessages->count_records_by_recipient($this->user['uid'],$this->user['utype'],$this->user['signdate']);
		$pagevar['cntunit']['tickets_outbox'] = $this->mdtickets->count_records_by_sender($this->user['uid']);
		$pagevar['cntunit']['tickets_inbox'] = $this->mdtickets->count_records_by_recipient($this->user['uid']);
		
		if($pagevar['userinfo']['remote']):
			if(intval($pagevar['userinfo']['balance'])<500 && !$pagevar['cntunit']['markets'] && !$pagevar['cntunit']['platforms']):
				$pagevar['userinfo']['locked'] = TRUE;
			endif;
		else:
			if(intval($pagevar['userinfo']['balance'])<500 && !$pagevar['cntunit']['platforms']):
				$pagevar['userinfo']['locked'] = TRUE;
			endif;
		endif;
		
		for($i=0;$i<count($pagevar['tickets']);$i++):
			$pagevar['tickets'][$i]['date'] = $this->operation_dot_date_on_time($pagevar['tickets'][$i]['date']);
			$pagevar['tickets'][$i]['msg_date'] = $this->operation_dot_date_on_time($this->mdtkmsgs->in_finish_message_date($this->user['uid'],$pagevar['tickets'][$i]['id']));
			$finish_sender = $this->mdtkmsgs->in_finish_message_sender($this->user['uid'],$pagevar['tickets'][$i]['id']);
			if($finish_sender):
				$pagevar['tickets'][$i]['msg_sender'] = $this->mdusers->read_field($finish_sender,'position');
			elseif($finish_sender == '0'):
				$pagevar['tickets'][$i]['msg_sender'] = 'Администратор';
			else:
				$pagevar['tickets'][$i]['msg_sender'] = 'Без ответа';
			endif;
			if($pagevar['tickets'][$i]['sender']):
				$pagevar['tickets'][$i]['position'] = $this->mdusers->read_field($pagevar['tickets'][$i]['sender'],'position');
				$pagevar['tickets'][$i]['msg_sender'] = $this->mdusers->read_field($this->mdtkmsgs->in_finish_message_sender($this->user['uid'],$pagevar['tickets'][$i]['id']),'position');
			else:
				$pagevar['tickets'][$i]['msg_sender'] = $pagevar['tickets'][$i]['position'] = 'Администратор';
			endif;
		endfor;
		$this->session->set_userdata('backpath',$this->uri->uri_string());
		$this->load->view("clients_interface/tickets/inbox",$pagevar);
	}
	
	public function read_ticket(){
		
		$ticket = $this->uri->segment(5);
		$from = intval($this->uri->segment(7));
		if(!$this->mdtickets->ownew_ticket_or_recipient($this->user['uid'],$ticket)):
			redirect('webmaster-panel/actions/control');
		endif;
		$pagevar = array(
					'description'	=> '',
					'author'		=> '',
					'title'			=> 'Кабинет Вебмастера | Тикеты | Чтение тикета',
					'baseurl' 		=> base_url(),
					'loginstatus'	=> $this->loginstatus['status'],
					'userinfo'		=> $this->user,
					'ticket'		=> $this->mdunion->view_ticket_info($ticket),
					'messages'		=> $this->mdunion->read_messages_by_ticket_pages($ticket,7,$from),
					'pages'			=> $this->pagination("webmaster-panel/actions/".$this->uri->segment(3)."/read-ticket-id/$ticket",7,$this->mdunion->count_messages_by_ticket($ticket),7),
					'cntunit'		=> array(),
					'msgs'			=> $this->session->userdata('msgs'),
					'msgr'			=> $this->session->userdata('msgr')
			);
		$this->session->unset_userdata('msgs');
		$this->session->unset_userdata('msgr');
		
		if($this->input->post('insticket')):
			unset($_POST['insticket']);
			
			$message = $this->input->post();
			$msgs = '';
			if($this->uri->segment(3) == 'tickets-outbox'):
				$recipient = $pagevar['ticket']['recipient'];
			elseif($this->uri->segment(3) == 'tickets-inbox'):
				$recipient = $pagevar['ticket']['sender'];
			endif;
			if(isset($message['closeticket'])):
				$this->mdlog->insert_record($this->user['uid'],'Событие №18: Состояние тикета - закрыт');
				$msgs .= '<span class="label label-important">Тикет закрыт</span><br/>';
				$this->mdtickets->update_field($ticket,'status',1);
				$this->mdtickets->update_field($ticket,'sender_answer',0);
				$this->mdtickets->update_field($ticket,'recipient_answer',0);
				$this->mdmessages->send_noreply_message($this->user['uid'],$recipient,2,$this->mdusers->read_field($recipient,'type'),'Вебмастер закрыл тикет ID: '.$ticket);
			else:
				if(empty($message['text'])):
					$this->session->set_userdata('msgr','Ошибка. Не заполены необходимые поля.');
					redirect($this->uri->uri_string());
				endif;
			endif;
			if(!empty($message['text'])):
				$result = $this->mdtkmsgs->insert_record($this->user['uid'],$ticket,$this->user['uid'],$recipient,0,$this->replace_href_tag($message['text']));
				if($result):
					if($this->user['uid'] == $pagevar['ticket']['recipient']):
						$this->mdtickets->update_field($ticket,'recipient_answer',1);
						$this->mdtickets->update_field($ticket,'sender_answer',0);
						$this->mdtickets->update_field($ticket,'sender_reading',0);
					elseif($this->user['uid'] == $pagevar['ticket']['sender']):
						$this->mdtickets->update_field($ticket,'sender_answer',1);
						$this->mdtickets->update_field($ticket,'recipient_answer',0);
						$this->mdtickets->update_field($ticket,'recipient_reading',0);
					endif;
					$this->mdlog->insert_record($this->user['uid'],'Событие №19: Состояние тикета - новое сообщение');
					$this->mdmessages->send_noreply_message($this->user['uid'],$recipient,2,$this->mdusers->read_field($recipient,'type'),'Новое сообщение через тикет-систему. Тикет ID: '.$ticket);
					$this->session->set_userdata('msgs',$msgs.' Сообщение отправлено');
					if(isset($message['sendmail'])):
						ob_start();
						?><img src="<?=base_url();?>images/logo.png" alt="" />
						<p><strong>Здравствуйте, <?=$this->mdusers->read_field($recipient,'fio');?></strong></p>
						<p>Получен ответ на Ваше сообщение. в тикет-системе.</p>
						<p>Что бы прочитать его войдите в <?=$this->link_cabinet($recipient);?> и перейдите в раздел "Тикеты"</p>
						<p><br/><?=$this->sub_tickettext($message['text'],$recipient);?><br/></p>
						<br/><br/><p><a href="http://www.bystropost.ru/">С уважением, www.Bystropost.ru</a></p><?
						$mailtext = ob_get_clean();
						$this->send_mail($this->mdusers->read_field($recipient,'login'),'admin@bystropost.ru','Bystropost.ru - Система мониторинга и управления','Bystropost.ru - Тикеты. Новое сообщение',$mailtext);
					endif;
					$this->admin_ticket_mail($this->user['ulogin'],$ticket);
				endif;
			endif;
			if(isset($message['closeticket'])):
				redirect($this->session->userdata('backpath'));
			else:
				redirect($this->uri->uri_string());
			endif;
		endif;
		
		for($i=0;$i<count($pagevar['messages']);$i++):
			$pagevar['messages'][$i]['date'] = $this->operation_dot_date_on_time($pagevar['messages'][$i]['date']);
			if($pagevar['messages'][$i]['sender']):
				$sender_type = $this->mdusers->read_field($pagevar['messages'][$i]['sender'],'type');
				switch($sender_type):
					case 1:
						$pagevar['messages'][$i]['position'] = $this->user['ulogin'];
						$pagevar['messages'][$i]['ico']	= '<img class="img-polaroid" src="'.$pagevar['baseurl'].'images/icons/webmaster.png" alt="" />';
						break;
					case 2:
						$pagevar['messages'][$i]['position'] = 'Менеджер';
						$pagevar['messages'][$i]['ico']	= '<img class="img-polaroid" src="'.$pagevar['baseurl'].'images/icons/manager.png" alt="" />';
						break;
				endswitch;
			else:
				$pagevar['messages'][$i]['position'] = 'Администратор';
				$pagevar['messages'][$i]['ico']	= '<img class="img-polaroid" src="'.$pagevar['baseurl'].'images/icons/administrator.png" alt="" />';
			endif;
		endfor;
		
		$pagevar['ticket']['message'] = $this->mdtkmsgs->main_message($ticket,FALSE,'id,text,date');
		$pagevar['ticket']['message']['date'] = $this->operation_dot_date_on_time($pagevar['ticket']['message']['date']);
		$pagevar['ticket']['message']['position'] = $this->mdusers->read_field($pagevar['ticket']['sender'],'position');
		if($pagevar['ticket']['sender']):
			$sender_type = $this->mdusers->read_field($pagevar['ticket']['sender'],'type');
			if($sender_type == 1):
				$pagevar['ticket']['message']['ico'] = '<img class="img-polaroid" src="'.$pagevar['baseurl'].'images/icons/webmaster.png" alt="" />';
			elseif($sender_type == 2):
				$pagevar['ticket']['message']['ico'] = '<img class="img-polaroid" src="'.$pagevar['baseurl'].'images/icons/manager.png" alt="" />';
			endif;
		else:
			$pagevar['ticket']['message']['position'] = 'Администратор';
			$pagevar['ticket']['message']['ico'] = '<img class="img-polaroid" src="'.$pagevar['baseurl'].'images/icons/administrator.png" alt="" />';
		endif;
		if($this->user['uid'] == $pagevar['ticket']['recipient']):
			$this->mdtickets->update_field($ticket,'recipient_reading',1);
			$this->mdtickets->update_field($ticket,'sender_answer',0);
		elseif($this->user['uid'] == $pagevar['ticket']['sender']):
			$this->mdtickets->update_field($ticket,'sender_reading',1);
		endif;
		
		$pagevar['cntunit']['delivers']['notpaid'] = $this->mddelivesworks->count_records_by_webmaster_status($this->user['uid'],0);
		$pagevar['cntunit']['delivers']['total'] = $this->mddelivesworks->count_records_by_webmaster($this->user['uid']);
		$totalsum = $this->mddelivesworks->calc_webmaster_summ($this->user['uid'],'2012-01-01',0);
		$pagevar['cntunit']['delivers']['totalsum'] = $totalsum['sum'];
		$pagevar['cntunit']['platforms'] = $this->mdplatforms->count_records_by_webmaster($this->user['uid']);
		$pagevar['cntunit']['markets'] = $this->mdwebmarkets->count_records($this->user['remoteid']);
		$pagevar['cntunit']['mails']['new'] = $this->mdmessages->count_records_by_recipient_new($this->user['uid'],$this->user['utype']);
		$pagevar['cntunit']['mails']['total'] = $this->mdmessages->count_records_by_recipient($this->user['uid'],$this->user['utype'],$this->user['signdate']);
		$pagevar['cntunit']['tickets_outbox'] = $this->mdtickets->count_records_by_sender($this->user['uid']);
		$pagevar['cntunit']['tickets_inbox'] = $this->mdtickets->count_records_by_recipient($this->user['uid']);
		
		if($pagevar['userinfo']['remote']):
			if(intval($pagevar['userinfo']['balance'])<500 && !$pagevar['cntunit']['markets'] && !$pagevar['cntunit']['platforms']):
				$pagevar['userinfo']['locked'] = TRUE;
			endif;
		else:
			if(intval($pagevar['userinfo']['balance'])<500 && !$pagevar['cntunit']['platforms']):
				$pagevar['userinfo']['locked'] = TRUE;
			endif;
		endif;
		$this->load->view("clients_interface/tickets/messages",$pagevar);
	}
	
	public function control_open_ticket(){
		
		$ticket = $this->uri->segment(5);
		if($ticket):
			$result = $this->mdtickets->open_ticket($ticket);
			if($result):
				$this->session->set_userdata('msgs','Тикет открыт');
			endif;
			redirect($this->session->userdata('backpath'));
		else:
			redirect('webmaster-panel/actions/control');
		endif;
	}

	public function hide_closed_tickets(){
		
		$statusval = array('status'=>FALSE);
		$toggle = trim($this->input->post('toggle'));
		$hideticket = $this->session->userdata('hideticket');
		if($hideticket):
			$this->session->set_userdata('hideticket',FALSE);
		else:
			$this->session->set_userdata('hideticket',TRUE);
		endif;
		$statusval['status'] = $this->session->userdata('hideticket');
		echo json_encode($statusval);
	}
	
	public function rating_ticket_message(){
		
		$statusval = array('status'=>FALSE,'newlink'=>'');
		$message_id = trim($this->input->post('message_id'));
		$message_rating = trim($this->input->post('message_rating'));
		if(!$message_id || !$message_rating):
			show_404();
		endif;
		$result = $this->mdtkmsgs->update_field($message_id,'rating',$message_rating);
		if($result):
			$statusval['status'] = TRUE;
			$statusval['newlink'] = ($message_rating > 3)?'<span class="label label-success">Ваша оценка: '.$message_rating.'</span>':'<span class="label label-inverse">Ваша оценка: '.$message_rating.'</span>';
		endif;
		echo json_encode($statusval);
	}
	
	/*************************************************** partner program **************************************************/	
	
	public function partner_program(){
		
		$pagevar = array(
					'description'	=> '',
					'author'		=> '',
					'title'			=> 'Кабинет Вебмастера | Партнерская программа',
					'baseurl' 		=> base_url(),
					'loginstatus'	=> $this->loginstatus['status'],
					'userinfo'		=> $this->user,
					'partners'		=> $this->mdunion->list_works_partners($this->user['uid']),
					'cnt'			=> array('webmasters'=>0,'platforms'=>0,'summa'=>0,'works'=>0),
					'msgs'			=> $this->session->userdata('msgs'),
					'msgr'			=> $this->session->userdata('msgr')
			);
		$this->session->unset_userdata('msgs');
		$this->session->unset_userdata('msgr');
	
		$pagevar['cnt']['webmasters'] = $this->mdusers->count_partners($this->user['uid']);
		$pagevar['cnt']['platforms'] = $this->mdunion->count_platforms_partners($this->user['uid']);
		$works = $this->mdunion->count_summa_works_partners($this->user['uid']);
		if($works):
			$pagevar['cnt']['summa'] = round($works['summa']*0.05,2); // 5% от общей суммы всех закрытых работ
			$pagevar['cnt']['works'] = $works['works'];
		endif;
		
		for($i=0;$i<count($pagevar['partners']);$i++):
			$pagevar['partners'][$i]['signdate'] = $this->operation_dot_date_no_time($pagevar['partners'][$i]['signdate']);
		endfor;
		
		$pagevar['cntunit']['delivers']['notpaid'] = $this->mddelivesworks->count_records_by_webmaster_status($this->user['uid'],0);
		$pagevar['cntunit']['delivers']['total'] = $this->mddelivesworks->count_records_by_webmaster($this->user['uid']);
		$totalsum = $this->mddelivesworks->calc_webmaster_summ($this->user['uid'],'2012-01-01',0);
		$pagevar['cntunit']['delivers']['totalsum'] = $totalsum['sum'];
		$pagevar['cntunit']['platforms'] = $this->mdplatforms->count_records_by_webmaster($this->user['uid']);
		$pagevar['cntunit']['markets'] = $this->mdwebmarkets->count_records($this->user['remoteid']);
		$pagevar['cntunit']['mails']['new'] = $this->mdmessages->count_records_by_recipient_new($this->user['uid'],$this->user['utype']);
		$pagevar['cntunit']['mails']['total'] = $this->mdmessages->count_records_by_recipient($this->user['uid'],$this->user['utype'],$this->user['signdate']);
		$pagevar['cntunit']['tickets_outbox'] = $this->mdtickets->count_records_by_sender($this->user['uid']);
		$pagevar['cntunit']['tickets_inbox'] = $this->mdtickets->count_records_by_recipient($this->user['uid']);
		
		if($pagevar['userinfo']['remote']):
			if(intval($pagevar['userinfo']['balance'])<500 && !$pagevar['cntunit']['markets'] && !$pagevar['cntunit']['platforms']):
				$pagevar['userinfo']['locked'] = TRUE;
			endif;
		else:
			if(intval($pagevar['userinfo']['balance'])<500 && !$pagevar['cntunit']['platforms']):
				$pagevar['userinfo']['locked'] = TRUE;
			endif;
		endif;
		
		$this->load->view("clients_interface/partner-program",$pagevar);
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

	/******************************************************** functions ******************************************************/	
	
	private function load_platforms($platforms,$market){
		
		$cntpl['import'] = 0;
		$cntpl['alien']	= array();
		if($platforms):
			$j = 0;
			$pl_data = array();
			foreach($platforms as $key => $value):
				$pl_data[$j] = $value;
				$pl_data[$j]['id'] = $key;
				$j++;
			endforeach;
			$works = $this->mdtypeswork->read_records();
			for($i=0;$i<count($pl_data);$i++):
				if(!isset($pl_data[$i]['url']) || empty($pl_data[$i]['url']) || !$pl_data[$i]['id']):
					continue;
				endif;
				$new_platform['id'] = $pl_data[$i]['id'];
				$new_platform['webmaster'] = $this->user['uid'];
				$new_platform['manager'] = 2;
				$new_platform['url'] = $pl_data[$i]['url'];
				$new_platform['subject'] = ($pl_data[$i]['tematic'])? $pl_data[$i]['tematic'] : 1;
				$new_platform['cms'] = ($pl_data[$i]['cms'])? $pl_data[$i]['cms'] : 1;
				$new_platform['adminpanel'] = ($pl_data[$i]['adminurl'])? $pl_data[$i]['adminurl'] : '';
				$new_platform['aplogin'] = ($pl_data[$i]['cms_login'])? $pl_data[$i]['cms_login'] : '';
				$new_platform['appassword'] = ($pl_data[$i]['cms_pass'])? $pl_data[$i]['cms_pass'] : '';
				$new_platform['tematcustom'] = $pl_data[$i]['tematcustom'];
				$new_platform['reviews'] = ($pl_data[$i]['review'])? 1 : 0;
				$new_platform['thematically'] = ($pl_data[$i]['subjects'])? 1 : 0;
				$new_platform['illegal'] = ($pl_data[$i]['filter'])? 1 : 0;
				$new_platform['criteria'] = '';
				$new_platform['imgstatus'] = $pl_data[$i]['param']['image']['status'];
				$new_platform['imgwidth'] = $pl_data[$i]['param']['image']['imgwidth'];
				$new_platform['imgheight'] = $pl_data[$i]['param']['image']['imgheight'];
				$new_platform['imgpos'] = $pl_data[$i]['param']['image']['imgpos'];
				$new_platform['requests'] = ($pl_data[$i]['info'])? $pl_data[$i]['info'] : '';
				$new_platform['tic'] = 0;
				$new_platform['pr'] = 0;
				$new_platform['ccontext'] = 0;
				$new_platform['mcontext'] = 0;
				$new_platform['cnotice'] = 0;
				$new_platform['mnotice'] = 0;
				$new_platform['creview'] = 0;
				$new_platform['mreview'] = 0;
				$new_platform['cnews'] = 0;
				$new_platform['mnews'] = 0;
				$new_platform['clinkpic'] = 0;
				$new_platform['mlinkpic'] = 0;
				$new_platform['cpressrel'] = 0;
				$new_platform['mpressrel'] = 0;
				$new_platform['clinkarh'] = 0;
				$new_platform['mlinkarh'] = 0;
				$new_platform['price'] = 0;
				$new_platform['locked'] = 0;
				$new_platform['status'] = ($pl_data[$i]['off'])? 0 : 1;
				
				$publication = 'По теме';
				$mrs = $pl_data[$i]['param']['category'];
				foreach($mrs AS $key=>$mr):
					$publication = $mr;
					if($key == 0):
						$publication = $mr;
					endif;
				endforeach;
				/*if(!$new_platform['cms'] || empty($new_platform['adminpanel']) || empty($new_platform['aplogin']) || empty($new_platform['appassword'])):
					$new_platform['status'] = 0;
					$param = 'siteid='.$new_platform['id'].'&value=1';
					$this->API('SetSiteActive',$param);
				endif;*/
				if(!$this->mdplatforms->exist_platform($new_platform['url'])):
					$platform = $this->mdplatforms->insert_record($this->user['uid'],$new_platform);
					if($platform):
						$this->mdmkplatform->insert_record($this->user['uid'],$platform,$market['market'],$market['login'],$market['password'],$publication);
						$pr = $this->getpagerank($new_platform['url']);
						$this->mdplatforms->update_field($platform,'pr',$pr);
						$tic = $this->getTIC('http://'.$new_platform['url']);
						$this->mdplatforms->update_field($platform,'tic',$tic);
						$sqlquery = $this->SQL_TIC_PR($tic,$platform);
						$this->mdplatforms->run_query($sqlquery);
						$cntpl['import']++;
						$this->mdlog->insert_record($this->user['uid'],'Событие №22: Импортирована новая площадка');
						$param = 'siteid='.$new_platform['id'];
						$services = $this->API('GetAdditionalService',$param);
						foreach($services as $key => $value):
							$service = $value; $srv_data = array();
							$srv = 0; $pl = $key;
							foreach($service as $key => $value):
								$srv_data[$srv]['service'] = $key;
								$srv_data[$srv]['srvval'] = $value;
								$srv_data[$srv]['platform'] = $pl;
								$srv++;
							endforeach;
						endforeach;
						for($srv = 0;$srv<count($srv_data);$srv++):
							$wprice = $this->mdvaluesrv->read_field($srv_data[$srv]['srvval'],'wprice');
							$mprice = $this->mdvaluesrv->read_field($srv_data[$srv]['srvval'],'mprice');
							$this->mdattachedservices->insert_record($this->user['uid'],$srv_data[$srv]['service'],$srv_data[$srv]['srvval'],$platform,$wprice,$mprice);
							
							$arr_works = $this->mdservices->read_field($srv_data[$srv]['service'],'types_works');
							$arr_works = preg_split('/,/',$arr_works);
							$sqlquery = "UPDATE platforms SET ";
							for($j=0;$j<count($works);$j++):
								$wadd = $madd = 0;
								if(in_array($works[$j]['id'],$arr_works)):
									$wadd = $wprice;
									$madd = $mprice;
								endif;
								$sqlquery .= 'c'.$works[$j]['nickname'].' = c'.$works[$j]['nickname'].'+'.$wadd.', m'.$works[$j]['nickname'].' = m'.$works[$j]['nickname'].'+'.$madd;
								if(isset($works[$j+1])):
									$sqlquery .= ', ';
								endif;
							endfor;
							$sqlquery .= ' WHERE platforms.id = '.$platform;
							$this->mdplatforms->run_query($sqlquery);
						endfor;
					endif;
				else:
					$platform = $this->mdplatforms->read_field_url($new_platform['url'],'id');
					if($this->mdplatforms->ownew_all_platform($this->user['uid'],$platform)):
						$mkid = $this->mdmkplatform->exist_market_platform($platform,$market['market'],$market['login'],$market['password']);
						if(!$mkid):
							$this->mdmkplatform->insert_record($this->user['uid'],$platform,$market['market'],$market['login'],$market['password'],$publication);
						else:
							$this->mdmkplatform->update_field($mkid,'publication',$publication);
						endif;
					else:
						$cntpl['alien'][] = $new_platform['url'];
					endif;
				endif;
			endfor;
			return $cntpl;
		else:
			$cntpl['alien'] = 0;
			return $cntpl;
		endif;
	}
}