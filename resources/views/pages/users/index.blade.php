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
                <small>System Agents Module</small>
            </h3>
            <ul class="breadcrumb">
                <li>
                    <a href="#"><i class="icon-home"></i></a><span class="divider">&nbsp;</span>
                </li>
                <li><a href="#">Home</a> <span class="divider">&nbsp;</span></li>
                <li><a href="#">System Users</a><span class="divider-last">&nbsp;</span></li>
            </ul>
            <!-- END PAGE TITLE & BREADCRUMB-->
        </div>
    </div>

    <div class="row-fluid">
        <div class="span12">
            <!-- BEGIN SAMPLE FORM widget-->
            <div class="widget">
                <div class="widget-title">
                    <h4><i class="icon-reorder"></i>System Users</h4>
                    <span class="tools">
                        <a href="javascript:;" class="icon-chevron-down"></a>
                        <a href="javascript:;" class="icon-remove"></a>
                    </span>
                </div>
                <div class="row-fluid">
                    <div class="clearfix">
                        <div class="btn-group pull-right" style="margin-right: 10px;">
                            <a href="{{route('admin.user.configuration')}}" class="btn green">Configuration <i class="icon-plus"></i></a>
                            <a href="{{route('admin.user.add')}}" class="btn green">Add new user <i class="icon-plus"></i></a>
                        </div>
                    </div>

                </div>
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
                                    <th class="hidden-phone sorting" role="columnheader" tabindex="0" aria-controls="sample_1" rowspan="1" colspan="1" aria-label="Points: activate to sort column ascending" style="width: 122px;">Name</th>
                                    <th class="hidden-phone sorting" role="columnheader" tabindex="0" aria-controls="sample_1" rowspan="1" colspan="1" aria-label="Email: activate to sort column ascending" style="width: 125px;">Username</th>
                                    <th class="hidden-phone sorting" role="columnheader" tabindex="0" aria-controls="sample_1" rowspan="1" colspan="1" aria-label="Joined: activate to sort column ascending" style="width: 183px;">Email</th>
                                    <th class="hidden-phone sorting" role="columnheader" tabindex="0" aria-controls="sample_1" rowspan="1" colspan="1" aria-label="Joined: activate to sort column ascending" style="width: 183px;">Role</th>
                                </tr>
                            </thead>

                            <tbody role="alert" aria-live="polite" aria-relevant="all">

                                @foreach ($users as $user)
                                <tr class="gradeX even">
                                    <td class="sorting_1">
                                        <div class="checker" id="uniform-undefined">
                                            <span><input type="checkbox" class="checkboxes" value="1" style="opacity: 0;" /></span>
                                        </div>
                                    </td>
                                    <td>{{$user->name}}</td>
                                    <td>{{$user->username}}</td>
                                    <td>{{$user->email ?? '-'}}</td>
                                    <td>{{$user->roleid == 1 ? 'Administrator' : 'Manager'}}</td>
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
