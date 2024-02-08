<?
if($CP_ID){

    $reg_date = date('Y/m/d');
    $reg_date2 = date('H:i:s');

    $sql = "
        select
            a.*,
            (select count(*) from $table where cp_id='$CP_ID' and id_no_origin=a.id_no) as bit
        from $table as a
        where 
            cp_id=''
        order by seq asc
    ";
    $dbo->query($sql);
    //checkVar(mysql_error(),$sql);
    while($rs=$dbo->next_record()){
        $id_no_origin = $rs[id_no];
        $seq = 0;
        //checkVar($rs[bit],$rs[text1]);
        $sqlInsert="
           insert into ez_nbanner1 (
              cp_id,
              text1,
              target,
              url,
              filename,
              text2,
              text3,
              bit_hide,
              filename2,
              seq,
              id_no_origin
          ) values (
              '$CP_ID',
              '$rs[text1]',
              '$rs[target]',
              '$rs[url]',
              '$rs[filename]',
              '$rs[text2]',
              '$rs[text3]',
              '$rs[bit_hide]',
              '$rs[filename2]',
              '$seq',
              '$id_no_origin'
        )";

        $sqlModify="
           update ez_nbanner1 set
              text1 = '$rs[text1]',
              text2 = '$rs[text2]',
              text3 = '$rs[text3]',
              url = '$rs[url]',
              target = '$rs[target]',
              filename = '$rs[filename]',
              filename2 = '$rs[filename2]'
           where id_no='$id_no_origin'
        ";

        $sql2 = ($rs[bit])? $sqlModify : $sqlInsert;
        $dbo2->query($sql2);
        //checkVar(mysql_error(),$sql2);
    }    


}
?>