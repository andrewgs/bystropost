<?php if(!defined('BASEPATH')) exit('No direct script access allowed');

class Mdplatforms extends CI_Model{

	var $id				= 0;
	var $webmaster		= 0;
	var $manager		= 0;
	var $url 			= '';
	var $subject 		= 0;
	var $cms 			= '';
	var $adminpanel 	= '';
	var $aplogin 		= '';
	var $appassword 	= '';
	var $amount 		= 0;
	var $reviews 		= 0;
	var $thematically 	= 0;
	var $illegal 		= 0;
	var $criteria 		= '';
	var $requests 		= '';
	var $tic 			= 0;
	var $pr 			= 0;
	var $date 			= '';
	
	function __construct(){
		parent::__construct();
	}
	
	function insert_record($uid,$data){
			
		$this->url 			= $data['url'];
		$this->webmaster 	= $uid;
		$this->subject 		= $data['subject'];
		$this->cms 			= $data['cms'];
		$this->adminpanel 	= $data['adminpanel'];
		$this->aplogin 		= $data['aplogin'];
		$this->appassword 	= $data['appassword'];
		$this->amount 		= $data['amount'];
		$this->reviews 		= $data['reviews'];
		$this->thematically = $data['thematically'];
		$this->illegal 		= $data['illegal'];
		$this->criteria 	= $data['criteria'];
		$this->requests 	= $data['requests'];
		$this->date 		= date("Y-m-d");
		
		$this->db->insert('platforms',$this);
		return $this->db->insert_id();
	}
	
	function update_record($id,$uid,$data){
		
		$this->db->set('url',$data['url']);
		$this->db->set('subject',$data['subject']);
		$this->db->set('cms',$data['cms']);
		$this->db->set('adminpanel',$data['adminpanel']);
		$this->db->set('aplogin',$data['aplogin']);
		$this->db->set('appassword',$data['appassword']);
		$this->db->set('amount',$data['amount']);
		$this->db->set('reviews',$data['reviews']);
		$this->db->set('thematically',$data['thematically']);
		$this->db->set('illegal',$data['illegal']);
		$this->db->set('criteria',$data['criteria']);
		$this->db->set('requests',$data['requests']);
		
		$this->db->where('id',$id);
		$this->db->where('webmaster',$uid);
		$this->db->update('platforms');
		return $this->db->affected_rows();
	}
	
	function read_records(){
		
		$this->db->order_by('title');
		$query = $this->db->get('platforms');
		$data = $query->result_array();
		if(count($data)) return $data;
		return NULL;
	}
	
	function read_records_by_webmaster($uid){
		
		$this->db->order_by('date');
		$this->db->where('webmaster',$uid);
		$query = $this->db->get('platforms');
		$data = $query->result_array();
		if(count($data)) return $data;
		return NULL;
	}
	
	function count_records_by_webmaster($uid){
		
		$this->db->select('COUNT(*) AS cnt');
		$this->db->where('webmaster',$uid);
		$query = $this->db->get('platforms');
		$data = $query->result_array();
		if(isset($data[0]['cnt'])) return $data[0]['cnt'];
		return 0;
	}
	
	function read_record($id){
		
		$this->db->where('id',$id);
		$query = $this->db->get('platforms',1);
		$data = $query->result_array();
		if(isset($data[0])) return $data[0];
		return NULL;
	}
	
	function read_field($id,$field){
			
		$this->db->where('id',$id);
		$query = $this->db->get('platforms',1);
		$data = $query->result_array();
		if(isset($data[0])) return $data[0][$field];
		return FALSE;
	}
	
	function delete_record($id){
	
		$this->db->where('id',$id);
		$this->db->delete('platforms');
		return $this->db->affected_rows();
	}

	function ownew_platform($webmaster,$id){
		
		$this->db->where('id',$id);
		$this->db->where('webmaster',$webmaster);
		$query = $this->db->get('platforms',1);
		$data = $query->result_array();
		if(count($data)) return TRUE;
		return FALSE;
	}
}