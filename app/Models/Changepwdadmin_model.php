<?php namespace App\Models;
use CodeIgniter\Model;

class Changepwdadmin_model extends Model 
{
	var $table = 'users';

    public function changepwd($uid, $n_pw)
    {
    	$model = new Login_model();
		$pwd  = password_hash($n_pw, PASSWORD_DEFAULT);
		$data = array(
		        'pwd' => $pwd
		);
		$where = array('rowid' => $uid);
		$this->db->table('users')->update($data, $where);
		return(true);

  //   	// print_r("<p>Inside if >> ChangePwdAdmin:changepwd: ");
  //   	// Getting hash for new password
  //   	$pwd  = $this->LogHash_model->create_hash($n_pw);
		// $data = array(
		//         // 'uid' => $uid,
		//         'pwd' => $pwd
		// );

		// $this->db->where('rowid', $uid);
		// $this->db->update('users', $data);
		// return(true);
    }

	public function getData()
	{
		$builder = $this->db->table($this->table);
		$query = $builder->select('*')
			                 ->where('deleted', 'N')
			                 ->orderBy('uid')
			                 ->get();
		$arr = array();
		$arr["-1"] = '--- Select User ---';
		foreach ($query->getResultArray() as $row)
		{
    		$arr[$row['rowid']]= $row['uid'];
		}
		return $arr;
	}

}

