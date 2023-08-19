<?php namespace App\Models;
use CodeIgniter\Model;

class Organisation_model extends Model 
{
    var $table = 'organisation';
	protected $primaryKey = 'id';
	protected $allowedFields = ['orgName','add1','add2','add3','add4','electricianNo','rechargeLimit','rechargeMobile'];

	protected $validationRules    = [
		
        'orgName'     => 'required|min_length[3]|max_length[100]',
        // 'email'        => 'required|valid_email|is_unique[users.email]',
        // 'password'     => 'required|min_length[8]|alpha_numeric_space',regex_match[/^[a-z0-9]+$/]
        // 'pass_confirm' => 'required_with[password]|matches[password]'
    ];

    protected $validationMessages = [
        'orgName'        => [
            'required' => 'Can not be blank',
            'min_length' => 'Minimum 3 chars',
            'max_length' => 'Maximum 100 chars',
        ]
    ];

    public function getOrganisation()
	{
		$query = $this->db->query('select * from organisation');
		return($query->getResultArray());
	}
}