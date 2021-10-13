<div id="sidebar" class="nav-collapse collapse">
    <!-- BEGIN SIDEBAR TOGGLER BUTTON -->
    <div class="sidebar-toggler hidden-phone"></div>
    <!-- BEGIN SIDEBAR TOGGLER BUTTON -->

    <!-- BEGIN RESPONSIVE QUICK SEARCH FORM -->
    <div class="navbar-inverse">
        <form class="navbar-search visible-phone">
            <input type="text" class="search-query" placeholder="Search" />
        </form>
    </div>
    <!-- END RESPONSIVE QUICK SEARCH FORM -->
    <!-- BEGIN SIDEBAR MENU -->
    <ul class="sidebar-menu">
        <li>
            <a class="" href="{{route('admin.dashboard')}}">
                <span class="icon-box"><i class="icon-tasks"></i></span>
                <span>Dashboard</span>
            </a>
        </li>
        <li>
            <a class="" href="#">
                <span class="icon-box"><i class="icon-qrcode"></i></span>
                <span>Product Managment</span>
            </a>
        </li>
        @if (auth()->user()->roleid == 1)
        <li>
            <a class="" href="{{route('admin.users')}}">
                <span class="icon-box"><i class="icon-star"></i></span>
                <span>User Managment</span>
            </a>
        </li>
        @endif
        <li>
            <a class="" href="{{route('admin.agents')}}">
                <span class="icon-box"><i class="icon-user"></i></span>
                <span>Agent Managment</span>
            </a>
        </li>
        @if (auth()->user()->roleid == 1)
        <li>
            <a class="" href="{{route('admin.awards')}}">
                <span class="icon-box"><i class="icon-money"></i></span>
                <span>Awards</span>
            </a>
        </li>
        @endif
    </ul>
    <!-- END SIDEBAR MENU -->
</div>
