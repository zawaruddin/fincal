<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Setting extends CI_Controller {

	private $template;

	function __construct(){
		parent::__construct();
		$this->load->model('Account_model', 'acc');
		$this->load->model('AccountType_model', 'act');
		$this->load->model('Category_model', 'cat');
		
		$this->template = 'layouts/template';
	}
	
	function type(){
		$data = ['content' => 'setting/type',
				 'js' => true,
				 'css' => true,
				];
		$this->load->view($this->template, $data);
	}
	
	function type_load(){			
		$this->act->fields('id, name');
		
		// Operation for Datatables (without SSP Class)
		$sort_col  = $this->input->post('order[0][dir]', true);
		$filter    = $this->input->post('search[value]', true);
		
		$order_col = "name";
		
		if (! empty($filter)) {
			$this->act->where('name', 'like', $filter);
		}			
		
		$data = $this->act->limit($this->input->post('length', true), $this->input->post('start', true))
						   ->order_by($order_col, $sort_col)
						   ->get_all();
		
		
		$rownum = ($this->input->post('start', true))? $this->input->post('start', true) : 1;
		$res    = [];
		if ($data) {		
			foreach ($data as $r) {
				$res[] = ['rownum' => $rownum, 'id' => $r->id, 'name' => $r->name,
						  'action' => "<i class='icon-small icon-edit detail_modal ibutton' data-url='".site_url('edit_type/'.$r->id)."'></i>
						  			   <i class='icon-small icon-trash modal_popup ibutton' data-url='".site_url('conf_type/'.$r->id)."'></i>"];
				$rownum++;	
			}
		}
		
		$count  = $this->act->count_rows();
		$result = array('stat' => true,
						'draw' => $this->input->post('draw', true) + 1,
						'recordsTotal' => $count,
						'recordsFiltered' => $count,
						'data' => $res);
		echo json_encode($result);				
	}
	
	function type_add(){
		$this->load->view('setting/type_add');
	}
}