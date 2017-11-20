@extends('admin.layouts.admin')

@section('title',__('views.admin.users.create.title') )

@section('content')
    <div class="row">
        <div class="col-md-12 col-sm-12 col-xs-12">
            {{ Form::open(['route'=>['admin.users.store'],'method' => 'post','class'=>'form-horizontal form-label-left']) }}

                <div class="form-group">
                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="name" >
                        {{ __('views.admin.users.create.name') }}
                        <span class="required">*</span>
                    </label>
                    <div class="col-md-6 col-sm-6 col-xs-12">
                        <input id="name" type="text" class="form-control col-md-7 col-xs-12 @if($errors->has('name')) parsley-error @endif"
                               name="name" required>
                        @if($errors->has('name'))
                            <ul class="parsley-errors-list filled">
                                @foreach($errors->get('name') as $error)
                                        <li class="parsley-required">{{ $error }}</li>
                                @endforeach
                            </ul>
                        @endif
                    </div>
                </div>

                <div class="form-group">
                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="last_name" >
                        {{ __('views.admin.users.create.last_name') }}
                        <span class="required">*</span>
                    </label>
                    <div class="col-md-6 col-sm-6 col-xs-12">
                        <input id="last_name" type="text" class="form-control col-md-7 col-xs-12 @if($errors->has('last_name')) parsley-error @endif"
                               name="last_name" required>
                        @if($errors->has('last_name'))
                            <ul class="parsley-errors-list filled">
                                @foreach($errors->get('last_name') as $error)
                                    <li class="parsley-required">{{ $error }}</li>
                                @endforeach
                            </ul>
                        @endif
                    </div>
                </div>

                <div class="form-group">
                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="email">
                        {{ __('views.admin.users.create.email') }}
                        <span class="required">*</span>
                    </label>
                    <div class="col-md-6 col-sm-6 col-xs-12">
                        <input id="email" type="email" class="form-control col-md-7 col-xs-12 @if($errors->has('email')) parsley-error @endif"
                               name="email" required>
                        @if($errors->has('email'))
                            <ul class="parsley-errors-list filled">
                                @foreach($errors->get('email') as $error)
                                    <li class="parsley-required">{{ $error }}</li>
                                @endforeach
                            </ul>
                        @endif
                    </div>
                </div>

                <div class="form-group">
                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="password">
                        {{ __('views.admin.users.create.password') }}
                        <span class="required">*</span>
                    </label>
                    <div class="col-md-6 col-sm-6 col-xs-12">
                        <input id="password" type="password" class="form-control col-md-7 col-xs-12 @if($errors->has('password')) parsley-error @endif"
                               name="password" required>
                        @if($errors->has('password'))
                            <ul class="parsley-errors-list filled">
                                @foreach($errors->get('password') as $error)
                                    <li class="parsley-required">{{ $error }}</li>
                                @endforeach
                            </ul>
                        @endif
                    </div>
                </div>

                <div class="form-group">
                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="password_confirmation">
                        {{ __('views.admin.users.create.confirm_password') }}
                        <span class="required">*</span>
                    </label>
                    <div class="col-md-6 col-sm-6 col-xs-12">
                        <input id="password_confirmation" type="password" class="form-control col-md-7 col-xs-12 @if($errors->has('password_confirmation')) parsley-error @endif"
                               name="password_confirmation" required>
                        @if($errors->has('password_confirmation'))
                            <ul class="parsley-errors-list filled">
                                @foreach($errors->get('password_confirmation') as $error)
                                    <li class="parsley-required">{{ $error }}</li>
                                @endforeach
                            </ul>
                        @endif
                    </div>
                </div>

                <div class="form-group">
                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="roles">
                        {{ __('views.admin.users.create.roles') }}
                    </label>
                    <div class="col-md-6 col-sm-6 col-xs-12">
                        <select id="roles" name="roles[]" class="select2" style="width: 100%" placeholder="Choose Role" autocomplete="off">
                            <option value="0">Choose Role</option>
                            @foreach($roles as $role)
                                @if(auth()->user()->hasRole('system_admin') && $role->name != 'company_user')
                                    <option value="{{ $role->id }}">{{ $role->name }}</option>
                                @elseif(auth()->user()->hasRole('company_admin') && $role->name == 'company_user')
                                    <option value="{{ $role->id }}" selected>{{ $role->name }}</option>
                                @endif
                            @endforeach
                        </select>
                        @if($errors->has('roles.0'))
                            <ul class="parsley-errors-list filled">
                                @foreach($errors->get('roles.0') as $error)
                                    <li class="parsley-required">{{ $error }}</li>
                                @endforeach
                            </ul>
                        @endif
                    </div>
                </div>

                <!--<div class="form-group">
                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="belong_company">
                        {{ __('views.admin.users.create.belong_company') }}
                    </label>
                    <div class="col-md-6 col-sm-6 col-xs-12">
                        <div class="">
                            <label>
                                <input type="checkbox" name="belong_company" id="belong_company" class="belong-switch" {{ !$errors->has('companies.0')? '':'checked' }} />
                            </label>
                        </div>
                    </div>
                </div>-->

                <div class="form-group {{!$errors->has('companies.0')? 'hidden':''}}" id="divCompanies">
                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="companies">
                        {{ __('views.admin.users.create.companies') }}
                    </label>
                    <div class="col-md-6 col-sm-6 col-xs-12">
                        <select class="form-control" id="companies" name="companies[]" >
                            @foreach($companies as $company)
                                <option value="{{ $company->id }}">{{ $company->name }}</option>
                            @endforeach
                        </select>
                        @if($errors->has('companies.0'))
                            <ul class="parsley-errors-list filled">
                                @foreach($errors->get('companies.0') as $error)
                                    <li class="parsley-required">{{ $error }}</li>
                                @endforeach
                            </ul>
                        @endif
                    </div>
                </div>



                <div class="form-group">
                    <div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-3">
                        <a class="btn btn-primary" href="{{ URL::previous() }}"> {{ __('views.admin.users.create.cancel') }}</a>
                        <button type="submit" class="btn btn-success"> {{ __('views.admin.users.create.save') }}</button>
                    </div>
                </div>
            {{ Form::close() }}
        </div>
    </div>
@endsection

@section('styles')
    @parent
    {{ Html::style(mix('assets/admin/css/users/create.css')) }}
@endsection

@section('scripts')
    @parent
    {{ Html::script(mix('assets/admin/js/users/create.js')) }}
@endsection