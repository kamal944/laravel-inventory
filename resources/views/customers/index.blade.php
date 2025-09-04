@extends('layouts.master')

@section('top')
    <!-- DataTables -->
    <link rel="stylesheet" href="{{ asset('assets/bower_components/datatables.net-bs/css/dataTables.bootstrap.min.css') }}">
    @include('sweet::alert')
@endsection

@section('content')
    <div class="box">
        <div class="box-header">
            <h3 class="box-title">{{ __('main.customers_data') }}</h3>
        </div>

        <div class="box-header">
            <a onclick="addForm()" class="btn btn-primary">{{ __('main.add_customer') }}</a>
            <a href="{{ route('exportPDF.customersAll') }}" class="btn btn-danger">{{ __('main.export_pdf') }}</a>
            <a href="{{ route('exportExcel.customersAll') }}" class="btn btn-success">{{ __('main.export_excel') }}</a>
        </div>

        <!-- /.box-header -->
        <div class="box-body">
            <table id="customer-table" class="table table-striped">
                <thead>
                <tr>
                    <th>{{ __('main.id') }}</th>
                    <th>{{ __('main.name') }}</th>
                    <th>{{ __('main.address') }}</th>
                    <th>{{ __('main.email') }}</th>
                    <th>{{ __('main.phone') }}</th>
                    <th>{{ __('main.actions') }}</th>
                </tr>
                </thead>
                <tbody></tbody>
            </table>
        </div>
        <!-- /.box-body -->
    </div>

    @include('customers.form_import')
    @include('customers.form')

@endsection

@section('bot')
    <!-- DataTables -->
    <script src="{{ asset('assets/bower_components/datatables.net/js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('assets/bower_components/datatables.net-bs/js/dataTables.bootstrap.min.js') }}"></script>

    <!-- Validator -->
    <script src="{{ asset('assets/validator/validator.min.js') }}"></script>

    <script type="text/javascript">
        var table = $('#customer-table').DataTable({
            processing: true,
            serverSide: true,
            ajax: "{{ route('api.customers') }}",
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
            $('.modal-title').text("{{ __('main.add_customer') }}");
        }

        function editForm(id) {
            save_method = 'edit';
            $('input[name=_method]').val('PATCH');
            $('#modal-form form')[0].reset();
            $.ajax({
                url: "{{ url('customers') }}" + '/' + id + "/edit",
                type: "GET",
                dataType: "JSON",
                success: function(data) {
                    $('#modal-form').modal('show');
                    $('.modal-title').text("{{ __('main.edit_customer') }}");

                    $('#id').val(data.id);
                    $('#name').val(data.name);
                    $('#address').val(data.address);
                    $('#email').val(data.email);
                    $('#phone').val(data.phone);
                },
                error : function() {
                    swal({
                        title: "{{ __('main.error_title') }}",
                        text: "{{ __('main.no_data') }}",
                        type: 'error',
                        timer: '1500'
                    });
                }
            });
        }

        function deleteData(id){
            var csrf_token = $('meta[name="csrf-token"]').attr('content');
            swal({
                title: "{{ __('main.delete_confirm_title') }}",
                text: "{{ __('main.delete_confirm_text') }}",
                type: 'warning',
                showCancelButton: true,
                cancelButtonColor: '#d33',
                confirmButtonColor: '#3085d6',
                confirmButtonText: "{{ __('main.delete_confirm_button') }}"
            }).then(function () {
                $.ajax({
                    url : "{{ url('customers') }}" + '/' + id,
                    type : "POST",
                    data : {'_method' : 'DELETE', '_token' : csrf_token},
                    success : function(data) {
                        table.ajax.reload();
                        swal({
                            title: "{{ __('main.delete_success_title') }}",
                            text: data.message,
                            type: 'success',
                            timer: '1500'
                        })
                    },
                    error : function () {
                        swal({
                            title: "{{ __('main.delete_error_title') }}",
                            text: "{{ __('main.delete_error_text') }}",
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
                    if (save_method == 'add') url = "{{ url('customers') }}";
                    else url = "{{ url('customers') . '/' }}" + id;

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
                                title: "{{ __('main.success_title') }}",
                                text: data.message,
                                type: 'success',
                                timer: '1500'
                            })
                        },
                        error : function(data){
                            swal({
                                title: "{{ __('main.error_title') }}",
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