ngAdmin.controller('SongController', function ($scope, $timeout, $document, $stateParams, services, song) {

    var songId = parseInt($stateParams.songId);

    $scope.add = false; //If reset button should appear or not
    var songData = song.data; //Sets the song data

    if (songId) {

        //Adds the data of the song being edited on the form    
        function showEdit() {

            $scope.song = songData.song;
            $document[0].title = 'Edit Song ' + songData.song.title; //Sets the title of the page
            $scope.headerTitle = 'Edit Song ' + songData.song.title; //Sets the header title of the page
            $scope.genres = songData.allGenres;
            $scope.songGenres = songData.genres;

        }

        //Call the function showEdit();
        showEdit();

    } else {

        $document[0].title = 'Add New Song'; //Sets the title of the page
        $scope.headerTitle = 'Add New Song'; //Sets the header title of the page
        $scope.add = true;

        $scope.genres = songData;

    }

    //Clears the form
    $scope.yearError = function () {

        return $scope.songForm.post_year.$error.maxlength ||
            $scope.songForm.post_year.$error.minlength ||
            $scope.songForm.post_year.$error.pattern;

    }

    //Updates the song if the form is valid    
    $scope.saveSong = function () {

        //Empty array
        var genre = [];

        //Loops over the genres, if genre is selected add to array
        angular.forEach($scope.songGenres, function (value) {

            if (value.genreId != undefined) {
                this.push(value.genreId);
            }

        }, genre);

        //Sets the paramaters to send via post
        $params = {
            'title': $scope.song.title,
            'album': $scope.song.album,
            'writer': $scope.song.writer,
            'genre': genre,
            'year': $scope.song.year,
            'songLink': $scope.song.songLink,
            'videoLink': $scope.song.videoLink
        };

        if (songId) {

            var result = services.updateSong(songId, $params);

            result.then(function (data) {

                showEdit();
                $scope.sMessage = 'Updated Song ' + config.data.title;
                $scope.sucess = true;

                //removes the success message from form
                $timeout(function () {

                    $scope.sMessage = '';
                    $scope.sucess = false;

                }, 3500);

            })
                .catch(function (data) {

                    $scope.catch = true;
                    $scope.eMessage = 'Something went wrong';

                });
        } else {

            var result = services.addSong($params);

            result.then(function (data) {

                $scope.sMessage = 'Song ' + config.data.title + ' has been added';
                $scope.sucess = true;
                $scope.songForm.$setPristine();

                //removes the success message from form
                $timeout(function () {

                    $scope.sMessage = '';
                    $scope.sucess = false;

                }, 3500);

            })
                .catch(function (data) {

                    $scope.catch = true;
                    $scope.eMessage = 'Something went wrong';

                });
        }
    }

});