<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Bus;
use Illuminate\Support\Facades\DB;

class BatchProgressController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(Request $request)
    {
        $batches = [];
        $bb = DB::table('job_batches')->latest()->get();
        foreach ($bb as $key => $b) {
            $batches[] = Bus::findBatch($b->id);
        }
        return view('others.batch-list', compact('batches'));
    }
}
