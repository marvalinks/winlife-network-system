<?php

namespace App\Http\Controllers;

use App\Models\Agent;
use App\Models\Bonus;
use App\Models\Payment;
use App\Models\Salary;
use Barryvdh\Snappy\Facades\SnappyPdf;
use Illuminate\Http\Request;
use Knp\Snappy\Pdf;

class BonusController extends Controller
{

    public $combPeriodToday;
    public $combPeriodPrevious;
    public $accgbv;
    public $loopcount;

    public function __construct()
    {
        $this->middleware('auth');
        $this->combPeriodToday = date('Y').date('m');
        $this->combPeriodPrevious = sprintf("%02d", (date('m') - 1));
        $this->accgbv = floatval(0);
        $this->loopcount = 0;
    }

    public function printPDF(Request $request)
    {
        // ddd($request->all());
        $sponser = json_decode($request->sponser);
        $first = collect(json_decode($request->firstPreview))->pluck('member_id')->toArray();
        $second = collect(json_decode($request->secondPreview))->pluck('member_id')->toArray();
        $firstPreview = Agent::whereIn('member_id', $first)->get();
        $secondPreview = Agent::whereIn('member_id', $second)->get();
        $pdf = SnappyPdf::loadView('pages.pdfs.payment', [
            'sponser' => $sponser, 'firstPreview' => $firstPreview, 'secondPreview' => $secondPreview
        ]);
        $orientation = 'portrait';
        $paper = 'A4';
        $pdf->setOrientation($orientation)
        ->setOption('page-size', $paper)
        ->setOption('margin-bottom', '0mm')
        ->setOption('margin-top', '8.7mm')
        ->setOption('margin-right', '0mm')
        ->setOption('margin-left', '0mm')
        ->setOption('enable-javascript', true)
        ->setOption('no-stop-slow-scripts', true)
        ->setOption('enable-smart-shrinking', true)
        ->setOption('javascript-delay', 1000)
        ->setTimeout(120);
        return $pdf->inline();
        return view('pages.pdfs.payment', compact('sponser', 'firstPreview', 'secondPreview'));
    }

    public function markPayment(Request $request)
    {
        $combPeriod = date('Y').date('m');
        $sponser = json_decode($request->sponser);
        $first = collect(json_decode($request->firstPreview))->pluck('member_id')->toArray();
        $second = collect(json_decode($request->secondPreview))->pluck('member_id')->toArray();
        $firstPreview = Agent::whereIn('member_id', $first)->get();
        $secondPreview = Agent::whereIn('member_id', $second)->get();

        foreach ($firstPreview as $key => $user) {
            $bns = Bonus::findOrFail($user->currentbonus($combPeriod)->id);
            if($bns) {
                $bns->paid = 1;
                $bns->save();
            }
        }
        foreach ($secondPreview as $key => $user) {
            $bns = Bonus::findOrFail($user->currentbonus($combPeriod)->id);
            if($bns) {
                $bns->paid = 1;
                $bns->save();
            }
        }
        $request->session()->flash('alert-success', 'Bonuses successfully paid!');
        return back();
    }

    public function calculateBonus(Request $request, $userid = null)
    {
        if($userid) {
            $this->loopcount = 0;
            $user = Agent::where('member_id', $userid)->first();
            if($user && ($user->level > 2)){
                // $this->accgbv = $user->archievements->where('period', $this->combPeriodToday)->sum('total_pv') ?? floatval(0);
                $this->accgbv = floatval($user->stats->current_pbv);
                $this->doBonus($user, 0);
                $this->loopcount++;
                $this->reloop($user);
            }
        }else{
            $users = Agent::latest()->get();
            Bonus::truncate();

            foreach ($users as $key => $user) {
                $this->loopcount = 0;
                if($user->level > 2){
                    $this->accgbv = floatval($user->stats->current_pbv);
                    $this->doBonus($user, 0);
                    $this->loopcount++;
                    $this->reloop($user);
                }
            }
        }
        // $this->calculateSalary();
        $request->session()->flash('alert-success', 'Bonuses calculated for agents');
        return back();
    }
    protected function calculateSalary()
    {
        Salary::truncate();
        $users = Agent::latest()->get();
        foreach ($users as $key => $user) {
            $bonus = Bonus::where('member_id', $user->member_id)->first();
            $payments = Payment::where('member_id', $user->member_id)->sum('amount');
            Salary::create([
                'member_id' => $user->member_id, 'period' => $this->combPeriodToday,
                'amount' => floatval($bonus->amount ?? floatval(0) - $payments)
            ]);
        }
    }
    public $pug;
    public function reloop($user)
    {
        if($user->sponser) {
            $usd = Agent::where('member_id', $user->sponser->member_id)->first();
            $this->pug = $usd;
            $this->doBonus($usd, $this->loopcount);
            $this->loopcount++;
            $this->reloop($usd);
        }
    }

    protected function doBonus($user, $key)
    {
        $accgbv = $this->accgbv;
        $firstsplit = floatval(0);
        $secondsplit = floatval(0);
        $thirdsplit = floatval(0);
        $rem = floatval(0);
        $amount = floatval(0);

        $first_percent = 0;
        $second_percent = 0;
        $third_percent = 0;

        if($key > 6 || $key < 11) {
            $first_percent = 0;
            $second_percent = 0.02;
            $third_percent = 0;
        }
        if($key === 0) {
            $first_percent = 0;
            $second_percent = 0;
            $third_percent = 0.2;
        }
        if($key === 1) {
            $first_percent = 0.2;
            $second_percent = 0.25;
            $third_percent = 0.05;
        }
        if($key === 2) {
            $first_percent = 0.05;
            $second_percent = 0;
            $third_percent = 0.05;
        }
        if($key === 3) {
            $first_percent = 0.05;
            $second_percent = 0;
            $third_percent = 0.03;
        }
        if($key === 4) {
            $first_percent = 0.03;
            $second_percent = 0;
            $third_percent = 0.03;
        }
        if($key === 5) {
            $first_percent = 0.03;
            $second_percent = 0;
            $third_percent = 0.02;
        }
        if($key === 6) {
            $first_percent = 0.02;
            $second_percent = 0;
            $third_percent = 0;
        }

        if($key === 11) {
            $first_percent = 0;
            $second_percent = 0.05;
            $third_percent = 0;
        }
        if($key > 11) {
            $first_percent = 0.005;
            $second_percent = 0;
            $third_percent = 0;
        }


        if(floatval($accgbv) >= floatval(150)){
            $firstsplit = floatval(150);
            $amount += ($first_percent * 150);
            $rem = floatval($accgbv) - $firstsplit;
            if(floatval($rem) >= floatval(50)){
                $secondsplit = floatval(50);
                $amount += ($second_percent * $secondsplit);
                $rem = floatval($accgbv) - $secondsplit;
                $thirdsplit = floatval($accgbv) - $firstsplit - $secondsplit;
                $amount += ($third_percent * $thirdsplit);
            }else{
                $amount += ($first_percent * (floatval($accgbv) - $firstsplit));
            }
        }


        $bonus = Bonus::where('member_id', $user->member_id)->where('period', $this->combPeriodToday)->first();

        if(!$bonus){
            Bonus::create([
                'member_id' => $user->member_id, 'period' => $this->combPeriodToday,
                'amount' => $amount
            ]);
        }else{
            $bonus2 = Bonus::where('member_id', $user->member_id)->where('period', $this->combPeriodPrevious)->first();
            if($key > 11) {
                if($user->level > 2) {
                    $bonus->period = $this->combPeriodToday;
                    $bonus->amount = $bonus->amount + $amount;
                    $bonus->save();
                }
            }else{
                $bonus->period = $this->combPeriodToday;
                $bonus->amount = $bonus->amount + $amount;
                $bonus->save();
            }
        }
    }
}
