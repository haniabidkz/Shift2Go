
    {{ Form::open(array('route' => array('employee.import'),'method'=>'post', 'enctype' => "multipart/form-data")) }}
    <div class="row"  style="padding: 15px 15px;">
        <div class="col-md-12 mb-2">
        <div class="d-flex align-items-center justify-content-between">
            {{Form::label('file',__('Download sample customer CSV file'),['class'=>'form-control-label w-auto m-0'])}}
            <div>
                <a href="{{asset(Storage::url('uploads/sample')).'/sample-product.csv'}}" class="btn btn-sm btn-primary" style="border-radius: 5px">
                    <i class="fa fa-download"></i> {{__('Download')}}
                </a>
            </div>
        </div>
        </div>
        <div class="col-md-12">
            {{Form::label('file',__('Select CSV File'),['class'=>'form-label'])}}
            <label for="file" class="form-label choose-files bg-primary "><i class="ti ti-upload px-1"></i>{{ __('Select File') }}</label>
            <input type="file" name="file" id="file" class="custom-input-file d-none">
        </div>
        <div class="modal-footer border-0 p-0">
            <input type="button" value="{{__('Close')}}" class="btn btn-light" data-bs-dismiss="modal">
            <input type="submit" value="{{__('Upload')}}" class="btn btn-primary">
        </div>
    </div>
    {{ Form::close() }}
