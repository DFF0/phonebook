$(function ($) {

    var $body = $("body");
    $body.on("change keyup input click", "input.number-only", function () {
        if (this.value.match(/[^0-9]/g)) {
            this.value = this.value.replace(/[^0-9]/g, '');
        }
    });

    $body.on("change keyup input click", "input.number-only-ex", function () {
        if (this.value.match(/[^0-9]/g)) {
            this.value = this.value.replace(/[^0-9\-]/g, '');
        }
    });

    $body.on("change keyup input click", "input.cyrillic-only", function () {
        if (this.value.match(/[^а-яА-Я]/g)) {
            this.value = this.value.replace(/[^а-яА-Я]/g, '');
        }
    });

    $body.on("change keyup input click", "input.latin-only", function () {
        if (this.value.match(/[^a-zA-Z]/g)) {
            this.value = this.value.replace(/[^a-zA-Z]/g, '');
        }
    });

    $body.on("change keyup input click", "input.latin-number-only", function () {
        if (this.value.match(/[^0-9a-zA-Z]/g)) {
            this.value = this.value.replace(/[^0-9a-zA-Z]/g, '');
        }
    });


    var $addBlock = $('.add-block');
    var $filterBlock = $('.filter-block');
    var $infoBlock = $('.info-block');

    var $btnAdd = $('.btn-add');
    var $btnFilter = $('.btn-filter');

    $btnAdd.click(function () {
        $btnFilter.removeClass('active');
        $btnAdd.toggleClass('active');

        $filterBlock.addClass('d-none');
        $addBlock.toggleClass('d-none');
    });

    $btnFilter.click(function () {
        $btnAdd.removeClass('active');
        $btnFilter.toggleClass('active');

        $addBlock.addClass('d-none');
        $filterBlock.toggleClass('d-none');
    });

    var file;

    $('input[type=file]').on('change', function(){
        file = this.files[0];
    });

    $addBlock.find('form').on( 'submit', function( event ) {
        event.stopPropagation();
        event.preventDefault();

        var data = new FormData();
        data.append('name',    $(this).find('input[name=name]').val());
        data.append('surname', $(this).find('input[name=surname]').val());
        data.append('email',   $(this).find('input[name=email]').val());
        data.append('phone',   $(this).find('input[name=phone]').val());
        data.append('uploadfile',    file);

        $.ajax({
            url         : '/phonebook/createAJAX/',
            type        : 'POST',
            contentType : false,
            data        : data,
            cache       : false,
            dataType    : 'json',
            processData : false,
            success     : function( response, status, jqXHR ) {
                console.log(response);
                if ( response.success ) {
                    var img = 'unnamed.png';
                    if (response.data.img !== '') {
                        img = response.data.user_id + '/' + response.data.img;
                    }
                    $infoBlock.prepend('<div class="note-block row" data-id="' + response.data.id + '">\n' +
                        '                    <div class="col-sm-2">\n' +
                        '                        <img src="/user_image/' + img + '" alt="user">\n' +
                        '                    </div>\n' +
                        '                    <div class="col-sm-8">\n' +
                        '                        <div class="row">\n' +
                        '                            <div class="col-sm-6">' + response.data.name + '</div>\n' +
                        '                            <div class="col-sm-6">' + response.data.surname + '</div>\n' +
                        '                            <div class="col-sm-6">' + response.data.email + '</div>\n' +
                        '                            <div class="col-sm-6">+7 ' + response.data.phone + '</div>\n' +
                        '                            <div class="col-sm-12">' + response.data.text_number + '</div>\n' +
                        '                        </div>\n' +
                        '                    </div>\n' +
                        '                    <div class="col-sm-2 pull-right">\n' +
                        '                        <div>\n' +
                        '                            <button class="btn btn-danger btn-sm mb-2 del" type="button">\n' +
                        '                                <svg width="1em" height="1em" viewBox="0 0 16 16" class="bi bi-trash-fill" fill="currentColor" xmlns="http://www.w3.org/2000/svg">\n' +
                        '                                    <path fill-rule="evenodd" d="M2.5 1a1 1 0 0 0-1 1v1a1 1 0 0 0 1 1H3v9a2 2 0 0 0 2 2h6a2 2 0 0 0 2-2V4h.5a1 1 0 0 0 1-1V2a1 1 0 0 0-1-1H10a1 1 0 0 0-1-1H7a1 1 0 0 0-1 1H2.5zm3 4a.5.5 0 0 1 .5.5v7a.5.5 0 0 1-1 0v-7a.5.5 0 0 1 .5-.5zM8 5a.5.5 0 0 1 .5.5v7a.5.5 0 0 1-1 0v-7A.5.5 0 0 1 8 5zm3 .5a.5.5 0 0 0-1 0v7a.5.5 0 0 0 1 0v-7z"></path>\n' +
                        '                                </svg>\n' +
                        '                            </button>\n' +
                        '                            <button class="btn btn-info btn-sm edit" type="button">\n' +
                        '                                <svg width="1em" height="1em" viewBox="0 0 16 16" class="bi bi-pencil-square" fill="currentColor" xmlns="http://www.w3.org/2000/svg">\n' +
                        '                                    <path d="M15.502 1.94a.5.5 0 0 1 0 .706L14.459 3.69l-2-2L13.502.646a.5.5 0 0 1 .707 0l1.293 1.293zm-1.75 2.456l-2-2L4.939 9.21a.5.5 0 0 0-.121.196l-.805 2.414a.25.25 0 0 0 .316.316l2.414-.805a.5.5 0 0 0 .196-.12l6.813-6.814z"></path>\n' +
                        '                                    <path fill-rule="evenodd" d="M1 13.5A1.5 1.5 0 0 0 2.5 15h11a1.5 1.5 0 0 0 1.5-1.5v-6a.5.5 0 0 0-1 0v6a.5.5 0 0 1-.5.5h-11a.5.5 0 0 1-.5-.5v-11a.5.5 0 0 1 .5-.5H9a.5.5 0 0 0 0-1H2.5A1.5 1.5 0 0 0 1 2.5v11z"></path>\n' +
                        '                                </svg>\n' +
                        '                            </button>\n' +
                        '                        </div>\n' +
                        '                    </div>\n' +
                        '                </div>');

                    $('.messages').find('.alert-danger').remove();

                    $(this).find('input[name=name]').val('');
                    $(this).find('input[name=surname]').val('');
                    $(this).find('input[name=email]').val('');
                    $(this).find('input[name=phone]').val('');
                } else {
                    $('.messages').append('<div class="alert alert-danger" role="alert">'+response.error+'</div>');
                }
            },
            error: function( jqXHR, status, errorThrown ){
                console.log( 'ОШИБКА AJAX запроса: ' + status, jqXHR );
            }
        });
    });
});

$(document).on('click', '.info-block .del', function () {
    var block = $(this).parents('.note-block');
    var id = block.data('id');

    var data = new FormData();
    data.append('id', id);

    $.ajax({
        url         : '/phonebook/delete/',
        type        : 'POST',
        contentType : false,
        data        : data,
        cache       : false,
        dataType    : 'json',
        processData : false,
        success     : function( response, status, jqXHR ) {
            if ( response.success ) {
                block.remove();
            } else {
                $('.messages').append('<div class="alert alert-danger" role="alert">'+response.error+'</div>');
            }
        }
    });

    return false;
});