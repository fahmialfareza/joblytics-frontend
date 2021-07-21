@php
    $PATH = explode('/', Request::path());
@endphp

<div class="sidebar sidebar-main bg-primary">
    <div class="sidebar-content">
        <!-- User menu -->
        <div class="sidebar-user">
            <div class="category-content">
                <div class="media">
                    <div class="media-left">
                        <div id="profileImage" class="img-sm">
                            <img class="img-circle" src="assets/images/placeholder.jpg" alt="">
                        </div>
                    </div>
                    <div class="media-body">
                        <span class="media-heading text-semibold">
                            Joblytics
                        </span>
                        <div class="text-muted">
                            Dashboard
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
                    <li @if($PATH[0]=='overview') class="active" @endif>
                        <a href="/overview"><i class="icon-graph"></i><span>Overview</span></a>
                    </li>
                    <li @if($PATH[0]=='trends') class="active" @endif>
                        <a href="/trends"><i class="icon-stats-growth"></i><span>Trends</span></a>
                    </li>
                    <li @if($PATH[0]=='comparison') class="active" @endif>
                        <a href="/comparison"><i class="icon-stats-bars3"></i><span>Comparison</span></a>
                    </li>
                    <li @if($PATH[0]=='job-details') class="active" @endif>
                        <a href="/job-details"><i class="icon-graph"></i><span>Job Details</span></a>
                    </li>
                    <li @if($PATH[0]=='future-jobs') class="active" @endif>
                        <a href="/future-jobs"><i class="icon-user-tie"></i><span>Future of Jobs</span></a>
                    </li>
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
