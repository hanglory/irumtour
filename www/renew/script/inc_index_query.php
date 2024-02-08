<?
/*시즌추천여행*/
$sql_best_c1 ="
    select
        a.*,
        (select seq from ez_tour_seq where tid=a.tid and code1='' and code2='' and code3='' and best='c1' and cp_id='$CID') as rseq
    from ez_tour as a left join ez_tour_seq as b
    on a.tid=b.tid
    where 
        a.bit=1 
        and a.sale_group='T'
        and b.best='c1'
        and a.cp_id='' 
        and b.cp_id=''
    group by a.tid
    order by rseq asc,seq asc    
    limit 20            
";  


$sql_best_c2 ="
    select
        a.*,
        (select seq from ez_tour_seq where tid=a.tid and code1='14' and code2='CODE2' and code3='' and best='c2' and cp_id='$CID') as rseq
    from ez_tour as a left join ez_tour_seq as b
    on a.tid=b.tid
    where 
        a.bit=1
        and a.sale_group='T'
        and (
            a.category1 like '14-CODE2-%'
            or a.category2 like '14-CODE2-%'
            or a.category3 like '14-CODE2-%'
            or a.category4 like '14-CODE2-%'
            or a.category5 like '14-CODE2-%'
            or a.category6 like '14-CODE2-%'
        )
        and b.best='c2'
        and a.cp_id='' 
        and b.cp_id=''
    group by a.tid
    order by rseq asc,seq asc
    limit 4
";
    


$sql_best_c4 ="
    select
        a.*,
        (select seq from ez_tour_seq where tid=a.tid and code1='' and code2='' and code3='' and best='c4' and cp_id='$CID') as rseq
    from ez_tour as a left join ez_tour_seq as b
    on a.tid=b.tid
    where 
        a.bit=1 
        and a.sale_group='T'
        and b.best='c4'
        and a.cp_id='' 
        and b.cp_id=''
    group by a.tid
    order by rseq asc,seq asc
    limit 4
";



?>