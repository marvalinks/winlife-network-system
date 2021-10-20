<?php

namespace App\Http\Controllers;

use App\Imports\AgentTempImport;
use App\Imports\ArchievementTempImport;
use App\Models\Achivement;
use App\Models\Agent;
use App\Models\TemporalAchivement;
use App\Models\TemporalAgent;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

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

    public function uploadRegistration(Request $request)
    {
        return view('others.uploads.upload-registration');
    }
    public function uploadAchivement(Request $request)
    {
        return view('others.uploads.upload-achivement');
    }
    public function postuploadRegistration(Request $request)
    {
        $data = $request->validate([
            'file' => 'required|mimes:xlsx,csv,xls',
        ]);
        try {
            Excel::import(new AgentTempImport(), $data['file']);
        } catch (\Throwable $th) {
            return back()->withError('There was a problem with your excel file.');
        }
        $this->fixTemp();
        $exports = TemporalAgent::latest()->get();
        return view('others.uploads.upload-registration', compact('exports'));
    }
    public function postuploadAchivement(Request $request)
    {
        $data = $request->validate([
            'file' => 'required|mimes:xlsx,csv,xls',
        ]);
        try {
            Excel::import(new ArchievementTempImport(), $data['file']);
        } catch (\Throwable $th) {
            return back()->withError('There was a problem with your excel file.');
        }
        $this->fixTemp();
        $aexports = TemporalAchivement::latest()->get();
        return view('others.uploads.upload-achivement', compact('aexports'));
    }

    protected function fixTemp()
    {
        $dels = TemporalAgent::where('member_id', '')->orWhereNull('member_id')->get();
        $ass = TemporalAchivement::where('member_id', '')->orWhereNull('member_id')->get();
        foreach ($dels as $key => $del) {
            $del->delete();
        }
        foreach ($ass as $key => $del) {
            $del->delete();
        }

    }


}
