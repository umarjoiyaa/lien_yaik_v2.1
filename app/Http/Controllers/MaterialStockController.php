<?php

namespace App\Http\Controllers;
use App\Helpers\Helper;
use Illuminate\Http\Request;
use App\Models\MaterialInventory;
use Illuminate\Support\Facades\DB as FacadesDB;

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
      $rowperpage = $_POST['length'];
      $columnIndex = $_POST['order'][0]['column'];
      $columnName = $_POST['columns'][$columnIndex]['data'];
      $columnSortOrder = $_POST['order'][0]['dir'];
      $searchValue = $_POST['search']['value'];

      ## Custom Field value
      $searchByName = $_POST['searchByName'];
      $searchByValue = $_POST['searchByValue'];
      $searchUsingComparator = $_POST['searchUsingComparator'];

      ## Search 
      $searchQuery = " ";
      if($searchByName != ''){
        $searchQuery .= " and (materials.name like '%".$searchByName."%' ) ";
      }
      if($searchByValue != ''){
        $searchQuery .= " and (material_inventories.value".$searchUsingComparator.$searchByValue.") ";
      }

      ## Total number of records without filtering
      $records = FacadesDB::select("select count(*) as allcount from materials inner join material_inventories on materials.id = material_inventories.item_id");
      $totalRecords = $records[0]->allcount;

      ## Total number of records with filtering
      $records= FacadesDB::select("select count(*) as allcount from materials inner join material_inventories on materials.id = material_inventories.item_id WHERE 1 ".$searchQuery);
      $totalRecordwithFilter = $records[0]->allcount;

      ## Fetch records
      $searchLower = strtolower($searchValue);

      $data = FacadesDB::select("
          SELECT 
              materials.name,
              material_inventories.value 
          FROM 
              materials 
          INNER JOIN 
              material_inventories ON materials.id = material_inventories.item_id 
          WHERE 
              1 " . $searchQuery . " 
              AND (
                  LOWER(materials.name) LIKE '%" . $searchLower . "%' OR
                  LOWER(material_inventories.value) LIKE '%" . $searchLower . "%'
              ) 
          ORDER BY 
              " . $columnName . " " . $columnSortOrder . " 
          LIMIT 
              " . $row . "," . $rowperpage
      );

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
