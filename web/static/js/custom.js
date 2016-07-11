$(function () {
    $('form.restful select').on('select2:select', function (e) {
        var name = $(this).attr('name'),
            $form = $(this).closest('form'),
            action = $form.attr('action'),
            actionPattern = $form.data('action-pat'),
            value = $(this).val();

        console.log(
            [name, $form, action, actionPattern, value]
        );

        if (typeof value != 'undefined' ) {
            var regexp = new RegExp("#"+name+"#");
            action = actionPattern.replace(regexp, value);
            $form.attr('action', action);
        }

        $(this).trigger('change');

    });

    $('form.restful select').select2({
        width: 'resolve',
        minimumResultsForSearch: -1
    });

    $('form[data-autosubmit] select').on('change', function (e) {
        $(this).parents('form').submit();
    });

    $('form[data-nodata]').on('submit', function (e) {
        if ($(this).is('.submitted')) {
        } else {
            e.preventDefault();
            $('form').find('select').prop('disabled','disabled');
            $(this).addClass('submitted');
            $(this).submit();
        }

    });

    $("body").css({ minHeight: ($(window).innerHeight()) + 'px' });
    $(window).resize(function() {
        $("body").css({ minHeight: ($(window).innerHeight()) + 'px' });
    });

    $('.popup-image').magnificPopup({type:'image'});


});