<?php namespace App\Models;
use CodeIgniter\Model;

class Addressbook_model extends Model 
{
	var $table = 'addressbook';

    public function getDataLimit()
	{
		$builder = $this->db->table($this->table);
		$query = $builder->select('*')
			                 ->where('deleted', 'N')
			                //  ->limit(5)
			                 ->orderBy('rowId desc')
			                 ->get();
		return($query->getResultArray());
	}

	public function checkDuplicate($request)
    {
		$builder = $this->db->table($this->table);
		$query = $builder->select('name')
			                 ->where('mobile', $request->getPost('mobile'))
			                 ->limit(1)
			                 ->get();
		$row = $query->getRow();
		if (isset($row))
		{
			return 1;
		}
    }

	public function insertNow($request)
    {
		$this->db->transStart();
		$builder = $this->db->table($this->table);
		$builder->selectMax('rowId');
		$query = $builder->get();
		$row = $query->getRow();
		$current_row = $row->rowId + 1;
    	$data = array(
	        'rowId' => $current_row
	        , 'name' => $request->getPost('name')
	        , 'hNo' => $request->getPost('hNo')
	        , 'locality' => $request->getPost('locality')
	        , 'occupation' => $request->getPost('occupation')
	        , 'telephone' => $request->getPost('telephone')
	        , 'mobile' => $request->getPost('mobile')
	        , 'remarks' => $request->getPost('remarks')
	        , 'createdBy' => session('userRowId')
	        , 'createdStamp' => date('Y-m-d H:i')
		);
		$this->db->table($this->table)->insert($data);
		$this->db->transComplete();
	}

	public function checkDuplicateOnUpdate($request)
    {
		$builder = $this->db->table($this->table);
		$query = $builder->select('name')
			                 ->where('mobile', $request->getPost('mobile'))
							 ->where('rowId !=', $request->getPost('globalrowid'))
							 ->limit(1)
			                 ->get();
		$row = $query->getRow();
		if (isset($row))
		{
			return 1;
		}
    }

	public function updateNow($request)
    {
		$this->db->transStart();
		$data = array(
	        'name' => $request->getPost('name')
	        , 'hNo' => $request->getPost('hNo')
	        , 'locality' => $request->getPost('locality')
	        , 'occupation' => $request->getPost('occupation')
	        , 'telephone' => $request->getPost('telephone')
	        , 'mobile' => $request->getPost('mobile')
	        , 'remarks' => $request->getPost('remarks')
		);
		$where = array('rowId' => $request->getPost('globalrowid'));
		$this->db->table($this->table)->update($data, $where);			
		$this->db->transComplete();	
	}

	public function deleteNow($request)
	{
		$this->db->transStart();
		$data = array(
		    'deleted' => 'Y'
		    , 'deletedBy' => session('userRowId')
        	, 'deletedStamp' => date('Y-m-d H:i')

		);
		$where = array('rowId' => $request->getPost('rowId'));
		$this->db->table($this->table)->update($data, $where);			
		$this->db->transComplete();	
	}

}