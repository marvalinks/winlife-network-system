@extends('pages.layouts.app')
@section('links')
<link rel="stylesheet" type="text/css" href="/backend/assets/chosen-bootstrap/chosen/chosen.css" />
<link href="https://cdn.datatables.net/1.11.3/css/jquery.dataTables.min.css" rel="stylesheet" />
<style>
    .fm{
        font-size: 17px;
        font-weight: 600;
    }
</style>
@endsection

@section('scripts')
<script type="text/javascript" src="/backend/assets/chosen-bootstrap/chosen/chosen.jquery.min.js"></script>
<script type="text/javascript" src="/backend/assets/data-tables/jquery.dataTables.js"></script>
<script type="text/javascript" src="/backend/assets/data-tables/DT_bootstrap.js"></script>
<script>
    $(".chosen-select").chosen({no_results_text: "Oops, nothing found!"});
    oTable = $('#dtable').DataTable({
            "iDisplayLength": -1,
            ordering: false,
            bPaginate: false
        });
    oTable.fnSort( [[4,'asc'] ] );
</script>


@endsection

@section('content')
<div class="container-fluid">
    <div class="row-fluid">
        <div class="span12">
            <!-- BEGIN PAGE TITLE & BREADCRUMB-->
            <h3 class="page-title">
                System Jobs
                <small>System Jobs Module</small>
            </h3>
            <ul class="breadcrumb">
                <li>
                    <a href="#"><i class="icon-home"></i></a><span class="divider">&nbsp;</span>
                </li>
                <li><a href="#">Home</a> <span class="divider">&nbsp;</span></li>
                <li><a href="#">System Jobs</a><span class="divider-last">&nbsp;</span></li>
            </ul>
            <!-- END PAGE TITLE & BREADCRUMB-->
        </div>
    </div>

    <div class="row-fluid">
        <div class="span12">
            <div class="widget">
                <div class="widget-title">
                    <h4><i class="icon-reorder"></i>Animated Progress Bars</h4>
                    <span class="tools">
                        <a href="javascript:;" class="icon-chevron-down"></a>
                        <a href="javascript:;" class="icon-remove"></a>
                    </span>
                </div>
                <div class="widget-body">
                    @if(count($batches) > 0)
                        <script>
                            window.setInterval(() => {
                                window.location.reload();
                            }, 2000);
                        </script>
                        @foreach ($batches as $key => $batch)
                        <div>
                            <h3 class="fm">Progress No.{{$key+1}}</h3>
                            <p>{{$batch->processedJobs()}}/{{$batch->totalJobs}}</p>
                            <p>({{$batch->progress()}}%)</p>
                        </div>
                        <div class="progress progress-striped progress-{{$batch->progress() >= 100 ? 'warning' : 'success'}}">
                            <div style="width: <?php echo $batch->progress() ?>%" class="bar"></div>
                        </div>
                        @endforeach
                    @else
                        <h4>No tasks running...</h4>
                    @endif
                </div>
            </div>

        </div>
    </div>

</div>
@endsection
