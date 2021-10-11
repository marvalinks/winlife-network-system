<tr class="gradeX {{($key+1) % 2 == 0 ? 'even' : 'odd'}}">
    <td class="sorting_1">
        <div class="" id="uniform-undefined">
            <span><input wire:model="selectedAgents" value="{{$child_sponser->member_id}}" type="checkbox" class="checkboxes" /></span>
        </div>
    </td>
    <td>{{$child_sponser->firstname.' '.$child_sponser->lastname}}</td>
    <td>{{$child_sponser->period}}</td>
    <td>{{$child_sponser->member_id}}</td>
    <td>{{$p ? $lvi : $lvf}}</td>
    <td>{{$child_sponser->stats->level}}</td>
    <td>{{number_format($child_sponser->archievements->where('period', $combPeriod)->sum('total_pv') ?? floatval(0),2)}}</td>
    <td>{{number_format($child_sponser->currentgbv($combPeriod), 2)}}</td>
    <td>{{number_format($child_sponser->archievements->where('period', $combPeriod)->sum('total_pv') ?? floatval(0), 2)}}</td>
    <td>{{number_format($child_sponser->accgbv($combPeriod), 2)}}</td>
    <td>{{$child_sponser->sponser_id ?? '-'}}</td>
    <td>{{number_format(($child_sponser->currentbonus($combPeriod)->amount ?? 0), 2)}}</td>
    <td>
        <a href="{{route('admin.agent.edit', [$child_sponser->member_id])}}">Adjust</a>
    </td>
    <td></td>
</tr>
@if ($child_sponser->sponsers)
    @foreach ($child_sponser->sponsers as $k => $childrenSponser)
        @php

            if($child_sponser->member_id === $childrenSponser->sponser_id){
                $lvf++;
            }
        @endphp
        @include('pages.fragments.child-sponser', ['child_sponser' => $childrenSponser, 'k' => $k, 'p' => 0])
    @endforeach
@endif
