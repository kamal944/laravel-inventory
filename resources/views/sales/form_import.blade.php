<div class="row">
    <!-- left column -->
    <div class="col-md-6">
        <!-- general form elements -->
        <div class="box box-primary">
            <div class="box-header with-border">
                <h3 class="box-title">{{ trans('main.import_sales_data') }}</h3>
                <br><br>
                @if(session('success'))
                    <div class="alert alert-success alert-dismissible ">
                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                        <i class="icon fa fa-check"></i> {{ trans('main.success') }}!&nbsp;
                        {{ session('success') }}
                    </div>
                @endif

                @if(session('error'))
                    <div class="alert alert-danger alert-dismissible">
                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                        <i class="icon fa fa-ban"></i> {{ trans('main.error') }}!&nbsp;
                        {{ session('error') }}
                    </div>
                @endif
            </div>
            <!-- /.box-header -->
            <!-- form start -->
            <form role="form" action="{{ route('import.sales') }}" method="post" enctype="multipart/form-data">
                @csrf
                <div class="box-body">
                    <div class="form-group">
                        <label for="exampleInputFile">
                            {{ trans('main.input_file') }}
                        </label>
                        <input type="file" id="file" name="file">
                        <p class="text-danger">{{ $errors->first('file') }}</p>
                    </div>
                </div>

                <!-- /.box-body -->

                <div class="box-footer">
                    <button type="submit" class="btn btn-primary">{{ trans('main.submit') }}</button>
                </div>

                <div class="box-body">
                    <div class="alert alert-warning alert-dismissible">
                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                        <i class="icon fa fa-warning"></i> {{ trans('main.note') }}! &nbsp;
                        {{ trans('main.file_format_note') }}
                    </div>
                </div>
            </form>
        </div>
        <!-- /.box -->
    </div>
</div>