(function($) {
    'use strict';

    var calendar_id = 'calendarDiary';
    var event_modal_id = 'eventModal';
    var event_modal_message = 'messageAlert';

    var event_title_id = 'eventTitle';
    var event_date_from_id = 'eventDateFrom';
    var event_date_to_id = 'eventDateTo';
    var event_description_id = 'eventDescription';
    var event_type_post_id = 'eventTypePost';

    var event_row_type_image = 'row_Image';
    var event_row_type_video = 'row_Video';
    var event_row_type_text = 'row_Text';

    var modal_btn_add_event = 'btnAddEvent';
    var modal_btn_update_event = 'btnUpdateEvent';
    var modal_btn_delete_event = 'btnDeleteEvent';
    var modal_label_title = 'eventModalLabel';

    var modal_file_upload_image = 'fileImage';
    var modal_file_upload_video = 'fileVideo';

    var company_select_id = 'companies';

    var event_details = null;

    var mediaFile = null;

    var apiServer = '';

    function loadComponents() {

        apiServer = window.location.protocol+'//'+window.location.host + '/api/';

        loadCalendar();
        loadModal();
        loadMainComponents();
    }

    function loadCalendar() {
        $('#'+calendar_id).fullCalendar({
            customButtons: {
                addPost: {
                    text: 'Add Post',
                    click: function() {
                        showModal();
                    },
                    bootstrapGlyphicon: 'glyphicon glyphicon-plus'
                }
            },
            header: {
                left: 'prev,next today addPost',
                center: 'title',
                right: 'month,agendaWeek,agendaDay'
            },
            eventClick: function(calEvent, jsEvent, view) {

                event_details = calEvent;
                showModal();

            },
            /*eventRender: function(event, element) {
                return $('<div>' + event.description + '</div>');
            },*/
            /*dayClick: function(date, jsEvent, view) {
            }*/
        });
    }

    function loadModalBtnActions() {
        $('#'+modal_btn_add_event).click(function() {
            var event = loadEvent();
            saveEvent(event);
        });

        $('#'+modal_btn_update_event).click(function() {
            var event = loadEvent();
            editEvent(event);
            //updateEvent(event, event_details);
        });

        $('#'+modal_btn_delete_event).click(function () {
            deleteEvent(event_details);
        });
    }

    function loadModalComponentsActions() {
        $('#'+event_date_from_id).datetimepicker({
            showClear: true,
            showClose: true
        }).on("dp.change", function (e) {
            $('#'+event_date_to_id).data("DateTimePicker").minDate(e.date).enable();
        });

        $('#'+event_date_to_id).datetimepicker({
            useCurrent: false,
            showClear: true,
            showClose: true
        }).on("dp.change", function (e) {
            $('#'+event_date_from_id).data("DateTimePicker").maxDate(e.date);
        }).data("DateTimePicker").disable();

        $('#'+event_type_post_id).select2({
            placeholder: "Select Type Post",
            allowClear: true
        }).on('select2:select', function (e) {
            console.log(e.params.data.text);
            showFileComponents(e.params.data.text);
        });
    }

    function showFileComponents(componentName) {
        console.log(componentName);
        if(componentName == 'Video') {
            $('#'+event_row_type_image).addClass('hidden');
            $('#'+event_row_type_text).addClass('hidden');
        }
        else if(componentName == 'Image') {
            $('#'+event_row_type_video).addClass('hidden');
            $('#'+event_row_type_text).addClass('hidden');
        }
        else {
            $('#'+event_row_type_image).addClass('hidden');
            $('#'+event_row_type_video).addClass('hidden');
        }
        $('#row_'+componentName).removeClass('hidden');
    }

    function setObjectPreview(fileObj, idElement) {
        var reader = new FileReader();
        reader.onload = function () {
            document.getElementById(idElement).src = reader.result;
        };
        reader.readAsDataURL(fileObj);
    }

    function setObjectUrlPreview(idElement, urlMedia) {
        //console.log(apiServer+'diary/picture/'+urlMedia);
        document.getElementById(idElement).src = apiServer+'diary/picture/'+urlMedia;
    }
    
    function loadModal() {

        loadModalComponentsActions();
        loadModalBtnActions();


        $('#'+event_modal_id).on('hide.bs.modal', function () {
            clearModal();
        });

        $('#'+event_modal_id).on('show.bs.modal', function () {

        });

        $('#'+modal_file_upload_image).change(function () {
            var fileInput = $(this).prop('files');
            console.log(fileInput[0]);
            mediaFile = fileInput[0];
            setObjectPreview(fileInput[0], 'idImagePreview');
        });

        $('#'+modal_file_upload_video).change(function () {
            var fileInput = $(this).prop('files');
            console.log(fileInput[0]);
            mediaFile = fileInput[0];
            setObjectPreview(fileInput[0], 'idVideoPreview');
        });

        var enableComponents = $('#'+event_modal_id).attr('data-enable_update') == ' true ';
        enableModalComponents(enableComponents);
    }

    function loadMainComponents() {
        $('#'+company_select_id).select2({
            placeholder: "Select a Company",
            allowClear: true
        });

        $('#'+company_select_id).on('select2:select', function (e) {
            console.log('select event');
            console.log(e);
            getEventsByCompany();
        });
    }

    function showModal() {
        if(event_details != null) {
            console.log(event_details);
            $('#'+event_title_id).val(event_details.title);
            $('#'+event_date_from_id).data("DateTimePicker").date(event_details.start);
            $('#'+event_date_to_id).data("DateTimePicker").date(event_details.end);
            $('#'+event_type_post_id).val(event_details.typePost).trigger('change');
            $('#'+modal_label_title).html('Update Post');
            $('#'+modal_btn_add_event).addClass('hidden');
            $('#'+modal_btn_update_event).removeClass('hidden');
            $('#'+modal_btn_delete_event).removeClass('hidden');
            //$('#'+event_modal_message).removeClass('hidden');
            showFileComponents(event_details.typePostName);

            if(event_details.typePost == 1) {
                $('#'+event_description_id).val(event_details.text_post);
            }
            else if(event_details.typePost == 2) {
                setObjectUrlPreview('idImagePreview', event_details.path_media);
            }
            else if(event_details.typePost == 3) {
                setObjectUrlPreview('idVideoPreview', event_details.path_media);
            }
        }
        else {
            $('#'+modal_label_title).html('Add Post');
            $('#'+modal_btn_add_event).removeClass('hidden');
            $('#'+modal_btn_update_event).addClass('hidden');
            $('#'+modal_btn_delete_event).addClass('hidden');
            $('#'+event_modal_message).addClass('hidden');
            $('#'+event_type_post_id).val('');
        }
        $('#'+event_modal_id).modal('show');
    }

    function closeModal() {
        $('#'+event_modal_id).modal('hide');
    }

    function clearModal() {
        $('#'+event_date_from_id).data("DateTimePicker").clear();
        $('#'+event_date_to_id).data("DateTimePicker").clear();
        $('#'+event_type_post_id).val('');
        $('#'+event_type_post_id).trigger('change');
        $('#'+event_description_id).val('');
        $('#'+event_title_id).val('');
        $('#'+event_row_type_image).addClass('hidden');
        $('#'+event_row_type_video).addClass('hidden');
        $('#'+event_row_type_text).addClass('hidden');
        event_details = null;
    }

    function enableModalComponents(enable) {
        if(enable) {
            $('#'+event_date_from_id).data("DateTimePicker").enable();
            $('#'+event_date_to_id).data("DateTimePicker").enable();
        }
        else {
            $('#'+event_date_from_id).data("DateTimePicker").disable();
            $('#'+event_date_to_id).data("DateTimePicker").disable();
        }
        $('#'+event_type_post_id).prop('disabled', !enable);
        $('#'+event_description_id).prop('disabled', !enable);
        $('#'+event_title_id).prop('disabled', !enable);
    }

    function loadEvent() {
        var company_id = $('#'+company_select_id).val();
        var title = $('#'+event_title_id).val();
        var dateFrom = $('#'+event_date_from_id).data("DateTimePicker").date();
        var dateTo = $('#'+event_date_to_id).data("DateTimePicker").date();
        var typePost = $('#'+event_type_post_id).val();

        var event = {
            company_id : company_id,
            title : title,
            typePost: typePost
        };

        if(dateFrom != null) {
            event.start = dateFrom.format('YYYY-MM-DD HH:mm');
        }

        if(dateTo != null) {
            event.end = dateTo.format('YYYY-MM-DD HH:mm');
        }

        if(typePost == 1) {
            event.text = $('#'+event_description_id).val();
        }
        else {
            event.file = mediaFile;
        }
        return event;
    }

    function addNewEvent(event) {
        $('#'+calendar_id).fullCalendar('addEventSource',[event]);
    }

    function updateEvent(newEvent, originEvent) {
        console.log(newEvent);
        console.log(originEvent);
        originEvent.title = newEvent.title;
        originEvent.start = newEvent.start;
        originEvent.end = newEvent.end;
        originEvent.typePost = newEvent.typePost;
        //$('#'+calendar_id).fullCalendar('updateEvent', originEvent);
    }

    function deleteEvent(event) {
        $('#'+calendar_id).fullCalendar('removeEvents', event._id);
    }

    function deleteEvents() {
        $('#'+calendar_id).fullCalendar('removeEvents');
    }

    function showErrorMessage(message) {
        $('#messageUpdate').removeClass('hidden').html(message);
    }

    function showApprovedStatus(approvedDate) {
        $('#'+event_modal_message).removeClass('hidden');

        if(approvedDate == null) {

        }
    }

    // Call Api Server Methods

    function getEventsByCompany() {
        var company_id = $('#'+company_select_id).val();

        $.get(
            apiServer+'diary/'+company_id,
            function(data) {
                deleteEvents();
                setEventsByCompanyToCalendar(data);
            },
            'json')
            .fail(
                function(e) {
                });
    }

    function setEventsByCompanyToCalendar(data) {
        for(var i=0;i<data.length;i++) {
            var event = {
                calendar_event_id: data[i].id,
                title : data[i].title,
                start : data[i].start,
                end : data[i].end,
                path_media : data[i].path_media,
                text_post : data[i].text_post,
                typePost: data[i].type_post.id,
                typePostName: data[i].type_post.name,
                approved : data[i].approved != null
            };
            addNewEvent(event);
        }
    }

    function saveEvent(event) {
        var formData = new FormData();
        formData.append('company_id', event.company_id);
        formData.append('title', event.title);
        formData.append('start', event.start);
        formData.append('end', event.end);
        formData.append('typePost', event.typePost);
        if(event.text != undefined) {
            formData.append('text', event.text);
        }
        else {
            formData.append('file', event.file);
        }

        $.ajax(
            {
                url: apiServer + 'diary',
                type: 'POST',
                data: formData,
                cache: false,
                contentType: false,
                processData: false
            },
            'json')
            .done(function (data) {
                addNewEvent(event);
                closeModal();
            })
            .fail(function (data) {
                showErrorMessage(data.responseText);
            });
    }

    function editEvent(event) {
        var formData = new FormData();
        formData.append('company_id', event.company_id);
        formData.append('title', event.title);
        formData.append('start', event.start);
        formData.append('end', event.end);
        formData.append('typePost', event.typePost);
        if(event.text != undefined) {
            formData.append('text', event.text);
        }
        else {
            formData.append('file', event.file);
        }

        $.ajax(
            {
                url: apiServer + 'diary/'+event_details.calendar_event_id,
                type: 'POST',
                data: formData,
                cache: false,
                contentType: false,
                processData: false
            },
            'json')
            .done(function (data) {
                //addNewEvent(event);
                updateEvent(event,event_details);
                closeModal();
            })
            .fail(function (data) {
                showErrorMessage(data.responseText);
            });
    }

    $(document).ready(function() {
        console.log('hola Diary');
        loadComponents();
    });
})(jQuery);

