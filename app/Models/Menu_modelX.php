<?php namespace App\Models;
use CodeIgniter\Model;

class Menu_model extends Model 
{
    var $table = 'userrights';
  

    // public function getRights($uid)
    // {
    //     $builder = $this->db->table($this->table);
    //     $query = $builder->select('menuoption')
    //                          ->where('userrowid', $uid)
    //                          ->orderBy('menuoption')
    //                          ->get();
    //     return($query->getResultArray());
    // }

    // public function getRights1()
    // {
    //     $this->db->select('menuoption');
    //     $this->db->where('userrowid', $this->session->userRowId);
    //     $this->db->order_by('');
    //     $query = $this->db->get('userrights');
    //     return($query->result_array());
    // }
}