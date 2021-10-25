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
        width: 50%;
    }
    .table th, .table td {
        padding: 3px!important;
        font-size: 11px;
        border: 1px solid #000!important;
    }
</style>
@endsection
@section('content')
@php
    $combPeriod = date('Y').date('m');
    $sumMoney = 0.0;
    $sumbv = 0.0;
    $conf = \App\Models\BvRate::first();
@endphp
    @for ($i=1; $i < 3; $i++)
    @if ($i == 2)
        <hr>
    @endif
    <div class="row w94 u90">
        <h2><b>{{env('APP_NAME')}} Bonus Payment Receipt</b></h2>
        <div class="p56">
            <div class="row">
                <div class="span6">
                    <p>
                        <span class="e4"><b>Bill No:</b></span>
                        <span>{{date('Y').date('m').date('d').mt_rand(10,99)}}</span>
                    </p>
                </div>
                <div class="span6">
                    <p>
                        <span class="e4"><b>Payment Collected By:</b></span>
                        <span>{{$sponser->firstname.' '.$sponser->lastname}}</span>
                    </p>
                </div>
            </div>
            <div class="row">
                <div class="span3">
                    <p>
                        <span class="e4"><b>Method:</b></span>
                        <span>Cash</span>
                    </p>
                </div>
                <div class="span3">
                    <p>
                        <span class="e4"><b>ID:</b></span>
                        <span>{{$sponser->member_id}}</span>
                    </p>
                </div>

            </div>
            <div class="row">
                <div class="span3">
                    <p>
                        <span class="e4"><b>Cheque No.:</b></span>
                        <span>-</span>
                    </p>
                </div>
                <div class="span3">
                    <p>
                        <span class="e4"><b>Tel No.:</b></span>
                        <span>{{$sponser->telephone ?? '-'}}</span>
                    </p>
                </div>
            </div>
        </div>
        <h6>Bonus Details</h6>
        <div class="row p56">
            <div class="span6 w50">
                <table class="table table-bordered table-hover">
                    <thead>
                        <tr>
                            <th>ID No.</th>
                            <th>Name</th>
                            <th>Bonus</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($firstPreview as $user)
                        <tr>
                            <td>{{$user->member_id}}</td>
                            <td>{{$user->firstname.' '.$user->lastname}}</td>
                            <td>{{number_format(($user->currentbonus($combPeriod)->amount ?? 0), 2)}}</td>
                            @php
                                if($i < 2) {
                                    $sumMoney += $user->currentsalary($combPeriod)->amount ?? 0;
                                    $sumbv += $user->currentach($combPeriod)->sum('total_pv');
                                }
                            @endphp
                        </tr>
                        @endforeach

                    </tbody>
                </table>
            </div>
            <div class="span6 w50">
                <table class="table table-bordered table-hover">
                    <thead>
                        <tr>
                            <th>ID No.</th>
                            <th>Name</th>
                            <th>Bonus</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($secondPreview as $user)
                        <tr class="gradeX even">
                            <td>{{$user->member_id}}</td>
                            <td>{{$user->firstname.' '.$user->lastname}}</td>
                            <td>{{number_format(($user->currentbonus($combPeriod)->amount ?? 0), 2)}}</td>
                            @php
                                if($i < 2) {
                                    $sumMoney += $user->currentsalary($combPeriod)->amount ?? 0;
                                    $sumbv += $user->currentach($combPeriod)->sum('total_pv');
                                }
                            @endphp
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        <div class="row p56">
            <div class="row"></div>
            <div class="row">
                <h6><b>Total BV:</b> <span>{{number_format(floatval($sumMoney), 2)}}</span></h6>
            </div>
            <div class="row">
                <h6><b>In GHC:</b> <span>{{number_format(floatval($sumMoney * ($conf->rate ?? float(0))), 2)}}</span></h6>
            </div>
        </div>
        <div class="row" style="margin-top: 20px; margin-bottom: 50px;">
            <p>I represent myself and the above listed members and have got the got the bonus listed above.</p>
        </div>
        <div class="row p56">
            <div class="row"></div>
            <div class="row">
                <b>Signature:</b>
            </div>
            <div class="row">
                <b>Date:</b> &nbsp; {{date('d-m-Y')}}
            </div>
        </div>
    </div>
    @endfor
@endsection
