<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Fincal extends CI_Controller {
	private $template;

	function __construct(){
		parent::__construct();
		$this->load->model('Calendar_model', 'cal');
		$this->load->model('Transaction_model', 'tran');
		$this->load->model('Account_model', 'acc');
		$this->load->model('Category_model', 'cat');
		$this->load->library('calendar', $this->_setting());
		
		$this->template = 'layouts/template';
	}

	function index($year = NULL){
		$year = (ctype_digit($year) && strlen($year) == 4)? $year : date('Y');
		
		$default = [1 => 0, 2 => 0, 3 => 0, 4 => 0, 5 => 0, 6 => 0, 7 => 0, 8 => 0, 9 => 0, 10 => 0, 11 => 0, 12 => 0];
		
		$income  = [];
		$expend  = [];
		
		$acc 	 = $this->acc->get_all();
		if($acc){
			foreach($acc as $a){			
				$res = $this->tran->getIncomePerMonthByAccount($year, $a->id);
				
				$income[$a->name] = $default;	
				if (! empty($res)) {
					foreach($res as $r){
						$income[$a->name][$r->mon] = $r->income;
					}	
				}
				
				$res = $this->tran->getExpendPerMonthByAccount($year, $a->id);
				
				$expend[$a->name] = $default;	
				if (! empty($res)) {
					foreach($res as $r){
						$expend[$a->name][$r->mon] = $r->expend;
					}	
				}			
			}
		}

		$data = ['content' => 'dashboard/dashboard',
				 'js' => true,
				 'css' => true,
				 'm_dashboard' => 'active',
				 'year' => $year, 
				 'income' => $income,
				 'expend' => $expend,
				 'list_year' => $this->cal->getListYear(),
				 'saldo' => $this->tran->getTotalBalance($year)];
		$this->load->view($this->template, $data);
	}
	
	function calendar($year = null, $month = null){		
		$year  = (ctype_digit($year) && strlen($year) == 4)? $year : date('Y');
		$month = (ctype_digit($month) &&  $month > 0 && $month < 13)? $month : date('m');
		
		$events = $this->cal->fields('day(calendar.date) as day, total')
							->where('YEAR(date)', '=', $year)
							->where('MONTH(date)', '=', $month)
							->get_all();
		
		$result = [];
		if ($events) {
			foreach ($events as $event) {
				$result[$event->day] = ($event->total >= 0)? '<font class="blue">'.number_format($event->total, 0, ',', '.').'</font>' : 
															 '<font class="red">'.number_format($event->total, 0,',', '.').'</font>';
			}	
		}
		
		$data = ['content' => 'calendar/calendar',
				 'js' => true,
				 'css' => true,
				 'm_calendar' => 'active',
				 'calendar' => $this->calendar->generate($year, $month, $result),
				 'year'  => $year, 
				 'mon'   => $month,
				];
		$this->load->view($this->template, $data);
	}
	
	function replaceInsufficientChar($str){
		return preg_replace("/[^A-Za-z0-9 +]/", "", $str);
	}
	
	function roundNumericValue($val){
		return round(str_replace(['.',','], ['','.'], $val), 0);
	}
	
	// do adding event for selected date
	function save(){
		$this->form_validation->set_rules('id', 'ID', 'trim|required|is_natural_no_zero');
		$this->form_validation->set_rules('ids', 'IDS', 'trim|is_natural_no_zero');
		$this->form_validation->set_rules('event', 'Event', 'trim|strip_tags|required|max_length[100]|callback_replaceInsufficientChar');
		$this->form_validation->set_rules('account', 'Account', 'trim|required|is_natural_no_zero');
		$this->form_validation->set_rules('category', 'Category', 'trim|required|is_natural_no_zero');
		$this->form_validation->set_rules('time', 'Time', 'trim|required');
		$this->form_validation->set_rules('amount', 'Amount', 'trim|required|callback_roundNumericValue|numeric');
		
		if ($this->form_validation->run() == false) {
			echo json_encode(array('stat' => false, 'title' => 'Error', 'msg' => validation_errors(' ', ' ')));
		} else {
			$id  = $this->input->post('id', true);
			$ids = $this->input->post('ids', true);
			
			if ($ids) {
				$update = array('account_id' => $this->input->post('account', true),
								'category_id' => $this->input->post('category', true),
								'transaction_time' => $this->input->post('time', true),
								'name' => $this->input->post('event', true),
								'amount' => $this->input->post('amount', true),);
				$this->tran->update($update, $ids);
			} else {
				$insert = array('calendar_id' => $id,
								'account_id' => $this->input->post('account', true),
								'category_id' => $this->input->post('category', true),
								'transaction_time' => $this->input->post('time', true),
								'name' => $this->input->post('event', true),
								'amount' => $this->input->post('amount', true),);
				$this->tran->insert($insert);				
			}
			
			$this->cal->syncTotalAmount($id);
			$total = $this->cal->fields('total')->get($id);
			$total = (($total->total < 0)? '<font class="red">' : '<font class="blue">').number_format($total->total, 0, ',', '.').'</font>'; 	
			echo json_encode(array('stat' => true,'title' => 'Success', 'msg' => 'Insert Transaction success', 'total' => $total));
		}
	}
	
	// same as index() function
	function detail($year = null, $month = null, $day = null){
		$year  = (ctype_digit($year) && strlen($year) == 4)? $year : date('Y');
		$month = (ctype_digit($month) &&  $month > 0 && $month < 13)? $month : date('m');
		$day   = (ctype_digit($day) &&  $day > 0 && $day <= 31)?  $day : date('d');
		
		if (checkdate($month , $day , $year)) {
			$res   = $this->cal->fields('id, total')->where('date', '=', "{$year}-{$month}-{$day}")->get(); 
			
			if (! $res) { // not exists calendar : create once
				$this->cal->insert(array('date' => "{$year}-{$month}-{$day}"));
				$res   = $this->cal->fields('id, total')->where('date', '=', "{$year}-{$month}-{$day}")->get(); 
			}
			
			
			$cat   = $this->cat->order_by('operation', 'DESC')->order_by('id', 'ASC')->as_dropdown('name')->get_all();
			$acc   = $this->acc->as_dropdown('name')->get_all();
			
			$data  = ['y' => $year, 
					  'm' => DateTime::createFromFormat('!m', $month)->format('F'), 
					  'd' => $day, 
					  'id' => $res->id,
					  'category' => $cat,
					  'account' => $acc,
					  'total' => $res->total];
			
			$this->load->view('calendar/_modal_detail', $data);	
		} else {
			$this->load->view('_modal_error',['title' => 'Error format date', 'msg' => 'Your format date is not valid']);	
		}
		
	}
	
	function getDetail(){
		$this->form_validation->set_rules('id', 'ID', 'trim|required|is_natural_no_zero');
		
		if ($this->form_validation->run() == false){
			$result = array('stat' => false, 'draw' => 0, 'recordsTotal' => 0, 'recordsFiltered' => 0, 'data' => array(), 'msg' => validation_errors('', ''));
		}else{
			$id   = $this->input->post('id', true);
			
			$this->tran->fields('id, transaction_time, name, amount')
						->where('calendar_id', '=', $id)
						->with_account('fields:name')
						->with_category('fields:name');
			
			// Operation for Datatables (without SSP Class)
			$order_col = $this->input->post('order[0][column]', true);
			$sort_col  = $this->input->post('order[0][dir]', true);
			$filter    = $this->input->post('search[value]', true);
			
			switch ($order_col) {
				case '2' : $order_col = "account_id"; break;
				case '3' : $order_col = "transaction_time"; break;
				case '4' : $order_col = "category_id"; break;
				case '5' : $order_col = "amount"; break;
			}
			
			if (! empty($filter)) {
				$this->tran->where('transaction.name', 'like', $filter);
			}			
			
			$data = $this->tran->limit($this->input->post('length', true), $this->input->post('start', true))
							   ->order_by($order_col, $sort_col)
							   ->get_all();
			
			
			$rownum = ($this->input->post('start', true))? $this->input->post('start', true) : 1;
			$res    = [];
			$total  = 0;
			if ($data) {		
				foreach ($data as $r) {
					$res[] = ['rownum' => $rownum, 'id' => $r->id, 'name' => $r->name,
							  'time' => $r->transaction_time, 'category' => $r->category->name,
							  'account' => $r->account->name, 'amount' => number_format($r->amount, 0, ',', '.'),
							  'action' => "<i class='icon-small icon-edit edit ibutton' data-id='{$r->id}' data-name='{$r->name}' data-time='{$r->transaction_time}'
							  				data-category='{$r->category_id}' data-account='{$r->account_id}' data-amount='".round($r->amount)."'></i> 
							  			   <i class='icon-small icon-trash modal_popup ibutton' data-url='".site_url('confirm/'.$r->id)."'></i>"];
					$total += $r->amount;
					$rownum++;	
				}
			}
			
			$count  = $this->tran->where('calendar_id', '=', $id)->count_rows();
			$result = array('stat' => true,
							'draw' => $this->input->post('draw', true) + 1,
							'recordsTotal' => $count,
							'recordsFiltered' => $count,
							'data' => $res,
							'total' => number_format($total, 0, ',', '.'));
		}
		echo json_encode($result);				
	}
	
	
	function confirm($id){
		$cat   = $this->cat->order_by('operation', 'ASC')->order_by('id', 'ASC')->as_dropdown('name')->get_all();
		$acc   = $this->acc->order_by('name', 'asc')->as_dropdown('name')->get_all();
		
		$data  = ['id' => $id,
				  'data' => $this->tran->get($id)];
		
		$this->load->view('calendar/_modal_confirm', $data);
	}
	
	function delete(){
		$this->form_validation->set_rules('id', 'ID', 'trim|required|is_natural_no_zero');
		$this->form_validation->set_rules('ids', 'IDS', 'trim|required|is_natural_no_zero');
		
		if ($this->form_validation->run() == false){
			echo json_encode(array('stat' => false, 'msg' => 'No ID or IDS value for this data.'));
		}else{
			$id  = $this->input->post('id', true);
			$ids = $this->input->post('ids', true);
			
			$this->tran->delete($ids);
			
			$this->cal->syncTotalAmount($id);
			$total = $this->cal->fields('total')->get($id);
			$total = (($total->total < 0)? '<font class="red">' : '<font class="blue">').number_format($total->total, 0, ',', '.').'</font>'; 
			
			echo json_encode(array('stat' => true, 'msg' => 'Data transaction deleted', 'total' => $total));
		}
	}
	
	// setting for calendar
	function _setting(){
		$uri = $this->uri->total_segments();
		$uri = ($uri > 1)? $this->uri->segment($uri - 1).'/'.$this->uri->segment($uri) : date('Y/m');
		
		$url = site_url("detail/{$uri}");
		return array(
			'start_day' 		=> 'monday',
			'show_next_prev' 	=> true,
			'next_prev_url' 	=> site_url('calendar'),
			'month_type'   		=> 'long',
            'day_type'     		=> 'short',
			'template' 			=> '{table_open}<table class="date">{/table_open}
								   {heading_row_start}&nbsp;{/heading_row_start}
								   {heading_previous_cell}<caption><a href="{previous_url}" class="prev_date" title="Previous Month">&laquo;&nbsp;Prev&nbsp;&nbsp;</a>{/heading_previous_cell}
								   {heading_title_cell}{heading}{/heading_title_cell}
								   {heading_next_cell}<a href="{next_url}" class="next_date"  title="Next Month">&nbsp;&nbsp;Next&nbsp;&raquo;</a></caption>{/heading_next_cell}
								   {heading_row_end}<col class="weekday" span="5"><col class="weekend_sat"><col class="weekend_sun">{/heading_row_end}
								   {week_row_start}<thead><tr>{/week_row_start}
								   {week_day_cell}<th>{week_day}</th>{/week_day_cell}
								   {week_row_end}</tr></thead><tbody>{/week_row_end}
								   {cal_row_start}<tr>{/cal_row_start}
								   {cal_cell_start}<td>{/cal_cell_start}
								   {cal_cell_content}<div class="date_event detail_modal" data-url="'.$url.'/{day}"><span class="date">{day}</span><span class="event d{day}">{content}</span></div>{/cal_cell_content}
								   {cal_cell_content_today}<div class="active_date_event detail_modal" data-url="'.$url.'/{day}"><span class="date">{day}</span><span class="event d{day}">{content}</span></div>{/cal_cell_content_today}
								   {cal_cell_no_content}<div class="no_event detail_modal" data-url="'.$url.'/{day}"><span class="date">{day}</span><span class="event d{day}">&nbsp;</span></div>{/cal_cell_no_content}
								   {cal_cell_no_content_today}<div class="active_no_event detail_modal" data-url="'.$url.'/{day}"><span class="date">{day}</span><span class="event d{day}">&nbsp;</span></div>{/cal_cell_no_content_today}
								   {cal_cell_blank}&nbsp;{/cal_cell_blank}
								   {cal_cell_end}</td>{/cal_cell_end}
								   {cal_row_end}</tr>{/cal_row_end}
								   {table_close}</tbody></table>{/table_close}');
	}
	
	
	function income($year = null, $month = null){		
		$year  = (ctype_digit($year) && strlen($year) == 4)? $year : date('Y');
		$month = (ctype_digit($month) &&  $month > 0 && $month < 13)? $month : date('m');
		
		$events = $this->cal->where('YEAR(date)', '=', $year)
							->where('MONTH(date)', '=', $month)
							->with_transaction(['where' => 'category_id = 1', 'with' => ['relation' => 'account']])// [['relation' => 'account'], ['relation' => 'category']]])
							->order_by('date', 'asc')
							->get_all();
		
		$data = ['content' => 'income/income',
				 'js' => true,
				 'css' => true,
				 'm_income' => 'active',
				 'year'  => $year, 
				 'month'   => $month,
				 'mon' => DateTime::createFromFormat('!m', $month)->format('F'),
				 'income' => $events,
				];
		$this->load->view($this->template, $data);
	}
	
	function expend($year = null, $month = null){		
		$year  = (ctype_digit($year) && strlen($year) == 4)? $year : date('Y');
		$month = (ctype_digit($month) &&  $month > 0 && $month < 13)? $month : date('m');
		
		$events = $this->cal->where('YEAR(date)', '=', $year)
							->where('MONTH(date)', '=', $month)
							->with_transaction(['where' => 'category_id = 2', 'with' => ['relation' => 'account']])// [['relation' => 'account'], ['relation' => 'category']]])
							->order_by('date', 'asc')
							->get_all();
		
		$data = ['content' => 'expend/expend',
				 'js' => true,
				 'css' => true,
				 'm_expend' => 'active',
				 'year'  => $year, 
				 'month'   => $month,
				 'mon' => DateTime::createFromFormat('!m', $month)->format('F'),
				 'expend' => $events,
				];
		$this->load->view($this->template, $data);
	}
}
