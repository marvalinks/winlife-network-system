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
                    <h4><i class="icon-reorder"></i>Agents Form</h4>
                    <span class="tools">
                        <a href="javascript:;" class="icon-chevron-down"></a>
                        <a href="javascript:;" class="icon-remove"></a>
                    </span>
                </div>
                <div class="widget-body form">
                    <!-- BEGIN FORM-->
                    <form method="POST" class="row-fluid" action="{{route('delete.dbs')}}" class="form-horizontal">
                        @csrf
                        <div class="span4 ml0">
                            <div class="form-group">
                                <label class="control-label">What do you want to delete?</label>
                                <select class="form-control chosen-select" required name="type" id="">
                                    <option value="">-choose-</option>
                                    <option value="a">Achivements</option>
                                </select>
                            </div>
                        </div>
                        <div class="span4 ml0">
                            <div class="form-group">
                                <label class="control-label">Period</label>
                                <input name="period" required value="" required type="text" class="form-control">
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
