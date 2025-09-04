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
            <h3 class="box-title">{{ __('main.products_in_title') }}</h3>
        </div>

        <!-- Add Product Form -->
        <div class="box-body">
            <div class="row" style="margin-left: 0">
<h3>{{__('main.add_product_in')}}</h3>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <form id="form-item" method="post" class="form-horizontal" enctype="multipart/form-data">
                        @csrf
                        <input type="hidden" name="_method" value="POST">

                        <!-- First Row - Labels -->
                        <div class="row">
                            <div class="col-md-3">
                                <label>{{ __('main.product') }}</label>
                            </div>
                            <div class="col-md-3">
                                <label>{{ __('main.supplier') }}</label>
                            </div>
                            <div class="col-md-3">
                                <label>{{ __('main.quantity') }}</label>
                            </div>
                            <div class="col-md-3">
                                <label>{{ __('main.date') }}</label>
                            </div>
                        </div>

                        <!-- Second Row - Inputs -->
                        <div class="row">
                            <div class="col-md-3 form-group" style="margin-left: 0px !important;">
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
                                <div class="help-block with-errors"></div>
                                <small id="current-qty" class="form-text text-muted" style="display: none;">
                                    {{ __('main.current_qty') }}: <span id="current-qty-value">0</span>
                                </small>
                            </div>

                            <div class="col-md-3 form-group" style="margin-left: 0px !important;">
                                {!! Form::select('supplier_id', $suppliers, null, [
                                    'class' => 'form-control select',
                                    'placeholder' => __('main.choose_supplier'),
                                    'id' => 'supplier_id',
                                    'required'
                                ]) !!}
                                <div class="help-block with-errors"></div>
                            </div>

                            <div class="col-md-3 form-group" style="margin-left: 0px !important;">
                                <input type="number" class="form-control" id="qty" name="qty" required min="1">
                                <div class="help-block with-errors"></div>
                            </div>

                            <div class="col-md-3 form-group" style="margin-left: 0px !important;">
                                <input data-date-format='yyyy-mm-dd' type="text" class="form-control datepicker" id="date" name="date" required value="{{ date('Y-m-d') }}">
                                <div class="help-block with-errors"></div>
                            </div>
                        </div>

                        <!-- Third Row - Submit Button -->
                        <div class="row" >
                            <div class="col-md-12 form-group"style="display: flex;justify-content: end" >
                                <button type="submit" class="btn btn-primary" id="submit-btn">{{ __('main.add_product_in') }}</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <div class="box-header">
            <h3>{{ __('main.export_invoice') }}</h3>
            <form method="GET" class="row mb-3" id="exportForm">
                @csrf
                <!-- Filter Inputs -->
                <div class="col-md-3 form-group">
                    <label>{{ __('main.from_date') }}</label>
                    <input type="date" name="from_date" class="form-control" value="{{ request('from_date') }}" placeholder="{{ __('main.from_date') }}">
                </div>
                <div class="col-md-3 form-group">
                    <label>{{ __('main.to_date') }}</label>
                    <input type="date" name="to_date" class="form-control" value="{{ request('to_date') }}" placeholder="{{ __('main.to_date') }}">
                </div>
                <div class="col-md-3 form-group">
                    <label>{{ __('main.product') }}</label>
                    <select name="product_id" class="form-control">
                        <option value="">{{ __('main.select_product') }}</option>
                        @foreach($products as $product)
                            <option value="{{ $product->id }}" {{ request('product_id') == $product->id ? 'selected' : '' }}>
                                @if(app()->getLocale() === 'ar')
                                    {{ $product->name_ar }}
                                @else
                                    {{ $product->name_en }}
                                @endif
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3 form-group">
                    <label>{{ __('main.supplier') }}</label>
                    <select name="supplier_id" class="form-control">
                        <option value="">{{ __('main.select_supplier') }}</option>
                        @foreach($suppliers as $id => $name)
                            <option value="{{ $id }}" {{ request('supplier_id') == $id ? 'selected' : '' }}>{{ $name }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- New column for recent records count -->
                <div class="col-md-3 form-group">
                    <label>{{ __('main.number_of_recent_records') }}</label>
                    <input type="number" name="recent_count" class="form-control" value="{{ request('recent_count') }}" placeholder="{{ __('main.enter_number') }}" min="1">
                </div>

                <div class="col-md-6 form-group" style="margin-top: 25px;">
                    <button type="submit" formaction="{{ route('exportExcel.productMasukAll') }}" class="btn btn-success">
                        {{ __('main.export_excel') }}
                    </button>
                    <button type="submit" formaction="{{ route('exportPDF.productMasukAll') }}" class="btn btn-danger">
                        {{ __('main.export_pdf') }}
                    </button>
                    <button type="submit" formaction="{{ route('invoice-in') }}" class="btn btn-primary">
                        {{ __('main.export_invoice') }}
                    </button>
                </div>
            </form>
        </div>
        <div class="box-body">
            <table id="products-in-table" class="table table-striped">
                <thead>
                <tr>
                    <th>{{ __('main.id') }}</th>
                    <th>{{ __('main.products') }}</th>
                    <th>{{ __('main.supplier') }}</th>
                    <th>{{ __('main.qty') }}</th>
                    <th>{{ __('main.date_in') }}</th>
                    <th>{{ __('main.actions') }}</th>
                </tr>
                </thead>
                <tbody></tbody>
            </table>
        </div>

    </div>
@endsection

@section('bot')
    <script src="{{ asset('assets/bower_components/datatables.net/js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('assets/bower_components/datatables.net-bs/js/dataTables.bootstrap.min.js') }}"></script>

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


    <script>
        $(function () {
            // Initialize date picker
            $('.datepicker').datepicker({
                autoclose: true,
                todayHighlight: true,
                format: 'yyyy-mm-dd'
            });

            // Initialize DataTables
            var table = $('#products-in-table').DataTable({
                processing: true,
                serverSide: true,
                ajax: "{{ route('api.productsIn') }}",
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
                    {data: 'supplier_name', name: 'supplier_name'},
                    {data: 'qty', name: 'qty'},
                    {data: 'date', name: 'date'},
                    {data: 'action', name: 'action', orderable: false, searchable: false}
                ],
                language: {
                    url: "{{ app()->getLocale() === 'ar' ? '//cdn.datatables.net/plug-ins/1.10.25/i18n/Arabic.json' : '' }}"
                }
            });

            // Show current quantity when product is selected
            $('#product_id').change(function() {
                updateCurrentQty();
            });

            function updateCurrentQty() {
                var selectedOption = $('#product_id').find('option:selected');
                var currentQty = selectedOption.data('qty') || 0;
                $('#current-qty-value').text(currentQty);
                $('#current-qty').toggle(!!selectedOption.val());
            }

// Form submission handler
            $('#form-item').on('submit', function(e) {
                e.preventDefault();

                // Validate form
                if (!this.checkValidity()) {
                    e.stopPropagation();
                    return false;
                }

                // Store current supplier and date values
                var currentSupplier = $('#supplier_id').val();
                var currentDate = $('#date').val();
                var productSelect = $('#product_id');

                // Disable submit button to prevent multiple submissions
                $('#submit-btn').prop('disabled', true);

                $.ajax({
                    url: "{{ route('productsIn.store') }}",
                    type: "POST",
                    data: $(this).serialize(),
                    success: function(response) {
                        // Reset only product and quantity fields
                        productSelect.val('').trigger('change');
                        $('#qty').val('');

                        // Restore supplier and date values
                        $('#supplier_id').val(currentSupplier);
                        $('#date').val(currentDate);

                        // Enable submit button
                        $('#submit-btn').prop('disabled', false);

                        // Reload table
                        table.ajax.reload(null, false);

                        // Show success message
                        toastr.success(response.message || 'Operation successful');

                        // Refresh product options without breaking select functionality
                        refreshProductOptions(productSelect);
                    },
                    error: function(xhr) {
                        // Enable submit button
                        $('#submit-btn').prop('disabled', false);

                        // Show error message
                        var errorMsg = xhr.responseJSON?.message || 'An error occurred';
                        toastr.error(errorMsg);

                        // Log full error to console
                        console.error('Error:', xhr.responseJSON || xhr.responseText);
                    }
                });
            });

            function refreshProductOptions(selectElement) {
                // Store the original select element reference
                var $originalSelect = selectElement;

                $.get("{{ route('api.products') }}", function(products) {
                    // Create a new select element
                    var $newSelect = $originalSelect.clone();
                    $newSelect.empty();

                    // Add default option
                    $newSelect.append($('<option></option>').val('').text('{{ __("main.choose_product") }}'));

                    // Add product options
                    products.forEach(function(product) {
                        $newSelect.append(
                            $('<option></option>')
                                .val(product.id)
                                .text(product.name)
                                .attr('data-qty', product.qty)
                        );
                    });

                    // Replace the old select with the new one
                    $originalSelect.replaceWith($newSelect);

                    // Reattach event handlers
                    $newSelect.change(function() {
                        updateCurrentQty();
                    });

                    // Update the reference
                    $('#product_id').data('select2')?.destroy();
                    $newSelect.select2();
                });
            }

// Initialize current quantity display on page load
            updateCurrentQty();

            // Delete function remains the same
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
                        url: "{{ url('productsIn') }}" + '/' + id,
                        type: "POST",
                        data: {'_method': 'DELETE', '_token': csrf_token},
                        success: function(data) {
                            table.ajax.reload();
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
        });
    </script>
@endsection