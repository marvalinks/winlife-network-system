@extends('pages.layouts.app')


@section('content')
<div class="container-fluid">
    <!-- BEGIN PAGE HEADER-->
    <div class="row-fluid">
        <div class="span12">
            <!-- BEGIN THEME CUSTOMIZER-->
            <div id="theme-change" class="hidden-phone">
                <i class="icon-cogs"></i>
                <span class="settings">
                    <span class="text">Theme:</span>
                    <span class="colors">
                        <span class="color-default" data-style="default"></span>
                        <span class="color-gray" data-style="gray"></span>
                        <span class="color-purple" data-style="purple"></span>
                        <span class="color-navy-blue" data-style="navy-blue"></span>
                    </span>
                </span>
            </div>
            <!-- END THEME CUSTOMIZER-->
            <!-- BEGIN PAGE TITLE & BREADCRUMB-->
            <h3 class="page-title">
                Dashboard
                <small> General Information </small>
            </h3>
            <ul class="breadcrumb">
                <li>
                    <a href="index.html#"><i class="icon-home"></i></a><span class="divider">&nbsp;</span>
                </li>
                <li><a href="index.html#">Admin Lab</a> <span class="divider">&nbsp;</span></li>
                <li><a href="index.html#">Dashboard</a><span class="divider-last">&nbsp;</span></li>
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
                        <input class="knob" data-width="100" data-height="100" data-displayPrevious="true" data-thickness=".2" value="35" data-fgColor="#4CC5CD" data-bgColor="#ddd" />
                    </div>
                    <div class="details">
                        <div class="number">+33</div>
                        <div class="title">New Users</div>
                    </div>
                </div>
            </div>
            <div class="span2 responsive" data-tablet="span3" data-desktop="span2">
                <div class="circle-stat block">
                    <div class="visual">
                        <div class="circle-state-icon">
                            <i class="icon-tags red-color"></i>
                        </div>
                        <input class="knob" data-width="100" data-height="100" data-displayPrevious="true" data-thickness=".2" value="65" data-fgColor="#e17f90" data-bgColor="#ddd" />
                    </div>
                    <div class="details">
                        <div class="number">987$</div>
                        <div class="title">Sales</div>
                    </div>
                </div>
            </div>

            <div class="span2 responsive" data-tablet="span3" data-desktop="span2">
                <div class="circle-stat block">
                    <div class="visual">
                        <div class="circle-state-icon">
                            <i class="icon-shopping-cart green-color"></i>
                        </div>
                        <input class="knob" data-width="100" data-height="100" data-displayPrevious="true" data-thickness=".2" value="30" data-fgColor="#a8c77b" data-bgColor="#ddd" />
                    </div>
                    <div class="details">
                        <div class="number">+320</div>
                        <div class="title">New Orders</div>
                    </div>
                </div>
            </div>

            <div class="span2 responsive" data-tablet="span3" data-desktop="span2">
                <div class="circle-stat block">
                    <div class="visual">
                        <div class="circle-state-icon">
                            <i class="icon-comments-alt gray-color"></i>
                        </div>
                        <input class="knob" data-width="100" data-height="100" data-displayPrevious="true" data-thickness=".2" value="15" data-fgColor="#b9baba" data-bgColor="#ddd" />
                    </div>
                    <div class="details">
                        <div class="number">387</div>
                        <div class="title">Comments</div>
                    </div>
                </div>
            </div>

            <div class="span2 responsive" data-tablet="span3" data-desktop="span2">
                <div class="circle-stat block">
                    <div class="visual">
                        <div class="circle-state-icon">
                            <i class="icon-eye-open purple-color"></i>
                        </div>
                        <input class="knob" data-width="100" data-height="100" data-displayPrevious="true" data-thickness=".2" value="45" data-fgColor="#c8abdb" data-bgColor="#ddd" />
                    </div>
                    <div class="details">
                        <div class="number">+987</div>
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
                        <input class="knob" data-width="100" data-height="100" data-displayPrevious="true" data-thickness=".2" value="25" data-fgColor="#93c4e4" data-bgColor="#ddd" />
                    </div>
                    <div class="details">
                        <div class="number">478</div>
                        <div class="title">Updates</div>
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
        <!-- BEGIN SQUARE STATISTIC BLOCKS-->
        <div class="square-state">
            <div class="row-fluid">
                <a class="icon-btn span2" href="index.html#">
                    <i class="icon-group"></i>
                    <div>Users</div>
                    <span class="badge badge-important">2</span>
                </a>
                <a class="icon-btn span2" href="index.html#">
                    <i class="icon-barcode"></i>
                    <div>Products</div>
                    <span class="badge badge-success">4</span>
                </a>
                <a class="icon-btn span2" href="index.html#">
                    <i class="icon-bar-chart"></i>
                    <div>Reports</div>
                </a>
                <a class="icon-btn span2" href="index.html#">
                    <i class="icon-calendar"></i>
                    <div>Calendar</div>
                </a>
                <a class="icon-btn span2" href="index.html#">
                    <i class="icon-sitemap"></i>
                    <div>Categories</div>
                </a>
                <a class="icon-btn span2" href="index.html#">
                    <i class="icon-tasks"></i>
                    <div>Task</div>
                    <span class="badge badge-important">3</span>
                </a>
            </div>
            <div class="row-fluid">
                <a class="icon-btn span2" href="index.html#">
                    <i class="icon-envelope"></i>
                    <div>Inbox</div>
                    <span class="badge badge-info">12</span>
                </a>
                <a class="icon-btn span2" href="index.html#">
                    <i class="icon-bullhorn"></i>
                    <div>Notification</div>
                    <span class="badge badge-warning">3</span>
                </a>
                <a class="icon-btn span2" href="index.html#">
                    <i class="icon-plane"></i>
                    <div>Projects</div>
                    <span class="badge badge-info">21</span>
                </a>
                <a class="icon-btn span2" href="index.html#">
                    <i class="icon-money"></i>
                    <div>Finance</div>
                </a>
                <a class="icon-btn span2" href="index.html#">
                    <i class="icon-thumbs-up"></i>
                    <div>Feedback</div>
                    <span class="badge badge-info">2</span>
                </a>
                <a class="icon-btn span2" href="index.html#">
                    <i class="icon-wrench"></i>
                    <div>Settings</div>
                </a>
            </div>
        </div>
    </div>
    <!-- END PAGE CONTENT-->
</div>
@endsection
