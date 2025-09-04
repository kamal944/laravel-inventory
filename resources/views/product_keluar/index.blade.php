@extends('layouts.master')

@section('top')
    <!-- DataTables -->
    <link rel="stylesheet" href="{{ asset('assets/bower_components/datatables.net-bs/css/dataTables.bootstrap.min.css') }}">

    <!-- daterange picker -->
    <link rel="stylesheet" href="{{ asset('assets/bower_components/bootstrap-daterangepicker/daterangepicker.css') }}">
    <!-- bootstrap datepicker -->
    <link rel="stylesheet" href="{{ asset('assets/bower_components/bootstrap-datepicker/dist/css/bootstrap-datepicker.min.css') }}">
@endsection

@section('content')
    <div class="box">
        <div class="box-header">
            <h3 class="box-title">{{ __('main.products_out_title') }}</h3>
        </div>
        <div class="box">

        <!-- Add Product Out Form -->
        <div class="box-header">
<h3>{{ __('main.add_product_out') }}</h3>
                    <form id="form-item" method="post" class="form-horizontal" data-toggle="validator" enctype="multipart/form-data">
                        {{ csrf_field() }} {{ method_field('POST') }}
                        <input type="hidden" id="id" name="id">

                        <!-- Inputs Row -->
                        <div class="row col-12">

                        <div class="col-md-3 form-group form-group-2 ">
                                <label for="product_id">{{ __('main.product') }}</label>
                                <select class="form-control select" name="product_id" id="product_id" required>
                                    <option value="">{{ __('main.choose_product') }}</option>
                                    @foreach($products as $id => $product)
                                        <option value="{{ $product->id }}" data-qty="{{ $product->qty }}">
                                            @if(app()->getLocale() === 'ar')
                                                {{ $product->name_ar ?? $product->name_en ?? __('main.no_name') }}
                                            @else
                                                {{ $product->name_en ?? $product->name_ar ?? __('main.no_name') }}
                                            @endif
                                        </option>
                                    @endforeach
                                </select>
                                <span class="help-block with-errors"></span>
                            </div>

                            <div class="col-md-3 form-group form-group-2">
                                <label for="customer_id">{{ __('main.customer') }}</label>
                                {!! Form::select('customer_id', $customers, old('customer_id'), [
                                    'class' => 'form-control select',
                                    'placeholder' => __('main.choose_customer'),
                                    'id' => 'customer_id',
                                    'required'
                                ]) !!}
                                <span class="help-block with-errors"></span>
                            </div>

                            <div class="col-md-2 form-group form-group-2">
                                <label for="qty">{{ __('main.quantity') }}</label>
                                <input type="number" class="form-control" id="qty" name="qty" required>
                                <small id="available-qty" class="form-text text-muted" style="display: none;">
                                    {{ __('main.available_qty') }}: <span id="available-qty-value">0</span>
                                </small>
                                <span class="help-block with-errors"></span>
                            </div>

                            <div class="col-md-2 form-group form-group-2">
                                <label for="date">{{ __('main.date') }}</label>
                                <input type="text" class="form-control datepicker" id="date" name="date" required
                                       value="{{ date('Y-m-d') }}">
                                <span class="help-block with-errors"></span>
                            </div>
                        </div>
                             <div class="row col-12" style=" display: flex;justify-content: end ;width: 80%">
                                <div class="col-md-2 form-group form-group-2"  >
                                     <button type="submit" class="btn btn-primary">{{ __('main.add_product_out') }}</button>
                                </div>
                            </div>
                    </form>

        </div>
        </div>

        <!-- Filter Form -->
        <div class="box-header">
            <h3>{{ __('main.export_invoice') }}</h3>
            <form method="GET" class="">
                <div class="col-md-3 form-group">
                    <label>{{ __('main.from_date') }}</label>
                    <input type="date" name="from_date" class="form-control" placeholder="{{ __('main.from_date') }}">
                </div>
                <div class="col-md-3 form-group">
                    <label>{{ __('main.to_date') }}</label>
                    <input type="date" name="to_date" class="form-control" placeholder="{{ __('main.to_date') }}">
                </div>
                <div class="col-md-3 form-group">
                    <label>{{ __('main.product') }}</label>
                    <select name="product_id" class="form-control">
                        <option value="">{{ __('main.select_product') }}</option>
                        @foreach($products as $product)
                            <option value="{{ $product->id }}">
                                @if(app()->getLocale() === 'ar')
                                    {{ $product->name_ar}}
                                @else
                                    {{ $product->name_en}}
                                @endif
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3 form-group">
                    <label>{{ __('main.customer') }}</label>
                    <select name="customer_id" class="form-control">
                        <option value="">{{ __('main.select_customer') }}</option>
                        @foreach($customers as $id => $name)
                            <option value="{{ $id }}">{{ $name }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- New column for recent records count -->
                <div class="col-md-3 form-group">
                    <label>{{ __('main.number_of_recent_records') }}</label>
                    <input type="number" name="recent_count" class="form-control" placeholder="{{ __('main.enter_number') }}" min="1">
                </div>

                <div class="col-md-6 form-group" style="margin-top: 25px;">
                    <button type="submit" formaction="{{ route('exportExcel.productKeluarAll') }}" class="btn btn-success">
                        {{ __('main.export_excel') }}
                    </button>
                    <button type="submit" formaction="{{ route('exportPDF.productKeluarAll') }}" class="btn btn-danger">
                        {{ __('main.export_pdf') }}
                    </button>
                    <button type="submit" formaction="{{ route('export_invoice') }}" class="btn btn-primary">
                        {{ __('main.export_invoice') }}
                    </button>
                </div>
            </form>
        </div>

        <!-- Data Table -->
        <div class="box-body">
            <table id="products-out-table" class="table table-striped">
                <thead>
                <tr>
                    <th>{{ __('main.id') }}</th>
                    <th>{{ __('main.products') }}</th>
                    <th>{{ __('main.customer') }}</th>
                    <th>{{ __('main.qty') }}</th>
                    <th>{{ __('main.date_out') }}</th>
                    <th></th>
                </tr>
                </thead>
                <tbody></tbody>
            </table>
        </div>
    </div>
@endsection

@section('bot')
    <!-- DataTables -->
    <script src=" {{ asset('assets/bower_components/datatables.net/js/jquery.dataTables.min.js') }} "></script>
    <script src="{{ asset('assets/bower_components/datatables.net-bs/js/dataTables.bootstrap.min.js') }} "></script>

    <!-- InputMask -->
    <script src="{{ asset('assets/plugins/input-mask/jquery.inputmask.js') }}"></script>
    <script src="{{ asset('assets/plugins/input-mask/jquery.inputmask.date.extensions.js') }}"></script>
    <script src="{{ asset('assets/plugins/input-mask/jquery.inputmask.extensions.js') }}"></script>
    <!-- date-range-picker -->
    <script src="{{ asset('assets/bower_components/moment/min/moment.min.js') }}"></script>
    <script src="{{ asset('assets/bower_components/bootstrap-daterangepicker/daterangepicker.js') }}"></script>
    <!-- bootstrap datepicker -->
    <script src="{{ asset('assets/bower_components/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js') }}"></script>
    <!-- bootstrap color picker -->
    <script src="{{ asset('assets/bower_components/bootstrap-colorpicker/dist/js/bootstrap-colorpicker.min.js') }}"></script>
    <!-- bootstrap time picker -->
    <script src="{{ asset('assets/plugins/timepicker/bootstrap-timepicker.min.js') }}"></script>
    {{-- Validator --}}
    <script src="{{ asset('assets/validator/validator.min.js') }}"></script>

    <style>
        /* RTL Support */
        html[dir="rtl"] .form-horizontal .form-group {
            text-align: right;
        }

        /* Highlight when quantity exceeds available */
        .qty-error {
            color: #dc3545;
            font-weight: bold;
        }

        /* Form group spacing */
        .form-group {
            margin-bottom: 15px;
        }
        .form-group-2 {
            margin-left: 10px!important;
        }

        /* Responsive adjustments */
        @media (max-width: 768px) {
            .form-inline .form-group {
                display: block;
                margin-bottom: 10px;
                margin-left: 10px;
            }

        }
    </style>

    <script>
        $(function () {
            // Initialize date picker
            $('.datepicker').datepicker({
                autoclose: true,
                todayHighlight: true,
                format: 'yyyy-mm-dd'
            });

            // Initialize DataTables
            var table = $('#products-out-table').DataTable({
                processing: true,
                serverSide: true,
                ajax: "{{ route('api.productsOut') }}",
                columns: [
                    {data: 'id', name: 'id'},
                    {
                        data: 'products_name',
                        name: 'products_name',
                        render: function(data, type, row) {
                            if ("{{ app()->getLocale() }}" === 'ar') {
                                return row.name_ar || data;
                            }
                            return row.name_en || data;
                        }
                    },
                    {data: 'customer_name', name: 'customer_name'},
                    {data: 'qty', name: 'qty'},
                    {data: 'date', name: 'date'},
                    {data: 'action', name: 'action', orderable: false, searchable: false}
                ],
                language: {
                    url: "{{ app()->getLocale() === 'ar' ? '//cdn.datatables.net/plug-ins/1.10.25/i18n/Arabic.json' : '' }}"
                }
            });

            // Show available quantity when product is selected
            $('#product_id').change(function() {
                var selectedOption = $(this).find('option:selected');
                var availableQty = selectedOption.data('qty');

                if (availableQty !== undefined) {
                    $('#available-qty-value').text(availableQty);
                    $('#available-qty').show();
                    $('#qty').attr('max', availableQty);
                } else {
                    $('#available-qty').hide();
                    $('#qty').removeAttr('max');
                }
            });

            // Validate quantity input
            $('#qty').on('input', function() {
                var enteredQty = parseInt($(this).val()) || 0;
                var availableQty = parseInt($('#available-qty-value').text()) || 0;

                if (enteredQty > availableQty) {
                    $('#available-qty').addClass('qty-error');
                } else {
                    $('#available-qty').removeClass('qty-error');
                }
            });

            // AJAX form submission for add product out
            $('#form-item').on('submit', function(e) {
                e.preventDefault();

                // Save the selected customer for persistence
                const selectedCustomer = $('#customer_id').val();

                $.ajax({
                    url: "{{ route('productsOut.store') }}",
                    type: "POST",
                    data: $(this).serialize(),
                    success: function(response) {
                        toastr.success(response.message);

                        // Reset form but keep customer and date
                        $('#form-item')[0].reset();
                        $('#product_id').val('').trigger('change');
                        $('#qty').val('');
                        $('#available-qty').hide();

                        // Restore the customer selection
                        $('#customer_id').val(selectedCustomer);

                        // Refresh the data table
                        table.ajax.reload();
                    },
                    error: function(xhr) {
                        toastr.error(xhr.responseJSON.message);
                    }
                });
            });
        });

        function editForm(id) {
            save_method = "edit";
            $('input[name=_method]').val('PATCH');
            $('#modal-form form')[0].reset();

            $.ajax({
                url: "{{ url('productsOut') }}" + '/' + id + "/edit",
                type: "GET",
                dataType: "JSON",
                success: function(data) {
                    $('#modal-form').modal('show');
                    $('.modal-title').text("@lang('main.edit_order')");

                    $('#id').val(data.id);
                    $('#product_id').val(data.product_id).trigger('change');
                    $('#customer_id').val(data.customer_id);
                    $('#qty').val(data.qty);
                    $('#date').val(data.date);

                    if (data.product_qty) {
                        $('#available-qty-value').text(data.product_qty);
                        $('#available-qty').show();
                    }
                },
                error: function() {
                    swal({
                        title: "{{ __('main.error_title') }}",
                        text: "{{ __('main.nothing_data') }}",
                        type: 'error',
                        timer: '1500'
                    });
                }
            });
        }

        function deleteData(id) {
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
                    url: "{{ url('productsOut') }}" + '/' + id,
                    type: "POST",
                    data: {'_method': 'DELETE', '_token': csrf_token},
                    success: function(data) {
                        $('#products-out-table').DataTable().ajax.reload();
                        swal({
                            title: "{{ __('main.delete_success_title') }}",
                            text: data.message,
                            type: 'success',
                            timer: '1500'
                        });
                    },
                    error: function(data) {
                        swal({
                            title: "{{ __('main.delete_error_title') }}",
                            text: data.responseJSON.message || "{{ __('main.delete_error_text') }}",
                            type: 'error',
                            timer: '1500'
                        });
                    }
                });
            });
        }
    </script>
@endsection