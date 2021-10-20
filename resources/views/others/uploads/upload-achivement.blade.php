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
        <div class="clearfix">
            <form action="{{route('upload.achivement')}}" enctype="multipart/form-data" method="post">
                @csrf
                <h4>Agent Achivements <span><a href="/formats/12A.xlsx" class="rt56">download format</a></span> </h4>
                <input required type="file" name="file" class="form-control">
                <button id="sample_editable_1_new" type="submit" class="btn green">Upload Excel <i class="icon-plus"></i></button>
            </form>
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
                            <th class="" style="width: 122px;">Member.ID</th>
                            <th class="" rowspan="1" style="width: 122px;">Name</th>
                            <th class="sorting" style="width: 400px;">Period</th>
                            <th class="sorting" style="width: 400px;">Total PV</th>
                            <th class="" rowspan="1" style="width: 125px;">Country</th>
                        </tr>
                    </thead>

                    <tbody role="alert" aria-live="polite" aria-relevant="all">
                        @foreach ($aexports as $export)
                        <tr class="gradeX even">
                            <td class="sorting_1"><input type="text" name="member_id[]" class="m-wrap small {{isset($export->agent) ? 'tgreen' : 'tred'}}" value="{{$export->member_id}}" /></td>
                            <td class=" "><input type="text" name="name[]" class="m-wrap small" value="{{$export->agent->name ?? '-'}}" /></td>
                            <td class=" "><input type="text" name="period[]" class="m-wrap small" value="{{$export->period}}" /></td>
                            <td class=" "><input type="text" name="total_pv[]" class="m-wrap small" value="{{number_format($export->total_pv, 2)}}" /></td>
                            <td class=" "><input type="text" name="country[]" class="m-wrap small" value="{{$export->country}}" /></td>
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
