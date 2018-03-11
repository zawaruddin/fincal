<?php  if(!defined('BASEPATH')) exit('No direct script access allowed');
class Account_model extends MY_Model
{
	public function __construct()
	{
        $this->table = 'Account';
        $this->primary_key = 'id';
        $this->has_one['acountType'] = array('AccountType_model','account_type_id','id');
		
		parent::__construct();
	}
}