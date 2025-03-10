@extends('layouts.master')


@section('top')
    <!-- DataTables --><!-- Log on to codeastro.com for more projects! -->
    <link rel="stylesheet" href="{{ asset('assets/bower_components/datatables.net-bs/css/dataTables.bootstrap.min.css') }}">
@endsection

@section('content')
    <div class="box box-success">

        <div class="box-header">
            <h3 class="box-title">List of Analytics</h3>
        </div>

        <div class="box-header">
            <!-- <a onclick="addForm()" class="btn btn-success" ><i class="fa fa-plus"></i> Add Analytics</a> -->
            <!-- <a href="{{ route('exportPDF.AnalyticsAll', [ 'id' => 4 ]) }}" class="btn btn-danger"><i class="fa fa-file-pdf-o"></i> Export PDF</a> -->
            <!-- <a href="exportAnalyticsPDF/16" class="btn btn-danger"><i class="fa fa-file-pdf-o"></i> Export PDF</a>
            <a href="{{ route('exportExcel.AnalyticsAll') }}" class="btn btn-primary"><i class="fa fa-file-excel-o"></i> Export Excel</a> -->
        </div>


        <!-- /.box-header -->
        <div class="box-body">
            <table id="sales-table" class="table table-bordered table-hover table-striped">
                <thead>
                <tr>
                    <th>ID</th>
                    <th>Number</th>
                    <th>Non Working Days</th>
                    <th>Non Working Hours</th>
                    <th>Now Bus Lines</th>
                    <th>File</th>
                    <th>Actions</th>
                </tr>
                </thead>
                <tbody></tbody>
            </table>
        </div>
        <!-- /.box-body -->
    </div>

    @include('Analytics.form_import')

    @include('Analytics.form')

@endsection

@section('bot')

    <!-- DataTables -->
    <script src=" {{ asset('assets/bower_components/datatables.net/js/jquery.dataTables.min.js') }} "></script>
    <script src="{{ asset('assets/bower_components/datatables.net-bs/js/dataTables.bootstrap.min.js') }} "></script>

    {{-- Validator --}}
    <script src="{{ asset('assets/validator/validator.min.js') }}"></script>

    {{--<script>--}}
    {{--$(function () {--}}
    {{--$('#items-table').DataTable()--}}
    {{--$('#example2').DataTable({--}}
    {{--'paging'      : true,--}}
    {{--'lengthChange': false,--}}
    {{--'searching'   : false,--}}
    {{--'ordering'    : true,--}}
    {{--'info'        : true,--}}
    {{--'autoWidth'   : false--}}
    {{--})--}}
    {{--})--}}
    {{--</script>--}}

    <script type="text/javascript">
        var table = $('#sales-table').DataTable({
            processing: true,
            serverSide: true,
            ajax: "{{ route('api.Analytics') }}",
            columns: [
                {data: 'id', name: 'id'},
                {data: 'number', name: 'number'},
                {data: 'non_working_days', name: 'non_working_days'},
                {data: 'non_working_hours', name: 'non_working_hours'},
                {data: 'non_bus_lines', name: 'non_bus_lines'},
                {data: 'file_url', name: 'file_url'},
                {data: 'action', name: 'action', orderable: false, searchable: false}
            ]
        });

        function addForm() {
            save_method = "add";
            $('input[name=_method]').val('POST');
            $('#modal-form').modal('show');
            $('#modal-form form')[0].reset();
            $('.modal-title').text('Add Analytics');
        }

        function editForm(id) {
            save_method = 'edit';
            $('input[name=_method]').val('PATCH');
            $('#modal-form form')[0].reset();
            $.ajax({
                url: "{{ url('Analytics') }}" + '/' + id + "/edit",
                type: "GET",
                dataType: "JSON",
                success: function(data) {
                    $('#modal-form').modal('show');
                    $('.modal-title').text('Edit Analytics');

                    $('#id').val(data.id);
                    $('#name').val(data.name);
                    $('#alamat').val(data.alamat);
                    $('#email').val(data.email);
                    $('#telepon').val(data.telepon);
                },
                error : function() {
                    alert("Nothing Data");
                }
            });
        }
        
        function exportPDF(id) {
            save_method = 'edit';
            $('input[name=_method]').val('PATCH');
            $('#modal-form form')[0].reset();
            $.ajax({
                url: "{{ url('Analytics') }}" + '/' + id + "/edit",
                type: "GET",
                dataType: "JSON",
                success: function(data) {
                    $('#modal-form').modal('show');
                    $('.modal-title').text('Edit Analytics');

                    $('#id').val(data.id);
                    $('#name').val(data.name);
                    $('#alamat').val(data.alamat);
                    $('#email').val(data.email);
                    $('#telepon').val(data.telepon);
                },
                error : function() {
                    alert("Nothing Data");
                }
            });
        }
        function exportExcel(id) {
            save_method = 'edit';
            $('input[name=_method]').val('PATCH');
            $('#modal-form form')[0].reset();
            $.ajax({
                url: "{{ url('Analytics') }}" + '/' + id + "/edit",
                type: "GET",
                dataType: "JSON",
                success: function(data) {
                    $('#modal-form').modal('show');
                    $('.modal-title').text('Edit Analytics');

                    $('#id').val(data.id);
                    $('#name').val(data.name);
                    $('#alamat').val(data.alamat);
                    $('#email').val(data.email);
                    $('#telepon').val(data.telepon);
                },
                error : function() {
                    alert("Nothing Data");
                }
            });
        }

        function deleteData(id){
            var csrf_token = $('meta[name="csrf-token"]').attr('content');
            swal({
                title: 'Are you sure?',
                text: "You won't be able to revert this!",
                type: 'warning',
                showCancelButton: true,
                cancelButtonColor: '#d33',
                confirmButtonColor: '#3085d6',
                confirmButtonText: 'Yes, delete it!'
            }).then(function () {
                $.ajax({
                    url : "{{ url('Analytics') }}" + '/' + id,
                    type : "POST",
                    data : {'_method' : 'DELETE', '_token' : csrf_token},
                    success : function(data) {
                        table.ajax.reload();
                        swal({
                            title: 'Success!',
                            text: data.message,
                            type: 'success',
                            timer: '1500'
                        })
                    },
                    error : function () {
                        swal({
                            title: 'Oops...',
                            text: data.message,
                            type: 'error',
                            timer: '1500'
                        })
                    }
                });
            });
        }

        $(function(){
            $('#modal-form form').validator().on('submit', function (e) {
                if (!e.isDefaultPrevented()){
                    var id = $('#id').val();
                    if (save_method == 'add') url = "{{ url('Analytics') }}";
                    else url = "{{ url('Analytics') . '/' }}" + id;

                    $.ajax({
                        url : url,
                        type : "POST",
                        //hanya untuk input data tanpa dokumen
//                      data : $('#modal-form form').serialize(),
                        data: new FormData($("#modal-form form")[0]),
                        contentType: false,
                        processData: false,
                        success : function(data) {
                            $('#modal-form').modal('hide');
                            table.ajax.reload();
                            swal({
                                title: 'Success!',
                                text: data.message,
                                type: 'success',
                                timer: '1500'
                            })
                        },
                        error : function(data){
                            swal({
                                title: 'Oops...',
                                text: data.message,
                                type: 'error',
                                timer: '1500'
                            })
                        }
                    });
                    return false;
                }
            });
        });
    </script>

@endsection
