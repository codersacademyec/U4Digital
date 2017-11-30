
@extends('admin.layouts.admin')

@section('title', 'Diary')

@section('content')

    <div class="row">
        <div class="col col-lg-12 col-md-12 col-xs-12 col-sm-12">
            <form>
                <div class="form-group">
                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="companies">
                        Companies
                    </label>
                    <div class="col-md-9 col-sm-9 col-xs-12">
                        <select id="companies" name="companies[]" class="select2 form-control input-group" style="width: 100%" autocomplete="off">
                            <option></option>
                            @foreach($companies as $company)
                                <option value="{{ $company->id }}">{{ $company->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </form>
        </div>
    </div>
    <hr/>
    <div class="row">
        <div class="col col-lg-12 col-md-12 col-xs-12 col-sm-12">
            <div id='calendarDiary'></div>
        </div>
    </div>

    <div class="modal fade" id="eventModal" data-enable_update="@if(auth()->user()->hasRole('community_manager') || auth()->user()->hasRole('system_admin')) true @else false @endif" tabindex="-1" role="dialog" aria-labelledby="eventModalLabel">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" id="eventModalLabel">Add Post </h4>
                </div>
                <div class="modal-body">
                    <form>
                        <div class="alert hidden" role="alert" id="messageAlert"></div>
                        <div class="alert alert-danger hidden" role="alert" id="messageUpdate"></div>
                        <div class="form-group">
                            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="ruc" >
                                Title
                                <span class="required">*</span>
                            </label>
                            <div class="col-md-9 col-sm-9 col-xs-12">
                                <input type='text' id="eventTitle" class="form-control input-group" />
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="ruc" >
                                Start
                                <span class="required">*</span>
                            </label>
                            <div class="col-md-9 col-sm-9 col-xs-12">
                                <div class='input-group date' id='eventDateFrom'>
                                    <input type='text' class="form-control" />
                                    <span class="input-group-addon">
                                        <span class="glyphicon glyphicon-calendar"></span>
                                    </span>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="ruc" >
                                End
                                <span class="required">*</span>
                            </label>
                            <div class="col-md-9 col-sm-9 col-xs-12">
                                <div class='input-group date' id='eventDateTo'>
                                    <input type='text' class="form-control" />
                                    <span class="input-group-addon">
                                        <span class="glyphicon glyphicon-calendar"></span>
                                    </span>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="ruc" >
                                Type Post
                                <span class="required">*</span>
                            </label>
                            <div class="col-md-9 col-sm-9 col-xs-12 ">
                                <select class="input-group" style="width: 100%;" id="eventTypePost">
                                    <option></option>
                                    @foreach($postTypes as $postType)
                                        <option value="{{ $postType->id }}">{{ $postType->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <br/>
                        <div class="form-group hidden" id="row_Image">
                            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="imageFile" >
                                Type Post
                                <span class="required">*</span>
                            </label>
                            <div class="col-md-9 col-sm-9 col-xs-12">
                                <label class="custom-input-file" id="lblDropZone">
                                    <input type="file" accept="image/*" id="fileImage" /> <span>Select Image</span>
                                </label>
                            </div>
                            <div class="col-md-12 col-sm-12 col-xs-12 text-center">
                                <img src="" id="idImagePreview" class="preview" />
                            </div>
                        </div>
                        <div class="form-group hidden" id="row_Video">
                            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="videoFile" >
                                Type Post
                                <span class="required">*</span>
                            </label>
                            <div class="col-md-9 col-sm-9 col-xs-12">
                                <label class="custom-input-file">
                                    <input type="file" accept="video/*" id="fileVideo" /> Select Video
                                </label>
                            </div>
                            <div class="col-md-12 col-sm-12 col-xs-12 text-center">
                                <video id="idVideoPreview" class="preview" src="" controls></video>
                            </div>
                        </div>
                        <div class="form-group hidden" id="row_Text">
                            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="ruc" >
                                Text
                                <span class="required">*</span>
                            </label>
                            <div class="col-md-9 col-sm-9 col-xs-12">
                                <textarea class="form-control input-group" id="eventDescription" rows="7"></textarea>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-dark" data-dismiss="modal">Close</button>
                    @if(auth()->user()->hasRole('community_manager') || auth()->user()->hasRole('system_admin'))
                        <button type="button" class="btn btn-primary" id="btnAddEvent" >Add</button>
                        <button type="button" class="btn btn-primary hidden" id="btnUpdateEvent" >Update</button>
                        <button type="button" class="btn btn-danger hidden" data-dismiss="modal" id="btnDeleteEvent" >Delete</button>
                    @elseif(auth()->user()->hasRole('company_admin') || auth()->user()->hasRole('system_admin'))
                        <button type="button" class="btn btn-success pull-left" data-dismiss="modal" id="btnApprovePost"><span class="fa fa-check"></span> Approve</button>
                        <button type="button" class="btn btn-warning pull-left" data-dismiss="modal" id="btnUnApprovePost"><span class="fa fa-close"></span> Un Approve</button>
                    @endif
                </div>
            </div>
        </div>
    </div>

@stop
@section('styles')
    @parent
    {{ Html::style(mix('assets/diary/diary.css')) }}
@endsection

@section('scripts')
    @parent
    {{ Html::script(mix('assets/diary/diary.js')) }}
@endsection