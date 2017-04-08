angular.module('tracks')
  .directive('sortTracks', function ($http) {

    return function (scope, element, attrs) {

      var fixHelper = function (e, tr) {
        tr.children().each(function () {
          $(this).width($(this).width());
        });
        return tr;
      };

      var updateOrder = function (e, tr) {

        var trackOrder = $(this).sortable('serialize');

        var result = $http.post('http://localhost/data/services.php?action=updatetracks', trackOrder)
          .then(function (data) {

          })
          .catch(function (status) {
            s
          });
      };

      element.sortable({

        cursor: 'move',
        opacity: 0.8,
        helper: fixHelper,
        update: updateOrder

      })
        .disableSelection()
        .addClass('cursorMove');
    }
  });