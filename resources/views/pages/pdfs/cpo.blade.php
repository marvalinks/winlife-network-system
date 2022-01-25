@extends('pages.layouts.pdf')

@section('links')
<style>
    .u90 h2 {
        text-align: center;
        font-size: 18px;
        margin-bottom: 24px;
        text-transform: uppercase;
    }

    .u90 span.e4 {
        font-weight: 600;
        text-transform: uppercase;
        font-size: 12px;
    }

    .u90 h6 {
        font-size: 15px;
        margin-top: 24px;
    }

    #main-content {
        width: 85%;
        margin: auto;
    }

    .p56 {
        /* display: flex;
        display: -webkit-flex; */
        display: -webkit-box;
        flex-direction: row;
        justify-content: space-between;
        -webkit-flex-direction: row;
        -webkit-justify-content: space-between;
    }

    .p56>.row {
        -webkit-box-flex: 1;
        -webkit-flex: 1;
        flex: 1;
        margin-right: 10%;
    }

    .w50 {
        width: 100%;
    }

    .table th,
    .table td {
        padding: 3px !important;
        font-size: 11px;
        border: 1px solid #000 !important;
    }

    .tright {
        text-align: right;
    }
</style>
@endsection
@section('content')
@php
$lv = 1;
$lvi = 1;
$lvf = 2;
@endphp
<div class="row w94 u90">
    <h2><b>{{$combPeriod}} {{env('APP_NAME')}} Network = {{$user->firstname.' '.$user->lastname}}</b></h2>
    <div class="row p56">
        <div class="span12 w50">
            <table class="table table-bordered table-hover">
                <thead>
                    <tr>
                        <th style="width: 700px;">Name</th>
                        <th style="width: 125px;">Period</th>
                        <th style="width: 122px;">Business.ID</th>
                        <th style="width: 90px;">Layer</th>
                        <th style="width: 90px;">Level</th>
                        <th style="width: 100px;">CurrentPBV</th>
                        <th style="width: 100px;">CurrentGBV</th>
                        <th style="width: 183px;">ACCPBV</th>
                        <th style="width: 183px;">ACCGBV</th>
                        <th style="width: 122px;">Sponser.ID</th>
                        <th style="width: 100px;">Salary</th>

                    </tr>
                </thead>
                <tbody role="alert" aria-live="polite" aria-relevant="all">
                    @if (isset($user))
                    <tr class="gradeX even">
                        <td>{{$user->firstname.' '.$user->lastname}}</td>
                        <td>{{$user->period}}</td>
                        <td>{{$user->member_id}}</td>
                        <td>0</td>
                        <td>{{$user->statlogs->where('period', $combPeriod)->first()->level ?? 'NA'}}</td>
                        <td>{{number_format($user->archievements->where('period', $combPeriod)->sum('total_pv') ?? floatval(0),2)}}</td>
                        <td>{{number_format($user->currentgbv($combPeriod), 2)}}</td>
                        @if (intval($combPeriod) >= intval($user->archievements->min('period')))
                        <td>{{number_format($user->archievements->whereBetween('period', [$user->archievements->min('period'), $combPeriod])->sum('total_pv') ?? floatval(0), 2)}}</td>
                        @else
                        <td>{{number_format(floatval(0), 2)}}</td>
                        @endif
                        <td>{{number_format($user->accgbv($combPeriod), 2)}}</td>
                        <td>{{$user->sponser_id ?? '-'}}</td>
                        <td class="{{($user->currentsalary($combPeriod) && $user->currentsalary($combPeriod)->active) ? '' : 'tred'}}">{{number_format(($user->currentsalary($combPeriod)->amount ?? 0), 2)}}</td>
                    </tr>
                    @foreach ($sponsers as $key => $sponser)
                    <tr class="gradeX {{($key+1) % 2 == 0 ? 'even' : 'odd'}}">
                        <td>{{$sponser->firstname.' '.$sponser->lastname}}</td>
                        <td>{{$sponser->period}}</td>
                        <td>{{$sponser->member_id}}</td>
                        <td>{{$sponser->level}}</td>
                        <td>{{$sponser->statlogs->where('period', $combPeriod)->first()->level ?? 'NA'}}</td>
                        <td>{{number_format($sponser->archievements->where('period', $combPeriod)->sum('total_pv') ?? floatval(0),2)}}</td>
                        <td>{{number_format($sponser->currentgbv($combPeriod), 2)}}</td>
                        @if (intval($combPeriod) >= intval($sponser->archievements->min('period')))
                        <td>{{number_format($sponser->archievements->whereBetween('period', [$sponser->archievements->min('period'), $combPeriod])->sum('total_pv') ?? floatval(0), 2)}}</td>
                        @else
                        <td>{{number_format(floatval(0), 2)}}</td>
                        @endif
                        <td>{{number_format($sponser->accgbv($combPeriod), 2)}}</td>
                        <td>{{$sponser->sponser_id ?? '-'}}</td>
                        <td class="{{($sponser->currentsalary($combPeriod) && $sponser->currentsalary($combPeriod)->active) ? '' : 'tred'}}">{{number_format(($sponser->currentsalary($combPeriod)->amount ?? 0), 2)}}</td>
                    </tr>
                    @endforeach
                    @endif

                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
