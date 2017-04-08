var ngAdmin = angular.module('tracks', ['ui.router', 'ngResource']);

ngAdmin.config(function ($urlRouterProvider, $stateProvider, $locationProvider, $httpProvider) {

    $locationProvider.html5Mode({
        enabled: true,
        requireBase: false
    });

    $httpProvider.defaults.headers.post['Content-Type'] = 'application/x-www-form-urlencoded; charset=UTF-8';

    $stateProvider
        .state('app', {
            abstract: true,
            template: '<div data-ui-view></div>'
        })
        .state('app.home', {
            url: '/',
            templateUrl: './partials/songs.html',
            controller: 'ListSongsController'
        })
        .state('app.add', {
            url: '/add',
            templateUrl: './partials/add-edit.html',
            controller: 'SongController',
            resolve: {
                song: function (services) {
                    return services.fetchAllGenres();
                }
            }
        })
        .state('app.edit', {
            url: '/edit/:songId',
            templateUrl: '../partials/add-edit.html',
            controller: 'SongController',
            resolve: {
                song: function (services, $stateParams) {
                    var songId = $stateParams.songId;
                    return services.editSong(songId);
                }
            }
        })
        .state('app.hottracks', {
            url: '/hot-tracks',
            templateUrl: './partials/tracks.html',
            controller: 'TracksController'
        });

    $urlRouterProvider.otherwise('/')

})
    .filter('startFrom', function () {
        return function (input, start) {
            if (input) {
                start = +start; //parse to int
                return input.slice(start);
            }
            return [];
        }
    });