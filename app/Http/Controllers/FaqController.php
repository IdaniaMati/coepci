<?php

namespace App\Http\Controllers;
use App\Models\Faq;
use App\Models\User;
use Illuminate\Http\Request;

class FaqController extends Controller
{
    public function showFaqForm()
    {
        return view('admin.faq');
    }

}
