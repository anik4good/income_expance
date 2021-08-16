<header class="header-top" header-theme="light">
    <div class="container-fluid">
        <div class="d-flex justify-content-between">
            <div class="top-menu d-flex align-items-center">
                <button type="button" class="btn-icon mobile-nav-toggle d-lg-none"><span></span></button>
                <div class="header-search">
                    <div class="input-group">

                        <span class="input-group-addon search-close">
                            <i class="ik ik-x"></i>
                        </span>
                        <input type="text" class="form-control">
                        <span class="input-group-addon search-btn"><i class="ik ik-search"></i></span>
                    </div>
                </div>
                <button class="nav-link" title="clear cache">
                    <a href="{{route('cache.clear.backend')}}">
                        <i class="ik ik-battery-charging"></i>
                    </a>
                </button> &nbsp;&nbsp;
                <button type="button" id="navbar-fullscreen" class="nav-link"><i class="ik ik-maximize"></i></button>

                <button class="nav-link" title="clear cache">
                    <a href="{{route('frontend.home')}}" target="_blanks">
                        <i class="ik ik-home"></i>
                    </a>
                </button> &nbsp;&nbsp;


            </div>
            <div class="top-menu d-flex align-items-center">
                <span class="text-sm-center">{{Auth::user()->name}}
                 <span
                     class="badge badge-pill badge-info" style="padding: 2px 5px">{{  Auth::user()->role() }}</span>
                </span>

                <div class="dropdown">
                    <a class="dropdown-toggle" href="#" id="userDropdown" role="button" data-toggle="dropdown"
                       aria-haspopup="true" aria-expanded="false">

                        @hasrole('SuperAdmin')
                        <img class="avatar"
                             src="{{ Auth::user()->getFirstMediaUrl('avatar') == null ? Auth::user()->getFirstMediaUrl('avatar') : asset('assets/backend/img/super_admin.jpg') }}"
                        ></a>

                    @else
                        <img class="avatar"
                             src="{{ Auth::user()->getFirstMediaUrl('avatar') != null ? Auth::user()->getFirstMediaUrl('avatar') : config('app.placeholder').'160' }}"
                        ></a>
                        @endhasrole



                        <div class="dropdown-menu dropdown-menu-right" aria-labelledby="userDropdown">
                            <a class="dropdown-item" href="{{route('profile.index')}}"><i
                                    class="ik ik-user dropdown-icon"></i> {{ __('Profile')}}</a>

                            <a class="dropdown-item" href="{{ url('logout') }}">
                                <i class="ik ik-power dropdown-icon"></i>
                                {{ __('Logout')}}
                            </a>
                        </div>
                </div>
            </div>
        </div>
    </div>
</header>
