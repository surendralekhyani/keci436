<?php namespace App\Models;
use CodeIgniter\Model;

class Customers_model extends Model 
{
	var $table = 'customers';
	protected $primaryKey = 'customerRowId';
	// protected $useAutoIncrement = false;
	protected $allowedFields = ['customerName','address','mobile1','mobile2','remarks','remarks2', 'createdBy', 'createdStamp', 'deleted', 'deletedBy', 'deletedStamp'];

	protected $validationRules    = [
		
        'customerName'     => 'required|min_length[1]|max_length[150]', //|is_unique[customers.customerName]
        'mobile1'        => 'required|min_length[10]|max_length[10]',
    ];

    protected $validationMessages = [
        'customerName'        => [
            'required' => 'Can not be blank',
            'min_length' => 'Minimum 1 chars',
            'max_length' => 'Maximum 150 chars',
            'is_unique' => 'Duplicate name..',
		],
        'mobile1'        => [
            'required' => 'Can not be blank',
            'min_length' => 'Minimum 10 chars',
            'max_length' => 'Maximum 10 chars',
		],
    ];
}