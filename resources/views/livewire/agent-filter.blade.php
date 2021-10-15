<div>
    @if (auth()->user()->roleid == 1)
    <div class="row-fluid">
        <div class="clearfix">
            <div class="btn-group">
                <a href="{{route('admin.agent.add')}}" class="btn green">Add New <i class="icon-plus"></i></a>
                <button wire:click="switchView('r')" class="btn green">Upload Registration</button>
                <button wire:click="switchView('a')" class="btn green">Upload Achivements</button>
            </div>
            <div class="btn-group pull-right" style="margin-right: 10px;">
                <a href="{{route('admin.calculate.bonus')}}" class="btn green">Calculate Bonus <i class="icon-plus"></i></a>
                <button class="btn dropdown-toggle" data-toggle="dropdown">Tools <i class="icon-angle-down"></i></button>
                <ul class="dropdown-menu pull-right">
                    <li><a href="" wire:click.prevent="fixSponsers()">Fix Sponsers</a></li>
                    <li><a href="#">Save as PDF</a></li>
                    <li><a href="#">Export to Excel</a></li>
                </ul>
            </div>
        </div>

    </div>
    <hr>
    @endif
    @if ($showReg)
    <div class="row-fluid">
        <div class="clearfix">
            <form action="" method="get">
                <h4>Agent Registration <span><a href="/formats/12R.xlsx" class="rt56">download format</a></span> </h4>

                @if ($excelLoading)
                    <span style="display: block; color: green;" class="error">uploading excel, please wait...</span>
                @endif
                @if ($excelLoadingSuccess)
                    <span style="display: block; color: green;" class="error">Excel upload successful</span>
                @endif
                <input wire:model="excelfile" type="file" class="form-control">
                @error('excelfile') <span style="display: block; color: red;" class="error">{{ $message }}</span> @enderror
                <button {{$excelLoading ? 'disabled' : ''}} id="sample_editable_1_new" wire:click.prevent="uploadExcel()" class="btn green">Upload Excel <i class="icon-plus"></i></button>
            </form>
        </div>
    </div>
    <hr>
    @endif
    @if ($showArch)
    <div class="row-fluid">
        <div class="clearfix">
            <form action="" method="get">
                <h4>Agent Achivements  <span><a href="/formats/12A.xlsx" class="rt56">download format</a></span></h4>
                @if ($excelLoading)
                    <span style="display: block; color: green;" class="error">uploading excel, please wait...</span>
                @endif
                @if ($excelLoadingSuccess)
                    <span style="display: block; color: green;" class="error">Excel upload successful</span>
                @endif
                <input wire:model="achfile" type="file" class="form-control">
                @error('achfile') <span style="display: block; color: red;" class="error">{{ $message }}</span> @enderror
                <button {{$excelLoading ? 'disabled' : ''}} id="sample_editable_1_new" wire:click.prevent="uploadAchievement()" class="btn green">Upload Excel <i class="icon-plus"></i></button>
            </form>
        </div>
    </div>
    <hr>
    @endif

    @if ($showTemporalTable)
        @if ($showagent56)
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
                            <td class="sorting_1"><input type="text" name="member_id[]" class="m-wrap small {{isset($export->agent) ? '' : 'tred'}}" value="{{$export->member_id}}" /></td>
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
                            <button type="button" wire:click.prevent="cancelPreview()" class="btn">Cancel</button>
                            <a href="{{route('admin.agent.export.aa')}}" class="btn">Export to Excel<i class="icon-export"></i></a>
                            <button type="button" onclick="document.getElementById('a23').submit();" class="btn">Upload to winlife system <i class="icon-plus"></i></button>
                        </div>
                    </div>

                </div>
            </form>
            @endif
        </div>
        @else
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
                            $msponser = '';
                            if(!isset($export->sponser)){
                                $msponser = 'tred';
                            }
                            if(isset($export->msponser)){
                                $msponser = 'tyellow';
                            }

                        @endphp
                        <tr class="gradeX even">
                            <td>{{$key+1}}</td>
                            <td class="sorting_1"><input type="text" class="m-wrap small {{isset($export->agent) ? 'tred' : ''}}" value="{{$export->member_id}}" /></td>
                            <td class="center"><input type="text" class="m-wrap small {{$msponser}}" value="{{$export->sponser_id}}" /></td>
                            <td class=" "><input type="text" class="m-wrap small" value="{{$export->firstname}}" /></td>
                            <td class=" "><input type="text"class="m-wrap small" value="{{$export->lastname}}" /></td>
                            <td class="center"><input type="text" class="m-wrap small" value="{{$export->telephone}}" /></td>
                            <td class="center"><input type="text" class="m-wrap small" value="{{$export->address}}" /></td>
                            <td class="center"><input type="text" class="m-wrap small" value="{{$export->period}}" /></td>
                            <td class="center"><input type="text" class="m-wrap small" value="{{$export->nationality}}" /></td>
                            <td class="center"><input type="text" class="m-wrap small" value="{{$export->bank_name}}" /></td>
                            <td class="center"><input type="text" class="m-wrap small" value="{{$export->bank_no}}" /></td>
                        </tr>
                        @endforeach

                    </tbody>
                </table>
                <hr>
                @if (auth()->user()->roleid == 1)
                <div class="row-fluid">
                    <div class="clearfix">
                        <div class="btn-group pull-right" style="margin-right: 10px;">
                            <button type="button" wire:click.prevent="cancelPreview()" class="btn">Cancel</button>
                            <a href="{{route('admin.agent.export.ar')}}" class="btn">Export to Excel<i class="icon-export"></i></a>
                            <button onclick="document.getElementById('b32').submit();" type="type" class="btn">Upload to winlife system <i class="icon-plus"></i></button>
                        </div>
                    </div>

                </div>
            </form>
            @endif
        </div>
        @endif
    @else
    <div class="row-fluid">
        <form wire:submit.prevent="search" class="fm" action="" method="get">
            <div class="control-group span3">
                <label class="control-label">Member</label>
                <div class="controls">
                    <input type="text" required wire:model="memberid">
                </div>
            </div>
            <div class="control-group span3">
                <label class="control-label">Year</label>
                <div class="controls">
                    <select wire:model="selectedYear" required name="" id="">
                        @for ($i=date('Y'); $i>2010; $i--)
                        <option value="{{$i}}">{{$i}}</option>
                        @endfor
                    </select>
                </div>
            </div>
            <div class="control-group span3">
                <label class="control-label">Month</label>
                <div class="controls">
                    <select wire:model="selectedMonth" required name="" id="">
                        <option selected value="">-choose-</option>
                        @foreach ($months as $month)
                        <option value="{{$month}}">{{$month}}</option>
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
    <div class="widget-body form">
        <table class="table table-striped table-bordered dataTable mx-table" id="dtable" aria-describedby="sample_1_info">
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
                @if ($showtable)
                @php
                    $lv = 1;
                    $lvi = 1;
                    $lvf = 2;
                    $dsd = 1;
                @endphp
                <tr class="gradeX even">
                    <td class="sorting_1">
                        <div class="checker" id="uniform-undefined">
                            <span><input type="checkbox" class="checkboxes" value="1" style="opacity: 0;" /></span>
                        </div>
                    </td>
                    <td>{{$user->firstname.' '.$user->lastname}}</td>
                    <td>{{$user->period}}</td>
                    <td>{{$user->member_id}}</td>
                    <td>0</td>
                    <td>{{$user->stats->level}}</td>
                    <td>{{number_format($user->archievements->where('period', $combPeriod)->sum('total_pv') ?? floatval(0),2)}}</td>
                    <td>{{number_format($user->currentgbv($combPeriod), 2)}}</td>
                    <td>{{number_format($user->archievements->whereBetween('period', [$user->archievements->min('period'), $combPeriodToday])->sum('total_pv') ?? floatval(0), 2)}}</td>
                    <td>{{number_format($user->accgbv($combPeriod), 2)}}</td>
                    <td>{{$user->sponser_id ?? '-'}}</td>
                    <td>{{number_format(($user->currentsalary($combPeriod)->amount ?? 0), 2)}}</td>
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
                        <div class="checker" id="uniform-undefined">
                            <span><input type="checkbox" class="checkboxes" value="1" style="opacity: 0;" /></span>
                        </div>
                    </td>
                    <td>{{$sponser->firstname.' '.$sponser->lastname}}</td>
                    <td>{{$sponser->period}}</td>
                    <td>{{$sponser->member_id}}</td>
                    <td>{{$lv}}</td>
                    <td>{{$sponser->stats->level}}</td>
                    <td>{{number_format($sponser->archievements->where('period', $combPeriod)->sum('total_pv') ?? floatval(0),2)}}</td>
                    <td>{{number_format($sponser->currentgbv($combPeriod), 2)}}</td>
                    <!-- <td>{{number_format($sponser->archievements->where('period', $combPeriod)->sum('total_pv') ?? floatval(0), 2)}}</td> -->
                    <td>{{number_format($sponser->archievements->whereBetween('period', [$sponser->archievements->min('period'), $combPeriodToday])->sum('total_pv') ?? floatval(0), 2)}}</td>
                    <td>{{number_format($sponser->accgbv($combPeriod), 2)}}</td>
                    <td>{{$sponser->sponser_id ?? '-'}}</td>
                    <td>{{number_format(($sponser->currentsalary($combPeriod)->amount ?? 0), 2)}}</td>
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

                @foreach ($sponser->childrenSponsers as $k => $childrenSponser)
                    @php
                        if($sponser->member_id === $childrenSponser->sponser_id){
                            $lvi++;
                        }

                    @endphp
                    @include('pages.fragments.child-sponser', ['child_sponser' => $childrenSponser, 'k' => $k, 'p' => 0])
                @endforeach
                @endforeach
                @endif

            </tbody>
        </table>

    </div>
    @endif
</div>
