<?php
require_once('begin.file.php');

define('PATH', '/home/a6248919/public_html/Ligue12010/images/avatars');
 
function destroyUnusedAvatars($dir) {
    global $_databaseObject;
    $mydir = opendir($dir);
    while(false !== ($file = readdir($mydir))) {
        if($file != "." && $file != "..") {
            //chmod($dir.$file, 0777);
            if(!is_dir($dir.$file)) {
              $sql = "SELECT AvatarName FROM players WHERE ";
              $sql .= "AvatarName='".mysql_real_escape_string($file)."'";

              $_databaseObject->queryPerf($sql,"Check if avatar file is linked to a user");
              if($_databaseObject->num_rows()==0) 
              {
                echo "<br/><span style='color:red;'>$dir/$file will be deleted!</span>";
                unlink($dir."/".$file) or DIE("couldn't delete $dir$file<br />");
              }
              else {
                  echo "<br/>$dir/$file will be kept!";
              }
            }
            else {
              echo "<br/>It is a folder.";
            }
        }
    }
    closedir($mydir);
}
destroyUnusedAvatars(PATH);
echo 'all done.';

require_once('end.file.php');

?>