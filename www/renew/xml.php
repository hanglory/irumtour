<?
include_once("script/include_common_file.php");


$date = date("c");

$xml='<?xml version="1.0" encoding="UTF-8"?>
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">
   <url>';


$xml.='
        <loc>https://irumtour.net/</loc>
        <lastmod>'.$date.'</lastmod>'; 

$xml.='
        <loc>https://irumtour.net/renew/index.html</loc>
        <lastmod>'.$date.'</lastmod>'; 

$xml.='
        <loc>https://irumtour.net/m2/index.html</loc>
        <lastmod>'.$date.'</lastmod>'; 


$dir = "./";
if (is_dir($dir)){                              
  if ($dh = opendir($dir)){                     
    while (($file = readdir($dh)) !== false){   
        if(strstr($file,".html") 
            && !strstr($file,"_bak.") 
            && !strstr($file,"lm_") 
            && !strstr($file,"vsl_") 
            && !strstr($file,"pop_") 
            && !strstr($file,"sub_") 
            && !strstr($file,"menu")
            && !strstr($file,"header")
            && !strstr($file,"footer")
            && !strstr($file,"chatbot")
            && !strstr($file,"prev")
            && !strstr($file,"visual")
            && !strstr($file,"detailview.html")
        ){
            $url = $DOMAIN."/renew/".$file;
            $xml.='
            <loc>'.$url.'</loc>
            <lastmod>'.$date.'</lastmod>';

        }
    }                                           
    closedir($dh);                              
  }                                             
}  
 
$dir = "../m2";
if (is_dir($dir)){                              
  if ($dh = opendir($dir)){                     
    while (($file = readdir($dh)) !== false){   
        if(strstr($file,".html") 
            && !strstr($file,"_bak.") 
            && !strstr($file,"lm_") 
            && !strstr($file,"vsl_") 
            && !strstr($file,"pop_") 
            && !strstr($file,"menu")
            && !strstr($file,"header")
            && !strstr($file,"footer")
            && !strstr($file,"chatbot")
            && !strstr($file,"prev")
            && !strstr($file,"visual")
            && !strstr($file,"inc")
            && !strstr($file,"itemview.html")
        ){
            $url = $DOMAIN."/m2/".$file;
            $xml.='
            <loc>'.$url.'</loc>
            <lastmod>'.$date.'</lastmod>';

        }
    }                                           
    closedir($dh);                              
  }                                             
}  




$sql = "
    select 
        * 
    from ez_tour_category1 
    where 
        bit_hide<>1 
    order by seq asc

";
$dbo->query($sql);
while($rs=$dbo->next_record()){

    $url = $DOMAIN."/renew/sublist.html?code1=$rs[id_no]";
    $url = htmlentities($url);
    $xml.='
            <loc>'.$url.'</loc>
            <lastmod>'.$date.'</lastmod>';  

    $url = $DOMAIN."/m2/sub_list.html?code1=$rs[id_no]";
    $url = htmlentities($url);
    $xml.='
            <loc>'.$url.'</loc>
            <lastmod>'.$date.'</lastmod>';  

    $sql2 = "
        select 
            * 
        from ez_tour 
        where 
            bit=1 
            and sale_group='T' 
            and (
                category1 like '$rs[id_no]-%'
                or category2 like '$rs[id_no]-%'
                or category3 like '$rs[id_no]-%'
                or category4 like '$rs[id_no]-%'
                or category5 like '$rs[id_no]-%'
                or category6 like '$rs[id_no]-%'
            )
        order by id_no desc
    ";
    $dbo2->query($sql2);
    while($rs2=$dbo2->next_record()){
        $url = $DOMAIN."/renew/detailview.html?tid=$rs2[tid]";
        $url = htmlentities($url);
        $xml.='
                <loc>'.$url.'</loc>
                <lastmod>'.$date.'</lastmod>';    

        $url = $DOMAIN."/renew/detailview.html?code1=&code2=&code3=&tid=$rs2[tid]";
        $url = htmlentities($url);
        $xml.='
                <loc>'.$url.'</loc>
                <lastmod>'.$date.'</lastmod>'; 

        $url = $DOMAIN."/renew/detailview.html?code1=$rs[id_no]&code2=&code3=&tid=$rs2[tid]";
        $url = htmlentities($url);
        $xml.='
                <loc>'.$url.'</loc>
                <lastmod>'.$date.'</lastmod>';    

        $url = $DOMAIN."/m2/itemview.html?tid=$rs2[tid]";
        $url = htmlentities($url);
        $xml.='
                <loc>'.$url.'</loc>
                <lastmod>'.$date.'</lastmod>';    

        $url = $DOMAIN."/m2/itemview.html?code1=$rs[id_no]&code2=&code3=&tid=$rs2[tid]";
        $url = htmlentities($url);
        $xml.='
                <loc>'.$url.'</loc>
                <lastmod>'.$date.'</lastmod>';                    

        $url = $DOMAIN."/m2/itemview.html?code1=&code2=&code3=&tid=$rs2[tid]";
        $url = htmlentities($url);
        $xml.='
                <loc>'.$url.'</loc>
                <lastmod>'.$date.'</lastmod>';                 
    }



    $sql2 = "
        select 
            * 
        from ez_tour_category2
        where 
            bit_hide<>1 
            and code1=$rs[id_no]
        order by seq asc

    ";
    $dbo2->query($sql2);
    while($rs2=$dbo2->next_record()){

        $url = $DOMAIN."/renew/sublist.html?code1=$rs[id_no]&code2=$rs2[id_no]";
        $url = htmlentities($url);
        $xml.='
                <loc>'.$url.'</loc>
                <lastmod>'.$date.'</lastmod>';          

        $url = $DOMAIN."/m2/sub_list.html?code1=$rs[id_no]&code2=$rs2[id_no]";
        $url = htmlentities($url);
        $xml.='
                <loc>'.$url.'</loc>
                <lastmod>'.$date.'</lastmod>';  

        $sql3 = "
            select 
                * 
            from ez_tour 
            where 
                bit=1 
                and sale_group='T' 
                and (
                    category1 like '$rs[id_no]-$rs2[id_no]-%'
                    or category2 like '$rs[id_no]-$rs2[id_no]-%'
                    or category3 like '$rs[id_no]-$rs2[id_no]-%'
                    or category4 like '$rs[id_no]-$rs2[id_no]-%'
                    or category5 like '$rs[id_no]-$rs2[id_no]-%'
                    or category6 like '$rs[id_no]-$rs2[id_no]-%'
                )
            order by id_no desc
        ";
        $dbo3->query($sql3);
        while($rs3=$dbo3->next_record()){  

            $url = $DOMAIN."/renew/detailview.html?code1=$rs[id_no]&code2=$rs2[id_no]&code3=&tid=$rs3[tid]";
            $url = htmlentities($url);
            $xml.='
                    <loc>'.$url.'</loc>
                    <lastmod>'.$date.'</lastmod>';    

            $url = $DOMAIN."/m2/itemview.html?code1=$rs[id_no]&code2=$rs2[id_no]&code3=&tid=$rs3[tid]";
            $url = htmlentities($url);
            $xml.='
                    <loc>'.$url.'</loc>
                    <lastmod>'.$date.'</lastmod>';    
        }





        $sql3 = "
            select 
                * 
            from ez_tour_category3
            where 
                bit_hide<>1 
                and code2=$rs2[id_no]
            order by seq asc

        ";
        $dbo3->query($sql3);
        while($rs3=$dbo3->next_record()){
                    

            $url = $DOMAIN."/renew/sublist.html?code1=$rs[id_no]&code2=$rs2[id_no]&code3=$rs3[id_no]";
            $url = htmlentities($url);
            $xml.='
                    <loc>'.$url.'</loc>
                    <lastmod>'.$date.'</lastmod>';     
                              
            $url = $DOMAIN."/m2/sub_list.html?code1=$rs[id_no]&code2=$rs2[id_no]&code3=$rs3[id_no]";
            $url = htmlentities($url);
            $xml.='
                    <loc>'.$url.'</loc>
                    <lastmod>'.$date.'</lastmod>';  

            $sql4 = "
                select 
                    * 
                from ez_tour 
                where 
                    bit=1 
                    and sale_group='T' 
                    and (
                        category1 like '$rs[id_no]-$rs2[id_no]-%'
                        or category2 like '$rs[id_no]-$rs2[id_no]-%'
                        or category3 like '$rs[id_no]-$rs2[id_no]-%'
                        or category4 like '$rs[id_no]-$rs2[id_no]-%'
                        or category5 like '$rs[id_no]-$rs2[id_no]-%'
                        or category6 like '$rs[id_no]-$rs2[id_no]-%'
                    )
                order by id_no desc
            ";
            $dbo4->query($sql4);
            while($rs4=$dbo4->next_record()){  

                $url = $DOMAIN."/renew/detailview.html?code1=$rs[id_no]&code2=$rs2[id_no]&code3=$rs3[id_no]&tid=$rs4[tid]";
                $url = htmlentities($url);
                $xml.='
                        <loc>'.$url.'</loc>
                        <lastmod>'.$date.'</lastmod>';    

                $url = $DOMAIN."/m2/itemview.html?code1=$rs[id_no]&code2=$rs2[id_no]&code3=$rs3[id_no]&tid=$rs4[tid]";
                $url = htmlentities($url);
                $xml.='
                        <loc>'.$url.'</loc>
                        <lastmod>'.$date.'</lastmod>';    
            }


        }        
        
    }
}



/*
$sql = "
    select 
        * 
    from ez_tour 
    where 
        bit=1 
        and sale_group='T' 
    order by id_no desc

";
$dbo->query($sql);
//if(strstr("@14.37.242.84@221.154.110.119@","@".$_SERVER["REMOTE_ADDR"]."@")){checkVar(mysql_error(),$sql);}
while($rs=$dbo->next_record()){
    $arr = explode("-",$rs[category1]);    
    $url = $DOMAIN."/renew/detailview.html?code1=$arr[0]&code2=$arr[2]&code3=$arr[3]&tid=$rs[tid]";
    $url = htmlentities($url);
    $xml.='
            <loc>'.$url.'</loc>
            <lastmod>'.$date.'</lastmod>';    

    $url = $DOMAIN."/renew/detailview.html?code1=&code2=&code3=&tid=$rs[tid]";
    $url = htmlentities($url);
    $xml.='
            <loc>'.$url.'</loc>
            <lastmod>'.$date.'</lastmod>';                 

    $url = $DOMAIN."/renew/detailview.html?tid=$rs[tid]";
    $url = htmlentities($url);
    $xml.='
            <loc>'.$url.'</loc>
            <lastmod>'.$date.'</lastmod>';     

    $url = $DOMAIN."/m2/itemview.html?code1=$arr[0]&code2=$arr[2]&code3=$arr[3]&tid=$rs[tid]";
    $url = htmlentities($url);
    $xml.='
            <loc>'.$url.'</loc>
            <lastmod>'.$date.'</lastmod>';   

    $url = $DOMAIN."/m2/itemview.html?code1=&code2=&code3=&tid=$rs[tid]";
    $url = htmlentities($url);
    $xml.='
            <loc>'.$url.'</loc>
            <lastmod>'.$date.'</lastmod>';    

    $url = $DOMAIN."/m2/itemview.html?tid=$rs[tid]";
    $url = htmlentities($url);
    $xml.='
            <loc>'.$url.'</loc>
            <lastmod>'.$date.'</lastmod>';    
}
*/


$sql = "
    select 
        * 
    from ez_bbs 
    where 
        bid in ('notice','review')
    order by id_no desc

";
$dbo->query($sql);
//if(strstr("@14.37.242.84@221.154.110.119@","@".$_SERVER["REMOTE_ADDR"]."@")){checkVar(mysql_error(),$sql);}
while($rs=$dbo->next_record()){
    $url = $DOMAIN."/renew/comty01.html?bmode=read&bid=$rs[bid]&id_no=$rs[id_no]";
    $url_m = $DOMAIN."/m2/comty01.html?bmode=read&bid=$rs[bid]&id_no=$rs[id_no]";
    $url = htmlentities($url);
    $url_m = htmlentities($url_m);
    $xml.='
            <loc>'.$url.'</loc>
            <lastmod>'.$date.'</lastmod>';    
    $xml.='
            <loc>'.$url_m.'</loc>
            <lastmod>'.$date.'</lastmod>';    
}



$xml.='
   </url>
</urlset>';

$filename= $_SERVER['DOCUMENT_ROOT']."/renew/public/sitemap/sitemap.xml";
$fp=fopen($filename, "w");
fwrite($fp,$xml);
fclose($fp);


if($mode=="download"){
    $url_download ="/renew/include/download.php?file=sitemap.xml&orgin_file_name=sitemap.xml&dir=public/sitemap";
    redirect2($url_download);
}
?>
