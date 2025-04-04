<?php
/**
 * Chatroom member banned words
 */


require "./../../RongCloud.php";
define("APPKEY", '');
define('APPSECRET','');
use RongCloud\RongCloud;
use RongCloud\Lib\Utils;

/**
 * Add chat room member mute
 */
function add()
{
    $RongSDK = new RongCloud(APPKEY,APPSECRET);
    $chatroom = [
        'members'=> [
            ['id'=>'seal9901']// Forbidden personnel id
        ],
        'minute'=>30// Forbidden speech duration
    ];
    $MuteChatrooms = $RongSDK->getUser()->MuteChatrooms()->add($chatroom);
    Utils::dump("add",$MuteChatrooms);
}
add();

/**
 * Unmute chat room member
 */
function remove()
{
    $RongSDK = new RongCloud(APPKEY,APPSECRET);
    $chatroom = [
        'members'=> [
            ['id'=>'seal9901']// Personnel ID
        ],
    ];
    $MuteChatrooms = $RongSDK->getUser()->MuteChatrooms()->remove($chatroom);
    Utils::dump("remove",$MuteChatrooms);
}
remove();

/**
 * Get the list of muted members in the chat room
 */
function getList()
{
    $RongSDK = new RongCloud(APPKEY,APPSECRET);
    $chatroom = [
        
    ];
    $MuteChatrooms = $RongSDK->getUser()->MuteChatrooms()->getList($chatroom);
    Utils::dump("getList",$MuteChatrooms);
}
getList();