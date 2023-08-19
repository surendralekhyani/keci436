<?php namespace App\Models;
use CodeIgniter\Model;
use App\Models\Login_model;

class Changepwd_model extends Model 
{
	var $table = 'customers';

    public function changepwd($uid, $o_pw, $n_pw)
    {
    	$model = new Login_model();
        $userRec = $model->checkuser($uid, $o_pw);
        if($userRec && count($userRec)>0)	// means old password is correct
        {
	    	// $pwd  = $this->LogHash_model->create_hash($n_pw);
	    	// print_r($userRec);
			$pwd  = password_hash($n_pw, PASSWORD_DEFAULT);
			$data = array(
			        'pwd' => $pwd
			);
			$where = array('rowid' => $userRec[0]['rowid']);
			$this->db->table('users')->update($data, $where);
			return(true);
		}
		return(false);
    }
}



	// <?php
	// $password = "password123456789012";
	// $iterations = 1000;

	// // Generate a random IV using mcrypt_create_iv(),
	// // openssl_random_pseudo_bytes() or another suitable source of randomness
	// // $salt = mcrypt_create_iv(16, MCRYPT_DEV_URANDOM);
	// $salt = 147852369;

	// $hash = hash_pbkdf2("sha256", $password, $salt, $iterations, 47);
	// echo $hash;
	// ?>