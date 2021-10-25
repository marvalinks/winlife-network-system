<tr class="gradeX {{($key+1) % 2 == 0 ? 'even' : 'odd'}}">

    <td>{{$child_sponser->firstname.' '.$child_sponser->lastname}}</td>
    <td>{{$child_sponser->period}}</td>
    <td>{{$child_sponser->member_id}}</td>
    <td>{{$p ? $lvi : $lvf}}</td>
    <td>{{$child_sponser->statlogs->where('period', $combPeriod)->first()->level ?? $child_sponser->stats->level}}</td>
    <td class="tright">{{number_format($child_sponser->archievements->where('period', $combPeriod)->sum('total_pv') ?? floatval(0),2)}}</td>
    <td class="tright">{{number_format($child_sponser->currentgbv($combPeriod), 2)}}</td>
    @if (intval($combPeriod) >= intval($child_sponser->archievements->min('period')))
    <td class="tright">{{number_format($child_sponser->archievements->whereBetween('period', [$child_sponser->archievements->min('period'), $combPeriod])->sum('total_pv') ?? floatval(0), 2)}}</td>
    @else
    <td class="tright">{{number_format(floatval(0), 2)}}</td>
    @endif
    <td class="tright">{{number_format($child_sponser->accgbv($combPeriod), 2)}}</td>
    <td class="tright">{{$child_sponser->sponser_id ?? '-'}}</td>
    <td class="tright {{($child_sponser->currentsalary($combPeriod) && $child_sponser->currentsalary($combPeriod)->active) ? '' : 'tred'}}">{{number_format(($child_sponser->currentsalary($combPeriod)->amount ?? 0), 2)}}</td>

</tr>
@if ($child_sponser->sponsers)
    @foreach ($child_sponser->sponsers->where('period', '<=', $combPeriod) as $k => $childrenSponser)
        @php

            if($child_sponser->member_id === $childrenSponser->sponser_id){
                $lvf++;
            }
        @endphp
        @include('pages.fragments.child-sponser-2', ['child_sponser' => $childrenSponser, 'k' => $k, 'p' => 0])
    @endforeach
@endif
