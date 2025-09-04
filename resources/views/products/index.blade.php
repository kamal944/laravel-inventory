@extends('layouts.master')

@section('top')
    <!-- DataTables -->
    <link rel="stylesheet" href="{{ asset('assets/bower_components/datatables.net-bs/css/dataTables.bootstrap.min.css') }}">
@endsection

@section('content')
    <div class="box">
        <div class="box-header">
            <h3 class="box-title">{{ __('main.data_products') }}</h3>
        </div>

        <div class="box-header">
            <a onclick="addForm()" class="btn btn-primary pull-right" >{{ __('main.add_button_product') }}</a>
            <a href="{{ route('exportPDF.productsAll') }}" class="btn btn-danger">{{ __('main.export_pdf') }}</a>
            <a href="{{ route('exportExcel.productsAll') }}" class="btn btn-success">{{ __('main.export_excel') }}</a>
        </div>

        <div class="box-body">
            <table id="products-table" class="table table-striped">
                <thead>
                <tr>
                    <th>{{ __('main.ID') }}</th>
                    <th>{{ __('main.name_en') }}</th>
                    <th>{{ __('main.name_ar') }}</th>
                    <th>{{ __('main.sku') }}</th>
                    <th>{{ __('main.date') }}</th>
                    <th>{{ __('main.qty') }}</th>
                    <th>{{ __('main.image') }}</th>
                    <th>{{ __('main.category') }}</th>
                    <th>{{ __('main.actions') }}</th>
                </tr>
                </thead>
                <tbody></tbody>
            </table>
        </div>
    </div>

    @include('products.form')

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    <form action="{{ route('products.import') }}" method="POST" enctype="multipart/form-data" class="mt-3">
        @csrf
        <input type="file" name="file" required class="form-control mb-2">
        <button type="submit" class="btn btn-primary">{{ __('main.import_products') }}</button>
    </form>
@endsection

@section('bot')
    <!-- DataTables -->
    <script src="{{ asset('assets/bower_components/datatables.net/js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('assets/bower_components/datatables.net-bs/js/dataTables.bootstrap.min.js') }}"></script>

    {{-- Validator --}}
    <script src="{{ asset('assets/validator/validator.min.js') }}"></script>

    <script type="text/javascript">
        var table = $('#products-table').DataTable({
            processing: true,
            serverSide: true,
            ajax: "{{ route('api.products') }}",
            columns: [
                {data: 'id', name: 'id'},
                {data: 'name_en', name: 'name_en'},
                {data: 'name_ar', name: 'name_ar'},
                {data: 'sku', name: 'sku'},
                {
                    data: 'date',
                    name: 'date',
                    render: function (data, type, row) {
                        if (!data) return '';
                        const dateObj = new Date(data);
                        const day = String(dateObj.getDate()).padStart(2, '0');
                        const month = String(dateObj.getMonth() + 1).padStart(2, '0');
                        const year = dateObj.getFullYear();
                        return `${day} - ${month} - ${year}`;
                    }
                },
                {data: 'qty', name: 'qty'},
                {data: 'show_photo', name: 'show_photo'},
                {data: 'category_name', name: 'category_name'},
                {data: 'action', name: 'action', orderable: false, searchable: false}
            ],
            language: {
                url: "{{ asset('assets/bower_components/datatables.net/lang/' . app()->getLocale() . '.json') }}"
            }
        });

        function addForm() {
            save_method = "add";
            $('input[name=_method]').val('POST');
            $('#modal-form').modal('show');
            $('#modal-form form')[0].reset();
            $('.modal-title').text("{{ __('main.add_product') }}");
        }

        function editForm(id) {
            save_method = 'edit';
            $('input[name=_method]').val('PATCH');
            $('#modal-form form')[0].reset();
            $.ajax({
                url: "{{ url('products') }}" + '/' + id + "/edit",
                type: "GET",
                dataType: "JSON",
                success: function(data) {
                    $('#modal-form').modal('show');
                    $('.modal-title').text("{{ __('main.edit_product') }}");

                    $('#id').val(data.id);
                    $('#name_en').val(data.name_en);
                    $('#name_ar').val(data.name_ar);
                    $('#date').val(data.date);
                    $('#qty').val(data.qty);
                    $('#category_id').val(data.category_id);
                },
                error : function() {
                    swal({
                        title: "{{ __('main.error_title') }}",
                        text: "{{ __('main.no_data') }}",
                        type: 'error',
                        timer: 1500
                    });
                }
            });
        }

        function deleteData(id){
            var csrf_token = $('meta[name="csrf-token"]').attr('content');
            swal({
                title: "{{ __('main.confirm_delete') }}",
                text: "{{ __('main.delete_warning') }}",
                type: 'warning',
                showCancelButton: true,
                cancelButtonColor: '#d33',
                confirmButtonColor: '#3085d6',
                confirmButtonText: "{{ __('main.delete_confirm') }}",
                cancelButtonText: "{{ __('main.delete_cancel') }}"
            }).then(function () {
                $.ajax({
                    url : "{{ url('products') }}" + '/' + id,
                    type : "POST",
                    data : {'_method' : 'DELETE', '_token' : csrf_token},
                    success : function(data) {
                        table.ajax.reload();
                        swal({
                            title: "{{ __('main.success_title') }}",
                            text: data.message,
                            type: 'success',
                            timer: 1500
                        });
                    },
                    error : function () {
                        swal({
                            title: "{{ __('main.error_title') }}",
                            text: data.message,
                            type: 'error',
                            timer: 1500
                        });
                    }
                });
            });
        }

        $(function(){
            $('#modal-form form').validator().on('submit', function (e) {
                if (!e.isDefaultPrevented()){
                    var id = $('#id').val();
                    if (save_method == 'add') url = "{{ url('products') }}";
                    else url = "{{ url('products') . '/' }}" + id;

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
                                timer: 1500
                            });
                        },
                        error : function(data){
                            swal({
                                title: "{{ __('main.error_title') }}",
                                text: data.message,
                                type: 'error',
                                timer: 1500
                            });
                        }
                    });
                    return false;
                }
            });
        });
    </script>
@endsection