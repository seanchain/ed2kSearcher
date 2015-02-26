
<meta charset="utf-8">
<head>
    <title>ed2k搜索</title>
</head>

<?php

include_once('simple_html_dom.php');
header("content-Type: text/html; charset=Utf-8");

$key = "猫和老鼠";

class ED2k
{
    private $page = 0;
    private $max_page = 0;
    public function __construct($key, $pg)
    {
        $this->page = $pg;
        $url = "http://donkey4u.com/search/".$key."?page=".$pg."&mode=list";
        $pic = "http://donkey4u.com/search/".$key."?page=".$pg."&mode=thumb";
        $this->get_infos($url, $pic);
        echo "<br /><br />";
    }
    public function getContent($url)
    {
        $ch = curl_init(); 
        curl_setopt($ch, CURLOPT_URL, $url); 
        curl_setopt($ch,CURLOPT_RETURNTRANSFER,true);       
        curl_setopt($ch,CURLOPT_USERAGENT,"Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1; SV1; .NET CLR 1.1.4322; .NET CLR 2.0.50727)"); 
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); 
        curl_setopt($ch, CURLOPT_HEADER, 0); 
        $contents = curl_exec($ch); 
        curl_close($ch);
        return $contents;
    }
    public function get_infos($url, $pic)
    {
        $contents = $this->getContent($url);
        $piccontents = $this->getContent($pic);
        preg_match_all('|ed2k://.+?/|', $contents, $links);
        $link = $links[0];
        for($i = 0; $i < count($link) - 1; $i ++){
            $linkary[$i] = $links[0][$i + 1];
        }
        $html = str_get_html($contents);
        $pichtml = str_get_html($piccontents);
        $sizes = $html->find('table.search_table td[width=70]');
        $seeds = $html->find('table.search_table td[width=100]');
        $totals = $html->find('table tbody tr td b');
        $thumbs = $pichtml->find('div#search_thumb a.borderit img');
        for($i = 0; $i < count($sizes); $i ++){
            $sizeary[$i] = $sizes[$i]->plaintext;
            $seedary[$i] = $seeds[$i]->plaintext;
        }
        for($k = 0; $k < count($thumbs); $k ++)
        {
            $thumbary[$k] = $thumbs[$k]->src;
        }
        $res = $totals[count($totals) - 1]->plaintext;
        preg_match('/[0-9]+/', $res, $total);
        $this->max_page = intval(intval($total[0]) / 20) + 1;
        $this->template($linkary, $sizeary, $seedary, $thumbary);
    }
    public function template($links, $sizes, $seeds, $thumbs)
    {
        $out = Array();
        for($i = 0; $i < count($links); $i ++){
            $out[$i] = "<img src='".$thumbs[$i]."' width='150px'/><br />".$links[$i]."<br />size: ".$sizes[$i]." &nbsp;seeds: ".$seeds[$i];
        }
        foreach ($out as $key){
          echo $key;
          echo "<br /><br />";
        }
        $this->nextPage();
    }
    public function nextPage()
    {
        if($this->page > 1) echo "<a href='./ed2k.php?pg=".($this->page - 1)."'>上一页</a>&nbsp;&nbsp;&nbsp;";
        if($this->page < $this->max_page) echo "<a href='./ed2k.php?pg=".($this->page + 1)."'>下一页</a>";
    }
}

$pg = $_GET['pg'];
$ed = new ED2k($key, $pg);






?>
