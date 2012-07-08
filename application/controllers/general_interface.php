<?php if(!defined('BASEPATH')) exit('No direct script access allowed');

class General_interface extends CI_Controller{
	
	function __construct(){
		
		parent::__construct();
		$this->load->model('mdmarkets');

}
	/******************************************************** functions ******************************************************/	
	
	function viewimage(){
		
		$section = $this->uri->segment(1);
		$id = $this->uri->segment(3);
		switch ($section):
			case 'markets'	:	$image = $this->mdmarkets->get_image($id); break;
			default			: 	show_404();break;
		endswitch;
		header('Content-type: image/gif');
		echo $image;
	}
}