<?php
require_once("includes/config.php");
include("songs.php");
header('Access-Control-Allow-Origin: *');

$Songs = new Songs(); 

if (isset($_GET['action'])) {
    $action = strtolower($_GET['action']);
    switch ($action) {
        case "listsongsandwriters":
            $Songs->listSongsAndWriters();
            break;
        
        case "getgenrebywriter":
            $Songs->getGenreByWriter();
            break;
        
        case "addsong":
            $Songs->addSong();
            break;
        
        case "editsong":
            $Songs->editSong();
            break;
        
        case "fetchallgenres":
            $Songs->fetchAllGenres();
            break;
        
        case "editfetchsonggenres":
            $Songs->editFetchSongGenres();
            break;
        
        case "updatesong":
            $Songs->updateSong();
            break;
        
        case "deletesong":
            $Songs->deleteSong();
            break;
        
        case "listhottracks":
            $Songs->listHotTracks();
            break;
        
        case "addhottrack":
            $Songs->addHotTrack();
            break;
        
        case "deletehottrack":
            $Songs->deleteHotTrack();
            break;
        
        case "updatetracks":
            $Songs->updateTracks();
            break;
        
        default:
            break;
    }
} else {
    echo "Action is not set";
}

?>