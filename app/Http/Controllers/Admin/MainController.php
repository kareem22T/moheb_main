<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use App\Models\Term;

class MainController extends Controller
{
    public function getExcelData() {
        $file = public_path("/Excel/moheb-add-terms.csv");
        $cvsData = file_get_contents($file);
        $rows = array_map('str_getcsv', explode( "\n", $cvsData));
        $header =  array_shift($rows);
        return $rows;
        // return file($file);
        // return $data = Excel::toArray([], $file);
        return Excel::import(new Term, $file);
        // Specify the column and row names
        $columnName = 'your_column_name';
        $rowName = 'your_row_name';

        // Retrieve data using column and row names
        $cellValue = $data->first(function ($row) use ($columnName, $rowName) {
            return $row->$columnName == $rowName;
        })->$columnName;

        return $cellValue;
    }
}
