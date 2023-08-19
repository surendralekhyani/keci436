<?php namespace App\Models;
use CodeIgniter\Model;

class Rptledger_model extends Model 
{
    var $table = 'ledger';
    
    public function getOpeningBal($request)
    {
        $builder = $this->db->table($this->table);
        $query = $builder->selectSum('amt')
                            ->selectSum('recd')
                             ->where('deleted', 'N')
                             ->where('ledger.customerRowId', $request->getPost('customerRowId'))
                             ->where('ledger.refDt <', date('Y-m-d', strtotime($request->getPost('dtFrom'))))
                             ->get();
        return($query->getResultArray());
    }

    public function getDataForReport($request)
    {
        $builder = $this->db->table($this->table);
        $query = $builder->select('ledger.*')
                             ->where('deleted', 'N')
                             ->where('ledger.customerRowId', $request->getPost('customerRowId'))
                             ->where('ledger.refDt <=', date('Y-m-d', strtotime($request->getPost('dtTo'))))
                             ->where('ledger.refDt >=', date('Y-m-d', strtotime($request->getPost('dtFrom'))))
                             ->orderBy('ledger.refDt, ledgerRowId')
                             ->get();
        return($query->getResultArray());
    }

    public function getSaleDetail($request)
    {
        $builder = $this->db->table('dbdetail');
        $query = $builder->select('dbdetail.dbdRowId, dbdetail.dbRowId, dbdetail.itemRowId, dbdetail.qty, dbdetail.rate, dbdetail.amt, dbdetail.discountPer, dbdetail.discountAmt, dbdetail.pretaxAmt, dbdetail.igst, dbdetail.igstAmt, dbdetail.cgst, dbdetail.cgstAmt, dbdetail.sgst, dbdetail.sgstAmt, dbdetail.netAmt, dbdetail.pp, dbdetail.itemRemarks, items.itemName')
                             ->where('dbRowId', $request->getPost('rowid'))
                             ->join('items', 'items.itemRowId = dbdetail.itemRowId')
                             ->orderBy('dbdRowId')
                             ->get();
        return($query->getResultArray());
    }


    public function getSaleDetailSr()
    {
        // $this->db->select('srdetail.*,sr.dbRowId');
        // $this->db->where('sr.dbRowId', $this->input->post('rowid'));
        // $this->db->join('sr','sr.srRowId = srdetail.srRowId');
        // $this->db->from('srdetail');
        // $this->db->order_by('srdRowId');
        // $query = $this->db->get();
        // return($query->result_array());

    }

    public function getPurchaseDetail($request)
    {
        $builder = $this->db->table('purchasedetail');
        $query = $builder->select('purchasedetail.purchaseDetailRowId, purchasedetail.purchaseRowId, purchasedetail.itemRowId, purchasedetail.qty, purchasedetail.rate, purchasedetail.amt, purchasedetail.discountPer, purchasedetail.discountAmt, purchasedetail.pretaxAmt, purchasedetail.igst, purchasedetail.igstAmt, purchasedetail.cgst, purchasedetail.cgstAmt, purchasedetail.sgst, purchasedetail.sgstAmt, purchasedetail.netAmt, purchasedetail.sellingPricePer, purchasedetail.sp, purchasedetail.freight, purchasedetail.itemRemarks, items.itemName')
                             ->where('purchaseRowId', $request->getPost('rowid'))
                             ->join('items', 'items.itemRowId = purchasedetail.itemRowId')
                             ->orderBy('purchaseDetailRowId')
                             ->get();
        return($query->getResultArray());
    }
}