<?php  if(!defined('BASEPATH')) exit('No direct script access allowed');
class Calendar_model extends MY_Model
{
	public function __construct()
	{
        $this->table = 'Calendar';
        $this->primary_key = 'id';
        $this->has_many['transaction'] = array('Transaction_model','calendar_id','id');
		
		parent::__construct();
	}
	
	function syncTotalAmount($id){
		$this->db->query("UPDATE {$this->table} cal
						  SET 	 cal.total = (
						  			(
							  		  	SELECT 	IFNULL(SUM(t1.amount), 0) 
										FROM 	transaction t1 
										JOIN	category c1 ON t1.category_id = c1.id
										WHERE 	t1.calendar_id = cal.id AND c1.operation = '+'	
									) - 
									(
										SELECT 	IFNULL(SUM(t2.amount), 0)
										FROM 	transaction t2 
										JOIN	category c2 ON t2.category_id = c2.id
										WHERE 	t2.calendar_id = cal.id AND c2.operation = '-'
									)
						  		  )
						  WHERE   cal.id = ?", array($id));
	}
	
	function getListYear(){
		return $this->db->select('DISTINCT YEAR(date) as year', false)->get($this->table)->result();
	}
}