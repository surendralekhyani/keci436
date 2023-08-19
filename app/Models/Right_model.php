<?php namespace App\Models;
use CodeIgniter\Model;

class Right_model extends Model 
{
	var $table = 'userrights';

 	public function insertAjax($request)
    {
    	$this->db->transStart();
    	
		$where = array('userrowid' => $request->getPost('uid'));
		$this->db->table($this->table)->delete($where);
		$builder = $this->db->table($this->table);
		$builder->selectMax('rightrowid');
		$query = $builder->get();
		$row = $query->getRow();
		$current_row = $row->rightrowid + 1;
		$data = explode(",", $_POST['rights']);
		$cn = explode(",", $_POST['cn']);
		for ($i=0; $i <count($data) ; $i++)
       	{
			$data1 = array(
		        'rightrowid' => $current_row
		        , 'userrowid' =>$request->getPost('uid')
		        , 'menuoption' => $data[$i]
		        , 'controllername' => $cn[$i]
		        , 'createdBy' => session('userRowId')
		        , 'createdStamp' => date('Y-m-d H:i')
			);
			$this->db->table($this->table)->insert($data1);
			$current_row++;
		}
		$this->db->transComplete();	
	}

	public function getRights($uid)
    {
        $builder = $this->db->table($this->table);
        $query = $builder->select('menuoption')
                             ->where('userrowid', $uid)
                             ->orderBy('menuoption')
                             ->get();
        return($query->getResultArray());
    }
}