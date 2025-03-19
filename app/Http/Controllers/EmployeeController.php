<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class EmployeeController extends Controller
{
    public function employee_page()
    {
        return view('employee.employee_page');
    }
}
