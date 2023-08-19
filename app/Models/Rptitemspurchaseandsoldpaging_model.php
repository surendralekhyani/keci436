<?php namespace App\Models;
use CodeIgniter\Model;

class Rptitemspurchaseandsoldpaging_model extends Model 
{
    var $table = 'customers';
	protected $primaryKey = 'customerRowId';
    protected $allowedFields = [
        'customerName', 
        'mobile1'
      ];


    public function getDataForReportSale($request, $perPage = null, $offset = null)
    {
        // $pager = \Config\Services::pager();
        $builder = $this->db->table('dbdetail');
        $query = $builder->select('dbdetail.dbdRowId as detailRowId, dbdetail.dbRowId as rowId, dbdetail.itemRowId, dbdetail.qty, dbdetail.rate, dbdetail.amt, dbdetail.discountPer, dbdetail.discountAmt, dbdetail.pretaxAmt, dbdetail.igst, dbdetail.igstAmt, dbdetail.cgst, dbdetail.cgstAmt, dbdetail.sgst, dbdetail.sgstAmt, dbdetail.netAmt, dbdetail.pp, "" as sp, dbdetail.itemRemarks, items.itemName, customers.customerName, db.dbDt as dt')
                            //  ->where('db.dbRowId>', 1)
                             ->where('db.deleted', 'N')
                             ->where('db.dbDt <=', date('Y-m-d', strtotime($request->getVar('dtTo'))))
                             ->where('db.dbDt >=', date('Y-m-d', strtotime($request->getVar('dtFrom'))))
                             ->groupStart()
                                 ->like('items.itemName', $request->getVar('searchWhat'))
                                 ->orLike('customers.customerName', $request->getVar('searchWhat'))
                             ->groupEnd()
                             ->join('db','db.dbRowId = dbdetail.dbRowId')
                             ->join('customers','customers.customerRowId = db.customerRowId')
                             ->join('items', 'items.itemRowId = dbdetail.itemRowId')
                             ->orderBy('dbdetail.dbRowId, dbdetail.dbdRowId')
                             ->limit($perPage, $offset)
                             ->get();
        return($query->getResultArray());
    }

    public function getDataForReportPurchase($request, $perPage = null, $offset = null)
    {
        $builder = $this->db->table('purchasedetail');
        $query = $builder->select('purchasedetail.purchaseDetailRowId as detailRowId, purchasedetail.purchaseRowId as rowId, purchasedetail.itemRowId, purchasedetail.qty, purchasedetail.rate, purchasedetail.amt, purchasedetail.discountPer, purchasedetail.discountAmt, purchasedetail.pretaxAmt, purchasedetail.igst, purchasedetail.igstAmt, purchasedetail.cgst, purchasedetail.cgstAmt, purchasedetail.sgst, purchasedetail.sgstAmt, purchasedetail.netAmt, purchasedetail.sellingPricePer, purchasedetail.sp, purchasedetail.freight, purchasedetail.itemRemarks, items.itemName, customers.customerName, purchase.purchaseDt as dt')
                             ->where('purchase.deleted', 'N')
                             ->where('purchase.purchaseDt <=', date('Y-m-d', strtotime($request->getVar('dtTo'))))
                             ->where('purchase.purchaseDt >=', date('Y-m-d', strtotime($request->getVar('dtFrom'))))
                             ->groupStart()
                                 ->like('items.itemName', $request->getVar('searchWhat'))
                                 ->orLike('customers.customerName', $request->getVar('searchWhat'))
                             ->groupEnd()
                             ->join('purchase','purchase.purchaseRowId = purchasedetail.purchaseRowId')
                             ->join('customers','customers.customerRowId = purchase.customerRowId')
                             ->join('items', 'items.itemRowId = purchasedetail.itemRowId')
                             ->orderBy('purchasedetail.purchaseRowId, purchasedetail.purchaseDetailRowId')
                             ->limit($perPage, $offset)
                             ->get();
        return($query->getResultArray());

    }


    // public function getDataForReportQuotation($request)
    // {
    //     $builder = $this->db->table('quotationdetail');
    //     $query = $builder->select('quotationdetail.quotationDetailRowId, quotationdetail.quotationRowId, quotationdetail.itemRowId, quotationdetail.qty, quotationdetail.rate, quotationdetail.amt, items.itemName, customers.customerName, quotation.quotationDt')
    //                          ->where('quotation.deleted', 'N')
    //                          ->where('quotation.quotationDt <=', date('Y-m-d', strtotime($request->getPost('dtTo'))))
    //                          ->where('quotation.quotationDt >=', date('Y-m-d', strtotime($request->getPost('dtFrom'))))
    //                          ->groupStart()
    //                              ->like('items.itemName', $request->getPost('searchWhat'))
    //                              ->orLike('customers.customerName', $request->getPost('searchWhat'))
    //                          ->groupEnd()
    //                          ->join('quotation','quotation.quotationRowId = quotationdetail.quotationRowId')
    //                          ->join('customers','customers.customerRowId = quotation.customerRowId')
    //                          ->join('items', 'items.itemRowId = quotationdetail.itemRowId')
    //                          ->orderBy('quotationdetail.quotationRowId, quotationdetail.quotationDetailRowId')
    //                          ->get();
    //     return($query->getResultArray());
    // }


    // public function getDataForReportCashSale($request)
    // {
    //     $builder = $this->db->table('cashsale');
    //     $query = $builder->select('cashsale.*')
    //                          ->where('cashsale.dt <=', date('Y-m-d', strtotime($request->getPost('dtTo'))))
    //                          ->where('cashsale.dt >=', date('Y-m-d', strtotime($request->getPost('dtFrom'))))
    //                          ->groupStart()
    //                              ->like('cashsale.itemName', $request->getPost('searchWhat'))
    //                          ->groupEnd()
    //                          ->orderBy('cashsale.cashSaleRowId')
    //                          ->get();
    //     return($query->getResultArray());
    // }

    
}