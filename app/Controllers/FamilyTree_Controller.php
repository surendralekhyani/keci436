<?php 
namespace App\Controllers;
use CodeIgniter\Controller;
use App\Models\Familytree_model;

class FamilyTree_Controller extends BaseController
{
	public function index()
	{
		$model = new Familytree_model();
		// $data['familyList'] = $model->getFamilyList();
		$data['records'] = $model->getDataLimit();
		$MenuRights['mr'] = $this->modelUtil->getUserRights();
        $data['errMsg'] = "";
		// echo view('header');
		// echo view('menu', $MenuRights);
        echo view('FamilyTree_view', $data);
		// echo view('footer');
	}  




}
