      SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
      SET time_zone = "+00:00";

      /*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
      /*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
      /*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
      /*!40101 SET NAMES utf8 */;


      CREATE TABLE IF NOT EXISTS `ads` (
        `id` int(10) NOT NULL AUTO_INCREMENT,
        `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
        `code` text COLLATE utf8_unicode_ci NOT NULL,
        PRIMARY KEY (`id`)
      ) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=12 ;

      CREATE TABLE IF NOT EXISTS `agffeed` (
        `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
        `uid` int(11) unsigned NOT NULL,
        `title` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
        `controls` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
        `instructions` text COLLATE utf8_unicode_ci NOT NULL,
        `description` text COLLATE utf8_unicode_ci NOT NULL,
        `category` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
        `thumbnail` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
        `medthumb` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
        `lgthumb` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
        `installdate` varchar(25) COLLATE utf8_unicode_ci NOT NULL,
        `file` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
        `zip` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
        `game_url` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
        `screen1` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
        `screen2` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
        `review` text COLLATE utf8_unicode_ci NOT NULL,
        `width` int(11) NOT NULL,
        `height` int(11) NOT NULL,
        `rating` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
        `ads` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
        `hsapi` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
        `keywords` text COLLATE utf8_unicode_ci NOT NULL,
        `installed` char(1) COLLATE utf8_unicode_ci NOT NULL DEFAULT '0',
        `hidden` tinyint(1) NOT NULL DEFAULT '0',
        PRIMARY KEY (`id`),
        KEY `uid` (`uid`)
      ) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1747 ;

      CREATE TABLE IF NOT EXISTS `avatars` (
        `userid` int(11) NOT NULL,
        `avatar` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
        KEY `userid` (`userid`,`avatar`)
      ) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

      CREATE TABLE IF NOT EXISTS `categories` (
        `id` int(11) NOT NULL AUTO_INCREMENT,
        `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
        `active` varchar(3) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
        `order` char(3) COLLATE utf8_unicode_ci NOT NULL DEFAULT '0',
        `parent` varchar(3) COLLATE utf8_unicode_ci NOT NULL DEFAULT 'yes',
        `home` varchar(3) COLLATE utf8_unicode_ci NOT NULL DEFAULT 'yes',
        `desc` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
        `pid` varchar(3) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
        PRIMARY KEY (`id`)
      ) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=11 ;

      CREATE TABLE IF NOT EXISTS `comments` (
        `id` int(11) NOT NULL AUTO_INCREMENT,
        `gameid` int(11) NOT NULL DEFAULT '0',
        `ipaddress` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
        `comment` text COLLATE utf8_unicode_ci NOT NULL,
        `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
        PRIMARY KEY (`id`)
      ) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

      CREATE TABLE IF NOT EXISTS `contact` (
        `id` int(11) NOT NULL AUTO_INCREMENT,
        `name` varchar(255) CHARACTER SET utf8 COLLATE utf8_bin DEFAULT NULL,
        `email` varchar(100) CHARACTER SET utf8 COLLATE utf8_bin DEFAULT NULL,
        `message` text CHARACTER SET utf8 COLLATE utf8_bin,
        `created_date` int(11) DEFAULT NULL,
        PRIMARY KEY (`id`)
      ) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

      CREATE TABLE IF NOT EXISTS `downgames` (
        `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
        `title` text COLLATE utf8_unicode_ci NOT NULL,
        `description` text COLLATE utf8_unicode_ci NOT NULL,
        `thumbnail` text COLLATE utf8_unicode_ci NOT NULL,
        `file` text COLLATE utf8_unicode_ci NOT NULL,
        `mochi` text COLLATE utf8_unicode_ci,
        `downloadtimes` int(10) NOT NULL,
        `mochidownloads` int(10) NOT NULL,
        PRIMARY KEY (`id`)
      ) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

      CREATE TABLE IF NOT EXISTS `favourite` (
        `userid` int(10) NOT NULL DEFAULT '0',
        `gameid` int(11) NOT NULL DEFAULT '0',
        KEY `userid` (`userid`),
        KEY `gameid` (`gameid`)
      ) ENGINE=MyISAM DEFAULT CHARSET=latin1;

      CREATE TABLE IF NOT EXISTS `featuredgames` (
        `gameid` int(11) unsigned NOT NULL,
        `add_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
        PRIMARY KEY (`gameid`)
      ) ENGINE=MyISAM DEFAULT CHARSET=utf8;

      CREATE TABLE IF NOT EXISTS `fgdfeed` (
        `id` int(11) NOT NULL AUTO_INCREMENT,
        `uuid` varchar(55) COLLATE utf8_unicode_ci NOT NULL,
        `title` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
        `description` varchar(512) COLLATE utf8_unicode_ci NOT NULL,
        `tags` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
        `gamefile` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
        `thumbfile` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
        `width` int(11) unsigned NOT NULL,
        `height` int(11) unsigned NOT NULL,
        `categories` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
        `mature` tinyint(3) unsigned NOT NULL DEFAULT '0',
        `hasads` tinyint(3) unsigned NOT NULL DEFAULT '0',
        `multiplayer` tinyint(3) unsigned NOT NULL DEFAULT '0',
        `featured` tinyint(3) unsigned NOT NULL DEFAULT '0',
        `highscores` tinyint(3) unsigned NOT NULL DEFAULT '0',
        `mobileready` tinyint(3) unsigned NOT NULL DEFAULT '0',
        `installed` tinyint(3) unsigned NOT NULL DEFAULT '0',
        `hidden` tinyint(1) NOT NULL DEFAULT '0',
        PRIMARY KEY (`id`),
        KEY `uuid` (`uuid`)
      ) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=28824 ;

      CREATE TABLE IF NOT EXISTS `fogfeed` (
        `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
        `uid` int(11) unsigned NOT NULL,
        `title` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
        `controls` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
        `updated` varchar(25) COLLATE utf8_unicode_ci NOT NULL,
        `game_url` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
        `description` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
        `category` int(11) NOT NULL DEFAULT '7',
        `small_thumbnail_url` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
        `med_thumbnail_url` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
        `large_thumbnail_url` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
        `created` varchar(25) COLLATE utf8_unicode_ci NOT NULL,
        `swf_file` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
        `width` int(11) NOT NULL,
        `height` int(11) NOT NULL,
        `installed` char(1) COLLATE utf8_unicode_ci NOT NULL DEFAULT '0',
        `hidden` tinyint(1) NOT NULL DEFAULT '0',
        PRIMARY KEY (`id`),
        KEY `uid` (`uid`)
      ) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=496 ;

      CREATE TABLE IF NOT EXISTS `forumcats` (
        `id` int(11) NOT NULL AUTO_INCREMENT,
        `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
        `active` varchar(3) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
        `order` char(3) COLLATE utf8_unicode_ci NOT NULL DEFAULT '0',
        `parent` varchar(3) COLLATE utf8_unicode_ci NOT NULL DEFAULT 'yes',
        `home` varchar(3) COLLATE utf8_unicode_ci NOT NULL DEFAULT 'yes',
        `desc` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
        `pid` varchar(3) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
        PRIMARY KEY (`id`)
      ) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=7 ;

      CREATE TABLE IF NOT EXISTS `forumposts` (
        `id` int(8) NOT NULL AUTO_INCREMENT,
        `text` text COLLATE utf8_unicode_ci NOT NULL,
        `date` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
        `topic` int(8) NOT NULL,
        `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
        PRIMARY KEY (`id`)
      ) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

      CREATE TABLE IF NOT EXISTS `forumtopics` (
        `id` int(8) NOT NULL AUTO_INCREMENT,
        `subject` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
        `cat` int(8) NOT NULL,
        `date` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
        `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
        `text` text COLLATE utf8_unicode_ci NOT NULL,
        `views` int(11) NOT NULL DEFAULT '0',
        `lastupdate` int(11) unsigned NOT NULL,
        PRIMARY KEY (`id`),
        KEY `cat` (`cat`)
      ) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

      CREATE TABLE IF NOT EXISTS `gameque` (
        `source` varchar(12) COLLATE utf8_unicode_ci NOT NULL,
        `sourceid` int(11) NOT NULL,
        `title` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
        `thumb` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
        `order` int(11) unsigned NOT NULL DEFAULT '0',
        KEY `source` (`source`),
        KEY `sourceid` (`sourceid`)
      ) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

      CREATE TABLE IF NOT EXISTS `games` (
        `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
        `title` text COLLATE utf8_unicode_ci NOT NULL,
        `description` text COLLATE utf8_unicode_ci NOT NULL,
        `instructions` text COLLATE utf8_unicode_ci NOT NULL,
        `keywords` text COLLATE utf8_unicode_ci NOT NULL,
        `file` text COLLATE utf8_unicode_ci NOT NULL,
        `height` int(11) NOT NULL DEFAULT '0',
        `width` int(11) NOT NULL DEFAULT '0',
        `category` int(11) NOT NULL DEFAULT '0',
        `plays` int(11) NOT NULL DEFAULT '0',
        `code` text COLLATE utf8_unicode_ci NOT NULL,
        `type` enum('IMAGE','MOV','MPG','AVI','FLV','WMV','SWF','DCR','UNITY','YOUTUBE','CustomCode') COLLATE utf8_unicode_ci NOT NULL DEFAULT 'SWF',
        `source` enum('OTHER','FGD','FOG','KONGREGATE','AGF','MGF') COLLATE utf8_unicode_ci NOT NULL DEFAULT 'OTHER',
        `sourceid` int(11) unsigned NOT NULL,
        `thumbnail` text COLLATE utf8_unicode_ci NOT NULL,
        --`ismochi` int(10) NOT NULL DEFAULT '0',
        --`thumbnail_200` varchar(255) CHARACTER SET utf8 NOT NULL,
        --`screen1` varchar(255) CHARACTER SET utf8 NOT NULL,
        --`screen2` varchar(255) CHARACTER SET utf8 NOT NULL,
        --`screen3` varchar(255) CHARACTER SET utf8 NOT NULL,
        --`screen4` varchar(255) CHARACTER SET utf8 NOT NULL,
        --`review` text CHARACTER SET utf8 NOT NULL,
        `active` tinyint(4) NOT NULL DEFAULT '1',
        PRIMARY KEY (`id`),
        KEY `source` (`source`)
      ) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=12 ;

      CREATE TABLE IF NOT EXISTS `kongregate` (
        `id` int(11) NOT NULL AUTO_INCREMENT,
        `kong_id` int(11) NOT NULL,
        `title` varchar(256) COLLATE utf8_unicode_ci NOT NULL,
        `file` varchar(512) COLLATE utf8_unicode_ci NOT NULL,
        `thumbnail` varchar(512) COLLATE utf8_unicode_ci NOT NULL,
        `width` int(11) NOT NULL,
        `height` int(11) NOT NULL,
        `category` varchar(64) COLLATE utf8_unicode_ci NOT NULL,
        `description` text COLLATE utf8_unicode_ci NOT NULL,
        `rating` float NOT NULL,
        `developer` varchar(512) COLLATE utf8_unicode_ci NOT NULL,
        `installed` tinyint(4) NOT NULL DEFAULT '0',
        `hidden` tinyint(1) NOT NULL DEFAULT '0',
        PRIMARY KEY (`id`)
      ) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=436 ;

      CREATE TABLE IF NOT EXISTS `links` (
        `id` int(11) NOT NULL AUTO_INCREMENT,
        `url` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
        `text` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
        `description` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
        `in` int(11) NOT NULL DEFAULT '0',
        `out` int(11) NOT NULL DEFAULT '0',
        `approved` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT 'no',
        `reciprocal` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
        `email` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
        PRIMARY KEY (`id`)
      ) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=5 ;

      CREATE TABLE IF NOT EXISTS `memberscomments` (
        `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
        `userid` int(11) NOT NULL,
        `ipaddress` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
        `comment` text COLLATE utf8_unicode_ci NOT NULL,
        `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
        PRIMARY KEY (`id`),
        KEY `name` (`name`)
      ) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

      CREATE TABLE IF NOT EXISTS `membersonline` (
        `id` int(11) NOT NULL AUTO_INCREMENT,
        `memberid` int(11) NOT NULL,
        `timeactive` int(11) NOT NULL,
        PRIMARY KEY (`id`),
        UNIQUE KEY `memberid` (`memberid`)
      ) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=6 ;

      CREATE TABLE IF NOT EXISTS `mgffeed` (
        `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
        `uid` int(11) unsigned NOT NULL,
        `title` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
        `controls` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
        `instructions` text COLLATE utf8_unicode_ci NOT NULL,
        `description` text COLLATE utf8_unicode_ci NOT NULL,
        `category` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
        `thumbnail` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
        `medthumb` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
        `lgthumb` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
        `installdate` varchar(25) COLLATE utf8_unicode_ci NOT NULL,
        `file` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
        `zip` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
        `game_url` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
        `screen1` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
        `screen2` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
        `review` text COLLATE utf8_unicode_ci NOT NULL,
        `width` int(11) NOT NULL,
        `height` int(11) NOT NULL,
        `rating` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
        `ads` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
        `hsapi` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
        `keywords` text COLLATE utf8_unicode_ci NOT NULL,
        `installed` char(1) COLLATE utf8_unicode_ci NOT NULL DEFAULT '0',
        `hidden` tinyint(1) NOT NULL DEFAULT '0',
        PRIMARY KEY (`id`),
        KEY `uid` (`uid`)
      ) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

      CREATE TABLE IF NOT EXISTS `news` (
        `id` int(11) NOT NULL AUTO_INCREMENT,
        `news_text` text COLLATE utf8_unicode_ci NOT NULL,
        `edit` int(11) DEFAULT '0',
        `date` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
        `topic` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
        PRIMARY KEY (`id`)
      ) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=2 ;

      CREATE TABLE IF NOT EXISTS `newsblog` (
        `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
        `username` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
        `newsid` int(11) unsigned NOT NULL,
        `ipaddress` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
        `comment` text COLLATE utf8_unicode_ci NOT NULL,
        `timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
        PRIMARY KEY (`id`),
        KEY `username` (`username`),
        KEY `newsid` (`newsid`)
      ) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

      CREATE TABLE IF NOT EXISTS `notifydown` (
        `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
        `email` text,
        PRIMARY KEY (`id`)
      ) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

      CREATE TABLE IF NOT EXISTS `ratingsbar` (
        `id` int(11) NOT NULL AUTO_INCREMENT,
        `total_votes` int(11) NOT NULL DEFAULT '0',
        `total_value` int(11) NOT NULL DEFAULT '0',
        `used_ips` longtext,
        PRIMARY KEY (`id`)
      ) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=9 ;

      CREATE TABLE IF NOT EXISTS `settings` (
        `id` int(11) NOT NULL AUTO_INCREMENT,
        `main` int(11) NOT NULL DEFAULT '1',
        `gperpage` int(11) NOT NULL DEFAULT '15',
        `numbgames` int(11) NOT NULL DEFAULT '0',
        `gamesort` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT 'newest',
        `seolink` varchar(3) COLLATE utf8_unicode_ci NOT NULL DEFAULT 'no',
        `seo` varchar(3) COLLATE utf8_unicode_ci NOT NULL DEFAULT 'no',
        `approvelinks` varchar(3) COLLATE utf8_unicode_ci NOT NULL DEFAULT 'no',
        `numblinks` int(11) NOT NULL DEFAULT '10',
        `version` varchar(12) COLLATE utf8_unicode_ci NOT NULL DEFAULT '2.6',
        `theme` varchar(255) CHARACTER SET utf8 NOT NULL DEFAULT 'minix_26',
        `skin` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT 'arcadegames',
        `password` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
        `userecaptcha` varchar(3) COLLATE utf8_unicode_ci NOT NULL DEFAULT 'no',
        `lightbox` varchar(3) COLLATE utf8_unicode_ci NOT NULL DEFAULT 'no',
        `email` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
        `disabled` char(3) COLLATE utf8_unicode_ci NOT NULL DEFAULT 'no',
        `regclosed` char(3) COLLATE utf8_unicode_ci NOT NULL DEFAULT 'no',
        `fb_app_id` varchar(25) COLLATE utf8_unicode_ci NOT NULL,
        `fb_app_secret` varchar(45) COLLATE utf8_unicode_ci NOT NULL,
        `tw_app_id` varchar(30) COLLATE utf8_unicode_ci NOT NULL,
        `tw_app_secret` varchar(55) COLLATE utf8_unicode_ci NOT NULL,
        `cachelife` int(11) NOT NULL DEFAULT '60',
        `siteurl` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
        `sitepath` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
        `sitename` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
        `slogan` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
        `metades` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
        `metakeywords` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
        `jobs` text COLLATE utf8_unicode_ci NOT NULL,
        PRIMARY KEY (`id`)
      ) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=2 ;

      CREATE TABLE IF NOT EXISTS `stats` (
        `id` int(10) NOT NULL AUTO_INCREMENT,
        `name` varchar(255) NOT NULL DEFAULT '',
        `numbers` int(10) NOT NULL DEFAULT '0',
        PRIMARY KEY (`id`)
      ) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=5 ;

      CREATE TABLE IF NOT EXISTS `tellafriend` (
        `id` int(11) NOT NULL AUTO_INCREMENT,
        `name` varchar(255) CHARACTER SET utf8 COLLATE utf8_bin DEFAULT NULL,
        `friendsname` varchar(255) CHARACTER SET utf8 COLLATE utf8_bin DEFAULT NULL,
        `email` varchar(100) CHARACTER SET utf8 COLLATE utf8_bin DEFAULT NULL,
        `friendsemail` varchar(100) CHARACTER SET utf8 COLLATE utf8_bin DEFAULT NULL,
        `message` text CHARACTER SET utf8 COLLATE utf8_bin,
        `created_date` int(11) DEFAULT NULL,
        PRIMARY KEY (`id`)
      ) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

      CREATE TABLE IF NOT EXISTS `user` (
        `id` int(11) NOT NULL AUTO_INCREMENT,
        `username` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
        `password` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
        `repeatpassword` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
        `name` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
        `useavatar` tinyint(1) NOT NULL DEFAULT '0',
        `avatarfile` varchar(75) COLLATE utf8_unicode_ci NOT NULL,
        `email` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
        `website` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
        `plays` int(11) NOT NULL DEFAULT '0',
        `date` int(10) NOT NULL DEFAULT '0',
        `location` varchar(75) COLLATE utf8_unicode_ci NOT NULL,
        `aboutme` text COLLATE utf8_unicode_ci NOT NULL,
        -- `job` varchar(75) COLLATE utf8_unicode_ci NOT NULL,
        `points` int(11) NOT NULL DEFAULT '0',
        `endban` int(10) DEFAULT '0',
        `oauth_uid` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
        `oauth_provider` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
        `twitter_oauth_token` varchar(200) COLLATE utf8_unicode_ci NOT NULL,
        `twitter_oauth_token_secret` varchar(200) COLLATE utf8_unicode_ci NOT NULL,
        `randomkey` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
        `activated` tinyint(1) NOT NULL,
        --`gender` varchar(5) COLLATE utf8_unicode_ci NOT NULL,
        --`birthday` int(11) DEFAULT NULL,
        `hobbies` text COLLATE utf8_unicode_ci NOT NULL,
        `posts` int(11) NOT NULL DEFAULT '0',
        `topics` int(11) NOT NULL DEFAULT '0',
        `totalposts` int(11) NOT NULL DEFAULT '0',
        `shloc` char(6) COLLATE utf8_unicode_ci NOT NULL DEFAULT 'show',
        `sheml` char(6) COLLATE utf8_unicode_ci NOT NULL DEFAULT 'show',
        `shname` char(6) COLLATE utf8_unicode_ci NOT NULL DEFAULT 'show',
        `shhobs` char(6) COLLATE utf8_unicode_ci NOT NULL DEFAULT 'show',
        `shabout` char(6) COLLATE utf8_unicode_ci NOT NULL DEFAULT 'show',
        `deact` char(6) COLLATE utf8_unicode_ci NOT NULL DEFAULT 'show',
        `cmtsdisabled` char(6) COLLATE utf8_unicode_ci NOT NULL DEFAULT 'show',
        `passreset` int(11) NOT NULL,
        PRIMARY KEY (`id`)
      ) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=2 ;

      CREATE TABLE IF NOT EXISTS `useronline` (
        `id` int(10) NOT NULL AUTO_INCREMENT,
        `ip` varchar(15) NOT NULL DEFAULT '',
        `timestamp` varchar(15) NOT NULL DEFAULT '',
        `agent` varchar(256) NOT NULL DEFAULT 'human',
        PRIMARY KEY (`id`),
        UNIQUE KEY `id` (`id`)
      ) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=126 ;

      /*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
      /*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
      /*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
