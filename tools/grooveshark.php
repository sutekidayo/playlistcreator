<?php
/**
 * Created by JetBrains PhpStorm.
 * User: SaiKo
 * Date: 11/4/12
 * Time: 4:18 PM
 * To change this template use File | Settings | File Templates.
 */

?>





<object width="250" height="40" classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000" id="gsSong2183054087"
        name="gsSong2183054087">
    <param name="movie" value="http://grooveshark.com/songWidget.swf"/>
    <param name="wmode" value="window"/>
    <param name="allowScriptAccess" value="always"/>
    <param name="flashvars" value="hostname=grooveshark.com&songID=<?php echo $_POST["songID"]?>&style=metal&p=1"/>
    <object type="application/x-shockwave-flash" data="http://grooveshark.com/songWidget.swf" width="250"
            height="40">
        <param name="wmode" value="window"/>
        <param name="allowScriptAccess" value="always"/>
        <param name="flashvars" value="hostname=grooveshark.com&songID=<?php echo $_POST["songID"]?>&style=metal&p=1"/>
    </object>
</object>

