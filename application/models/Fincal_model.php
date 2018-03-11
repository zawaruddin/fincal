<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/*
 * Fincal Model (Financial Management Calendar)
 *
 * Author       : Moch Zawaruddin Abdullah
 * Date Created : 10 April 2016
 * Version      : 1.0
 * Website		: zawaruddin.blogspot.com
 *
 */
 
class Fincal_model extends CI_Model {
	
	
	
	// for get all event date in one month
	function getDateEvent($year, $month){
		$year  = ($month < 10 && strlen($month) == 1) ? "$year-0$month" : "$year-$month";
		$query = $this->db->select('event_date, total_events')->from('events')->like('event_date', $year, 'after')->get();
		echo $this->db->last_query(); die;
		if($query->num_rows() > 0){
			$data = array();
			foreach($query->result_array() as $row){
				$date = explode('-',$row['event_date']);
				$data[(int) end($date)] = ($row['total_events'] >= 0)? '<font color="blue">'.number_format($row['total_events'], 0,',', '.').'</font>' : '<font color="red">'.number_format($row['total_events'], 0,',', '.').'</font>';
			}
			return $data;
		}else{
			return false;
		}
	}
	
	// get event detail for selected date
	function getEvent($year, $month, $day, $start = 0, $perpage = 10){
		$day   = (strlen($day) == 1)? "0$day" : $day;
		$year  = (strlen($month) == 1) ? "$year-0$month-$day" : "$year-$month-$day";
		
		
		$query = $this->db->select('@rownum := @rownum + 1 as rownum, idevent as id, jenis, akun, event, harga', false)->order_by('idevent')->get_where('event_detail', array('event_date' => $year));
		if($query->num_rows() > 0){
			$res = array();
			foreach($query->result_array() as $r){
				$res[] = array('no' => $r['rownum'], 'id' => $r['id'], 'jenis' => $r['jenis'], 'akun' => $r['akun'], 'event' => $r['event'], 'harga' => number_format($r['harga'], 0, ',', '.'));
			}
			return $res;
		}else{
			return null;
		}
	}
	
	// insert event
	function addEvent($year, $month, $day, $jenis, $akun, $event, $harga){	
		$day 	= (strlen($day) == 1)? "0$day" : $day;
		$month  = (strlen($month) == 1)? "0$month" : $month;
		
		$check = $this->db->get_where('events', array('event_date' => "$year-$month-$day"));
		if($check->num_rows() > 0){
			$this->db->insert('event_detail', array('event_date' => "$year-$month-$day", 'jenis' => $jenis, 'akun' => $akun, 'event' => $event, 'harga' => $harga));
			$income = $this->db->query("SELECT IFNULL(SUM(harga), 0) as harga FROM event_detail WHERE event_date = ? AND jenis = 1", array("$year-$month-$day"))->row();
			$expend = $this->db->query("SELECT IFNULL(SUM(harga), 0) as harga FROM event_detail WHERE event_date = ? AND jenis = 0", array("$year-$month-$day"))->row();
			$this->db->query("UPDATE events SET total_events = ? WHERE event_date = ?", array($income->harga - $expend->harga, "$year-$month-$day", ));
		}else{
			$this->db->insert('events', array('event_date' => "$year-$month-$day", 'total_events' => ($jenis)? $harga : -$harga ));
		    $this->db->insert('event_detail', array('event_date' => "$year-$month-$day", 'jenis' => $jenis, 'akun' => $akun, 'event' => $event, 'harga' => $harga));
		}
	}
	
	// delete event
	function deleteEvent($year, $month, $day, $id){
		$day 	= (strlen($day) == 1)? "0$day" : $day;
		$month  = (strlen($month) == 1)? "0$month" : $month;
	
		$this->db->delete("event_detail", array('idevent' => $id, 'event_date' => "$year-$month-$day"));
		$check = $this->db->query('SELECT count(*) as total FROM event_detail WHERE event_date = ?', array("$year-$month-$day"))->row();
		if($check->total > 0){
			$income = $this->db->query("SELECT IFNULL(SUM(harga), 0) as harga FROM event_detail WHERE event_date = ? AND jenis = 1", array("$year-$month-$day"))->row();
			$expend = $this->db->query("SELECT IFNULL(SUM(harga), 0) as harga FROM event_detail WHERE event_date = ? AND jenis = 0", array("$year-$month-$day"))->row();
			$this->db->query("UPDATE events SET total_events = ? WHERE event_date = ?", array($income->harga - $expend->harga, "$year-$month-$day", ));
		}else{
			$this->db->delete("events", array('event_date' => "$year-$month-$day"));
		}
		$check = $this->db->query('SELECT total_events as total FROM events WHERE event_date = ?', array("$year-$month-$day"));
		if($check->num_rows() > 0){
			$check = $check->row();
			return $check->total;
		} else {
			return null;
		}
	}
}