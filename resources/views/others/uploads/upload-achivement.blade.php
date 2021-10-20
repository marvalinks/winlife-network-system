@extends('pages.layouts.app')
@section('links')
<link rel="stylesheet" type="text/css" href="/backend/assets/chosen-bootstrap/chosen/chosen.css" />
<style>
    .m-wrap.small {
        width: 135px !important;
        font-size: 10px!important;
        padding: 0px;
    }
</style>
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
        <div class="clearfix">
            <form action="{{route('upload.achivement')}}" enctype="multipart/form-data" method="post">
                @csrf
                <h4>Agent Achivements <span><a href="/formats/12A.xlsx" class="rt56">download format</a></span> </h4>
                <input required type="file" name="file" class="form-control">
                <button id="sample_editable_1_new" type="submit" class="btn green">Upload Excel <i class="icon-plus"></i></button>
            </form>
        </div>
        <div class="btn-group pull-right" style="margin-right: 10px;">
            <a href="{{route('upload.registration')}}" class="btn green">Upload Registration</a>
        </div>
    </div>
    <div class="widget-body form">
        <div class="row">
            <h4>Color codes</h4>
            <ul>
                <li><span class="tred">Red color for agent</span> : <br>
                    <span>Agent sponser ID not in the system</span>
                </li>
                <li><span class="tbrown">Brown color for agent</span> : <br>
                    <span> - Agent member ID already in the system</span>
                </li>
                <li><span class="tblue">Blue color for agent</span> : <br>
                    <span> - Agent member ID not in the system</span>
                </li>
                <li><span class="tgreen">Green color for agent</span> : <br>
                    <span> - Agent sponser ID available in the system</span>
                </li>
                <li><span class="tyellow">Yellow color for agent</span> : <br>
                    <span> - Agent sponser ID not in the system but in excel form.</span>
                </li>
            </ul>
        </div>
    </div>
    <hr>
    @if (isset($aexports))
        <div class="widget-body form">
            <form id="a23" action="{{route('admin.agent.upload.export.a')}}" method="post">
                @csrf
                <table class="table table-striped table-bordered dataTable mx-table" id="">
                    <thead>
                        <tr role="row">
                            <th></th>
                            <th class="" style="width: 122px;">Member.ID</th>
                            <th class="" style="width: 122px;">Name</th>
                            <th class="" style="width: 122px;">Period</th>
                            <th class="" style="width: 122px;">Total PV</th>
                            <th class="" style="width: 125px;">Country</th>
                        </tr>
                    </thead>

                    <tbody role="alert">
                        @foreach ($aexports as $key => $export)
                        <tr class="">
                            <td>{{$key+1}}</td>
                            <td class=""><input disabled type="text" name="member_id[]" class="m-wrap small {{isset($export->agent) ? 'tgreen' : 'tred'}}" value="{{$export->member_id}}" /></td>
                            <td class=""><input disabled type="text" name="name[]" class="m-wrap small" value="{{$export->agent->name ?? '-'}}" /></td>
                            <td class=""><input disabled type="text" name="period[]" class="m-wrap small" value="{{$export->period}}" /></td>
                            <td class=""><input disabled type="text" name="total_pv[]" class="m-wrap small" value="{{number_format($export->total_pv, 2)}}" /></td>
                            <td class=""><input disabled type="text" name="country[]" class="m-wrap small" value="{{$export->country}}" /></td>
                        </tr>
                        @endforeach

                    </tbody>
                </table>
                <hr>
                @if (auth()->user()->roleid == 1)
                <div class="row-fluid">
                    <div class="clearfix">
                        <div class="btn-group pull-right" style="margin-right: 10px;">
                            <a href="{{route('admin.agent.export.aa')}}" class="btn">Export to Excel<i class="icon-export"></i></a>
                            <button type="button" onclick="document.getElementById('a23').submit();" class="btn">Upload to winlife system <i class="icon-plus"></i></button>
                        </div>
                    </div>

                </div>
            </form>
            @endif
        </div>
    @endif
</div>
@endsection
