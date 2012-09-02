<?php if(!defined('BASEPATH')) exit('No direct script access allowed');

class Mdattachedservices extends CI_Model{

	var $id			= 0;
	var $user		= 0;
	var $valuesrv	= 0;
	var $platform	= 0;
	var $service	= 0;
	var $date 		= 0;
	var $price		= 0;
	
	function __construct(){
		parent::__construct();
	}
	
	function insert_record($uid,$serveice,$valuesrv,$platform,$price){
			
		$this->user 	= $uid;
		$this->serveice = $serveice;
		$this->price 	= $price;
		$this->valuesrv = $valuesrv;
		$this->platform = $platform;
		$this->date 	= date("Y-m-d");
		
		$this->db->insert('attachedservices',$this);
		return $this->db->insert_id();
	}
	
	function read_records($uid){
		
		$this->db->order_by('date');
		$this->db->where('user',$uid);
		$query = $this->db->get('attachedservices');
		$data = $query->result_array();
		if(count($data)) return $data;
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
}