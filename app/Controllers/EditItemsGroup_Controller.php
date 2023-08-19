<?php 
namespace App\Controllers;
use CodeIgniter\Controller;
use App\Models\Edititemsgroup_model;

class EditItemsGroup_Controller extends BaseController
{
	public function index()
	{
		$model = new Edititemsgroup_model();
		$data['itemGroups'] = $model->getItemGroups();
		$data['itemGroupsForTable'] = $model->getItemGroupsForTable();
		$MenuRights['mr'] = $this->modelUtil->getUserRights();
        $data['errMsg'] = "";
		echo view('header');
		echo view('menu', $MenuRights);
        echo view('EditItemsGroup_view', $data);
		echo view('footer');
	}  

	public function showData()
	{
		$timeStart = microtime(TRUE);

		$model = new Edititemsgroup_model();
		$data['records'] = $model->getDataForReport($this->request);

		$timeEnd = microtime(TRUE);
		$data['timeTook'] = round( $timeEnd - $timeStart, 3 ) ;
		echo json_encode($data);
	}


	public function saveData()
	{
		$model = new Edititemsgroup_model();
		$model->insertNow($this->request);
	}
}
