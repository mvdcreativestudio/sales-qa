<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AccountingController extends Controller
{

    public function receipts()
    {
        return view('content.accounting.receipts');
    }

    public function entries()
    {
        return view('content.accounting.entries');
    }

    public function entrie()
    {
        return view('content.accounting.entrie');
    }
}
