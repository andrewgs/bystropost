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
	var $ccontext 		= 0; //контекстная ссылка
	var $mcontext 		= 0;
	var $cnotice 		= 0; //Заметка
	var $mnotice 		= 0;
	var $creview 		= 0; //Обзор
	var $mreview 		= 0;
	var $cnews 			= 0; //Новость
	var $mnews 			= 0;
	var $clinkpic 		= 0; //Ссылка-картинка
	var $mlinkpic 		= 0;
	var $cpressrel 		= 0; //Пресс-релиз
	var $mpressrel 		= 0;
	var $clinkarh 		= 0; //Ссылка в архиве
	var $mlinkarh 		= 0;
	var $price 			= '';
	var $date 			= '';
	var $locked			= 0;
	var $status			= 1;
	
	function __construct(){
		parent::__construct();
	}
	
	function run_query($sql){
		
		$this->db->query($sql);
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
	
	function read_managers_platform_online($uid){
		
		$this->db->select('manager,url');
		$this->db->where('webmaster',$uid);
		$this->db->where('locked',0);
		$this->db->where('status',1);
		$query = $this->db->get('platforms');
		$data = $query->result_array();
		if(count($data)) return $data;
		return NULL;
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
	
	function update_price($id,$uid,$data){
		
		$this->db->set('ccontext',$data['ccontext']);
		$this->db->set('mcontext',$data['mcontext']);
		$this->db->set('cnotice',$data['cnotice']);
		$this->db->set('mnotice',$data['mnotice']);
		$this->db->set('creview',$data['creview']);
		$this->db->set('mreview',$data['mreview']);
		$this->db->set('cnews',$data['cnews']);
		$this->db->set('mnews',$data['mnews']);
		
		$this->db->set('clinkpic',$data['clinkpic']);
		$this->db->set('mlinkpic',$data['mlinkpic']);
		$this->db->set('cpressrel',$data['cpressrel']);
		$this->db->set('mpressrel',$data['mpressrel']);
		$this->db->set('clinkarh',$data['clinkarh']);
		$this->db->set('mlinkarh',$data['mlinkarh']);
		
		$this->db->where('id',$id);
		$this->db->where('webmaster',$uid);
		$this->db->update('platforms');
		return $this->db->affected_rows();
	}
	
	function update_lock($id,$uid,$locked){
		
		$this->db->set('locked',$locked);
		
		$this->db->where('id',$id);
		$this->db->where('webmaster',$uid);
		$this->db->update('platforms');
		return $this->db->affected_rows();
	}
	
	function update_manager($id,$uid,$manager){
		
		$this->db->set('manager',$manager);
		
		$this->db->where('id',$id);
		$this->db->where('webmaster',$uid);
		$this->db->update('platforms');
		return $this->db->affected_rows();
	}
	
	function update_status($id,$uid,$status){
		
		$this->db->set('status',$status);

		$this->db->where('id',$id);
		$this->db->where('webmaster',$uid);
		$this->db->update('platforms');
		return $this->db->affected_rows();
	}
	
	function close_platform_by_user_delete($uid){
		
		$this->db->set('locked',1);
		$this->db->set('webmaster',0);
		
		$this->db->where('webmaster',$uid);
		$this->db->update('platforms');
		return $this->db->affected_rows();
	}
	
	function platforms_status_offline($uid){
		
		$this->db->set('status',0);
		
		$this->db->where('webmaster',$uid);
		$this->db->update('platforms');
		return $this->db->affected_rows();
	}
	
	function platforms_status_online($uid){
		
		$this->db->set('status',1);
		
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
	
	function exist_platform($url){
		
		$this->db->where('url',$url);
		$query = $this->db->get('platforms');
		$data = $query->result_array();
		if(count($data)) return TRUE;
		return FALSE;
	}
	
	function read_records_by_webmaster($uid){
		
		$this->db->order_by('date');
		$this->db->where('webmaster',$uid);
		$query = $this->db->get('platforms');
		$data = $query->result_array();
		if(count($data)) return $data;
		return NULL;
	}
	
	function read_records_by_webmaster_nolock($uid){
		
		$this->db->order_by('url','ASC');
		$this->db->where('webmaster',$uid);
		$this->db->where('locked',0);
		$this->db->where('status',1);
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
	
	function read_records_by_manager($uid){
		
		$this->db->order_by('date');
		$this->db->where('manager',$uid);
		$query = $this->db->get('platforms');
		$data = $query->result_array();
		if(count($data)) return $data;
		return NULL;
	}
	
	function count_records_by_manager($uid){
		
		$this->db->select('COUNT(*) AS cnt');
		$this->db->where('manager',$uid);
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
		$this->db->where('locked',0);
		$this->db->where('status',1);
		$query = $this->db->get('platforms',1);
		$data = $query->result_array();
		if(count($data)) return TRUE;
		return FALSE;
	}

	function ownew_manager_platform($manager,$id){
		
		$this->db->where('id',$id);
		$this->db->where('manager',$manager);
		$this->db->where('status',1);
		$this->db->where('locked',0);
		$query = $this->db->get('platforms',1);
		$data = $query->result_array();
		if(count($data)) return TRUE;
		return FALSE;
	}

}