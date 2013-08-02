<?php defined('C5_EXECUTE') or die(_("Access Denied.")) ?>
<?php


$bt = BlockType::getByHandle('playlistcreator');
$toolsdir = Loader::helper('concrete/urls')->getBlockTypeToolsURL($bt);
$blockdir = Loader::helper('concrete/urls')->getBlockTypeAssetsURL($bt);

global $c;


?>


<link rel="stylesheet" href="http://code.jquery.com/ui/1.9.0/themes/base/jquery-ui.css">
<link href='http://fonts.googleapis.com/css?family=Spicy+Rice' rel='stylesheet' type='text/css'>

<script src="http://code.jquery.com/ui/1.9.0/jquery-ui.js"></script>
<div id="block_wrapper">
    <div id="preview" class="stickypanel">
        <span id="grooveshark_embed">
            <p>Click the Play button next to a song to have it load up here.</p>
        </span>
    </div>
    <div id="playlist_wrapper">

        <h1>Youth Dance Social Playlist Creator</h1>
        <input id="search" type="text" size="100" value="Search for a new song to add here"/>

        <div id="howto"><p>To find a song to add to the playlist, type it in the search box above and press enter.</p>
        </div>
        <div class="loading"><img src="<?php echo $blockdir?>/images/ajax-loader.gif"/></div>
        <div id="results_wrapper"></div>
        <div id="playlist"></div>
    </div>
</div>
<script>
    var clear_search = true;
    setInterval(update, 10000);
    var stickyPanelSettings = {
        // Use this to set the top margin of the detached panel.
        topPadding: 0,

        // This class is applied when the panel detaches.
        afterDetachCSSClass: "",

        // When set to true the space where the panel was is kept open.
        savePanelSpace: false,

        // Event fires when panel is detached
        // function(detachedPanel, panelSpacer){....}
        onDetached: null,

        // Event fires when panel is reattached
        // function(detachedPanel){....}
        onReAttached: null,

        // Set this using any valid jquery selector to
        // set the parent of the sticky panel.
        // If set to null then the window object will be used.
        parentSelector: null
    };

    function update() {
        $("#playlist").load("<?php echo $toolsdir?>/playlist.php");
    }


    $(document).ready(function () {
        $("#playlist").load("<?php echo $toolsdir?>/playlist.php", function (data) {
            $(".loading").hide();
        });
        $("#preview").stickyPanel(stickyPanelSettings);
    });

    $("#search").focusin(function () {
        if (clear_search) {
            clear_search = false;
            $("#search").attr("value", "");
            $("#search").css("color", "#000000");


        }

    });

    $("#search").keypress(function (event) {
        var keycode = (event.keyCode ? event.keyCode : event.which);
        if (keycode == '13') {
            $(".loading").show();
            $("#howto").html("<p>Now click the row of the song you want to add</p>");
            $("#results_wrapper").load("<?php echo $toolsdir?>/search.php", {query: $("#search").val()}, function (data) {
                $(".loading").hide();
            });
        }

    });
</script>

