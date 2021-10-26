@extends('pages.layouts.error')
@section('links')
<link rel="stylesheet" type="text/css" href="/backend/assets/chosen-bootstrap/chosen/chosen.css" />
<link href="https://cdn.datatables.net/1.11.3/css/jquery.dataTables.min.css" rel="stylesheet" />
<style>
    .fm{
        /* display: flex;
        justify-content: space-evenly; */
        padding-left: 21px;
    }
    .clearfix{
        padding-left: 15px;
        padding-top: 7px;
    }
    .rt56{
        font-size: 13px;
        text-decoration: underline;
    }
    .fm .control-group input,
    .fm .control-group select{
        width: 100%;
    }
</style>
@endsection

@section('scripts')
<script type="text/javascript" src="/backend/assets/chosen-bootstrap/chosen/chosen.jquery.min.js"></script>
<script type="text/javascript" src="/backend/assets/data-tables/jquery.dataTables.js"></script>
<script type="text/javascript" src="/backend/assets/data-tables/DT_bootstrap.js"></script>
<script>
    $(".chosen-select").chosen({no_results_text: "Oops, nothing found!"});
    window.addEventListener('reopenDatatable', event => {
        oTable = $('.dtable').DataTable({
            "iDisplayLength": -1
        });
        oTable.fnSort( [ [4,'asc'] ] );
    })
</script>

@endsection

@section('content')
<div class="container-fluid">
    <!-- BEGIN PAGE HEADER-->
    <div class="row-fluid">
        <div class="span12">
        </div>
    </div>
    <!-- END PAGE HEADER-->
    <!-- BEGIN PAGE CONTENT-->
    <div class="row-fluid">
        <div class="span12">
            <div class="space20"></div>
            <div class="space20"></div>
            <div class="widget-body">
                <div class="error-page" style="min-height: 800px;">
                    <img src="/backend/img/404.png" alt="404 error" />
                    <h1>
                        <strong>404</strong> <br />
                        Page Not Found
                    </h1>
                    <p>We are sorry, the page you were looking for does not exist anymore.</p>
                    <a href="{{route('admin.dashboard')}}" class="btn green">Go back</a>
                </div>
            </div>
        </div>
    </div>
    <!-- END PAGE CONTENT-->
</div>

@endsection
