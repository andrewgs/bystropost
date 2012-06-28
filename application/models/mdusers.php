<?php if(!defined('BASEPATH')) exit('No direct script access allowed');

class Mdusers extends CI_Model{

	var $id   			= 0;
	var $login 			= '';
	var $cryptpassword 	= '';
	var $password 	= '';
	var $fio  			= '';
	var $wmid  			= '';
	var $knowus			= '';
	var $phones  		= '';
	var $icq  			= '';
	var $skype  		= '';
	var $forum	  		= '';
	var $balance  		= 0;
	var $logo			= '';
	var $signdate  		= '';
	var $closedate  	= '';
	var $sendmail 	 	= 0;
	var $type	  		= 1;

	function __construct(){
        
		parent::__construct();
    }
	
	function insert_record($data){
			
		$this->login 			= $data['login'];
		$this->cryptpassword	= $this->encrypt->encode($data['password']);
		$this->password			= md5($data['password']);
		$this->fio 				= $data['fio'];
		$this->wmid 			= $data['wmid'];
		$this->knowus 			= $data['knowus'];
		$this->balance 			= 0;
		$this->signdate 		= date("Y-m-d");
		$this->closedate		= "0000-00-00";
		$this->sendmail 		= $data['sendmail'];
		$this->type 			= 1;
		
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
		endif;
		$this->db->where('id',$_POST['uid']);
		$this->db->update('users');
		return $this->db->affected_rows();
	}
	
	function read_record($id){
		
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
		if(count($data) > 0) return $data[0]['id'];
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
	
	function delete_record($id){
	
		$this->db->where('id',$id);
		$this->db->delete('users');
		return $this->db->affected_rows();
	}	
}