<?php if(!defined('BASEPATH')) exit('No direct script access allowed');

class General_interface extends CI_Controller{
	
	function __construct(){
		
		parent::__construct();
		$this->load->model('mdmarkets');
		$this->load->model('mdevents');
		$this->load->model('mdunion');

}

	/******************************************************** functions ******************************************************/	
	
	function viewimage(){
		
		$section = $this->uri->segment(1);
		$id = $this->uri->segment(3);
		switch ($section):
			case 'markets'	:	$image = $this->mdmarkets->get_image($id); break;
			case 'news'		:	$image = $this->mdevents->get_image($id); break;
			default			: 	show_404();break;
		endswitch;
		header('Content-type: image/gif');
		echo $image;
	}
	
	public function balance_result(){
		
		$result = 'Ошибок не обнаружено! ';
		if(!$this->input->post('LMI_PAYEE_PURSE') && !$this->input->post('LMI_HASH')):
			exit();
		endif;
		$hash = strtoupper(md5($_POST["LMI_PAYEE_PURSE"].$_POST["LMI_PAYMENT_AMOUNT"].$_POST["LMI_PAYMENT_NO"].$_POST["LMI_MODE"].$_POST["LMI_SYS_INVS_NO"].$_POST["LMI_SYS_TRANS_NO"].$_POST["LMI_SYS_TRANS_DATE"].'fjQlfu1kfi8qk'.$_POST["LMI_PAYER_PURSE"].$_POST["LMI_PAYER_WM"]));
		foreach($_POST as $index => $value):
			$result .= $index.' = '.$value.' <br>';
		endforeach;
		if($hash != $_POST["LMI_HASH"]):
			exit();
		endif;
		
		/*$result = '';
		$_POST["LMI_PAYER_WM"] = '231231231231';
		$_POST["LMI_PAYMENT_AMOUNT"] = 10000;*/
		if(isset($_POST["LMI_PAYER_WM"]) && isset($_POST["LMI_PAYMENT_AMOUNT"])):
			$user = array();
			$this->load->model('mdfillup');
			$this->load->model('mdusers');
			$this->load->model('mdlog');
			$user['uid'] = $this->mdusers->read_by_wmid($_POST["LMI_PAYER_WM"]);
			if($user['uid']):
				$user['balance'] = $this->mdusers->read_field($user['uid'],'balance');
				$this->mdfillup->insert_record($user['uid'],$_POST["LMI_PAYMENT_AMOUNT"],$result,1,1);
				$this->mdfillup->insert_record($user['uid'],$_POST["LMI_PAYMENT_AMOUNT"],"Пополнение баланса через WebMoney",0,1);
				$new_balance = $user['balance']+$_POST["LMI_PAYMENT_AMOUNT"];
				$this->mdusers->update_field($user['uid'],'balance',$new_balance);
				$this->mdlog->insert_record($user['uid'],'Событие №6: Баланс пополнен');
			else:
				$this->mdlog->insert_record($user['uid'],'Событие №24: Попытка начисления средств на не существующий WMID');
			endif;
		endif;
	}

	public function support(){
		
		$pagevar = array(
			'title'			=> 'Быстропост - система автоматической монетизации | Поддержка',
			'description'	=> '',
			'author'		=> '',
			'baseurl' 		=> base_url(),
			'msgauth'		=> $this->session->userdata('msgauth')
		);
		$this->session->unset_userdata('msgauth');
		$this->load->view("users_interface/support",$pagevar);
	}

	function load_views(){
	
		$type = $this->uri->segment(2);
		switch ($type):
			case 'market-profile'	:	$pagevar = array('markets'=>$this->mdmarkets->read_records(),'baseurl'=>base_url());
										$this->load->view('clients_interface/includes/markets-profile',$pagevar);
										break;
					default 		:	show_404();
		endswitch;
	}
	
	function distribution_of_notifications(){
		
		$start_time = microtime(true);
		
		$platforms = $this->mdunion->webmaster_locked_platforms();
		$webmasters = array();
		for($i=0,$j=0;$i<count($platforms);$i++,$j++):
			$webmasters[$j]['uid'] = $platforms[$i]['uid'];
			$webmasters[$j]['fio'] = $platforms[$i]['fio'];
			$webmasters[$j]['login'] = $platforms[$i]['login'];
			$webmasters[$j]['password'] = $this->encrypt->decode($platforms[$i]['cryptpassword']);
			$webmasters[$j]['platform'][] = $platforms[$i]['url'];
			for($n=$i+1;$n<count($platforms);$n++):
				if(isset($platforms[$n])):
					if($webmasters[$j]['uid'] == $platforms[$n]['uid']):
						$webmasters[$j]['platform'][] = $platforms[$n]['url'];
					else:
						$i = $n-1;
						break;
					endif;
				endif;
			endfor;
		endfor;
		for($i=0;$i<count($webmasters);$i++):
			ob_start();?>
			<?=anchor('','<img src="'.base_url().'images/logo.png" alt="" />');?>
			<p><strong>Здравствуйте<?=($webmasters[$i]['fio'] == 'Имя не указанно')?'!':', '.$webmasters[$i]['fio'].'!';?></strong></p>
			<p>В системе быстропост на данный момент, есть сайт(ы):
			<?php $platforms = ''; ?>
			<?php for($j=0;$j<count($webmasters[$i]['platform']);$j++):?>
				<?php $platforms .= $webmasters[$i]['platform'][$j];?>
				<?php if($j+1<count($webmasters[$i]['platform'])):?>
					<?php $platforms .= ', ';?>
				<?php endif; ?>
			<?php endfor; ?>
			<?=$platforms?> которые НЕ монетизируются.<br/>На данный момент у них статус в системе НЕактивен.<br/>
			Имеются ли проблемы с настройкой сайта или подключения дополнительных бирж? Планируется ли включение площадки?</p>
			<p>
				Ваши данные от системы <?=anchor('','http://bystropost.ru');?>
				<br/>L: <?=$webmasters[$i]['login'];?>
				<br/>P: <?=$webmasters[$i]['password'];?>
			</p>
			<p>Ждём вашего ответа. <?=mailto('sacred3@gmail.com','sacred3@gmail.com');?></p>
			<p>С уважением, Анатолий<br/><?=anchor('','http://bystropost.ru');?></p>
			<?
			$mailtext = ob_get_clean();
			$this->email->clear(TRUE);
			$config['smtp_host'] = 'localhost';
			$config['charset'] = 'utf-8';
			$config['wordwrap'] = TRUE;
			$config['mailtype'] = 'html';
			$this->email->initialize($config);
			$this->email->to($webmasters[$i]['login']);
			$this->email->from('sacred3@gmail.com','Монетизация Быстропост');
			$this->email->bcc('');
			$this->email->subject("Монетизация Быстропост");
			$this->email->message($mailtext);
//			$this->email->send();
			echo $mailtext.'<br/><br/><br/><br/>';
		endfor;
		
		$exec_time = round((microtime(true) - $start_time),2);
		$text = "<br/>Скрипт выполнен за: $exec_time сек.";
		echo($text);
	}
}