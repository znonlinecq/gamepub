 <!-- Left side column. contains the logo and sidebar -->
  <aside class="main-sidebar">

    <!-- sidebar: style can be found in sidebar.less -->
    <section class="sidebar">
    <li class="header"></li> 
     <!-- Sidebar Menu -->
      <ul class="sidebar-menu">
        <!-- Optionally, you can add icons to the links -->
        <li class="treeview" >
            <a href="#"><i class="fa fa-link"></i> <span>公会管理</span></a>
            <ul class="treeview-menu">
                <li><a href="group_founders">会长审核</a></li>
                <li><a href="{{ url('groups') }}">公会审核</a></li>
            </ul>
        </li>
        
        <li class="treeview">
          <a href="#"><i class="fa fa-link"></i> <span>应用管理</span>
            <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
          </a>
          <ul class="treeview-menu">
            <li><a href="{{ url('applications') }}">应用审核</a></li>
            <li><a href="{{ url('application_blacklist') }}">应用黑名单</a></li>
          </ul>
        </li>
        <li class="treeview">
          <a href="#"><i class="fa fa-link"></i> <span>模块管理</span>
            <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
          </a>
          <ul class="treeview-menu">
            <li><a href="{{ url('modules') }}">模块列表</a></li>
            <li><a href="{{ url('modules/create') }}">模块添加</a></li>
          </ul>
        </li>


        <li class="treeview" >
            <a href="#"><i class="fa fa-link"></i> <span>系统设置</span></a>
            <ul class="treeview-menu">
                <li><a href="{{ url('users') }}">用户管理</a></li>
                <li><a href="{{ url('roles') }}">角色管理</a></li>
            </ul>
        </li>  
    </ul>
      <!-- /.sidebar-menu -->
    </section>
    <!-- /.sidebar -->
  </aside>


