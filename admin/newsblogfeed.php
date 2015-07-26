<div id="center-column">
<div class="top-bar">
				<a href="index.php?act=newsblogfeed" title="RSS Feed" class="button">News Feed</a>
				<h1>Cpanel - News Feed</h1>
				<div class="breadcrumbs"><a href="index.php?act=sitemap" title="Sitemap">Sitemap</a> / <a href="index.php?act=rssfeed" title="RSS Feed">RSS Feed</a> / <a href="index.php?act=newsblogfeed" title="RSS Feed">News Blog RSS Feed</a></div>
			</div><br />
		  <div class="select-bar">
		    <label>
		    <h3>Create Blog Feed</h3>
		    </label>
		  </div>
    <div align="center">
        <a href="index.php?act=newsblogfeed&state=create">Click here to generate an RSS file for news blog</a><br>
        <?php
        if(isset($_GET['state'])) {
		switch($_GET['state']){
            case 'done':
                echo'<center><font size="3" color="#00CC33"><b>RSS file done</b></font><br/><br/>';
				echo 'New blog feed at '.$setting['siteurl'].'newsblogfeed.xml<br/><br/>';
				echo 'All blog feed at '.$setting['siteurl'].'news-blog-feed.xml</center>';
                break;
            case 'create':
              echo'<center><font size="3" color="#FF0000"><b>Creating RSS file...</b></font></center><br><br>';
				$rrsfile = '../newsblogfeed.xml';
				ob_start();
				print "<?xml version=\"1.0\" encoding=\"ISO-8859-1\"?>\n";
				print ""."<rss version=\"2.0\">\n";
				print ""."<channel>\n";
				print ""."<title>" . $setting['sitename'] . "</title>\n";
				print ""."<link>" . $setting['siteurl'] . "</link>\n";
				
				print ""."<ttl>600</ttl>\n";
				print ""."<description>News Blog Rss Feed.</description>\n";
				print ""."<copyright>".date("Y")." " . $setting['sitename'] . " All rights reserved.</copyright>\n";    
				$query = yasDB_select("SELECT * FROM news order by id DESC LIMIT 40",false);
				while($row = $query->fetch_array(MYSQLI_ASSOC)) {
				   $ID = $row['id'];
				   $date = $row['date'];
				   $topic = $row['topic'];
				   $news = $row['news_text'];
	               $rowurl = $row['news_text'];
                   $rowurl = str_replace(" ", "-", $rowurl);
                   $rowurl = str_replace("'", "_", $rowurl);
                   $rowurl = str_replace('"', "_", $rowurl);
                   $rowurl = str_replace('/', "_", $rowurl);
                   $rowurl = str_replace("\\", "_", $rowurl);
                   $rowurl = rawurlencode($rowurl);
				   if ( $setting['seo']=='yes' ) {
				   $newslink = $setting['siteurl'].'shownews/'.$ID.'.html';
				   } else {
				   $newslink = $setting['siteurl'] . 'index.php?act=shownews&id='.$ID;
				   }
					echo ""."<item>\n";

					echo ""."<title><![CDATA[" . $topic ."]]></title>\n";
					echo ""."<link><![CDATA[" . $newslink ." ]]></link>\n";				
					echo ""."<description><![CDATA[ $news  ]]></description>\n";
					echo ""."</item>\n";
				}
				echo "</channel>\n";
				echo "</rss>";             
				$query->close();
				
				$fopen = fopen($rrsfile, 'w');
				fwrite($fopen, ob_get_contents());
				fclose($fopen);
				echo '<META http-equiv="refresh" content="2; URL=' . $setting['siteurl'] . 'admin/newsblogfeed.php?act=done">';
				ob_end_flush();
				$rrsfile = '../news-blog-feed.xml';
				ob_start();
				print "<?xml version=\"1.0\" encoding=\"ISO-8859-1\"?>\n";
				print ""."<rss version=\"2.0\">\n";
				print ""."<channel>\n";
				print ""."<title>" . $setting['sitename'] . "</title>\n";
				print ""."<link>" . $setting['siteurl'] . "</link>\n";
				
				print ""."<ttl>600</ttl>\n";
				print ""."<description>News Blog Rss Feed.</description>\n";
				print ""."<copyright>".date("Y")." " . $setting['sitename'] . " All rights reserved.</copyright>\n";   
				$query = yasDB_select("SELECT * FROM news ORDER BY id DESC");
				while($row = $query->fetch_array(MYSQLI_ASSOC)) {
				   $ID = $row['id'];
				   $date = $row['date'];
				   $topic = $row['topic'];
				   $news = $row['news_text'];
	               $rowurl = $row['news_text'];
                   $rowurl = str_replace(" ", "-", $rowurl);
                   $rowurl = str_replace("'", "_", $rowurl);
                   $rowurl = str_replace('"', "_", $rowurl);
                   $rowurl = str_replace('/', "_", $rowurl);
                   $rowurl = str_replace("\\", "_", $rowurl);
                   $rowurl = rawurlencode($rowurl);
				   if ( $setting['seo']=='yes' ) {
				   $newslink = $setting['siteurl'].'shownews/'.$ID.'.html';
				   } else {
				   $newslink = $setting['siteurl'] . 'index.php?act=shownews&id='.$ID;
				   }
					echo ""."<item>\n";
					echo ""."<title><![CDATA[" . $topic ."]]></title>\n";
					echo ""."<link><![CDATA[" . $newslink . "]]></link>\n";
					echo ""."<description><![CDATA[ $news  ]]></description>\n";
					echo ""."</item>\n";
				}
				$query->close();
				
				echo "</channel>\n";
				echo "</rss>";             
				$fopen = fopen($rrsfile, 'w');
				fwrite($fopen, ob_get_contents());
				fclose($fopen);
				echo '<META http-equiv="refresh" content="2; URL=index.php?act=newsblogfeed&state=done">';
				ob_end_flush();
				break;
			default:
				break;
        }
		}
?>
    </div>
</div> 