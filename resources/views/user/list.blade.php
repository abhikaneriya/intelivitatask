@extends('layout.app')
@section('body')
<!-- Content Header (Page header) -->
<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-2">
                <h1>
                    User
                    <a href="{{ route('user.point.recalculate') }}" class="btn btn-dark ml-4">Re-Calculate</a>
                </h1>
            </div>
            <div class="col-sm-4">
                <select class="form-control" id="filterData">
                    <option>Select Filter</option>
                    <option value="1">Day</option>
                    <option value="2">Month</option>
                    <option value="3">Year</option>
                </select>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="#">Home</a></li>
                    <li class="breadcrumb-item active">User</li>
                </ol>
            </div>
        </div>
    </div><!-- /.container-fluid -->
</section>

<!-- Main content -->
<section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">User table</h3>
                    </div>
                    <!-- /.card-header -->
                    <div class="card-body">
                        <table id="example1" class="table table-bordered table-striped userListTable">
                        </table>
                    </div>
                    <!-- /.card-body -->
                </div>
                <!-- /.card -->
            </div>
            <!-- /.col -->
        </div>
        <!-- /.row -->
    </div>
    <!-- /.container-fluid -->
</section>
<!-- /.content -->
</div>
@endsection
@push('scripts')
<script>
    $(document).ready(function() {
        var table = $('.userListTable');
        setUserListTable();

        function setUserListTable(result = '') {
            oTable = table.dataTable({
                "processing": true,
                "serverSide": true,
                "language": {
                    "aria": {
                        "sortAscending": ": click to sort column ascending",
                        "sortDescending": ": click to sort column descending"
                    },
                    "emptyTable": "No data available in table",
                    "info": "Showing _START_ to _END_ of _TOTAL_ entries",
                    "infoEmpty": "No entries found",
                    "infoFiltered": "(filtered1 from _MAX_ total entries)",
                    "lengthMenu": "_MENU_ entries",
                    "search": "Search:",
                    "zeroRecords": "No matching records found",
                    "paginate": {
                        "previous": "Prev",
                        "next": "Next",
                        "last": "Last",
                        "first": "First",
                        "page": "Page",
                        "pageOf": "of"
                    }
                },
                "columns": [{
                        "title": "S.No",
                        "data": "id"
                    },
                    {
                        "title": "Name",
                        "data": "name"
                    },
                    {
                        "title": "Point",
                        "data": "point"
                    },
                    {
                        "title": "Rank",
                        "data": "rank"
                    },
                ],
                responsive: true,
                "order": [
                    [3, 'asc']
                ],
                "lengthMenu": [
                    [10, 20, 50, 100],
                    [10, 20, 50, 100]
                ],
                "pageLength": 10,
                "columnDefs": [{
                    'targets': [2], // column index (start from 0)
                    'orderable': false, // set orderable false for selected columns
                }],
                "ajax": {
                    "url": "{{ route('getUserData') }}", // ajax source
                    "data": {
                        data: result
                    }
                },
                "dom": "<'row' <'col-md-12'>><'row'<'col-md-12 col-sm-12'f>r><'table-scrollable't>",
                // horizobtal scrollable datatable
            });
        }

        $("#filterData").change(function() {
            var result = $("#filterData").val();
            table.dataTable().fnDestroy();
            setUserListTable(result);
        });
    });
</script>
@endpush