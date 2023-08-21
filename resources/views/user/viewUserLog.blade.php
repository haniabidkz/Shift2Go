{{ Form::model($request, ['route' => ['email.setting'], 'method' => 'POST', 'class' => 'permission_table_information', 'enctype' => 'multipart/form-data']) }}
    <div class="row">
        <div class="form-group col-md-6">
            {{ Form::label('status', __('Status'), ['class' => 'form-label text-dark']) }}
            {{ Form::text('status', null, ['class' => 'form-control', 'placeholder' => __('Enter Mail Driver')]) }}
        </div>
        <div class="form-group col-md-6">
            {{ Form::label('country', __('Country'), ['class' => 'form-label text-dark']) }}
            {{ Form::text('country', null, ['class' => 'form-control ', 'placeholder' => __('Enter Mail Driver')]) }}
        </div>
        <div class="form-group col-md-6">
            {{ Form::label('countryCode', __('Country Code'), ['class' => 'form-label text-dark']) }}
            {{ Form::text('countryCode', null, ['class' => 'form-control', 'placeholder' => __('Enter Mail Port')]) }}
        </div>
        <div class="form-group col-md-6">
            {{ Form::label('region', __('Region'), ['class' => 'form-label text-dark']) }}
            {{ Form::text('region', null, ['class' => 'form-control', 'placeholder' => __('Enter Mail Username')]) }}
        </div>
        <div class="form-group col-md-6">
            {{ Form::label('regionName', __('Region Name'), ['class' => 'form-label text-dark']) }}
            {{ Form::text('regionName', null, ['class' => 'form-control', 'placeholder' => __('Enter Mail Username')]) }}
        </div>
        <div class="form-group col-md-6">
            {{ Form::label('city', __('City'), ['class' => 'form-label text-dark']) }}
            {{ Form::text('city', null, ['class' => 'form-control', 'placeholder' => __('Enter Mail Encryption')]) }}
        </div>
        <div class="form-group col-md-6">
            {{ Form::label('zip', __('Zip'), ['class' => 'form-label text-dark']) }}
            {{ Form::text('zip', null, ['class' => 'form-control', 'placeholder' => __('Enter Mail From Address')]) }}
        </div>
        <div class="form-group col-md-6">
            {{ Form::label('lat', __('Lat'), ['class' => 'form-label text-dark']) }}
            {{ Form::text('lat', null, ['class' => 'form-control', 'placeholder' => __('Enter Mail From Name')]) }}
        </div>
        <div class="form-group col-md-6">
            {{ Form::label('lon', __('Lon'), ['class' => 'form-label text-dark']) }}
            {{ Form::text('lon', null, ['class' => 'form-control', 'placeholder' => __('Enter Mail From Address')]) }}
        </div>
        <div class="form-group col-md-6">
            {{ Form::label('timezone', __('TimeZone'), ['class' => 'form-label text-dark']) }}
            {{ Form::text('timezone', null, ['class' => 'form-control', 'placeholder' => __('Enter Mail From Name')]) }}
        </div>
        <div class="form-group col-md-6">
            {{ Form::label('isp', __('Isp'), ['class' => 'form-label text-dark']) }}
            {{ Form::text('isp', null, ['class' => 'form-control', 'placeholder' => __('Enter Mail Driver')]) }}
        </div>
        <div class="form-group col-md-6">
            {{ Form::label('org', __('Org'), ['class' => 'form-label text-dark']) }}
            {{ Form::text('org', null, ['class' => 'form-control ', 'placeholder' => __('Enter Mail Driver')]) }}
        </div>
        <div class="form-group col-md-6">
            {{ Form::label('as', __('As'), ['class' => 'form-label text-dark']) }}
            {{ Form::text('as', null, ['class' => 'form-control', 'placeholder' => __('Enter Mail Port')]) }}
        </div>
        <div class="form-group col-md-6">
            {{ Form::label('query', __('Query'), ['class' => 'form-label text-dark']) }}
            {{ Form::text('query', null, ['class' => 'form-control', 'placeholder' => __('Enter Mail Username')]) }}
        </div>
        <div class="form-group col-md-6">
            {{ Form::label('browser_name', __('Browser Name'), ['class' => 'form-label text-dark']) }}
            {{ Form::text('browser_name', null, ['class' => 'form-control', 'placeholder' => __('Enter Mail Username')]) }}
        </div>
        <div class="form-group col-md-6">
            {{ Form::label('os_name', __('Os Name'), ['class' => 'form-label text-dark']) }}
            {{ Form::text('os_name', null, ['class' => 'form-control', 'placeholder' => __('Enter Mail Encryption')]) }}
        </div>
        <div class="form-group col-md-6">
            {{ Form::label('browser_language', __('Browser Language'), ['class' => 'form-label text-dark']) }}
            {{ Form::text('browser_language', null, ['class' => 'form-control', 'placeholder' => __('Enter Mail From Address')]) }}
        </div>
        <div class="form-group col-md-6">
            {{ Form::label('device_type', __('Device Type'), ['class' => 'form-label text-dark']) }}
            {{ Form::text('device_type', null, ['class' => 'form-control', 'placeholder' => __('Enter Mail From Name')]) }}
        </div>
        <div class="form-group col-md-6">
            {{ Form::label('referrer_host', __('Referrer Host'), ['class' => 'form-label text-dark']) }}
            {{ Form::text('referrer_host', null, ['class' => 'form-control', 'placeholder' => __('Enter Mail From Address')]) }}
        </div>
        <div class="form-group col-md-6">
            {{ Form::label('referrer_path', __('Referrer Path'), ['class' => 'form-label text-dark']) }}
            {{ Form::text('referrer_path', null, ['class' => 'form-control', 'placeholder' => __('Enter Mail From Name')]) }}
        </div>
    </div>
{{ Form::close() }}

