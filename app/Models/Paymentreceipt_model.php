<?php namespace App\Models;
use CodeIgniter\Model;

class Paymentreceipt_model extends Model 
{
    var $table = 'ledger';

    // public function getCustomers()
    // {
    //     $this->db->select('customers.*');
    //     $this->db->where('deleted', 'N');
    //     $this->db->order_by('customerName');
    //     $query = $this->db->get('customers');

    //     return($query->result_array());
    // } 
    public function getCustomerList()
    {
        $builder = $this->db->table('customers');
        $query = $builder->select('customers.customerRowId, customers.customerName')
                             ->where('deleted', 'N')
                             ->orderBy('customerName')
                             ->get();
        // return($query->getResultArray());
        $arr = array();
        $arr["-1"] = '--- Select ---';
        foreach ($query->getResultArray() as $row)
        {
            $arr[$row['customerRowId']]= $row['customerName'];
        }

        return $arr;

    }  
    

    public function checkDuplicateNewCustomer($request)
    {
        $builder = $this->db->table('customers');
        $query = $builder->select('customerRowId')
                             ->where('customerName', $request->getPost('customerName'))
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
        $customerRowId = $request->getPost('customerRowId');
        if( $customerRowId == -1 ) ///new customer
        {
            $builder = $this->db->table('customers');
            $builder->selectMax('customerRowId');
            $query = $builder->get();
            $row = $query->getRow();
            $customerRowId = $row->customerRowId + 1;
            $data = array(
                'customerRowId' => $customerRowId
                , 'customerName' => ucwords($request->getPost('customerName'))
                , 'address' => $request->getPost('address')         
                , 'mobile1' => $request->getPost('mobile1')
                , 'remarks' => $request->getPost('customerRemarks')
                , 'createdBy' => session('userRowId')
                , 'createdStamp' => date('Y-m-d H:i')
            );
            $this->db->table('customers')->insert($data);	
        }
        
        if( $request->getPost('transactionMode') == "Payment" || $request->getPost('transactionMode') == "UPI" )
        {
            $builder = $this->db->table('ledger');
            $query = $builder->selectMax('refRowId')
                    ->where('ledger.vType', 'P')
                    ->get();
            $row = $query->getRowArray();
            $refRowId = $row['refRowId']+1;


            $builder = $this->db->table('ledger');
            $query = $builder->selectMax('ledgerRowId')
                    ->get();
            $row = $query->getRowArray();
            $ledgerRowId = $row['ledgerRowId']+1;

            $dt = date('Y-m-d', strtotime($request->getPost('dt')));
            $data = array(
                'ledgerRowId' => $ledgerRowId
                , 'vType' => 'P'
                , 'refRowId' => $refRowId
                , 'refDt' => $dt
                , 'customerRowId' => $customerRowId
                , 'amt' => $request->getPost('amt')
                , 'bal' => $request->getPost('amt')
                , 'orderRowId' => -222
                , 'remarks' => $request->getPost('remarks')
            );
            $this->db->table('ledger')->insert($data);
        }   
        else if( $request->getPost('transactionMode') == "Received" )
        {
            $builder = $this->db->table('ledger');
            $query = $builder->selectMax('refRowId')
                    ->where('ledger.vType', 'R')
                    ->get();
            $row = $query->getRowArray();
            $refRowId = $row['refRowId']+1;

            $builder = $this->db->table('ledger');
            $query = $builder->selectMax('ledgerRowId')
                    ->get();
            $row = $query->getRowArray();
            $ledgerRowId = $row['ledgerRowId']+1;

            $dt = date('Y-m-d', strtotime($request->getPost('dt')));
            $data = array(
                'ledgerRowId' => $ledgerRowId
                , 'vType' => 'R'
                , 'refRowId' => $refRowId
                , 'refDt' => $dt
                , 'customerRowId' => $customerRowId
                , 'recd' => $request->getPost('amt')
                , 'orderRowId' => -222
                , 'remarks' => $request->getPost('remarks')
            );
            $this->db->table('ledger')->insert($data);
        }   
        $this->db->transComplete();
	}

    public function getCustomerNewBalance($request)
    {
        $builder = $this->db->table('ledger');
        $query = $builder->select('sum(amt)-sum(recd) as balance')
                                ->where('customerRowId', $request->getPost('customerRowId'))
                                ->where('deleted', 'N')
                             ->get();
        return($query->getResultArray());
    }
    
	public function updateNow()
    {

	}


	public function deleteNow($request)
    {
        $data = array(
                'deleted' => 'Y',
        );
        $where = array('vType' => $request->getPost('globalVtype')
                        , 'refRowId' => $request->getPost('globalrowid'));
        $this->db->table('ledger')->update($data, $where);
    }



	public function getDataLimit()
    {
        $builder = $this->db->table('ledger');
        $query = $builder->select('ledger.*, customers.customerName')
                                ->where('ledger.vType', 'P')
                                ->orWhere('ledger.vType', 'R')
                             ->join('customers','customers.customerRowId = ledger.customerRowId')
                             ->limit(15)
                             ->orderBy('ledgerRowId desc')
                             ->get();
        return($query->getResultArray());

    }
    public function getDataAll()
    {
        $builder = $this->db->table('ledger');
        $query = $builder->select('ledger.*, customers.customerName')
                                ->where('ledger.vType', 'P')
                                ->orWhere('ledger.vType', 'R')
                             ->join('customers','customers.customerRowId = ledger.customerRowId')
                             ->orderBy('ledgerRowId desc')
                             ->get();
        return($query->getResultArray());
    }

}