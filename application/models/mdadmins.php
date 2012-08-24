<?php if(!defined('BASEPATH')) exit('No direct script access allowed');

class Mdadmins extends CI_Model {

var $id				= 0;
var $login			= '';
var $password		= '';
var $cryptpassword 	= '';
var $email			= '';

	function __construct(){
		parent::__construct();
	}
	
	function insert_record($data){
			
		$this->login 			= $data['login'];
		$this->password			= md5($data['password']);
		$this->cryptpassword	= $this->encrypt->encode($data['password']);
		$this->email 			= $data['email'];
		
		$this->db->insert('admins',$this);
		return $this->db->insert_id();
	}
	
	function read_record($id){
		
		$this->db->where('id',$id);
		$query = $this->db->get('admins',1);
		$data = $query->result_array();
		if(isset($data[0])) return $data[0];
		return NULL;
	}
	
	function auth_user($login,$password){
		
		$this->db->where('login',$login);
		$this->db->where('password',md5($password));
		$query = $this->db->get('admins',1);
		$data = $query->result_array();
		if(isset($data[0])) return $data[0];
		return NULL;
	}

	function user_exist($field,$parameter){
			
		$this->db->where($field,$parameter);
		$query = $this->db->get('admins',1);
		$data = $query->result_array();
		if(count($data) > 0) return $data[0]['id'];
		return FALSE;
	}
	
	function user_id($field,$parameter){
			
		$this->db->where($field,$parameter);
		$query = $this->db->get('admins',1);
		$data = $query->result_array();
		if(count($data)>0) return $data[0]['id'];
		return FALSE;
	}
	
	function read_field($id,$field){
			
		$this->db->where('id',$id);
		$query = $this->db->get('admins',1);
		$data = $query->result_array();
		if(isset($data[0])) return $data[0][$field];
		return FALSE;
	}
	
	function delete_record($id){
	
		$this->db->where('id',$id);
		$this->db->delete('admins');
		return $this->db->affected_rows();
	}	
}