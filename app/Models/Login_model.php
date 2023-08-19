<?php namespace App\Models;
use CodeIgniter\Model;

class Login_model extends Model {

	var $table = 'users';
	protected $primaryKey = 'rowid';
	// protected $useAutoIncrement = false;
	protected $allowedFields = ['uid','pwd'];

	// public function checkuser($uid, $pwd)
	// {
	// 	// $db      = \Config\Database::connect();
	// 	$builder = $this->db->table('users');
	// 	$query = $builder->select('*')
	// 	                 ->where('uid', $uid)
	// 	                 ->limit(1)
	// 	                 ->get();

	// 	if(count($query->getResultArray()) > 0)
	// 	{
	// 		$row = $query->getRow();
	// 		if (isset($row))
	// 		{
	// 			$dbPwd = $row->pwd;
	//         	if(password_verify($pwd, $dbPwd))	// authenticates successfully
	//         	{
	//         		return $query->getResultArray();
	//         	}
	// 		}

	// 	}
		
	// }
		
	// 	public function getReminders()
	//     {
	//     	$dt = date("Y-m-d");
	//      $this->db->select('ledger.*, customers.customerName');
	//      $this->db->from('ledger');
	//      $this->db->join('customers','customers.customerRowId = ledger.customerRowId');
	//      $this->db->where('ledger.deleted', 'N');
	//      $this->db->where('ledger.reminder >=', $dt);
	//      $this->db->where('ledger.reminder <=', $dt);
	//      $this->db->where('ledger.bal >', 0);
	//      $this->db->order_by('ledger.refDt, ledgerRowId');
	//      $query = $this->db->get();
	//      return($query->result_array());
	//     }


	//     public function getLastBackupDt()
	//     {
	//     	//SELECT CURRENT_DATE - max(dt) from bkp
	//     	$sqlStr = "SELECT DATEDIFF(CURDATE(), max(dt)) AS kitneDinHoGaye FROM bkp";
	// 		$query = $this->db->query($sqlStr);
	// 		$kitneDinHoGaye;
	// 	    if ($query->num_rows() > 0)
	// 		{
	// 			$row = $query->row_array();
	// 		    $kitneDinHoGaye = $row['kitneDinHoGaye'];	
	// 		    return $kitneDinHoGaye;
	// 		}
	//     }
	//     public function setBackupDt()
	//     {
	//     	$dt = date("Y-m-d");
	//     	$this->db->select_max('rowId');
	// 		$query = $this->db->get('bkp');
	//         $row = $query->row_array();
	//         $current_row = $row['rowId']+1;
	// 		$data = array(
	// 	        'rowId' => $current_row
	// 	        , 'dt' => $dt
	// 		);
	// 		$this->db->insert('bkp', $data);

	// 		///// purane record delete
	//      	$this->db->where('dt <', $dt);
	// 		$this->db->delete('bkp');

	//     }


	// public function markPadhLiya()
	// {
	// 	$data = array(
	//         'deleted' => 'Y'
	// 	);
	// 	$this->db->where('rowId', $this->input->post('notificationRowId'));
	// 	$this->db->where('notificationType', $this->input->post('notificationType'));
	// 	$this->db->update('notifications', $data);		
	// }

	

}