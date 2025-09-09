<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Certificate;
use Illuminate\Http\Request;

class CertificateController extends Controller
{
    public function mine(Request $request)
    {
        return Certificate::with('event')
            ->where('student_id', $request->user()->id)
            ->latest('issued_on')
            ->paginate(15);
    }
}


