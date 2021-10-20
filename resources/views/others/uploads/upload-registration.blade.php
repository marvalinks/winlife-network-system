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
            <form action="{{route('upload.registration')}}" enctype="multipart/form-data" method="post">
                @csrf
                <h4>Agent Registration <span><a href="/formats/12R.xlsx" class="rt56">download format</a></span> </h4>
                <input required type="file" name="file" class="form-control">
                <button id="sample_editable_1_new" type="submit" class="btn green">Upload Excel <i class="icon-plus"></i></button>
            </form>
        </div>
        <div class="btn-group pull-right" style="margin-right: 10px;">
            <a href="{{route('upload.achivement')}}" class="btn green">Upload Achivements</a>
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
    @if (isset($exports))
    <div class="widget-body form">
        <form id="b32" action="{{route('admin.agent.upload.export.r')}}" method="post">
            @csrf
            <table class="table table-striped table-bordered dataTable mx-table">
                <thead>
                    <tr role="row">
                        <th></th>
                        <th class="" style="width: 122px;">Member.ID</th>
                        <th class="" rowspan="1" style="width: 122px;">Sponser.ID</th>
                        <th class="sorting" style="width: 400px;">Firstname</th>
                        <th class="sorting" style="width: 400px;">Lastname</th>
                        <th class="" rowspan="1" style="width: 125px;">Telephone</th>
                        <th class="" rowspan="1" style="width: 183px;">Address</th>
                        <th class="" rowspan="1" style="width: 183px;">Period</th>
                        <th class="" rowspan="1" style="width: 183px;">Nationality</th>
                        <th class="" rowspan="1" style="width: 183px;">Bank.name</th>
                        <th class="" rowspan="1" style="width: 183px;">Bank.number</th>
                    </tr>
                </thead>

                <tbody role="alert" aria-live="polite" aria-relevant="all">
                    @foreach ($exports as $key => $export)
                    @php
                        $msponser = 'tred';
                        $dd = 'tgreen';
                        if(isset($export->msponser)){
                            $msponser = 'tyellow';
                        }
                        if(isset($export->sponser)){
                            $msponser = 'tgreen';
                        }
                        if(isset($export->agent)){
                            $dd = 'tbrown';
                        }else{
                            $dd = 'tblue';
                        }

                    @endphp
                    <tr class="gradeX even">
                        <td>{{$key+1}}</td>
                        <td class="sorting_1"><input type="text" disabled class="m-wrap small {{$dd}}" value="{{$export->member_id}}" /></td>
                        <td class="center"><input type="text" disabled class="m-wrap small {{$msponser}}" value="{{$export->sponser_id}}" /></td>
                        <td class=" "><input type="text" disabled class="m-wrap small" value="{{$export->firstname}}" /></td>
                        <td class=" "><input type="text" disabled class="m-wrap small" value="{{$export->lastname}}" /></td>
                        <td class="center"><input type="text" disabled class="m-wrap small" value="{{$export->telephone}}" /></td>
                        <td class="center"><input type="text" disabled class="m-wrap small" value="{{$export->address}}" /></td>
                        <td class="center"><input type="text" disabled class="m-wrap small" value="{{$export->period}}" /></td>
                        <td class="center"><input type="text" disabled class="m-wrap small" value="{{$export->nationality}}" /></td>
                        <td class="center"><input type="text" disabled class="m-wrap small" value="{{$export->bank_name}}" /></td>
                        <td class="center"><input type="text" disabled class="m-wrap small" value="{{$export->bank_no}}" /></td>
                    </tr>
                    @endforeach

                </tbody>
            </table>
            <hr>
            @if (auth()->user()->roleid == 1)
            <div class="row-fluid">
                <div class="clearfix">
                    <div class="btn-group pull-right" style="margin-right: 10px;">
                        <a href="{{route('upload.registration')}}" class="btn">Cancel</a>
                        <a href="{{route('admin.agent.export.ar')}}" class="btn">Export to Excel<i class="icon-export"></i></a>
                        <button onclick="document.getElementById('b32').submit();" type="type" class="btn">Upload to winlife system <i class="icon-plus"></i></button>
                    </div>
                </div>

            </div>
        </form>
        @endif
    </div>
    @endif
</div>
@endsection
