@extends('layouts.admin')

@section('page-title')
    {{ __('Edit Supplier') }}
@endsection


@section('content')

<style>
    .cursor-pointer {
        cursor: pointer;
    }
</style>

    <div class="">
        <div class="">

            {{ Form::model($supplier, ['route' => ['suppliers.update', $supplier->id], 'method' => 'PUT', 'enctype' => 'multipart/form-data']) }}
            <div class="row">
                <div class="col-md-8">
                    <div class="card ">
                        <div class="card-header">
                            <h5>{{ __('Personal Detail') }}</h5>
                        </div>
                        <div class="card-body">

                            <div class="row">
                                <div class="form-group col-md-6">
                                    {!! Form::label('name', __(' Name'), ['class' => 'form-label']) !!}
                                    {!! Form::text('name', old('name'), [
                                        'class' => 'form-control',
                                        'required' => 'required',
                                        'placeholder' => 'Enter first name',
                                    ]) !!}
                                </div>
                               
                                <div class="form-group col-md-6">
                                    {!! Form::label('name', __(' Email'), ['class' => 'form-label']) !!}
                                    {!! Form::text('email', old('email'), [
                                        'class' => 'form-control',
                                        'required' => 'required',
                                        'placeholder' => 'Enter Email',
                                    ]) !!}
                                </div>
                               
                                <div class="form-group col-md-6">
                                    {!! Form::label('phone_number', __('Phone Number'), ['class' => 'form-label']) !!}
                                    {!! Form::text('phone', old('phone'), [
                                        'class' => 'form-control',
                                        'required' => 'required',
                                        'placeholder' => 'Enter Phone Number',
                                    ]) !!}
                                </div>
                                <div class="form-group col-md-6">
                                    {!! Form::label('location', __('Location'), ['class' => 'form-label']) !!}
                                    {!! Form::text('location', old('location'), [
                                        'class' => 'form-control',
                                        'required' => 'required',
                                        'placeholder' => 'Enter Location',
                                    ]) !!}
                                </div>
                                <div class="form-group col-md-6">
                                    {!! Form::label('status', __('Status'), ['class' => 'form-label']) !!}
                                    {!! Form::select('status', ['active' => 'Active', 'inactive' => 'Inactive'], old('status'), [
                                        'class' => 'form-control',
                                        'required' => 'required',
                                        'placeholder' => 'Select Status',
                                    ]) !!}
                                </div>
                                
                                <div class="form-group col-md-6">
                                    {{ Form::label('user', __('User'),['class'=>'col-form-label']) }}
                                    {!! Form::select('category_id', $categories, null,array('class' => 'form-select','required'=>'required')) !!}
                                </div>
                        
                        </div>
                    </div>
                </div>
                
                <div class="float-end">
                    <button type="submit" class="btn  btn-primary">{{ 'Update' }}</button>
                </div>
         
            <div class="col-12">
                {!! Form::close() !!}
            </div>
        </div>
    </div>

@endsection
