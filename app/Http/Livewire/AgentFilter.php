<?php

namespace App\Http\Livewire;

use App\Imports\AgentImport;
use App\Imports\ArchievementImport;
use App\Models\Agent;
use App\Models\AgentStatistics;
use Livewire\Component;
use Livewire\WithFileUploads;
use Maatwebsite\Excel\Facades\Excel;

class AgentFilter extends Component
{

    public $sponsers;
    public $user;
    public $memberid = "";
    public $showtable = false;
    public $excelfile;
    public $excelLoading = false;
    public $excelLoadingSuccess = false;
    public $achfile;
    public $months;
    public $selectedYear = "2021";
    public $selectedMonth = "10";
    public $combPeriod;
    public $combPeriodToday;

    public $currentGBV = 0.0;
    public $ACCGBV = 0.0;

    use WithFileUploads;

    public function mount()
    {
        $this->sponsers = Agent::latest()->get();
        $this->combPeriodToday = date('Y').date('m');
        $this->months = [
            'January' => '01','February' => '02','March' => '03',
            'April' => '04','May' => '05','June' => '06','July' => '07',
            'August' => '08','September' => '09','October' => '10',
            'November' => '11','December' => '12'
        ];
        $this->memberid = Agent::first()->member_id ?? '';
        $this->fixSponsers();
    }

    public $showReg = false;
    public $showArch = false;

    public function switchView($type)
    {
        switch ($type) {
            case 'r':
                $this->showReg = true;
                $this->showArch = false;
                break;
            case 'a':
                $this->showReg = false;
                $this->showArch = true;
                break;

            default:
                $this->showReg = false;
                $this->showArch = false;
                break;
        }
    }

    public function uploadExcel()
    {
        $this->excelLoadingSuccess = false;
        $this->excelLoading = true;
        $this->validate([
            'excelfile' => 'required|mimes:xlsx,csv,xls',
        ]);
        try {
            Excel::import(new AgentImport(), $this->excelfile);
            $this->fixSponsers();
        } catch (\Throwable $th) {
            return back()->withError('There was a problem with your excel file.');
        }

        $this->excelfile = null;
        $this->excelLoadingSuccess = true;
        $this->excelLoading = false;
        $this->fixSponsers();
    }
    public function uploadAchievement()
    {
        $this->excelLoadingSuccess = false;
        $this->excelLoading = true;
        $this->validate([
            'achfile' => 'required|mimes:xlsx,csv,xls',
        ]);
        try {
            Excel::import(new ArchievementImport(), $this->achfile);
            $this->fixSponsers();
        } catch (\Throwable $th) {
            return back()->withError('There was a problem with your excel file.');
        }

        $this->achfile = null;
        $this->excelLoadingSuccess = true;
        $this->excelLoading = false;
        $this->fixSponsers();

    }
    public function fixSponsers()
    {
        $dels = Agent::where('member_id', '')->orWhereNull('member_id')->get();
        $stats = AgentStatistics::where('agent_id', '')->orWhereNull('agent_id')->get();
        $us = Agent::first();
        foreach ($dels as $key => $del) {
            $del->delete();
        }
        foreach ($stats as $key => $del) {
            $del->delete();
        }
        $agents = Agent::with(['sponsers'])->latest()->get();
        if($us) {
            foreach ($agents as $key => $agent) {
                if($agent->member_id != $us->member_id) {
                    if(!$agent->sponser) {
                        $agent->sponser_id = $us->member_id;
                        $agent->save();
                        $agent->stats->sponser_id = $us->member_id;
                        $agent->stats->save();
                    }
                }
            }
        }
        
    }
    public function search()
    {
        $id = $this->memberid;
        $this->combPeriod = $this->selectedYear.''. $this->selectedMonth;
        $agents =  Agent::with(['childrenSponsers'])->where('sponser_id', $id)->get();
        $user =  Agent::with(['grandchildren','childrenSponsers', 'archievements', 'sponsers'])->where('member_id', $this->memberid)->first();
        
        if($user) {
            $this->user = $user;
            $this->sponsers = $agents;
            $this->showtable = true;
        }else{
            $this->showtable = false;
        }
        // ddd($user->archievements->min('period'));

        $this->currentGBV = $user->archievements->where('period', $this->combPeriod)->sum('total_pv') ?? floatval(0);
        $this->ACCGBV = $user->archievements->whereBetween('period', [$user->archievements->min('period'), $this->combPeriod])->sum('total_pv') ?? floatval(0);
        // $this->ACCGBV = 0;

        foreach ($agents as $key => $sponser) {
            // ddd($sponser->currentgbv($this->combPeriod));
            //level 1
            $this->currentGBV += $sponser->archievements->where('period', $this->combPeriod)->sum('total_pv') ?? floatval(0);
            $this->ACCGBV += $sponser->archievements->whereBetween('period', [$sponser->archievements->min('period'), $this->combPeriod])->sum('total_pv') ?? floatval(0);
            foreach ($sponser->childrenSponsers as $k => $child_sponser) {
                //level2
                $this->currentGBV += $child_sponser->archievements->where('period', $this->combPeriod)->sum('total_pv') ?? floatval(0);
                $this->ACCGBV += $child_sponser->archievements->whereBetween('period', [$child_sponser->archievements->min('period'), $this->combPeriod])->sum('total_pv') ?? floatval(0);
                $this->reloop($child_sponser);
            }
        }
        // ddd($this->currentGBV);
        $this->dispatchBrowserEvent('reopenDatatable');
    }
    public function reloop($child_sponser)
    {
        if ($child_sponser->sponsers) {
            foreach ($child_sponser->sponsers as $k => $childrenSponser) {
                // $this->currentGBV += 1;
                $this->currentGBV += $childrenSponser->archievements->where('period', $this->combPeriod)->sum('total_pv') ?? floatval(0);
                $this->ACCGBV += $childrenSponser->archievements->whereBetween('period', [$childrenSponser->archievements->min('period'), $this->combPeriod])->sum('total_pv') ?? floatval(0);
                // $this->ACCGBV += 1;
                $this->reloop($childrenSponser);
            }
        }
    }

    public function render()
    {
        return view('livewire.agent-filter');
    }
}
