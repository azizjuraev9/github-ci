<?php
/**
 * Created by PhpStorm.
 * User: Aziz Juraev
 * Date: 11.08.2020
 * Time: 11:36
 */

namespace juraev\github_ci;


class WebHookHandler
{

    const EVENT_KEY = 'X_GITHUB_EVENT';
    const DELIVERY_KEY = 'X_GITHUB_DELIVERY';

    public function getRequest()
    {
        $request = [];

        if ( $_SERVER['REQUEST_METHOD'] !== 'POST' )
        {
            return [];
        }

        if( !strstr($_SERVER['HTTP_USER_AGENT'],'GitHub-Hookshot/') )
        {
            return [];
        }

        if( !isset($_SERVER['HTTP_' . self::EVENT_KEY]) )
        {
            return [];
        }

        $request[self::EVENT_KEY] = $_SERVER['HTTP_' . self::EVENT_KEY];

        $body = file_get_contents('php://input');

        $request['body'] = json_decode($body,true);

        return $request;
    }

//    public function

}