<?php

namespace TechWilk\Rota;

use DateTime;

/*
    This file is part of Church Rota.

    Copyright (C) 2011 David Bunce, 2013 Benjamin Schmitt

    Church Rota is free software: you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation, either version 3 of the License, or
    (at your option) any later version.

    Church Rota is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with Church Rota.  If not, see <http://www.gnu.org/licenses/>.
*/

function executeDbSql($sql)
{
    if (!mysqli_query(db(), $sql)) {
        die('Error: '.mysqli_error(db()).', SQL: '.$sql);
    }
}

function updateDatabase()
{
    $sql = 'SELECT VERSION( ) AS mysqli_version';
    $result = mysqli_query(db(), $sql) or die('MySQL-Error: '.mysqli_error(db()));
    $dbv = mysqli_fetch_array($result, MYSQLI_ASSOC);
    $mysqli_version = $dbv['mysqli_version'];
    //echo $mysqli_version."<br>";

    $sql = "SHOW COLUMNS from settings like 'version'";
    $result = mysqli_query(db(), $sql) or die(mysqli_error(db()));
    $num_rows = mysqli_num_rows($result);

    if ($num_rows > 0) {
        $sql = 'SELECT version FROM settings';
        $result = mysqli_query(db(), $sql) or die(mysqli_error(db()));

        while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
            $version = $row['version'];
        }
    } else {
        $version = 'unknown';
    }

    switch ($version) {
        case 'unknown':
            executeDbSql('create table settings_bkp_orig as select * from settings');
            executeDbSql("alter table events add(deleted varchar(2) default '0')");
            executeDbSql("alter table eventPeople add(deleted varchar(2) default '0')");
            executeDbSql('alter table settings add(version varchar(20))');

            executeDbSql("update settings set version = '2.0.0'");
            //break;

        case '2.0.0':
            executeDbSql("update settings set version = '2.0.1'");

        case '2.0.1':
            executeDbSql("update settings set version = '2.0.2'");
        case '2.0.2':
            executeDbSql('create table settings_bkp2_0_2 as select * from settings');

            executeDbSql('alter table settings add(lang_locale varchar(20))');
            executeDbSql('alter table settings add(event_sorting_latest int(1))');
            executeDbSql('alter table settings add(snapshot_show_two_month int(1))');
            executeDbSql('alter table settings add(snapshot_reduce_skills_by_group int(1))');
            executeDbSql('alter table settings add(logged_in_show_snapshot_button int(1))');
            executeDbSql('alter table settings add(time_format_long varchar(50))');
            executeDbSql('alter table settings add(time_format_normal varchar(50))');
            executeDbSql('alter table settings add(time_format_short varchar(50))');
            executeDbSql('alter table settings add(users_start_with_myevents int(1))');
            executeDbSql('alter table settings add(time_zone varchar(50))');
            executeDbSql('alter table settings add(google_group_calendar varchar(100))');
            executeDbSql('alter table users modify email varchar(255)');
            executeDbSql('alter table settings add(overviewemail text NOT NULL)');
            executeDbSql("alter table users add(isOverviewRecipient char(2) NOT NULL DEFAULT '0')");
            executeDbSql('alter table groups add(short_name char(2))');

            executeDbSql("update settings set lang_locale = 'en_GB'");                     // de_DE
            executeDbSql('update settings set event_sorting_latest = 0');
            executeDbSql('update settings set snapshot_show_two_month = 0');
            executeDbSql('update settings set snapshot_reduce_skills_by_group = 0');
            executeDbSql('update settings set logged_in_show_snapshot_button = 0');
            executeDbSql("update settings set time_format_long = '%A, %B %e @ %I:%M %p'"); // de_DE: %A, %e. %B %Y, %R Uhr, KW%V
            executeDbSql("update settings set time_format_normal = '%m/%d/%y %I:%M %p'"); // de_DE: %d.%m.%Y %H:%M
            executeDbSql("update settings set time_format_short = '%a, <strong>%b %e</strong>, %I:%M %p'");              // de_DE: %a, <strong>%e. %b</strong>, KW%V
            executeDbSql("update settings set version = '2.1.0'");
            executeDbSql('update settings set users_start_with_myevents = 0');
            executeDbSql("update settings set time_zone = 'Europe/London'"); //de_DE: Europe/Berlin
            executeDbSql("update settings set google_group_calendar = ''");
            executeDbSql("update settings set overviewemail = 'Hello,\r\n\r\nIn this email you find the Rota for [MONTH] [YEAR].\r\n\r\n[OVERVIEW]\r\n\r\nPlease inform us as soon as possible, if you are not able to serve as scheduled.\r\n\r\nBe blessed.\r\nChurch Support Stuff'");

            notifyInfo(__FILE__, 'db-update='.$version.'->2.1.0', $_SESSION['userid']);
        case '2.1.0':
            executeDbSql('create table settings_bkp2_1_0 as select * from settings');
            executeDbSql('alter table settings add(group_sorting_name int(1))');
            executeDbSql("update settings set version = '2.1.1'");
            notifyInfo(__FILE__, 'db-update='.$version.'->2.1.1', $_SESSION['userid']);
        case '2.1.1':
            executeDbSql("update settings set version = '2.1.2'");
            notifyInfo(__FILE__, 'db-update='.$version.'->2.1.2', $_SESSION['userid']);
        case '2.1.2':
            executeDbSql("alter table settings add(debug_mode int(1) DEFAULT '0')");
            executeDbSql('update settings set group_sorting_name = 0');  //was a workaround, fixed in V2.2.1

            executeDbSql("update settings set version = '2.2.0'");
            notifyInfo(__FILE__, 'db-update='.$version.'->2.2.0', $_SESSION['userid']);
        case '2.2.0':
            executeDbSql("alter table users add(isBandAdmin char(2) NOT NULL DEFAULT '0')");
            executeDbSql('update settings set group_sorting_name = 0'); //due to an error reset it again

            executeDbSql("update settings set version = '2.2.1'");
            notifyInfo(__FILE__, 'db-update='.$version.'->2.2.1', $_SESSION['userid']);
        case '2.2.1':
                $sql = "CREATE TABLE IF NOT EXISTS `statistics` (
				  `id` int(11) NOT NULL AUTO_INCREMENT,
				  `userid` int(6) NOT NULL DEFAULT '0',
				  `date` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
				  `type` text NOT NULL,
				  `detail1` text NOT NULL,
				  `detail2` text NOT NULL,
				  `detail3` text NOT NULL,
				  `script` text NOT NULL,
				  PRIMARY KEY (`id`)
				) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=50";
            executeDbSql($sql);

            executeDbSql("update settings set version = '2.3.0'");
            notifyInfo(__FILE__, 'db-update='.$version.'->2.3.0', $_SESSION['userid']);
            insertStatistics('system', __FILE__, 'db-update', '2.3.0', $version);
        case '2.3.0':
            executeDbSql("alter table users add(isEventEditor char(2) NOT NULL DEFAULT '0')");

            executeDbSql("update settings set version = '2.3.1'");
            notifyInfo(__FILE__, 'db-update='.$version.'->2.3.1', $_SESSION['userid']);
            insertStatistics('system', __FILE__, 'db-update', '2.3.1', $version);
        case '2.3.1':
            executeDbSql("update settings set version = '2.3.2'");
            notifyInfo(__FILE__, 'db-update='.$version.'->2.3.2', $_SESSION['userid']);
            insertStatistics('system', __FILE__, 'db-update', '2.3.2', $version);
        case '2.3.2':
            executeDbSql("update settings set version = '2.3.3'");
            notifyInfo(__FILE__, 'db-update='.$version.'->2.3.3', $_SESSION['userid']);
            insertStatistics('system', __FILE__, 'db-update', '2.3.3', $version);
        case '2.3.3':
            if (substr($mysqli_version, 0, 1) == 5) {
                executeDbSql("
					CREATE FUNCTION getBrowserInfo (user_agent VARCHAR(255)) RETURNS VARCHAR(100) DETERMINISTIC
					 BEGIN
					  DECLARE v_browser,v_os VARCHAR(20);
					  DECLARE v_agent VARCHAR(255);
					
					  SET v_browser = 'OTHER';
					  SET v_os = 'OTHER';
					
					  select detail3 into v_agent 
						from statistics 
						where detail1='login'
						order by date desc
						limit 1;
					
					  if (user_agent = '-') then
					   SET v_agent = upper(v_agent);
					  else
					   SET v_agent = upper(user_agent);
					  end if;
					  
					  if (instr(v_agent,'IE')>0) then set v_browser= 'IE';
					   elseif (instr(v_agent,'OPERA')>0) then set v_browser= 'OPERA';
					   elseif (instr(v_agent,'NETSCAPE')>0) then set v_browser= 'NETSCAPE';
					   elseif (instr(v_agent,'FIREFOX')>0) then set v_browser= 'FIREFOX';
					   elseif (instr(v_agent,'FLOCK')>0) then set v_browser= 'FLOCK';
					   elseif (instr(v_agent,'CHROME')>0) then set v_browser= 'CHROME';
					   elseif (instr(v_agent,'SAFARI')>0) then set v_browser= 'SAFARI';   
					   elseif (instr(v_agent,'MOZILLA')>0) then set v_browser= 'MOZILLA';      
					  end if;
					
					  if (instr(v_agent,'WINDOWS')>0) then set v_os= 'WINDOWS';
					   elseif (instr(v_agent,'IPHONE')>0) then set v_os= 'IPHONE';
					   elseif (instr(v_agent,'IPAD')>0) then set v_os= 'IPAD';
					   elseif (instr(v_agent,'ANDROID')>0) then set v_os= 'ANDROID';
					   elseif (instr(v_agent,'MAC')>0) then set v_os= 'MAC';   
					   elseif (instr(v_agent,'LINUX')>0) then set v_os= 'LINUX'; 
					  end if;
					
					  RETURN CONCAT(v_browser ,' / ',v_os);
					END;"
                    );
            }
            executeDbSql("update settings set version = '2.3.4'");
            notifyInfo(__FILE__, 'db-update='.$version.'->2.3.4', $_SESSION['userid']);
            insertStatistics('system', __FILE__, 'db-update', '2.3.4', $version);
        case '2.3.4':
            executeDbSql("update settings set version = '2.3.5'");
            notifyInfo(__FILE__, 'db-update='.$version.'->2.3.5', $_SESSION['userid']);
            insertStatistics('system', __FILE__, 'db-update', '2.3.5', $version);
        case '2.3.5':
            executeDbSql("update settings set version = '2.4.0'");
            notifyInfo(__FILE__, 'db-update='.$version.'->2.4.0', $_SESSION['userid']);
            insertStatistics('system', __FILE__, 'db-update', '2.4.0', $version);
        case '2.4.0':
            executeDbSql("update settings set version = '2.4.1'");
            notifyInfo(__FILE__, 'db-update='.$version.'->2.4.1', $_SESSION['userid']);
            insertStatistics('system', __FILE__, 'db-update', '2.4.1', $version);
        case '2.4.1':
            executeDbSql('alter table settings add(days_to_alert int(2) DEFAULT 5) ');
            executeDbSql("alter table settings add(token varchar(100) DEFAULT '') ");
            executeDbSql("update settings set version = '2.4.2'");
            notifyInfo(__FILE__, 'db-update='.$version.'->2.4.2', $_SESSION['userid']);
            insertStatistics('system', __FILE__, 'db-update', '2.4.2', $version);
        case '2.4.2':
            executeDbSql("update settings set version = '2.4.3'");
            notifyInfo(__FILE__, 'db-update='.$version.'->2.4.3', $_SESSION['userid']);
            insertStatistics('system', __FILE__, 'db-update', '2.4.3', $version);
        case '2.4.3':
            executeDbSql("update settings set version = '2.4.4'");
            notifyInfo(__FILE__, 'db-update='.$version.'->2.4.4', $_SESSION['userid']);
            insertStatistics('system', __FILE__, 'db-update', '2.4.4', $version);
        case '2.4.4':
            if (substr($mysqli_version, 0, 1) == 5) {
                executeDbSql('DROP FUNCTION getBrowserInfo');
                executeDbSql("
					CREATE FUNCTION getBrowserInfo (user_agent VARCHAR(255)) RETURNS VARCHAR(100) DETERMINISTIC
					 BEGIN
					  DECLARE v_browser,v_os VARCHAR(20);
					  DECLARE v_agent VARCHAR(255);
					
					  SET v_browser = 'OTHER';
					  SET v_os = 'OTHER';
					
					  select detail3 into v_agent 
						from statistics 
						where detail1='login'
						order by date desc
						limit 1;
					
					  if (user_agent = '-') then
					   SET v_agent = upper(v_agent);
					  else
					   SET v_agent = upper(user_agent);
					  end if;
					  
					  if (instr(v_agent,'IE')>0) then set v_browser= 'IE';
					   elseif (instr(v_agent,'OPERA')>0) then set v_browser= 'OPERA';
					   elseif (instr(v_agent,'NETSCAPE')>0) then set v_browser= 'NETSCAPE';
					   elseif (instr(v_agent,'FIREFOX')>0) then set v_browser= 'FIREFOX';
					   elseif (instr(v_agent,'FLOCK')>0) then set v_browser= 'FLOCK';
					   elseif (instr(v_agent,'CHROME')>0) then set v_browser= 'CHROME';
					   elseif (instr(v_agent,'SAFARI')>0) then set v_browser= 'SAFARI';   
					   elseif (instr(v_agent,'MOZILLA')>0) then set v_browser= 'MOZILLA';      
					  end if;
					
					  if (instr(v_agent,'WINDOWS')>0) then set v_os= 'WINDOWS';
					   elseif (instr(v_agent,'IPHONE')>0) then set v_os= 'IPHONE';
					   elseif (instr(v_agent,'IPAD')>0) then set v_os= 'IPAD';
					   elseif (instr(v_agent,'ANDROID')>0) then set v_os= 'ANDROID';
					   elseif (instr(v_agent,'MAC')>0) then set v_os= 'MAC';   
					   elseif (instr(v_agent,'LINUX')>0) then set v_os= 'LINUX'; 
					  end if;
					
					  RETURN CONCAT(v_browser ,' / ',v_os);
					END;"
                );
            }
            executeDbSql("update settings set version = '2.4.5'");
            notifyInfo(__FILE__, 'db-update='.$version.'->2.4.5', $_SESSION['userid']);
            insertStatistics('system', __FILE__, 'db-update', '2.4.5', $version);
        case '2.4.5':
            executeDbSql("update settings set version = '2.5.0'");
            notifyInfo(__FILE__, 'db-update='.$version.'->2.5.0', $_SESSION['userid']);
            insertStatistics('system', __FILE__, 'db-update', '2.5.0', $version);
        case '2.5.0':
            executeDbSql("update settings set version = '2.5.1'");
            notifyInfo(__FILE__, 'db-update='.$version.'->2.5.1', $_SESSION['userid']);
            insertStatistics('system', __FILE__, 'db-update', '2.5.1', $version);
        case '2.5.1':
            executeDbSql("update settings set version = '2.5.2'");
            notifyInfo(__FILE__, 'db-update='.$version.'->2.5.2', $_SESSION['userid']);
            insertStatistics('system', __FILE__, 'db-update', '2.5.2', $version);
        case '2.5.2':
            executeDbSql("update settings set version = '2.5.3'");
            notifyInfo(__FILE__, 'db-update='.$version.'->2.5.3', $_SESSION['userid']);
            insertStatistics('system', __FILE__, 'db-update', '2.5.3', $version);
        case '2.5.3':
            executeDbSql("update settings set version = '2.6.0'");
            notifyInfo(__FILE__, 'db-update='.$version.'->2.6.0', $_SESSION['userid']);
            insertStatistics('system', __FILE__, 'db-update', '2.6.0', $version);
        case '2.6.0':
            $sql = "ALTER TABLE users
								ADD COLUMN lastLogin DATETIME NULL AFTER isEventEditor,
								ADD COLUMN passwordChanged DATETIME NULL AFTER lastLogin,
								ADD COLUMN created TIMESTAMP NOT NULL DEFAULT '0000-00-00 00:00:00' AFTER passwordChanged,
								ADD COLUMN updated TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP AFTER created;
								
							CREATE TABLE IF NOT EXISTS swaps (
								id int(11) NOT NULL AUTO_INCREMENT,
								eventPersonId int(11) NOT NULL DEFAULT '0',
								oldUserRoleId int(11) NOT NULL DEFAULT '0',
								newUserRoleId int(11) NOT NULL DEFAULT '0',
								accepted int(1) NOT NULL DEFAULT '0',
								declined int(1) NOT NULL DEFAULT '0',
								requestedBy int(11) NOT NULL,
								verificationCode varchar(18) NOT NULL,
								created TIMESTAMP NOT NULL DEFAULT '0000-00-00 00:00:00',
								updated TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
								PRIMARY KEY (`id`)
							) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=50;
									
							ALTER TABLE roles
								ADD COLUMN allowRoleSwaps tinyint(1) NULL AFTER rehersalId;
							
							ALTER TABLE groups
								ADD COLUMN allowRoleSwaps tinyint(1) NULL AFTER description;
								
							ALTER TABLE eventTypes
								ADD COLUMN name varchar(30) NULL AFTER id,
								ADD COLUMN defaultDay int(1) NULL AFTER description,
								ADD COLUMN defaultTime time NULL AFTER defaultDay;
								
							CREATE TABLE IF NOT EXISTS eventSubTypes (
								id int(30) NOT NULL AUTO_INCREMENT,
								name varchar(128) NOT NULL,
								description text NOT NULL,
								PRIMARY KEY (`id`)
							) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=50;
							
							ALTER TABLE events
								ADD COLUMN name varchar(100) NULL AFTER date,
								ADD COLUMN subType int(30) NULL AFTER type,
								ADD COLUMN eventGroup int(30) NULL AFTER location,
								ADD COLUMN sermonTitle int(30) NULL AFTER eventGroup,
								ADD COLUMN bibleVerse int(30) NULL AFTER sermonTitle,
								ADD COLUMN created TIMESTAMP NOT NULL DEFAULT '0000-00-00 00:00:00' AFTER deleted,
								ADD COLUMN updated TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP AFTER created;
								
							ALTER TABLE eventPeople
								CHANGE eventID eventId int(11);
								
							CREATE TABLE IF NOT EXISTS eventGroups (
								id int(30) NOT NULL AUTO_INCREMENT,
								name varchar(128) NOT NULL,
								description text NOT NULL,
								archived tinyint(1) NOT NULL DEFAULT '0',
								PRIMARY KEY (`id`)
							) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=50;
							
							CREATE TABLE IF NOT EXISTS emails (
								id int(30) NOT NULL AUTO_INCREMENT,
								emailTo varchar(100) NOT NULL DEFAULT '',
								emailBcc varchar(100) NOT NULL DEFAULT '',
								emailFrom varchar(100) NOT NULL DEFAULT '',
								subject varchar(150) NOT NULL,
								message text NOT NULL,
								created TIMESTAMP NOT NULL DEFAULT '0000-00-00 00:00:00',
								updated TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
								error varchar(200) NULL,
								PRIMARY KEY (`id`)
							) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=50";
            executeDbSql($sql);
            executeDbSql("update settings set version = '3.0.0-pre1'");

            notifyInfo(__FILE__, 'db-update='.$version.'->3.0.0-pre1', $_SESSION['userid']);
            insertStatistics('system', __FILE__, 'db-update', '3.0.0-pre1', $version);
        case '3.0.0-pre1':
            $sql = "CREATE TABLE IF NOT EXISTS notifications (
								id int(30) NOT NULL AUTO_INCREMENT,
								timestamp TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
								userId int(30) NOT NULL,
								summary varchar(40) NOT NULL,
								body text NOT NULL,
								link varchar(150) NULL,
								type int(2) NOT NULL,
								seen tinyint(1) NOT NULL DEFAULT '0',
								dismissed tinyint(1) NOT NULL DEFAULT '0',
								archived tinyint(1) NOT NULL DEFAULT '0',
								PRIMARY KEY (`id`)
							) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=50;
							
							CREATE TABLE IF NOT EXISTS notificationClicks (
								id int(30) NOT NULL AUTO_INCREMENT,
								notificationId int(30) NOT NULL,
								referer varchar(50) NOT NULL,
								timestamp TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
								PRIMARY KEY (`id`)
							) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=50;
							
							ALTER TABLE eventTypes
								ADD COLUMN defaultLocationId int(30) NULL AFTER defaultTime;
							";
            executeDbSql($sql);
            executeDbSql("update settings set version = '3.0.0-pre2'");

        case '3.0.0-pre2':
            // latest version

            //todo in a later version:
            //executeDbSql("alter table settings CHANGE debug_mode verbose_statistics int(1) DEFAULT '0' ");
            break;

    }
}
