@extends('pages.layouts.app')
@section('links')
<link rel="stylesheet" type="text/css" href="/backend/assets/chosen-bootstrap/chosen/chosen.css" />
<link href="https://cdn.datatables.net/1.11.3/css/jquery.dataTables.min.css" rel="stylesheet" />
<style>
    .fm{
        /* display: flex;
        justify-content: space-evenly; */
        padding-left: 21px;
    }
    .clearfix{
        padding-left: 15px;
        padding-top: 7px;
    }
    .rt56{
        font-size: 13px;
        text-decoration: underline;
    }
    .fm .control-group input,
    .fm .control-group select{
        width: 100%;
    }
</style>
@endsection

@section('scripts')
<script type="text/javascript" src="/backend/assets/chosen-bootstrap/chosen/chosen.jquery.min.js"></script>
<script type="text/javascript" src="/backend/assets/data-tables/jquery.dataTables.js"></script>
<script type="text/javascript" src="/backend/assets/data-tables/DT_bootstrap.js"></script>
<script>
    $(".chosen-select").chosen({no_results_text: "Oops, nothing found!"});
    oTable = $('#dtable').DataTable({
            "iDisplayLength": -1,
            ordering: false,
            bPaginate: false
        });
    oTable.fnSort( [[4,'asc'] ] );
</script>
<script>
    function toggle(source) {
        var checkboxes = document.getElementsByClassName('ckbox');
        for (let index = 0; index < checkboxes.length; index++) {
            if(checkboxes[index] != source) {
                checkboxes[index].checked = source.checked
            }

        }
    }
    function confirmPrint() {
        if(confirm("Are You Sure to print this?")) {
            document.getElementById('po').submit();
        }
    }
</script>

@endsection

@section('content')
<div class="container-fluid">
    <div class="row-fluid">
        <div class="span12">
            <!-- BEGIN PAGE TITLE & BREADCRUMB-->
            <h3 class="page-title">
                System Agents
                <small>System Agents Module</small>
            </h3>
            <ul class="breadcrumb">
                <li>
                    <a href="#"><i class="icon-home"></i></a><span class="divider">&nbsp;</span>
                </li>
                <li><a href="#">Home</a> <span class="divider">&nbsp;</span></li>
                <li><a href="#">System Agents</a><span class="divider-last">&nbsp;</span></li>
            </ul>
            <!-- END PAGE TITLE & BREADCRUMB-->
        </div>
    </div>

    <div class="row-fluid">
        <div class="span12">
            <!-- BEGIN SAMPLE FORM widget-->
            <div class="widget">
                <div class="widget-title">
                    <h4><i class="icon-reorder"></i>System Agents</h4>
                    <span class="tools">
                        <a href="javascript:;" class="icon-chevron-down"></a>
                        <a href="javascript:;" class="icon-remove"></a>
                    </span>
                </div>
                <!--  -->
                @if (auth()->user()->roleid == 1)
                <div class="row-fluid">
                    <div class="clearfix">
                        <div class="btn-group">
                            <a href="{{route('admin.agent.add')}}" class="btn green">Add New <i class="icon-plus"></i></a>
                            <a href="{{route('upload.registration')}}" class="btn green">Upload Registration</a>
                            <a href="{{route('upload.achivement')}}" class="btn green">Upload Achivements</a>
                        </div>
                        @if(auth()->user()->roleid === 1)
                        <div class="btn-group pull-right" style="margin-right: 10px;">
                            <a href="{{route('admin.calculate.bonus')}}" class="btn green">Calculate Bonus <i class="icon-plus"></i></a>
                            <button class="btn dropdown-toggle" data-toggle="dropdown">Tools <i class="icon-angle-down"></i></button>
                            <ul class="dropdown-menu pull-right">
                                <li><a href="" wire:click.prevent="fixSponsers()">Fix Sponsers</a></li>
                                <li><a href="{{route('delete.dbs')}}">Delete DBS</a></li>
                                <li><a href="#">Export to Excel</a></li>
                            </ul>
                            <button type="button" onclick="confirmPrint();"  class="btn green">Bonus <i class="icon-plus"></i></button>
                        </div>
                        @endif
                    </div>

                </div>
                <hr>
                @endif
                <div class="row-fluid">
                    <form class="fm" action="" method="get">
                        <div class="control-group span3">
                            @csrf
                            <label class="control-label">Member</label>
                            <div class="controls">
                                <input type="text" value="{{$memberid ?? ''}}" required name="memberid">
                            </div>
                        </div>
                        <div class="control-group span3">
                            <label class="control-label">Year</label>
                            <div class="controls">
                                <select name="selectedYear" required>
                                    @for ($i=date('Y'); $i>2010; $i--)
                                    <option {{isset($yr) && $yr === $i ? 'selected' : ''}} value="{{$i}}">{{$i}}</option>
                                    @endfor
                                </select>
                            </div>
                        </div>
                        <div class="control-group span3">
                            <label class="control-label">Month</label>
                            <div class="controls">
                                <select name="selectedMonth" required>
                                    <option selected value="">-choose-</option>
                                    @foreach ($months as $month)
                                    <option {{isset($mth) && $mth === $month ? 'selected' : ''}} value="{{$month}}">{{$month}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="control-group span3">
                            <label class="control-label">.</label>
                            <button type="submit" class="btn">Search</button>
                        </div>
                    </form>
                </div>
                <!--  -->
                <div class="widget-body form">
                    <form id="po" action="{{route('bonus.pdf')}}" method="get">
                        @csrf
                        <table class="table table-striped table-bordered dataTable mx-table" id="dtable" aria-describedby="sample_1_info">
                            <thead>
                                <tr role="row">
                                    <th style="width: 24px;" class="sorting_disabled">
                                        <span><input value="" onclick="toggle(this);" type="checkbox" class="" /></span>
                                    </th>
                                    <th class="sorting" role="columnheader" tabindex="0" aria-controls="sample_1" rowspan="1" colspan="1" aria-label="Username: activate to sort column ascending" style="width: 400px;">Name</th>
                                    <th colspan="1" aria-label="Email: activate to sort column ascending" style="width: 125px;">Period</th>
                                    <th colspan="1" aria-label="Points: activate to sort column ascending" style="width: 122px;">Business.ID</th>
                                    <th colspan="1" aria-label="Joined: activate to sort column ascending" style="width: 183px;">Layer</th>
                                    <th colspan="1" aria-label="Joined: activate to sort column ascending" style="width: 183px;">Level</th>
                                    <th colspan="1" aria-label="Joined: activate to sort column ascending" style="width: 183px;">CurrentPBV</th>
                                    <th colspan="1" aria-label="Joined: activate to sort column ascending" style="width: 183px;">CurrentGBV</th>
                                    <th colspan="1" aria-label="Joined: activate to sort column ascending" style="width: 183px;">ACCPBV</th>
                                    <th colspan="1" aria-label="Joined: activate to sort column ascending" style="width: 183px;">ACCGBV</th>
                                    <th colspan="1" aria-label="Joined: activate to sort column ascending" style="width: 122px;">Sponser.ID</th>
                                    <th colspan="1" aria-label="Joined: activate to sort column ascending" style="width: 183px;">Salary</th>
                                    @if (auth()->user()->roleid == 1)
                                        <th colspan="1" aria-label="Joined: activate to sort column ascending" style="width: 183px;">Paid</th>
                                    @endif
                                    <th aria-controls="sample_1" rowspan="1" colspan="1" aria-label="Joined: activate to sort column ascending" style="width: 183px;"></th>
                                    <th aria-controls="sample_1" rowspan="1" colspan="1" aria-label="Joined: activate to sort column ascending" style="width: 183px;"></th>
                                </tr>

                            </thead>

                            <tbody role="alert" aria-live="polite" aria-relevant="all">


                                @if (isset($user))
                                <tr class="gradeX even">
                                    <td class="">
                                        <div class="" id="uniform-undefined">
                                            <span><input type="checkbox" class="ckbox" /></span>
                                            <input type="hidden" name="sponser" value="{{$user->member_id}}" />
                                            <input type="hidden" name="period" value="{{$yr.$mth}}" />
                                        </div>
                                    </td>
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
                                    @if (auth()->user()->roleid == 1)
                                        <td>
                                            <input type="checkbox" disabled {{($user->currentsalary($combPeriod) && $user->currentsalary($combPeriod)->paid) ? 'checked' : ''}}>
                                        </td>
                                    @endif
                                    <td>
                                        <a href="{{route('admin.agent.edit', [$user->member_id])}}">Adjust</a>
                                    </td>
                                    <td></td>
                                </tr>
                                @foreach ($sponsers as $key => $sponser)
                                <tr class="gradeX {{($key+1) % 2 == 0 ? 'even' : 'odd'}}">
                                    <td class="sorting_1">
                                        <div class="" id="uniform-undefined">
                                            <span><input type="checkbox" name="agents[]" class="checkboxes ckbox" value="{{$sponser->member_id}}" /></span>
                                        </div>
                                    </td>
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
                                    @if (auth()->user()->roleid == 1)
                                        <td>
                                            <input type="checkbox" disabled {{($sponser->currentsalary($combPeriod) && $sponser->currentsalary($combPeriod)->paid) ? 'checked' : ''}}>
                                        </td>
                                    @endif
                                    <td>
                                        <a href="{{route('admin.agent.edit', [$sponser->member_id])}}">Adjust</a>
                                    </td>
                                    <td></td>
                                </tr>
                                @endforeach
                                @endif

                            </tbody>
                        </table>
                    </form>
                    @if (isset($sponsers))
                    <div class="row-fluid">
                        <div class="span6"></div>
                        <div class="span6">
                            <div class="dataTables_paginate paging_bootstrap pagination">
                                {{$sponsers->appends(request()->input())->links()}}
                            </div>
                        </div>
                    </div>

                    @endif
                </div>
                <!--  -->
            </div>
            <!-- END SAMPLE FORM widget-->
        </div>
    </div>

</div>
@endsection
