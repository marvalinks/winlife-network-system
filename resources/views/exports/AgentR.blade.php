<table class="table table-striped table-bordered dataTable mx-table" id="" aria-describedby="sample_1_info">
    <thead>
        <tr role="row">
            <th class="" style="width: 122px;">MemberID</th>
            <th class="" rowspan="1" style="width: 122px;">SponsorID</th>
            <th class="sorting" style="width: 400px;">FirstName</th>
            <th class="sorting" style="width: 400px;">LastName</th>
            <th class="" rowspan="1" style="width: 125px;">Phone</th>
            <th class="" rowspan="1" style="width: 183px;">Address</th>
            <th class="" rowspan="1" style="width: 183px;">Period</th>
            <th class="" rowspan="1" style="width: 183px;">Nationality</th>
            <th class="" rowspan="1" style="width: 183px;">BankName</th>
            <th class="" rowspan="1" style="width: 183px;">BankNo</th>
            <th class="" rowspan="1" style="width: 183px;">MoMoName</th>
            <th class="" rowspan="1" style="width: 183px;">MoMoNo</th>
        </tr>
    </thead>

    <tbody role="alert" aria-live="polite" aria-relevant="all">
        @foreach ($exports as $export)
        @php
            $msponser = '';
            if(!isset($export->sponser)){
                $msponser = 'tred';
            }
            if(isset($export->msponser)){
                $msponser = 'tyellow';
            }

        @endphp
        <tr class="gradeX even">
            <td class="sorting_1"><span style="color:red;" class="{{isset($export->agent) ? 'tred' : ''}}">{{$export->member_id}}</span></td>
            <td class="center"><span class="{{$msponser}}">{{$export->sponser_id}}</span></td>
            <td class=" "><span>{{$export->firstname}}</span></td>
            <td class=" "><span>{{$export->lastname}}</span></td>
            <td class="center"><span>{{$export->telephone}}</span></td>
            <td class="center"><span>{{$export->address}}</span></td>
            <td class="center"><span>{{$export->period}}</span></td>
            <td class="center"><span>{{$export->nationality}}</span></td>
            <td class="center"><span>{{$export->bank_name}}</span></td>
            <td class="center"><span>{{$export->bank_no}}</span></td>
            <td class="center"><span>{{$export->momo_name ?? null}}</span></td>
            <td class="center"><span>{{$export->momo_no ?? null}}</span></td>
        </tr>
        @endforeach

    </tbody>
</table>
