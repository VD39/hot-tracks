<?php

class Songs {

    public function listSongsAndWriters()
    {
        try {
            
            global $conn;
            
            $sql = "SELECT songs.songId, songs.title, songs.album, songs.writer, songs.year, songs.songLink, songs.videoLink, GROUP_CONCAT(genres.genre SEPARATOR ', ') AS genres, hot_tracks.songId AS inhottrack FROM songs 
                    LEFT JOIN songgenre ON songs.songId = songgenre.songId 
                    LEFT JOIN genres ON genres.genreId = songgenre.genreId 
                    LEFT JOIN hot_tracks on songs.songId = hot_tracks.songId
                    GROUP BY songs.songId";

            $statement = $conn->prepare($sql);

            $statement->execute();

            $listOfSongs = $statement->fetchAll(PDO::FETCH_ASSOC);

            $sql = "SELECT writer, songId, count(writer) AS total 
                    FROM songs 
                    GROUP BY writer
                    ORDER BY writer ASC";

            $statement = $conn->prepare($sql);

            $statement->execute();

            $listOfWriters = $statement->fetchAll(PDO::FETCH_ASSOC);

            $sql = "SELECT genres.genre as genres, count(genres.genre) AS total FROM songs 
                    LEFT join songgenre ON songs.songId = songgenre.songId 
                    LEFT join genres ON genres.genreId = songgenre.genreId  
                    WHERE genres.genre IS NOT NULL
                    GROUP BY genres.genre
                    ORDER BY genres.genre ASC";

            $statement = $conn->prepare($sql);

            $statement->execute();

            $listOfGenres = $statement->fetchAll(PDO::FETCH_ASSOC);

            $json = json_encode(array(
                "songs" => $listOfSongs,
                "writers" => $listOfWriters,
                "genres" => $listOfGenres
            ));

            // convert to json		
            echo $json;
        }

        catch (PDOException $e) {
            echo "Error:" . $e->getMessage();
        }
    }


    public function getGenreByWriter()
    {
        try {
            global $conn;
            if (isset($_GET["writer"])) {
                $writer    = $_GET["writer"];
                $sql       = "SELECT genres.genre as genres, count(genres.genre) AS total FROM songs 
                        LEFT join songgenre ON songs.songId = songgenre.songId 
                        LEFT join genres ON genres.genreId = songgenre.genreId 
                        WHERE songs.writer = :writer and genres.genre IS NOT NULL
                        GROUP BY genres.genre
                        ORDER BY genres.genre ASC";
                $statement = $conn->prepare($sql);
                $statement->execute(array(
                    ":writer" => $writer
                ));
                $result = $statement->fetchAll(PDO::FETCH_ASSOC);
                $json   = json_encode($result);
                echo $json;
            } else {
                echo "Writer not set";
            }
        }

        catch (PDOException $e) {
            echo "Error:" . $e->getMessage();
        }
    }

    public function addSong()
    {
        try {
            global $conn;
            $post      = json_decode(file_get_contents("php://input"));
            $title     = !empty($post->title) ? $post->title : '';
            $album     = !empty($post->album) ? $post->album : '';
            $writer    = !empty($post->writer) ? $post->writer : '';
            $year      = !empty($post->year) ? $post->year : '';
            $songLink  = !empty($post->songLink) ? $post->songLink : '';
            $videoLink = !empty($post->videoLink) ? $post->videoLink : '';
            $sql       = "INSERT INTO songs (  title, album, writer, year, songLink,  videoLink )
                    VALUES ( :title, :album, :writer, :year, :songLink,  :videoLink )";
            $query     = $conn->prepare($sql);
            $query->execute(array(
                ":title" => $title,
                ":album" => $album,
                ":writer" => $writer,
                ":year" => $year,
                ":songLink" => $songLink,
                ":videoLink" => $videoLink
            ));
            if (!empty($post->genre)) {
                $insertId = $conn->lastInsertId();
                $genre    = $post->genre;
                foreach ($genre as $genreId) {
                    if ($genreId == '' || $genreId == 0 || $genreId == '0') {
                        continue;
                    }

                    $sql   = "INSERT INTO songgenre(genreId, songId) 
                        VALUES (:genreId, :songId)";
                    $query = $conn->prepare($sql);
                    $query->execute(array(
                        ":genreId" => $genreId,
                        ":songId" => $insertId
                    ));
                }
            }
        }

        catch (PDOException $e) {
            echo "Error:" . $e->getMessage();
        }
    }

    public function editSong()
    {
        try {
            global $conn;
            if (isset($_REQUEST["songId"])) {
                $songId    = $_REQUEST["songId"];
                $sql       = "SELECT songs.* FROM songs 
                        WHERE songs.songId = :songId";
                $statement = $conn->prepare($sql);
                $statement->execute(array(
                    ":songId" => $songId
                ));

                $getSong = $statement->fetch(PDO::FETCH_ASSOC);

                $sql   = "SELECT genres.* FROM genres
                        INNER JOIN songgenre ON genres.genreId = songgenre.genreId
                        INNER JOIN songs ON songs.songId = songgenre.songId
                        WHERE songs.songId = :songId
                        ORDER BY genres.genre ASC";
                $query = $conn->prepare($sql);
                $query->execute(array(
                    ":songId" => $songId
                ));

                $getGenreBySong = $query->fetchAll(PDO::FETCH_ASSOC);


                $sql       = "SELECT * FROM genres 
                        ORDER BY genre ASC";
                $statement = $conn->prepare($sql);
                $statement->execute();
                $allGenres = $statement->fetchAll(PDO::FETCH_ASSOC);


                // convert to json

                $json = json_encode(array(

                    "song" => $getSong,
                    "genres" => $getGenreBySong,
                    "allGenres" => $allGenres

                ));

                echo $json;


            } else {
                echo "Song ID not found";
            }
        }

        catch (PDOException $e) {
            echo "Error:" . $e->getMessage();
        }
    }

    public function fetchAllGenres()
    {
        try {
            global $conn;
            $sql       = "SELECT * FROM genres 
                    ORDER BY genre ASC";
            $statement = $conn->prepare($sql);
            $statement->execute();
            $result = $statement->fetchAll(PDO::FETCH_ASSOC);

            // convert to json

            $json = json_encode($result);
            echo $json;
        }

        catch (PDOException $e) {
            echo "Error:" . $e->getMessage();
        }
    }

    public function updateSong()
    {
        try {
            global $conn;
            if (isset($_GET["songId"])) {
                $songId = $_GET["songId"];
                $post   = json_decode(file_get_contents("php://input"));

                $sql   = "UPDATE songs SET 
                        title = :title, 
                        album = :album, 
                        writer = :writer, 
                        year = :year, 
                        songLink = :songLink,  
                        videoLink = :videoLink 
                        WHERE songId = :songId";
                $query = $conn->prepare($sql);
                $check = $query->execute(array(
                    ":songId" => $songId,
                    ":title" => $post->title,
                    ":album" => $post->album,
                    ":writer" => $post->writer,
                    ":year" => $post->year,
                    ":songLink" => $post->songLink,
                    ":videoLink" => $post->videoLink
                ));
                $sql   = "DELETE FROM songgenre 
                        WHERE songId  = :songId";
                $query = $conn->prepare($sql);
                $query->execute(array(
                    ":songId" => $songId
                ));
                $genre = $post->genre;
                foreach ($genre as $genreId) {
                    if ($genreId == '' || $genreId == 0 || $genreId == '0') {
                        continue;
                    }

                    $sql   = "INSERT INTO songgenre(genreId, songId) 
                            VALUES (:genreId, :songId)";
                    $query = $conn->prepare($sql);
                    $check = $query->execute(array(
                        ":genreId" => $genreId,
                        ":songId" => $songId
                    ));
                }
            } else {
                echo "Song ID not found";
            }
        }

        catch (PDOException $e) {
            echo "Error:" . $e->getMessage();
        }
    }

    public function deleteSong()
    {
        try {
            global $conn;
            if (isset($_GET["songId"])) {
                $songId = $_GET["songId"];

                $sql   = "DELETE FROM songs 
                        WHERE songId = :songId";
                $query = $conn->prepare($sql);
                $query->execute(array(
                    ":songId" => $songId
                ));
                $sql   = "DELETE FROM songgenre
                        WHERE songId  = :songId";
                $query = $conn->prepare($sql);
                $query->execute(array(
                    ":songId" => $songId
                ));
                $sql   = "DELETE FROM hot_tracks 
                        WHERE songId  = :songId";
                $query = $conn->prepare($sql);
                $query->execute(array(
                    ":songId" => $songId
                ));

                $sql = "SELECT writer, songId, count(writer) AS total 
                    FROM songs 
                    GROUP BY writer
                    ORDER BY writer ASC";

                $statement = $conn->prepare($sql);

                $statement->execute();

                $listOfWriters = $statement->fetchAll(PDO::FETCH_ASSOC);

                if (isset($_GET["songWriter"])) {

                    $writer = $_GET["songWriter"];

                    $sql = "SELECT genres.genre as genres, count(genres.genre) AS total FROM songs 
                            LEFT join songgenre ON songs.songId = songgenre.songId 
                            LEFT join genres ON genres.genreId = songgenre.genreId   
                            WHERE genres.genre IS NOT NULL               
                            GROUP BY genres.genre
                            ORDER BY genres.genre ASC";

                    $statement = $conn->prepare($sql);

                    $statement->execute();

                    $listOfGenres = $statement->fetchAll(PDO::FETCH_ASSOC);

                    $sql = "SELECT genres.genre as genres, count(genres.genre) AS total FROM songs 
                            LEFT join songgenre ON songs.songId = songgenre.songId 
                            LEFT join genres ON genres.genreId = songgenre.genreId 
                            WHERE songs.writer = :writer and genres.genre IS NOT NULL
                            GROUP BY genres.genre
                            ORDER BY genres.genre ASC";

                    $statement = $conn->prepare($sql);

                    $statement->execute(array(
                        ":writer" => $writer
                    ));

                    $listOfGenresByWriter = $statement->fetchAll(PDO::FETCH_ASSOC);

                    $json = json_encode(array(
                        "writers" => $listOfWriters,
                        "genres" => $listOfGenres,
                        "genresByWriter" => $listOfGenresByWriter
                    ));

                } else {

                    $sql = "SELECT genres.genre as genres, count(genres.genre) AS total FROM songs 
                            LEFT join songgenre ON songs.songId = songgenre.songId 
                            LEFT join genres ON genres.genreId = songgenre.genreId   
                            WHERE genres.genre IS NOT NULL               
                            GROUP BY genres.genre
                            ORDER BY genres.genre ASC";

                    $statement = $conn->prepare($sql);

                    $statement->execute();

                    $listOfGenres = $statement->fetchAll(PDO::FETCH_ASSOC);

                    $json = json_encode(array(
                        "writers" => $listOfWriters,
                        "genres" => $listOfGenres
                    ));
                }

                // convert to json		
                echo $json;
            } else {
                echo "Song ID not found";
            }
        }

        catch (PDOException $e) {
            echo "Error:" . $e->getMessage();
        }
    }

    public function listHotTracks()
    {
        try {
            global $conn;
            $sql       = "SELECT * FROM hot_tracks
                    ORDER BY hotTrackPosition";
            $statement = $conn->prepare($sql);
            $statement->execute();
            $result = $statement->fetchAll(PDO::FETCH_ASSOC);

            // convert to json

            $json = json_encode($result);
            echo $json;
        }

        catch (PDOException $e) {
            echo "Error:" . $e->getMessage();
        }
    }

    public function addHotTrack()
    {
        try {
            global $conn;
            if (isset($_GET["songId"])) {
                $songId = $_GET["songId"];
                $sql    = "SELECT songs.*, hot_tracks.songId AS hotsongId FROM songs 
                        LEFT JOIN hot_tracks ON hot_tracks.songId = songs.songId
                        WHERE songs.songId = :songId";
                $query  = $conn->prepare($sql);
                $query->execute(array(
                    ":songId" => $songId
                ));
                $result = $query->fetch(PDO::FETCH_ASSOC);

                $hotsongId = !empty($result["hotsongId"]) ? $result["hotsongId"] : '';
                $title     = !empty($result["title"]) ? $result["title"] : '';
                $writer    = !empty($result["writer"]) ? $result["writer"] : '';
                $songLink  = !empty($result["songLink"]) ? $result["songLink"] : '';

                if ($hotsongId == '') {

                    $sql   = "SELECT hot_tracks.hotTrackPosition FROM hot_tracks 
                            ORDER BY hot_tracks.hotTrackPosition DESC 
                            LIMIT 1";
                    $query = $conn->prepare($sql);
                    $query->execute(array(
                        ":songId" => $songId
                    ));
                    $result           = $query->fetch(PDO::FETCH_ASSOC);
                    $hotTrackPosition = !empty($result["hotTrackPosition"]) ? ($result["hotTrackPosition"] + 1) : '1';
                    $sql              = "INSERT INTO hot_tracks (songId, hotTrackPosition, hotTrackTitle, hotTrackWriter, hotTrackLink) 
                            VALUES (:songId, :hotTrackPosition, :title, :writer, :songLink)";
                    $query            = $conn->prepare($sql);
                    $check            = $query->execute(array(
                        ":songId" => $songId,
                        ":hotTrackPosition" => $hotTrackPosition,
                        ":title" => $title,
                        ":writer" => $writer,
                        ":songLink" => $songLink
                    ));
                }
            } else {
                echo "Song ID not found";
            }
        }

        catch (PDOException $e) {
            echo "Error:" . $e->getMessage();
        }
    }

    public function deleteHotTrack()
    {
        try {
            global $conn;
            if (isset($_GET["hotTrackId"])) {
                $hotTrackId = $_GET["hotTrackId"];
                echo $hotTrackId;
                $sql   = "DELETE FROM hot_tracks 
                        WHERE hotTrackId = :hotTrackId";
                $query = $conn->prepare($sql);
                $query->execute(array(
                    ":hotTrackId" => $hotTrackId
                ));
            } else {
                echo "Hot Track ID not found";
            }
        }

        catch (PDOException $e) {
            echo "Error:" . $e->getMessage();
        }
    }

    public function updateTracks()
    {
        try {
            global $conn;
            if (isset($_POST["order"])) {
                $trackIds  = $_POST["order"];
                $posistion = 1;
                foreach ($trackIds as $trackId) {
                    $sql   = "UPDATE hot_tracks SET                  
                            hotTrackPosition = :hotTrackPosition
                            WHERE hotTrackId = :hotTrackId";
                    $query = $conn->prepare($sql);
                    $query->execute(array(
                        ":hotTrackPosition" => $posistion,
                        ":hotTrackId" => $trackId
                    ));
                    $posistion++;
                }
            } else {
                echo "Order not set";
            }
        }

        catch (PDOException $e) {
            echo "Error:" . $e->getMessage();
        }
    }
}

?>