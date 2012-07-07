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
		
		$pagevar['cntunit']['platforms'] = $this->mdplatforms->count_records_by_webmaster($this->user['uid']);
		$pagevar['cntunit']['mails'] = $this->mdmessages->count_records_by_recipient($this->user['uid'],$this->user['utype']);
		$pagevar['cntunit']['tickets'] = $this->mdtickets->count_records_by_recipient($this->user['uid']);
		
		$this->load->view("clients_interface/control-panel",$pagevar);
	}
	
	public function control_cabinet(){
		
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
				redirect($this->uri->uri_string());
			endif;
		endif;
		
		$this->load->view("clients_interface/control-cabinet",$pagevar);
	}
	
	/******************************************************** mails *********************************************************/	
	
	public function control_mails(){
		
		$pagevar = array(
					'description'	=> '',
					'author'		=> '',
					'title'			=> 'Кабинет Вебмастера | Управление входящими сообщениями',
					'baseurl' 		=> base_url(),
					'loginstatus'	=> $this->loginstatus['status'],
					'userinfo'		=> $this->user,
					'platforms'		=> $this->mdplatforms->count_records_by_webmaster($this->user['uid']),
					'tickets'		=> $this->mdtickets->count_records_by_recipient($this->user['uid'],$this->user['utype']),
					'mails'			=> $this->mdunion->read_mails_by_recipient($this->user['uid'],$this->user['utype']),
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
		for($i=0;$i<count($pagevar['mails']);$i++):
			$pagevar['mails'][$i]['date'] = $this->operation_date($pagevar['mails'][$i]['date']);
		endfor;
		$this->load->view("clients_interface/control-mails",$pagevar);
	}
	
	public function control_reply_mail(){
		
		$mlid = $this->uri->segment(6);
		$mail = $this->mdmessages->read_record($mlid);
		$pagevar = array(
					'description'	=> '',
					'author'		=> '',
					'title'			=> 'Кабинет Вебмастера | Отправка сообщения',
					'baseurl' 		=> base_url(),
					'loginstatus'	=> $this->loginstatus['status'],
					'userinfo'		=> $this->user,
					'mltext'		=> $mail['text'],
					'sender'		=> $this->mdusers->read_small_info($mail['sender']),
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
					
				endif;
			endif;
			redirect('webmaster-panel/actions/mails');
		endif;
		
		$this->load->view("clients_interface/control-reply-mail",$pagevar);
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

	/******************************************************** platforms ******************************************************/	
	
	public function control_platforms(){
		
		$pagevar = array(
					'description'	=> '',
					'author'		=> '',
					'title'			=> 'Кабинет Вебмастера | Управление площадками',
					'baseurl' 		=> base_url(),
					'loginstatus'	=> $this->loginstatus['status'],
					'userinfo'		=> $this->user,
					'platforms'		=> $this->mdplatforms->read_records_by_webmaster($this->user['uid']),
					'markets'		=> $this->mdunion->read_mkplatform_by_webmaster($this->user['uid']),
					'mails'			=> $this->mdmessages->count_records_by_recipient($this->user['uid'],$this->user['utype']),
					'tickets'		=> $this->mdtickets->count_records_by_recipient($this->user['uid'],$this->user['utype']),
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
			else:
				$platform = $this->mdplatforms->insert_record($this->user['uid'],$_POST);
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
					$this->session->set_userdata('msgs','Платформа успешно создана.');
				else:
					$this->session->set_userdata('msgr','Платформа не создана.');
				endif;
			endif;
			redirect('webmaster-panel/actions/platforms');
		endif;
		
		$this->load->view("clients_interface/control-add-platform",$pagevar);
	}
	
	public function control_edit_platform(){
		
		$platform = $this->uri->segment(5);
		if(!$this->mdplatforms->ownew_platform($this->user['uid'],$platform)):
			show_404();
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
			else:
				$result = $this->mdplatforms->update_record($platform,$this->user['uid'],$_POST);
				if($result):
					$this->session->set_userdata('msgs','Платформа успешно сохранена.');
				else:
					$this->session->set_userdata('msgr','Платформа не сохранена.');
				endif;
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
		
		$this->load->view("clients_interface/control-edit-platform",$pagevar);
	}
	
	/******************************************************** tickets ******************************************************/	
	
	public function control_tickets(){
		
		$pagevar = array(
					'description'	=> '',
					'author'		=> '',
					'title'			=> 'Кабинет Вебмастера | Управление тикетами',
					'baseurl' 		=> base_url(),
					'loginstatus'	=> $this->loginstatus['status'],
					'userinfo'		=> $this->user,
					'platforms'		=> $this->mdplatforms->count_records_by_webmaster($this->user['uid']),
					'mails'			=> $this->mdmessages->count_records_by_recipient($this->user['uid'],$this->user['utype']),
					'tickets'		=> $this->mdunion->read_tickets_by_sender($this->user['uid']),
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
		for($i=0;$i<count($pagevar['tickets']);$i++):
			$pagevar['tickets'][$i]['date'] = $this->operation_dot_date($pagevar['tickets'][$i]['date']);
			if($pagevar['tickets'][$i]['recipient']):
				$pagevar['tickets'][$i]['fio'] = $this->mdusers->read_field($pagevar['tkmsgs'][$i]['recipient'],'fio');
				$pagevar['tickets'][$i]['email'] = $this->mdusers->read_field($pagevar['tkmsgs'][$i]['recipient'],'login');
			else:
				$pagevar['tickets'][$i]['fio'] = '<em><strong>Администратору</strong></em>';
				$pagevar['tickets'][$i]['email'] = '';
			endif;
		endfor;
		
		if($this->input->post('submit')):
			$_POST['submit'] = NULL;
			$this->form_validation->set_rules('title',' ','required|trim');
			$this->form_validation->set_rules('type',' ','required|trim');
			if(!$this->form_validation->run()):
				$this->session->set_userdata('msgr','Ошибка при сохранении. Не заполены необходимые поля.');
			else:
				$recipient = 0;
				if($_POST['type'] == 1):
					$recipient = FALSE;
					//Тут происходит поиск менеджера
				endif;
				$ticket = $this->mdtickets->insert_record($this->user['uid'],$recipient,$_POST);
				if($ticket):
					$this->mdtkmsgs->insert_record($this->user['uid'],$ticket,$this->user['uid'],$recipient,0,$_POST['text']);
					$this->session->set_userdata('msgs','Тикет успешно создан.');
				else:
					$this->session->set_userdata('msgr','Тикет не создан.');
				endif;
			endif;
			redirect('webmaster-panel/actions/tickets');
		endif;
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
					'title'			=> 'Кабинет Вебмастера | Управление тикетами | Просмотр тикета',
					'baseurl' 		=> base_url(),
					'loginstatus'	=> $this->loginstatus['status'],
					'userinfo'		=> $this->user,
					'ticket'		=> $this->mdtickets->read_record($ticket),
					'tkmsgs'		=> $this->mdtkmsgs->read_tkmsgs_by_owner_pages($this->user['uid'],$ticket,5,$from),
					'count'			=> $this->mdtkmsgs->count_tkmsgs_by_owner_pages($this->user['uid'],$ticket),
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
		
		if($this->input->post('submit')):
			$_POST['submit'] = NULL;
			$this->form_validation->set_rules('url',' ','required|trim');
			$this->form_validation->set_rules('subject',' ','required|trim');
			$this->form_validation->set_rules('cms',' ','required|trim');
			if(!$this->form_validation->run()):
				$this->session->set_userdata('msgr','Ошибка при сохранении. Не заполены необходимые поля.');
			else:
				$result = $this->mdtkmsgs->insert_record($ticket,$this->user['uid'],$_POST);
				if($result):
					$this->session->set_userdata('msgs','Платформа успешно сохранена.');
				else:
					$this->session->set_userdata('msgr','Платформа не сохранена.');
				endif;
				$this->mdmkplatform->delete_records_by_platform($platform,$this->user['uid']);
			endif;
			redirect('webmaster-panel/actions/tickets');
		endif;
		for($i=0;$i<count($pagevar['tkmsgs']);$i++):
			$pagevar['tkmsgs'][$i]['date'] = $this->operation_dot_date($pagevar['tkmsgs'][$i]['date']);
			if($pagevar['tkmsgs'][$i]['sender'] != $this->user['uid']):
				$pagevar['tkmsgs'][$i]['fio'] = $this->mdusers->read_field($pagevar['tkmsgs'][$i]['sender'],'fio');
				$pagevar['tkmsgs'][$i]['email'] = $this->mdusers->read_field($pagevar['tkmsgs'][$i]['sender'],'login');
			endif;
		endfor;
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
	
	public function control_reply_ticket(){
		
		$tkmsg = $this->uri->segment(8);
		$ticket = $this->uri->segment(5);
		if(!$this->mdtickets->ownew_ticket($this->user['uid'],$ticket)):
			show_404();
		endif;
		$sender = $this->mdtkmsgs->read_field($tkmsg,'sender');
		if($sender == $this->user['uid']):
			redirect($this->session->userdata('backpath'));
		endif;
		$pagevar = array(
					'description'	=> '',
					'author'		=> '',
					'title'			=> 'Кабинет Вебмастера | Отправка сообщения',
					'baseurl' 		=> base_url(),
					'loginstatus'	=> $this->loginstatus['status'],
					'userinfo'		=> $this->user,
					'tkmsg'			=> $this->mdtkmsgs->read_record($tkmsg,$this->user['uid']),
					'backpath'		=> $this->session->userdata('backpath'),
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
			$this->form_validation->set_rules('recipient',' ','required|trim');
			$this->form_validation->set_rules('text',' ','trim');
			if(!$this->form_validation->run()):
				$this->session->set_userdata('msgr','Ошибка при сохранении. Не заполены необходимые поля.');
			else:
				$tuser = $this->mdtickets->read_field($_POST['recipient'],'type');
				switch($tuser):
					case 1 : redirect($this->uri->uri_string()); break;
					case 2 : TRUE; break;
					case 3 : redirect($this->uri->uri_string()); break;
					case 4 : redirect($this->uri->uri_string()); break;
					case 5 : $_POST['recipient'] = 0; break;
				endswitch;
				$_POST['recipient'] = 0;
				if(isset($_POST['closeticket'])):
					$_POST['text'] .= '<br/><strong>Cпасибо за информацию. Тикет закрыт!</strong>';
					$this->mdtickets->update_field($ticket,'status',1);
				endif;
				$id = $this->mdtkmsgs->insert_record($this->user['uid'],$ticket,$this->user['uid'],$_POST['recipient'],$tkmsg,$_POST['text']);
				if($id):
					$this->session->set_userdata('msgs','Сообщение отправлено');
				endif;
				if(isset($_POST['sendmail'])):
					//уведомление по почте
				endif;
			endif;
			redirect($pagevar['backpath']);
		endif;
		
		$ktrcp = $this->mdtickets->read_field($ticket,'recipient');
		if($ktrcp):
			$pagevar['tkmsg']['fio'] = $this->mdusers->read_field($pagevar['tkmsg']['sender'],'fio');
			$pagevar['tkmsg']['email'] = $this->mdusers->read_field($pagevar['tkmsg']['sender'],'login');
		else:
			$pagevar['tkmsg']['fio'] = '<em><strong>Администратор</strong></em>';
			$pagevar['tkmsg']['email'] = '';
		endif;
		
		$this->load->view("clients_interface/control-reply-ticket",$pagevar);
	}
	
	/******************************************************** other ******************************************************/	
	
	public function actions_cabinet(){
		
		$pagevar = array(
					'description'	=> '',
					'author'		=> '',
					'title'			=> 'Кабинет Вебмастера | Личный кабинет',
					'baseurl' 		=> base_url(),
					'userinfo'		=> $this->user,
					'msgs'			=> $this->session->userdata('msgs'),
					'msgr'			=> $this->session->userdata('msgr')
			);
		$this->session->unset_userdata('msgs');
		$this->session->unset_userdata('msgr');
		$this->load->view("clients_interface/admin-cabinet",$pagevar);
	}
	
	function views(){
	
		$type = $this->uri->segment(2);
		switch ($type):
			case 'market-profile'	:	$pagevar = array('markets'=>$this->mdmarkets->read_records(),'baseurl'=>base_url());
										$this->load->view('clients_interface/includes/markets-profile',$pagevar);
										break;
					default 		:	show_404();
		endswitch;
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