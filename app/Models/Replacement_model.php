<?php namespace App\Models;
use CodeIgniter\Model;

class Replacement_model extends Model 
{
	var $table = 'replacement';
    public function getCustomers()
    {
    	$builder = $this->db->table('customers');
		$query = $builder->select('customers.*')
			                 ->where('deleted', 'N')
			                 ->orderBy('customerName')
			                 ->get();
		return($query->getResultArray());
    }   

    public function getItems()
	{
		$builder = $this->db->table('items');
		$query = $builder->select('itemRowId, itemName')
			                 ->where('deleted', 'N')
			                 ->orderBy('itemName')
			                 ->get();
		return($query->getResultArray());
	}	


    public function getDataLimit()
	{
		$builder = $this->db->table('replacement');
		$query = $builder->select('replacement.*')
			                 ->where('deleted', 'N')
			                 ->where('recd', 'N')
			                 ->orderBy('replacementRowId')
			                 ->get();
		return($query->getResultArray());
	}

	public function getDataLimitOld()
	{
		$builder = $this->db->table('replacement');
		$query = $builder->select('replacement.*')
			                 ->where('deleted', 'N')
			                 ->where('recd', 'Y')
			                 ->orderBy('replacementRowId desc')
			                 ->get();
		return($query->getResultArray());
	}

    public function getDataAllOld()
	{
		$builder = $this->db->table('replacement');
		$query = $builder->select('replacement.*')
			                 ->where('deleted', 'N')
			                 ->where('recd', 'Y')
			                 ->orderBy('replacementRowId desc')
			                 ->get();
		return($query->getResultArray());
	}

    
	public function insertNow($request)
    {
        $this->db->transStart();
        $builder = $this->db->table('replacement');
		$builder->selectMax('replacementRowId');
		$query = $builder->get();
		$row = $query->getRow();
		$current_row = $row->replacementRowId + 1;
        $dt = date('Y-m-d', strtotime($request->getPost('dt')));
    	$data = array(
	        'replacementRowId' => $current_row
	        , 'dt' => $dt
	        , 'itemRowId' => $request->getPost('itemRowId')
	        , 'itemName' => $request->getPost('itemName')
	        , 'partyRowId' => $request->getPost('partyRowId')
	        , 'partyName' => $request->getPost('partyName')
	        , 'qty' => $request->getPost('qty')
	        , 'remarks' => $request->getPost('remarks')
	        , 'createdBy' => session('userRowId')
	        , 'createdStamp' => date('Y-m-d H:i')
		);
		$this->db->table($this->table)->insert($data);
		$this->db->transComplete();	
	}

	public function updateNow($request)
    {
        $this->db->transStart();

        $dt = date('Y-m-d', strtotime($request->getPost('dt')));

		$data = array(
	        'dt' => $dt
	        , 'itemRowId' => $request->getPost('itemRowId')
	        , 'itemName' => $request->getPost('itemName')
	        , 'partyRowId' => $request->getPost('partyRowId')
	        , 'partyName' => $request->getPost('partyName')
	        , 'qty' => $request->getPost('qty')
	        , 'remarks' => $request->getPost('remarks')
		);
		$where = array('replacementRowId' => $request->getPost('globalrowid'));
		$this->db->table($this->table)->update($data, $where);			
		$this->db->transComplete();	
	}

	public function deleteNow($request)
	{
		$this->db->transStart();
		$where = array('replacementRowId' => $request->getPost('rowId'));
		$this->db->table($this->table)->delete($where); 
		$this->db->transComplete();	
	}

	public function setSent($request)
    {
        $this->db->transStart();
        $TableData = $request->getPost('TableData');
        $TableData = stripcslashes($TableData);
        $TableData = json_decode($TableData,TRUE);
        $myTableRows = count($TableData);
        for ($i=0; $i < $myTableRows; $i++) 
        {
        	$data = array(
		        'sent' => 'Y'
		        , 'sentDt' => date('Y-m-d')
			);
			$where = array('replacementRowId' => $TableData[$i]['rowId']);
			$this->db->table($this->table)->update($data, $where);			
        }
		$this->db->transComplete();	
	}

	public function setRecd($request)
    {
        $this->db->transStart();
        $TableData = $request->getPost('TableData');
        $TableData = stripcslashes($TableData);
        $TableData = json_decode($TableData,TRUE);
        $myTableRows = count($TableData);
        for ($i=0; $i < $myTableRows; $i++) 
        {
        	$where = array('replacementRowId' => $TableData[$i]['rowId']);
			$this->db->table($this->table)->delete($where); 
        }
		$this->db->transComplete();		
	}
}