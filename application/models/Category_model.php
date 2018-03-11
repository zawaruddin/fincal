<?php  if(!defined('BASEPATH')) exit('No direct script access allowed');
class Category_model extends MY_Model
{
	public function __construct()
	{
        $this->table = 'Category';
        $this->primary_key = 'id';
        $this->has_many['transaction'] = array('Transaction_model','category_id','id');
		
		parent::__construct();
	}
}