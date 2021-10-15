@extends('pages.layouts.app')
@section('links')
<link rel="stylesheet" type="text/css" href="/backend/assets/chosen-bootstrap/chosen/chosen.css" />
@endsection

@section('scripts')
<script type="text/javascript" src="/backend/assets/chosen-bootstrap/chosen/chosen.jquery.min.js"></script>
<script>
    $(".chosen-select").chosen({no_results_text: "Oops, nothing found!"});
</script>
<script type="text/javascript" src="/backend/assets/chosen-bootstrap/chosen/chosen.jquery.min.js"></script>
<script type="text/javascript" src="/backend/assets/data-tables/jquery.dataTables.js"></script>
<script type="text/javascript" src="/backend/assets/data-tables/DT_bootstrap.js"></script>
<script>
    $(".chosen-select").chosen({no_results_text: "Oops, nothing found!"});
    // $(document).ready(function() {
    //     $('#dtable2').DataTable( {

    //     } );
    // } );
</script>
@endsection

@section('content')
    @php
        $lv = 1;
        $lvi = 1;
        $lvf = 2;
        $combPeriod = date('Y').date('m');
    @endphp
<div class="container-fluid">
    <div class="row-fluid">
        <div class="span12">
            <!-- BEGIN SAMPLE FORM widget-->
            <div class="widget">
                <div class="widget-title">
                    <h4><i class="icon-reorder"></i>Agents Form</h4>
                    <span class="tools">
                        <a href="javascript:;" class="icon-chevron-down"></a>
                        <a href="javascript:;" class="icon-remove"></a>
                    </span>
                </div>
                <hr>
                <div class="row-fluid">
                    <div class="clearfix">
                        <div class="btn-group" style="margin-left: 10px;">
                            <h2>GHC {{number_format(($sponser->currentbonus($combPeriod)->amount ?? 0), 2)}}</h2>
                        </div>
                        @if (auth()->user()->roleid == 1)
                        <div class="btn-group pull-right" style="margin-right: 10px;">
                            <a href="{{route('admin.calculate.bonus')}}" class="btn green">Calculate Bonus <i class="icon-plus"></i></a>
                            <a href="{{route('admin.agent.payment',[$sponser->member_id])}}" class="btn green">Make Payment <i class="icon-plus"></i></a>
                        </div>
                        @endif
                    </div>

                </div>
                <hr>
                <h2 style="padding-left: 30px;">Downlines</h2>
                <hr>
                <div class="widget-body form">
                    <table class="table table-striped table-bordered dataTable mx-table" id="dtable2" aria-describedby="sample_1_info">
                        <thead>
                            <tr role="row">
                                <th style="width: 24px;" class="sorting_disabled" role="columnheader" rowspan="1" colspan="1" aria-label="">
                                    <div class="checker" id="uniform-undefined">
                                        <span><input type="checkbox" class="group-checkable" data-set="#sample_1 .checkboxes" style="opacity: 0;" /></span>
                                    </div>
                                </th>
                                <th class="sorting" role="columnheader" tabindex="0" aria-controls="sample_1" rowspan="1" colspan="1" aria-label="Username: activate to sort column ascending" style="width: 400px;">Name</th>
                                <th class="hidden-phone sorting" role="columnheader" tabindex="0" aria-controls="sample_1" rowspan="1" colspan="1" aria-label="Email: activate to sort column ascending" style="width: 125px;">Period</th>
                                <th class="hidden-phone sorting" role="columnheader" tabindex="0" aria-controls="sample_1" rowspan="1" colspan="1" aria-label="Points: activate to sort column ascending" style="width: 122px;">Business.ID</th>
                                <th class="hidden-phone sorting" role="columnheader" tabindex="0" aria-controls="sample_1" rowspan="1" colspan="1" aria-label="Joined: activate to sort column ascending" style="width: 183px;">Layer</th>
                                <th class="hidden-phone sorting" role="columnheader" tabindex="0" aria-controls="sample_1" rowspan="1" colspan="1" aria-label="Joined: activate to sort column ascending" style="width: 183px;">Level</th>
                                <th class="hidden-phone sorting" role="columnheader" tabindex="0" aria-controls="sample_1" rowspan="1" colspan="1" aria-label="Joined: activate to sort column ascending" style="width: 183px;">CurrentPBV</th>
                                <th class="hidden-phone sorting" role="columnheader" tabindex="0" aria-controls="sample_1" rowspan="1" colspan="1" aria-label="Joined: activate to sort column ascending" style="width: 183px;">CurrentGBV</th>
                                <th class="hidden-phone sorting" role="columnheader" tabindex="0" aria-controls="sample_1" rowspan="1" colspan="1" aria-label="Joined: activate to sort column ascending" style="width: 183px;">ACCPBV</th>
                                <th class="hidden-phone sorting" role="columnheader" tabindex="0" aria-controls="sample_1" rowspan="1" colspan="1" aria-label="Joined: activate to sort column ascending" style="width: 183px;">ACCGBV</th>
                                <th class="hidden-phone sorting" role="columnheader" tabindex="0" aria-controls="sample_1" rowspan="1" colspan="1" aria-label="Joined: activate to sort column ascending" style="width: 122px;">Sponser.ID</th>
                                <th class="hidden-phone sorting" role="columnheader" tabindex="0" aria-controls="sample_1" rowspan="1" colspan="1" aria-label="Joined: activate to sort column ascending" style="width: 183px;">Salary</th>
                                @if (auth()->user()->roleid == 1)
                                    <th class="hidden-phone sorting" role="columnheader" tabindex="0" aria-controls="sample_1" rowspan="1" colspan="1" aria-label="Joined: activate to sort column ascending" style="width: 183px;">Paid</th>
                                @endif
                                <th class="hidden-phone sorting" role="columnheader" tabindex="0" aria-controls="sample_1" rowspan="1" colspan="1" aria-label="Joined: activate to sort column ascending" style="width: 183px;"></th>
                                <th class="hidden-phone sorting" role="columnheader" tabindex="0" aria-controls="sample_1" rowspan="1" colspan="1" aria-label="Joined: activate to sort column ascending" style="width: 183px;"></th>
                            </tr>
                        </thead>

                        <tbody role="alert" aria-live="polite" aria-relevant="all">

                            <tr class="gradeX even}}">
                                <td class="sorting_1">
                                    <div class="checker" id="uniform-undefined">
                                        <span><input type="checkbox" class="checkboxes" value="1" style="opacity: 0;" /></span>
                                    </div>
                                </td>
                                <td>{{$sponser->firstname.' '.$sponser->lastname}}</td>
                                <td>{{$sponser->period}}</td>
                                <td>{{$sponser->member_id}}</td>
                                <td>0</td>
                                <td>{{$sponser->stats->level}}</td>
                                <td>{{number_format($sponser->archievements->where('period', $combPeriod)->sum('total_pv') ?? floatval(0),2)}}</td>
                                <td>{{number_format($sponser->currentgbv($combPeriod), 2)}}</td>
                                <td>{{number_format($sponser->archievements->where('period', $combPeriod)->sum('total_pv') ?? floatval(0), 2)}}</td>
                                <td>{{number_format($sponser->accgbv($combPeriod), 2)}}</td>
                                <td>{{$sponser->sponser_id ?? '-'}}</td>
                                <td>{{number_format(($sponser->currentbonus($combPeriod)->amount ?? 0), 2)}}</td>
                                @if (auth()->user()->roleid == 1)
                                    <td>
                                        <input type="checkbox" disabled {{($sponser->currentbonus($combPeriod) && $sponser->currentbonus($combPeriod)->paid) ? 'checked' : ''}}>
                                    </td>
                                @endif
                                <td></td>
                                <td></td>
                            </tr>

                            @foreach ($sponsers as $key => $spp)
                            <tr class="gradeX {{($key+1) % 2 == 0 ? 'even' : 'odd'}}">
                                <td class="sorting_1">
                                    <div class="checker" id="uniform-undefined">
                                        <span><input type="checkbox" class="checkboxes" value="1" style="opacity: 0;" /></span>
                                    </div>
                                </td>
                                <td>{{$spp->firstname.' '.$spp->lastname}}</td>
                                <td>{{$spp->period}}</td>
                                <td>{{$spp->member_id}}</td>
                                <td>{{$lv}}</td>
                                <td>{{$spp->stats->level}}</td>
                                <td>{{number_format($spp->archievements->where('period', $combPeriod)->sum('total_pv') ?? floatval(0),2)}}</td>
                                <td>{{number_format($spp->currentgbv($combPeriod), 2)}}</td>
                                <td>{{number_format($spp->archievements->where('period', $combPeriod)->sum('total_pv') ?? floatval(0), 2)}}</td>
                                <td>{{number_format($spp->accgbv($combPeriod), 2)}}</td>
                                <td>{{$spp->sponser_id ?? '-'}}</td>
                                <td>{{number_format(($spp->currentbonus($combPeriod)->amount ?? 0), 2)}}</td>
                                @if (auth()->user()->roleid == 1)
                                    <td>
                                        <input type="checkbox" disabled {{($spp->currentbonus($combPeriod) && $spp->currentbonus($combPeriod)->paid) ? 'checked' : ''}}>
                                    </td>
                                @endif
                                <td>
                                    <a href="{{route('admin.agent.edit', [$spp->member_id])}}">Adjust</a>
                                </td>
                                <td></td>
                            </tr>

                            @foreach ($spp->childrenSponsers as $k => $childrenSponser)
                                @php
                                    if($spp->member_id === $childrenSponser->sponser_id){
                                        $lvi++;
                                    }

                                @endphp
                                @include('pages.fragments.child-sponser', ['child_sponser' => $childrenSponser, 'k' => $k, 'p' => 0])
                            @endforeach
                            @endforeach

                        </tbody>
                    </table>

                </div>
                <hr>
                <div class="widget-body form">
                    <!-- BEGIN FORM-->
                    <form method="POST" class="row-fluid" action="#" class="form-horizontal">
                        @csrf
                        <div class="span4 ml0">
                            <div class="form-group">
                                <label class="control-label">Member ID</label>
                                <input value="{{$sponser->member_id}}" disabled required type="text" class="form-control">
                            </div>
                        </div>
                        <div class="span4 ml0">
                            <div class="form-group">
                                <label class="control-label">First Name</label>
                                <input name="firstname" value="{{$sponser->firstname}}" required type="text" class="form-control">
                            </div>
                        </div>
                        <div class="span4 ml0">
                            <div class="form-group">
                                <label class="control-label">Last Name</label>
                                <input name="lastname" value="{{$sponser->lastname}}" required type="text" class="form-control">
                            </div>
                        </div>
                        <div class="span4 ml0">
                            <div class="form-group">
                                <label class="control-label">Telephone</label>
                                <input name="telephone" value="{{$sponser->telephone}}" required type="text" class="form-control">
                            </div>
                        </div>
                        <div class="span4 ml0">
                            <div class="form-group">
                                <label class="control-label">Address</label>
                                <input name="address" value="{{$sponser->address}}" required type="text" class="form-control">
                            </div>
                        </div>
                        <div class="span4 ml0">
                            <div class="form-group">
                                <label class="control-label">Period</label>
                                <input name="period" value="{{$sponser->period}}" required type="text" class="form-control">
                            </div>
                        </div>
                        <div class="span4 ml0">
                            <div class="form-group">
                                <label class="control-label">Sponser</label>
                                <input value="{{$sponser->sponser_id}}" disabled type="text" class="form-control">

                            </div>
                        </div>
                        <div class="span12 ml0">
                            <div class="form-actions">
                              <button type="submit" class="btn btn-success">save</button>
                              <button type="button" class="btn">Cancel</button>
                           </div>
                        </div>

                    </form>
                    <hr>
                    <form method="POST" class="row-fluid" action="{{route('admin.agent.adjust.pvb', [$sponser->member_id])}}" class="form-horizontal">
                        @csrf
                        <div class="span4 ml0">
                            <div class="form-group">
                                <label class="control-label">Level</label>
                                <input value="{{$sponser->stats->level}}" disabled type="text" class="form-control">
                            </div>
                        </div>
                        <div class="span4 ml0">
                            <div class="form-group">
                                <label class="control-label">CurrentPBV</label>
                                <input value="{{number_format($sponser->archievements->where('period', $combPeriodToday)->sum('total_pv') ?? floatval(0),2)}}" disabled type="text" class="form-control">
                            </div>
                        </div>
                        <div class="span4 ml0">
                            <div class="form-group">
                                <label class="control-label">CurrentGBV</label>
                                <input value="{{number_format($currentGBV, 2)}}" disabled type="text" class="form-control">
                            </div>
                        </div>
                        <div class="span4 ml0">
                            <div class="form-group">
                                <label class="control-label">ACCPBV</label>
                                <input value="{{number_format($sponser->archievements->whereBetween('period', [$sponser->archievements->min('period'), $combPeriodToday])->sum('total_pv') ?? floatval(0), 2)}}" disabled type="text" class="form-control">
                            </div>
                        </div>
                        <div class="span4 ml0">
                            <div class="form-group">
                                <label class="control-label">ACCGBV</label>
                                <input value="{{number_format($ACCGBV, 2)}}" disabled type="text" class="form-control">
                            </div>
                        </div>
                        @if (auth()->user()->roleid == 1)
                        <div class="span4 ml0">
                            <div class="form-group">
                                <label class="control-label">Adjust PBV</label>
                                <input name="adj" required type="number" step="1" class="form-control">
                            </div>
                        </div>
                        <div class="span12 ml0">
                            <div class="form-actions">
                              <button type="submit" class="btn btn-success">adjust PVB</button>
                           </div>
                        </div>
                        @endif

                    </form>
                    <hr>
                    <h2>Achievments</h2>
                    <hr>
                    <div class="widget-body form">
                        <table class="table table-striped table-bordered dataTable" id="dtable" aria-describedby="sample_1_info">
                            <thead>
                                <tr role="row">
                                    <th style="width: 24px;" class="sorting_disabled" role="columnheader" rowspan="1" colspan="1" aria-label="">
                                        <div class="checker" id="uniform-undefined">
                                            <span><input type="checkbox" class="group-checkable" data-set="#sample_1 .checkboxes" style="opacity: 0;" /></span>
                                        </div>
                                    </th>
                                    <th class="hidden-phone sorting" role="columnheader" tabindex="0" aria-controls="sample_1" rowspan="1" colspan="1" aria-label="Points: activate to sort column ascending" style="width: 122px;">Business.ID</th>
                                    <th class="hidden-phone sorting" role="columnheader" tabindex="0" aria-controls="sample_1" rowspan="1" colspan="1" aria-label="Email: activate to sort column ascending" style="width: 125px;">Period</th>
                                    <th class="hidden-phone sorting" role="columnheader" tabindex="0" aria-controls="sample_1" rowspan="1" colspan="1" aria-label="Joined: activate to sort column ascending" style="width: 183px;">Name</th>
                                    <th class="hidden-phone sorting" role="columnheader" tabindex="0" aria-controls="sample_1" rowspan="1" colspan="1" aria-label="Joined: activate to sort column ascending" style="width: 183px;">PVB</th>
                                    <th class="hidden-phone sorting" role="columnheader" tabindex="0" aria-controls="sample_1" rowspan="1" colspan="1" aria-label="Joined: activate to sort column ascending" style="width: 183px;">Country</th>
                                </tr>
                            </thead>

                            <tbody role="alert" aria-live="polite" aria-relevant="all">

                                @foreach ($archievements as $ach)
                                <tr class="gradeX even">
                                    <td class="sorting_1">
                                        <div class="checker" id="uniform-undefined">
                                            <span><input type="checkbox" class="checkboxes" value="1" style="opacity: 0;" /></span>
                                        </div>
                                    </td>
                                    <td>{{$ach->member_id}}</td>
                                    <td>{{$ach->period}}</td>
                                    <td>{{$ach->name ?? '-'}}</td>
                                    <td>{{$ach->total_pv}}</td>
                                    <td>{{$ach->country}}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>

                    </div>

                </div>
            </div>
            <!-- END SAMPLE FORM widget-->
        </div>
    </div>

</div>
@endsection
