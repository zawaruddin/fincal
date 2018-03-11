<?php  if(!defined('BASEPATH')) exit('No direct script access allowed');
class Transaction_model extends MY_Model
{
	public function __construct()
	{
        $this->table = 'Transaction';
        $this->primary_key = 'id';
        $this->has_one['account'] = array('Account_model','id','account_id');
		$this->has_one['category'] = array('Category_model','id','category_id');
		$this->has_one['currency'] = array('Currency_model','id','currency_id');
		
		parent::__construct();
	}
	
	
	function getIncomePerMonth($year){
		return $this->db->select('month(cal.date) as month, SUM(tra.amount) as income', false)
						->from('calendar cal')
						->join($this->table.' tra', 'cal.id = tra.calendar_id')
						->join('category cat', "cat.id = tra.category_id AND cat.operation = '+'")
						->where('year(cal.date)', $year)
						->group_by('month')->get()->result();
	}
	
	function getExpendPerMonth($year){
		return $this->db->select('month(cal.date) as month, SUM(tra.amount) as expend', false)
						->from('calendar cal')
						->join($this->table.' tra', 'cal.id = tra.calendar_id')
						->join('category cat', "cat.id = tra.category_id AND cat.operation = '-'")
						->where('year(cal.date)', $year)
						->group_by('month')->get()->result();
	}
	
	function getIncomePerMonthByAccount($year, $account){
		return $this->db->select('month(cal.date) as mon, SUM(tra.amount) as income', false)
						->from('calendar cal')
						->join($this->table.' tra', 'cal.id = tra.calendar_id')
						->join('category cat', "cat.id = tra.category_id AND cat.operation = '+'")
						->where('year(cal.date)', $year)
						->where('tra.account_id', $account)
						->group_by('mon')->get()->result();
	}
	
	function getExpendPerMonthByAccount($year, $account){
		return $this->db->select('month(cal.date) as mon, SUM(tra.amount) as expend', false)
						->from('calendar cal')
						->join($this->table.' tra', 'cal.id = tra.calendar_id')
						->join('category cat', "cat.id = tra.category_id AND cat.operation = '-'")
						->where('year(cal.date)', $year)
						->where('tra.account_id', $account)
						->group_by('mon')->get()->result();
	}
	
	function getTotalBalance($year){
		$income = $this->db->select('SUM(tra.amount) as total', false)
						->from('calendar cal')
						->join($this->table.' tra', 'cal.id = tra.calendar_id')
						->join('category cat', "cat.id = tra.category_id AND cat.operation = '+'")
						->where('year(cal.date)', $year)->get()->row();
		
		$expend = $this->db->select('SUM(tra.amount) as total', false)
						->from('calendar cal')
						->join($this->table.' tra', 'cal.id = tra.calendar_id')
						->join('category cat', "cat.id = tra.category_id AND cat.operation = '-'")
						->where('year(cal.date)', $year)->get()->row();
		
		return ($income->total - $expend->total);
	}
}