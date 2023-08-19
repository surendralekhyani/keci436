<?php namespace App\Models;
use CodeIgniter\Model;

class Util_model extends Model 
{
    
    

    public function getCustomerWithBalance()
    {
        $q = "Select sum(amt)-sum(recd) as balance, customers.customerRowId, customers.customerName,customers.mobile1, customers.address,customers.remarks from customers LEFT JOIN ledger  ON ledger.customerRowId=customers.customerRowId AND ledger.deleted='N' WHERE customers.deleted='N' group by customers.customerRowId, customers.customerName,customers.mobile1, customers.address,customers.remarks order by customerName";
        $query = $this->db->query($q);
        return($query->getResultArray());
    }

    public function isDependent($tableName, $fieldName, $val)
    {
        $builder = $this->db->table($tableName);
        $query = $builder->select('*')
                             ->where($fieldName, $val)
                             ->limit(1)
                             ->get();
        $row = $query->getRow();
        if (isset($row))
        {
            return 1;
        }
    } 
      


    public function getOrg()
    {
        $db      = \Config\Database::connect();
        $builder = $db->table('organisation');
        $query = $builder->select('organisation.*')
                         ->get();
        return($query->getResultArray());
    }

    


    public function getUserRights()
    {
        $db      = \Config\Database::connect();
        $builder = $db->table('userrights');
        $query = $builder->select('menuoption')
                         ->where('userrowid', session('userRowId'))
                        //  ->where('userrowid', 1)
                         ->get();
        return($query->getResultArray());
        
    }


    // public function getAuth($r="sdsa2dsda")
    // {
    //     if ($this->session->isLogin===True && $this->session->session_id != '' && $this->session->apnaAadmi == 'haanHai' ) /*if logged in*/
    //     {
    //       return 1;
    //     }
    //     else
    //     {
    //       return 0;
    //     }
    // }
}