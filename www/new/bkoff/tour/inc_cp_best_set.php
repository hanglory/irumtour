<?


$sql = "
        select
            count(*) as cnt
        from ez_tour_seq
        where 
            cp_id='$id'
            and best<>''
    ";
$dbo->query($sql);
$rs=$dbo->next_record();
//checkVar(mysql_error(),$sql);
if(!$rs[cnt]){

    $sql = "
        select
            *
        from ez_tour_seq
        where 
            cp_id=''
            and best<>''
        order by seq asc
        ";
    $dbo->query($sql);
    //checkVar(mysql_error(),$sql);
    while($rs=$dbo->next_record()){
        $sql2 = "
            insert into ez_tour_seq (
                cp_id,
                tid,
                code1,
                code2,
                code3,
                best,
                seq
            ) values (
                '$id',
                '$rs[tid]',
                '$rs[code1]',
                '$rs[code2]',
                '$rs[code3]',
                '$rs[best]',
                '$rs[seq]'
            )
            ";
        $dbo2->query($sql2);
        //checkVar(mysql_error(),$sql2);
    }

}
