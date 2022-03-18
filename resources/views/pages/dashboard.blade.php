@extends('pages.layouts.app')


@section('content')
    <div class="container-fluid">
        <!-- BEGIN PAGE HEADER-->
        <div class="row-fluid">
            <div class="span12">
                <!-- BEGIN PAGE TITLE & BREADCRUMB-->
                <h3 class="page-title">
                    Dashboard
                    <small> General Information </small>
                </h3>
                <ul class="breadcrumb">
                    <li>
                        <a href="#"><i class="icon-home"></i></a><span class="divider">&nbsp;</span>
                    </li>
                    <li><a href="#">{{ env('APP_NAME') }}</a> <span class="divider">&nbsp;</span></li>
                    <li><a href="#">Dashboard</a><span class="divider-last">&nbsp;</span></li>
                    <li class="pull-right search-wrap">
                        <form class="hidden-phone" action="search_result.html">
                            <div class="search-input-area">
                                <input id=" " class="search-query" type="text" placeholder="Search" />
                                <i class="icon-search"></i>
                            </div>
                        </form>
                    </li>
                </ul>
                <!-- END PAGE TITLE & BREADCRUMB-->
            </div>
        </div>
        <!-- END PAGE HEADER-->
        <!-- BEGIN PAGE CONTENT-->
        <div id="page" class="dashboard">
            <!-- BEGIN OVERVIEW STATISTIC BLOCKS-->
            <div class="row-fluid circle-state-overview">
                <div class="span2 responsive" data-tablet="span3" data-desktop="span2">
                    <div class="circle-stat block">
                        <div class="visual">
                            <div class="circle-state-icon">
                                <i class="icon-user turquoise-color"></i>
                            </div>
                            <input class="knob" data-width="100" data-height="100" data-displayPrevious="true"
                                data-thickness=".2" value="35" data-fgColor="#4CC5CD" data-bgColor="#ddd" />
                        </div>
                        <div class="details">
                            <div class="number">{{ $totalAgents }}</div>
                            <div class="title">Total Agents</div>
                        </div>
                    </div>
                </div>
                <div class="span2 responsive" data-tablet="span3" data-desktop="span2">
                    <div class="circle-stat block">
                        <div class="visual">
                            <div class="circle-state-icon">
                                <i class="icon-tags red-color"></i>
                            </div>
                            <input class="knob" data-width="100" data-height="100" data-displayPrevious="true"
                                data-thickness=".2" value="65" data-fgColor="#e17f90" data-bgColor="#ddd" />
                        </div>
                        <div class="details">
                            <div class="number">{{ $totalUsers }}</div>
                            <div class="title">System Users</div>
                        </div>
                    </div>
                </div>

                <div class="span2 responsive" data-tablet="span3" data-desktop="span2">
                    <div class="circle-stat block">
                        <div class="visual">
                            <div class="circle-state-icon">
                                <i class="icon-shopping-cart green-color"></i>
                            </div>
                            <input class="knob" data-width="100" data-height="100" data-displayPrevious="true"
                                data-thickness=".2" value="30" data-fgColor="#a8c77b" data-bgColor="#ddd" />
                        </div>
                        <div class="details">
                            <div class="number">{{ $totalAchivements }}</div>
                            <div class="title">Total Achivements</div>
                        </div>
                    </div>
                </div>

                <div class="span2 responsive" data-tablet="span3" data-desktop="span2">
                    <div class="circle-stat block">
                        <div class="visual">
                            <div class="circle-state-icon">
                                <i class="icon-comments-alt gray-color"></i>
                            </div>
                            <input class="knob" data-width="100" data-height="100" data-displayPrevious="true"
                                data-thickness=".2" value="15" data-fgColor="#b9baba" data-bgColor="#ddd" />
                        </div>
                        <div class="details">
                            <div class="number">{{ $totalData }}</div>
                            <div class="title">Uploaded Data</div>
                        </div>
                    </div>
                </div>

                <div class="span2 responsive" data-tablet="span3" data-desktop="span2">
                    <div class="circle-stat block">
                        <div class="visual">
                            <div class="circle-state-icon">
                                <i class="icon-eye-open purple-color"></i>
                            </div>
                            <input class="knob" data-width="100" data-height="100" data-displayPrevious="true"
                                data-thickness=".2" value="45" data-fgColor="#c8abdb" data-bgColor="#ddd" />
                        </div>
                        <div class="details">
                            <div class="number">0</div>
                            <div class="title">Unique Visitor</div>
                        </div>
                    </div>
                </div>

                <div class="span2 responsive" data-tablet="span3" data-desktop="span2">
                    <div class="circle-stat block">
                        <div class="visual">
                            <div class="circle-state-icon">
                                <i class="icon-bar-chart blue-color"></i>
                            </div>
                            <input class="knob" data-width="100" data-height="100" data-displayPrevious="true"
                                data-thickness=".2" value="25" data-fgColor="#93c4e4" data-bgColor="#ddd" />
                        </div>
                        <div class="details">
                            <div class="number">{{ $totalJobs }}</div>
                            <div class="title">System Jobs</div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- END OVERVIEW STATISTIC BLOCKS-->

            <div class="row-fluid">
                <div class="span8">
                    <!-- BEGIN SITE VISITS PORTLET-->
                    <div class="widget">
                        <div class="widget-title">
                            <h4><i class="icon-bar-chart"></i> Line Chart</h4>
                            <span class="tools">
                                <a href="javascript:;" class="icon-chevron-down"></a>
                                <a href="javascript:;" class="icon-remove"></a>
                            </span>
                        </div>
                        <div class="widget-body">
                            <div id="site_statistics_loading">
                                <img src="img/loading.gif" alt="loading" />
                            </div>
                            <div id="site_statistics_content" class="hide">
                                <div id="site_statistics" class="chart"></div>
                            </div>
                        </div>
                    </div>
                    <!-- END SITE VISITS PORTLET-->
                </div>
                <div class="span4">
                    <!-- BEGIN SERVER LOAD PORTLET-->
                    <div class="widget">
                        <div class="widget-title">
                            <h4><i class="icon-umbrella"></i> Live Chart</h4>
                            <span class="tools">
                                <a href="javascript:;" class="icon-chevron-down"></a>
                                <a href="javascript:;" class="icon-remove"></a>
                            </span>
                        </div>
                        <div class="widget-body">
                            <div id="load_statistics_loading">
                                <img src="img/loading.gif" alt="loading" />
                            </div>
                            <div id="load_statistics_content" class="hide" style="margin: 0px 0 20px 0;">
                                <div id="load_statistics" class="chart" style="height: 280px;"></div>
                            </div>
                        </div>
                    </div>
                    <!-- END SERVER LOAD PORTLET-->
                </div>
            </div>
        </div>
        <!-- END PAGE CONTENT-->
    </div>
@endsection
