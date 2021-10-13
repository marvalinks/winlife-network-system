@extends('pages.layouts.app')
@section('links')
<link rel="stylesheet" type="text/css" href="/backend/assets/chosen-bootstrap/chosen/chosen.css" />
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
        <div class="span12">
            <!-- BEGIN SAMPLE FORM widget-->
            <div class="widget">
                <div class="widget-title">
                    <h4><i class="icon-reorder"></i>System Awards Form</h4>
                    <span class="tools">
                        <a href="javascript:;" class="icon-chevron-down"></a>
                        <a href="javascript:;" class="icon-remove"></a>
                    </span>
                </div>
                <div class="widget-body form">
                    <!-- BEGIN FORM-->
                    <form method="POST" class="row-fluid" action="{{route('admin.awards.add')}}" class="form-horizontal">
                        @csrf
                        <div class="span4 ml0">
                            <div class="form-group">
                                <label class="control-label">Award</label>
                                <input name="name" required type="text" class="form-control">
                            </div>
                        </div>
                        <div class="span4 ml0">
                            @php
                                $periods = [1, 2, 3];
                            @endphp
                            <div class="form-group">
                                <label class="control-label">Period</label>
                                <select required class="form-control chosen-select" name="period" id="">
                                    <option value="">-choose-</option>
                                    @foreach ($periods as $key => $period)
                                        <option value="{{$period}}">{{$period}} months</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="span4 ml0">
                            @php
                                $levels = [1, 2, 3, 4, 5, 6, 7, 8, 9];
                            @endphp
                            <div class="form-group">
                                <label class="control-label">Minimum level</label>
                                <select required class="form-control chosen-select" name="min_level" id="">
                                    <option value="">-choose-</option>
                                    @foreach ($levels as $key => $level)
                                        <option value="{{$level}}">Level {{$level}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="span4 ml0">
                            <div class="form-group">
                                <label class="control-label">Minimun BV</label>
                                <input name="min_bv" required type="number" min1 class="form-control">
                            </div>
                        </div>
                        <div class="span12">
                            <div class="form-actions">
                              <button type="submit" class="btn btn-success">save</button>
                              <button type="button" class="btn">Cancel</button>
                           </div>
                        </div>

                    </form>
                    <!-- END FORM-->
                </div>
            </div>
            <!-- END SAMPLE FORM widget-->
        </div>
    </div>

</div>
@endsection
