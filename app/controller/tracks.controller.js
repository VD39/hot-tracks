ngAdmin.controller('TracksController', function ($scope, $document, services) {

    //Sets the title of the page
    $document[0].title = 'Hot Tracks';

    //Displays the tracks on the page
    function showTracks() {

        var result = services.listHotTracks();

        result.then(function (data) {

            $scope.tracks = data;

        })
            .catch(function (data) {

            });
    };

    //Call the function showTracks();
    showTracks();

    //Deletes the song from the hot track list
    $scope.deleteTrack = function (track) {

        var result = services.deleteHotTrack(track.hotTrackId)

        result.then(function (data) {

            $scope.tracks.splice($scope.tracks.indexOf(track), 1); //Removes from the json object literial

        })
            .catch(function (data) {

            });
    };

});