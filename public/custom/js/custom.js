$.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
});

$(document).ready(function () {

    loadConfirm();
    comman_fuction();
    if ($('.js-single-select').length > 0) {
        $('.js-single-select').select2();
    }
    if ($('.js-multiple-select').length > 0) {
        $('.js-multiple-select').select2();
    }

    if ($('.input[name="daterange"]').length > 0) {
        $('input[name="daterange"]').daterangepicker();
    }

    $(document).on('input', '.autogrow', function () {
        $(this).height("auto").height($(this)[0].scrollHeight - 24);
    });

    $(document).on('click', '.employee_menu_listt>a', function () {
        var id_name = $(this).attr('id');
        $(".employee_menu_list .employee_menu").removeClass('active');
        $(".employee_menu_list .employee_menu." + id_name).addClass('active');
    })

    $(document).on('click', '.list-group-item', function () {
        var href = $(this).attr('data-href');
        $('.tabs-card').addClass('d-none');
        $(href).removeClass('d-none');
        $('#tabs .list-group-item').removeClass('text-primary');
        $(this).addClass('text-primary');
    });

    $(document).on('click', '.create_new_group', function () {
        $('.select_group').hide();
        $('.crete_group').show();
        $(this).parent().parent().find('input[name="group_type"]').attr('value', 'create');
    });

    $(document).on('click', '.select_group_btn', function () {
        $('.crete_group').hide();
        $('.select_group').show();
        $(this).parent().parent().find('input[name="group_type"]').attr('value', 'select');
    });

    $(document).on('click', '.new_category', function () {
        $('.setting_select_category_div').hide();
        $('.setting_new_category_div').show();
    });

    $(document).on('click', '.select_category', function () {
        $('.setting_new_category_div').hide();
        $('.setting_select_category_div').show();
    });

    $(document).on('change', '.applye_for_employee', function () {
        $('.applye_for_employee_select').toggle();
    });

    $(document).on('change', '.leave_rule', function () {
        var rule = $(this).val();
        $(this).parent().find('.set_rules').hide();
        if (rule == '1') {
            $(this).parent().find('.set_rules.num_employees').show();
        }
        if (rule == '2') {
            $(this).parent().find('.set_rules.num_employees_on_location').show();
        }
        if (rule == '3') { $(this).parent().find('.set_rules.num_employees_in_group').show(); }
        if (rule == '4') { $(this).parent().find('.set_rules.num_employees_with_role').show(); }
    });

    $(document).on('click', '.add_rule', function () {
        var htmlll = $(this).prev().find('.rules').first().html();
        $(this).parent().find('.rules_div').append('<div class="rules">' + htmlll + '</div>');
    });

    $(document).on('click', '.close_leave_rules', function () {
        var rule_length = $(this).parent().parent().parent().children().length;
        if (rule_length != 1) {
            $(this).parent().parent().remove();
        }
    });

    $(document).on('change', '.leave_date_start', function () {
        var start_date_val = $(this).val();
        var due_date_val = $(this).parent().parent().parent().find('.leave_date_due').val();
        if (due_date_val == undefined || due_date_val == '') {
            due_date_val = start_date_val;
            $(this).parent().parent().parent().find('.leave_date_due').attr('value', start_date_val);
        }
        var start_date = $(this).val();
        var end_date = $(this).parent().parent().parent().find('.leave_date_due').val();

        var start = moment(start_date, "YYYY-MM-DD");
        var end = moment(end_date, "YYYY-MM-DD");
        var total_days = moment.duration(end.diff(start)).asDays() + 1;
        $('.leave_days').attr('value', total_days);
        $('.leave_days').attr('data-value', total_days);

        var record_hours = getallday(start_date, end_date, total_days)
        $('.total_date_hour').html(record_hours + '<span class="clearfix"></span>');
    });

    function leave_day_duration() {
        alert('dsada');
        var start_date_val = $(this).val();
        var due_date_val = $(this).parent().parent().parent().find('.leave_date_due').val();
        if (due_date_val == undefined || due_date_val == '') {
            due_date_val = start_date_val;
            $(this).parent().parent().parent().find('.leave_date_due').attr('value', start_date_val);
        }
        var start_date = $(this).val();
        var end_date = $(this).parent().parent().parent().find('.leave_date_due').val();

        var start = moment(start_date, "YYYY-MM-DD");
        var end = moment(end_date, "YYYY-MM-DD");
        var total_days = moment.duration(end.diff(start)).asDays() + 1;
        $('.leave_days').attr('value', total_days);
        $('.leave_days').attr('data-value', total_days);

        var record_hours = getallday(start_date, end_date, total_days)
        $('.total_date_hour').html(record_hours + '<span class="clearfix"></span>');
     }

    $(document).on('change', '.leave_date_due', function () {
        var due_date_val = $(this).val();
        var start_date_val = $(this).parent().parent().parent().find('.leave_date_start').val();
        console.log(start_date_val);
        if (start_date_val == undefined || start_date_val == '') {
            start_date_val = due_date_val;
            $(this).parent().parent().parent().find('.leave_date_start').attr('value', due_date_val);
        }
        var start_date = $(this).parent().parent().parent().find('.leave_date_start').val();
        var end_date = $(this).val();
        var start = moment(start_date, "YYYY-MM-DD");
        var end = moment(end_date, "YYYY-MM-DD");
        var total_days = moment.duration(end.diff(start)).asDays() + 1;
        $('.leave_days').attr('value', total_days);
        $('.leave_days').attr('data-value', total_days, total_days);

        var record_hours = getallday(start_date, end_date, total_days)
        $('.total_date_hour').html(record_hours + '<span class="clearfix"></span>');
    });

    $(document).on('change', '.total_daily_hour', function () {
        var this_val = $(this).val();
        if (this_val == 'total') {
            $('.total_date_hour').hide();
            $('.total_all_hour').show();
        }
        if (this_val == 'daily') {
            $('.total_date_hour').show();
            $('.total_all_hour').hide();
        }
    });

    $(document).on('change', '.set_time_zone_route_select', function () {
        var tmmt = $(this).val();
        if (tmmt == 'auto') {
            $('.time_zone_selectbox').hide();
        }
        if (tmmt == 'manual') {
            $('.time_zone_selectbox').show();
        }
    });

    $(document).on('click', '.weak_go', function () {
        var weak_prev = $(this).hasClass('weak-prev');
        var weak_left = $(this).hasClass('weak-left');
        var week_no = $(this).parent().find('.week_add_sub').val();
        var week_startdate = $(this).parent().find('.week_last_daye').attr('data-start');
        var week_enddate = $(this).parent().find('.week_last_daye').attr('data-end');
        var total_week = $(this).parent().find('.week_add_sub').val();

        if (weak_prev) {
            week_no = parseInt(week_no) - 1;
            $(this).parent().find('.week_add_sub').attr('value', week_no);
            var next_week_startdate = moment(week_startdate);
            var next_week_startdate_b = next_week_startdate.subtract(week_no, 'week');
            next_week_startdate1 = next_week_startdate.format('D MMM YYYY');
            next_week_startdate2 = next_week_startdate.format('YYYY/MM/D');

            var next_week_enddate = moment(week_enddate);
            var next_week_enddate_b = next_week_enddate.subtract(week_no, 'week');
            next_week_enddate1 = next_week_enddate.format('D MMM YYYY');
            next_week_enddate2 = next_week_enddate.format('YYYY/MM/D');
        }
        if (weak_left) {
            week_no = parseInt(week_no) + 1;
            $(this).parent().find('.week_add_sub').attr('value', week_no);

            var next_week_startdate = moment(week_startdate);
            var next_week_startdate_b = next_week_startdate.add(week_no, 'week');
            next_week_startdate1 = next_week_startdate.format('D MMM YYYY');
            next_week_startdate2 = next_week_startdate.format('YYYY/MM/D');

            var next_week_enddate = moment(week_enddate);
            var next_week_enddate_b = next_week_enddate.add(week_no, 'week');
            next_week_enddate1 = next_week_enddate.format('D MMM YYYY');
            next_week_enddate2 = next_week_enddate.format('YYYY/MM/D');
        }
        //$(this).parent().find('.weak_go_html').html(next_week_startdate1 +' - '+ next_week_enddate1);

        var between = betweenDate(next_week_startdate2, next_week_enddate2);

        var record_hours = '<th></th>';
        var record_hours1 = '';
        $.each(between, function (key, val) {
            var date = moment(val).format('D');
            var mon = moment(val).format('MMM');
            var days = moment(val).format('ddd');

            var today = [];
            today.push(date + '/' + mon + '/' + days);

            record_hours1 += '<th><span>' + days + '</span><br><span>' + date + ' ' + mon + '</span></th>';
        });
        record_hours += record_hours1;
        $(".week_go_table").html(record_hours);
    });

    $(document).on('click', '.weak_go1', function () {
        var weak_prev = $(this).hasClass('weak-prev1');
        var weak_left = $(this).hasClass('weak-left1');
        var week_no = $(this).parent().find('.week_add_sub1').val();
        if (weak_prev) {
            week_no = parseInt(week_no) - 1;
            $(this).parent().find('.week_add_sub1').attr('value', week_no);
        }
        if (weak_left) {
            week_no = parseInt(week_no) + 1;
            $(this).parent().find('.week_add_sub1').attr('value', week_no);
        }
    });

    $(document).on('change', '#date_between', function () {
        var start_date = $(this).parent().parent().parent().find('.start_date').val();
        var end_date = $(this).parent().parent().parent().find('.end_date').val();
        if (start_date != '') {
            start_date1 = moment(start_date).format('M/DD/YYYY');
            if (end_date == '') {
                var end_date = $(this).parent().parent().parent().find('.end_date').val(start_date);
                end_date = start_date1;
            }
        }
        if (end_date != '') {
            end_date1 = moment(end_date).format('M/DD/YYYY');
            if (start_date == '') {
                var start_date = $(this).parent().parent().parent().find('.start_date').val(end_date);
                start_date = end_date1;
            }
        }
        if (end_date != '' && start_date != '') {
            var daysdifferences = daysdifference(start_date, end_date);
            var start_date = $(this).parent().parent().parent().find('.total_day').val(daysdifferences + 1);
        }

    });

    $(document).on('click', '.approve_request_button', function () {
        $(this).parent().find('.leave_approval').attr('value', 1);
    });

    $(document).on('click', '.deny_request_button', function () {
        $(this).parent().find('.leave_approval').attr('value', 2);
    });

    $(document).on('click', '.approvepayment_request_button', function () {
        $(this).parent().find('.payment_approval').attr('value', 1);
    });

    $(document).on('click', '.denypayment_request_button', function () {
        $(this).parent().find('.payment_approval').attr('value', 2);
    });

    $(document).on('change', '#imgInp', function () {
        readURL(this);
    });



    $(document).on('click', '.edit_schedule', function () {
        var datasrc = $('body').find('#edit_schedule').attr('data-src');
        $(document).find('#edit_schedule').attr('src', datasrc);
        $(document).find('#add_schedule').attr('src', '');
    });

    $(document).on('click', '.add_schedule', function () {
        var datasrc = $('body').find('#add_schedule').attr('data-src');
        $(document).find('#add_schedule').attr('src', datasrc);
        $(document).find('#edit_schedule').attr('src', '');
    });

    $(document).on('change', '.manager_manag_emp', function () {
        var emp_type = $(this).val();
        $('.manager_permission_data').hide();
        if (emp_type == 2) {
            $('.manager_permission_data').show();
        }
    });


    $(document).on('click', '.set_password', function () {
        $('.password_box input[name="share_password"]').val('');
        if ($(this).prop("checked") == true) {
            $('.password_box').show();
        }
        else if ($(this).prop("checked") == false) {
            $('.password_box').hide();
        }
    });

    $(document).on('click', '.set_expiry_date', function () {
        if ($(this).prop("checked") == true) {
            $('.expiry_date_box').show();
        }
        else if ($(this).prop("checked") == false) {
            $('.expiry_date_box').hide();
            $('.expiry_date_box input[name="expiry_date"]').val('');
        }
    });



    $(document).on('click', 'a[data-ajax-popup="true"], div[data-ajax-popup="true"], td[data-ajax-popup="true"], button[data-ajax-popup="true"]', function (e) {
        e.preventDefault();
        var data = {};
        var title = $(this).data('title');
        var size = (($(this).data('size') == '') && (typeof $(this).data('size') === "undefined")) ? 'md' : $(this).data('size');
        var url = $(this).attr('data-url');
        var align = $(this).data('align');
        var rotas_location = $('.rotas_location_change').val();
        var data_availability = $(this).parent().parent().attr('data-availability-json');
        $("#commonModal .modal-title").html(title);
        $("#commonModal .modal-dialog").addClass('modal-' + size + ' modal-dialog-' + align);

        // console.log($('.share_rotas_cls').length);

        $.ajax({
            url: url,
            data: data,
            cache: false,
            success: function (data) {

                $('#commonModal .modal-body').html(data);

                if($('.js-single-select').length > 0) {
                    $('.js-single-select').select2();
                }
                if($('.js-multiple-select').length > 0) {
                    $('.js-multiple-select').select2();
                }
                $('#rotas_ctrate_location').attr('value', rotas_location);

                $('.autogrow').height("auto").height($(this)[0].scrollHeight - 24);
                $("#rule_select").trigger("change");
                $("#date_between").trigger("change");
                $(".total_daily_hour").trigger("change");
                $(".manager_manag_emp").trigger("change");

                availabilitytablejs();
                ddatetime_range();
                if (data_availability != undefined) {
                    var data = JSON.parse(data_availability);
                    editavailabilitytablejs(data);
                }

                //loadConfirm();
                comman_fuction();

                $('#commonModal').modal('toggle');
                $('#commonModal').modal({ keyboard: false });
            },
            error: function (data) {
                data = data.responseJSON;
                show_toastr('Error', data.error, 'error')
            }
        });
    });
});

function datatable_call() {
    if ($('.pc-dt-simple').length > 0) {
        const dataTable = new simpleDatatables.DataTable(".pc-dt-simple");
    }
}

function comman_fuction() {

    /* chosen js => select */
    if ($(".multi-select").length > 0) {
        $($(".multi-select")).each(function (index, element) {
            var id = $(element).attr('id');
            var multipleCancelButton = new Choices(
                '#' + id, {
                removeItemButton: true,
            }
            );
        });
    }

    /* Tooltip */
    // if ($('[data-bs-toggle="tooltip"]').length > 0) {
    //     var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
    //     var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
    //     return new bootstrap.Tooltip(tooltipTriggerEl)
    //     })
    // }

    /* Date Piker */
    if ($('.pc-datepicker').length > 0) {
        $($(".pc-datepicker")).each(function (index, element) {
            var id = $(element).attr('id');
            (function () {
                const d_week = new Datepicker(document.querySelector('#'+id), {
                    buttonClass: 'btn'
                });
            })();
        });
    }

    /* Time Piker */
    if ($('.pc-timepicker-1-modal').length > 0) {
        $($(".pc-timepicker-1-modal")).each(function (index, element) {
            var id = '#' + $(element).attr('id');
            document.querySelector(id).flatpickr({
                enableTime: true,
                noCalendar: true,
                minuteIncrement:1,
            });
        });
    }

    /* Date Range Piker */
    if ($('.pc-daterangepicker-1').length > 0) {
        $($(".pc-daterangepicker-1")).each(function (index, element) {
            var id = '#' + $(element).attr('id');
            document.querySelector(id).flatpickr({
                mode: "range"
            });
        });
    }
}

function readURL(input) {
    if (input.files && input.files[0]) {
        var reader = new FileReader();

        reader.onload = function (e) {
            console.log(e.target.result);
            $('#blah').attr('src', e.target.result);
        }
        reader.readAsDataURL(input.files[0]);
    }
}

function getallday(firstDate, secondDate, total_days = '0') {
    var start = moment(firstDate, "YYYY/MM/DD");
    var end = moment(secondDate, "YYYY/MM/DD");
    var between = betweenDate(start, end);
    var record_hours = '';
    $.each(between, function (key, val) {
        var dd = val.getDate();
        var mm = val.getMonth() + 1;

        var yyyy = val.getFullYear();
        if (dd < 10) {
            dd = '0' + dd;
        }
        if (mm < 10) {
            mm = '0' + mm;
        }
        var today = dd + '/' + mm + '/' + yyyy;
        record_hours += '<div class="form-group w-20 float-left">\n' +
            '                                <label class="form-control-label"> Hours for ' + today + ' </label>\n' +
            '                                <input type="number" name="leave_time2[' + today + ']" class="form-control w-90">\n' +
            '                            </div>';
    });
    return record_hours;
}

function daysdifference(firstDate, secondDate) {
    var startDay = new Date(firstDate);
    var endDay = new Date(secondDate);
    var millisBetween = startDay.getTime() - endDay.getTime();
    var days = millisBetween / (1000 * 3600 * 24);
    return Math.round(Math.abs(days));
}

function isDate(dateArg) {
    var t = (dateArg instanceof Date) ? dateArg : (new Date(dateArg));
    return !isNaN(t.valueOf());
}

function isValidRange(minDate, maxDate) {
    return (new Date(minDate) <= new Date(maxDate));
}

function betweenDate(startDt, endDt) {
    var error = ((isDate(endDt)) && (isDate(startDt)) && isValidRange(startDt, endDt)) ? false : true;
    var between = [];
    if (error) console.log('error occured!!!... Please Enter Valid Dates');
    else {
        var currentDate = new Date(startDt),
            end = new Date(endDt);
        while (currentDate <= end) {
            between.push(new Date(currentDate));
            currentDate.setDate(currentDate.getDate() + 1);
        }
    }
    return between;
}

function availabilitytablejs() { }
function editavailabilitytablejs(data = []) { }


function loadConfirm() {
    // $('[data-confirm]').each(function () {
    //     var me = $(this),
    //         me_data = me.data('confirm');
    //     me_data = me_data.split("|");
    //     me.fireModal({
    //         title: me_data[0],
    //         body: me_data[1],
    //         buttons: [
    //             {
    //                 text: me.data('confirm-text-yes') || 'Yes',
    //                 class: 'btn btn-sm btn-danger rounded-pill',
    //                 handler: function () {
    //                     eval(me.data('confirm-yes'));
    //                 }
    //             },
    //             {
    //                 text: me.data('confirm-text-cancel') || 'Cancel',
    //                 class: 'btn btn-sm btn-secondary rounded-pill',
    //                 handler: function (modal) {
    //                     $.destroyModal(modal);
    //                     eval(me.data('confirm-no'));
    //                 }
    //             }
    //         ]
    //     })
    // });
}



function show_toastr(title, message, type) {
    var o, i;
    var icon = '';
    var cls = '';
    if (type == 'success') {
        icon = 'fas fa-check-circle';
        cls = 'primary';
    } else {
        icon = 'fas fa-times-circle';
        cls = 'danger';
    }
    $.notify({ icon: icon, title: " " + title, message: message, url: "" }, {
        element: "body",
        type: cls,
        allow_dismiss: !0,
        placement: { from: 'top', align: 'right' },
        offset: { x: 15, y: 15 },
        spacing: 10,
        z_index: 1080,
        delay: 2500,
        timer: 2000,
        url_target: "_blank",
        mouse_over: !1,
        animate: { enter: o, exit: i },
        template: '<div class="toast text-white bg-' + cls + ' fade show" role="alert" aria-live="assertive" aria-atomic="true">'
            + '<div class="d-flex">'
            + '<div class="toast-body"> ' + message + ' </div>'
            + '<button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>'
            + '</div>'
            + '</div>'
    });
}



// Delete to open modal
// (function ($, window, i) {
//     // Bootstrap 4 Modal
//     $.fn.fireModal = function (options) {
//         var options = $.extend({
//             size: 'modal-md',
//             center: false,
//             animation: true,
//             title: 'Modal Title',
//             closeButton: true,
//             header: true,
//             bodyClass: '',
//             footerClass: '',
//             body: '',
//             buttons: [],
//             autoFocus: true,
//             created: function () {
//             },
//             appended: function () {
//             },
//             onFormSubmit: function () {
//             },
//             modal: {}
//         }, options);
//         this.each(function () {
//             if ($(this).attr('class').includes('trigger--fire-modal-')) {
//                 return;
//             }
//             i++;
//             var id = 'fire-modal-' + i,
//                 trigger_class = 'trigger--' + id,
//                 trigger_button = $('.' + trigger_class);
//             $(this).addClass(trigger_class);
//             // Get modal body
//             let body = options.body;
//             if (typeof body == 'object') {
//                 if (body.length) {
//                     let part = body;
//                     body = body.removeAttr('id').clone().removeClass('modal-part');
//                     part.remove();
//                 } else {
//                     body = '<div class="text-danger">Modal part element not found!</div>';
//                 }
//             }
//             // Modal base template
//             var modal_template = '   <div class="modal' + (options.animation == true ? ' fade' : '') + '" tabindex="-1" role="dialog" id="' + id + '">  ' +
//                 '     <div class="modal-dialog ' + options.size + (options.center ? ' modal-dialog-centered' : '') + '" role="document">  ' +
//                 '       <div class="modal-content">  ' +
//                 ((options.header == true) ?
//                     '         <div class="modal-header">  ' +
//                     '           <h5 class="modal-title">' + options.title + '</h5>  ' +
//                     ((options.closeButton == true) ?
//                         '           <button type="button" class="close" data-dismiss="modal" aria-label="Close">  ' +
//                         '             <span aria-hidden="true">&times;</span>  ' +
//                         '           </button>  '
//                         : '') +
//                     '         </div>  '
//                     : '') +
//                 '         <div class="modal-body">  ' +
//                 '         </div>  ' +
//                 (options.buttons.length > 0 ?
//                     '         <div class="modal-footer">  ' +
//                     '         </div>  '
//                     : '') +
//                 '       </div>  ' +
//                 '     </div>  ' +
//                 '  </div>  ';
//             // Convert modal to object
//             var modal_template = $(modal_template);
//             // Start creating buttons from 'buttons' option
//             var this_button;
//             options.buttons.forEach(function (item) {
//                 // get option 'id'
//                 let id = "id" in item ? item.id : '';
//                 // Button template
//                 this_button = '<button type="' + ("submit" in item && item.submit == true ? 'submit' : 'button') + '" class="' + item.class + '" id="' + id + '">' + item.text + '</button>';
//                 // add click event to the button
//                 this_button = $(this_button).off('click').on("click", function () {
//                     // execute function from 'handler' option
//                     item.handler.call(this, modal_template);
//                 });
//                 // append generated buttons to the modal footer
//                 $(modal_template).find('.modal-footer').append(this_button);
//             });
//             // append a given body to the modal
//             $(modal_template).find('.modal-body').append(body);
//             // add additional body class
//             if (options.bodyClass) $(modal_template).find('.modal-body').addClass(options.bodyClass);
//             // add footer body class
//             if (options.footerClass) $(modal_template).find('.modal-footer').addClass(options.footerClass);
//             // execute 'created' callback
//             options.created.call(this, modal_template, options);
//             // modal form and submit form button
//             let modal_form = $(modal_template).find('.modal-body form'),
//                 form_submit_btn = modal_template.find('button[type=submit]');
//             // append generated modal to the body
//             $("body").append(modal_template);
//             // execute 'appended' callback
//             options.appended.call(this, $('#' + id), modal_form, options);
//             // if modal contains form elements
//             if (modal_form.length) {
//                 // if `autoFocus` option is true
//                 if (options.autoFocus) {
//                     // when modal is shown
//                     $(modal_template).on('shown.bs.modal', function () {
//                         // if type of `autoFocus` option is `boolean`
//                         if (typeof options.autoFocus == 'boolean')
//                             modal_form.find('input:eq(0)').focus(); // the first input element will be focused
//                         // if type of `autoFocus` option is `string` and `autoFocus` option is an HTML element
//                         else if (typeof options.autoFocus == 'string' && modal_form.find(options.autoFocus).length)
//                             modal_form.find(options.autoFocus).focus(); // find elements and focus on that
//                     });
//                 }
//                 // form object
//                 let form_object = {
//                     startProgress: function () {
//                         modal_template.addClass('modal-progress');
//                     },
//                     stopProgress: function () {
//                         modal_template.removeClass('modal-progress');
//                     }
//                 };
//                 // if form is not contains button element
//                 if (!modal_form.find('button').length) $(modal_form).append('<button class="d-none" id="' + id + '-submit"></button>');
//                 // add click event
//                 form_submit_btn.click(function () {
//                     modal_form.submit();
//                 });
//                 // add submit event
//                 modal_form.submit(function (e) {
//                     // start form progress
//                     form_object.startProgress();
//                     // execute `onFormSubmit` callback
//                     options.onFormSubmit.call(this, modal_template, e, form_object);
//                 });
//             }
//             $(document).on("click", '.' + trigger_class, function () {
//                 $('#' + id).modal('show');
//                 // $('#' + id).modal(options.modal);
//                 return false;
//             });
//         });
//     }
//     // Bootstrap Modal Destroyer
//     $.destroyModal = function (modal) {
//         modal.modal('hide');
//         modal.on('hidden.bs.modal', function () {
//         });
//     }
// })(jQuery, this, 0);

$(document).on('click', '.fc-day-grid-event', function (e) {
    // if (!$(this).hasClass('project')) {
    e.preventDefault();
    var event = $(this);
    var title = $(this).find('.fc-content .fc-title').html();
    var size = 'md';
    var url = $(this).attr('href');
    $("#comModal .modal-title").html(title);
    $("#comModal .modal-dialog").addClass('modal-' + size);
    $.ajax({
        url: url,
        success: function (data) {
            $('#comModal .modal-body').html(data);
            $("#comModal").modal('show');

        },
        error: function (data) {
            data = data.responseJSON;
            toastrs('Error', data.error, 'error')
        }
    });
    // }
});
function ddatetime_range() {
    if($('.datetime_class').length > 0) {
        $('.datetime_class').daterangepicker({
            "singleDatePicker": true,
            "timePicker": true,
            "autoApply": true,
            "locale": {
                "format": 'YYYY-MM-DD H:mm'
            },
            "timePicker24Hour": true,
        }, function (start, end, label) {
            $('.start_date').val(start.format('YYYY-MM-DD H:mm'));
        });
    }
}

$(function(){
    $(document).on("click",".show_confirm",function(){
    var form = $(this).closest("form");
    const swalWithBootstrapButtons = Swal.mixin({
        customClass: {
            confirmButton: 'btn btn-success',
            cancelButton: 'btn btn-danger'
        },
        buttonsStyling: false
    })
    swalWithBootstrapButtons.fire({
        title: 'Are you sure?',
        text: "This action can not be undone. Do you want to continue?",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Yes',
        cancelButtonText: 'No',
        reverseButtons: true
    }).then((result) => {
        if (result.isConfirmed) {
            form.submit();
        }
    })
});
});

if ($('#useradd-sidenav').length > 0) {
    var scrollSpy = new bootstrap.ScrollSpy(document.body, {
        target: '#useradd-sidenav',
        offset: 300,
    });
}

$(document).on('click', 'a[data-ajax-popup-over="true"], button[data-ajax-popup-over="true"], div[data-ajax-popup-over="true"]', function () {

    var validate = $(this).attr('data-validate');
    var id = '';
    if (validate) {
        id = $(validate).val();
    }

    var title = $(this).data('title');
    var size = ($(this).data('size') == '') ? 'md' : $(this).data('size');
    var url = $(this).data('url');

    $("#commonModalOver .modal-title").html(title);
    $("#commonModalOver .modal-dialog").addClass('modal-' + size);

    $.ajax({
        url: url + '?id=' + id,
        success: function (data) {
            $('#commonModalOver .modal-body').html(data);
            $("#commonModalOver").modal('show');
            taskCheckbox();
        },
        error: function (data) {
            data = data.responseJSON;
            show_toastr('Error', data.error, 'error')
        }
    });

});
