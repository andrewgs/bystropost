<?php if(!defined('BASEPATH')) exit('No direct script access allowed');

class Mdmkplatform extends CI_Model{

	var $id			= 0;
	var $webmaster	= 0;
	var $platform	= 0;
	var $market 	= 0;
	var $login 		= '';
	var $password 	= '';
	var $cryptpassword 	= '';
	
	function __construct(){
		parent::__construct();
	}
	
	function insert_record($uid,$platform,$market,$login,$password){
			
		$this->webmaster 	= $uid;
		$this->platform 	= $platform;
		$this->market 		= $market;
		$this->login 		= $login;
		$this->password 	= md5($password);
		$this->cryptpassword= $this->encrypt->encode($password);
		
		$this->db->insert('mkplatform',$this);
		return $this->db->insert_id();
	}
	
	function group_insert($uid,$platform,$data){
		$query = '';
		for($i=0;$i<count($data);$i++):
			
			$query .= '('.$uid.','.$platform.','.$data[$i]['mkid'].',"'.$data[$i]['mklogin'].'","'.md5($data[$i]['mkpass']).'","'.$this->encrypt->encode($data[$i]['mkpass']).'") ';
			if($i+1<count($data)):
				$query.=',';
			endif;
		endfor;
		$this->db->query("INSERT INTO mkplatform (webmaster,platform,market,login,password,cryptpassword) VALUES ".$query);
	}
	
	function read_records(){
		
		$this->db->order_by('title');
		$query = $this->db->get('mkplatform');
		$data = $query->result_array();
		if(count($data)) return $data;
		return NULL;
	}
	
	function read_records_by_platform($platform,$uid){
		
		$this->db->where('platform',$platform);
		$this->db->where('webmaster',$uid);
		$query = $this->db->get('mkplatform');
		$data = $query->result_array();
		if(count($data)) return $data;
		return NULL;
	}
	
	function read_records_by_webmaster($uid){
		
		$this->db->where('webmaster',$uid);
		$query = $this->db->get('mkplatform');
		$data = $query->result_array();
		if(count($data)) return $data;
		return NULL;
	}
	
	function read_records_platform($platform){
		
		$this->db->order_by('platform,id');
		$this->db->group_by('market');
		$this->db->where('platform',$platform);
		$query = $this->db->get('mkplatform');
		$data = $query->result_array();
		if(count($data)) return $data;
		return NULL;
	}
	
	function read_record($id){
		
		$this->db->where('id',$id);
		$query = $this->db->get('mkplatform',1);
		$data = $query->result_array();
		if(isset($data[0])) return $data[0];
		return NULL;
	}
	
	function read_field($id,$field){
			
		$this->db->where('id',$id);
		$query = $this->db->get('mkplatform',1);
		$data = $query->result_array();
		if(isset($data[0])) return $data[0][$field];
		return FALSE;
	}
	
	function delete_record($id){
	
		$this->db->where('id',$id);
		$this->db->delete('mkplatform');
		return $this->db->affected_rows();
	}
	
	function exist_market_platform($platform,$market,$login,$password){
		
		$this->db->where('market',$market);
		$this->db->where('platform',$platform);
		$this->db->where('login',$login);
		$this->db->where('password',md5($password));
		$query = $this->db->get('mkplatform');
		$data = $query->result_array();
		if(count($data)) return TRUE;
		return FALSE;
	}
	
	function delete_records_by_platform($platform,$uid){
	
		$this->db->where('platform',$platform);
		$this->db->where('webmaster',$uid);
		$this->db->delete('mkplatform');
		return $this->db->affected_rows();
	}
	
	function delete_records_by_webmarket($webmaster,$market,$login,$password){
	
		$this->db->where('webmaster',$webmaster);
		$this->db->where('market',$market);
		$this->db->where('login',$login);
		$this->db->where('password',$password);
		$this->db->delete('mkplatform');
		return $this->db->affected_rows();
	}
}