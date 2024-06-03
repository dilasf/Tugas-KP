<?php

namespace App\Http\Controllers;

use App\Models\AccountStudent;
use Illuminate\Http\Request;

class AccountStudentController extends Controller
{

    public function index()
    {
        // $data = AccountStudent::all();
        return view('account.student.index',
        // ['accountstudent' => $data]
    );
    }
}
