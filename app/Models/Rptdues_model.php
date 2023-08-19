<?php namespace App\Models;
use CodeIgniter\Model;

class Rptdues_model extends Model 
{
    var $table = 'customers';

    public function getCustomerList()
    {
        $builder = $this->db->table($this->table);
        $query = $builder->select('*')
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

    public function getDues()
    {
        $q = "Select sum(amt)-sum(recd) as balance, ledger.customerRowId, customers.customerName, customers.mobile1, doobat from ledger, customers where ledger.customerRowId=customers.customerRowId AND ledger.deleted='N' AND NOT customers.remarks='dont show in dues' group by customerRowId having balance>0 order by customerName";
        $query = $this->db->query($q);
        return($query->getResultArray());
    }

    public function getDuesNegative()
    {
        $q = "Select sum(amt)-sum(recd) as balance, ledger.customerRowId, customers.customerName from ledger, customers where ledger.customerRowId=customers.customerRowId AND ledger.deleted='N' AND NOT customers.remarks='dont show in dues' group by customerRowId having balance<0 order by customerName";
        $query = $this->db->query($q);
        return($query->getResultArray());
    }


    public function checkDuplicate($request)
    {
		$builder = $this->db->table('ledger');
		$query = $builder->select('ledgerRowId')
			                 ->where('customerRowId', $request->getPost('customerRowId'))
			                 ->where('recd', $request->getPost('rAmt'))
			                 ->where('refDt', date('Y-m-d'))
			                 ->limit(1)
			                 ->get();
		$row = $query->getRow();
		if (isset($row))
		{
			return 1;
		}
    }

    public function receiveAmt($request)
    {
        $this->db->transStart();
        $customerRowId = $request->getPost('customerRowId');
         
        $builder = $this->db->table('ledger');
        $builder->selectMax('refRowId')
                ->where('ledger.vType', 'R');
        $query = $builder->get();
        $row = $query->getRow();
        $refRowId = $row->refRowId + 1;

        $builder = $this->db->table('ledger');
        $builder->selectMax('ledgerRowId');
        $query = $builder->get();
        $row = $query->getRow();
        $ledgerRowId = $row->ledgerRowId + 1;

        $dt = date('Y-m-d');
        $data = array(
            'ledgerRowId' => $ledgerRowId
            , 'vType' => 'R'
            , 'refRowId' => $refRowId
            , 'refDt' => $dt
            , 'customerRowId' => $customerRowId
            , 'recd' => $request->getPost('rAmt')
            , 'orderRowId' => -333
            , 'remarks' => 'rid-'.$request->getPost('remarks')
        );
        $this->db->table('ledger')->insert($data);

        $this->db->transComplete();
    }


    public function payAmt($request)
    {
        $this->db->transStart();
        $customerRowId = $request->getPost('customerRowId');
         
        $builder = $this->db->table('ledger');
        $builder->selectMax('refRowId')
                ->where('ledger.vType', 'P');
        $query = $builder->get();
        $row = $query->getRow();
        $refRowId = $row->refRowId + 1;

        $builder = $this->db->table('ledger');
        $builder->selectMax('ledgerRowId');
        $query = $builder->get();
        $row = $query->getRow();
        $ledgerRowId = $row->ledgerRowId + 1;

        $dt = date('Y-m-d');
        $data = array(
            'ledgerRowId' => $ledgerRowId
            , 'vType' => 'P'
            , 'refRowId' => $refRowId
            , 'refDt' => $dt
            , 'customerRowId' => $customerRowId
            , 'amt' => $request->getPost('rAmt')
            , 'bal' => $request->getPost('rAmt')
            , 'orderRowId' => -444
            , 'remarks' => 'pid-'.$request->getPost('remarks')
        );
        $this->db->table('ledger')->insert($data);

        $this->db->transComplete();
    }

    public function markDoobat($request)
    {
        $this->db->transStart();
        $customerRowId = $request->getPost('customerRowId');
         
        if( $request->getPost('abhiDoobatHaiKya') == "Yes" )
        {
            $data = array(
                'doobat' => "No"
            );
        }
        else
        {
            $data = array(
                'doobat' => "Yes"
            );
        }
        $where = array('customerRowId' => $request->getPost('customerRowId'));
        $this->db->table('customers')->update($data, $where);

        $this->db->transComplete();
    }

    public function deleteOldRecs($request)
    {
        $this->db->transStart();
        $dt = date('Y-m-d', strtotime($request->getPost('dt')));

        ///////// Sab Parties k balance Fwd (clBal -> opBal) kr rahe h
            $builder = $this->db->table('ledger');
            $query = $builder->select('sum(amt) - sum(recd) as bal, ledger.customerRowId')
                                 ->where('ledger.deleted', 'N')
                                 ->where('refDt<', $dt)
                                 ->groupBy('ledger.customerRowId')
                                 ->orderBy('ledger.customerRowId')
                                 ->get();
            foreach ($query->getResultArray() as $row)
            {
                if( $row['bal'] > 0 ) //// amt
                {
                    $amt = abs($row['bal']);
                    $recd = 0;
                }
                else  //// recd
                {
                    $amt = 0;
                    $recd = abs($row['bal']);
                }
                ////// insert in ledgerA
                $builder = $this->db->table('ledger');
                $builder->selectMax('ledgerRowId');
                $query = $builder->get();
                $rowInner = $query->getRow();
                $rowId = $rowInner->ledgerRowId + 1;
                $data = array(
                    'ledgerRowId' => $rowId
                    , 'vType' => 'OB'
                    , 'customerRowId' => $row['customerRowId']
                    , 'refRowId' => 1
                    , 'refDt' => date('Y-m-d', strtotime($request->getPost('dt')))
                    , 'amt' => floatval( $amt )  /// floatVal se agar null hoga to 0 ho jayega
                    , 'recd' => floatval( $recd )
                    , 'bal' => floatval( $amt )
                    , 'remarks' => ''
                );
                $this->db->table('ledger')->insert($data);
            }/// loop end
        ///////// END - Sab Parties k balance Fwd (clBal -> opBal) kr rahe h

        ///// del ledger
            $where = array('refDt<' => $dt);
            $this->db->table('ledger')->delete($where); 
        ///// END -del ledger


     ///// del BKP table all
            $where = array('rowId>' => 0);
            $this->db->table('bkp')->delete($where);

     ///// del Complaints
            $where = array('solved' => 'Y');
            $this->db->table('complaints')->delete($where);

     ///// del Notifications
            $where = array('deleted' => 'Y');
            $this->db->table('notifications')->delete($where);

     ///// del reminders
            $where = array('deleted' => 'Y');
            $this->db->table('reminders')->delete($where);

     ///// del replacement
            $where = array('sent' => 'Y', 'recd' => 'Y');
            $this->db->table('replacement')->delete($where);
    
    ///// del SendSms table all
            $where = array('smsRowId>' => 0);
            $this->db->table('sendsms')->delete($where);

     ///// del Sale before defined dt
            $where = array('dbDt<' => $dt);
            $this->db->table('db')->delete($where);
        $this->db->query('DELETE FROM dbdetail WHERE dbRowId NOT IN (Select dbRowId from db)'); 

     ///// del Purchase before defined dt
            $where = array('purchaseDt<' => $dt);
            $this->db->table('purchase')->delete($where);
        $this->db->query('DELETE FROM purchasedetail WHERE purchaseRowId NOT IN (Select purchaseRowId from purchase)');  
        $this->db->transComplete();     
    }
}