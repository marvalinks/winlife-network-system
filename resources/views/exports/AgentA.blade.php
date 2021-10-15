<table class="table table-striped table-bordered dataTable mx-table" id="" aria-describedby="sample_1_info">
    <thead>
        <tr role="row">
            <th class="" style="width: 122px;">Member ID</th>
            <th class="sorting" style="width: 400px;">Period</th>
            <th class="sorting" style="width: 400px;">Total PV</th>
            <th class="" rowspan="1" style="width: 125px;">Country</th>
        </tr>
    </thead>

    <tbody role="alert" aria-live="polite" aria-relevant="all">
         @foreach ($aexports as $export)
        <tr class="gradeX even">
            <td class="sorting_1">{{$export->member_id}}</td>
            <td class=" ">{{$export->period}}</td>
            <td class=" ">{{$export->total_pv}}</td>
            <td class=" ">{{$export->country}}</td>
        </tr>
        @endforeach

    </tbody>
</table>
