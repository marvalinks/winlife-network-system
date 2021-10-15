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
                    <td>{{number_format($sponser->archievements->where('period', $combPeriod)->sum('total_pv') ?? floatval(0), 2)}}</td>
                    <td>{{number_format($sponser->accgbv($combPeriod), 2)}}</td>
                    <td>{{$sponser->sponser_id ?? '-'}}</td>
                    <td>{{number_format(($sponser->currentbonus($combPeriod)->amount ?? 0), 2)}}</td>
                    @if (auth()->user()->roleid == 1)
                        <td>
                            <input type="checkbox" disabled {{($sponser->currentbonus($combPeriod) && $sponser->currentbonus($combPeriod)->paid) ? 'checked' : ''}}>
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
