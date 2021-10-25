@extends('pages.layouts.app')
@section('links')
<link rel="stylesheet" type="text/css" href="/backend/assets/chosen-bootstrap/chosen/chosen.css" />
<style>
    .fm{
        display: flex;
        justify-content: space-evenly;
    }
    .clearfix{
        padding-left: 15px;
        padding-top: 7px;
    }
</style>
@endsection

@section('scripts')
<script type="text/javascript" src="/backend/assets/chosen-bootstrap/chosen/chosen.jquery.min.js"></script>
<script type="text/javascript" src="/backend/assets/data-tables/jquery.dataTables.js"></script>
<script type="text/javascript" src="/backend/assets/data-tables/DT_bootstrap.js"></script>
<script>
    $(".chosen-select").chosen({no_results_text: "Oops, nothing found!"});
    $(document).ready(function() {
        $('#dtable').DataTable( {
            "order": [[ 3, "desc" ]]
        } );
    } );
    // window.addEventListener('reopenDatatable', event => {
    //     $('#dtable').DataTable({});
    // })
</script>

@endsection

@section('content')
<div class="container-fluid">
    <div class="row-fluid">
        <div class="span12">
            <!-- BEGIN PAGE TITLE & BREADCRUMB-->
            <h3 class="page-title">
                System Agents
                <small>System Awards Module</small>
            </h3>
            <ul class="breadcrumb">
                <li>
                    <a href="#"><i class="icon-home"></i></a><span class="divider">&nbsp;</span>
                </li>
                <li><a href="#">Home</a> <span class="divider">&nbsp;</span></li>
                <li><a href="#">System Awards</a><span class="divider-last">&nbsp;</span></li>
            </ul>
            <!-- END PAGE TITLE & BREADCRUMB-->
        </div>
    </div>

    <div class="row-fluid">
        <div class="span12">
            <!-- BEGIN SAMPLE FORM widget-->
            <div class="widget">
                <div class="widget-title">
                    <h4><i class="icon-reorder"></i>System Awards</h4>
                    <span class="tools">
                        <a href="javascript:;" class="icon-chevron-down"></a>
                        <a href="javascript:;" class="icon-remove"></a>
                    </span>
                </div>
                <div class="row-fluid">
                    <div class="clearfix">
                        <div class="btn-group pull-right" style="margin-right: 10px;">
                            <a href="{{route('admin.awards.add')}}" class="btn green">Add new award <i class="icon-plus"></i></a>
                        </div>
                    </div>

                </div>
                <hr>
                <div class="widget-body form">
                        <table class="table table-striped table-bordered dataTable" id="" aria-describedby="sample_1_info">
                            <thead>
                                <tr role="row">
                                    <th aria-label="Points: activate to sort column ascending" style="width: 122px;">Member ID</th>
                                    <th aria-label="Email: activate to sort column ascending" style="width: 155px;">Name</th>
                                    <th aria-label="Joined: activate to sort column ascending" style="width: 50px;"></th>
                                    @foreach ($awards as $award)
                                    <th aria-label="Joined: activate to sort column ascending" style="width: 183px;">{{$award->name}}</th>
                                    @endforeach
                                </tr>
                            </thead>

                            <tbody role="alert" aria-live="polite" aria-relevant="all">
                                @foreach ($agents as $agent)
                                <tr>
                                    <td>{{$agent->member_id}}</td>
                                    <td>{{$agent->name}}</td>
                                    <td></td>
                                    @foreach ($awards as $award)
                                    <td>
                                        <input disabled type="checkbox" {{($agent->awards->where('award_id', $award->award_id)->first() && $agent->awards->where('award_id', $award->award_id)->first()->collected) ? 'checked' : ''}} name="" id="">
                                        @if ($agent->awards->where('award_id', $award->award_id)->first())
                                        <a href="#" disabled>Asign award</a>
                                        @endif
                                    </td>
                                    @endforeach
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
