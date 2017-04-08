ngAdmin.factory('services', function ($http) {

    return {

        listSongsAndWriters: function () {
            return $http.get('http://localhost/data/services.php?action=listsongsandwriters');
        },

        deleteSong: function (songId) {
            return $http.delete('http://localhost/data/services.php?action=deletesong&songId=' + songId);
        },

        deleteSongWithWriter: function (songId, writer) {
            return $http.delete('http://localhost/data/services.php?action=deletesong&songId=' + songId + '&songWriter=' + writer);
        },

        getGenreByWriter: function (writer) {
            return $http.get('http://localhost/data/services.php?action=getgenrebywriter&writer=' + writer);
        },

        addHotTrack: function (songId) {
            return $http.get('http://localhost/data/services.php?action=addhottrack&songId=' + songId);
        },

        editSong: function (songId) {
            return $http.get('http://localhost/data/services.php?action=editsong&songId=' + songId);
        },

        updateSong: function (songId, $params) {
            return $http.put('http://localhost/data/services.php?action=updatesong&songId=' + songId, $params);
        },

        fetchAllGenres: function () {
            return $http.get('http://localhost/data/services.php?action=fetchallgenres');
        },

        addSong: function ($params) {
            return $http.put('http://localhost/data/services.php?action=addsong', $params);
        },

        listHotTracks: function () {
            return $http.get('http://localhost/data/services.php?action=listhottracks');
        },

        deleteHotTrack: function (hotTrackId) {
            return $http.delete('http://localhost/data/services.php?action=deletehottrack&hotTrackId=' + hotTrackId);
        }
    }

});