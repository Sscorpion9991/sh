@extends('layouts.admin')

@section('content')
    <div class="container">
        @if(isset($organization))
            @include('admin.organization.menu')
        @endif

        @include('common.errors')
        @if(session()->get('save'))
        <p class="alert alert-success">Сохранено</p>
        @endif
        <form action="{{isset($organization) && $parent != $organization ? url('admin/organization/'. $organization->id .'/edit') : url('admin/organization/create')}}" method="post" class="form-horizontal">
            {{ csrf_field() }}

            {{-- нужно для определения того что добавляется подразделение к предприятию --}}
            {!! Form::hidden('type', $type) !!}
            @if(isset($parent))
                {!! Form::hidden('parent', $parent->id) !!}
            @endif

            @if(isset($parent))
                <div class="form-group">
                    {!! Form::label('status', 'Статус организации', ['class' => 'col-md-2 control-label']) !!}
                    <div class="col-md-10">
                        {{$parent->status->name}}
                    </div>
                </div>
                <div class="form-group">
                    {!! Form::label('opf', 'ОПФ', ['class' => 'col-md-2 control-label']) !!}
                    <div class="col-md-10">
                        {{$parent->opf->name}}
                    </div>
                </div>
            @else
                <div class="form-group">
                    {!! Form::label('status', 'Статус организации', ['class' => 'col-md-2 control-label']) !!}
                    <div class="col-md-10">
                        {!! Form::select('status', $statuses, isset($organization) && $organization->status ? $organization->status->id : '', ['class' => 'multiselect']) !!}
                    </div>
                </div>
                <div class="form-group">
                    {!! Form::label('opf', 'ОПФ', ['class' => 'col-md-2 control-label']) !!}
                    <div class="col-md-10">
                        {!! Form::select('opf', $opfs, isset($organization) && $organization->opf ? $organization->opf->id : old('opf'), ['class' => 'multiselect']) !!}
                    </div>
                </div>
            @endif

            <div class="form-group">
                {!! Form::label('edrpou', 'ЄДРПОУ код', ['class' => 'col-md-2 control-label']) !!}
                <div class="col-md-10">
                    {!! Form::text('edrpou', old('edrpou'), ['class' => 'form-control']) !!}
                </div>
            </div>
            <div class="form-group">
                {!! Form::label('fullName', 'Название полное', ['class' => 'col-md-2 control-label']) !!}
                <div class="col-md-10">
                    {!! Form::text('fullName', old('fullName'), ['class' => 'form-control']) !!}
                </div>
            </div>
            <div class="form-group">
                {!! Form::label('shortName', 'Название сокращенное', ['class' => 'col-md-2 control-label']) !!}
                <div class="col-md-10">
                    {!! Form::text('shortName', old('shortName'), ['class' => 'form-control']) !!}
                </div>
            </div>
            <div class="form-group">
                {!! Form::label('address', 'Адрес', ['class' => 'col-md-2 control-label']) !!}
                <div class="col-md-10">
                    {!! Form::text('address', old('address'), ['class' => 'form-control']) !!}
                </div>
            </div>
            <div class="form-group">
                {!! Form::label('postCode', 'Почтовый индекс', ['class' => 'col-md-2 control-label']) !!}
                <div class="col-md-10">
                    {!! Form::text('postCode', old('postCode'), ['class' => 'form-control']) !!}
                </div>
            </div>
            <div class="form-group">
                {!! Form::label('city', 'Город', ['class' => 'col-md-2 control-label']) !!}
                <div class="col-md-10">
                    {!! Form::select('city', ['' => 'Другой'] + $cities->toArray(), isset($organization) && $organization->city ? $organization->city->id : old('city'), ['class' => 'multiselect']) !!}
                </div>
            </div>
            <div class="form-group">
                <label class="control-label col-md-2">Телефон</label>
                <div class="col-md-10">
                    @forelse($phone as $val)
                        <input type="text" name="phone[]" value="{{$val}}" class="form-control" >
                    @empty
                        <input type="text" name="phone[]" class="form-control" >
                    @endforelse
                    <a class="pull-right duplicateForm">добавить еще телефон</a>
                </div>
            </div>
            <div class="form-group">
                <label class="col-md-2 control-label">E-Mail</label>
                <div class="col-md-10">
                    @forelse($email as $val)
                        <input type="email" name="email[]" value="{{$val}}" class="form-control">
                    @empty
                        <input type="email" name="email[]" class="form-control">
                    @endforelse
                    <a class="pull-right duplicateForm">добавить еще e-mail</a>
                </div>
            </div>

            <div class="form-group">
                {!! Form::label('chief', 'Руководитель', ['class' => 'col-md-2 control-label']) !!}
                <div class="col-md-10">
                    {!! Form::text('chief', old('chief'), ['class' => 'form-control']) !!}
                </div>
            </div>

            <div class="col-md-offset-2">
                <button type="submit" class="btn btn-primary">Сохранить</button>
            </div>
        </form>
    </div>
@endsection