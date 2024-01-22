<?php

namespace App\Http\Controllers;

use App\Helpers\Helper;
use Illuminate\Http\Request;
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
        $rowperpage = $_POST['length'];
        $columnIndex = $_POST['order'][0]['column'];
        $columnName = $_POST['columns'][$columnIndex]['data'];
        $columnSortOrder = $_POST['order'][0]['dir'];
        $searchValue = $_POST['search']['value'];

        ## Custom Field value
        $searchByName = $_POST['searchByName'];
        $searchByPartName = $_POST['searchByPartName'];
        $searchByValue = $_POST['searchByValue'];
        $searchUsingComparator = $_POST['searchUsingComparator'];

        ## Search 
        $searchQuery = " ";
        if($searchByName != ''){
            $searchQuery .= " and (pelletes.pellete_no like '%".$searchByName."%' ) ";
        }
        if($searchByPartName != ''){
            $searchQuery .= " and (products.part_name like '%".$searchByPartName."%' ) ";
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
        $searchLower = strtolower($searchValue);

        $data = DB::select("
            SELECT 
                pelletes.pellete_no,
                inventories.value AS pcs,
                inventories.weight,
                products.name AS part_name
            FROM 
                pelletes 
            INNER JOIN 
                inventories ON pelletes.id = inventories.pellete_id 
            INNER JOIN 
                products ON products.id = inventories.product_id 
            WHERE 
                1 " . $searchQuery . " 
                AND (
                    LOWER(pelletes.pellete_no) LIKE '%" . $searchLower . "%' OR
                    LOWER(inventories.value) LIKE '%" . $searchLower . "%' OR
                    LOWER(inventories.weight) LIKE '%" . $searchLower . "%' OR
                    LOWER(products.name) LIKE '%" . $searchLower . "%'
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
