<?php

namespace App\Http\Controllers;

use App\Http\Services\BonusService;
use App\Http\Services\GroupService;
use App\Http\Services\LevelService;
use App\Http\Services\StatisticLogService;
use App\Jobs\CalculateBonus;
use App\Jobs\LevelServiceJob;
use App\Jobs\StatisticLogJob;
use App\Models\Achivement;
use App\Models\Agent;
use App\Models\BigAgent;
use App\Models\Bonus;
use App\Models\Payment;
use App\Models\Salary;
use App\Models\StatisticLog;
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
        $this->combPeriodToday = date('Y') . date('m');
        $this->combPeriodPrevious = sprintf("%02d", (date('m') - 1));
        $this->accgbv = floatval(0);
        $this->loopcount = 0;
    }

    public function printPDF(Request $request)
    {
        // ddd($request->all());
        if (!$request->agents || count($request->agents) < 1) {
            $request->session()->flash('alert-danger', 'No Agent selected!');
            return back();
        }
        $sps = json_decode($request->sponser);
        $arr = [strval($sps)];
        $arr = array_merge_recursive($arr, $request->agents);
        $fs = [];
        $ss = [];
        foreach ($arr as $key => $a) {
            if ($key % 2 == 0) {
                $fs = array_merge($fs, [$a]);
            } else {
                $ss = array_merge($ss, [$a]);
            }
        }
        $firstPreview = Agent::whereIn('member_id', $fs)->get();
        $secondPreview = Agent::whereIn('member_id', $ss)->get();
        $sponser = Agent::where('member_id', $sps)->first();
        $combPeriod = $request->period;

        if ($request->type === "b") {
            $sponsers = Agent::whereIn('member_id', $arr)->get();
            $user =  BigAgent::where('member_id', $sps)->where('level', 0)->first();
            if ($user) {
                if (intval($user->period) > intval($combPeriod)) {
                    $request->session()->flash('alert-danger', 'Member ID not found in for this period!');
                    return redirect()->route('admin.agents');
                }
                $sponsers =  BigAgent::where('parent_id', $sps)->where('period', '<=', $combPeriod)->orderBy('level', 'asc')->simplePaginate(20);

                $user = $user;
                // return view('pages.pdfs.cpo', [
                //     'user' => $user, 'sponsers' => $sponsers,
                //     'combPeriod' => $combPeriod
                // ]);
                $pdf = SnappyPdf::loadView('pages.pdfs.cpo', [
                    'user' => $user, 'sponsers' => $sponsers,
                    'combPeriod' => $combPeriod
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

                $name = $request->period . '-' . $user->member_id . '.pdf';
                return $pdf->download($name);
            } else {
                $request->session()->flash('alert-danger', 'Member ID not found in system!');
                return redirect()->route('admin.agents');
            }
        } else {
            $pdf = SnappyPdf::loadView('pages.pdfs.payment', [
                'sponser' => $sponser, 'firstPreview' => $firstPreview, 'secondPreview' => $secondPreview,
                'combPeriod' => $combPeriod
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

            $name = $request->period . '-' . $firstPreview[0]->member_id . '.pdf';
            return $pdf->download($name);
        }




        // return view('pages.pdfs.payment', [
        //     'sponser' => $sponser, 'firstPreview' => $firstPreview, 'secondPreview' => $secondPreview,
        //     'combPeriod' => $combPeriod
        // ]);
        // return $pdf->inline();

    }
    public function printPDF2(Request $request)
    {
        if (!$request->agents || count($request->agents) < 1) {
            $request->session()->flash('alert-danger', 'No Agent selected!');
            return back();
        }
        $sps = json_decode($request->sponser);
        $arr = [strval($sps)];
        $arr = array_merge_recursive($arr, $request->agents);
        $fs = [];
        $ss = [];
        foreach ($arr as $key => $a) {
            if ($key % 2 == 0) {
                $fs = array_merge($fs, [$a]);
            } else {
                $ss = array_merge($ss, [$a]);
            }
        }
        $firstPreview = Agent::whereIn('member_id', $fs)->get();
        $secondPreview = Agent::whereIn('member_id', $ss)->get();
        $sponser = Agent::where('member_id', $sps)->first();
        $combPeriod = $request->period;
        $pdf = SnappyPdf::loadView('pages.pdfs.payment', [
            'sponser' => $sponser, 'firstPreview' => $firstPreview, 'secondPreview' => $secondPreview,
            'combPeriod' => $combPeriod
        ]);
        // ddd($first, $second, $this->combPeriodToday);

        $salaries = Salary::whereIn('member_id', $arr)->where('period', $request->period)->get();
        foreach ($salaries as $key => $salary) {
            $salary->paid = 1;
            $salary->save();
        }
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

        // return view('pages.pdfs.payment', [
        //     'sponser' => $sponser, 'firstPreview' => $firstPreview, 'secondPreview' => $secondPreview,
        //     'combPeriod' => $combPeriod
        // ]);
        // return $pdf->inline();
        $name = $request->period . '-' . $firstPreview[0]->member_id . '.pdf';
        return $pdf->download($name);
    }

    public function markPayment(Request $request)
    {
        $combPeriod = date('Y') . date('m');
        $sponser = json_decode($request->sponser);
        $first = collect(json_decode($request->firstPreview))->pluck('member_id')->toArray();
        $second = collect(json_decode($request->secondPreview))->pluck('member_id')->toArray();
        $firstPreview = Agent::whereIn('member_id', $first)->get();
        $secondPreview = Agent::whereIn('member_id', $second)->get();

        foreach ($firstPreview as $key => $user) {
            $bns = Bonus::findOrFail($user->currentbonus($combPeriod)->id);
            if ($bns) {
                $bns->paid = 1;
                $bns->save();
            }
        }
        foreach ($secondPreview as $key => $user) {
            $bns = Bonus::findOrFail($user->currentbonus($combPeriod)->id);
            if ($bns) {
                $bns->paid = 1;
                $bns->save();
            }
        }
        $request->session()->flash('alert-success', 'Bonuses successfully paid!');
        return back();
    }

    public function calculateBonus(Request $request, $userid = null)
    {
        $this->dispatch(new LevelServiceJob($this->combPeriodToday));
        // StatisticLog::truncate();
        // Salary::truncate();
        $grp = new GroupService();
        $grp->GRP();
        $acs = Achivement::distinct('period')->orderBy('period', 'asc')->pluck('period');
        // Salary::truncate();
        if (count($acs) > 0) {
            foreach ($acs as $key => $ac) {
                $this->dispatch(new StatisticLogJob($ac));
                $this->dispatch(new CalculateBonus($ac));

                // $st = new StatisticLogService();
                // $st->ABP($ac);

                // $bns = new BonusService();
                // $bns->calculateBonus($ac);
            }
        }
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
        if ($user->sponser) {
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

        if ($key > 6 || $key < 11) {
            $first_percent = 0;
            $second_percent = 0.02;
            $third_percent = 0;
        }
        if ($key === 0) {
            $first_percent = 0;
            $second_percent = 0;
            $third_percent = 0.2;
        }
        if ($key === 1) {
            $first_percent = 0.2;
            $second_percent = 0.25;
            $third_percent = 0.05;
        }
        if ($key === 2) {
            $first_percent = 0.05;
            $second_percent = 0;
            $third_percent = 0.05;
        }
        if ($key === 3) {
            $first_percent = 0.05;
            $second_percent = 0;
            $third_percent = 0.03;
        }
        if ($key === 4) {
            $first_percent = 0.03;
            $second_percent = 0;
            $third_percent = 0.03;
        }
        if ($key === 5) {
            $first_percent = 0.03;
            $second_percent = 0;
            $third_percent = 0.02;
        }
        if ($key === 6) {
            $first_percent = 0.02;
            $second_percent = 0;
            $third_percent = 0;
        }

        if ($key === 11) {
            $first_percent = 0;
            $second_percent = 0.05;
            $third_percent = 0;
        }
        if ($key > 11) {
            $first_percent = 0.005;
            $second_percent = 0;
            $third_percent = 0;
        }


        if (floatval($accgbv) >= floatval(150)) {
            $firstsplit = floatval(150);
            $amount += ($first_percent * 150);
            $rem = floatval($accgbv) - $firstsplit;
            if (floatval($rem) >= floatval(50)) {
                $secondsplit = floatval(50);
                $amount += ($second_percent * $secondsplit);
                $rem = floatval($accgbv) - $secondsplit;
                $thirdsplit = floatval($accgbv) - $firstsplit - $secondsplit;
                $amount += ($third_percent * $thirdsplit);
            } else {
                $amount += ($first_percent * (floatval($accgbv) - $firstsplit));
            }
        }

        // if($user->member_id == "202110141234") {
        //     ddd($user);
        // }

        $bonus = Bonus::where('member_id', $user->member_id)->where('period', $this->combPeriodToday)->first();
        if (!$bonus) {
            Bonus::create([
                'member_id' => $user->member_id, 'period' => $this->combPeriodToday,
                'amount' => $amount
            ]);
        } else {
            $bonus2 = Bonus::where('member_id', $user->member_id)->where('period', $this->combPeriodPrevious)->first();
            if ($key > 11) {
                if ($user->level > 2) {
                    $bonus->period = $this->combPeriodToday;
                    $bonus->amount = $bonus->amount + $amount;
                    $bonus->save();
                }
            } else {
                $bonus->period = $this->combPeriodToday;
                $bonus->amount = $bonus->amount + $amount;
                $bonus->save();
            }
        }
    }
}
