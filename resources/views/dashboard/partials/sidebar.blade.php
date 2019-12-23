<aside id="sidebar-wrapper">
    <div class="sidebar-brand">
        <a href="">{{ env('APP_NAME', 'PhotoLife') }}</a>
    </div>
    <div class="sidebar-brand sidebar-brand-sm">
        <a href="#">{{ strtoupper(substr(env('APP_NAME', 'PhotoLife'), 0, 2)) }}</a>
    </div>
    <ul class="sidebar-menu">
        <li class="menu-header">Dashboard</li>
        <li class="{{ request()->is('dashboard') ? 'active' : '' }}">
            <a class="nav-link" href="{{ route('dashboard.index') }}">
                <i class="fa fa-columns"></i>
                <span>Dashboard</span>
            </a>
        </li>
        <li class="{{ request()->is('dashboard/pages') ? 'active' : '' }}">
            <a href="{{ route('dashboard.pages.index') }}">
                <i class="fa fa-pager"></i>
                <span>Pages</span>
            </a>
        </li>
        <li class="menu-header">Members</li>
        <li class="{{ request()->is('dashboard/members') ? 'active' : '' }}">
            <a class="nav-link" href="{{ route('dashboard.members.index') }}">
                <i class="fa fa-users"></i>
                <span>Members</span>
            </a>
        </li>
        <li class="menu-header">Posts</li>
        <li class="{{ request()->is('dashboard/posts') ? 'active' : '' }}">
            <a class="nav-link" href="{{ route('dashboard.posts.index') }}">
                <i class="fa fa-newspaper"></i>
                <span>Posts</span>
            </a>
        </li>
        <li class="{{ request()->is('dashboard/reports') ? 'active' : '' }}">
            <a class="nav-link" href="{{ route('dashboard.reports.index') }}">
                <i class="fa fa-bug"></i>
                <span>Reports</span>
            </a>
        </li>
        <li class="menu-header">Settings</li>
        <li>
            <a class="nav-link" href="{{ route('dashboard.settings.index') }}">
                <i class="fa fa-cog"></i>
                <span>Settings</span>
            </a>
        </li>
    </ul>
</aside>
