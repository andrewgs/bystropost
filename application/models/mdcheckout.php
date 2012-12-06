<?php if(!defined('BASEPATH')) exit('No direct script access allowed');

class Mdcheckout extends CI_Model{

	var $id			= 0;
	var $webmaster 	= 0;
	var $invoice 	= '';
	var $wmid 		= '';
	var $summa 		= '';
	var $date 		= '';
	var $paid 		= 0;
	
	function __construct(){
		parent::__construct();
	}
	
	function insert_record($webmaster,$invoice,$summa,$wmid){
			
		$this->webmaster 	= $webmaster;
		$this->invoice		= $invoice;
		$this->summa		= $summa;
		$this->wmid			= $wmid;
		$this->date			= date("Y-m-d H:i:s");
			
		$this->db->insert('checkout',$this);
		return $this->db->insert_id();
	}
	
	function read_records(){
			
		$this->db->order_by('date','DESC');
		$this->db->order_by('webmaster');
		$this->db->where('paid',0);
		$query = $this->db->get('checkout');
		$data = $query->result_array();
		if(count($data)) return $data;
		return NULL;
	}
	
	function read_field($id,$field){
			
		$this->db->where('id',$id);
		$query = $this->db->get('checkout',1);
		$data = $query->result_array();
		if(isset($data[0])) return $data[0][$field];
		return FALSE;
	}
	
	function update_field($id,$field,$value){
			
		$this->db->set($field,$value);
		$this->db->where('id',$id);
		$this->db->update('checkout');
		return $this->db->affected_rows();
	}
	
	function max_invoce(){
			
		$this->db->select_max('invoice','max');
		$query = $this->db->get('checkout',1);
		$data = $query->result_array();
		if(!empty($data[0]['max'])) return $data[0]['max'];
		return 0;
	}
}