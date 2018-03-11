<?php  if(!defined('BASEPATH')) exit('No direct script access allowed');
class Currency_model extends MY_Model
{
	public function __construct()
	{
        $this->table = 'Currency';
        $this->primary_key = 'id';
        $this->has_many['account'] = array('Account_model','currency_id','id');
        $this->has_many['transaction'] = array('Transaction_model','currency_id','id');

		parent::__construct();
	}
}