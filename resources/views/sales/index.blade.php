@extends('layouts.master')

@section('top')
    <!-- DataTables -->
    <link rel="stylesheet" href="{{ asset('assets/bower_components/datatables.net-bs/css/dataTables.bootstrap.min.css') }}">
@endsection

@section('content')
    <div class="box">

        <div class="box-header">
            <h3 class="box-title">@lang('main.sales_data')</h3>
        </div>

        <div class="box-header">
            <a onclick="addForm()" class="btn btn-primary">@lang('main.add_sales')</a>
            <a href="{{ route('exportPDF.salesAll') }}" class="btn btn-danger">@lang('main.export_pdf')</a>
            <a href="{{ route('exportExcel.salesAll') }}" class="btn btn-success">@lang('main.export_excel')</a>
        </div>

        <!-- /.box-header -->
        <div class="box-body">
            <table id="sales-table" class="table table-striped">
                <thead>
                <tr>
                    <th>@lang('main.id')</th>
                    <th>@lang('main.name')</th>
                    <th>@lang('main.address')</th>
                    <th>@lang('main.email')</th>
                    <th>@lang('main.phone')</th>
                    <th></th>
                </tr>
                </thead>
                <tbody></tbody>
            </table>
        </div>
        <!-- /.box-body -->
    </div>

    @include('sales.form_import')
    @include('sales.form')
@endsection

@section('bot')
    <!-- DataTables -->
    <script src="{{ asset('assets/bower_components/datatables.net/js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('assets/bower_components/datatables.net-bs/js/dataTables.bootstrap.min.js') }}"></script>

    {{-- Validator --}}
    <script src="{{ asset('assets/validator/validator.min.js') }}"></script>

    <script type="text/javascript">
        var table = $('#sales-table').DataTable({
            processing: true,
            serverSide: true,
            ajax: "{{ route('api.sales') }}",
            columns: [
                {data: 'id', name: 'id'},
                {data: 'name', name: 'name'},
                {data: 'address', name: 'address'},
                {data: 'email', name: 'email'},
                {data: 'phone', name: 'phone'},
                {data: 'action', name: 'action', orderable: false, searchable: false}
            ]
        });

        function addForm() {
            save_method = "add";
            $('input[name=_method]').val('POST');
            $('#modal-form').modal('show');
            $('#modal-form form')[0].reset();
            $('.modal-title').text('@lang("main.add_sales")');
        }

        function editForm(id) {
            save_method = 'edit';
            $('input[name=_method]').val('PATCH');
            $('#modal-form form')[0].reset();
            $.ajax({
                url: "{{ url('sales') }}" + '/' + id + "/edit",
                type: "GET",
                dataType: "JSON",
                success: function(data) {
                    $('#modal-form').modal('show');
                    $('.modal-title').text('@lang("main.edit_sales")');

                    $('#id').val(data.id);
                    $('#name').val(data.name);
                    $('#address').val(data.address);
                    $('#email').val(data.email);
                    $('#phone').val(data.phone);
                },
                error : function() {
                    alert("@lang('main.no_data')");
                }
            });
        }

        function deleteData(id){
            var csrf_token = $('meta[name="csrf-token"]').attr('content');
            swal({
                title: "@lang('main.are_you_sure')",
                text: "@lang('main.delete_warning')",
                type: 'warning',
                showCancelButton: true,
                cancelButtonColor: '#d33',
                confirmButtonColor: '#3085d6',
                confirmButtonText: "@lang('main.yes_delete')"
            }).then(function () {
                $.ajax({
                    url : "{{ url('sales') }}" + '/' + id,
                    type : "POST",
                    data : {'_method' : 'DELETE', '_token' : csrf_token},
                    success : function(data) {
                        table.ajax.reload();
                        swal({
                            title: "@lang('main.success')!",
                            text: data.message,
                            type: 'success',
                            timer: '1500'
                        })
                    },
                    error : function () {
                        swal({
                            title: "@lang('main.oops')...",
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
                    if (save_method == 'add') url = "{{ url('sales') }}";
                    else url = "{{ url('sales') . '/' }}" + id;

                    $.ajax({
                        url : url,
                        type : "POST",
                        data: new FormData($("#modal-form form")[0]),
                        contentType: false,
                        processData: false,
                        success : function(data) {
                            $('#modal-form').modal('hide');
                            table.ajax.reload();
                            swal({
                                title: "@lang('main.success')!",
                                text: data.message,
                                type: 'success',
                                timer: '1500'
                            })
                        },
                        error : function(data){
                            swal({
                                title: "@lang('main.oops')...",
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