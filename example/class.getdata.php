<?php

class getdata
{

    public static function mc($data, $nobody=false, $options = array(), $oneoptions = array())
    {
        $curls = array();
        $result = array();
        $mh = curl_multi_init();
        foreach ($data as $id => $d)
        {
            $curls[$id] = curl_init();
            $url = (is_array($d) && !empty($d['url'])) ? $d['url'] : $d;
            curl_setopt($curls[$id], CURLOPT_URL, $url);
            curl_setopt($curls[$id], CURLOPT_HEADER, 0);
            curl_setopt($curls[$id], CURLOPT_RETURNTRANSFER, true);
            //curl_setopt($curls[$id], CURLOPT_FOLLOWLOCATION, 1);
            curl_setopt($curls[$id], CURLOPT_USERAGENT, "Mozilla/5.0(Windows;U;WindowsNT5.1;ru;rv:1.9.0.4)Gecko/2008102920AdCentriaIM/1.7Firefox/3.0.4");

            if (!empty($options))
                curl_setopt_array($curls[$id], $options);
            if (!empty($oneoptions[$id]))
                curl_setopt_array($curls[$id], $oneoptions[$id]);
            if (is_array($d))
                if (!empty($d['post']))
                {
                    curl_setopt($curls[$id], CURLOPT_POST, 1);
                    curl_setopt($curls[$id], CURLOPT_POSTFIELDS, $d['post']);
                }
            curl_multi_add_handle($mh, $curls[$id]);
        }
        $running = null;
        do
            curl_multi_execs($mh, $running);
        while ($running > 0);
        foreach ($curls as $id => $content)
        {
            $result[$id] = curl_multi_getcontent($content);
            curl_multi_remove_handle($mh, $content);
        }
        curl_multi_close($mh);
        return $result;
    }

    public static function c($url, $postarray = null, $cookie = null, $https = null, $headers = array())
    {
        $ch = curl_init();
        if ($https)
        {
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);
        }
        if($headers){
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        }
        if ($postarray)
        {
            curl_setopt($ch, CURLOPT_POSTFIELDS, self::createPostString($postarray));
        }
        if ($cookie)
        {
            curl_setopt($ch, CURLOPT_COOKIEFILE, dirname(__FILE__) . '/cookies.txt');
            curl_setopt($ch, CURLOPT_COOKIEJAR, dirname(__FILE__) . '/cookies.txt');
        }
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US; rv:1.8.1.6) Gecko/20070725 Firefox/2.0.0.6");
        curl_setopt($ch, CURLOPT_TIMEOUT, 60);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        $result = curl_exec($ch);
        curl_close($ch);
        return $result;
    }

    private function createPostString($aPostFields)
    {
        foreach ($aPostFields as $key => $value)
            $aPostFields[$key] = urlencode($key) . '=' . urlencode($value);
        return implode('&', $aPostFields);
    }

}

?>
