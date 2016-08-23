@extends('dashboard')
@section('content')
<section class="content">
    <section class="content">
      <div class="row">
        <div class="col-xs-12">
          <div class="box">
            <div class="box-header">
              <h3 class="box-title">
              @if(isset($title))
                {{ $title }}
              @else
                页面标题
              @endif
                </h3>
            </div>
            <!-- /.box-header -->
            <div class="box-body">
              <table id="example1" class="table table-bordered table-striped">
                <thead>
                <tr>
                  <th>ID</th>
                  <th>名称</th>
                  <th>状态</th>
                  <th>描述</th>
                  <th>操作</th>
                </tr>
                </thead>
                <tbody>
                    <tr>
                        <td> 1 </td>
                        <td>李海</td>
                        <td>通过</td>
                        <td>公会会长</td>
                        <td><a href="#">编辑</a></td>
                    </tr>
                    <tr>
                        <td> 2 </td>
                        <td>蓝风</td>
                        <td>未审核</td>
                        <td>公会会长</td>
                        <td><a href="#">编辑</a></td>
                    </tr> 
                    <tr>
                        <td> 3 </td>
                        <td>明清</td>
                        <td>通过</td>
                        <td>公会会长</td>
                        <td><a href="#">编辑</a></td>
                    </tr> 
                    <tr>
                        <td> 4 </td>
                        <td>李永</td>
                        <td>通过</td>
                        <td>公会会长</td>
                        <td><a href="#">编辑</a></td>
                    </tr>
                    <tr>
                        <td> 5 </td>
                        <td>刘同</td>
                        <td>通过</td>
                        <td>公会会长</td>
                        <td><a href="#">编辑</a></td>
                    </tr>
                </tbody>
              </table>
            </div>
            <!-- /.box-body -->
          </div>
          <!-- /.box -->
        </div>
        <!-- /.col -->
      </div>
      <!-- /.row -->
    </section>
    <!-- /.content -->
  </div>
 
</section>
<!-- DataTables -->
<script src="{{ asset('resources/plugins/datatables/jquery.dataTables.min.js') }}"></script>
<script src="{{ asset('resources/plugins/datatables/dataTables.bootstrap.min.js') }}"></script>
<!-- SlimScroll -->
<script src="{{ asset('resources/plugins/slimScroll/jquery.slimscroll.min.js') }}"></script>
<!-- FastClick -->
<script src="{{ asset('resources/plugins/fastclick/fastclick.js') }}"></script>
<!-- AdminLTE App -->
<script src="{{ asset('resources/dist/js/app.min.js') }}"></script>
<!-- AdminLTE for demo purposes -->
<script src="{{ asset('resources/dist/js/demo.js') }}"></script>
<!-- page script -->
<script>
  $(function () {
    $("#example1").DataTable();
    $('#example2').DataTable({
      "paging": true,
      "lengthChange": false,
      "searching": false,
      "ordering": true,
      "info": true,
      "autoWidth": false
    });
  });
</script>
   
@endsection
