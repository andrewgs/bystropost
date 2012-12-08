<?php if(!defined('BASEPATH')) exit('No direct script access allowed');

class Managers_interface extends CI_Controller{
	
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
		$this->load->model('mdlog');
		$this->load->model('mdthematic');
		$this->load->model('mdcms');
		$this->load->model('mdwebmarkets');
		$this->load->model('mdfillup');
		
		$cookieuid = $this->session->userdata('logon');
		if(isset($cookieuid) and !empty($cookieuid)):
			$this->user['uid'] = $this->session->userdata('userid');
			if($this->user['uid']):
				$userinfo = $this->mdusers->read_record($this->user['uid']);
				if($userinfo['type'] == 2):
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
		
		$from = intval($this->uri->segment(8));
		$fpaid = $fnotpaid = 1;
		if($this->session->userdata('jobsfilter') != ''):
			$filter = preg_split("/,/",$this->session->userdata('jobsfilter'));
			if(count($filter) == 1):
				$fpaid = ($filter[0])?1:0;
				$fnotpaid = (!$filter[0])?1:0;
			endif;
		else:
			$this->session->set_userdata('jobsfilter','0,1');
		endif;
		$cntunit = 25;
		if($this->session->userdata('jobscount') != ''):
			$cntunit = $this->session->userdata('jobscount');
		else:
			$this->session->set_userdata('jobscount',25);
		endif;
		
		$pagevar = array(
					'description'	=> '',
					'author'		=> '',
					'title'			=> 'Кабинет Менеджера | Выполненные задания',
					'baseurl' 		=> base_url(),
					'loginstatus'	=> $this->loginstatus['status'],
					'userinfo'		=> $this->user,
					'delivers'		=> $this->mdunion->delivers_works_manager($this->user['uid'],$this->session->userdata('jobscount'),$from,$this->session->userdata('jobsfilter')),
					'filter'		=> array('fpaid'=>$fpaid,'fnotpaid'=>$fnotpaid),
					'cntwork'		=> $cntunit,
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
		
		$config['base_url'] 		= $pagevar['baseurl'].'manager-panel/actions/control/from/';
		$config['uri_segment'] 		= 5;
		$config['total_rows'] 		= $this->mdunion->count_delivers_works_manager($this->user['uid'],$this->session->userdata('jobsfilter'));
		$config['per_page'] 		= $cntunit;
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
		
		if($this->input->post('scsubmit')):
			unset($_POST['scsubmit']);
			$result = $this->mdunion->read_manager_jobs($this->user['uid'],$_POST['srdjid'],$_POST['srdjurl']);
			$pagevar['title'] .= 'Поиск выполнен';
			$pagevar['delivers'] = $result;
			$pagevar['pages'] = NULL;
		endif;
		
		for($i=0;$i<count($pagevar['delivers']);$i++):
			if(mb_strlen($pagevar['delivers'][$i]['ulrlink'],'UTF-8') > 15):
				$pagevar['delivers'][$i]['link'] = mb_substr($pagevar['delivers'][$i]['ulrlink'],0,15,'UTF-8');
				$pagevar['delivers'][$i]['link'] .= ' ... '.mb_substr($pagevar['delivers'][$i]['ulrlink'],strlen($pagevar['delivers'][$i]['ulrlink'])-10,10,'UTF-8');;
			else:
				$pagevar['delivers'][$i]['link'] = $pagevar['delivers'][$i]['ulrlink'];
			endif;
		endfor;
		
		$pagevar['cntunit']['delivers']['paid'] = $this->mddelivesworks->count_records_by_manager_status($this->user['uid'],1);
		$pagevar['cntunit']['delivers']['total'] = $this->mddelivesworks->count_all_manager($this->user['uid']);
		$pagevar['cntunit']['platforms'] = $this->mdplatforms->count_records_by_manager($this->user['uid']);
		$pagevar['cntunit']['mails']['new'] = $this->mdmessages->count_records_by_recipient_new($this->user['uid'],$this->user['utype']);
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
				$this->session->set_userdata('msgr','Ошибка. Неверно заполнены необходимые поля<br/>');
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
		$pagevar['cntunit']['delivers']['total'] = $this->mddelivesworks->count_all_manager($this->user['uid']);
		$pagevar['cntunit']['platforms'] = $this->mdplatforms->count_records_by_manager($this->user['uid']);
		$pagevar['cntunit']['mails']['new'] = $this->mdmessages->count_records_by_recipient_new($this->user['uid'],$this->user['utype']);
		$pagevar['cntunit']['mails']['total'] = $this->mdmessages->count_records_by_recipient($this->user['uid'],$this->user['utype'],$this->user['signdate']);
		$pagevar['cntunit']['tickets']['inbox'] = $this->mdtickets->count_records_by_recipient($this->user['uid']);
		$pagevar['cntunit']['tickets']['outbox'] = $this->mdtickets->count_records_by_sender($this->user['uid']);
		
		$this->load->view("managers_interface/manager-profile",$pagevar);
	}

	public function control_jobs_search(){
		
		$statusval = array('status'=>FALSE,'retvalue'=>'');
		$search = $this->input->post('squery');
		if(!$search) show_404();
		$jworks = $this->mddelivesworks->search_manager_jobs($this->user['uid'],$search);
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
	
	/***************************************************** platforms ******************************************************/	
	
	public function control_platforms(){
		
		$from = intval($this->uri->segment(5));
		$pagevar = array(
					'description'	=> '',
					'author'		=> '',
					'title'			=> 'Кабинет Менеджера | Назначенные площадки',
					'baseurl' 		=> base_url(),
					'loginstatus'	=> $this->loginstatus['status'],
					'userinfo'		=> $this->user,
					'cntunit'		=> array(),
					'platforms'		=> $this->mdplatforms->read_records_by_manager($this->user['uid'],10,$from),
					'count'			=> $this->mdplatforms->count_records_by_manager($this->user['uid']),
					'pages'			=> array(),
					'workplatform'	=> $this->mdplatforms->count_works_records_by_manager($this->user['uid']),
					'msgs'			=> $this->session->userdata('msgs'),
					'msgr'			=> $this->session->userdata('msgr')
			);
		$this->session->unset_userdata('msgs');
		$this->session->unset_userdata('msgr');
		
		$config['base_url'] 		= $pagevar['baseurl'].'manager-panel/actions/platforms/from/';
		$config['uri_segment'] 		= 5;
		$config['total_rows'] 		= $pagevar['count']; 
		$config['per_page'] 		= 10;
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
		
		$pagevar['cntunit']['delivers']['paid'] = $this->mddelivesworks->count_records_by_manager_status($this->user['uid'],1);
		$pagevar['cntunit']['delivers']['total'] = $this->mddelivesworks->count_all_manager($this->user['uid']);
		$pagevar['cntunit']['platforms'] = $this->mdplatforms->count_records_by_manager($this->user['uid']);
		$pagevar['cntunit']['mails']['new'] = $this->mdmessages->count_records_by_recipient_new($this->user['uid'],$this->user['utype']);
		$pagevar['cntunit']['mails']['total'] = $this->mdmessages->count_records_by_recipient($this->user['uid'],$this->user['utype'],$this->user['signdate']);
		$pagevar['cntunit']['tickets']['inbox'] = $this->mdtickets->count_records_by_recipient($this->user['uid']);
		$pagevar['cntunit']['tickets']['outbox'] = $this->mdtickets->count_records_by_sender($this->user['uid']);
		
		if($this->input->post('mtsubmit')):
			$_POST['mtsubmit'] = NULL;
			$this->form_validation->set_rules('pid',' ','required|trim');
			$this->form_validation->set_rules('text',' ','required|trim');
			if(!$this->form_validation->run()):
				$this->session->set_userdata('msgr','Ошибка при сохранении. Не заполены необходимые поля.');
			else:
				$recipient = $this->mdplatforms->read_field($_POST['pid'],'webmaster');
				if($recipient):
					$id = $this->mdmessages->insert_record($this->user['uid'],$recipient,$_POST['text']);
					if($id):
						$this->mdmessages->send_noreply_message($this->user['uid'],0,2,5,'Менеджер '.$this->user['ulogin'].' написал письмо вебмастеру '.$this->mdusers->read_field($recipient,'login'));
						$this->session->set_userdata('msgs','Сообщение отправлено');
					endif;
					if(isset($_POST['sendmail'])):
						ob_start();
						?>
						<img src="<?=base_url();?>images/logo.png" alt="" />
						<p><strong>Здравствуйте, <?=$this->mdusers->read_field($recipient,'fio');?></strong></p>
						<p>У Вас новое сообщение</p>
						<p>Что бы прочитать его войдите в <?=$this->link_cabinet($recipient);?> и перейдите в раздел "Почта"</p>
						<p><br/><?=$this->sub_mailtext($_POST['text'],$recipient);?><br/></p>
						<br/><br/><p><a href="http://www.bystropost.ru/">С уважением, www.Bystropost.ru</a></p>
						<?
						$mailtext = ob_get_clean();
						
						$this->email->clear(TRUE);
						$config['smtp_host'] = 'localhost';
						$config['charset'] = 'utf-8';
						$config['wordwrap'] = TRUE;
						$config['mailtype'] = 'html';
						
						$this->email->initialize($config);
						$this->email->to($this->mdusers->read_field($recipient,'login'));
						$this->email->from('admin@bystropost.ru','Bystropost.ru - Система мониторинга и управления');
						$this->email->bcc('');
						$this->email->subject('Bystropost.ru - Почта. Новое сообщение');
						$this->email->message($mailtext);
						$this->email->send();
					endif;
				endif;
			endif;
			redirect($this->uri->uri_string());
		endif;
		
		if($this->input->post('scsubmit')):
			unset($_POST['scsubmit']);
			$result = $this->mdunion->read_platform($_POST['srplid'],$_POST['srplurl'],$this->user['uid']);
			$pagevar['title'] .= 'Кабинет Менеджера | Назначенные площадки | Поиск выполнен';
			$pagevar['platforms'] = $result;
			$pagevar['pages'] = NULL;
		endif;
		
		for($i=0;$i<count($pagevar['platforms']);$i++):
			$pagevar['platforms'][$i]['date'] = $this->operation_dot_date($pagevar['platforms'][$i]['date']);
		endfor;
		$this->session->set_userdata('backpath',$this->uri->uri_string());
		$this->load->view("managers_interface/control-platforms",$pagevar);
	}
	
	public function control_edit_platform(){
		
		$platform = $this->uri->segment(5);
		if(!$this->mdplatforms->ownew_manager_platform($this->user['uid'],$platform)):
			show_404();
		endif;
		$webmaster = $this->mdplatforms->read_field($platform,'webmaster');
		
		$pagevar = array(
					'description'	=> '',
					'author'		=> '',
					'title'			=> 'Кабинет Менеджера | Назначенные площадки | Редактирование площадки',
					'baseurl' 		=> base_url(),
					'loginstatus'	=> $this->loginstatus['status'],
					'userinfo'		=> $this->user,
					'platform'		=> $this->mdplatforms->read_record($platform),
					'markets'		=> $this->mdmarkets->read_records(),
					'thematic'		=> $this->mdthematic->read_records(),
					'cms'			=> $this->mdcms->read_records(),
					'mymarkets'		=> $this->mdmkplatform->read_records_by_platform($platform,$webmaster),
					'services'		=> array(),
					'msgs'			=> $this->session->userdata('msgs'),
					'msgr'			=> $this->session->userdata('msgr')
			);
		$this->session->unset_userdata('msgs');
		$this->session->unset_userdata('msgr');
		
		$pagevar['userinfo']['remote'] = TRUE;
		
		$attached = $this->mdunion->services_attached_list($webmaster);
		for($i=0;$i<count($attached);$i++):
			$pagevar['services'][$i] = $this->mdunion->read_srvvalue_service_platform($attached[$i]['service'],$platform,$webmaster);
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
				$result = $this->mdplatforms->update_record($platform,$webmaster,$_POST);
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
						$this->mdmkplatform->delete_records_by_platform($platform,$webmaster);
						$this->mdmkplatform->group_insert($webmaster,$platform,$marketslist);
					endif;
				endif;
			endif;
			redirect('manager-panel/actions/platforms');
		endif;
		for($i=0;$i<count($pagevar['mymarkets']);$i++):
			$pagevar['mymarkets'][$i]['password'] = $this->encrypt->decode($pagevar['mymarkets'][$i]['cryptpassword']);
		endfor;
		if(!$pagevar['platform']['imgwidth'] && !$pagevar['platform']['imgheight']):
			$pagevar['platform']['imgstatus'] = 0;
			$pagevar['platform']['imgwidth'] = '';
			$pagevar['platform']['imgheight'] = '';
		endif;
		
		$this->load->view("managers_interface/control-edit-platform",$pagevar);
	}
	
	public function control_view_platform(){
		
		$platform = $this->uri->segment(5);
		if(!$this->mdplatforms->ownew_manager_platform($this->user['uid'],$platform)):
			redirect('manager-panel/actions/platforms');
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
					'services'		=> array(),
					'thematic'		=> $this->mdthematic->read_records(),
					'cms'			=> $this->mdcms->read_records(),
					'msgs'			=> $this->session->userdata('msgs'),
					'msgr'			=> $this->session->userdata('msgr')
			);
		$this->session->unset_userdata('msgs');
		$this->session->unset_userdata('msgr');
		
		$pagevar['mymarkets'] = $this->mdmkplatform->read_records_by_platform($platform,$pagevar['platform']['webmaster']);
		$attached = $this->mdunion->services_attached_list($pagevar['platform']['webmaster']);
		for($i=0;$i<count($attached);$i++):
			$pagevar['services'][$i] = $this->mdunion->read_srvvalue_service_platform($attached[$i]['service'],$platform,$pagevar['platform']['webmaster']);
		endfor;
		for($i=0;$i<count($pagevar['mymarkets']);$i++):
			$pagevar['mymarkets'][$i]['password'] = $this->encrypt->decode($pagevar['mymarkets'][$i]['cryptpassword']);
		endfor;
		if(!$pagevar['platform']['imgwidth'] && !$pagevar['platform']['imgheight']):
			$pagevar['platform']['imgstatus'] = 0;
			$pagevar['platform']['imgwidth'] = '-';
			$pagevar['platform']['imgheight'] = '-';
		endif;
		
		$this->load->view("managers_interface/control-view-platform",$pagevar);
	}
	
	public function search_platforms(){
		
		$statusval = array('status'=>FALSE,'retvalue'=>'');
		$search = $this->input->post('squery');
		if(!$search) show_404();
		$platforms = $this->mdplatforms->search_platforms($search,$this->user['uid']);
		if($platforms):
			$statusval['retvalue'] = '<ul>';
			for($i=0;$i<count($platforms);$i++):
				$statusval['retvalue'] .= '<li class="plorg" data-plid="'.$platforms[$i]['id'].'">'.$platforms[$i]['url'].'</li>';
			endfor;
			$statusval['retvalue'] .= '</ul>';
			$statusval['status'] = TRUE;
		endif;
		echo json_encode($statusval);
	}
	
	/**************************************************** deliver work ****************************************************/	
	
	public function deliver_work(){
		
		$platform = $this->uri->segment(4);
		
		if(!$this->mdplatforms->ownew_manager_platform($this->user['uid'],$platform)):
			redirect('manager-panel/actions/platforms');
		endif;
		
		$pagevar = array(
					'description'	=> '',
					'author'		=> '',
					'title'			=> 'Кабинет Менеджера | Сдача задания',
					'baseurl' 		=> base_url(),
					'loginstatus'	=> $this->loginstatus['status'],
					'userinfo'		=> $this->user,
					'cntunit'		=> array(),
					'typeswork'		=> $this->mdtypeswork->read_records(),
					'markets'		=> $this->mdmarkets->read_records(),
					'platform'		=> $this->mdplatforms->read_record($platform),
					'msgs'			=> $this->session->userdata('msgs'),
					'msgr'			=> $this->session->userdata('msgr')
			);
		$this->session->unset_userdata('msgs');
		$this->session->unset_userdata('msgr');
		
		if($this->input->post('submit')):
			$_POST['submit'] = NULL;
			$this->form_validation->set_rules('typework',' ','required|trim');
			$this->form_validation->set_rules('market',' ','required|trim');
			$this->form_validation->set_rules('mkprice',' ','required|trim');
			$this->form_validation->set_rules('ulrlink',' ','required|prep_url|trim');
			$this->form_validation->set_rules('countchars',' ','required|trim');
			if(!$this->form_validation->run()):
				$this->session->set_userdata('msgr','Ошибка при сохранении. Не заполены необходимые поля.');
			else:
				if(!strstr($_POST['ulrlink'],$pagevar['platform']['url'])):
					$this->session->set_userdata('msgr','URL не пренадлежит площадке. Повторите ввод.');
					redirect($this->uri->uri_string());
				endif;
				$webmaster = $this->mdplatforms->read_field($platform,'webmaster');
				$nickname = $this->mdtypeswork->read_field($_POST['typework'],'nickname');
				$wprice = $this->mdplatforms->read_field($platform,'c'.$nickname);
				$mprice = $this->mdplatforms->read_field($platform,'m'.$nickname);
				if($webmaster):
					$work = $this->mddelivesworks->insert_record($webmaster,$platform,$this->user['uid'],$wprice,$mprice,$_POST);
					if($work):
						$user = $this->mdusers->read_record($webmaster);
						if($user['autopaid'] && ($user['balance'] >= $wprice)):
							$this->mdusers->change_user_balance($webmaster,-$wprice);
							$this->mddelivesworks->update_status_ones($webmaster,$work);
							$this->mdusers->change_user_balance($this->user['uid'],$mprice);
							$this->mdusers->change_admins_balance($wprice-$mprice);
							$this->mdfillup->insert_record(0,$wprice-$mprice,'Начисление средств администратору при автоматической оплате (Ручной ввод)');
							$this->mdlog->insert_record($webmaster,'Событие №11: Произведена оплата за выполненные работы');
							if($user['partner_id']):
								$pprice = floor($wprice*0.05);
								$this->mdusers->change_user_balance($user['partner_id'],$pprice);
								$this->mdfillup->insert_record($user['partner_id'],$pprice,'Средства по партнерской программе',0,1);
							endif;
						endif;
						$this->mdlog->insert_record($this->user['uid'],'Событие №21: Состояние задания - сдано');
						$this->session->set_userdata('msgs','Отчет о выполенной работе создан');
					endif;
				else:
					$this->session->set_userdata('msgr','Отчет о выполенной работе не создан');
				endif;
			endif;
			redirect($this->uri->uri_string());
		endif;
		$arr = array(); $i = 0;
		foreach($pagevar['platform'] as $key => $value):
			$arr[$i] = $value;
			$i++;
		endforeach;
		$pagevar['typeswork'][0]['mprice'] = $arr[23]; //context
		$pagevar['typeswork'][1]['mprice'] = $arr[25]; //notice
		$pagevar['typeswork'][2]['mprice'] = $arr[27]; //rewiew
		$pagevar['typeswork'][3]['mprice'] = $arr[31]; //linkpic
		$pagevar['typeswork'][4]['mprice'] = $arr[33]; //press
		$pagevar['typeswork'][5]['mprice'] = $arr[35]; //linkarh
		$pagevar['typeswork'][6]['mprice'] = $arr[29]; //news
		
		$pagevar['platform']['date'] = $this->operation_dot_date($pagevar['platform']['date']);
		
		$pagevar['cntunit']['delivers']['paid'] = $this->mddelivesworks->count_records_by_manager_status($this->user['uid'],1);
		$pagevar['cntunit']['delivers']['total'] = $this->mddelivesworks->count_all_manager($this->user['uid']);
		$pagevar['cntunit']['platforms'] = $this->mdplatforms->count_records_by_manager($this->user['uid']);
		$pagevar['cntunit']['mails']['new'] = $this->mdmessages->count_records_by_recipient_new($this->user['uid'],$this->user['utype']);
		$pagevar['cntunit']['mails']['total'] = $this->mdmessages->count_records_by_recipient($this->user['uid'],$this->user['utype'],$this->user['signdate']);
		$pagevar['cntunit']['tickets']['inbox'] = $this->mdtickets->count_records_by_recipient($this->user['uid']);
		$pagevar['cntunit']['tickets']['outbox'] = $this->mdtickets->count_records_by_sender($this->user['uid']);
		
		$this->load->view("managers_interface/deliver-work",$pagevar);
	}
	
	public function remote_deliver_work(){
		
		$statusval = array('nextstep'=>TRUE,'plcount'=>0,'count'=>'','from'=>'','wkol'=>0,'datefrom'=>'','dateto'=>'');
		$count = trim($this->input->post('count'));
		$from = trim($this->input->post('from'));
		if(!$count):
			show_404();
		endif;
		$datefrom = date("Y-m-d",mktime(0,0,0,date("m")-1,date("d"),date("Y")));
//		$datefrom = "2012-11-01";
		$dateto = date("Y-m-d");
		$platforms = $this->mdplatforms->read_managers_platform_remote($this->user['uid'],$count,$from);
		if(!count($platforms)):
			$statusval['nextstep'] = FALSE;
		else:
			$markets = $this->mdmarkets->read_records();
			$typeswork = $this->mdtypeswork->read_records_id();
			for($pl=0;$pl<count($platforms);$pl++):
				$remote_webmaster = $this->mdusers->read_field($platforms[$pl]['webmaster'],'remoteid');
				if(!$remote_webmaster):
					continue;
				endif;
				$webmarkets = $this->mdwebmarkets->read_records($remote_webmaster);
				for($mk=0;$mk<count($markets);$mk++):
					for($wmk=0;$wmk<count($webmarkets);$wmk++):
						if($webmarkets[$wmk]['market'] == $markets[$mk]['id']):
							$param = 'birzid='.$markets[$mk]['id'].'&accid='.$webmarkets[$wmk]['id'].'&datefrom='.$datefrom.'&dateto='.$dateto;
							$deliver_works = $this->API('GetFinishedOrder',$param);
							if($deliver_works):
								$dwd = 0;
								$dw_data = array();
								foreach($deliver_works as $key => $value):
									$dw_data[$dwd] = $value;
									$dw_data[$dwd]['id'] = $key;
									$dwd++;
								endforeach;
								for($dwd=0;$dwd<count($dw_data);$dwd++):
									if($platforms[$pl]['remoteid'] === $dw_data[$dwd]['siteid']):
										if($dw_data[$dwd]['type'] <= 7):
											$new_work['id'] 		= $dw_data[$dwd]['id'];
											$new_work['webmaster'] 	= $platforms[$pl]['webmaster'];
											$new_work['platform'] 	= $dw_data[$dwd]['siteid'];
											$new_work['manager'] 	= $this->user['uid'];
											$new_work['typework'] 	= $dw_data[$dwd]['type'];
											$new_work['market'] 	= $markets[$mk]['id'];
											$new_work['mkprice'] 	= ( isset($dw_data[$dwd]['birzprice']) && !is_null($dw_data[$dwd]['birzprice']) ) ? $dw_data[$dwd]['birzprice'] : 0; // andrewgs
											$new_work['ulrlink'] 	= $dw_data[$dwd]['link'];
											$new_work['countchars'] = ( isset($dw_data[$dwd]['size']) && !is_null($dw_data[$dwd]['size']) ) ? $dw_data[$dwd]['size'] : 0; // andrewgs
											if(isset($dw_data[$dwd]['our_price']) && isset($dw_data[$dwd]['client_price'])):
												$new_work['wprice']	= $dw_data[$dwd]['client_price'];
												$new_work['mprice']	= $dw_data[$dwd]['our_price'];
											else:
												$new_work['wprice']	= $this->mdplatforms->read_field($platforms[$pl]['id'],'c'.$typeswork[$dw_data[$dwd]['type']-1]['nickname']);
												$new_work['mprice']	= $this->mdplatforms->read_field($platforms[$pl]['id'],'m'.$typeswork[$dw_data[$dwd]['type']-1]['nickname']);
											endif;
											$new_work['status'] 	= 0;
											$new_work['date'] 		= $dw_data[$dwd]['date'];
											$new_work['datepaid'] 	= '0000-00-00';
											
											if(!$this->mddelivesworks->exist_work($new_work['id'])):
												$work = $this->mddelivesworks->insert_record($new_work['webmaster'],$platforms[$pl]['id'],$this->user['uid'],$new_work['wprice'],$new_work['mprice'],$new_work);
												if($work):
													$user = $this->mdusers->read_record($new_work['webmaster']);
													if($user['autopaid'] && ($user['balance'] >= $new_work['wprice'])):
														$this->mdusers->change_user_balance($new_work['webmaster'],-$new_work['wprice']);
														$this->mddelivesworks->update_status_ones($new_work['webmaster'],$work);
														$this->mdusers->change_user_balance(2,$new_work['mprice']);
														$this->mdusers->change_admins_balance($new_work['wprice']-$new_work['mprice']);
														$this->mdfillup->insert_record(0,$new_work['wprice']-$new_work['mprice'],'Начисление средств администратору при автоматической плате (Автоматический ввод)');
														if($user['partner_id']):
															$pprice = floor($new_work['wprice']*0.05);
															$this->mdusers->change_user_balance($user['partner_id'],$pprice);
															$this->mdfillup->insert_record($user['partner_id'],$pprice,'Средства по партнерской программе',0,1);
														endif;
													endif;
												endif;
												$statusval['wkol']++;
											else:
												continue;
											endif;
										endif;
									endif;
								endfor;
							endif;
						endif;
					endfor;
				endfor;
			endfor;
		endif;
		$statusval['plcount'] = count($platforms);
		$statusval['count'] = $count;
		$statusval['from'] = $from;
		$statusval['datefrom'] = $datefrom;
		$statusval['dateto'] = $dateto;
		echo json_encode($statusval);
	}

	/******************************************************* mails *********************************************************/	
	
	public function control_mails(){
		
		$from = intval($this->uri->segment(5));
		$pagevar = array(
					'description'	=> '',
					'author'		=> '',
					'title'			=> 'Кабинет Менеджера | Входящие сообщения',
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
			else:
				$id = $this->mdmessages->insert_record($this->user['uid'],$_POST['recipient'],$_POST['text']);
				if($id):
					$this->session->set_userdata('msgs','Сообщение отправлено');
				endif;
				if(isset($_POST['sendmail'])):
					ob_start();
					?>
					<img src="<?=base_url();?>images/logo.png" alt="" />
					<p><strong>Здравствуйте, <?=$this->mdusers->read_field($_POST['recipient'],'fio');?></strong></p>
					<p>У Вас новое сообщение</p>
					<p>Что бы прочитать его войдите в <?=$this->link_cabinet($_POST['recipient']);?> и перейдите в раздел "Почта"</p>
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
		
		$config['base_url'] 	= $pagevar['baseurl'].'manager-panel/actions/mails/from/';
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
		$this->mdmessages->set_read_mails_by_recipient($this->user['uid'],$this->user['utype']);
		
		$pagevar['cntunit']['delivers']['paid'] = $this->mddelivesworks->count_records_by_manager_status($this->user['uid'],1);
		$pagevar['cntunit']['delivers']['total'] = $this->mddelivesworks->count_all_manager($this->user['uid']);
		$pagevar['cntunit']['platforms'] = $this->mdplatforms->count_records_by_manager($this->user['uid']);
		$pagevar['cntunit']['mails']['new'] = $this->mdmessages->count_records_by_recipient_new($this->user['uid'],$this->user['utype']);
		$pagevar['cntunit']['mails']['total'] = $this->mdmessages->count_records_by_recipient($this->user['uid'],$this->user['utype'],$this->user['signdate']);
		$pagevar['cntunit']['tickets']['inbox'] = $this->mdtickets->count_records_by_recipient($this->user['uid']);
		$pagevar['cntunit']['tickets']['outbox'] = $this->mdtickets->count_records_by_sender($this->user['uid']);
		
		$this->load->view("managers_interface/control-mails",$pagevar);
	}
	
	public function control_delete_mail(){
		
		$mid = $this->uri->segment(6);
		if($mid):
			if($this->mdmessages->is_system($mid)):
				redirect('manager-panel/actions/mails');
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
	
	/****************************************************** tickets ******************************************************/	
	
	public function control_tickets_outbox(){
		
		$from = intval($this->uri->segment(6));
		$hideticket = FALSE;
		if($this->session->userdata('hideticket')):
			$hideticket = TRUE;
		endif;
		$pagevar = array(
					'description'	=> '',
					'author'		=> '',
					'title'			=> 'Кабинет Менеджера | Исходящие тикеты',
					'baseurl' 		=> base_url(),
					'loginstatus'	=> $this->loginstatus['status'],
					'userinfo'		=> $this->user,
					'tickets'		=> $this->mdunion->read_tickets_by_sender($this->user['uid'],5,$from,$hideticket),
					'hidetikets'	=> $hideticket,
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
			if(!$this->form_validation->run()):
				$this->session->set_userdata('msgr','Ошибка при сохранении. Не заполены необходимые поля.');
			else:
				$_POST['type'] = 2; $_POST['platform'] = 0;
				$this->mdmessages->send_noreply_message($this->user['uid'],0,2,2,'Новое сообщение через тикет-систему');
				$ticket = $this->mdtickets->insert_record($this->user['uid'],0,$_POST);
				if($ticket):
					$this->mdtkmsgs->insert_record($this->user['uid'],$ticket,$this->user['uid'],0,0,$_POST['text']);
					$this->mdlog->insert_record($this->user['uid'],'Событие №17: Состояние тикета - создан');
					$this->session->set_userdata('msgs','Тикет успешно создан.');
				else:
					$this->session->set_userdata('msgr','Тикет не создан.');
				endif;
			endif;
			redirect($this->uri->uri_string());
		endif;
		
		$config['base_url'] 	= $pagevar['baseurl'].'manager-panel/actions/tickets/outbox/from/';
		$config['uri_segment'] 	= 6;
		$config['total_rows'] 	= $this->mdunion->count_tickets_by_sender($this->user['uid'],$hideticket);
		$config['per_page'] 	= 5;
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
		
		$pagevar['cntunit']['delivers']['paid'] = $this->mddelivesworks->count_records_by_manager_status($this->user['uid'],1);
		$pagevar['cntunit']['delivers']['total'] = $this->mddelivesworks->count_all_manager($this->user['uid']);
		$pagevar['cntunit']['platforms'] = $this->mdplatforms->count_records_by_manager($this->user['uid']);
		$pagevar['cntunit']['mails']['new'] = $this->mdmessages->count_records_by_recipient_new($this->user['uid'],$this->user['utype']);
		$pagevar['cntunit']['mails']['total'] = $this->mdmessages->count_records_by_recipient($this->user['uid'],$this->user['utype'],$this->user['signdate']);
		$pagevar['cntunit']['tickets']['inbox'] = $this->mdtickets->count_records_by_recipient($this->user['uid']);
		$pagevar['cntunit']['tickets']['outbox'] = $this->mdtickets->count_records_by_sender($this->user['uid']);
		
		for($i=0;$i<count($pagevar['tickets']);$i++):
			$pagevar['tickets'][$i]['text'] = $this->mdtkmsgs->noowner_finish_message($pagevar['tickets'][$i]['id']);
			$pagevar['tickets'][$i]['date'] = $this->operation_dot_date_on_time($pagevar['tickets'][$i]['date']);
		endfor;
		$this->load->view("managers_interface/control-tickets-outbox",$pagevar);
	}
	
	public function control_tickets_inbox(){
		
		$from = intval($this->uri->segment(6));
		$hideticket = FALSE;
		if($this->session->userdata('hideticket')):
			$hideticket = TRUE;
		endif;
		$pagevar = array(
					'description'	=> '',
					'author'		=> '',
					'title'			=> 'Кабинет Менеджера | Исходящие тикеты',
					'baseurl' 		=> base_url(),
					'loginstatus'	=> $this->loginstatus['status'],
					'userinfo'		=> $this->user,
					'tickets'		=> $this->mdunion->read_tickets_by_recipient($this->user['uid'],5,$from,$hideticket),
					'hidetikets'	=> $hideticket,
					'pages'			=> array(),
					'cntunit'		=> array(),
					'msgs'			=> $this->session->userdata('msgs'),
					'msgr'			=> $this->session->userdata('msgr')
			);
		$this->session->unset_userdata('msgs');
		$this->session->unset_userdata('msgr');
		
		$config['base_url'] 	= $pagevar['baseurl'].'manager-panel/actions/tickets/inbox/from/';
		$config['uri_segment'] 	= 6;
		$config['total_rows'] 	= $this->mdunion->count_tickets_by_recipient($this->user['uid'],$hideticket);
		$config['per_page'] 	= 5;
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
		
		$pagevar['cntunit']['delivers']['paid'] = $this->mddelivesworks->count_records_by_manager_status($this->user['uid'],1);
		$pagevar['cntunit']['delivers']['total'] = $this->mddelivesworks->count_all_manager($this->user['uid']);
		$pagevar['cntunit']['platforms'] = $this->mdplatforms->count_records_by_manager($this->user['uid']);
		$pagevar['cntunit']['mails']['new'] = $this->mdmessages->count_records_by_recipient_new($this->user['uid'],$this->user['utype']);
		$pagevar['cntunit']['mails']['total'] = $this->mdmessages->count_records_by_recipient($this->user['uid'],$this->user['utype'],$this->user['signdate']);
		$pagevar['cntunit']['tickets']['inbox'] = $this->mdtickets->count_records_by_recipient($this->user['uid']);
		$pagevar['cntunit']['tickets']['outbox'] = $this->mdtickets->count_records_by_sender($this->user['uid']);
		
		for($i=0;$i<count($pagevar['tickets']);$i++):
			$pagevar['tickets'][$i]['text'] = $this->mdtkmsgs->noowner_finish_message($pagevar['tickets'][$i]['id']);
			$pagevar['tickets'][$i]['date'] = $this->operation_dot_date_on_time($pagevar['tickets'][$i]['date']);
		endfor;
		
		$this->load->view("managers_interface/control-tickets-inbox",$pagevar);
	}
	
	public function control_view_inbox_ticket(){
		
		$ticket = $this->uri->segment(6);
		if(!$this->mdtickets->ownew_ticket_or_recipient($this->user['uid'],$ticket)):
			show_404();
		endif;
		$from = intval($this->uri->segment(8));
		$pagevar = array(
					'description'	=> '',
					'author'		=> '',
					'title'			=> 'Кабинет Менеджера | Входящие тикеты | Просмотр тикета',
					'baseurl' 		=> base_url(),
					'loginstatus'	=> $this->loginstatus['status'],
					'userinfo'		=> $this->user,
					'ticket'		=> $this->mdunion->view_ticket_info($ticket),
					'tkmsgs'		=> $this->mdunion->read_messages_by_ticket_pages($ticket,5,$from),
					'count'			=> $this->mdunion->count_messages_by_ticket($ticket),
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
			else:
				if(isset($_POST['closeticket'])):
					$this->mdlog->insert_record($this->user['uid'],'Событие №18: Состояние тикета - закрыт');
					$_POST['text'] .= ' Тикет закрыт.';
					$this->mdtickets->update_field($ticket,'status',1);
				endif;
				$result = $this->mdtkmsgs->insert_record($pagevar['ticket']['sender'],$ticket,$this->user['uid'],$_POST['recipient'],$_POST['mid'],$_POST['text']);
				if($result):
					$this->mdmessages->send_noreply_message($this->user['uid'],$_POST['recipient'],2,2,'Новое сообщение через тикет-систему');
					$this->mdlog->insert_record($this->user['uid'],'Событие №19: Состояние тикета - новое сообщение');
					$this->session->set_userdata('msgs','Сообщение отправлено');
					if(isset($_POST['sendmail'])):
						ob_start();
						?>
						<img src="<?=base_url();?>images/logo.png" alt="" />
						<p><strong>Здравствуйте, <?=$this->mdusers->read_field($_POST['recipient'],'fio');?></strong></p>
						<p>У Вас новое сообщение</p>
						<p>Что бы прочитать его войдите в <?=$this->link_cabinet($_POST['recipient']);?> и перейдите в раздел "Тикеты"</p>
						<p><br/><?=$this->sub_tickettext($_POST['text'],$_POST['recipient']);?><br/></p>
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
			endif;
			redirect($this->uri->uri_string());
		endif;
		for($i=0;$i<count($pagevar['tkmsgs']);$i++):
			$pagevar['tkmsgs'][$i]['date'] = $this->operation_dot_date_on_time($pagevar['tkmsgs'][$i]['date']);
			if($pagevar['tkmsgs'][$i]['sender'] != $this->user['uid']):
				if($pagevar['tkmsgs'][$i]['sender']):
					$pagevar['tkmsgs'][$i]['position'] = $this->mdusers->read_field($pagevar['tkmsgs'][$i]['sender'],'position');
				else:
					$pagevar['tkmsgs'][$i]['position'] = '<b>Администратор</b>';
				endif;
			else:
				$pagevar['tkmsgs'][$i]['position'] = 'Вы';
			endif;
		endfor;
		$config['base_url'] 	= $pagevar['baseurl'].'manager-panel/actions/tickets/'.$this->uri->segment(4).'/view-ticket/'.$this->uri->segment(6).'/from/';
		$config['uri_segment'] 	= 8;
		$config['total_rows'] 	= $pagevar['count'];
		$config['per_page'] 	= 5;
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
		
		$pagevar['cntunit']['delivers']['paid'] = $this->mddelivesworks->count_records_by_manager_status($this->user['uid'],1);
		$pagevar['cntunit']['delivers']['total'] = $this->mddelivesworks->count_all_manager($this->user['uid']);
		$pagevar['cntunit']['platforms'] = $this->mdplatforms->count_records_by_manager($this->user['uid']);
		$pagevar['cntunit']['mails']['new'] = $this->mdmessages->count_records_by_recipient_new($this->user['uid'],$this->user['utype']);
		$pagevar['cntunit']['mails']['total'] = $this->mdmessages->count_records_by_recipient($this->user['uid'],$this->user['utype'],$this->user['signdate']);
		$pagevar['cntunit']['tickets']['inbox'] = $this->mdtickets->count_records_by_recipient($this->user['uid']);
		$pagevar['cntunit']['tickets']['outbox'] = $this->mdtickets->count_records_by_sender($this->user['uid']);
		
		$this->load->view("managers_interface/view-inbox-ticket",$pagevar);
	}
	
	public function control_view_outbox_ticket(){
		
		$ticket = $this->uri->segment(6);
		if(!$this->mdtickets->ownew_ticket($this->user['uid'],$ticket)):
			show_404();
		endif;
		$from = intval($this->uri->segment(8));
		$pagevar = array(
					'description'	=> '',
					'author'		=> '',
					'title'			=> 'Кабинет Менеджера | Исходящие тикеты | Просмотр тикета',
					'baseurl' 		=> base_url(),
					'loginstatus'	=> $this->loginstatus['status'],
					'userinfo'		=> $this->user,
					'ticket'		=> $this->mdtickets->read_field($ticket,'title'),
					'tstatus'		=> $this->mdtickets->read_field($ticket,'status'),
					'tkmsgs'		=> $this->mdtkmsgs->read_tkmsgs_by_manager_pages($this->user['uid'],$ticket,5,$from),
					'count'			=> $this->mdtkmsgs->count_tkmsgs_by_manager_pages($this->user['uid'],$ticket),
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
			else:
				if(isset($_POST['closeticket'])):
					$this->mdlog->insert_record($this->user['uid'],'Событие №18: Состояние тикета - закрыт');
					$_POST['text'] .= ' Cпасибо за информацию. Тикет закрыт.';
					$this->mdtickets->update_field($ticket,'status',1);
				endif;
				$result = $this->mdtkmsgs->insert_record($pagevar['ticket']['sender'],$ticket,$this->user['uid'],$_POST['recipient'],$_POST['mid'],$_POST['text']);
				if($result):
					$this->mdmessages->send_noreply_message($this->user['uid'],$_POST['recipient'],2,2,'Новое сообщение через тикет-систему');
					$this->mdlog->insert_record($this->user['uid'],'Событие №19: Состояние тикета - новое сообщение');
					$this->session->set_userdata('msgs','Сообщение отправлено');
					if(isset($_POST['sendmail'])):
						ob_start();
						?>
						<img src="<?=base_url();?>images/logo.png" alt="" />
						<p><strong>Здравствуйте, <?=$this->mdusers->read_field($_POST['recipient'],'fio');?></strong></p>
						<p>У Вас новое сообщение</p>
						<p>Что бы прочитать его войдите в <?=$this->link_cabinet($_POST['recipient']);?> и перейдите в раздел "Тикеты"</p>
						<p><br/><?=$this->sub_tickettext($_POST['text'],$_POST['recipient']);?><br/></p>
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
			endif;
			redirect($this->uri->uri_string());
		endif;
		for($i=0;$i<count($pagevar['tkmsgs']);$i++):
			$pagevar['tkmsgs'][$i]['date'] = $this->operation_dot_date_on_time($pagevar['tkmsgs'][$i]['date']);
			if($pagevar['tkmsgs'][$i]['sender'] != $this->user['uid']):
				if($pagevar['tkmsgs'][$i]['sender']):
					$pagevar['tkmsgs'][$i]['position'] = $this->mdusers->read_field($pagevar['tkmsgs'][$i]['sender'],'position');
				else:
					$pagevar['tkmsgs'][$i]['position'] = '<b>Администратор</b>';
				endif;
			else:
				$pagevar['tkmsgs'][$i]['position'] = 'Вы';
			endif;
		endfor;
		$config['base_url'] 	= $pagevar['baseurl'].'manager-panel/actions/tickets/'.$this->uri->segment(4).'/view-ticket/'.$this->uri->segment(6).'/from/';
		$config['uri_segment'] 	= 8;
		$config['total_rows'] 	= $pagevar['count'];
		$config['per_page'] 	= 5;
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
		
		$pagevar['cntunit']['delivers']['paid'] = $this->mddelivesworks->count_records_by_manager_status($this->user['uid'],1);
		$pagevar['cntunit']['delivers']['total'] = $this->mddelivesworks->count_all_manager($this->user['uid']);
		$pagevar['cntunit']['platforms'] = $this->mdplatforms->count_records_by_manager($this->user['uid']);
		$pagevar['cntunit']['mails']['new'] = $this->mdmessages->count_records_by_recipient_new($this->user['uid'],$this->user['utype']);
		$pagevar['cntunit']['mails']['total'] = $this->mdmessages->count_records_by_recipient($this->user['uid'],$this->user['utype'],$this->user['signdate']);
		$pagevar['cntunit']['tickets']['inbox'] = $this->mdtickets->count_records_by_recipient($this->user['uid']);
		$pagevar['cntunit']['tickets']['outbox'] = $this->mdtickets->count_records_by_sender($this->user['uid']);
		
		$this->load->view("managers_interface/view-outbox-ticket",$pagevar);
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
				$this->mdlog->insert_record($this->user['uid'],'Событие №20: Состояние тикета - удален');
				$this->session->set_userdata('msgs','Сообшение удалено успешно');
			else:
				$this->session->set_userdata('msgr','Сообшение не удалено');
			endif;
			redirect($_SERVER['HTTP_REFERER']);
		else:
			show_404();
		endif;
	}

	public function hide_closed_tickets(){
		
		$statusval = array('status'=>TRUE,'hideticket'=>FALSE);
		$hide = trim($this->input->post('hide'));
		$this->session->set_userdata('hideticket',$statusval['hideticket']);
		if(!$hide):
			$this->session->set_userdata('hideticket',FALSE);
		else:
			$this->session->set_userdata('hideticket',TRUE);
			$statusval['hideticket'] = TRUE;
		endif;
		echo json_encode($statusval);
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
	
	public function operation_date_on_time($field){
			
		$list = preg_split("/-/",$field);
		$nmonth = $this->months[$list[1]];
		$pattern = "/(\d+)(-)(\w+)(-)(\d+) (\d+)(:)(\d+)(:)(\d+)/i";
		$replacement = "\$5 $nmonth \$1 г. \$6:\$8"; 
		return preg_replace($pattern, $replacement,$field);
	}
	
	public function split_date($field){
			
		$list = preg_split("/-/",$field);
		$nmonth = $this->months[$list[1]];
		$pattern = "/(\d+)(-)(\w+)(-)(\d+)/i";
		$replacement = "\$5 $nmonth \$1"; 
		return preg_replace($pattern, $replacement,$field);
	}
	
	public function escaping_domen($domen){
			
		$list = preg_split("/\./",$domen);
		return implode("\.", $list);
	}
	
	public function split_dot_date($field){
			
		$list = preg_split("/\./",$field);
		$nmonth = $this->months[$list[1]];
		$pattern = "/(\d+)(\.)(\w+)(\.)(\d+)/i";
		$replacement = "\$1 $nmonth \$5"; 
		return preg_replace($pattern, $replacement,$field);
	}
	
	public function operation_dot_date_on_time($field){
			
		$list = preg_split("/-/",$field);
		$pattern = "/(\d+)(-)(\w+)(-)(\d+) (\d+)(:)(\d+)(:)(\d+)/i";
		$replacement = "\$5.$3.\$1 \$6:\$8";
		return preg_replace($pattern, $replacement,$field);
	}
	
	public function operation_dot_date($field){
			
		$list = preg_split("/-/",$field);
		$pattern = "/(\d+)(-)(\w+)(-)(\d+)/i";
		$replacement = "\$5.$3.\$1"; 
		return preg_replace($pattern, $replacement,$field);
	}
	
	public function link_cabinet($uid,$plus=0){
		
		$utype = $this->mdusers->read_field($uid,'type');
		switch ($utype+$plus):
			case 1 : return '<a href="'.base_url().'webmaster-panel/actions/control">личный кабинет</a>';break;
			case 2 : return '<a href="'.base_url().'manager-panel/actions/control">личный кабинет</a>';break;
			case 3 : return '<a href="'.base_url().'optimizator-panel/actions/control">личный кабинет</a>';break;
			case 4 : show_404();break;
			case 5 : return '<a href="'.base_url().'admin-panel/management/users/all">личный кабинет</a>';break;
			
			case 11 : return '<a href="'.base_url().'webmaster-panel/actions/mails">Читать сообщение &raquo;</a>';break;
			case 12 : return '<a href="'.base_url().'manager-panel/actions/mails">Читать сообщение &raquo;</a>';break;
			case 13 : return '<a href="'.base_url().'optimizator-panel/actions/tickets">Читать сообщение &raquo;</a>';break;
			case 14 : show_404();break;
			case 15 : return '<a href="'.base_url().'admin-panel/management/mails">Читать сообщение &raquo;</a>';break;
			
			case 21 : return '<a href="'.base_url().'webmaster-panel/actions/tickets">Читать сообщение &raquo;</a>';break;
			case 22 : return '<a href="'.base_url().'manager-panel/actions/tickets/inbox">Читать сообщение &raquo;</a>';break;
			case 23 : return '<a href="'.base_url().'optimizator-panel/actions/tickets">Читать сообщение &raquo;</a>';break;
			case 24 : show_404();break;
			case 25 : return '<a href="'.base_url().'admin-panel/messages/tickets">Читать сообщение &raquo;</a>';break;
			default: show_404(); break;
		endswitch;
	}
	
	public function sub_mailtext($text,$uid){
		
		$text = strip_tags($text);
		if(mb_strlen($text,'UTF-8') > 150):
			$text = mb_substr($text,0,150,'UTF-8');
			$pos = mb_strrpos($text,' ',0,'UTF-8');
			$text = mb_substr($text,0,$pos,'UTF-8');
			$text .= ' ...<br/>'.$this->link_cabinet($uid,10);
		endif;
		return $text;
	}

	public function sub_tickettext($text,$uid){
		
		$text = strip_tags($text);
		if(mb_strlen($text,'UTF-8') > 150):
			$text = mb_substr($text,0,150,'UTF-8');
			$pos = mb_strrpos($text,' ',0,'UTF-8');
			$text = mb_substr($text,0,$pos,'UTF-8');
			$text .= ' ...<br/>'.$this->link_cabinet($uid,20);
		endif;
		return $text;
	}
	
	/******************************************************** Функции API *******************************************************************/
	
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