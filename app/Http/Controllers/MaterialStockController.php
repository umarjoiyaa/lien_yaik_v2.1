<?php

namespace App\Http\Controllers;
use App\Helpers\Helper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
class MaterialStockController extends Controller
{

    public function index()
    {
      Helper::logSystemActivity('Material Stock', 'View Material Stock');
        return view('reports.material-stock.index');
    }

    public function get(Request $req)
    {

      $draw = $_POST['draw'];
      $row = $_POST['start'];
      $rowperpage = $_POST['length']; // Rows display per page
      $columnIndex = $_POST['order'][0]['column']; // Column index
      $columnName = $_POST['columns'][$columnIndex]['data']; // Column name
      $columnSortOrder = $_POST['order'][0]['dir']; // asc or desc
      $searchValue = $_POST['search']['value']; // Search value

      ## Custom Field value
      $searchByName = $_POST['searchByName'];
      $searchByValue = $_POST['searchByValue'];
      $searchUsingComparator = $_POST['searchUsingComparator'];

      ## Search 
      $searchQuery = " ";
      if($searchValue != ''){
        $searchQuery .= " and (materials.name like '%".$searchValue."%' ) or (material_inventories.value like '%".$searchValue."%' ) ";
      }
      if($searchByName != ''){
        $searchQuery .= " and (materials.name like '%".$searchByName."%' ) ";
      }
      if($searchByValue != ''){
        $searchQuery .= " and (material_inventories.value".$searchUsingComparator.$searchByValue.") ";
      }

      ## Total number of records without filtering
      $records = DB::select("select count(*) as allcount from materials inner join material_inventories on materials.id = material_inventories.item_id");
      $totalRecords = $records[0]->allcount;

      ## Total number of records with filtering
      $records= DB::select("select count(*) as allcount from materials inner join material_inventories on materials.id = material_inventories.item_id WHERE 1 ".$searchQuery);
      $totalRecordwithFilter = $records[0]->allcount;

      ## Fetch records
      $data = DB::select("select materials.name,material_inventories.value from materials inner join material_inventories on materials.id = material_inventories.item_id WHERE 1 ".$searchQuery." order by ".$columnName." ".$columnSortOrder." limit ".$row.",".$rowperpage);

      ## Response
      $response = array(
        "draw" => intval($draw),
        "iTotalRecords" => $totalRecords,
        "iTotalDisplayRecords" => $totalRecordwithFilter,
        "aaData" => $data,
        "destroy" => true
      );

      return json_encode($response);

    }
}
