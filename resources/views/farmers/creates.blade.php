@extends('layouts.admin')

@section('page-title')
    {{ __('Create Farmer') }}
@endsection

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('home') }}">{{ __('Home') }}</a></li>
    <li class="breadcrumb-item"><a href="{{ url('employee') }}">{{ __('Farmer') }}</a></li>
    <li class="breadcrumb-item">{{ __('Create Farmer') }}</li>
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
            {{ Form::open(['route' => ['farmer.store'], 'method' => 'post', 'enctype' => 'multipart/form-data']) }}
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
                                    {!! Form::text('grower_id', old('grower_id'), [
                                        'class' => 'form-control',
                                        'required' => 'required',
                                        'placeholder' => 'Enter Grower ID',
                                    ]) !!}
                                </div>
                              
                                <div class="form-group col-md-6">
                                    {!! Form::label('id_number', __('Id Number'), ['class' => 'form-label']) !!}<span class="text-danger pl-1">*</span>
                                    {!! Form::text('id_number', old('id_number'), [
                                        'class' => 'form-control',
                                        'required' => 'required',
                                        'placeholder' => 'Enter id  number',
                                    ]) !!}
                                </div>
                              
                                <div class="form-group col-md-6">
                                    {!! Form::label('name', __('First Name'), ['class' => 'form-label']) !!}<span class="text-danger pl-1">*</span>
                                    {!! Form::text('first_name', old('first_name'), [
                                        'class' => 'form-control',
                                        'required' => 'required',
                                        'placeholder' => 'Enter first name',
                                    ]) !!}
                                </div>
                                <div class="form-group col-md-6">
                                    {!! Form::label('name', __('Middle Name'), ['class' => 'form-label']) !!}<span class="text-danger pl-1">*</span>
                                    {!! Form::text('middle_name', old('middle_name'), [
                                        'class' => 'form-control',
                                        'required' => 'required',
                                        'placeholder' => 'Enter middle name',
                                    ]) !!}
                                </div>
                                <div class="form-group col-md-6">
                                    {!! Form::label('name', __('Last Name'), ['class' => 'form-label']) !!}<span class="text-danger pl-1">*</span>
                                    {!! Form::text('last_name', old('last_name'), [
                                        'class' => 'form-control',
                                        'required' => 'required',
                                        'placeholder' => 'Enter last name',
                                    ]) !!}
                                </div>
                               
                                <div class="form-group col-md-6">
                                    {!! Form::label('phone_number', __('Phone Number'), ['class' => 'form-label']) !!}<span class="text-danger pl-1">*</span>
                                    {!! Form::text('phone_number', old('phone_number'), [
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
                                                <input type="radio" id="g_male" value="Male" name="gender"
                                                    class="form-check-input">
                                                <label class="form-check-label " for="g_male">{{ __('Male') }}</label>
                                            </div>
                                            <div class="custom-control custom-radio ms-1 custom-control-inline">
                                                <input type="radio" id="g_female" value="Female" name="gender"
                                                    class="form-check-input">
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
                                <div class="form-group col-md-6">

                                    {!! Form::label('region', __('Region'), ['class' => 'form-label']) !!}
                                    {!! Form::text('region',null, ['class' => 'form-control']) !!}
                                </div>
                                <div class="form-group col-md-6">
                                    {!! Form::label('town', __('Town'), ['class' => 'form-label']) !!}
                                    {!! Form::text('town',null, ['class' => 'form-control']) !!}
                                </div>

                             

                                <div class="form-group col-md-6">

                                    {!! Form::label('route', __('Route'), ['class' => 'form-label']) !!}<span class="text-danger pl-1">*</span>
                                    {!! Form::text('route', old('route'), [
                                        'class' => 'form-control',
                                        'required' => 'required',
                                        'placeholder' => 'Enter Route',
                                    ]) !!}
                                </div>
                                <div class="form-group col-md-6">

                                     {!! Form::label('collection', __('Collection Center'), ['class' => 'form-label']) !!}<span class="text-danger pl-1">*</span>
                                     {!! Form::text('collection_center', old('collection_center'), [
                                         'class' => 'form-control',
                                         'required' => 'required',
                                         'placeholder' => 'Enter collection center',
                                     ]) !!}
                                    </div>
                                <div class="form-group col-md-6">

                                    {!! Form::label('route', __('Nearest Centre '), ['class' => 'form-label']) !!}<span class="text-danger pl-1">*</span>
                                    {!! Form::text('nearest_center', old('nearest_center'), [
                                        'class' => 'form-control',
                                        'required' => 'required',
                                        'placeholder' => 'Enter nearest center',
                                    ]) !!}
                                    </div>
                                    <div class="form-group col-md-6">

                                     {!! Form::label('leased_land', __('Leased Land in Acres'), ['class' => 'form-label']) !!}<span class="text-danger pl-1">*</span>
                                     {!! Form::text('leased_land', old('leased_land'), [
                                         'class' => 'form-control',
                                         'required' => 'required',
                                         'placeholder' => '',
                                     ]) !!}
                                    </div>

                            
                        </div>
                    </div>
                </div>
            </div>
          
         
     

        </div>

        <div class="float-end">
            <button type="submit" class="btn  btn-primary">{{ 'Create' }}</button>
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
