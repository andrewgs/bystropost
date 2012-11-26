<?php if(!defined('BASEPATH')) exit('No direct script access allowed');

class Mdfillup extends CI_Model{

	var $id		= 0;
	var $user 	= 0;
	var $summa 	= 0;
	var $date 	= '';
	var $result = '';
	var $status = 0; // 0 - деньги cписались 1 - деньги добавились
	var $system = 0; // 1 - не учитывать 0- учитывать
	
	function __construct(){
		parent::__construct();
	}
	
	function insert_record($user,$summa,$result,$system = 0,$status = 0){
			
		$this->user 	= $user;
		$this->summa	= $summa;
		$this->date		= date("Y-m-d H:i:s");
		$this->result	= $result;
		$this->system	= $system;
		$this->status	= $status;
		
		$this->db->insert('fillup',$this);
		return $this->db->insert_id();
	}
	
	function read_records(){
		
		$this->db->order_by('date','DESC');
		$query = $this->db->get('fillup');
		$data = $query->result_array();
		if(count($data)) return $data;
		return NULL;
	}
	
	function count_records_by_user($user){
		
		$this->db->select('COUNT(*) AS cnt');
		$this->db->where('user',$user);
		$query = $this->db->get('fillup');
		$data = $query->result_array();
		if(count($data)) return $data[0]['cnt'];
		return 0;
	}
	
	function read_records_by_user($uid,$count,$from){
		
		$this->db->where('user',$uid);
		$this->db->where('system',0);
		$this->db->limit($count,$from);
		$this->db->order_by('date','DESC');
		$query = $this->db->get('fillup');
		$data = $query->result_array();
		if(count($data)) return $data;
		return NULL;
	}
	
	function read_record($id){
		
		$this->db->where('id',$id);
		$query = $this->db->get('fillup',1);
		$data = $query->result_array();
		if(isset($data[0])) return $data[0];
		return NULL;
	}
	
	function read_field($id,$field){
			
		$this->db->where('id',$id);
		$query = $this->db->get('fillup',1);
		$data = $query->result_array();
		if(isset($data[0])) return $data[0][$field];
		return FALSE;
	}
	
	function delete_record($id){
	
		$this->db->where('id',$id);
		$this->db->delete('fillup');
		return $this->db->affected_rows();
	}

	function week_statistic($user){
	
		$date = date("Y-m-d",mktime(0,0,0,date("m"),date("d")-7,date("Y")));
		$query = "SELECT SUM(summa) AS summa, status FROM fillup WHERE user = $user AND system = 0 AND date >= '$date' GROUP BY status ORDER BY status DESC";
		$query = $this->db->query($query);
		$data = $query->result_array();
		if(count($data)) return $data;
		return NULL;
	}
	
	function bymonth_statistic($user){
		
		$date = date("Y-m-d",mktime(0,0,0,date("m")-1,date("d"),date("Y")));
		$query = "SELECT SUM(summa) AS summa,status FROM fillup WHERE user = $user AND system = 0 AND date >= '$date' GROUP BY status ORDER BY status DESC";
		$query = $this->db->query($query);
		$data = $query->result_array();
		if(count($data)) return $data;
		return NULL;
	}
	
	function month_statistic($user){
	
		$bdate = date("Y-m-d H:i:s",mktime(0,0,0,date("m")-1,'01',date("Y")));
		$edate = date("Y-m-d H:i:s",mktime(0,0,0,date("m"),'01',date("Y"))-1);
		$query = "SELECT SUM(summa) AS summa,status FROM fillup WHERE user = $user AND system = 0 AND date >= '$bdate' AND date <= '$edate' GROUP BY status ORDER BY status DESC";
		$query = $this->db->query($query);
		$data = $query->result_array();
		if(count($data)) return $data;
		return NULL;
	}
	
	function total_statistic($user){
		
		$query = "SELECT SUM(summa) AS summa,status FROM fillup WHERE user = $user AND system = 0 GROUP BY status ORDER BY status DESC";
		$query = $this->db->query($query);
		$data = $query->result_array();
		if(count($data)) return $data;
		return NULL;
	}
}