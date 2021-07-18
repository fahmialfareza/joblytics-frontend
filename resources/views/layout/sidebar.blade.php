@php
    $PATH = explode('/', Request::path());
@endphp

<div class="sidebar sidebar-main">
    <div class="sidebar-content">

        <!-- User menu -->
        <div class="sidebar-user">
            <div class="category-content">
                <div class="media">
                    <div class="media-left">
                        <div id="profileImage" class="img-sm">
                            <img class="img-circle" src="./pp.jpeg" alt="">
                        </div>
                    </div>
                    <div class="media-body">
                        <span class="media-heading text-semibold">
                            Joblytics
                        </span>
                        <div class="text-muted">
                            Admin
                        </div>
                    </div>

                    <div class="media-right media-middle">
                    </div>
                </div>
            </div>
        </div>
        <!-- /user menu -->

        <!-- Main navigation -->
        <div class="sidebar-category sidebar-category-visible">
            <div class="category-content no-padding">
                <ul class="navigation navigation-main navigation-accordion">

                    <!-- Main -->
                    <li class="navigation-header"><span>Menu</span> <i class="icon-menu" title="Main pages"></i></li>
                    <li @if($PATH[0]=='job') class="active" @endif>
                        <a href="/job"><i class="icon-graph"></i><span>Jobs</span></a>
                    </li>
                    <li @if($PATH[0]=='skill') class="active" @endif>
                        <a href="/skill"><i class="icon-stars"></i><span>Skills</span></a>
                    </li>
                    <li @if($PATH[0]=='industry') class="active" @endif>
                        <a href="/industry"><i class="icon-info22"></i><span>Industry</span></a>
                    </li>
                    <li @if($PATH[0]=='needs') class="active" @endif>
                        <a href="/needs"><i class="icon-bookmark"></i><span>Needs</span></a>
                    </li>
                    {{-- <li @if($PATH[0]=='categories') class="active" @endif><a href="/#"><i class="icon-price-tags"></i> <span>Categories</span></a></li>
                    <li @if($PATH[0]=='information') class="active" @endif><a href="/information"><i class="icon-info22"></i> <span>Information</span></a></li>
                    <li @if($PATH[0]=='user') class="active" @endif><a href="/user"><i class="icon-users"></i> <span>User Registered</span></a></li>
                    <li @if($PATH[0]=='booking') class="active" @endif><a href="/booking"><i class="icon-bookmark"></i> <span>Booking</span></a></li>
                    <li @if($PATH[0]=='review') class="active" @endif><a href="/review"><i class="icon-stars"></i> <span>Review</span></a></li>
                    <li @if($PATH[0]=='notification') class="active" @endif><a href="/notification"><i class="icon-bell3"></i> <span>Push Notification</span></a></li>
                    <li @if($PATH[0]=='history') class="active" @endif><a href="/history"><i class="icon-history"></i> <span>History Activity</span></a></li> --}}
                    <hr>
                    <li onclick="logout()"><a href="/auth/logout"><i class="icon-exit3"></i><span>Logout</span></a></li>
                </ul>
            </div>
        </div>
        <!-- /main navigation -->
        <script>
            function logout() {
                localStorage.clear();
                OneSignal.setSubscription(false);
            }
        </script>
    </div>
</div>
