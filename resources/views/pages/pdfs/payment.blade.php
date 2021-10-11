@extends('pages.layouts.pdf')

@section('links')
<style>
    .u90 h2{
        text-align: center;
        font-size: 25px;
        margin-bottom: 24px;
    }
    .u90 span.e4{
        font-weight: 600;
    }
    .u90 h6{
        font-size: 15px;
        margin-top: 24px;
    }
</style>
@endsection
@section('content')
@php
$combPeriod = date('Y').date('m');
@endphp
    @for ($i=1; $i < 3; $i++)
    <hr>
    <div class="row-fluid w94 u90">
        <h2>{{env('APP_NAME')}} Bonus Payment Receipt</h2>
        <div class="row-fluid">
            <div class="span6">
                <p>
                    <span class="e4">Bill No:</span>
                    <span>{{date('Y').date('m').mt_rand(100, 999)}}</span>
                </p>
            </div>
            <div class="span3">
                <p>
                    <span class="e4">Method:</span>
                    <span>Cash</span>
                </p>
            </div>
            <div class="span3">
                <p>
                    <span class="e4">Cheque No.:</span>
                    <span>-</span>
                </p>
            </div>
        </div>
        <div class="row-fluid">
            <div class="span6">
                <p>
                    <span class="e4">Payment Collected By:</span>
                    <span>{{$sponser->firstname.' '.$sponser->lastname}}</span>
                </p>
            </div>
            <div class="span3">
                <p>
                    <span class="e4">ID:</span>
                    <span>{{$sponser->member_id}}</span>
                </p>
            </div>
            <div class="span3">
                <p>
                    <span class="e4">Tel No.:</span>
                    <span>{{$sponser->telephone ?? '-'}}</span>
                </p>
            </div>
        </div>
        <h6>Bonus Details</h6>
        <div class="row-fluid">
            <div class="span6">
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
                            <td>{{$user->fistname.' '.$user->lastname}}</td>
                            <td>{{number_format(($user->currentbonus($combPeriod)->amount ?? 0), 2)}}</td>
                        </tr>
                        @endforeach

                    </tbody>
                </table>
            </div>
            <div class="span6">
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
                            <td>{{$user->fistname.' '.$user->lastname}}</td>
                            <td>{{number_format(($user->currentbonus($combPeriod)->amount ?? 0), 2)}}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    @endfor
@endsection
