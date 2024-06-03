<?php

namespace App\Http\Controllers;

use App\Models\AccountTeacher;
use Illuminate\Http\Request;

class AccountTeacherController extends Controller
{

    public function index()
    {
        // $data = AccountTeacher::all();
        return view('account.teacher.index',
        // ['accountteacher' => $data]
    );
    }
}
