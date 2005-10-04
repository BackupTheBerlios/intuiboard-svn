-- phpMyAdmin SQL Dump
-- version 2.6.2-pl1
-- http://www.phpmyadmin.net
-- 
-- Host: localhost
-- Generation Time: Oct 04, 2005 at 01:35 PM
-- Server version: 4.0.22
-- PHP Version: 5.0.4
-- 
-- Database: `intuiboa_dev`
-- 

-- --------------------------------------------------------

-- 
-- Table structure for table `ib_caches`
-- 

CREATE TABLE IF NOT EXISTS ib_caches (
  c_id smallint(5) unsigned NOT NULL auto_increment,
  c_key varchar(32) NOT NULL default '',
  c_value text NOT NULL,
  c_updated int(10) unsigned NOT NULL default '0',
  PRIMARY KEY  (c_id),
  UNIQUE KEY key (c_key)
) TYPE=MyISAM AUTO_INCREMENT=3 ;

-- 
-- Dumping data for table `ib_caches`
-- 

INSERT INTO ib_caches (c_id, c_key, c_value, c_updated) VALUES (1, 'stats', 'a:3:{s:13:"total_members";i:1;s:12:"total_topics";i:2;s:13:"total_replies";i:1;}', 1123920429);
INSERT INTO ib_caches (c_id, c_key, c_value, c_updated) VALUES (2, 'config', 'a:10:{s:9:"site_name";s:11:"Breeze Home";s:8:"site_url";s:26:"http://www.breezeboard.com";s:10:"board_name";s:17:"Development Board";s:8:"base_url";s:40:"http://localhost/intuiboard/dev/current/";s:9:"image_url";s:53:"http://localhost/intuiboard/dev/current/cache/images/";s:12:"sess_max_age";s:2:"15";s:12:"single_forum";s:1:"0";s:10:"show_stats";s:1:"1";s:20:"stats_online_max_age";s:2:"15";s:13:"gzip_compress";s:1:"1";}', 1128384704);

-- --------------------------------------------------------

-- 
-- Table structure for table `ib_config`
-- 

CREATE TABLE IF NOT EXISTS ib_config (
  cf_id smallint(5) unsigned NOT NULL auto_increment,
  cf_key varchar(64) NOT NULL default '',
  cf_value varchar(255) NOT NULL default '',
  cf_title varchar(128) NOT NULL default '',
  cf_description text NOT NULL,
  cf_type varchar(32) NOT NULL default 'text',
  cf_group smallint(5) unsigned NOT NULL default '0',
  cf_blank tinyint(1) NOT NULL default '0',
  PRIMARY KEY  (cf_id),
  UNIQUE KEY cf_key (cf_key)
) TYPE=MyISAM AUTO_INCREMENT=11 ;

-- 
-- Dumping data for table `ib_config`
-- 

INSERT INTO ib_config (cf_id, cf_key, cf_value, cf_title, cf_description, cf_type, cf_group, cf_blank) VALUES (1, 'site_name', 'Breeze Home', 'Website Name', 'The name of your website, used for a link in the board header.', 'text', 1, 1);
INSERT INTO ib_config (cf_id, cf_key, cf_value, cf_title, cf_description, cf_type, cf_group, cf_blank) VALUES (2, 'site_url', 'http://www.breezeboard.com', 'Website URL', 'The URL of your website, used for a link in the board header.', 'text', 1, 1);
INSERT INTO ib_config (cf_id, cf_key, cf_value, cf_title, cf_description, cf_type, cf_group, cf_blank) VALUES (3, 'board_name', 'Development Board', 'Board Name', 'The name of this board.', 'text', 1, 0);
INSERT INTO ib_config (cf_id, cf_key, cf_value, cf_title, cf_description, cf_type, cf_group, cf_blank) VALUES (4, 'base_url', 'http://localhost/intuiboard/dev/current/', 'Board URL', 'The base URL of this board, this must be correct for the board links to be correct. eg. http://www.domain.com/forum/', 'text', 1, 0);
INSERT INTO ib_config (cf_id, cf_key, cf_value, cf_title, cf_description, cf_type, cf_group, cf_blank) VALUES (5, 'image_url', 'http://localhost/intuiboard/dev/current/cache/images/', 'Image URL', 'The URL for board images, will default to board_url/cache/images/. Use this is you want to store images at a different location.', 'text', 1, 1);
INSERT INTO ib_config (cf_id, cf_key, cf_value, cf_title, cf_description, cf_type, cf_group, cf_blank) VALUES (6, 'sess_max_age', '15', 'Session Expiration Time', 'The time(in minutes) which a session lasts.', 'text', 2, 0);
INSERT INTO ib_config (cf_id, cf_key, cf_value, cf_title, cf_description, cf_type, cf_group, cf_blank) VALUES (7, 'single_forum', '0', 'Function as a single forum?', 'Set this to a forum ID to make the board function as a single forum. ie. There are no forums/categories, just the topic listing of this forum on the board index.', 'text', 1, 1);
INSERT INTO ib_config (cf_id, cf_key, cf_value, cf_title, cf_description, cf_type, cf_group, cf_blank) VALUES (8, 'show_stats', '1', 'Show Board Stats', 'You can disable the board stats on the board index.', 'yes_no', 1, 0);
INSERT INTO ib_config (cf_id, cf_key, cf_value, cf_title, cf_description, cf_type, cf_group, cf_blank) VALUES (9, 'stats_online_max_age', '15', 'Maximum Online Time', 'The time(in minutes) for which a person is considered online, if there has been no activity in this time they are considered offline.', 'text', 1, 0);
INSERT INTO ib_config (cf_id, cf_key, cf_value, cf_title, cf_description, cf_type, cf_group, cf_blank) VALUES (10, 'gzip_compress', '1', 'GZip Compress HTML?', 'You can enable or disable GZip compression of HTML, this will save bandwidth but increase server load.', 'yes_no', 1, 0);

-- --------------------------------------------------------

-- 
-- Table structure for table `ib_forums`
-- 

CREATE TABLE IF NOT EXISTS ib_forums (
  f_id smallint(6) unsigned NOT NULL auto_increment,
  f_order smallint(5) unsigned NOT NULL default '0',
  f_name varchar(255) NOT NULL default '',
  f_description text NOT NULL,
  f_closed tinyint(1) unsigned NOT NULL default '0',
  f_redirect varchar(255) NOT NULL default '',
  f_perms text NOT NULL,
  f_topics smallint(5) unsigned NOT NULL default '0',
  f_replies smallint(5) unsigned NOT NULL default '0',
  f_last_topic_id smallint(6) unsigned NOT NULL default '0',
  f_last_topic_title varchar(255) NOT NULL default '',
  f_last_post_time int(10) NOT NULL default '0',
  f_last_poster_id smallint(6) NOT NULL default '0',
  f_last_poster_name varchar(255) NOT NULL default '',
  PRIMARY KEY  (f_id),
  KEY f_order (f_order)
) TYPE=MyISAM AUTO_INCREMENT=6 ;

-- 
-- Dumping data for table `ib_forums`
-- 

INSERT INTO ib_forums (f_id, f_order, f_name, f_description, f_closed, f_redirect, f_perms, f_topics, f_replies, f_last_topic_id, f_last_topic_title, f_last_post_time, f_last_poster_id, f_last_poster_name) VALUES (1, 2, 'Test Forum', 'This is a test forum.', 0, '', 'a:5:{s:5:"reply";s:3:"4|5";s:5:"start";s:3:"4|5";s:4:"view";s:3:"4|5";s:4:"list";s:3:"4|5";s:6:"attach";s:3:"4|5";}', 2, 1, 2, 'Another topic', 1121928258, 1, 'Michael');
INSERT INTO ib_forums (f_id, f_order, f_name, f_description, f_closed, f_redirect, f_perms, f_topics, f_replies, f_last_topic_id, f_last_topic_title, f_last_post_time, f_last_poster_id, f_last_poster_name) VALUES (2, 1, 'Test Forum 2', 'This is a 2nd test forum.', 0, '', 'a:5:{s:5:"reply";s:3:"4|5";s:5:"start";s:3:"4|5";s:4:"view";s:3:"4|5";s:4:"list";s:3:"4|5";s:6:"attach";s:3:"4|5";}', 14, 27, 136, 'What you listening to?', 2005, 124, 'Jim');
INSERT INTO ib_forums (f_id, f_order, f_name, f_description, f_closed, f_redirect, f_perms, f_topics, f_replies, f_last_topic_id, f_last_topic_title, f_last_post_time, f_last_poster_id, f_last_poster_name) VALUES (3, 3, 'Test Forum 3', 'This is a 3rd test forum.', 0, '', 'a:5:{s:5:"reply";s:3:"4|5";s:5:"start";s:3:"4|5";s:4:"view";s:3:"4|5";s:4:"list";s:3:"4|5";s:6:"attach";s:3:"4|5";}', 452, 1068, 162, 'New song', 2005, 4, 'Harry');

-- --------------------------------------------------------

-- 
-- Table structure for table `ib_groups`
-- 

CREATE TABLE IF NOT EXISTS ib_groups (
  g_id smallint(5) unsigned NOT NULL auto_increment,
  g_name varchar(255) NOT NULL default '',
  g_perms smallint(5) unsigned NOT NULL default '0',
  g_admin tinyint(1) unsigned NOT NULL default '0',
  g_banned tinyint(1) unsigned NOT NULL default '0',
  PRIMARY KEY  (g_id)
) TYPE=MyISAM AUTO_INCREMENT=6 ;

-- 
-- Dumping data for table `ib_groups`
-- 

INSERT INTO ib_groups (g_id, g_name, g_perms, g_admin, g_banned) VALUES (1, 'Banned', 1, 0, 1);
INSERT INTO ib_groups (g_id, g_name, g_perms, g_admin, g_banned) VALUES (2, 'Guests', 2, 0, 0);
INSERT INTO ib_groups (g_id, g_name, g_perms, g_admin, g_banned) VALUES (3, 'Validating', 3, 0, 0);
INSERT INTO ib_groups (g_id, g_name, g_perms, g_admin, g_banned) VALUES (4, 'Member', 4, 0, 0);
INSERT INTO ib_groups (g_id, g_name, g_perms, g_admin, g_banned) VALUES (5, 'Administrator', 5, 1, 0);

-- --------------------------------------------------------

-- 
-- Table structure for table `ib_members`
-- 

CREATE TABLE IF NOT EXISTS ib_members (
  m_id smallint(5) unsigned NOT NULL auto_increment,
  m_name varchar(255) NOT NULL default '',
  m_email varchar(255) NOT NULL default '',
  m_group smallint(5) unsigned NOT NULL default '0',
  m_pass_hash varchar(32) NOT NULL default '',
  m_pass_salt varchar(8) NOT NULL default '',
  m_posts int(10) unsigned NOT NULL default '0',
  m_signature text NOT NULL,
  m_avatar_location varchar(255) NOT NULL default '',
  m_avatar_size varchar(7) NOT NULL default '',
  m_avatar_type tinyint(1) unsigned NOT NULL default '0',
  m_tz_offset smallint(2) NOT NULL default '0',
  PRIMARY KEY  (m_id)
) TYPE=MyISAM PACK_KEYS=0 AUTO_INCREMENT=1 ;

-- 
-- Dumping data for table `ib_members`
-- 


-- --------------------------------------------------------

-- 
-- Table structure for table `ib_perms`
-- 

CREATE TABLE IF NOT EXISTS ib_perms (
  perm_id smallint(5) unsigned NOT NULL auto_increment,
  perm_name varchar(255) NOT NULL default '',
  PRIMARY KEY  (perm_id)
) TYPE=MyISAM AUTO_INCREMENT=6 ;

-- 
-- Dumping data for table `ib_perms`
-- 

INSERT INTO ib_perms (perm_id, perm_name) VALUES (1, 'Banned');
INSERT INTO ib_perms (perm_id, perm_name) VALUES (2, 'Guests');
INSERT INTO ib_perms (perm_id, perm_name) VALUES (3, 'Validating');
INSERT INTO ib_perms (perm_id, perm_name) VALUES (4, 'Members');
INSERT INTO ib_perms (perm_id, perm_name) VALUES (5, 'Administrators');

-- --------------------------------------------------------

-- 
-- Table structure for table `ib_posts`
-- 

CREATE TABLE IF NOT EXISTS ib_posts (
  p_id bigint(15) unsigned NOT NULL auto_increment,
  p_topic int(10) unsigned NOT NULL default '0',
  p_author_id int(10) unsigned NOT NULL default '0',
  p_author_name varchar(255) NOT NULL default '',
  p_post text NOT NULL,
  p_date int(10) unsigned NOT NULL default '0',
  p_emoticons tinyint(1) unsigned NOT NULL default '1',
  p_bbcode tinyint(1) unsigned NOT NULL default '1',
  PRIMARY KEY  (p_id),
  KEY date (p_date)
) TYPE=MyISAM AUTO_INCREMENT=5 ;

-- 
-- Dumping data for table `ib_posts`
-- 

INSERT INTO ib_posts (p_id, p_topic, p_author_id, p_author_name, p_post, p_date, p_emoticons, p_bbcode) VALUES (1, 2, 1, 'Michael', 'This is the first post!!!111!!oneone!', 1121922715, 1, 1);
INSERT INTO ib_posts (p_id, p_topic, p_author_id, p_author_name, p_post, p_date, p_emoticons, p_bbcode) VALUES (2, 2, 1, 'Michael', 'Test reply.', 1121928258, 1, 1);
INSERT INTO ib_posts (p_id, p_topic, p_author_id, p_author_name, p_post, p_date, p_emoticons, p_bbcode) VALUES (3, 1, 1, 'Michael', 'Lorem ipsum dolor sit amet, consectetuer adipiscing elit. Phasellus ullamcorper. Maecenas rhoncus. Morbi at nulla. Sed rutrum neque. Sed congue arcu quis ante. Phasellus ornare fringilla justo. Etiam at ligula. Etiam tortor nisl, interdum nec, fermentum et, elementum nec, velit. Vestibulum accumsan tellus quis orci. Sed ullamcorper pede ut risus. Nam faucibus convallis augue. Proin scelerisque leo vitae est. Nunc pulvinar nunc ut metus. Sed aliquam neque vel diam.\r\n\r\nDonec ultrices quam ut nunc. Aenean gravida purus ut erat. Praesent vel sem. Etiam ultrices dictum risus. Sed ac lectus. Ut gravida vehicula eros. Suspendisse interdum, lacus quis sollicitudin dapibus, neque ante rutrum est, sit amet blandit quam urna vel eros. Sed eu tellus vitae sem congue blandit. Donec consequat adipiscing dui. Cras mauris enim, tincidunt quis, luctus condimentum, mollis eu, odio. Donec faucibus.\r\n\r\nNam erat. Maecenas velit sapien, suscipit id, pharetra et, faucibus at, neque. Mauris nec mauris at velit venenatis malesuada. Aenean vel elit. Fusce eu nulla. Praesent sem. Cras dapibus posuere neque. Nunc quam nibh, pretium nec, fringilla at, molestie id, ipsum. Aliquam erat volutpat. Vivamus suscipit purus at sem. Nulla eu magna ac orci accumsan convallis. Phasellus libero. Mauris ultricies libero sed turpis lacinia mollis. Cras quis tellus.\r\n\r\nNam scelerisque, neque ac auctor molestie, libero risus laoreet dolor, vel dignissim metus diam vitae purus. Pellentesque nibh. Sed varius lobortis magna. Quisque iaculis luctus est. Nam nec augue. Integer rhoncus. Mauris vitae lectus eu lectus dictum pharetra. Sed mattis, leo sit amet eleifend sollicitudin, diam eros mollis justo, eu aliquam nunc libero vitae massa. Mauris lobortis purus a risus. Curabitur id urna. Sed tincidunt blandit arcu. Sed nec nibh. Aliquam erat volutpat. Morbi ut quam. In hac habitasse platea dictumst. Nunc adipiscing purus quis mauris. Curabitur eu diam at pede pretium porttitor. In tincidunt condimentum quam. Quisque eu nunc et tellus euismod porttitor.\r\n\r\nPraesent ut massa at diam viverra dignissim. Donec eget tellus. Morbi vulputate. Maecenas felis. Quisque quis quam. Aliquam in lacus. Nullam at odio. Aliquam erat volutpat. Proin velit orci, pellentesque quis, iaculis at, interdum ac, risus. Sed convallis enim non libero. Vivamus vehicula enim eu orci.\r\n\r\nVivamus nec sem. Integer mollis gravida nisl. Praesent fringilla, tortor ac lacinia scelerisque, tellus augue mollis felis, nec commodo est turpis non quam. Vivamus eget ipsum. Vestibulum sed metus et purus porta fringilla. In pulvinar magna nec urna. Nullam adipiscing urna. Donec interdum. Phasellus ligula. Quisque dictum massa eget libero. Curabitur eget leo aliquet nibh mattis placerat. Nunc non orci. Quisque diam augue, consectetuer ut, vestibulum non, interdum eget, velit. Quisque leo felis, feugiat vel, rhoncus id, semper quis, metus.\r\n\r\nCras facilisis lorem ac nibh. Duis pede. Suspendisse semper fermentum augue. Morbi fermentum feugiat ligula. Ut consectetuer erat quis nisl. Vivamus id urna malesuada tellus nonummy consectetuer. Suspendisse diam ipsum, nonummy sed, congue eget, tempus laoreet, massa. Sed porta sem vel tellus. Cras egestas vehicula mauris. Cras elit nisi, ornare nec, vulputate sed, ornare sed, magna. Mauris vehicula elementum orci. Pellentesque neque.\r\n\r\nInteger dui. Praesent ac metus ac justo auctor varius. Sed ante leo, malesuada ut, sodales quis, vestibulum non, urna. Mauris iaculis diam et nisl. Ut sit amet dui. Ut eget libero. Suspendisse semper interdum eros. Nullam metus lorem, imperdiet nec, euismod eu, molestie ac, sapien. Ut sit amet magna nec ante accumsan pellentesque. Nunc vel turpis sed tortor interdum pharetra. Sed faucibus egestas lorem. Vestibulum facilisis, enim quis ornare rhoncus, velit turpis suscipit risus, sagittis placerat velit turpis ut nisl. Sed cursus nisi a enim. Quisque aliquam, risus nec eleifend commodo, nibh lacus molestie nisi, nec convallis sem enim eu sem. Curabitur mi quam, placerat eu, tincidunt in, commodo lobortis, enim. Aliquam nec tellus.\r\n\r\nProin dapibus faucibus elit. Vestibulum metus. Sed in est. Sed gravida euismod sapien. Quisque feugiat quam in turpis. Nunc laoreet, sapien eget varius tristique, mauris lectus blandit leo, et malesuada ligula ligula sit amet ipsum. Sed lobortis elit. Vivamus cursus sodales sapien. Cum sociis natoque penatibus et magnis dis parturient montes, nascetur ridiculus mus. Class aptent taciti sociosqu ad litora torquent per conubia nostra, per inceptos hymenaeos. Morbi laoreet. Etiam condimentum velit id eros. Donec ac ligula. Praesent ullamcorper suscipit lacus. Mauris diam eros, porta suscipit, condimentum eget, aliquet eget, lorem. Curabitur pharetra diam vel lorem.\r\n\r\nDuis porta, nibh egestas laoreet lacinia, justo velit pulvinar justo, vitae malesuada ligula tortor quis nulla. Vestibulum accumsan rutrum elit. Quisque et risus at diam venenatis vestibulum. Fusce sed dui lobortis ligula varius auctor. Curabitur orci pede, pulvinar et, mattis non, fermentum sit amet, risus. In semper pede eu ipsum. Praesent tortor. Donec et leo. Aenean elementum urna in quam. Etiam placerat lorem et elit. Aenean odio est, congue porttitor, rutrum vitae, aliquet ullamcorper, magna. Phasellus sit amet mi.\r\n', 1127435372, 1, 1);
INSERT INTO ib_posts (p_id, p_topic, p_author_id, p_author_name, p_post, p_date, p_emoticons, p_bbcode) VALUES (4, 1, 1, 'Michael', 'Blah', 1127435372, 1, 1);

-- --------------------------------------------------------

-- 
-- Table structure for table `ib_sessions`
-- 

CREATE TABLE IF NOT EXISTS ib_sessions (
  s_id varchar(32) NOT NULL default '',
  s_data varchar(255) NOT NULL default '',
  s_age int(10) unsigned NOT NULL default '0',
  s_member_id int(10) unsigned NOT NULL default '0',
  UNIQUE KEY s_id (s_id)
) TYPE=HEAP;

-- 
-- Dumping data for table `ib_sessions`
-- 


-- --------------------------------------------------------

-- 
-- Table structure for table `ib_topics`
-- 

CREATE TABLE IF NOT EXISTS ib_topics (
  t_id int(10) unsigned NOT NULL auto_increment,
  t_forum smallint(5) unsigned NOT NULL default '0',
  t_title varchar(255) NOT NULL default '',
  t_description varchar(255) NOT NULL default '',
  t_author_id int(10) unsigned NOT NULL default '0',
  t_author_name varchar(255) NOT NULL default '',
  t_replies smallint(5) unsigned NOT NULL default '0',
  t_date int(10) unsigned NOT NULL default '0',
  t_first_post bigint(15) unsigned NOT NULL default '0',
  t_last_post_date int(10) unsigned NOT NULL default '0',
  t_last_post_author_id int(10) unsigned NOT NULL default '0',
  t_last_post_author_name varchar(255) NOT NULL default '',
  PRIMARY KEY  (t_id),
  KEY date (t_date),
  KEY last_post_date (t_last_post_date)
) TYPE=MyISAM AUTO_INCREMENT=3 ;

-- 
-- Dumping data for table `ib_topics`
-- 

INSERT INTO ib_topics (t_id, t_forum, t_title, t_description, t_author_id, t_author_name, t_replies, t_date, t_first_post, t_last_post_date, t_last_post_author_id, t_last_post_author_name) VALUES (1, 1, 'Test Topic', 'this is a test description', 1, 'Michael', 0, 1121893664, 0, 1121893664, 1, 'Michael');
INSERT INTO ib_topics (t_id, t_forum, t_title, t_description, t_author_id, t_author_name, t_replies, t_date, t_first_post, t_last_post_date, t_last_post_author_id, t_last_post_author_name) VALUES (2, 1, 'Another topic', 'yep...', 1, 'Michael', 1, 1121921042, 1, 1121928258, 1, 'Michael');
