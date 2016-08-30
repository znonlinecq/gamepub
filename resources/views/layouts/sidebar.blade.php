 <!-- Left side column. contains the logo and sidebar -->
  <aside class="main-sidebar">

    <!-- sidebar: style can be found in sidebar.less -->
    <section class="sidebar">
    <li class="header"></li> 
     <!-- Sidebar Menu -->
      <ul class="sidebar-menu">
        <!-- Optionally, you can add icons to the links -->
        @foreach($menus as $menu)
        <li class="treeview @if($menu->active) active @endif" >
            <a href="#"><i class="fa fa-link"></i>{{$menu->name}}<span></span></a>
            <ul class="treeview-menu">
                @foreach($menu->functions as $submenu)
                    <li @if($submenu->active) class="active" @endif><a href="{{url($submenu->path)}}">{{$submenu->name}}</a></li>
                @endforeach
            </ul>
        </li>
        @endforeach
    </ul>
      <!-- /.sidebar-menu -->
    </section>
    <!-- /.sidebar -->
  </aside>


