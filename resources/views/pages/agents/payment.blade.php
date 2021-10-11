@extends('pages.layouts.app')
@section('links')
<link rel="stylesheet" type="text/css" href="/backend/assets/chosen-bootstrap/chosen/chosen.css" />

<style>
    .multi-column {
        /* Standard */
        column-count: 2;
        column-width: 150px;
        /* Webkit-based */
        -webkit-column-count: 2;
        -webkit-column-width: 150px;
        /* Gecko-based */
        -moz-column-count: 2;
        -moz-column-width: 150px;
    }
    .u90 h2{
        text-align: center;
        font-size: 25px;
        margin-bottom: 24px;
    }
    .u90 span.e4{
        font-weight: 600;
    }
    .u90 h6{
        font-size: 15px;
        margin-top: 24px;
    }
</style>
@endsection

@section('scripts')
<script type="text/javascript" src="/backend/assets/chosen-bootstrap/chosen/chosen.jquery.min.js"></script>
<script>
    $(".chosen-select").chosen({no_results_text: "Oops, nothing found!"});
</script>
<script type="text/javascript" src="/backend/assets/chosen-bootstrap/chosen/chosen.jquery.min.js"></script>
<script type="text/javascript" src="/backend/assets/data-tables/jquery.dataTables.js"></script>
<script type="text/javascript" src="/backend/assets/data-tables/DT_bootstrap.js"></script>
<script>
    $(".chosen-select").chosen({no_results_text: "Oops, nothing found!"});
    // $(document).ready(function() {
    //     $('#dtable2').DataTable( {

    //     } );
    // } );
</script>
@endsection

@section('content')
<div class="container-fluid">
    <div class="row-fluid">
        <div class="span12">
            <!-- BEGIN SAMPLE FORM widget-->
            @livewire('agent-payment', ['sponser' => $sponser, 'sponsers' => $sponsers])
            <!-- END SAMPLE FORM widget-->
        </div>
    </div>

</div>
@endsection
