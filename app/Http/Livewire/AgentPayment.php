<?php

namespace App\Http\Livewire;

use App\Models\Agent;
use Livewire\Component;

class AgentPayment extends Component
{
    public $sponser;
    public $sponsers;
    public $selectedAgents = [];
    public $selectAll = false;
    public $bulkDisabled = true;
    public $firstPreview;
    public $secondPreview;
    public $showData = false;

    public function mount($sponser, $sponsers)
    {
        $this->sponser = $sponser;
        $this->sponsers = $sponsers;
        $this->selectedAgents = collect();
        $this->firstPreview = collect();
        $this->secondPreview = collect();
    }
    public function paySelected()
    {
        // Agent::query()->whereIn('member_id', $this->selectedAgents)->delete();
        ddd($this->selectedAgents);
        $this->selectedAgents = [];
        $this->selectAll = false;
    }
    public function updatedSelectAll($value)
    {
        if($value) {
            $this->selectedAgents = $this->sponsers->pluck('member_id');
            $this->selectedAgents->push($this->sponser->member_id);
        }else{
            $this->selectedAgents = [];
        }
    }
    public function preview()
    {
        $aa = $this->selectedAgents;
        $fs = [];
        $ss = [];
        foreach ($aa as $key => $a) {
            if($key % 2 == 0) {
                $fs = array_merge($fs, [$a]);
            }else{
                $ss = array_merge($ss, [$a]);
            }
        }
        $this->firstPreview = Agent::whereIn('member_id', $fs)->get();
        $this->secondPreview = Agent::whereIn('member_id', $ss)->get();
        $this->showData = true;
        // ddd($this->firstPreview, $this->secondPreview);
    }
    public function render()
    {
        $this->bulkDisabled = count($this->selectedAgents) < 1;
        return view('livewire.agent-payment');
    }
}
