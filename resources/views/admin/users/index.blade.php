@extends('admin.layouts.admin')

@section('title', __('views.admin.users.index.title'))

@section('content')
    <div class="row">
        <div class="col col-md-12">
            <div class="pull-right">
                <a class="btn btn-xs btn-primary" href="{{ route('admin.users.create') }}" data-toggle="tooltip" data-placement="top" data-title="{{ __('views.admin.users.index.create') }}">
                    <i class="fa fa-plus"></i>
                </a>
            </div>
        </div>
        <table class="table table-striped table-bordered dt-responsive nowrap" cellspacing="0"
               width="100%">
            <thead>
            <tr>
                <th>@sortablelink('email', __('views.admin.users.index.table_header_0'),['page' => $users->currentPage()])</th>
                <th>@sortablelink('name',  __('views.admin.users.index.table_header_1'),['page' => $users->currentPage()])</th>
                <th>{{ __('views.admin.users.index.table_header_2') }}</th>
                <th>@sortablelink('active', __('views.admin.users.index.table_header_3'),['page' => $users->currentPage()])</th>
                <th>@sortablelink('confirmed', __('views.admin.users.index.table_header_4'),['page' => $users->currentPage()])</th>
                <th>@sortablelink('created_at', __('views.admin.users.index.table_header_5'),['page' => $users->currentPage()])</th>
                <th>@sortablelink('updated_at', __('views.admin.users.index.table_header_6'),['page' => $users->currentPage()])</th>
                <th>Actions</th>
            </tr>
            </thead>
            <tbody>
            @foreach($users as $user)
                <tr>
                    <td>{{ $user->email }}</td>
                    <td>{{ $user->name }}</td>
                    <td>{{ $user->roles->pluck('name')->implode(',') }}</td>
                    <td>
                        @if($user->active)
                            <span class="label label-primary">{{ __('views.admin.users.index.active') }}</span>
                        @else
                            <span class="label label-danger">{{ __('views.admin.users.index.inactive') }}</span>
                        @endif
                    </td>
                    <td>
                        @if($user->confirmed)
                            <span class="label label-success">{{ __('views.admin.users.index.confirmed') }}</span>
                        @else
                            <span class="label label-warning">{{ __('views.admin.users.index.not_confirmed') }}</span>
                        @endif</td>
                    <td>{{ $user->created_at }}</td>
                    <td>{{ $user->updated_at }}</td>
                    <td>
                        <a class="btn btn-xs btn-primary" href="{{ route('admin.users.show', [$user->id]) }}" data-toggle="tooltip" data-placement="top" data-title="{{ __('views.admin.users.index.show') }}">
                            <i class="fa fa-eye"></i>
                        </a>
                        @if(!auth()->user()->hasRole('community_manager'))
                        <a class="btn btn-xs btn-info" href="{{ route('admin.users.edit', [$user->id]) }}" data-toggle="tooltip" data-placement="top" data-title="{{ __('views.admin.users.index.edit') }}">
                            <i class="fa fa-pencil"></i>
                        </a>
                        <a class="btn btn-xs btn-danger" data-user_id="{{ $user->id }}" name="btnUserDelete"  data-user_name="{{ $user->name }}" type="button" data-placement="top" data-title="{{ __('views.admin.users.index.edit') }}">
                            <i class="fa fa-trash"></i>
                        </a>
                        @endif
                        {{--@if(!$user->hasRole('administrator'))--}}
                            {{--<button class="btn btn-xs btn-danger user_destroy"--}}
                                    {{--data-url="{{ route('admin.users.destroy', [$user->id]) }}" data-toggle="tooltip" data-placement="top" data-title="{{ __('views.admin.users.index.delete') }}">--}}
                                {{--<i class="fa fa-trash"></i>--}}
                            {{--</button>--}}
                        {{--@endif--}}
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
        <div class="pull-right">
            {{ $users->links() }}
        </div>
    </div>

    <div class="modal fade" id="confirmUserDelete" tabindex="-1" role="dialog" aria-labelledby="favoritesModalLabel">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" id="favoritesModalLabel">{{ __('views.admin.companies.delete.modal.title') }}</h4>
                </div>
                <div class="modal-body">
                    <p>{{ __('views.admin.companies.delete.modal.message') }} <span id="pName"></span>?</p>
                </div>
                <div class="modal-footer">
                    {{ Form::open(['method' => 'delete', 'id'=>'formConfirmDelete','class'=>'form-horizontal form-label-left']) }}
                    <button type="button" class="btn btn-default" data-dismiss="modal">{{ __('views.admin.companies.delete.modal.cancel') }}</button>
                    <span class="pull-right">
                        <button type="submit" class="btn btn-danger" id="confirmDeleteBtnUser" >
                        {{ __('views.admin.companies.delete.modal.accept') }}
                      </button>
                    </span>
                    {{ Form::close() }}
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    @parent
    {{ Html::script(mix('assets/admin/js/users/delete.js')) }}
@endsection