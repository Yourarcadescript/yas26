<?php 
if ($_GET['act'] == 'settings') {
include "right_column/modrewrite.php";
include "right_column/file.php";
include "right_column/media.php";
include "right_column/code.php";
} elseif ($_GET['act'] == 'adddownloadgame') {
include "right_column/managedowngame.php";
} elseif ($_GET['act'] == 'managedowngame') {
include "right_column/managedowngame.php";
} elseif ($_GET['act'] == 'sitemap') {
include "right_column/sitemap.php";
} else {
include "right_column/stats.php";
include "right_column/arcadebannerclicks.php";
include "right_column/arcadejumper.php";
}?>