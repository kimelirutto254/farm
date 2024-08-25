@extends('layouts.admin')

@section('page-title')
    {{ __('Edit Farmer') }}
@endsection

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('home') }}">{{ __('Home') }}</a></li>
    <li class="breadcrumb-item"><a href="{{ url('employee') }}">{{ __('Farmer') }}</a></li>
    <li class="breadcrumb-item">{{ __('Edit Farmer') }}</li>
@endsection

@section('content')
    <style>
        .cursor-pointer {
            cursor: pointer;
        }
    </style>

    <div class="">
        <div class="">
            <div class="row">

            </div>
            {{ Form::model($farmer, ['route' => ['farmer.update', $farmer->id], 'method' => 'put', 'enctype' => 'multipart/form-data']) }}
            <div class="row">
                <div class="col-md-6">
                    <div class="card em-card">
                        <div class="card-header">
                            <h5>{{ __('Personal Detail') }}</h5>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="form-group col-md-6">
                                    {!! Form::label('grower_id', __('Grower ID'), ['class' => 'form-label']) !!}<span class="text-danger pl-1">*</span>
                                    {!! Form::text('grower_id', null, [
                                        'class' => 'form-control',
                                        'required' => 'required',
                                        'placeholder' => 'Enter Grower ID',
                                    ]) !!}
                                </div>

                                <div class="form-group col-md-6">
                                    {!! Form::label('id_number', __('Id Number'), ['class' => 'form-label']) !!}<span class="text-danger pl-1">*</span>
                                    {!! Form::text('id_number', null, [
                                        'class' => 'form-control',
                                        'required' => 'required',
                                        'placeholder' => 'Enter id  number',
                                    ]) !!}
                                </div>

                                <div class="form-group col-md-6">
                                    {!! Form::label('first_name', __('First Name'), ['class' => 'form-label']) !!}<span class="text-danger pl-1">*</span>
                                    {!! Form::text('first_name', null, [
                                        'class' => 'form-control',
                                        'required' => 'required',
                                        'placeholder' => 'Enter first name',
                                    ]) !!}
                                </div>
                                <div class="form-group col-md-6">
                                    {!! Form::label('middle_name', __('Middle Name'), ['class' => 'form-label']) !!}<span class="text-danger pl-1">*</span>
                                    {!! Form::text('middle_name', null, [
                                        'class' => 'form-control',
                                        'required' => 'required',
                                        'placeholder' => 'Enter middle name',
                                    ]) !!}
                                </div>
                                <div class="form-group col-md-6">
                                    {!! Form::label('last_name', __('Last Name'), ['class' => 'form-label']) !!}<span class="text-danger pl-1">*</span>
                                    {!! Form::text('last_name', null, [
                                        'class' => 'form-control',
                                        'required' => 'required',
                                        'placeholder' => 'Enter last name',
                                    ]) !!}
                                </div>

                                <div class="form-group col-md-6">
                                    {!! Form::label('phone_number', __('Phone Number'), ['class' => 'form-label']) !!}<span class="text-danger pl-1">*</span>
                                    {!! Form::text('phone_number', null, [
                                        'class' => 'form-control',
                                        'required' => 'required',
                                        'placeholder' => 'Enter Phone Number',
                                    ]) !!}
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group">
                                        {!! Form::label('gender', __('Gender'), ['class' => 'form-label']) !!}<span class="text-danger pl-1">*</span>
                                        <div class="d-flex radio-check">
                                            <div class="custom-control custom-radio custom-control-inline">
                                                {!! Form::radio('gender', 'Male', null, ['id' => 'g_male', 'class' => 'form-check-input']) !!}
                                                <label class="form-check-label " for="g_male">{{ __('Male') }}</label>
                                            </div>
                                            <div class="custom-control custom-radio ms-1 custom-control-inline">
                                                {!! Form::radio('gender', 'Female', null, ['id' => 'g_female', 'class' => 'form-check-input']) !!}
                                                <label class="form-check-label " for="g_female">{{ __('Female') }}</label>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                            </div>

                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="card em-card">
                        <div class="card-header">
                            <h5>{{ __('Other Details') }}</h5>
                        </div>
                        <div class="card-body employee-detail-create-body">
                            <div class="row">
                                @csrf
                                @method('PUT')
                                <div class="form-group col-md-6">
                                    {!! Form::label('region', __('Region'), ['class' => 'form-label']) !!}
                                    {!! Form::text('region', null, ['class' => 'form-control']) !!}
                                </div>
                                <div class="form-group col-md-6">
                                    {!! Form::label('town', __('Town'), ['class' => 'form-label']) !!}
                                    {!! Form::text('town', null, ['class' => 'form-control']) !!}
                                </div>

                                <div class="form-group col-md-6">
                                    {!! Form::label('route', __('Route'), ['class' => 'form-label']) !!}<span class="text-danger pl-1">*</span>
                                    {!! Form::text('route', null, [
                                        'class' => 'form-control',
                                        'required' => 'required',
                                        'placeholder' => 'Enter Route',
                                    ]) !!}
                                </div>
                                <div class="form-group col-md-6">
                                    {!! Form::label('collection_center', __('Collection Center'), ['class' => 'form-label']) !!}<span class="text-danger pl-1">*</span>
                                    {!! Form::text('collection_center', null, [
                                        'class' => 'form-control',
                                        'required' => 'required',
                                        'placeholder' => 'Enter collection center',
                                    ]) !!}
                                </div>
                                <div class="form-group col-md-6">
                                    {!! Form::label('nearest_center', __('Nearest Centre'), ['class' => 'form-label']) !!}<span class="text-danger pl-1">*</span>
                                    {!! Form::text('nearest_center', null, [
                                        'class' => 'form-control',
                                        'required' => 'required',
                                        'placeholder' => 'Enter nearest center',
                                    ]) !!}
                                </div>
                                <div class="form-group col-md-6">
                                    {!! Form::label('leased_land', __('Leased Land in Acres'), ['class' => 'form-label']) !!}<span class="text-danger pl-1">*</span>
                                    {!! Form::text('leased_land', null, [
                                        'class' => 'form-control',
                                        'required' => 'required',
                                        'placeholder' => 'Enter Leased Land in Acres',
                                    ]) !!}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>

        <div class="float-end">
            <button type="submit" class="btn  btn-primary">{{ __('Update') }}</button>
        </div>
        </form>
    </div>
@endsection

@push('script-page')
    <script>
        $('input[type="file"]').change(function(e) {
            var file = e.target.files[0].name;
            var file_name = $(this).attr('data-filename');
            $('.' + file_name).append(file);
        });
    </script>

    <script>
        $(document).ready(function() {
            var now = new Date();
            var month = (now.getMonth() + 1);
            var day = now.getDate();
            if (month < 10) month = "0" + month;
            if (day < 10) day = "0" + day;
            var today = now.getFullYear() + '-' + month + '-' + day;
            $('.current_date').val(today);
        });
    </script>
@endpush
