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
                    <form method="POST" class="row-fluid" action="{{route('admin.agent.edit')}}" class="form-horizontal">
                        @csrf
                        <div class="span4 ml0">
                            <div class="form-group">
                                <label class="control-label">Member ID</label>
                                <input name="member_id" required type="text" class="form-control">
                            </div>
                        </div>
                        <div class="span4 ml0">
                            <div class="form-group">
                                <label class="control-label">First Name</label>
                                <input name="firstname" required type="text" class="form-control">
                            </div>
                        </div>
                        <div class="span4 ml0">
                            <div class="form-group">
                                <label class="control-label">Last Name</label>
                                <input name="lastname" required type="text" class="form-control">
                            </div>
                        </div>
                        <div class="span4 ml0">
                            <div class="form-group">
                                <label class="control-label">Telephone</label>
                                <input name="telephone" required type="text" class="form-control">
                            </div>
                        </div>
                        <div class="span4 ml0">
                            <div class="form-group">
                                <label class="control-label">Address</label>
                                <input name="address" required type="text" class="form-control">
                            </div>
                        </div>
                        <div class="span4 ml0">
                            <div class="form-group">
                                <label class="control-label">Period</label>
                                <input name="period" value="{{date('Y').date('m').date('d')}}" required type="text" class="form-control">
                            </div>
                        </div>
                        <div class="span4 ml0">
                            <div class="form-group">
                                <label class="control-label">Sponser</label>
                                <select class="form-control chosen-select" name="sponser_id" id="">
                                    <option value="">-choose-</option>
                                    @foreach ($sponsers as $sponser)
                                        <option value="{{$sponser->member_id}}">{{$sponser->firstname.' '.$sponser->lastname}}</option>
                                    @endforeach
                                </select>
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
