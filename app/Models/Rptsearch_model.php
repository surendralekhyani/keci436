<?php namespace App\Models;
use CodeIgniter\Model;

class Rptsearch_model extends Model 
{
    var $table = 'ledger';
    public function getLedgerData($request)
    {
        $builder = $this->db->table('ledger');
        $query = $builder->select('ledger.refDt, ledger.remarks, ledger.vType, ledger.refRowId, ledger.amt, ledger.recd, customers.customerName')
                             ->where('ledger.deleted', 'N')
                             ->like('ledger.remarks', $request->getPost('searchWhat'))
                             ->orLike('customers.customerName', $request->getPost('searchWhat'))
                             ->join('customers','customers.customerRowId = ledger.customerRowId')
                             ->orderBy('ledger.refDt, ledgerRowId')
                             ->get();
        return($query->getResultArray());

     // $this->db->select('ledger.refDt, ledger.remarks, ledger.vType, ledger.refRowId, ledger.amt, ledger.recd, customers.customerName');
     // $this->db->from('ledger');
     // $this->db->join('customers','customers.customerRowId = ledger.customerRowId');
     // $this->db->where('ledger.deleted', 'N');
     // $this->db->like('ledger.remarks', $this->input->post('searchWhat'));
     // $this->db->or_like('customers.customerName', $this->input->post('searchWhat')); 

     // $this->db->order_by('ledger.refDt, ledgerRowId');
     // $query = $this->db->get();
     // return($query->result_array());
    }

    public function getReminderData($request)
    {
        $builder = $this->db->table('reminders');
        $query = $builder->select('reminders.dt, reminders.remarks, reminders.repeat')
                             ->where('reminders.deleted', 'N')
                             ->like('reminders.remarks', $request->getPost('searchWhat'))
                             ->orLike('reminders.repeat', $request->getPost('searchWhat'))
                             ->orderBy('reminders.dt, reminderRowId')
                             ->get();
        return($query->getResultArray());

     // $this->db->select('reminders.dt, reminders.remarks, reminders.repeat');
     // $this->db->from('reminders');
     // $this->db->where('reminders.deleted', 'N');
     // $this->db->like('reminders.remarks', $this->input->post('searchWhat'));
     // $this->db->or_like('reminders.repeat', $this->input->post('searchWhat')); 

     // $this->db->order_by('reminders.dt, reminderRowId');
     // $query = $this->db->get();
     // return($query->result_array());
    }

    public function getDatesData($request)
    {
        $builder = $this->db->table('dates');
        $query = $builder->select('dates.dt, dates.remarks')
                             ->where('dates.deleted', 'N')
                             ->like('dates.remarks', $request->getPost('searchWhat'))
                             ->orderBy('dates.dt, dateRowId')
                             ->get();
        return($query->getResultArray());

     // $this->db->select('dates.dt, dates.remarks');
     // $this->db->from('dates');
     // $this->db->where('dates.deleted', 'N');
     // $this->db->like('dates.remarks', $this->input->post('searchWhat'));

     // $this->db->order_by('dates.dt, dateRowId');
     // $query = $this->db->get();
     // return($query->result_array());
    }

    public function getCashSaleData($request)
    {
        $builder = $this->db->table('cashsale');
        $query = $builder->select('cashsale.*')
                             ->like('cashsale.remarks', $request->getPost('searchWhat'))
                             ->orLike('cashsale.itemName', $request->getPost('searchWhat'))
                             // ->join('customers','customers.customerRowId = ledger.customerRowId')
                             ->orderBy('cashsale.dt, cashsale.cashSaleRowId')
                             ->get();
        return($query->getResultArray());
     // $this->db->select('cashsale.*');
     // $this->db->from('cashsale');
     // // $this->db->where('cashsale.deleted', 'N');
     // $this->db->like('cashsale.remarks', $this->input->post('searchWhat'));
     // $this->db->or_like('cashsale.itemName', $this->input->post('searchWhat')); 

     // $this->db->order_by('cashsale.dt, cashSaleRowId');
     // $query = $this->db->get();
     // return($query->result_array());
    }    
}