@extends('layouts.master')

@section('top')
    <!-- DataTables -->
    <link rel="stylesheet" href="{{ asset('assets/bower_components/datatables.net-bs/css/dataTables.bootstrap.min.css') }}">
@endsection

@section('content')
    <div class="box">
        <div class="box-header">
            <h3 class="box-title">@lang('main.data_categories')</h3>
        </div>

        <div class="box-header">
            <a onclick="addForm()" class="btn btn-primary">@lang('main.add_categories')</a>
            <a href="{{ route('exportPDF.categoriesAll') }}" class="btn btn-danger">@lang('main.export_pdf')</a>
            <a href="{{ route('exportExcel.categoriesAll') }}" class="btn btn-success">@lang('main.export_excel')</a>
        </div>

        <!-- /.box-header -->
        <div class="box-body">
            <table id="categories-table" class="table table-striped">
                <thead>
                <tr>
                    <th>@lang('main.id')</th>
                    <th>@lang('main.name')</th>
                    <th>@lang('main.action')</th>
                </tr>
                </thead>
                <tbody></tbody>
            </table>
        </div>
        <!-- /.box-body -->
    </div>

    @include('categories.form')
@endsection

@section('bot')
    <!-- DataTables -->
    <script src="{{ asset('assets/bower_components/datatables.net/js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('assets/bower_components/datatables.net-bs/js/dataTables.bootstrap.min.js') }}"></script>

    {{-- Validator --}}
    <script src="{{ asset('assets/validator/validator.min.js') }}"></script>

    <script type="text/javascript">
        var table = $('#categories-table').DataTable({
            processing: true,
            serverSide: true,
            ajax: "{{ route('api.categories') }}",
            columns: [
                {data: 'id', name: 'id'},
                {data: 'name', name: 'name'},
                {data: 'action', name: 'action', orderable: false, searchable: false}
            ],
            @if(app()->getLocale() == 'ar')
            language: {
                url: "//cdn.datatables.net/plug-ins/1.10.19/i18n/Arabic.json"
            }
            @endif
        });

        function addForm() {
            save_method = "add";
            $('input[name=_method]').val('POST');
            $('#modal-form').modal('show');
            $('#modal-form form')[0].reset();
            $('.modal-title').text("@lang('main.add_categories')");
        }

        function editForm(id) {
            save_method = 'edit';
            $('input[name=_method]').val('PATCH');
            $('#modal-form form')[0].reset();
            $.ajax({
                url: "{{ url('categories') }}" + '/' + id + "/edit",
                type: "GET",
                dataType: "JSON",
                success: function(data) {
                    $('#modal-form').modal('show');
                    $('.modal-title').text("@lang('main.edit_categories')");
                    $('#id').val(data.id);
                    $('#name').val(data.name);
                },
                error : function() {
                    alert("@lang('main.nothing_data')");
                }
            });
        }

        function deleteData(id){
            var csrf_token = $('meta[name="csrf-token"]').attr('content');
            swal({
                title: "@lang('main.delete_confirm')",
                text: "@lang('main.delete_confirm_text')",
                type: 'warning',
                showCancelButton: true,
                cancelButtonColor: '#d33',
                confirmButtonColor: '#3085d6',
                confirmButtonText: "@lang('main.delete_confirm_btn')"
            }).then(function () {
                $.ajax({
                    url : "{{ url('categories') }}" + '/' + id,
                    type : "POST",
                    data : {'_method' : 'DELETE', '_token' : csrf_token},
                    success : function(data) {
                        table.ajax.reload();
                        swal({
                            title: "@lang('main.delete_success')",
                            text: data.message,
                            type: 'success',
                            timer: '1500'
                        })
                    },
                    error : function () {
                        swal({
                            title: "@lang('main.delete_error')",
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
                    if (save_method == 'add') url = "{{ url('categories') }}";
                    else url = "{{ url('categories') . '/' }}" + id;

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
                                title: "@lang('main.delete_success')",
                                text: data.message,
                                type: 'success',
                                timer: '1500'
                            })
                        },
                        error : function(data){
                            swal({
                                title: "@lang('main.delete_error')",
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