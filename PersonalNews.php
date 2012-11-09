<?php
/*
 * neoPersonalNews.php - Plugin for Stud.IP
 * Copyright (c) 2012  Johannes Stichler / johannes.stichler@hfwu.de
 *
 * This program is free software; you can redistribute it and/or
 * modify it under the terms of the GNU General Public License as
 * published by the Free Software Foundation; either version 2 of
 * the License, or (at your option) any later version.
 */

class PersonalNews extends StudipPlugin implements PortalPlugin
{
    public function getPortalTemplate()
    {
        $template_path = $this->getPluginPath().'/templates';
        $template_factory = new Flexi_TemplateFactory($template_path);
        $template = $template_factory->open('news');
        $template->title = "Ankündigungen meiner Einrichtungen und Veranstaltungen";
        $template->icon_url = $this->getPluginURL() . '/assets/images/breaking-news.png';
        $news = $this->getAllNews();
        $template->news = $news;
        return $template;
    }

    private function getAllNews() {
        //die ID des Users Sammeln
        $userid = $GLOBALS['user']->id;
        $db = DBManager::get();
        //Alle News des Users sammeln!
        if($_REQUEST["pnshowall"] == "true") {
            $sql = "SELECT news_id, topic, body, author, user_id, date, expire FROM `news` WHERE `news_id` in (
                  SELECT news_id FROM  `news_range`
                  WHERE range_id in (
                      SELECT  Institut_id FROM  `user_inst` WHERE user_id = '".$userid."') OR
                      range_id in (SELECT  Seminar_id FROM  `seminar_user` WHERE user_id = '".$userid."')
                  )
                  AND (date+expire) > '".time()."'
                ORDER BY date DESC
                LIMIT 0, 30";
        } else {
            $sql = "SELECT news_id, topic, body, author, user_id, date, expire FROM `news` WHERE `news_id` in (
                  SELECT news_id FROM  `news_range`
                  WHERE range_id in (
                      SELECT  Institut_id FROM  `user_inst` WHERE user_id = '".$userid."') OR
                      range_id in (SELECT  Seminar_id FROM  `seminar_user` WHERE user_id = '".$userid."')
                  )
                  AND (date+expire) > '".time()."'
                  AND NOT news_id in (SELECT  newsid FROM  plugins_personalnews WHERE user_id = '".$userid."')
                ORDER BY date DESC
                LIMIT 0, 30";
        }
        echo $sql;
        $news = $db->query($sql)->fetchAll(PDO::FETCH_ASSOC);

        foreach($news as $n) {
            //Info Name des Autors hinzufügen
            $sql = "SELECT username, Vorname, Nachname FROM `auth_user_md5` WHERE user_id = '".$n["user_id"]."'";
            $name = $db->query($sql)->fetchAll(PDO::FETCH_ASSOC);
            $uname = $name[0]["username"];
            $name = $name[0]["Vorname"]." ".$name[0]["Nachname"];
            $n["autor"] = $name;
            $n["username"] = $uname;
            //Ranges Hinzufügen
            $sql = "SELECT * FROM  `news_range` WHERE news_id = '".$n["news_id"]."'";
            $range = $db->query($sql)->fetchAll(PDO::FETCH_ASSOC);
            foreach($range as $r) {
                //Schauen ob diese Range bei Einrichtunge gefunden wird
                $sql = "SELECT `Institut_id` as id, Name FROM `Institute` WHERE Institut_id = '".$r["range_id"]."'";
                $einrichtungen = $db->query($sql)->fetchAll(PDO::FETCH_ASSOC);
                foreach($einrichtungen as $e) {
                    $n["einrichtungen"][] = array("id" => $e["id"],
                                                  "Name" => $e["Name"]);
                }
                //Einrichtungsdaten sammeln
                $n["einrichtungen"][] = $einrichtungen;
                $sql = "SELECT `Seminar_id` as id, Name FROM `seminare` WHERE Seminar_id = '".$r["range_id"]."'";
                //echo $sql;
                $vls = $db->query($sql)->fetchAll(PDO::FETCH_ASSOC);
                foreach($vls as $vl) {
                  $n["vls"][] = array("id" => $vl["id"],
                                      "Name" => $vl["Name"]);
                }
            $n["usercount"] = $this->countUserVisitors($n["news_id"]);
            $n["visit"] = $this->getUserVisitStatus($n["news_id"], $userid);
            }
            $news_allinfos[] = $n;
        }
        return $news_allinfos;
    }
    //gibt die Zahl der User aus die diese News schon gelsen haben
    private function countUserVisitors($newsid) {
        $db = DBManager::get();
        $sql = "SELECT COUNT(user_id)
                FROM  `object_user_visits`
                WHERE  `object_id` =  '".$newsid."'";
        $count = $db->query($sql)->fetchAll(PDO::FETCH_ASSOC);
        $count = $count[0]["COUNT(user_id)"];
        return $count;

    }
    //Gibt True oder False an ob der User diese Ankündigung schon gelesen hat
    private function getUserVisitStatus($newsid, $userid) {
        $db = DBManager::get();
        $sql = "SELECT COUNT(user_id)
                FROM  `object_user_visits`
                WHERE  `object_id` =  '".$newsid."' AND
               `user_id` =  '".$userid."'";
        $count = $db->query($sql)->fetchAll(PDO::FETCH_ASSOC);
        $count = $count[0]["COUNT(user_id)"];
        if($count == 0) {
            return false;
        } else {
            return true;
        }



    }
    //Ändert den Status damit der User diese Ankündigung schon gelesen hat
    private function setUserVisitStatus($newsid) {

    }
    
    /*
     * Versteckt die Ankündigung
     * 
     * @parm    string newsid   ID der Ankuendigung
     * @return  true/false      Erfolgreich oder auch nicht erfolgreich
     * 
     */
    private function hiddenews($newsid) {
        
        
        return true;
    }
}
?>
