<?php

namespace App\Models;

use CodeIgniter\Model;

class Dailycash_model extends Model
{
    var $table = 'dailycash';

    public function getOpeningBal()
    {
        $builder = $this->db->table($this->table);
        $query = $builder->selectSum('out')->selectSum('in')
            ->where('dailycash.dt <', date('Y-m-01'))
            ->get();
        return ($query->getResultArray());
    }

    public function getPlusDues()
    {
        $q = "Select sum(amt)-sum(recd) as balance, ledger.customerRowId, doobat from ledger, customers where ledger.customerRowId=customers.customerRowId AND ledger.deleted='N' AND doobat='No' AND NOT customers.remarks='dont show in dues' group by customerRowId having balance>0 order by customerName";
        $query = $this->db->query($q);
        return ($query->getResultArray());
    }

    public function getMinusDues()
    {
        $q = "Select sum(amt)-sum(recd) as balance, ledger.customerRowId, doobat from ledger, customers where ledger.customerRowId=customers.customerRowId AND ledger.deleted='N' AND NOT customers.remarks='dont show in dues' group by customerRowId having balance<0 order by customerName";
        $query = $this->db->query($q);
        return ($query->getResultArray());
    }

    public function getPurchaseSum()
    {
        $builder = $this->db->table('ledger');
        $query = $builder->selectSum('amt')
            ->where('ledger.refDt', date('Y-m-d'))
            ->where('ledger.vType', 'PV')
            ->where('ledger.deleted', 'N')
            ->get();
        return ($query->getResultArray());
    }
    public function getPaymentsSum()
    {
        $builder = $this->db->table('ledger');
        $query = $builder->selectSum('amt')
            ->where('ledger.refDt', date('Y-m-d'))
            ->where('ledger.vType', 'P')
            ->where('ledger.deleted', 'N')
            ->get();
        return ($query->getResultArray());
    }
    public function getUpiCollection()
    {
        $builder = $this->db->table('ledger');
        $query = $builder->selectSum('amt')
            ->where('ledger.refDt', date('Y-m-d'))
            ->where('ledger.vType', 'P')
            ->where('ledger.deleted', 'N')
            ->where('ledger.remarks', 'UPI')
            ->get();
        return ($query->getResultArray());
    }

    public function getDeepuSuriBank()
    {
        $bal = 0;
        $suri = 0;
        ////Suri
        $q = "Select sum(amt)-sum(recd) as balance, customerRowId from ledger  WHERE ledger.deleted='N' AND customerRowId=1 group by customerRowId";
        $query = $this->db->query($q);
        if ($query->getNumRows() > 0) 
        {
            $row = $query->getRowArray();
            $bal = $row['balance'];
            $suri = $row['balance'];
        }
        /// Deepu
        $deepu = 0;
        $q = "Select sum(amt)-sum(recd) as balance, customerRowId from ledger  WHERE ledger.deleted='N' AND customerRowId=98 group by customerRowId";
        $query = $this->db->query($q);
        if ($query->getNumRows() > 0) 
        {
            $row = $query->getRowArray();
            $bal += (int)$row['balance'];
            $deepu = $row['balance'];
        }
        /// Equtas Bank
        $equitas = 0;
        $q = "Select sum(amt)-sum(recd) as balance, customerRowId from ledger  WHERE ledger.deleted='N' AND customerRowId=880 group by customerRowId";
        $query = $this->db->query($q);
        if ($query->getNumRows() > 0) 
        {
            $row = $query->getRowArray();
            $bal += (int)$row['balance'];
            $equitas = $row['balance'];
        }
        /// ACC Bank
        $acc = 0;
        $q = "Select sum(amt)-sum(recd) as balance, customerRowId from ledger  WHERE ledger.deleted='N' AND customerRowId=570 group by customerRowId";
        $query = $this->db->query($q);
        if ($query->getNumRows() > 0) 
        {
            $row = $query->getRowArray();
            $bal += (int)$row['balance'];
            $acc = $row['balance'];
        }

        ///// udahri + except deepu, suri, equitas and acc
        $q = "Select sum(amt)-sum(recd) as balance, ledger.customerRowId, doobat from ledger, customers where ledger.customerRowId=customers.customerRowId AND customers.customerRowId NOT IN (1,880,98,570) AND ledger.deleted='N' AND doobat='No' AND NOT customers.remarks='dont show in dues' group by customerRowId having balance>0 order by customerName";
        $query = $this->db->query($q);
        $plusSum = 0;
        foreach ($query->getResult() as $row) {
            $plusSum += $row->balance;
        }

        
        ///// udahri Chukani h 
        $q = "Select sum(amt)-sum(recd) as balance, ledger.customerRowId, doobat from ledger, customers where ledger.customerRowId=customers.customerRowId AND ledger.deleted='N' AND NOT customers.remarks='dont show in dues' group by customerRowId having balance<0 order by customerName";
        $query = $this->db->query($q);
        $minussSum = 0;
        foreach ($query->getResult() as $row) {
            $minussSum += $row->balance;
        }

        $bal += $plusSum + $minussSum;
        // return $bal;
        $detail = array("bal" => $bal, "suri" => $suri, "deepu" => $deepu, "equitas" => $equitas, "acc" => $acc, "plusSum" => $plusSum, "minusSum" => $minussSum);
        return $detail;
    }


    public function getDataLimit()
    {
        $builder = $this->db->table('dailycash');
        $query = $builder->select('dailycash.*')
            ->where('dailycash.dt >=', date('Y-m-01'))
            // ->limit(5)
            ->orderBy('dailycash.dt, rowId')
            ->get();
        return ($query->getResultArray());
    }
    public function getDataAll()
    {
        $builder = $this->db->table('dailycash');
        $query = $builder->select('dailycash.*')
            //  ->where('dailycash.dt >=', date('Y-m-01'))
            ->orderBy('dailycash.dt, rowId')
            ->get();
        return ($query->getResultArray());
    }

    public function insertNow($request)
    {
        $this->db->transStart();

        $dt = date('Y-m-d', strtotime($request->getPost('dt')));
        ////Kya is date ki entry pahle se h???
        $builder = $this->db->table('dailycash');
        $query = $builder->select('dailycash.*')
            ->where('dt', $dt)
            ->get();
        if ($query->getNumRows() > 0) ///agar already h ye date ka record, OverWrite
        {
            $row = $query->getRowArray();
            $rowId = $row['rowId'];
            $rem = $row['remarks'];

            if ($request->getPost('inOutMode') == "IN") {
                $data = array(
                    'in' => $request->getPost('amt'), 'remarks' => $request->getPost('remarks'), 'denominationIn' => $request->getPost('deno')
                );
                $where = array('rowId' => $rowId);
                $this->db->table($this->table)->update($data, $where);
            } else if ($request->getPost('inOutMode') == "OUT") {
                $data = array(
                    'out' => $request->getPost('amt'), 'remarks' => $rem . ' ' . $request->getPost('remarks'), 'denominationOut' => $request->getPost('deno')
                );
                $where = array('rowId' => $rowId);
                $this->db->table($this->table)->update($data, $where);
            }
        } else {
            if ($request->getPost('inOutMode') == "IN") {
                $builder = $this->db->table($this->table);
                $builder->selectMax('rowId');
                $query = $builder->get();
                $row = $query->getRow();
                $rowId = $row->rowId + 1;

                $data = array(
                    'rowId' => $rowId, 'dt' => $dt, 'in' => $request->getPost('amt'), 'remarks' => $request->getPost('remarks'), 'denominationIn' => $request->getPost('deno'), 'createdBy' => session('userRowId'), 'createdStamp' => date('Y-m-d H:i')
                );
                $this->db->table($this->table)->insert($data);
            } else if ($request->getPost('inOutMode') == "OUT") {
                //pahle in wal record insert ho hi chuka hoga is liye yaha kuch nahi
            }
        }

        $this->db->transComplete();
    }


    public function dailyCashInEntry()
    {
        $curDate = date('Y-m-d');
        $builder = $this->db->table('dailycash');
        $query = $builder->select('rowId')
            ->where('dailycash.dt', $curDate)
            ->get();
        if ($query->getNumRows() > 0) {
            return "entered";
        } else {
            return "notEntered";
        }
    }

    public function thisDateMustBeThare($request)
    {
        $dt = date('Y-m-d', strtotime($request->getPost('dt')));
        $builder = $this->db->table('dailycash');
        $query = $builder->select('rowId')
            ->where('dailycash.dt', $dt)
            ->get();

        if ($query->getNumRows() > 0) {
            return 1;
        } else {
            return 0;
        }
    }


    public function deleteOldData($request)
    {
        $this->db->transStart();

        $dt = date('Y-m-d', strtotime($request->getPost('dt')));
        //// clos bal is date ka
        $builder = $this->db->table('dailycash');
        $query = $builder->selectSum('out')->selectSum('in')
            ->where('dailycash.dt <=', $dt)
            ->get();
        // return($query->getResultArray());

        $clBal = 0;
        if ($query->getNumRows() > 0) {
            $row = $query->getRowArray();
            $clBal = $row['out'] - $row['in'];
        }

        //// is date se pahle k rec del
        $where = array('dt<' => $dt);
        $this->db->table('dailycash')->delete($where);

        $data = array(
            'in' => 0,
            'out' => $clBal,
            'remarks' => 'data deleted on ' . date('Y-m-d')
        );
        $where = array('dt' => $dt);
        $this->db->table('dailycash')->update($data, $where);

        $this->db->transComplete();
    }


    public function saveUpiAmt($request)
    {
        $this->db->transStart();

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

        $dt = date('Y-m-d');
        $data = array(
            'ledgerRowId' => $ledgerRowId
            , 'vType' => 'P'
            , 'refRowId' => $refRowId
            , 'refDt' => $dt
            , 'customerRowId' => 98
            , 'amt' => $request->getPost('upiAmt')
            , 'bal' => $request->getPost('upiAmt')
            , 'orderRowId' => -987
            , 'remarks' => "UPI"
        );
        $this->db->table('ledger')->insert($data);
        

        $this->db->transComplete();
    }
}
