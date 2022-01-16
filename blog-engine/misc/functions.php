<?php
function format($text) {
  $text  = "<p>" . $text . "</p>";
  $search = array("\r", "\n\n", "\n");
  $replace = array("","</p><p>", "<br />");
  $text = str_replace($search, $replace, $text);
  $text = stripslashes($text);
  return $text;
}

function makerssfeed() {
  // set file to write
  $filename =  $_SERVER["DOCUMENT_ROOT"] . "/shelves/webarticles/blog-engine/index.xml";
  
  // open file
  $fh = @fopen($filename, "w");
  
  if($fh) {
    $rssfile = "<rss version=\"2.0\">
	<channel>
  <title>All By My Shelves</title>
  <link>http://www.myoldandnewshelves.com</link>
  <description>Book Reviews on the Tiles in My Old & New Shelves</description>
  <language>en-us</language>";

    // pull blogs from database
    $sql = "SELECT post_id, title, summary, DATE_FORMAT(postdate, '%a, %d %b %Y %T GMT') as pubdate FROM posts ORDER BY postdate DESC LIMIT 10";
    $result = mysql_query($sql);
    
    if ($mypost = mysql_fetch_array($result)) {
      do {
        $post_id = $mypost["post_id"];
        $pubdate = $mypost["pubdate"];
        $summary = format($mypost["summary"]);
        $title = $mypost["title"];
        $title = strip_tags($title);
        $title = htmlentities($title);
    
        $rssfile .= "    <item>\n";
        $rssfile .= "      <pubDate>$pubdate</pubDate>\n";
        $rssfile .= "      <title>$title</title>\n";
        $rssfile .= "      <link>http://www.electbride.com/discboard/post.php?post_id=$post_id</link>\n";
        $rssfile .= "      <description><![CDATA[$summary]]></description>\n";
        $rssfile .= "    </item>\n";
      } while ($mypost = mysql_fetch_array($result));
    }
    
    $rssfile .="  </channel>
</rss>";
    
    // write to file
    $fw = @fwrite($fh, $rssfile);
    if (!$fw) {
      $message = "Could not write to the file $filename";
    } else {
      $message = "RSS file updated.";
    }
    
    // close file
    fclose($fh);
  } else {
    $message = "Could not open file $filename";
  }
  return $message;
}
?>
