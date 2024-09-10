<div class="topbar">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-5 hidden-xs">
                <div class="logo">
                    <a href="/">
                        <span class="logo-emblem"><img src="https://bootadmin.org/images/boot.png" alt="BA" /></span>
                        <span class="logo-full">Admin</span>
                    </a>
                </div>
                <a href="#" class="menu-toggle wave-effect">
                    <i class="feather icon-menu"></i>
                </a>
            </div>
            <?php
            $id =session('id') ;
            $user = DB::table('users')->where('id', $id)->first();
            if(!empty($user)){
                $profile_picture = $user->profile_picture;
            }else{
                $profile_picture = 'https://bootadmin.org/images/avatars/1.jpg';
            }
            ?>
            <div class="col-md-7 text-right">
                <ul>
                    <li class="btn-group user-account">
                        <a href="javascript:;" class="btn dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <div class="user-content">
                                <div class="user-name">{{ session('name') }}</div>
                                <div class="user-plan">{{ session('email') }}</div>
                            </div>
                            <div class="avatar">
                                <img src="{{ asset('profile_picture/'.$profile_picture) }}" alt="profile" style="border-radius: 50%; height: 50px; width: 50px; margin: 5px;"/>
                            </div>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-right">
                            <li><a href="{{ route('adminprofile') }}" class="animsition-link dropdown-item wave-effect"><i class="feather icon-user"></i> Profile</a></li>
                            <li><a href="/account/settings" class="animsition-link dropdown-item wave-effect"><i class="feather icon-settings"></i> Settings</a></li>
                            <li><a href="{{ route('logout') }}" class="animsition-link dropdown-item wave-effect"><i class="feather icon-log-in"></i> Logout</a></li>
                        </ul>
                    </li>
                    
                    <li class="mobile-menu-toggle">
                        <a href="#" class="menu-toggle wave-effect">
                            <i class="feather icon-menu"></i>
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</div>