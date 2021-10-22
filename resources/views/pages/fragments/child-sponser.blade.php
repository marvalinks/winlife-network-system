<tr class="gradeX {{($key+1) % 2 == 0 ? 'even' : 'odd'}}">
    <td class="sorting_1">
        <div class="" id="uniform-undefined">
            <span><input {{isset($dsd) && $dsd == 1 ? 'disabled': ''}} wire:model="selectedAgents" value="{{$child_sponser->member_id}}" type="checkbox" class="checkboxes" /></span>
        </div>
    </td>
    <td>{{$child_sponser->firstname.' '.$child_sponser->lastname}}</td>
    <td>{{$child_sponser->period}}</td>
    <td>{{$child_sponser->member_id}}</td>
    <td>{{$p ? $lvi : $lvf}}</td>
    <td>{{$child_sponser->statlogs->where('period', $combPeriod)->first()->level ?? $child_sponser->stats->level}}</td>
    <td>{{number_format($child_sponser->archievements->where('period', $combPeriod)->sum('total_pv') ?? floatval(0),2)}}</td>
    <td>{{number_format($child_sponser->currentgbv($combPeriod), 2)}}</td>
    <!-- <td>{{number_format($child_sponser->archievements->where('period', $combPeriod)->sum('total_pv') ?? floatval(0), 2)}}</td> -->
    @if (intval($combPeriod) >= intval($child_sponser->archievements->min('period')))
    <td>{{number_format($child_sponser->archievements->whereBetween('period', [$child_sponser->archievements->min('period'), $combPeriod])->sum('total_pv') ?? floatval(0), 2)}}</td>
    @else
    <td>{{number_format(floatval(0), 2)}}</td>
    @endif
    <!-- <td>{{number_format($child_sponser->archievements->whereBetween('period', [$child_sponser->archievements->min('period'), $combPeriod])->sum('total_pv') ?? floatval(0), 2)}}</td> -->
    <td>{{number_format($child_sponser->accgbv($combPeriod), 2)}}</td>
    <td>{{$child_sponser->sponser_id ?? '-'}}</td>
    <td>{{number_format(($child_sponser->currentsalary($combPeriod)->amount ?? 0), 2)}}</td>
    @if (auth()->user()->roleid == 1)
        <td>
            <input type="checkbox" disabled {{($child_sponser->currentsalary($combPeriod) && $child_sponser->currentsalary($combPeriod)->paid) ? 'checked' : ''}}>
        </td>
    @endif
    <td>
        <a href="{{route('admin.agent.edit', [$child_sponser->member_id])}}">Adjust</a>
    </td>
    <td></td>
</tr>
@if ($child_sponser->sponsers)
    @foreach ($child_sponser->sponsers->where('period', '<=', $combPeriod) as $k => $childrenSponser)
        @php

            if($child_sponser->member_id === $childrenSponser->sponser_id){
                $lvf++;
            }
        @endphp
        @include('pages.fragments.child-sponser', ['child_sponser' => $childrenSponser, 'k' => $k, 'p' => 0])
    @endforeach
@endif
