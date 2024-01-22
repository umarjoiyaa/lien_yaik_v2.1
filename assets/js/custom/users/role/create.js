$(document).ready(function () {
    const arrowButton = document.querySelectorAll(".menu-arrow");

    arrowButton.forEach((el) =>
        el.addEventListener("click", (event) => {
            const subMenu = event.target.parentElement.querySelector(".sub-menu1");
            subMenu.classList.toggle("open");
        })
    );

    $('.menu-arrow').click(function () {
        var $this = $(this);
        if ($this.hasClass('rotated')) {
            $this.css({
                "-webkit-transform": "",
                "-moz-transform": "",
                "transform": ""
            }).removeClass('rotated');
        } else {
            $this.css({
                "-webkit-transform": "rotate(180deg)",
                "-moz-transform": "rotate(180deg)",
                "transform": "rotate(180deg)"
            }).addClass('rotated');
        }
    });

    $('.dashboards , .reports , .users , .products , .materials , .productions , .inprogresses , .warehouses , .others').on('change', function () {
        if ($(this).is(':checked')) {
            $(this).closest(".parents").find("input").each(function () {
                $(this).prop('checked', true)
            });
        } else {
            $(this).closest(".parents").find("input").each(function () {
                $(this).prop('checked', false)
            });
        }
    });

    $('.names').on('change', function () {
        var $parent = $(this).closest('.check_box');
        var $siblings = $parent.find("input[type='checkbox']");
        var $parentCheckbox = $parent.closest('.parents').find('.check_parent');
        $parentCheckbox.prop('checked', $siblings.filter(':checked').length == $siblings.length).trigger('change');
    });

    $('.check_parent').on('change', function () {
        var $parent = $(this).closest('.check_name');
        var $siblings = $parent.find("input[type='checkbox']");
        var $parentCheckbox = $parent.closest('.parents').find('.name');
        $parentCheckbox.prop('checked', $siblings.filter(':checked').length == $siblings.length);
    });

    $('.check_parent').on('click', function () {
        if ($(this).is(':checked')) {
            $(this).closest(".child").find("input").each(function () {
                $(this).prop('checked', true)
            });
        } else {
            $(this).closest(".child").find("input").each(function () {
                $(this).prop('checked', false)
            });
        }
    });

    $('#permission').on('keyup', function () {
        var search = $(this).val().toLowerCase();
        var labels = $('.label');

        if (search == '') {
            $('li').show();
            $('.menu-arrow').show();
            $('.sub-menu1').removeClass('open');
            $('.menu-arrow').css({
                "-webkit-transform": "",
                "-moz-transform": "",
                "transform": ""
            }).removeClass('rotated');
        } else {
            labels.each(function () {
                let data = $(this).text().toLowerCase();
                if (data.includes(search)) {
                    $(this).closest('li').show();
                    $(this).next('.menu-arrow').show();
                    $(this).closest('.sub-menu1').addClass('open');
                    $(this).closest('.sub-menu1').prev('.menu-arrow').css({
                        "-webkit-transform": "rotate(180deg)",
                        "-moz-transform": "rotate(180deg)",
                        "transform": "rotate(180deg)"
                    }).addClass('rotated');
                    $(this).next('.menu-arrow').css({
                        "-webkit-transform": "rotate(180deg)",
                        "-moz-transform": "rotate(180deg)",
                        "transform": "rotate(180deg)"
                    }).addClass('rotated');
                    $(this).next('.menu-arrow').next('.sub-menu1').addClass('open');
                } else {
                    $(this).closest('.child').hide();
                    $(this).next('.menu-arrow').hide();
                    $(this).closest('.sub-menu1').prev('.menu-arrow').css({
                        "-webkit-transform": "",
                        "-moz-transform": "",
                        "transform": ""
                    }).removeClass('rotated')
                    $(this).next('.menu-arrow').css({
                        "-webkit-transform": "",
                        "-moz-transform": "",
                        "transform": ""
                    }).removeClass('rotated');
                    $(this).next('.menu-arrow').next('.sub-menu1').removeClass('open');
                }
            });
        }
    });
});
