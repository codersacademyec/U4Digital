
@extends('admin.layouts.admin')

@section('title', __('views.admin.companies.index.title'))

@section('content')
    <div class="row">
        <div class="pull-right">
            {{ 'Enlaces'  }}
        </div>
        <div class="col col-md-12">
            <div class="pull-right">
                <a class="btn btn-xs btn-primary" href="{{ route('admin.companies.create') }}" data-toggle="tooltip" data-placement="top" data-title="{{ __('views.admin.companies.add.title') }}">
                    <i class="fa fa-plus"></i>
                </a>
            </div>
        </div>
        <div class="col col-md-12">

        </div>
    </div>
@endsection