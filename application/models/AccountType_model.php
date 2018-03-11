<?php  if(!defined('BASEPATH')) exit('No direct script access allowed');
class AccountType_model extends MY_Model
{
	public function __construct()
	{
        $this->table = 'Account_type';
        $this->primary_key = 'id';
        $this->has_many['account'] = array('Account_model','account_type_id','id');
		
		parent::__construct();
	}
}