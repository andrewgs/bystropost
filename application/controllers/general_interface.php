<?php if(!defined('BASEPATH')) exit('No direct script access allowed');

class General_interface extends CI_Controller{
	
	function __construct(){
		
		parent::__construct();
		$this->load->model('mdmarkets');

}
	/******************************************************** functions ******************************************************/	
	
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
	
	public function balance_result(){
		
		$result = '';
		if(!$this->input->post('LMI_PAYEE_PURSE') && !$this->input->post('LMI_HASH')):
			//show_404();
			exit();
		endif;
		$hash = strtoupper(md5($_POST["LMI_PAYEE_PURSE"].$_POST["LMI_PAYMENT_AMOUNT"].$_POST["LMI_PAYMENT_NO"].$_POST["LMI_MODE"].$_POST["LMI_SYS_INVS_NO"].$_POST["LMI_SYS_TRANS_NO"].$_POST["LMI_SYS_TRANS_DATE"].'fjQlfu1kfi8qk'.$_POST["LMI_PAYER_PURSE"].$_POST["LMI_PAYER_WM"]));
		foreach($_POST as $index => $value):
			$result .= $index.' = '.$value.' <br>';
		endforeach;
		if($hash != $_POST["LMI_HASH"]):
			//show_404();
			exit();
		endif;
		
		/*
		$_POST["LMI_PAYER_WM"] = 915236488902;
		$_POST["LMI_PAYMENT_AMOUNT"] = 100;*/
		if(isset($_POST["LMI_PAYER_WM"]) && isset($_POST["LMI_PAYMENT_AMOUNT"])):
			$user = array();
			$this->load->model('mdfillup');
			$this->load->model('mdusers');
			$this->load->model('mdlog');
			$user['uid'] = $this->mdusers->read_by_wmid($_POST["LMI_PAYER_WM"]);
			if($user['uid']):
				$user['balance'] = $this->mdusers->read_field($user['uid'],'balance');
				$this->mdfillup->insert_record($user['uid'],$_POST["LMI_PAYMENT_AMOUNT"],$result);
				$new_balance = $user['balance']+$_POST["LMI_PAYMENT_AMOUNT"];
				$this->mdusers->update_field($user['uid'],'balance',$new_balance);
				$this->mdlog->insert_record($user['uid'],'Событие №6: Баланс пополнен');
			else:
				$this->mdlog->insert_record($user['uid'],'Событие №24: Попытка начисления средств на не существующий WMID');
			endif;
		endif;
	}
}