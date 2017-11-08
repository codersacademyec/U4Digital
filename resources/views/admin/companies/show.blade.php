@extends('admin.layouts.admin')

@section('title', __('views.admin.companies.show.title', ['name' => $company->name]))

@section('content')
    <div class="row">
        <table class="table table-striped table-hover">
            <tbody>
            <tr>
                <th>{{ __('views.admin.companies.show.table_header_0') }}</th>
                <td>{{ $company->ruc }}</td>
            </tr>

            <tr>
                <th>{{ __('views.admin.companies.show.table_header_1') }}</th>
                <td>{{ $company->name }}</td>
            </tr>

            <tr>
                <th>{{ __('views.admin.companies.show.table_header_2') }}</th>
                <td>{{ $company->business_name }}</td>
            </tr>
            <tr>
                <th>{{ __('views.admin.companies.show.table_header_3') }}</th>
                <td>{{ $company->email }}</td>
            </tr>
            <tr>
                <th>{{ __('views.admin.companies.show.table_header_4') }}</th>
                <td>{{ $company->phone }}</td>
            </tr>
            <tr>
                <th>{{ __('views.admin.companies.show.table_header_5') }}</th>
                <td>{{ $company->created_at }} ({{ $company->created_at->diffForHumans() }})</td>
            </tr>

            <tr>
                <th>{{ __('views.admin.companies.show.table_header_6') }}</th>
                <td>{{ $company->updated_at }} ({{ $company->updated_at->diffForHumans() }})</td>
            </tr>
            </tbody>
        </table>
    </div>
@endsection