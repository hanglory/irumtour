<?
if($CP_ID){

    $sql = "
        select
            a.*,
            (select count(*) from $table where cp_id='$CP_ID' and id_no_origin=a.id_no) as bit
        from $table as a
        where 
            cp_id=''
        order by seq asc
    ";
    list($rows) = $dbo->query($sql);
    //checkVar(mysql_error(),$sql);
    while($rs=$dbo->next_record()){
        $id_no_origin = $rs[id_no];
        //checkVar($rs[bit],$rs[text1]);
        $sqlInsert="
           insert into ez_nbanner2 (
              category1,
              category2,
              category3,
              category4,
              category5,
              category6,
              text1,
              text2,
              text3,
              target,
              url,
              filename,
              bit_hide,
              reg_date,
              cp_id,
              id_no_origin
          ) values (
              '$rs[category1]',
              '$rs[category2]',
              '$rs[category3]',
              '$rs[category4]',
              '$rs[category5]',
              '$rs[category6]',
              '$rs[text1]',
              '$rs[text2]',
              '$rs[text3]',
              '$rs[target]',
              '$rs[url]',
              '$rs[filename]',
              '$rs[bit_hide]',
              '$rs[reg_date]',
              '$CP_ID',
              '$id_no_origin'
        )";

        $sqlModify="
           update ez_nbanner2 set
              category1 = '$rs[category1]',
              category2 = '$rs[category2]',
              category3 = '$rs[category3]',
              category4 = '$rs[category4]',
              category5 = '$rs[category5]',
              category6 = '$rs[category6]',
              text1 = '$rs[text1]',
              text2 = '$rs[text2]',
              text3 = '$rs[text3]',
              target = '$rs[target]',
              url = '$rs[url]',
              filename = '$rs[filename]',
              bit_hide = '$rs[bit_hide]'
           where id_no='$id_no_origin'
              $FILTER_PARTNER_QUERY
        ";

        $sql2 = ($rs[bit])? $sqlModify : $sqlInsert;
        $dbo2->query($sql2);
        //checkVar(mysql_error(),$sql2);
    }    



    /*처음인 경우s*/
    $sql = "
            select
                *
            from ez_nbanner2_seq
            where 
                cp_id='$CP_ID'
            limit 1
        ";
    $dbo->query($sql);
    $rs=$dbo->next_record();
    //checkVar(mysql_error(),$sql);
    if(!$rs[id_no]){
        $sql = "
            select
                a.id_no,
                b.code1,
                b.code2,
                b.seq
            from ez_nbanner2 as a left join ez_nbanner2_seq as b
            on a.id_no_origin=b.code
            where 
                a.cp_id='$CP_ID'
            order by b.id_no asc
        ";
        $dbo->query($sql);
        //checkVar(mysql_error(),$sql);
        while($rs=$dbo->next_record()){
            $sql2 = "insert into ez_nbanner2_seq (code,code1,code2,seq,cp_id) values ('$rs[id_no]','$rs[code1]','$rs[code2]',$rs[seq],'$CP_ID')";
            $dbo2->query($sql2);
            //checkVar(mysql_error(),$sql2);
        }
    }
    /*처음인 경우f*/






}
?>