<?php if(!defined('BASEPATH')) exit('No direct script access allowed');

class Users_interface extends CI_Controller{
	
	function __construct(){
		
		parent::__construct();
		$this->load->model('mdusers');
		$this->load->model('mdunion');
		$this->load->model('mdmarkets');
		$this->load->model('mdratings');
		$this->load->model('mdlog');
		$this->load->model('mdevents');
		$this->load->model('mdpromocodes');
		
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
			'title'			=> 'Быстропост - система автоматической монетизации | Покупка статей | Комплексное продвижение сайта | Покупка ссылок, биржа копирайтинга | Написание статей для сайта',
			'description'	=> 'Мы предлагаем помощь оптимизаторам и вебмастерам. Комплексное продвижение сайта. SEO копирайтинг, уникальный контент для web сайтов, размещение ссылок на качественных сайтах.',
			'author'		=> 'grapheme',
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
					$this->session->set_userdata(array('logon'=>md5($user['login'].$user['password']),'userid'=>$user['id'],'ulogin'=>$user['login']));
					$this->mdusers->update_field($user['id'],'lastlogin',date("Y-m-d"));
					$this->access_cabinet($user['id'],$user['type']);
				endif;
			endif;
		endif;
		show_404();
	}
	
	public function actions_logoff(){
		
		$this->session->sess_destroy();
		redirect('');
	}
	
	public function restore_password(){
		
		if($this->session->userdata('ressuc')):
			$this->restore_successfull();
			return FALSE;
		endif;
		
		$pagevar = array(
			'title'			=> 'Быстропост | Восстановление пароля',
			'description'	=> 'Восстановление пароля для возобновления доступа к системе автоматической монетизации Быстропост',
			'author'		=> 'grapheme',
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
			'title'			=> 'Быстропост | Восстановление пароля',
			'description'	=> 'Восстановление пароля для возобновления доступа к системе автоматической монетизации Быстропост',
			'author'		=> 'grapheme',
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
			'title'			=> '',
			'description'	=> '',
			'author'		=> 'grapheme',
			'baseurl' 		=> base_url(),
			'msgauth'		=> $this->session->userdata('msgauth')
		);
		$this->session->unset_userdata('msgauth');
		switch ($this->uri->segment(2)):
			case 'gogetlinks' : 
				$pagevar['title'] .= 'Биржа вечных ссылок GoGetLinks | Система взаимодействия между сайтами вебмастера и деньгами оптимизатора';
				$pagevar['description'] .= 'Биржа вечных ссылок GoGetLinks, система взаимодействия между сайтами вебмастера и деньгами оптимизатора.'; 
				$this->load->view("users_interface/markets/market-gogetlinks",$pagevar); 
				break;
			case 'miralinks' : 
				$pagevar['title'] .= 'Биржа вечных статей Miralinks | Статейный маркетинг | Лучшая биржа статейного продвижения';
				$pagevar['description'] .= 'Биржа вечных статей Miralinks, cтатейный маркетинг, лучшая биржа статейного продвижения.'; 
				$this->load->view("users_interface/markets/market-miralinks",$pagevar); 
				break;
			case 'getgoodlinks' : 
				$pagevar['title'] .= 'Биржа вечных ссылок GetGoodLinks | Продвижение в поисковой системе Google';
				$pagevar['description'] .= 'Биржа вечных ссылок GetGoodLinks, продвижение в поисковой системе Google.'; 
				$this->load->view("users_interface/markets/market-getgoodlinks",$pagevar); 
				break;
			case 'blogocash' : 
				$pagevar['title'] .= 'Купить вечные ссылки | Продажа вечных ссылок | Биржа Блогокеш';
				$pagevar['description'] .= 'Биржа вечных ссылок Блогокеш. Эффективная система купли/продажи вечных ссылок на любых сайтах. Заработок даже с тех сайтов, где владельцами вы не являетесь.'; 
				$this->load->view("users_interface/markets/market-blogcash",$pagevar); 
				break;
			case 'prsape' : 
				$pagevar['title'] .= 'Качественный сервис по размещению статей и ссылок | Контекстные ссылки, написание уникального контента | Размещение "навсегда" от PR.Sape';
				$pagevar['description'] .= 'Pr.sape - качественный сервис по размещению статей и ссылок, контекстные ссылки, написание уникального контента, Размещение "навсегда" от PR.Sape.'; 
				$this->load->view("users_interface/markets/market-prsaperu",$pagevar); 
				break;
			case 'blogun' : 
				$pagevar['title'] .= 'Биржа постовых Blogun | Размещение постовых, покупка ссылок, реклама в социальных сетях, реклама в блоге';
				$pagevar['description'] .= 'Биржа постовых Blogun, размещение постовых, покупка ссылок, реклама в социальных сетях, реклама в блоге.'; 
				$this->load->view("users_interface/markets/market-blogun",$pagevar); 
				break;
			case 'rotapost' : 
				$pagevar['title'] .= 'Эффективная реклама в блогах | Эффективный сервис для продвижения сайтов | Размещение ссылок навсегда RotaPost';
				$pagevar['description'] .= 'Rotapost - купля/продажа вечных ссылок в новом контенте. Эффективная реклама в блогах, эффективный сервис для продвижения сайтов, размещение ссылок навсегда RotaPost.'; 
				$this->load->view("users_interface/markets/market-rotapost",$pagevar); 
				break;
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
			'title'			=> 'Быстропост | Отзывы о системе | Отзывы от рекламодателей и вебмастеров о системе',
			'description'	=> 'Полный список отзывов от рекламодателей и от вебмастеров по системе автоматической монетизации Быстропост.',
			'author'		=> 'grapheme',
			'ratings'		=> $this->mdratings->read_records($rtype),
			'baseurl' 		=> base_url(),
			'msgauth'		=> $this->session->userdata('msgauth')
		);
		$this->session->unset_userdata('msgauth');
		$this->load->view("users_interface/users-ratings",$pagevar);
	}
	
	public function reading_rating(){
		
		$pagevar = array(
			'title'			=> 'Быстропост | Отзывы о системе',
			'description'	=> 'Список отзывов по системе автоматической монетизации Быстропост.',
			'author'		=> 'grapheme',
			'rating'		=> $this->mdratings->read_record($this->uri->segment(4)),
			'baseurl' 		=> base_url(),
			'msgauth'		=> $this->session->userdata('msgauth')
		);
		$this->session->unset_userdata('msgauth');
		$this->load->view("users_interface/reading-rating",$pagevar);
	}
	
	public function idea(){
		
		$pagevar = array(
			'title'			=> 'Быстропост | Ваши идеи | Отзывы и предложения по системе автоматической монетизации Быстропост',
			'description'	=> 'Отзывы и предложения по системе Быстропост, система автоматической монетизации.',
			'author'		=> 'grapheme',
			'rating'		=> $this->mdratings->read_record($this->uri->segment(4)),
			'baseurl' 		=> base_url(),
			'msgauth'		=> $this->session->userdata('msgauth')
		);
		$this->session->unset_userdata('msgauth');
		$this->load->view("users_interface/idea",$pagevar);
	}
	
	public function about(){
		
		$pagevar = array(
			'title'			=> 'Сайты заработка в интернете | Продвижение вечными ссылками | Биржа ссылок, биржа копирайтинга, seo копирайтинг | Биржа покупки ссылок',
			'description'	=> 'Быстропост предлагает помощь вебмастерам и оптимизаторам - заработок с помощью сайта, профессиональный копирайтинг, размещение статей на качественных площадках. Работая с нами, вы экономите время и нервы.',
			'author'		=> 'grapheme',
			'baseurl' 		=> base_url(),
			'msgauth'		=> $this->session->userdata('msgauth')
		);
		$this->session->unset_userdata('msgauth');
		$this->load->view("users_interface/about",$pagevar);
	}
	
	public function partners_program(){
		
		$pagevar = array(
			'title'			=> 'Быстропост | Партнерская программа',
			'description'	=> 'Теперь каждый может зарабатывать деньги через партнерскую программу. Условия просты, за каждую оплаченную заявку мы вам начисляем 5% от стоимости.',
			'author'		=> 'grapheme',
			'baseurl' 		=> base_url(),
			'msgauth'		=> $this->session->userdata('msgauth')
		);
		$this->session->unset_userdata('msgauth');
		$this->load->view("users_interface/partners-program",$pagevar);
	}
	
	public function webmasters(){
		
		$pagevar = array(
			'title'			=> 'Биржа вечных ссылок, продажа статей, размещение ссылок, написание текстов на сайт | Как продвигать сайт самостоятельно | Быстропост',
			'description'	=> 'Помощь вебмастерам. Полная работа под ключ - мониторинг биржи, общение с оптимизаторами, выполнение заявок, написание уникального текста, публикация контента, сдача заявки в биржу.',
			'author'		=> 'grapheme',
			'baseurl' 		=> base_url(),
			'msgauth'		=> $this->session->userdata('msgauth')
		);
		$this->session->unset_userdata('msgauth');
		$this->load->view("users_interface/webmasters",$pagevar);
	}
	
	public function optimizers(){
		
		$pagevar = array(
			'title'			=> 'Продвижение статьями, рерайт текста | Заработок с помощью сайта, копирайтинг | Написание статей  для сайта, покупка статей | Быстропост',
			'description'	=> 'Быстропост предлагает помощь оптимизаторам - заработок с помощью сайта, профессиональный копирайтинг, размещение статей на качественных площадках. Работая с нами, вы экономите время и нервы.',
			'author'		=> 'grapheme',
			'baseurl' 		=> base_url(),
			'msgauth'		=> $this->session->userdata('msgauth')
		);
		$this->session->unset_userdata('msgauth');
		$this->load->view("users_interface/optimizers",$pagevar);
	}
	
	public function faq(){
		
		$pagevar = array(
			'title'			=> 'Часто задаваемые вопросы по системе Быстропост | Лучшая биржа для монетизации вашего ресурса',
			'description'	=> 'Распространенные вопросы в системе Быстропост. Лучшая биржа для монетизации вашего ресурса.',
			'author'		=> 'grapheme',
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
			'author'		=> 'grapheme',
			'baseurl' 		=> base_url(),
			'msgauth'		=> $this->session->userdata('msgauth')
		);
		$this->session->unset_userdata('msgauth');
		$this->load->view("users_interface/manner-payment",$pagevar);
	}
	
	public function site_monetization(){
		
		$pagevar = array(
			'title'			=> 'Порядок оплаты | Система автоматической монетизации Быстропост',
			'description'	=> 'Порядок оплаты по системе автоматической монетизации Быстропост.',
			'author'		=> 'grapheme',
			'baseurl' 		=> base_url(),
			'msgauth'		=> $this->session->userdata('msgauth')
		);
		$this->session->unset_userdata('msgauth');
		$this->load->view("users_interface/site-monetization",$pagevar);
	}
	
	public function additional_services(){
		
		$pagevar = array(
			'title'			=> 'Быстропост | Дополнительные услуги',
			'description'	=> 'Полный список дополнительных услуг, предоставляемых системой автоматический монетизации Быстропост.',
			'author'		=> 'grapheme',
			'baseurl' 		=> base_url(),
			'msgauth'		=> $this->session->userdata('msgauth')
		);
		$this->session->unset_userdata('msgauth');
		$this->load->view("users_interface/additional-services",$pagevar);
	}
	
	public function disclaimer(){
		
		$pagevar = array(
			'title'			=> 'Быстропост | Уведомление об ответственности',
			'description'	=> 'Мы являемся независимым предприятием, оказывающим услуги, и самостоятельно принимаем решения о ценах и предложениях.',
			'author'		=> 'grapheme',
			'baseurl' 		=> base_url(),
			'msgauth'		=> $this->session->userdata('msgauth')
		);
		$this->session->unset_userdata('msgauth');
		$this->load->view("users_interface/disclaimer",$pagevar);
	}
	
	public function about_content(){
		
		$pagevar = array(
			'title'			=> 'Биржа копирайтинга | Размещение статей, seo копирайтинг | Квечные ссылки, сайты заработка в интернете | Быстропост',
			'description'	=> 'Быстропост предлагает услуги копирайтинга. Весь контент пишется людьми, у которых богатый опыт в написание контента любой сложности и тематики. Многолетний опыт работы.',
			'author'		=> 'grapheme',
			'baseurl' 		=> base_url(),
			'msgauth'		=> $this->session->userdata('msgauth')
		);
		$this->session->unset_userdata('msgauth');
		$this->load->view("users_interface/about-content",$pagevar);
	}
	
	public function capabilities(){
		
		$pagevar = array(
			'title'			=> 'Наши возможности | Сайты для размещения ссылок | Биржа покупки ссылок | Написание статей на сайт размещение статей | Контент для сайта',
			'description'	=> 'Быстропост предлагает помощь вебмастерам и оптимизаторам - мгновенное выполнение заявок, написание уникального текста, публикация контента, сдача заявки в биржу. Качественная работа, качественные тексты, открывают дорогу вашему ресурсу в белые списки оптимизаторов.',
			'author'		=> 'grapheme',
			'baseurl' 		=> base_url(),
			'msgauth'		=> $this->session->userdata('msgauth')
		);
		$this->session->unset_userdata('msgauth');
		$this->load->view("users_interface/capabilities",$pagevar);
	}
	
	public function site_interface(){
		
		$pagevar = array(
			'title'			=> 'Быстропост | Интерфейс системы | Основные разделы',
			'description'	=> 'Описание основных разделов системы автоматической монетизации Быстропост.',
			'author'		=> 'grapheme',
			'baseurl' 		=> base_url(),
			'msgauth'		=> $this->session->userdata('msgauth')
		);
		$this->session->unset_userdata('msgauth');
		$this->load->view("users_interface/interface",$pagevar);
	}
	
	public function news(){
		
		$from = intval($this->uri->segment(3));
		$pagevar = array(
			'title'			=> 'Быстропост | Новости системы | Обновления системы',
			'description'	=> 'Последние новости о нововведениях и улучшениях в системе Быстропост. Новости о работе с биржами gogetlinks, miralinks, rotapost, sape.',
			'author'		=> 'grapheme',
			'baseurl' 		=> base_url(),
			'pages'			=> array(),
			'events'		=> $this->mdevents->read_records_limit(5,$from),
			'msgauth'		=> $this->session->userdata('msgauth')
		);
		$this->session->unset_userdata('msgauth');
		
		$config['base_url'] 		= $pagevar['baseurl'].'news/from/';
		$config['uri_segment'] 		= 3;
		$config['total_rows'] 		= $this->mdevents->count_records();
		$config['per_page'] 		= 5;
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
		
		for($i=0;$i<count($pagevar['events']);$i++):
			$pagevar['events'][$i]['date'] = $this->operation_date($pagevar['events'][$i]['date']);
		endfor;
		
		$this->load->view("users_interface/news",$pagevar);
	}
	
	public function news_view(){
		
		$nid = $this->mdevents->read_field_translit($this->uri->segment(3),'id');
		if(!$nid):
			redirect($this->session->userdata('backpath'));
		endif;
		$pagevar = array(
			'title'			=> '| Новости | Быстропост',
			'description'	=> '',
			'author'		=> 'grapheme',
			'baseurl' 		=> base_url(),
			'pages'			=> array(),
			'event'			=> $this->mdevents->read_record($nid),
			'msgauth'		=> $this->session->userdata('msgauth')
		);
		$this->session->unset_userdata('msgauth');
		
		$pagevar['event']['date'] = $this->operation_date($pagevar['event']['date']);
		$pagevar['title'] = $pagevar['event']['title']." | Новости | Быстропост"; 
		
		$this->load->view("users_interface/news-view",$pagevar);
	}
	
	public function rss(){
		
		$pagevar = array(
			'title'			=> 'Быстропост | RSS лента | Новости системы | Обновления системы',
			'description'	=> 'Последние новости о нововведениях и улучшениях в системе Быстропост. Новости о работе с биржами gogetlinks, miralinks, rotapost, sape.',
			'author'		=> 'grapheme',
			'baseurl' 		=> base_url(),
			'pages'			=> array(),
			'events'		=> $this->mdevents->read_records_limit(50,0)
		);
		
		for($i=0;$i<count($pagevar['events']);$i++):
			$pagevar['events'][$i]['date'] = $this->operation_date($pagevar['events'][$i]['date']);
		endfor;
		
		$this->load->view("users_interface/rss",$pagevar);
	}
	
	public function contacts(){
		
		$pagevar = array(
			'title'			=> 'Система автоматической монетизации Быстропост | Контактная информация',
			'description'	=> 'Контактная информация, адрес и телефоны системы Быстропост.',
			'author'		=> 'grapheme',
			'baseurl' 		=> base_url(),
			'msgauth'		=> $this->session->userdata('msgauth')
		);
		$this->session->unset_userdata('msgauth');
		$this->load->view("users_interface/contacts",$pagevar);
	}
	
	public function prices(){
		
		$pagevar = array(
			'title'			=> 'Быстропост | Цены на услуги | Продажа статей, покупка ссылок, написание текстов на сайт | Проверенные сайты заработка, размещение рекламы на сайте',
			'description'	=> 'Каждый новый клиент Быстропост по умолчанию, имеет базовый комплект за 50 рублей: написание контента, публикация контента с загрузкой изображения на ваш хостинг. Мы стараемся найти индивидуальный подход к каждому клиенту.',
			'author'		=> 'grapheme',
			'baseurl' 		=> base_url(),
			'msgauth'		=> $this->session->userdata('msgauth')
		);
		$this->session->unset_userdata('msgauth');
		$this->load->view("users_interface/prices",$pagevar);
	}
	
	public function forum(){
		
		$pagevar = array(
			'title'			=> 'Быстропост | Форум',
			'description'	=> 'Обсуждения по системе автоматической монетизации Быстропост',
			'author'		=> 'grapheme',
			'baseurl' 		=> base_url(),
			'msgauth'		=> $this->session->userdata('msgauth')
		);
		$this->session->unset_userdata('msgauth');
		$this->load->view("users_interface/forum",$pagevar);
	}
	
	public function site_map(){
		
		$pagevar = array(
			'title'			=> 'Система автоматической монетизации Быстропост | Карта сайта',
			'description'	=> 'Карта сайта. Система автоматической монетизации Быстропост.',
			'author'		=> 'grapheme',
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
			case 5 : redirect('admin-panel/management/users/webmasters');break;
			default: show_404(); break;
		endswitch;
	}
	
	public function registering(){
		
		if($this->uri->segment(1) == 'partner'):
			if($this->mdusers->read_field($this->uri->segment(2),'type') == 1):
				$this->session->set_userdata('partner',$this->uri->segment(2));
			endif;
			redirect('users/registering/webmaster');
		endif;
		
		$redirect = $this->uri->segment(3).'s';
		if(isset($_SERVER['HTTP_REFERER'])):
			$redirect = $_SERVER['HTTP_REFERER'];
		endif;
		$usertype = $this->uri->segment(3);
		switch ($usertype):
			case 'webmaster': 	$tutype = 'вебмастера';$utype = 1; break;
			case 'optimizer': 	redirect($redirect);break;
//									$tutype = 'оптимизатора';$utype = 3; break;
			default			: 	redirect($redirect);break;
		endswitch;
		$pagevar = array(
				'title'			=> 'Cистема автоматической монетизации Быстропост | Регистрация пользователей',
				'description'	=> 'Регистрация в системе автоматической монетизации Быстропост.',
				'author'		=> 'grapheme',
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
			unset($_POST['submit']);
			$this->form_validation->set_rules('fio',' ','required|trim');
			$this->form_validation->set_rules('login',' ','required|valid_email|trim');
			$this->form_validation->set_rules('password',' ','required|trim');
			$this->form_validation->set_rules('confpass',' ','required|trim');
			$this->form_validation->set_rules('wmid',' ','required|integer|exact_length[12]|trim');
			$this->form_validation->set_rules('knowus',' ','trim');
			$this->form_validation->set_rules('promo',' ','trim');
			if(!$this->form_validation->run()):
				$this->session->set_userdata('msgr','Ошибка. Неверно заполнены необходимые поля<br/>');
				redirect($this->uri->uri_string());
			else:
				if($this->mdusers->user_exist('login',$_POST['login'])):
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
						if(isset($remote_user['id']) && is_numeric($remote_user['id'])):
							if(!empty($_POST['promo'])):
								$codeid = $this->mdpromocodes->exist_code(strtolower($_POST['promo']));
								if($codeid):
									$promocode = $this->mdpromocodes->read_record($codeid);
									$param = 'userid='.$remote_user['id'].'&user='.$_POST['fio'].'&email='.$_POST['login'].'&coupon_price='.$promocode['price'].'&coupon_code='.strtolower($promocode['code']).'&coupon_count='.$promocode['count'].'&coupon_birzid='.$promocode['birzid'].'&coupon_date='.$promocode['dateto'];
									$result = $this->API('EditUser',$param);
								endif;
							endif;
							$this->mdusers->update_field($uid,'remoteid',$remote_user['id']);
						endif;
					endif;
					$partner = $this->session->userdata('partner');
					if($partner):
						$this->mdusers->update_field($uid,'partner_id',$partner);
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

	public function viewimage(){
		
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

	public function operation_date($field){
		
		$months = array("01"=>"января","02"=>"февраля","03"=>"марта","04"=>"апреля","05"=>"мая","06"=>"июня",
						"07"=>"июля","08"=>"августа","09"=>"сентября","10"=>"октября","11"=>"ноября","12"=>"декабря");
		
		$list = preg_split("/-/",$field);
		$nmonth = $months[$list[1]];
		$pattern = "/(\d+)(-)(\w+)(-)(\d+)/i";
		$replacement = "\$5 $nmonth \$1 г."; 
		return preg_replace($pattern, $replacement,$field);
	}
	
}