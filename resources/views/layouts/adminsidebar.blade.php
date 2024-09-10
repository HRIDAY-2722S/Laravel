<div class="sidebar">
    <div class="logo">
        <a href="/admin">
            <span class="logo-emblem"><img src="{{ asset('profile_picture/Laravel.png') }}" alt="BA" /></span>
            <span class="logo-full">Admin</span>
        </a>
    </div>
    <ul id="sidebarCookie">
        <li class="spacer"></li>
        <li class="spacer"></li>
        <li class="nav-item">
            <a href="{{ route('admindashboard') }}" class="nav-link wave-effect collapsed wave-effect {{ Request::route()->getName() == 'admindashboard'? 'active' : '' }}">
                <i class="feather icon-grid"></i>
                <span class="menu-title">Dashboard</span>
            </a>
        </li>
        <li class="nav-item">
            <a href="{{ route('products') }}" class="nav-link wave-effect collapsed {{ Request::route()->getName() == 'products'? 'active' : '' }}">
                <i class="feather icon-sidebar"></i>
                <span class="menu-title">Products</span>
            </a>
        </li>
        <li class="nav-item">
            <a href="{{ route('users') }}" class="nav-link wave-effect collapsed {{ Request::route()->getName() == 'users'? 'active' : '' }}">
                <i class="feather icon-users"></i>
                <span class="menu-title">Users</span>
            </a>
        </li>
        <li class="nav-item">
            <a href="{{ route('add_products') }}" class="nav-link wave-effect collapsed {{ Request::route()->getName() == 'add_products'? 'active' : '' }}">
                <i class="feather icon-layout"></i>
                <span class="menu-title">Add Products</span>
            </a>
        </li>
        <li class="nav-item">
            <a href="{{ route('users_orders') }}" class="nav-link wave-effect collapsed {{ Request::route()->getName() == 'users_orders'? 'active' : '' }}">
                <i class="feather icon-mail"></i>
                <span class="menu-title">Orders</span>
            </a>
        </li>
        <li class="nav-item">
            <a href="{{ route('user_quotations') }}" class="nav-link wave-effect nav-single {{ Request::route()->getName() == 'user_quotations'? 'active' : '' }}">
                <i class="feather icon-award"></i>
                <span class="menu-title">Quotations</span>
            </a>
        </li>
        <li class="nav-item">
            <a href="{{ route('coupons') }}" class="nav-link wave-effect nav-single {{ Request::route()->getName() == 'coupons'? 'active' : '' }}">
                <i class="feather icon-package"></i>
                <span class="menu-title">Coupons</span>
            </a>
        </li>
    </ul>
</div>
<style>
    .active{
        background-color: #5D8AA8;
    }
</style>