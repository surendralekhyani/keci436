<?php 
namespace App\Controllers;
use CodeIgniter\Controller;
use App\Models\Adminrights_model;

class AdminRights_Controller extends BaseController
{
	public function index()
  {
    $model = new Adminrights_model();
    $data['records'] = $model->getData();
    $MenuRights['mr'] = $this->modelUtil->getUserRights();
    echo view('header');
    echo view('menu', $MenuRights);
    echo view('AdminRights_view', $data);
    echo view('footer');
  }      
	
  public function insert()
  {
    $model = new Adminrights_model();
    if($model->checkDuplicate($this->request) == 1)
        {
          $data = "Duplicate record...";
          echo json_encode($data);
        }
        else
        {
          $model->insertNow($this->request);
          $data['records'] = $model->getData();
          echo json_encode($data);
        }
  }


  public function delete()
  {
      $model = new Adminrights_model();
      $model->deleteNow($this->request);
      $data['records'] = $model->getData();
      echo json_encode($data);
  }
}
