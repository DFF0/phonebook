$(function () {

    $("body").on("change keyup input click", "input.number-only", function () {
        if (this.value.match(/[^0-9]/g)) {
            this.value = this.value.replace(/[^0-9]/g, '');
        }
    });

    $("body").on("change keyup input click", "input.number-only-ex", function () {
        if (this.value.match(/[^0-9]/g)) {
            this.value = this.value.replace(/[^0-9\-]/g, '');
        }
    });

    $("body").on("change keyup input click", "input.cyrillic-only", function () {
        if (this.value.match(/[^а-яА-Я]/g)) {
            this.value = this.value.replace(/[^а-яА-Я]/g, '');
        }
    });

    $("body").on("change keyup input click", "input.latin-only", function () {
        if (this.value.match(/[^a-zA-Z]/g)) {
            this.value = this.value.replace(/[^a-zA-Z]/g, '');
        }
    });

    $("body").on("change keyup input click", "input.latin-number-only", function () {
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
});