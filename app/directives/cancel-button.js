angular.module('tracks')
    .directive('cancelButton', function () {

        return function (scope, element, attrs) {

            element.on({
                mouseenter: function () {
                    $(this)
                        .removeClass('btn-primary')
                        .addClass('btn-danger');
                },
                mouseleave: function () {
                    $(this)
                        .removeClass('btn-danger')
                        .addClass('btn-primary');
                },
                mousedown: function () {
                    $(this)
                        .removeClass('btn-primary')
                        .addClass('btn-danger');
                }
            });
        }

    });