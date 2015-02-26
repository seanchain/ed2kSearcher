
<meta charset="utf-8">
<head>
    <title>ed2k搜索</title>
</head>

<?php

include_once('simple_html_dom.php');
header("content-Type: text/html; charset=Utf-8");

$key = "老友记";

class ED2k
{
    public function __construct($key)
    {
        $url = "http://donkey4u.com/search/".$key."?page=1&mode=list";
        $this->get_infos($url);
        echo "<br /><br />";
    }
    public function get_infos($url)
    {
        $ch = curl_init(); 
        curl_setopt($ch, CURLOPT_URL, $url); 
        curl_setopt($ch,CURLOPT_RETURNTRANSFER,true);       
        curl_setopt($ch,CURLOPT_USERAGENT,"Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1; SV1; .NET CLR 1.1.4322; .NET CLR 2.0.50727)"); 
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); 
        curl_setopt($ch, CURLOPT_HEADER, 0); 
        $contents = curl_exec($ch); 
        curl_close($ch);
        preg_match_all('|ed2k://.+?/|', $contents, $links);
        $link = $links[0];
        for($i = 0; $i < count($link); $i ++){
            $linkary[$i] = $links[0][$i + 1];
        }
        $html = str_get_html($contents);
        $sizes = $html->find('table.search_table td[width=70]');
        $seeds = $html->find('table.search_table td[width=100]');
        for($i = 0; $i < count($sizes); $i ++){
            $sizeary[$i] = $sizes[$i]->plaintext;
            $seedary[$i] = $seeds[$i]->plaintext;
        }
        $this->template($linkary, $sizeary, $seedary);
    }
    public function template($links, $sizes, $seeds)
    {
        for($i = 0; $i < 20; $i ++){
            echo $links[$i]."<br />size: ".$sizes[$i]." &nbsp;seeds: ".$seeds[$i]."<br /><br />";
        }
    }
}


$ed = new ED2k($key);






?>