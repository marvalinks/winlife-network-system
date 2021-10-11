<div>
    <div class="widget">
        <div class="widget-title">
            <h4><i class="icon-reorder"></i>Agents Form</h4>
            <span class="tools">
                <a href="javascript:;" class="icon-chevron-down"></a>
                <a href="javascript:;" class="icon-remove"></a>
            </span>
        </div>
        <hr>
        <div class="row-fluid">
            <div class="clearfix">
                <div class="btn-group" style="margin-left: 10px;">
                    <h2>GHC 0.0</h2>
                </div>
                @if (auth()->user()->roleid == 1)
                <div class="btn-group pull-right" style="margin-right: 10px;">
                    @if (!$bulkDisabled)
                    <button wire:click.prevent="preview"
                        onclick="confirm('Are you sure ?') || event.stopImmediatePropagation()"
                        class="btn green">
                        Pay Agents <i class="icon-plus"></i>
                    </button>
                    @endif
                </div>
                @endif
            </div>

        </div>
        <hr>
        <div class="widget-body form">
            <h2>Downlines</h2>
            <hr>
            <div class="widget-body form">
                <table class="table table-striped table-bordered dataTable mx-table" id="dtable2" aria-describedby="sample_1_info">
                    <thead>
                        <tr role="row">
                            <th style="width: 24px;" class="sorting_disabled" role="columnheader" rowspan="1" colspan="1" aria-label="">
                                <div class="" id="uniform-undefined">
                                    <span><input wire:model="selectAll" type="checkbox" class="" /></span>
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
                        </tr>
                    </thead>

                    <tbody role="alert" aria-live="polite" aria-relevant="all">
                        @php
                            $lv = 1;
                            $lvi = 1;
                            $lvf = 2;
                            $combPeriod = date('Y').date('m');
                        @endphp
                        <tr class="gradeX even">
                            <td class="sorting_1">
                                <div class="" id="">
                                    <span><input wire:model="selectedAgents" value="{{$sponser->member_id}}" type="checkbox" class="" /></span>
                                </div>
                            </td>
                            <td>{{$sponser->firstname.' '.$sponser->lastname}}</td>
                            <td>{{$sponser->period}}</td>
                            <td>{{$sponser->member_id}}</td>
                            <td>0</td>
                            <td>{{$sponser->stats->level}}</td>
                            <td>{{number_format($sponser->archievements->where('period', $combPeriod)->sum('total_pv') ?? floatval(0),2)}}</td>
                            <td>{{number_format($sponser->currentgbv($combPeriod), 2)}}</td>
                            <td>{{number_format($sponser->archievements->where('period', $combPeriod)->sum('total_pv') ?? floatval(0), 2)}}</td>
                            <td>{{number_format($sponser->accgbv($combPeriod), 2)}}</td>
                            <td>{{$sponser->sponser_id ?? '-'}}</td>
                            <td>{{number_format(($sponser->currentbonus($combPeriod)->amount ?? 0), 2)}}</td>
                            <td></td>
                            <td></td>
                        </tr>

                        @foreach ($sponsers as $key => $spp)
                        <tr class="gradeX {{($key+1) % 2 == 0 ? 'even' : 'odd'}}">
                            <td class="sorting_1">
                                <div class="" id="uniform-undefined">
                                    <span><input wire:model="selectedAgents" value="{{$spp->member_id}}" type="checkbox" class="" /></span>
                                </div>
                            </td>
                            <td>{{$spp->firstname.' '.$spp->lastname}}</td>
                            <td>{{$spp->period}}</td>
                            <td>{{$spp->member_id}}</td>
                            <td>{{$lv}}</td>
                            <td>{{$spp->stats->level}}</td>
                            <td>{{number_format($spp->archievements->where('period', $combPeriod)->sum('total_pv') ?? floatval(0),2)}}</td>
                            <td>{{number_format($spp->currentgbv($combPeriod), 2)}}</td>
                            <td>{{number_format($spp->archievements->where('period', $combPeriod)->sum('total_pv') ?? floatval(0), 2)}}</td>
                            <td>{{number_format($spp->accgbv($combPeriod), 2)}}</td>
                            <td>{{$spp->sponser_id ?? '-'}}</td>
                            <td>{{number_format(($spp->currentbonus($combPeriod)->amount ?? 0), 2)}}</td>
                            <td>
                                <a href="{{route('admin.agent.edit', [$spp->member_id])}}">Adjust</a>
                            </td>
                            <td></td>
                        </tr>

                        @foreach ($spp->childrenSponsers as $k => $childrenSponser)
                            @php
                                if($spp->member_id === $childrenSponser->sponser_id){
                                    $lvi++;
                                }

                            @endphp
                            @include('pages.fragments.child-sponser', ['child_sponser' => $childrenSponser, 'k' => $k, 'p' => 0])
                        @endforeach
                        @endforeach

                    </tbody>
                </table>

            </div>
        </div>
        @for ($i=1; $i < 3; $i++)
        <hr>
        <div class="row-fluid w94 u90">
            <h2>{{env('APP_NAME')}} Bonus Payment Receipt</h2>
            <div class="row-fluid">
                <div class="span6">
                    <p>
                        <span class="e4">Bill No:</span>
                        <span>{{date('Y').date('m').mt_rand(100, 999)}}</span>
                    </p>
                </div>
                <div class="span3">
                    <p>
                        <span class="e4">Method:</span>
                        <span>Cash</span>
                    </p>
                </div>
                <div class="span3">
                    <p>
                        <span class="e4">Cheque No.:</span>
                        <span>-</span>
                    </p>
                </div>
            </div>
            <div class="row-fluid">
                <div class="span6">
                    <p>
                        <span class="e4">Payment Collected By:</span>
                        <span>{{$sponser->firstname.' '.$sponser->lastname}}</span>
                    </p>
                </div>
                <div class="span3">
                    <p>
                        <span class="e4">ID:</span>
                        <span>{{$sponser->member_id}}</span>
                    </p>
                </div>
                <div class="span3">
                    <p>
                        <span class="e4">Tel No.:</span>
                        <span>{{$sponser->telephone ?? '-'}}</span>
                    </p>
                </div>
            </div>
            <h6>Bonus Details</h6>
            <div class="row-fluid">
                <div class="span6">
                    <table class="table table-bordered table-hover">
                        <thead>
                            <tr>
                                <th>ID No.</th>
                                <th>Name</th>
                                <th>Bonus</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($firstPreview as $user)
                            <tr>
                                <td>{{$user->member_id}}</td>
                                <td>{{$user->fistname.' '.$user->lastname}}</td>
                                <td>{{number_format(($user->currentbonus($combPeriod)->amount ?? 0), 2)}}</td>
                            </tr>
                            @endforeach

                        </tbody>
                    </table>
                </div>
                <div class="span6">
                    <table class="table table-bordered table-hover">
                        <thead>
                            <tr>
                                <th>ID No.</th>
                                <th>Name</th>
                                <th>Bonus</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($secondPreview as $user)
                            <tr class="gradeX even">
                                <td>{{$user->member_id}}</td>
                                <td>{{$user->fistname.' '.$user->lastname}}</td>
                                <td>{{number_format(($user->currentbonus($combPeriod)->amount ?? 0), 2)}}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        @endfor
        <div class="row-fluid" style="padding: 20px;">
            <button class="btn green">Print Receipt</button>
            <button class="btn green">Pay Agents</button>
        </div>
    </div>
</div>
