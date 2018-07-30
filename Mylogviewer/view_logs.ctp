<!DOCTYPE html>
<h1>Log Viewer </h1>
<div id="log-form-div" title="">    
    </div>
<form name="viewlogform" id="viewlogformid">    
    <div id = "file_path_div">
        <input type= text name="path_to_file" id = "path_to_file_id" required></input>      
        <input type= button value ="View" name="view_file_logs" id = "view_file_logs"></input>
    </div>
    <?php
        $loading_div_id = "loading_div";
        echo $this->element("ajax_loading", array("divid" => $loading_div_id));
        ?>
</form>
<div id = "file_data_div">
    <table class= "logvaluetableclass" id="logvalue_table">
    <?php for($i =0; $i < 10; $i++) 
    {
        $count = $i + 1;
    ?>
        <tr>
    <?php $s_noid = "s_no_".$i;?>
    <?php $contentid = "content_".$i;?>
            <td width = "5%" id =<?php echo $s_noid;?>><?php echo $count;?></td>
            <td id = <?php echo $contentid;?>></td>
        </tr>
    <?php } ?>
    </table>
</div>
<div id ="nav_buttons">
    <table class="nav_table">
        <tr>
            <td id ="file_begin_id"><a class="active" href="#"><?php echo $this->Html->image('begin.png');?></a></td>
            <td id ="nav_prev_id"><a class="active" href="#"><?php echo $this->Html->image('prev.png');?></a></td>
            <td id ="nav_next_id"><a class="active" href="#"><?php echo $this->Html->image('next.png');?></a></td>
            <td id ="file_end_id"><a class="active" href="#"><?php echo $this->Html->image('end.png');?></a></td>
        </tr>
    </table>
</div>

<script>

    var page_no = 1;
    var total_page_count = 1;    
    
    function set_total_page_count()
    {      
        var request = $.ajax({url: '/LogViewer/mylogviewer/get_page_count/',
            type: 'POST',
            async: false
        });   
        request.done(function (data)
        {
            var o = JSON.parse(data);
            if (o["result"] === "Success")
            {
                total_page_count = o["msg"];
            }
        });   
    }
    
    $(document).ready()
    {
         page_no = 1;
         total_page_count = 1;
    }
    
    function clear_table_columns()
    {
      for(var i =0; i < 10; i++)
      {
        var contentid = "#content_" + i;
        $(contentid).html("");
      }
    }
    
    function check_file_path()
    {
        var file_path = $('#path_to_file_id').val();
        if(file_path === '')
        {
            alert("File Path cannot be empty");
            return false;
        }
        return true;
    }
    
    $("#view_file_logs").click(function (event)
    {  
        if(check_file_path() === false)
        {
            return false;
        }
        var file_path = $('#path_to_file_id').val();
        $("#loading_div").show();
        var request = $.ajax({url: '/LogViewer/mylogviewer/read_from_file/',
            type: 'GET',
            data: {rest_path: file_path, page_no: page_no}});   
        request.done(function (data)
        {
                        //alert(data);

            var o = JSON.parse(data);
            if (o["result"] === "Success")
            {
                $("#loading_div").hide();
                clear_table_columns();
                $("#log-form-div").html("");
                $("#log-form-div").removeClass("error-message");                
                var msg_obj = $.parseJSON(o["msg"]);
                $.each(msg_obj, function (index, value) {
                    var contentid = "#content_" + index;                    
                    if (value !== '')
                    {
                        $(contentid).html(value);
                    }
                });               
            }
            if (o["result"] === "Failed")
            {
                var msg = o["msg"];            
                $("#log-form-div").html("");                
                $("#log-form-div").addClass("error-message");
                $("#log-form-div").html(msg);
            }
            set_total_page_count();            
        });        
    });

    $("#nav_next_id").click(function (event)
    {        
        if(check_file_path() === false)
        {
            return false;
        }
        page_no++;
        if(page_no > total_page_count)
        {
            page_no = page_no - 1;
            $("#nav_next_id").attr("disabled",disabled);
            $("#nav_prev_id").removeAttr("disabled");
            alert("return next" + page_no);
            return false;
        }
        var file_path = $('#path_to_file_id').val();
        var request = $.ajax({url: '/LogViewer/mylogviewer/read_from_file/',
            type: 'GET',
            data: {rest_path: file_path, page_no: page_no}});
        request.done(function (data)
        {
            var o = JSON.parse(data);
            if (o["result"] === "Success")
            {
                clear_table_columns();
                $("#log-form-div").html("");
                $("#log-form-div").removeClass("error-message");
                var msg_obj = $.parseJSON(o["msg"]);
                $.each(msg_obj, function (index, value) {
                    var contentid = "#content_" + index;
                    $(contentid).html(value);
                });
            }
            if (o["result"] === "Failed")
            {
                var msg = o["msg"];            
                $("#log-form-div").html("");                
                $("#log-form-div").addClass("error-message");
                $("#log-form-div").html(msg);
            }
        });
    });
    
    $("#nav_prev_id").click(function (event)
    {       
        if(check_file_path() === false)
        {
            return false;
        }
        page_no--;
        if(page_no < 1)
        {
             page_no = page_no + 1;
             $("#nav_prev_id").attr("disabled",disabled);
             $("#nav_next_id").removeAttr("disabled");           
            //alert("return prev" + page_no);
            return false;
        }
        var file_path = $('#path_to_file_id').val();
        var request = $.ajax({url: '/LogViewer/mylogviewer/read_from_file/',
            type: 'GET',
            data: {rest_path: file_path, page_no: page_no}});
        request.done(function (data)
        {
            var o = JSON.parse(data);
            if (o["result"] === "Success")
            {
                clear_table_columns();
                $("#log-form-div").html("");
                $("#log-form-div").removeClass("error-message");
                var msg_obj = $.parseJSON(o["msg"]);
                $.each(msg_obj, function (index, value) {
                    var contentid = "#content_" + index;
                    $(contentid).html(value);
                });
            }
            if (o["result"] === "Failed")
            {
                var msg = o["msg"];            
                $("#log-form-div").html("");                
                $("#log-form-div").addClass("error-message");
                $("#log-form-div").html(msg);
            }
        });
    });
    
    $("#file_begin_id").click(function (event)
    {   
        if(check_file_path() === false)
        {
            return false;
        }
        page_no = 1;        
        $("#nav_next_id").removeAttr("disabled");
        $("#file_end_id").removeAttr("disabled");
        var file_path = $('#path_to_file_id').val();
        var request = $.ajax({url: '/LogViewer/mylogviewer/read_from_file/',
            type: 'GET',
            data: {rest_path: file_path,page_no: page_no}});
            request.done(function (data)
            {
                var o = JSON.parse(data);
                if (o["result"] === "Success")
                {
                    clear_table_columns();
                    $("#log-form-div").html("");
                    $("#log-form-div").removeClass("error-message");
                    var msg_obj = $.parseJSON(o["msg"]);
                    $.each(msg_obj, function (index, value) {
                        var contentid = "#content_" + index;
                        $(contentid).html(value);
                    });
                }
                if (o["result"] === "Failed")
                {
                    var msg = o["msg"];            
                    $("#log-form-div").html("");                
                    $("#log-form-div").addClass("error-message");
                    $("#log-form-div").html(msg);
                }
            });
    });
    
    $("#file_end_id").click(function (event)
    {        
        if(check_file_path() === false)
        {
            return false;
        }
        page_no = total_page_count;
        $("#nav_prev_id").removeAttr("disabled");
        $("#file_begin_id").removeAttr("disabled");
            
        var file_path = $('#path_to_file_id').val();
        var request = $.ajax({url: '/LogViewer/mylogviewer/move_to_end/',
            type: 'GET',
            data: {rest_path: file_path}});
        request.done(function (data)
        { 
            var o = JSON.parse(data);
            if (o["result"] === "Success")
            {
                clear_table_columns();
                $("#log-form-div").html("");
                $("#log-form-div").removeClass("error-message");
                var msg_obj = $.parseJSON(o["msg"]);
                    $.each(msg_obj, function (index, value) {
                        var contentid = "#content_" + index;
                        $(contentid).html(value);
                    });
            }
            if (o["result"] === "Failed")
            {
                var msg = o["msg"];            
                $("#log-form-div").html("");                
                $("#log-form-div").addClass("error-message");
                $("#log-form-div").html(msg);
            }
        });
    });
</script>
