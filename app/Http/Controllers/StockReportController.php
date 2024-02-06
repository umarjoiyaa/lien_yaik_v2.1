<?php

namespace App\Http\Controllers;

use App\Helpers\Helper;
use Illuminate\Support\Facades\DB;

class StockReportController extends Controller
{
    public function index()
    {
      Helper::logSystemActivity('Stock Report', 'View Stock Report');
        return view('reports.stock-report.index');
    }

    public function get()
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
        $searchByPartName = $_POST['searchByPartName'];
        $searchByValue = $_POST['searchByValue'];
        $searchUsingComparator = $_POST['searchUsingComparator'];

        ## Search 
        $searchQuery = " ";
        if($searchValue != ''){
            $searchQuery .= " and (pelletes.pellete_no like '%".$searchValue."%' ) or (products.name like '%".$searchValue."%' ) or (inventories.value like '%".$searchValue."%' ) or (inventories.weight like '%".$searchValue."%' ) ";
        }
        if($searchByName != ''){
            $searchQuery .= " and (pelletes.pellete_no like '%".$searchByName."%' ) ";
        }
        if($searchByPartName != ''){
            $searchQuery .= " and (products.name like '%".$searchByPartName."%' ) ";
        }
        if($searchByValue != ''){
            $searchQuery .= " and (inventories.value".$searchUsingComparator.$searchByValue.") ";
        }

        ## Total number of records without filtering
        $records = DB::select("select count(*) as allcount from pelletes inner join inventories on pelletes.id = inventories.pellete_id inner join products on products.id = inventories.product_id");
        $totalRecords = $records[0]->allcount;

        ## Total number of records with filtering
        $records= DB::select("select count(*) as allcount from pelletes inner join inventories on pelletes.id = inventories.pellete_id inner join products on products.id = inventories.product_id WHERE 1 ".$searchQuery);
        $totalRecordwithFilter = $records[0]->allcount;

        ## Fetch records
        $data = DB::select("select pelletes.pellete_no,inventories.value,inventories.weight,products.name from pelletes inner join inventories on pelletes.id = inventories.pellete_id inner join products on products.id = inventories.product_id WHERE 1 ".$searchQuery." order by ".$columnName." ".$columnSortOrder." limit ".$row.",".$rowperpage);

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
