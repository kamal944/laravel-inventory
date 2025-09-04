<div class="modal fade" id="modal-form" tabindex="1" role="dialog" aria-hidden="true" data-backdrop="static">
    <div class="modal-dialog">
        <div class="modal-content">
            <form id="form-item" method="post" class="form-horizontal" data-toggle="validator" enctype="multipart/form-data">
                {{ csrf_field() }} {{ method_field('POST') }}

                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="{{ __('main.close') }}">
                        <span aria-hidden="true">&times;</span>
                    </button>
                    <h3 class="modal-title"></h3>
                </div>

                <div class="modal-body">
                    <input type="hidden" id="id" name="id">

                    <div class="box-body">
                        <div class="form-group">
                            <label>{{ __('main.product') }}</label>
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

                        <div class="form-group">
                            <label>{{ __('main.customer') }}</label>
                            {!! Form::select('customer_id', $customers, null, [
                                'class' => 'form-control select',
                                'placeholder' => __('main.choose_customer'),
                                'id' => 'customer_id',
                                'required'
                            ]) !!}
                            <span class="help-block with-errors"></span>
                        </div>

                        <div class="form-group">
                            <label>{{ __('main.quantity') }}</label>
                            <input type="number" class="form-control" id="qty" name="qty" required>
                            <small id="available-qty" class="form-text text-muted" style="display: none;">
                                {{ __('main.available_qty') }}: <span id="available-qty-value">0</span>
                            </small>
                            <span class="help-block with-errors"></span>
                        </div>

                        <div class="form-group">
                            <label>{{ __('main.date') }}</label>
                            <input data-date-format='yyyy-mm-dd' type="text" class="form-control" id="date" name="date" required>
                            <span class="help-block with-errors"></span>
                        </div>
                    </div>
                    <!-- /.box-body -->
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-default pull-left" data-dismiss="modal">{{ __('main.cancel') }}</button>
                    <button type="submit" class="btn btn-primary">{{ __('main.submit') }}</button>
                </div>
            </form>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>
<!-- /.modal -->

<style>
    /* RTL Support */
    html[dir="rtl"] .modal-header .close {
        margin-left: 0;
        margin-right: auto;
    }

    html[dir="rtl"] .form-horizontal .form-group {
        text-align: right;
    }

    html[dir="rtl"] .modal-footer {
        flex-direction: row-reverse;
    }

    html[dir="rtl"] .pull-left {
        float: right !important;
    }

    /* Highlight when quantity exceeds available */
    .qty-error {
        color: #dc3545;
        font-weight: bold;
    }
</style>

