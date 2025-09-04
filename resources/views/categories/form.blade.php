<div class="modal fade" id="modal-form" tabindex="1" role="dialog" aria-hidden="true" data-backdrop="static">
    <div class="modal-dialog">
        <div class="modal-content">
            <form id="form-item" method="post" class="form-horizontal" data-toggle="validator" enctype="multipart/form-data">
                {{ csrf_field() }} {{ method_field('POST') }}

                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                    <h3 class="modal-title"></h3>
                </div>

                <div class="modal-body">
                    <input type="hidden" id="id" name="id">

                    <div class="box-body">
                        <div class="form-group">
                            <label>@lang('main.name')</label>
                            <input type="text" class="form-control" id="name" name="name" autofocus required>
                            <span class="help-block with-errors"></span>
                        </div>
                    </div>
                    <!-- /.box-body -->
                </div>

                <div class="modal-footer" style="display: flex; justify-content: space-between;">
                    <button type="button" class="btn btn-default" data-dismiss="modal">
                        @lang('main.cancel')
                    </button>
                    <button type="submit" class="btn btn-primary">
                        @lang('main.submit')
                    </button>
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
</style>