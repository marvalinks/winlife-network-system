<?php

namespace App\Http\Controllers;

use App\Models\Achivement;
use App\Models\Agent;
use Illuminate\Http\Request;

class AdminController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function dashboard(Request $request)
    {
        return view('pages.dashboard');
    }
    public function deleteDBS(Request $request)
    {
        return view('others.delete-dbs');
    }
    public function postdeleteDBS(Request $request)
    {
        $data = $request->validate([
            'type' => 'required', 'period' => 'required'
        ]);

        if($data['type'] == 'a') {
            $acs = Achivement::where('period', $data['period'])->get();
            foreach ($acs as $key => $ac) {
               $ac->delete();
            }
        }
        $request->session()->flash('alert-success', 'Data successfully deleted!');
        return back();
    }


}
