<?php namespace App\Models;
use CodeIgniter\Model;

class Edititemsgroup_model extends Model 
{
    var $table = 'itemgroups';

    public function getItemGroups()
    {
        $builder = $this->db->table($this->table);
        $query = $builder->select('itemgroups.*')
                             ->where('deleted', 'N')
                             ->orderBy('itemGroupName')
                             ->get();
        return($query->getResultArray());
    }

    public function getItemGroupsForTable()
    {
        $builder = $this->db->table($this->table);
        $query = $builder->select('itemgroups.*')
                             ->where('deleted', 'N')
                             ->orderBy('itemGroupName')
                             ->get();
        return($query->getResultArray());
    }

    public function getDataForReport($request)
    {
        if( $request->getPost('itemGroupRowId') == "-1" )
        {
            $where = "1=1";
        }
        else
        {
            $where = ('items.itemGroupRowId = '. $request->getPost('itemGroupRowId'));
        }
        $builder = $this->db->table($this->table);
        $query = $builder->select('items.*, itemGroupName')
                             ->join('items', 'itemgroups.itemGroupRowId = items.itemGroupRowId')
                             ->where($where)
                             ->where('items.deleted', 'N')
                             ->orderBy('itemName')
                             ->get();
        return($query->getResultArray());
    }

    

    public function insertNow($request)
    {
        $this->db->transStart();

        $TableData = $request->getPost('TableData');
        $TableData = stripcslashes($TableData);
        $TableData = json_decode($TableData,TRUE);
        $myTableRows = count($TableData);

        for ($i=0; $i < $myTableRows; $i++) 
        {
            $data = array(
                'itemGroupRowId' => $TableData[$i]['itemGroupRowId']
            );
            $where = array('itemRowId' => $TableData[$i]['itemRowId']);
            $this->db->table('items')->update($data, $where);
        }
        $this->db->transComplete();


        // $this->db->trans_start();

        // $TableData = $this->input->post('TableData');
        // $TableData = stripcslashes($TableData);
        // $TableData = json_decode($TableData,TRUE);
        // $myTableRows = count($TableData);


        // for ($i=0; $i < $myTableRows; $i++) 
        // {
        //     $data = array(
        //         'itemGroupRowId' => $TableData[$i]['itemGroupRowId']
        //     );
        //     $this->db->where('itemRowId', $TableData[$i]['itemRowId']);
        //     $this->db->update('items', $data);
        // }
            

        // $this->db->trans_complete();
    }
}