/* hover dropdown */
$('.dropdown-menu a.dropdown-toggle').on('click', function (e) {
    if (!$(this).next().hasClass('show')) {
        $(this).parents('.dropdown-menu').first().find('.show').removeClass("show");
    }
    var $subMenu = $(this).next(".dropdown-menu");
    $subMenu.toggleClass('show');

    $(this).parents('li.nav-item.dropdown.show').on('hidden.bs.dropdown', function (e) {
        $('.dropdown-submenu .show').removeClass("show");
    });

    return false;
});


/* top-partners carousel */
$('.top-partners.owl-carousel').owlCarousel({
    autoplay: true,
    items: 10,
    rtl: false,
    center: false,
    loop: true,   
    margin: 5,
    nav: false,
    dots: false,
    autoplayTimeout: 2000,
    autoplayHoverPause: true,
    /* 
    slideTransition: "linear", 
    autoplayTimeout: 1500,
    autoplaySpeed: 1500,
    */
    lazyLoad: true,
    responsive: {
        0: {
            items: 6
        },
        560: {
            items: 12
        },
    }
})

var fixOwl = function(){
    var $stage = $('.owl-stage'),
        stageW = $stage.width(),
        $el = $('.owl-item'),
        elW = 0;
    $el.each(function() {
        elW += $(this).width()+ +($(this).css("margin-right").slice(0, -2))
    });
    if ( elW > stageW ) {
        $stage.width( elW );
    };
}

/* home-arbiters carousel */
$('.home-arbiters.owl-carousel').owlCarousel({
    autoplay: false,
    items: 5,
    margin: 10,
    nav: true,
    loop: false,   
    dots: false,
    navText: ['<i class="fa fa-chevron-left"></i>', '<i class="fa fa-chevron-right"></i>'],
    lazyLoad: true,
    responsive: {
        0: {
            items: 3
        },
        560: {
            items: 5
        }
    }
});

/* home-testimonials carousel */
$('.home-testimonials.owl-carousel').owlCarousel({
    autoplay: false,
    margin: 20,
    nav: true,
    dots: false,
    navText: ['<i class="fa fa-chevron-left"></i>', '<i class="fa fa-chevron-right"></i>'],
    lazyLoad: true,
    responsive: {
        0: {
            items: 1
        },
        560: {
            items: 2
        },
        800: {
            items: 3
        },
    }
});

/* home-arbiters carousel */
$('.breaking-news.owl-carousel').owlCarousel({
    autoplay: true,
    items: 1,
    margin: 0,
    nav: true,
    loop: false,   
    dots: false,
    navText: ['<i class="fa fa-caret-left"></i>', '<i class="fa fa-caret-right"></i>'],
    lazyLoad: true,
    autoplayHoverPause: true
});

/* submit login form */
$('#login-form').submit(function () {
    $(this).ajaxSubmit({
        target: '#login-form-output',
        success: function (responseText, statusText) {
            if (responseText == "")
                //window.location.href = window.location.href;
                window.location.href = base_url+lang_code+'/account/register_to_competition';
        }
    });
    return false;
});

/* submit check diploma form */
$('.genuine-form').submit(function () {

    $('.genuine-form-output').html('<img src='+base_url+'images/loading.gif'+' width=30>');

    $(this).ajaxSubmit({
        target: '.genuine-form-output'        
    });
    return false;
});


/* ajax upload file*/
$('.ajax-upload').change(function () {

    var element = $(this);
    var element_name = element.attr('name');

    output_element = element.parents('.form-group').find('.ajax-output');

    //show output
    output_element.removeClass('d-none');
    output_element.addClass('d-flex');

    //output loding
    output_element.html('<div><strong>Loading...</strong><div class="spinner-border ml-auto" role="status" aria-hidden="true"></div>');

    //clear hidden input value 
    element.parents('.form-group').find('input.ajax-hidden-field').attr("value", "");

    var data = new FormData();
    $.each(element[0].files, function (i, file) {
        data.append(element_name, file);
    });

    $.ajax({
        url: base_url + "ro/account/ajax_upload",
        data: data,
        cache: false,
        contentType: false,
        processData: false,
        method: 'POST',
        type: 'POST',
        success: function (data) {
            //alert(data);                       
            response = data.split('|*|');

            //success
            if (response[0] == 'success') {
                ajax_set_output(output_element, response[1], response[2]);
            }
            //error
            else {
                output_element.html('<div class="invalid-feedback d-block">' + response[1] + '</div>');
            }
            element.next('.custom-file-label').html('Choose file');
        }
    });
});
function ajax_set_output(output_element, file_name, file_url) {
    //output html

    output_html = "";

    if (file_name.match(/\.(jpg|jpeg|png|gif)$/))
        output_html += '<div class="mr-3"><img src="' + file_url + '" width="100" class="border rounded"></div>';

    output_html += '<div class="text-muted"><small><a href="' + file_url + '" download>' + file_name + '</a></small></div>';
    output_html += '<div class="ml-auto"><button type="button" onclick="ajax_cancel_file($(this))" class="btn btn-light btn-sm" data-file-url = "' + file_url + '"><i class="fa fa-trash-alt"></i> remove</button></div>';

    //set output
    output_element.html(output_html);

    //set hidden input value 
    output_element.parents('.form-group').find('input.ajax-hidden-field').attr("value", file_name);

    //clear field server error
    output_element.parents('.form-group').find('.invalid-feedback').remove();

    //hide upload input
    output_element.parents('.form-group').find('.custom-file').addClass('d-none');
    output_element.parents('.form-group').find('.form-text').addClass('d-none');
}
function ajax_cancel_file(element) {

    element.after('<div class="spinner-border ml-auto" role="status" aria-hidden="true"></div>');
    $.post(base_url + lang_code + '/account/ajax_upload', { 'delete_file_url': element.attr('data-file-url') },
        function (data) {
            element.next('.spinner-border').remove();
            if (data == '') {
                var output_element = element.parents('.ajax-output');

                //hide output
                output_element.removeClass('d-flex');
                output_element.addClass('d-none');

                //show upload input
                element.parents('.form-group').find('.custom-file').removeClass('d-none');
                element.parents('.form-group').find('.form-text').removeClass('d-none');

                //clear hidden input value
                //element.parents('.form-group').find('.ajax-hidden-field').val(""); 
                element.parents('.form-group').find('input.ajax-hidden-field').attr("value", "");

                //clear output
                output_element.html('');
            }
            else {
                alert("Error: Connot be removed!");
            }
        });
}
$(document).ready(function () {
    $('.ajax-output').each(function () {
        file_name = $(this).attr('data-file-name');
        file_url = $(this).attr('data-file-url');

        if (file_name) {
            $(this).removeClass('d-none');
            $(this).addClass('d-flex');
            ajax_set_output($(this), file_name, file_url);
        }
    });
});

/* rewrite bootstrap required fields message */
document.addEventListener("DOMContentLoaded", function () {
    var elements = document.getElementsByTagName("INPUT");
    for (var i = 0; i < elements.length; i++) {
        elements[i].oninvalid = function (e) {
            e.target.setCustomValidity("");
            if (!e.target.validity.valid) {
                e.target.setCustomValidity(required_field);
            }
        };
        elements[i].oninput = function (e) {
            e.target.setCustomValidity("");
        };
    }
});

/* datepicker */
$('.datepicker').datepicker({
    format: 'yyyy-mm-dd',
    autoclose: true,
    language: 'ro'
});

/* uplodifive */
$(document).ready(function () {
    $('.uploadyfive').uploadifive({
        'uploadScript': base_url + lang_code + '/account/ajax_upload',
        'auto': true,
        'multi': true,
        'buttonText': select_project,
        'queueSizeLimit': 1,
        'fileObjName': 'project_file',
        'removeCompleted': false,
        'method': 'post',
        'onError': function (errorType) {
            alert('The error was: ' + errorType);
        },
        'onUploadComplete': function (file, data) {
            output = data.split('|*|');
            type = output[0];
            message = output[1];
            file_url = output[2];
            if (type == 'success') {
                $('#' + file.queueItem[0].id).append('<div class="text-success"><i class="fas fa-check-circle"></i> ' + message + '</div>');
                $(this).parents('form').find('input[name=project_filename]').val(message);
                $(this).parents('form').find('.project_filename').text(message);
                //$(this).parents('form').find('input[name=SubmitProject]').removeClass('d-none');
            }
            else if (type == 'error') {
                $('#' + file.queueItem[0].id).append('<div class="text-danger"><i class="fas fa-exclamation-circle"></i> ' + message + '</div>');
                //$(this).parents('form').find('input[name=SubmitProject]').addClass('d-none');
            }
        },
        'onCancel': function (file) {
            $.post(base_url + lang_code + '/account/ajax_upload', { 'delete_project_filename': $(this).parents('form').find('input[name=project_filename]').val() }, function (data) { });
            $(this).parents('form').find('input[name=project_filename]').val('');
            $(this).parents('form').find('.project_filename').text('');
           // $(this).parents('form').find('input[name=SubmitProject]').addClass('d-none');
        },
        'onQueueComplete': function (uploads) {
            //alert(uploads.successful + ' files were uploaded successfully.');
            //window.location.reload();
        },
        'onFallback': function () {
            alert('Oops!  You have to use the non-HTML5 file uploader.');
        }
    });
});

/* tooltip */
$(function () {
    $('[data-toggle="tooltip"]').tooltip()
})

/* autopopulate required field message text */
$('form input[required], form select[required], textarea').each(function () {
    
    var text = '<small class="font-italic" style="color:#999999">*required field</small>';

    if ($(this).attr("type") == "checkbox")
        $(this).parents('.form-group').append(text);
    else
        $(this).parents('.form-group').find('label').append(text)
});

/* submit project form */
$('.submit-project-form').submit(function () {
    $(this).ajaxSubmit({
        target: '#'+$(this).find('.submit-project-form-output').attr('id'),
        success: function (responseText, statusText) {
            if (responseText == "")
                window.location.href = window.location.href;                
        }
    });
    return false;
});


$(".countTime").TimeCircles({
    time: {
        Days: { color: "#E77918" },
        Hours: { color: "#E77918" },
        Minutes: { color: "#E77918" },
        Seconds: { color: "#E77918" }
    },
    use_background: true,
    fg_width: 0.04,
    bg_width: 0.5,
    circle_bg_color: "#cccccc",
    count_past_zero: false
});
