<?php if(!defined('BASEPATH')) exit('No direct script access allowed');

class Mddelivesworks extends CI_Model{

	var $id			= 0;
	var $remoteid	= 0;
	var $webmaster 	= 0;
	var $platform 	= 0;
	var $manager 	= 0;
	var $typework 	= 0;
	var $market 	= 0;
	var $mkprice 	= 0;
	var $ulrlink 	= '';
	var $countchars = 0;
	var $wprice 	= 0;
	var $mprice 	= 0;
	var $status 	= 0;
	var $date 		= '';
	var $datepaid 	= '';
	
	function __construct(){
		parent::__construct();
	}
	
	function insert_record($webmaster,$platform,$manager,$wprice,$mprice,$data){
			
		$this->remoteid 	= $data['id'];
		$this->webmaster 	= $webmaster;
		$this->platform 	= $platform;
		$this->manager 		= $manager;
		$this->typework 	= $data['typework'];
		$this->market 		= $data['market'];
		$this->mkprice 		= $data['mkprice'];
		$this->ulrlink 		= $data['ulrlink'];
		$this->countchars 	= $data['countchars'];
		$this->countchars 	= $data['countchars'];
		$this->wprice 		= $wprice;
		$this->mprice 		= $mprice;
		$this->date 		= date("Y-m-d");
		
		$this->db->insert('delivesworks',$this);
		return $this->db->insert_id();
	}
	
	function update_record($id,$webmaster,$platform,$data){
		
		$this->db->set('typework',$data['typework']);
		$this->db->set('market',$data['market']);
		$this->db->set('mkprice',$data['mkprice']);
		$this->db->set('ulrlink',$data['ulrlink']);
		$this->db->set('countchars',$data['countchars']);

		$this->db->where('id',$id);
		$this->db->where('webmaster',$webmaster);
		$this->db->where('platform',$platform);
		$this->db->update('delivesworks');
		return $this->db->affected_rows();
	}
	
	function read_records_webmaster($webmaster){
		
		$this->db->where('webmaster',$webmaster);
		$query = $this->db->get('delivesworks');
		$data = $query->result_array();
		if(count($data)) return $data;
		return NULL;
	}
	
	function read_records_platform($webmaster,$platform){
		
		$this->db->where('webmaster',$webmaster);
		$this->db->where('platform',$platform);
		$query = $this->db->get('delivesworks');
		$data = $query->result_array();
		if(count($data)) return $data;
		return NULL;
	}
	
	function read_records_manager($manager){
		
		$this->db->where('manager',$manager);
		$query = $this->db->get('delivesworks');
		$data = $query->result_array();
		if(count($data)) return $data;
		return NULL;
	}
	
	function read_record($id){
		
		$this->db->where('id',$id);
		$query = $this->db->get('delivesworks',1);
		$data = $query->result_array();
		if(isset($data[0])) return $data[0];
		return NULL;
	}
	
	function count_records_by_manager_status($manager,$status){
		
		$this->db->select('COUNT(*) AS cnt');
		$this->db->where('manager',$manager);
		$this->db->where('status',$status);
		$query = $this->db->get('delivesworks',1);
		$data = $query->result_array();
		if(count($data)) return $data[0]['cnt'];
		return 0;
	}
	
	function count_records_by_webmaster_status($webmaster,$status){
		
		$this->db->select('COUNT(*) AS cnt');
		$this->db->where('webmaster',$webmaster);
		$this->db->where('status',$status);
		$query = $this->db->get('delivesworks',1);
		$data = $query->result_array();
		if(count($data)) return $data[0]['cnt'];
		return 0;
	}
	
	function count_records_by_webmaster($webmaster){
		
		$this->db->select('COUNT(*) AS cnt');
		$this->db->where('webmaster',$webmaster);
		$query = $this->db->get('delivesworks',1);
		$data = $query->result_array();
		if(count($data)) return $data[0]['cnt'];
		return 0;
	}
	
	function read_field($id,$field){
			
		$this->db->where('id',$id);
		$query = $this->db->get('delivesworks',1);
		$data = $query->result_array();
		if(isset($data[0])) return $data[0][$field];
		return FALSE;
	}
	
	function delete_record($id){
	
		$this->db->where('id',$id);
		$this->db->delete('delivesworks');
		return $this->db->affected_rows();
	}

	function read_summ_by_webmaster($uid,$works){
		
		$in = '';
		for($i=0;$i<count($works);$i++):
			$in .=$works[$i];
			if(isset($works[$i+1])):
				$in .= ',';
			endif;
		endfor;
		$query = "SELECT SUM(wprice) AS summa FROM delivesworks WHERE id IN ($in) AND webmaster = $uid";
		$query = $this->db->query($query);
		$data = $query->result_array();
		if(isset($data[0]['summa'])) return $data[0]['summa'];
		return NULL;
	}
	
	function update_status($uid,$works){
		
		$in = '';
		for($i=0;$i<count($works);$i++):
			$in .=$works[$i];
			if(isset($works[$i+1])):
				$in .= ',';
			endif;
		endfor;
		$curdate = date("Y-m-d");
		$query = "UPDATE delivesworks SET status = 1,datepaid = '$curdate' WHERE id IN ($in) AND webmaster = $uid";
		$this->db->query($query);
		return $this->db->affected_rows();
	}

	function count_records_by_platform_status($platform,$status){
		
		$this->db->select('COUNT(*) AS cnt');
		$this->db->where('platform',$platform);
		$this->db->where('status',$status);
		$query = $this->db->get('delivesworks',1);
		$data = $query->result_array();
		if(count($data)) return $data[0]['cnt'];
		return 0;
	}
	
	function count_records_by_platform($platform){
		
		$this->db->select('COUNT(*) AS cnt');
		$this->db->where('platform',$platform);
		$query = $this->db->get('delivesworks',1);
		$data = $query->result_array();
		if(count($data)) return $data[0]['cnt'];
		return 0;
	}
	
	function change_managers($new_manager,$old_manager,$platform){
		
		$this->db->set('manager',$new_manager);
		$this->db->where('platform',$platform);
		$this->db->where('manager',$old_manager);
		$this->db->update('delivesworks');
		return $this->db->affected_rows();
	}

	function exist_work($url){
		
		$this->db->where('ulrlink',$url);
		$query = $this->db->get('delivesworks',1);
		$data = $query->result_array();
		if(count($data)) return TRUE;
		return FALSE;
	}

	function calc_summ($field,$data,$status){
		
		$query = "SELECT SUM($field) AS sum,COUNT($field) AS cnt FROM delivesworks WHERE date >= '$data' AND status = $status";
		$query = $this->db->query($query);
		$data = $query->result_array();
		if(isset($data[0])) return $data[0];
		return NULL;
	}
}