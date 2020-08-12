<?php
/**
 * Magiccart 
 * @category    Magiccart 
 * @copyright   Copyright (c) 2014 Magiccart (http://www.magiccart.net/) 
 * @license     http://www.magiccart.net/license-agreement.html
 * @Author: DOng NGuyen<nguyen@dvn.com>
 * @@Create Date: 2017-08-26 15:31:37
 * @@Modify Date: 2017-08-30 23:52:33
 * @@Function:
 */

namespace Magiccart\Alothemes\Block\Widget;

use Magiccart\Alothemes\Block\Twitter\TwitterOAuth;

class Twitter extends Socialstream
{

    public $tweetCfg = array();

    private function cleanTwitterName($twitterid)
    {
        $test = substr($twitterid,0,1);
        
        if($test == "@"){
            $twitterid = substr($twitterid,1);  
        }
        
        return $twitterid;
        
    }
    
    private function changeLink($string, $tags=true, $nofollow=true, $newwindow=true, $attags=true, $hashtags=true)
    {
        if(!$tags){
            $string = strip_tags($string);
        }
        if($nofollow){
            $string = str_replace('<a ','<a rel="nofollow"', $string);  
        }
        if($newwindow){
            $string = str_replace('<a ','<a  Target="_Blank" ', $string);   
        }
        return $string;
    }
    
    private function getTimeAgo($time)
    {
            $tweettime = strtotime($time); // This is the value of the time difference - UK + 1 hours (3600 seconds)
            $nowtime = time();
            $timeago = ($nowtime-$tweettime);
            $thehours = floor($timeago/3600);
            $theminutes = floor($timeago/60);
            $thedays = floor($timeago/86400);
            /********************* Checking the times and returning correct value */
            if($theminutes < 60){
                if($theminutes < 1){
                    $timemessage =  "Less than 1 minute ago";
                } else if($theminutes == 1) {
                    $timemessage = $theminutes." minute ago";
                } else {
                    $timemessage = $theminutes." minutes ago";
                }
            } else if($theminutes > 60 && $thedays < 1){
                 if($thehours == 1){
                    $timemessage = $thehours." hour ago";
                 } else {
                    $timemessage = $thehours." hours ago";
                 }
            } else {
                 if($thedays == 1){
                    $timemessage = $thedays." day ago";
                 } else {
                    $timemessage = $thedays." days ago";
                 }
            }
        return $timemessage;    
    }
    
    public function getTweets($tweets)
    {
        $t = array();
        $i = 0;
        foreach($tweets as $tweet)
        {   
            $text = $tweet->text;
            $urls = $tweet->entities->urls;
            $mentions = $tweet->entities->user_mentions;
            $hashtags = $tweet->entities->hashtags;
            if($urls){
                foreach($urls as $url){
                    if(strpos($text,$url->url) !== false){
                        $text = str_replace($url->url,'<a href="'.$url->url.'">'.$url->url.'</a>',$text);   
                    }
                }
            }
            if($mentions && $this->tweetCfg['attags']){
                foreach($mentions as $mention){
                    if(strpos($text,$mention->screen_name) !== false){
                        $text = str_replace("@".$mention->screen_name." ",'<a href="http://twitter.com/'.$mention->screen_name.'">@'.$mention->screen_name.'</a> ',$text);  
                    }
                }
            }
            if($hashtags && $this->tweetCfg['hashtags']){
                foreach($hashtags as $hashtag){
                    if(strpos($text,$hashtag->text) !== false){
                        $text = str_replace('#'.$hashtag->text." ",'<a href="http://twitter.com/search?q=%23'.$hashtag->text.'">#'.$hashtag->text.'</a> ',$text);   
                    }
                }
            }
            $t[$i]["name"] = $tweet->user->name;
            $t[$i]["screen_name"] = $tweet->user->screen_name;
            $t[$i]["profile_image_url"] = $tweet->user->profile_image_url;
            $t[$i]["profile_background_image_url_https"] = $tweet->user->profile_background_image_url_https;
            $t[$i]["tweet"] = trim($this->changeLink($text, $this->tweetCfg['showlinks'], $this->tweetCfg['usenofollow'], $this->tweetCfg['opennew'])); 
            $t[$i]["time"] = trim($this->getTimeAgo($tweet->created_at));
            $i++;
        }
        return $t;
    }

    public function getLatestTweets()
    {
        if(!$this->tweetCfg) $this->tweetCfg = $this->_sysCfg->twitter;            
        $screen_name        = $this->tweetCfg['twitterid'];
        $consumerkey        = $this->tweetCfg['consumerkey'];
        $consumersecret     = $this->tweetCfg['consumersecret'];
        $accesstoken        = $this->tweetCfg['accesstoken'];
        $accesstokensecret  = $this->tweetCfg['accesstokensecret'];
        $not                = $this->tweetCfg['limit'];    
        
        if (!$screen_name){
            return false;
        }
 
        try {
            $twitterconn = new TwitterOAuth($consumerkey, $consumersecret, $accesstoken, $accesstokensecret);
            // omposer require hybridauth/hybridauth
            // composer require abraham/twitteroauth
        }
        catch (Exception $e) {
            // require_once('Twitter/twitteroauth.php');
            $twitterconn = new \TwitterOAuth($consumerkey, $consumersecret, $accesstoken, $accesstokensecret);
            // echo $e->getMessage();
        }
        catch (InvalidArgumentException $e) {
            echo $e->getMessage();
        }
 
        $latesttweets = $twitterconn->get("https://api.twitter.com/1.1/statuses/user_timeline.json?screen_name=".$screen_name."&count=".$not);      
        
        // if($latesttweets && !$latesttweets->errors ){
            return($this->getTweets($latesttweets));
        // }
        
    }

}
