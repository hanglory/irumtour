
            <div style="clear:both;padding:0;margin:0;">                
                <link rel="stylesheet" href="/renew/lib/jodit.min.css"/>
                <script src="/renew/lib/jodit.min.js"></script>

                <textarea name="content<?=$editors_id?>" id="content<?=$editors_id?>" rows="10" cols="100" style="width:100%; height:<?=$board_height?>px; display:none;"><?=$rs["content".$editors_id]?></textarea>
                <script>
                    const editor = Jodit.make('#content<?=$editors_id?>' ,{
                        iframe: true,
                        height: <?=$board_height?>,
                        enter: 'BR',
                        uploader: {
                            url: '/new/script/jodit_upload.php?action=upload', 
                            headers: null,
                            data: null,
                            filesVariableName: function(i){
                                return "file["+i+"]"
                            },
                            withCredentials: false,
                            pathVariableName: "path",
                            format: "json",
                            method: "POST",
                            prepareData: function(formData){
                                formData.append('id', 1)
                            },
                            isSuccess: function (resp) {
                                return !resp.error;
                            },
                            getMessage: function (resp) {
                                return resp.msgs.join('\n');
                            },
                            process: function (resp) {
                                return resp;
                            },
                            defaultHandlerSuccess: function (resp) {
                                for(var i=0; i<resp.file.length; i++) {
                                    if(resp.file[i]['url']!=undefined){
                                        editor.selection.insertImage(resp.file[i]['url']);
                                    }    
                                        
                                }
                            }          
                        }

                    });
                </script>
            </div>   