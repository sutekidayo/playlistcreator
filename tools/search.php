<?php
/**
 * Created by JetBrains PhpStorm.
 * User: SaiKo
 * Date: 11/4/12
 * Time: 10:29 PM
 * To change this template use File | Settings | File Templates.
 */
$bt = BlockType::getByHandle('playlistcreator');
$toolsdir = Loader::helper('concrete/urls')->getBlockTypeToolsURL($bt);

$query = str_replace(" ","+",$_POST['query']);
$groove_contents="";
$grooveAPI = "5ba3b657be55af5802c98fa78daaf77f"; // Tinysong API KEY
$grooveURL ="http://tinysong.com/s/$query?format=json&limit=32&key=$grooveAPI";

$ch = curl_init();
$timeout = 1; // set to zero for no timeout
curl_setopt ($ch, CURLOPT_URL, $grooveURL);
curl_setopt ($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt ($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
$groove_contents = json_decode(curl_exec($ch),true);
curl_close($ch);



echo '<table id="results"><thead><tr><th>Song Name</th><th>Artist Name</th><th>Album Name</th><th></th></tr></thead><tbody>';
foreach( $groove_contents as $result)
{
    echo '<tr id="'.$result['SongID'].'"><td class="songName clickable">'.$result["SongName"].'</td><td class="artistName clickable">'.$result['ArtistName'].'</td><td class="clickable">'.$result['AlbumName'].'</td><td><img class="play" style="padding-left:25px;"width="25px" title="Play Song" src="'.$toolsdir.'/play.png" name="'.$result['SongID'].'"/><tr/>' ;
}
?></tbody></table>';
<script>$(".play").click(function(){
           $("#grooveshark_embed").load("<?php echo $toolsdir?>/grooveshark.php",{songID:$(this).attr("name")});

        });
        $(".clickable").click(function(){
            var songID = $(this).parent().attr("id");
            //alert("parent: "+songID);
            var songName = $(this).parent().find(".songName").html();
            var artistName = $(this).parent().find(".artistName").html();
            //alert(songID+" "+songName+" "+artistName);
            $.post("<?php echo $toolsdir?>/playlist.php",{action:"add", SongID:songID, SongName:songName, ArtistName:artistName}, function(data){
                $("#playlist").load("<?php echo $toolsdir?>/playlist.php", function(data){
                    $("#results_wrapper").html("");
                });

            });
        });

        </script>';