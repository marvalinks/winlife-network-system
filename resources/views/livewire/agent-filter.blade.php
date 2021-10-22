<div>
    @if (auth()->user()->roleid == 1)
    <div class="row-fluid">
        <div class="clearfix">
            <div class="btn-group">
                <a href="{{route('admin.agent.add')}}" class="btn green">Add New <i class="icon-plus"></i></a>
                <a href="{{route('upload.registration')}}" class="btn green">Upload Registration</a>
                <a href="{{route('upload.achivement')}}" class="btn green">Upload Achivements</a>
            </div>
            @if(auth()->user()->roleid === 1)
            <div class="btn-group pull-right" style="margin-right: 10px;">
                <a href="{{route('admin.calculate.bonus')}}" class="btn green">Calculate Bonus <i class="icon-plus"></i></a>
                <button class="btn dropdown-toggle" data-toggle="dropdown">Tools <i class="icon-angle-down"></i></button>
                <ul class="dropdown-menu pull-right">
                    <li><a href="" wire:click.prevent="fixSponsers()">Fix Sponsers</a></li>
                    <li><a href="{{route('delete.dbs')}}">Delete DBS</a></li>
                    <li><a href="#">Export to Excel</a></li>
                </ul>
            </div>
            @endif
        </div>

    </div>
    <hr>
    @endif

    @if (!$showTemporalTable)
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
        <table class="table table-striped table-bordered dataTable mx-table dtable" id="" aria-describedby="sample_1_info">
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
                    <td>{{number_format($user->archievements->whereBetween('period', [$user->archievements->min('period'), $combPeriod])->sum('total_pv') ?? floatval(0), 2)}}</td>
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
