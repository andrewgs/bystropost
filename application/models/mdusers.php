<?php if(!defined('BASEPATH')) exit('No direct script access allowed');

class Mdusers extends CI_Model{

	var $id   			= 0;
	var $login 			= '';
	var $cryptpassword 	= '';
	var $password 		= '';
	var $fio  			= '';
	var $wmid  			= '';
	var $knowus			= '';
	var $phones  		= '';
	var $icq  			= '';
	var $skype  		= '';
	var $forum	  		= '';
	var $balance  		= 0;
	var $logo			= '';
	var $signdate  		= '0000-00-00';
	var $lastlogin 		= '0000-00-00';
	var $closedate  	= '0000-00-00';
	var $sendmail 	 	= 0;
	var $type	  		= 1;
	var $position	  	= '';

	function __construct(){
		parent::__construct();
	}
	
	function insert_record($data,$utype){

		$this->login 			= $data['login'];
		$this->cryptpassword	= $this->encrypt->encode($data['password']);
		$this->password			= md5($data['password']);
		$this->fio 				= $data['fio'];
		$this->wmid 			= $data['wmid'];
		$this->knowus 			= $data['knowus'];
		$this->signdate 		= date("Y-m-d");
		$this->sendmail 		= $data['sendmail'];
		$this->type 			= $utype;
		
		switch ($utype):
			case 1 : $this->position = 'Вебмастер';break;
			case 2 : $this->position = 'Менеджер';break;
			case 3 : $this->position = 'Оптимизатор';break;
			case 4 : $this->position = 'Резерв';break;
			case 5 : $this->position = 'Администратор';break;
			default: $this->position = 'Не определен';break;
		endswitch;
		
		$this->db->insert('users',$this);
		return $this->db->insert_id();
	}
	
	function update_record($data){
		
		if(isset($_POST['login'])):
			$this->db->set('login',$_POST['login']);
		endif;
		if(isset($_POST['password'])):
			$this->db->set('password',md5($data['password']));
			$this->db->set('cryptpassword',$this->encrypt->encode($data['password']));
		endif;
		if(isset($_POST['wmid'])):
			$this->db->set('wmid',$_POST['wmid']);
		endif;
		if(isset($_POST['knowus'])):
			$this->db->set('knowus',$_POST['knowus']);
		endif;
		if(isset($_POST['fio'])):
			$this->db->set('fio',$_POST['fio']);
		endif;
		if(isset($_POST['phones'])):
			$this->db->set('phones',$_POST['phones']);
		endif;
		if(isset($_POST['balance'])):
			$this->db->set('balance',$_POST['balance']);
		endif;
		if(isset($_POST['icq'])):
			$this->db->set('icq',$_POST['icq']);
		endif;
		if(isset($_POST['skype'])):
			$this->db->set('skype',$_POST['skype']);
		endif;
		if(isset($_POST['forum'])):
			$this->db->set('forum',$_POST['forum']);
		endif;
		if(isset($_POST['logo'])):
			$this->db->set('logo',$_POST['logo']);
		endif;
		if(isset($_POST['sendmail'])):
			$this->db->set('sendmail',$_POST['sendmail']);
		endif;
		if(isset($_POST['type'])):
			$this->db->set('type',$_POST['type']);
			switch ($_POST['type']):
				case 1 : $this->db->set('position','Вебмастер');break;
				case 2 : $this->db->set('position','Менеджер');break;
				case 3 : $this->db->set('position','Оптимизатор');break;
				case 4 : $this->db->set('position','Резерв');break;
				case 5 : $this->db->set('position','Администратор');break;
				default: $this->db->set('position','Не определен');break;
			endswitch;
		endif;
		$this->db->where('id',$_POST['uid']);
		$this->db->update('users');
		return $this->db->affected_rows();
	}
	
	function read_email_record($email){
		
		$this->db->select('id,login,fio,cryptpassword,signdate,closedate,position');
		$this->db->where('login',$email);
		$this->db->where('closedate =','0000-00-00');
		$query = $this->db->get('users');
		$data = $query->result_array();
		if(isset($data[0])) return $data[0];
		return NULL;
	}
	
	function read_record($id){
		
		$this->db->where('id',$id);
		$query = $this->db->get('users',1);
		$data = $query->result_array();
		if(isset($data[0])) return $data[0];
		return NULL;
	}
	
	function read_users_by_type($type){
		
		$this->db->where('type',$type);
		$query = $this->db->get('users');
		$data = $query->result_array();
		if(count($data)) return $data;
		return NULL;
	}
	
	function read_small_info($id){
		
		$this->db->select('id,login,fio,phones,icq,skype,balance,signdate,position');
		$this->db->where('id',$id);
		$query = $this->db->get('users',1);
		$data = $query->result_array();
		if(isset($data[0])) return $data[0];
		return NULL;
	}
	
	function auth_user($login,$password){
		
		$this->db->where('login',$login);
		$this->db->where('password',md5($password));
		$query = $this->db->get('users',1);
		$data = $query->result_array();
		if(isset($data[0])) return $data[0];
		return NULL;
	}

	function user_exist($field,$parameter){
			
		$this->db->where($field,$parameter);
		$query = $this->db->get('users',1);
		$data = $query->result_array();
		if(count($data)) return $data[0]['id'];
		return FALSE;
	}
	
	function user_id($field,$parameter){
			
		$this->db->where($field,$parameter);
		$query = $this->db->get('users',1);
		$data = $query->result_array();
		if(count($data)>0) return $data[0]['id'];
		return FALSE;
	}
	
	function read_field($id,$field){
			
		$this->db->where('id',$id);
		$query = $this->db->get('users',1);
		$data = $query->result_array();
		if(isset($data[0])) return $data[0][$field];
		return FALSE;
	}
	
	function update_field($id,$field,$value){
			
		$this->db->set($field,$value);
		$this->db->where('id',$id);
		$this->db->update('users');
		return $this->db->affected_rows();
	}
	
	function delete_record($id){
	
		$this->db->where('id',$id);
		$this->db->delete('users');
		return $this->db->affected_rows();
	}	
}