<?php namespace App\Models;
use CodeIgniter\Model;

class Family_model extends Model 
{
	var $table = 'family';

    public function getFamilyList()
    {
        $builder = $this->db->table('family');
        $query = $builder->select('family.*')
                             ->orderBy('name')
                             ->get();
        return($query->getResultArray());
    }   
    public function getDataLimit()
	{
        $q1 = "SELECT familyRowId, name, parentRowId, contactNo, address, remarks, 'None' as parentName, siblingOrder
        FROM family 
        WHERE parentRowId=-2";
        $query1 = $this->db->query($q1);
        $r1 = $query1->getResultArray();

        $q2 = "SELECT F1.familyRowId, F1.name, F1.parentRowId, F1.contactNo, F1.address, F1.remarks, F2.name as parentName, F1.siblingOrder
        FROM family F1, family F2
        WHERE F2.familyRowId=F1.parentRowId 
        ORDER BY F1.familyRowId";
        $query2 = $this->db->query($q2);
        $r2 = $query2->getResultArray();
        $r3 = array_merge($r1,$r2);
        return ($r3);
        // return ($query2->getResultArray());
	}


	public function checkDuplicate($request)
    {
		$builder = $this->db->table($this->table);
		$query = $builder->select('name')
			                 ->where('name', $request->getPost('name'))
			                 ->where('parentRowId', $request->getPost('parentRowId'))
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
		$builder->selectMax('familyRowId');
		$query = $builder->get();
		$row = $query->getRow();
		$current_row = $row->familyRowId + 1;

		///siblingRowId
		// $builder = $this->db->table($this->table);
		$query = $builder->selectMax('siblingOrder')
				->where('parentRowId', $request->getPost('parentRowId'))
				->get();
		$row = $query->getRowArray();
		$siblingOrder = $row['siblingOrder']+1;

    	$data = array(
	        'familyRowId' => $current_row
	        , 'name' => $request->getPost('name')
	        , 'parentRowId' => $request->getPost('parentRowId')	        
	        , 'siblingOrder' => $siblingOrder	        
	        , 'contactNo' => $request->getPost('contactNo')	        
	        , 'address' => $request->getPost('address')	        
	        , 'remarks' => $request->getPost('remarks')	        
		);
		$this->db->table($this->table)->insert($data);
		$this->db->transComplete();		
	}

	public function checkDuplicateOnUpdate($request)
    {
    	$builder = $this->db->table($this->table);
		$query = $builder->select('familyRowId')
			                 ->where('name', $request->getPost('name'))
			                 ->where('parentRowId', $request->getPost('parentRowId'))
			                 ->where('familyRowId !=', $request->getPost('globalrowid'))
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
        // $this->db->transStart(true); /// this line to test rollback
        $this->db->transStart();
    	$data = array(
	        'name' => $request->getPost('name')
	        , 'parentRowId' => $request->getPost('parentRowId')
			, 'contactNo' => $request->getPost('contactNo')	        
	        , 'address' => $request->getPost('address')	        
	        , 'remarks' => $request->getPost('remarks')	        
		);
		$where = array('familyRowId' => $request->getPost('globalrowid'));
		$this->db->table($this->table)->update($data, $where);			
		$this->db->transComplete();		
	}

	public function isDependent($rowId)
    {
    	$builder = $this->db->table($this->table);
		$query = $builder->select('familyRowId')
			                 ->where('parentRowId', $rowId)
			                 ->limit(1)
			                 ->get();
		$row = $query->getRow();
		if (isset($row))
		{
			return 1;
		}
    }

	public function deleteNow($request)
	{
        $this->db->transStart();
		
		$where = array('familyRowId' => $request->getPost('rowId'));
		$this->db->table($this->table)->delete($where);
		$this->db->transComplete();		
	}

	public function getChildren($request)
    {
        $builder = $this->db->table('family');
        $query = $builder->select('family.*')
							 ->where('parentRowId', $request->getPost('rowId'))
                             ->orderBy('siblingOrder')
                             ->get();
        return($query->getResultArray());
    } 
	
	public function saveChildOrder($request)
    {
        $this->db->transStart();

        $TableData = $request->getPost('TableData');
        $TableData = stripcslashes($TableData);
        $TableData = json_decode($TableData,TRUE);
        $myTableRows = count($TableData);

        for ($i=0; $i < $myTableRows; $i++) 
        {
            $data = array(
                'siblingOrder' => $TableData[$i]['siblingOrder']
            );
            $where = array('familyRowId' => $TableData[$i]['familyRowId']);
            $this->db->table($this->table)->update($data, $where);
        }
        $this->db->transComplete();
    }

	public function insertBulkEdit($request)
    {
        $this->db->transStart();

        $TableData = $request->getPost('TableData');
        $TableData = stripcslashes($TableData);
        $TableData = json_decode($TableData,TRUE);
        $myTableRows = count($TableData);

        for ($i=0; $i < $myTableRows; $i++) 
        {
            $data = array(
                'name' => $TableData[$i]['name']
                , 'contactNo' => $TableData[$i]['contactNo']
                , 'address' => $TableData[$i]['address']
                , 'remarks' => $TableData[$i]['remarks']
            );
            $where = array('familyRowId' => $TableData[$i]['familyRowId']);
            $this->db->table($this->table)->update($data, $where);
        }
        $this->db->transComplete();
    }
}