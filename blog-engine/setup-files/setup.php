<?php

$db = mysql_connect("localhost", "root", "VictoriousMercy77") or die("Could not connect to database.");
mysql_select_db("blog",$db);

$sql = "CREATE TABLE IF NOT EXISTS `comments` (
  `comment_id` int(11) NOT NULL auto_increment,
  `post_id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `website` varchar(255) NOT NULL,
  `comment` text NOT NULL,
  `tstamp` timestamp NOT NULL default CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP,
  PRIMARY KEY  (`comment_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1;

CREATE TABLE IF NOT EXISTS `posts` (
  `post_id` int(11) NOT NULL auto_increment,
  `title` varchar(255) NOT NULL,
  `postdate` datetime NOT NULL,
  `summary` text NOT NULL,
  `post` text NOT NULL,
  `tstamp` timestamp NOT NULL default CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP,
  PRIMARY KEY  (`post_id`),
  KEY `idx_postdate` (`postdate`),
  KEY `idx_postid` (`post_id`),
  FULLTEXT KEY `idx_search` (`title`,`summary`,`post`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2;" ;

$result = @mysql_query($sql);

if(!$result) 
{
	echo "Setup didn't succeed..!";
}
else 
	echo 'Setup completed successfully...';

?>