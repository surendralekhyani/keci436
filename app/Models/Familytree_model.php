<?php namespace App\Models;
use CodeIgniter\Model;

class Familytree_model extends Model 
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
        ORDER BY F1.parentRowId, F1.siblingOrder";
        $query2 = $this->db->query($q2);
        $r2 = $query2->getResultArray();
        $r3 = array_merge($r1,$r2);
        return ($r3);
        // return ($query2->getResultArray());
	}



}