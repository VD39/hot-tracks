ngAdmin.controller('ListSongsController', function ($scope, $timeout, $document, services) {

    //Sets the title of the page
    $document[0].title = 'View Songs';

    //Set an empty object literial for all genre to act as a cache
    var allGenres = {};

    //Get the data to show on the view on the page load
    function listSongsAndWriters() {

        var result = services.listSongsAndWriters(); //Gets the result from get request in /js/services/factory.js

        result.then(function (data) {

            $scope.songs = data.data.songs;
            $scope.writers = data.data.writers;
            allGenres = data.data.genres; //Song genre data added to cache variable
            $scope.genres = allGenres;
            $scope.currentPage = 1; //Sets the current page
            $scope.entryLimit = 10; //Sets the limit of number of songs on a page
            $scope.filteredItems = $scope.songs.length; //Sets the total count of the songs

        })
            .catch(function (data) {

            });

    };

    //Call the function listSongsAndWriters();
    listSongsAndWriters();

    //Checks if the writer or genre is set
    $scope.refine = function () {

        return $scope.writer || $scope.genre;

    };

    //Checks with option was removed and updated the list of songs
    $scope.updateOptions = function (option) {

        if (option == 'writer') {
            $scope.writer = '';
            $scope.genres = allGenres; //Sets genres to the cached genres
            $scope.filter();
        } else if (option == 'genre') {
            $scope.genre = '';
            $scope.filter();
        }

    };

    //Fetches the list of genre of that artist
    $scope.updateGenres = function () {

        //If genres list is slected reset the list
        if ($scope.genre) {
            $scope.genre = '';
        }

        var writer = $scope.writer.writer;

        writer = encodeURIComponent(writer); //Encode for punctuation

        var result = services.getGenreByWriter(writer);

        result.then(function (data) {

            $scope.genres = data.data;
            $scope.filter();

        })
            .catch(function (data) {

            });

    };

    //Sets the sorting of the table
    $scope.sortByType = 'title';
    $scope.reverse = false;

    $scope.sortBy = function (byType) {

        $scope.sortByType = byType;
        $scope.reverse = !$scope.reverse;

    };

    //Deletes the songs from the list
    $scope.deleteSong = function (song) {

        if (confirm('Are you sure you want to delete this song?')) {

            //Checks if the writer is set, if it is then get new count of genres of that writer or fetch all genre data
            if ($scope.writer) {
                var writer = encodeURIComponent(song.writer); //Encode for punctuation 
                var result = services.deleteSongWithWriter(song.songId, writer);
            } else {
                var result = services.deleteSong(song.songId);
            }

            result.then(function (data) {

                $scope.songs.splice($scope.songs.indexOf(song), 1); //Remove from the json object literial

                $scope.writers = data.data.writers;

                //Checks if the genresByWriter is not undefined (won't be if $scope.writer is set)
                if (data.data.genresByWriter != undefined) {
                    allGenres = data.data.genres; //Cache all genres for when the $scope.writer is not set
                    $scope.genres = data.data.genresByWriter;
                } else {
                    $scope.genres = data.data.genres;
                }

                //Checks if the $scope.writer or $scope.genre is set, if the total is 0 then set them to null
                $timeout(function () {
                    $scope.filter();
                    //Checks if the $scope.writer or $scope.genre is set, if the total is 0 then set genre to null    
                    if ($scope.writer || $scope.genre && $scope.filtered.length == 0) {

                        $scope.genre = '';

                        $timeout(function () {

                            $scope.filter();
                            //Checks if the $scope.writer is set, if the total is 0 then set writer to null
                            if ($scope.writer && $scope.filtered.length == 0) {
                                $scope.writer = '';
                                $scope.genres = allGenres; //Set genres to the cahed data
                            }
                        }, 0);
                    }
                }, 0);

            })
                .catch(function (data) {

                });

        }

    };

    //Adds a song to the hot track list
    $scope.addHotTrack = function (song) {

        var result = services.addHotTrack(song.songId);

        result.then(function (data) {

            song.inhottrack = true;

        })
            .catch(function (data) {

            });

    };

    //Checks if the song is in the hot track
    $scope.inHotTrack = function (song) {

        return song.inhottrack;

    };

    //Gets the current page number
    $scope.setPage = function (pageNo) {

        $scope.currentPage = pageNo;

    };

    //Gets the filtered number of songs
    $scope.filter = function () {

        $timeout(function () {
            $scope.filteredItems = $scope.filtered.length;
        }, 0);

    };

});