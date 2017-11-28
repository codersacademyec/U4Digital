(function($) {
    'use strict';

    var calendar_id = 'calendarDiary';
    var event_modal_id = 'eventModal';
    var event_date_from_id = 'eventDateFrom';
    var event_date_to_id = 'eventDateTo';
    var event_description_id = 'eventDescription';
    var event_type_post_id = 'eventTypePost';

    var modal_btn_add_event = 'btnAddEvent';
    var modal_btn_update_event = 'btnUpdateEvent';
    var modal_btn_delete_event = 'btnDeleteEvent';
    var modal_label_title = 'eventModalLabel';

    var event_details = null;

    function loadComponents() {
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

                /*alert('Event: ' + calEvent.title);
                alert('Coordinates: ' + jsEvent.pageX + ',' + jsEvent.pageY);
                alert('View: ' + view.name);

                // change the border color just for fun
                $(this).css('border-color', 'red');*/

            },
            /*eventRender: function(event, element) {
                return $('<div>' + event.description + '</div>');
            },*/
            dayClick: function(date, jsEvent, view) {
            }
        });
    }
    
    function loadModal() {
        $('#'+event_date_from_id).datetimepicker({
            minDate: new moment(),
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

        $('#'+modal_btn_add_event).click(function() {
            var event = loadEvent();
            addNewEvent(event);
        });

        $('#'+modal_btn_update_event).click(function() {
            var event = loadEvent();
            updateEvent(event, event_details);
        });

        $('#'+modal_btn_delete_event).click(function () {
            deleteEvent(event_details);
        });

        $('#'+event_modal_id).on('hide.bs.modal', function () {
            clearModal();
        });

        var enableComponents = $('#'+event_modal_id).attr('data-enable_update') == ' true ';
        enableModalComponents(enableComponents);
    }

    function loadMainComponents() {
        $('#companies').select2({
            placeholder: "Select a Company",
            allowClear: true
        });

        $('#companies').on('select2:select', function (e) {
            console.log('select event');
            console.log(e);
        });
    }

    function showModal() {
        if(event_details != null) {
            $('#'+event_date_from_id).data("DateTimePicker").date(event_details.start);
            $('#'+event_date_to_id).data("DateTimePicker").date(event_details.end);
            $('#'+event_type_post_id).val(event_details.title);
            $('#'+event_description_id).val(event_details.description);
            $('#'+modal_label_title).html('Update Post');
            $('#'+modal_btn_add_event).addClass('hidden');
            $('#'+modal_btn_update_event).removeClass('hidden');
            $('#'+modal_btn_delete_event).removeClass('hidden');
        }
        else {
            $('#'+modal_label_title).html('Add Post');
            $('#'+modal_btn_add_event).removeClass('hidden');
            $('#'+modal_btn_update_event).addClass('hidden');
            $('#'+modal_btn_delete_event).addClass('hidden');
        }
        $('#'+event_modal_id).modal('show');
    }

    function clearModal() {
        $('#'+event_date_from_id).data("DateTimePicker").clear();
        $('#'+event_date_to_id).data("DateTimePicker").clear();
        $('#'+event_type_post_id).val('');
        $('#'+event_description_id).val('');
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
            //$('#'+event_type_post_id).disable();
            //$('#'+event_description_id).disable();
        }
        $('#'+event_type_post_id).prop('disabled', !enable);
        $('#'+event_description_id).prop('disabled', !enable);
    }

    function loadEvent() {
        var dateFrom = $('#'+event_date_from_id).data("DateTimePicker").date();
        var dateTo = $('#'+event_date_to_id).data("DateTimePicker").date();
        var typePost = $('#'+event_type_post_id).val();
        var description = $('#'+event_description_id).val();

        var event = {
            title : typePost,
            start : dateFrom,
            end : dateTo,
            description: description
        }
        return event;
    }

    function addNewEvent(event) {
        $('#'+calendar_id).fullCalendar('addEventSource',[event]);
    }

    function updateEvent(newEvent, originEvent) {
        originEvent.title = newEvent.title;
        originEvent.start = newEvent.start;
        originEvent.end = newEvent.end;
        originEvent.description = newEvent.description;
        $('#'+calendar_id).fullCalendar('updateEvent', originEvent);
    }

    function deleteEvent(event) {
        $('#'+calendar_id).fullCalendar('removeEvents', event._id);
    }

    $(document).ready(function() {
        console.log('hola Diary');
        loadComponents();
    });
})(jQuery);