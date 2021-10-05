@extends('pages.layouts.app')
@section('links')
<link rel="stylesheet" type="text/css" href="/backend/assets/chosen-bootstrap/chosen/chosen.css" />
@endsection

@section('scripts')
<script type="text/javascript" src="/backend/assets/chosen-bootstrap/chosen/chosen.jquery.min.js"></script>
<script>
    $(".chosen-select").chosen({no_results_text: "Oops, nothing found!"});
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
                <div class="widget-body form">
                    <table class="table table-striped table-bordered dataTable mx-table" id="sample_1" aria-describedby="sample_1_info">
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
                                <th class="hidden-phone sorting" role="columnheader" tabindex="0" aria-controls="sample_1" rowspan="1" colspan="1" aria-label="Joined: activate to sort column ascending" style="width: 183px;"></th>
                                <th class="hidden-phone sorting" role="columnheader" tabindex="0" aria-controls="sample_1" rowspan="1" colspan="1" aria-label="Joined: activate to sort column ascending" style="width: 183px;"></th>
                                <th class="hidden-phone sorting" role="columnheader" tabindex="0" aria-controls="sample_1" rowspan="1" colspan="1" aria-label="Joined: activate to sort column ascending" style="width: 183px;"></th>
                                <th class="hidden-phone sorting" role="columnheader" tabindex="0" aria-controls="sample_1" rowspan="1" colspan="1" aria-label="Joined: activate to sort column ascending" style="width: 183px;"></th>
                            </tr>
                        </thead>

                        <tbody role="alert" aria-live="polite" aria-relevant="all">
                            @foreach ($sponsers as $key => $sponser)
                            <tr class="gradeX {{($key+1) % 2 == 0 ? 'even' : 'odd'}}">
                                <td class="sorting_1">
                                    <div class="checker" id="uniform-undefined">
                                        <span><input type="checkbox" class="checkboxes" value="1" style="opacity: 0;" /></span>
                                    </div>
                                </td>
                                <td>{{$sponser->firstname.' '.$sponser->lastname}}</td>
                                <td>{{$sponser->period}}</td>
                                <td>{{$sponser->member_id}}</td>
                                <td>{{$sponser->stats->layer}}</td>
                                <td>{{$sponser->stats->level}}</td>
                                <td>{{$sponser->stats->current_pbv}}</td>
                                <td>{{$sponser->stats->current_gbv}}</td>
                                <td>{{$sponser->stats->acc_pvb}}</td>
                                <td>{{$sponser->stats->acc_gbv}}</td>
                                <td>{{$sponser->stats->sponser_id ?? '-'}}</td>
                                <td>0</td>
                                <td>
                                    <a href="#">+ pvb</a>
                                </td>
                                <td>
                                    <a href="#">- pvb</a>
                                </td>
                                <td>
                                    <a href="#">Edit</a>
                                </td>
                                <td>
                                    <a href="#">Delete</a>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>

                </div>
            </div>
            <!-- END SAMPLE FORM widget-->
        </div>
    </div>

</div>
@endsection
