<?php
/**
 * Created by JetBrains PhpStorm.
 * User: SaiKo
 * Date: 11/4/12
 * Time: 4:50 PM
 * To change this template use File | Settings | File Templates.
 */

$bt = BlockType::getByHandle('playlistcreator');
$toolsdir = Loader::helper('concrete/urls')->getBlockTypeToolsURL($bt);
$blockdir = Loader::helper('concrete/urls')->getBlockTypeAssetsURL($bt);
$lastfmAPI = "0ada7a99aead9d29f6ecae4de5a229a4";

$db = Loader::db();
$db->SetFetchMode(ADODB_FETCH_ASSOC);

// get the selected playlist
if (empty($_POST["action"])) {
    $result = $db->GetAssoc("SELECT * FROM songs,playlist_songs WHERE playlist_songs.sID = songs.SongID AND playlist_songs.pID=1 ORDER BY songs.sRank DESC");

        $playlist = '<table id="playlist" width="100%">
           <thead><tr>
               <th>Song Name</th> <th>Artist Name</th><th>Votes</th><th>Duration</th><th></th>
           </tr></thead>';

        foreach ($result as $row) {
            $playlist .= '<tr>
                <td>' . $row["sName"] . '</td><td>' . $row["sArtist"] . '</td><td class="votes">' . $row["sRank"] . '</td><td>' . gmdate('i:s', $row["sLength"] * .001) . '</td>
                <td style="text-align:left"width="120px">
                    <div class="vote">
                        <img title="Vote UP" class="vote_up" src="'.$blockdir.'/images/vote_up.png"name="' . $row["sID"] . '"/>
                        <img title="Vote DOWN"class="vote_down" src="'.$blockdir.'/images/vote_down.png" name="' . $row["sID"] . '">
                    </div>
                    <img title="Play Song"class="play" style="padding-left:25px;"width="50px" src="'.$blockdir.'/images/play.png" name="' . $row["sID"] . '"/>
                </td>
            </tr>';
        }
echo $playlist;
?>
        </table>
            <script>
            $(".play").click(function(){
                $("#grooveshark_embed").load("<?php echo $toolsdir?>/grooveshark.php",{songID:$(this).attr("name")});
            });

            $(".vote_up").click(function(){
                $.post("<?php echo $toolsdir?>/playlist.php",{action:"vote_up", SongID:$(this).attr("name")},function(data){
                    //Update the Vote Tally
                   $("#playlist").load("<?php echo $toolsdir?>/playlist.php");
                });
            });
            $(".vote_down").click(function(){
                 $.post("<?php echo $toolsdir?>/playlist.php",{action:"vote_down", SongID:$(this).attr("name")},function(data){
                    //Update the Vote Tally
                    $("#playlist").load("<?php echo $toolsdir?>/playlist.php");
                });
            });

        </script>



<?php

} else if ($_POST["action"] == "add") {
    //First check to see if the song already exists in DB
    $result = $db->GetAll("SELECT songID FROM songs WHERE songID=" . $_POST["SongID"]);
    if (count($result) == 0) { // Need to add a new entry to the songs DB
        //find song length from LastFM
        $lastfmURL = "http://ws.audioscrobbler.com/2.0/?method=track.getInfo&api_key=$lastfmAPI&track=" . urlencode($_POST["SongName"]) . "&artist=" . urlencode($_POST['ArtistName']) . "&autocorrect=1&format=json";
        $ch = curl_init();
        $timeout = 1; // set to zero for no timeout
        curl_setopt($ch, CURLOPT_URL, $lastfmURL);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
        $lastfm_contents = json_decode(curl_exec($ch), true);
        curl_close($ch);
        $length = $lastfm_contents["track"]["duration"]; // Time in Miliseconds


        if (!$db->Execute("INSERT INTO songs (songID,sName,sArtist,sRank,sLength) VALUES
            ('" . $_POST["SongID"] . "',
            '" . $_POST["SongName"] . "',
            '" . $_POST["ArtistName"] . "',
            '0','" . $length . "')")
        ) {
            die('Error inserting into table');
        }
    }
    // Song is Already in the database! Add it to the current Playlist
    $result = $db->GetAll("SELECT sID FROM playlist_songs WHERE sID=" . $_POST["SongID"]);
    if (count($result) == 0) {
        if (!$db->Execute("INSERT INTO playlist_songs (pID, sID) VALUES('1','" . $_POST["SongID"] . "')")) {
            die('Error: Could not Insert into Playlist_songs table');
        }
    }

} else if ($_POST["action"] == "vote_up") {

    if (!$db->Execute("UPDATE songs SET sRank=sRank+1 WHERE songID=" . $_POST["SongID"])) {
        die('Error: Could not Vote Song UP!');
    }
} else if ($_POST["action"] == "vote_down") {

    if (!$db->Execute("UPDATE songs SET sRank=sRank-1 WHERE songID=" . $_POST["SongID"])) {
        die('Error: Could not vote song down!');
    }
}

// Approve

// Not Approved

//

// checkLyrics Function calls the Lyric API for the song in Questions
// Calls getLyrics() and safeLyrics()
// returns the number of innappropriate words found in the lyrics
?>