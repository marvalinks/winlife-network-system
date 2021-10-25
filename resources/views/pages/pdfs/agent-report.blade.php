@extends('pages.layouts.pdf')

@section('links')
<style>
    .u90 h2{
        text-align: center;
        font-size: 18px;
        margin-bottom: 24px;
        text-transform: uppercase;
    }
    .u90 span.e4{
        font-weight: 600;
        text-transform: uppercase;
        font-size: 12px;
    }
    .u90 h6{
        font-size: 15px;
        margin-top: 24px;
    }
    #main-content{
        width: 85%;
        margin: auto;
    }
    .p56{
        /* display: flex;
        display: -webkit-flex; */
        display: -webkit-box;
        flex-direction: row;
        justify-content: space-between;
        -webkit-flex-direction: row;
        -webkit-justify-content: space-between;
    }
    .p56 > .row{
        -webkit-box-flex: 1;
        -webkit-flex: 1;
        flex: 1;
        margin-right: 10%;
    }
    .w50{
        width: 100%;
    }
    .table th, .table td {
        padding: 3px!important;
        font-size: 11px;
        border: 1px solid #000!important;
    }
    .tright{
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
        <h2><b>{{$combPeriod}} {{env('APP_NAME')}} Network = {{$sponser->firstname.' '.$sponser->lastname}}</b></h2>
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
                        <tr class="gradeX even">
                            <td>{{$sponser->firstname.' '.$sponser->lastname}}</td>
                            <td>{{$sponser->period}}</td>
                            <td>{{$sponser->member_id}}</td>
                            <td>0</td>
                            <td>{{$sponser->statlogs->where('period', $combPeriod)->first()->level ?? $sponser->stats->level}}</td>
                            <td class="tright">{{number_format($sponser->archievements->where('period', $combPeriod)->sum('total_pv') ?? floatval(0),2)}}</td>
                            <td class="tright">{{number_format($sponser->currentgbv($combPeriod), 2)}}</td>
                            @if (intval($combPeriod) >= intval($sponser->archievements->min('period')))
                            <td class="tright">{{number_format($sponser->archievements->whereBetween('period', [$sponser->archievements->min('period'), $combPeriod])->sum('total_pv') ?? floatval(0), 2)}}</td>
                            @else
                            <td class="tright">{{number_format(floatval(0), 2)}}</td>
                            @endif
                            <td class="tright">{{number_format($sponser->accgbv($combPeriod), 2)}}</td>
                            <td class="tright">{{$sponser->sponser_id ?? '-'}}</td>
                            <td class="tright {{($sponser->currentsalary($combPeriod) && $sponser->currentsalary($combPeriod)->active) ? '' : 'tred'}}">{{number_format(($sponser->currentsalary($combPeriod)->amount ?? 0), 2)}}</td>

                        </tr>

                        @foreach ($sponsers->where('period', '<=', $combPeriod) as $key => $spp)
                        <tr class="gradeX {{($key+1) % 2 == 0 ? 'even' : 'odd'}}">
                            <td>{{$spp->firstname.' '.$spp->lastname}}</td>
                            <td>{{$spp->period}}</td>
                            <td>{{$spp->member_id}}</td>
                            <td>{{$lv}}</td>
                            <td>{{$spp->statlogs->where('period', $combPeriod)->first()->level ?? $spp->stats->level}}</td>
                            <td class="tright">{{number_format($spp->archievements->where('period', $combPeriod)->sum('total_pv') ?? floatval(0),2)}}</td>
                            <td class="tright">{{number_format($spp->currentgbv($combPeriod), 2)}}</td>
                            @if (intval($combPeriod) >= intval($spp->archievements->min('period')))
                            <td class="tright">{{number_format($spp->archievements->whereBetween('period', [$spp->archievements->min('period'), $combPeriod])->sum('total_pv') ?? floatval(0), 2)}}</td>
                            @else
                            <td class="tright">{{number_format(floatval(0), 2)}}</td>
                            @endif
                            <td class="tright">{{number_format($spp->accgbv($combPeriod), 2)}}</td>
                            <td class="tright">{{$spp->sponser_id ?? '-'}}</td>
                            <td class="tright {{($spp->currentsalary($combPeriod) && $spp->currentsalary($combPeriod)->active) ? '' : 'tred'}}">{{number_format(($spp->currentsalary($combPeriod)->amount ?? 0), 2)}}</td>

                        </tr>

                        @foreach ($spp->childrenSponsers as $k => $childrenSponser)
                            @php
                                if($spp->member_id === $childrenSponser->sponser_id){
                                    $lvi++;
                                }

                            @endphp
                            @include('pages.fragments.child-sponser-2', ['child_sponser' => $childrenSponser, 'k' => $k, 'p' => 0])
                        @endforeach
                        @endforeach

                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
