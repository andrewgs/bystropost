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