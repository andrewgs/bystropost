<?php if(!defined('BASEPATH')) exit('No direct script access allowed');

class Mdattachedservices extends CI_Model{

	var $id			= 0;
	var $user		= 0;
	var $valuesrv	= 0;
	var $platform	= 0;
	var $service	= 0;
	var $date 		= 0;
	var $wprice		= 0;
	var $mprice		= 0;
	
	function __construct(){
		parent::__construct();
	}
	
	function insert_record($uid,$serveice,$valuesrv,$platform,$wprice,$mprice){
			
		$this->user 	= $uid;
		$this->serveice = $serveice;
		$this->wprice 	= $wprice;
		$this->mprice 	= $mprice;
		$this->valuesrv = $valuesrv;
		$this->platform = $platform;
		$this->date 	= date("Y-m-d");
		
		$this->db->insert('attachedservices',$this);
		return $this->db->insert_id();
	}
	
	function group_insert($uid,$service,$valuesrv,$platforms){
	
		$query = '';
		for($i=0;$i<count($platforms);$i++):
			$query .= '('.$uid.','.$valuesrv.','.$platforms[$i]['id'].','.$service.',"'.date("Y-m-d").'",0,0)';
			if($i+1<count($platforms)):
				$query.=',';
			endif;
		endfor;
		$this->db->query("INSERT INTO attachedservices (user,valuesrv,platform,service,date,wprice,mprice) VALUES ".$query);
	}
	
	function update_field($id,$field,$value){
			
		$this->db->set($field,$value);
		$this->db->where('id',$id);
		$this->db->update('attachedservices');
		return $this->db->affected_rows();
	}
	
	function read_records($uid){
		
		$this->db->order_by('date');
		$this->db->where('user',$uid);
		$query = $this->db->get('attachedservices');
		$data = $query->result_array();
		if(count($data)) return $data;
		return NULL;
	}
	
	function read_records_service($service,$uid){
		
		$this->db->where('service',$service);
		$this->db->where('user',$uid);
		$query = $this->db->get('attachedservices');
		$data = $query->result_array();
		if(count($data)) return $data;
		return NULL;
	}
	
	function read_records_service_platform($service,$platform,$uid){
		
		$this->db->where('service',$service);
		$this->db->where('platform',$platform);
		$this->db->where('user',$uid);
		$query = $this->db->get('attachedservices',1);
		$data = $query->result_array();
		if(isset($data[0])) return $data[0];
		return NULL;
	}
	
	function read_record($id,$uid){
		
		$this->db->where('id',$id);
		$this->db->where('user',$uid);
		$query = $this->db->get('attachedservices',1);
		$data = $query->result_array();
		if(isset($data[0])) return $data[0];
		return NULL;
	}
	
	function read_field($id,$field){
			
		$this->db->where('id',$id);
		$query = $this->db->get('attachedservices',1);
		$data = $query->result_array();
		if(isset($data[0])) return $data[0][$field];
		return FALSE;
	}
	
	function delete_record($id){
	
		$this->db->where('id',$id);
		$this->db->delete('attachedservices');
		return $this->db->affected_rows();
	}
	
	function delete_records($service,$uid){
	
		$this->db->where('service',$service);
		$this->db->where('user',$uid);
		$this->db->delete('attachedservices');
		return $this->db->affected_rows();
	}
	
	function delete_records_by_service($service){
	
		$this->db->where('service',$service);
		$this->db->delete('attachedservices');
		return $this->db->affected_rows();
	}
	
	function delete_records_by_user($uid){
	
		$this->db->where('user',$uid);
		$this->db->delete('attachedservices');
		return $this->db->affected_rows();
	}

	function service_exist($uid,$service){
		
		$this->db->where('user',$uid);
		$this->db->where('service',$service);
		$query = $this->db->get('attachedservices');
		$data = $query->result_array();
		if(count($data)) return TRUE;
		return FALSE;
	}

	function service_admin_exist($service){
		
		$this->db->where('service',$service);
		$query = $this->db->get('attachedservices');
		$data = $query->result_array();
		if(count($data)) return TRUE;
		return FALSE;
	}
}