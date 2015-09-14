/*!
 * JavaScript Library 
 * CHECKwoo
 *
 * Copyright 2011, Vestige System
 * Released under Vestige Licence
 *
 * Date: April 11 2011 14:20:19
 */

$(document).ready(function(){
    
    /* INITIALIZE */    
    var cport = new Object();
    
    cport.http  = '80';
    cport.imap  = '143';
    cport.pop3  = '110';
    cport.smtp  = '25';
    cport.ssmtp = '465';
    cport.ftp   = '21';
    cport.sftp  = '22';

    
    $(".frmCheckURL").dialog({
        title: 'Check Panel',
        autoOpen: false,
        width: 600,
        modal: true,
        resizable: false,
        draggable: false,
        buttons: {
                "Ok": function() {
                      if($("input[name='uid']").val() == ''){
                        add_url();
                      }else{
                        update_url();
                      }
                },
                "Cancel": function() {
                    close();
                    clear();
                }
        },
        beforeClose: function(){
            clear();
        }
    });

    /* EVENTS */
    $(".add_new_url").click(function(e){
        e.preventDefault();
         $("input[name='check_url']").val('http://');
        $('.frmCheckURL').dialog('open');
    });

    $(".edit_url").live('click',function(){
        clear();
        var id= $(this).attr('alt');

        $("input[name='uid']").val(id);
        $("input[name='check_url']").val($(this).parent().parent().find("td").eq(0).text());
        $("input[name='check_port']").val($(this).parent().parent().find("td").eq(1).text());
        $("select[name='check_type']").val($(this).parent().parent().find("td").eq(2).text());
        $("input[name='check_status']")[0].checked = true;

        if($(this).parent().parent().find("td").eq(3).text() == 'active'){
            $("input[name='check_status']")[0].checked = true;
        }else{
            $("input[name='check_status']")[1].checked = true;
        }

       $('.frmCheckURL').dialog('open');

       $(".tbl-checkurl tbody td").css('background-color','#D4D4D4');
       $(this).parent().parent().find("td").css('background-color','#0099CC');
       
    });

    $(".delete_url").live("click",function(){
        var id= $(this).attr('alt');
        
        $("input[name='uid']").val(id);
         var where_to= confirm('Are you sure do you want to delete '+$(this).parent().parent().find("td").eq(0).text()+' URL?');
         if (where_to== false) return false;
         delete_url();
         return true;
    });

    $(".tbl-checkurl tbody tr").live("click",function(){
       $(".tbl-checkurl tbody td").css('background-color','#D4D4D4');
       $(this).find("td").css('background-color','#0099CC');
    });

    $("#check_type").change(function (){
        var port_type = $(this).val();       
          $.each(cport,function(el,data){
              if($.trim(port_type) == el){
                  $("input[name='check_port']").val(data);
                  return false;
              }
              return true;
          });
    });

    $(".save_changes").click(function(){
        notified();
    })

    $(".show_msg").live("click",function(){
        $(this).hide();
    });

    /* FUNCTIONS */
    function add_url(){
        var has_chk;

        if($("input[name='check_url']").val()==''){
                err_msg('Check URL is required');
                $("input[name='check_url']").focus();
                return false;}

        if($("input[name='check_port']").val()==''){
                err_msg('Check Port is required');
                $("input[name='check_port']").focus();
                return false;}

        if($("input[name='check_now']")[0].checked){
            has_chk = 1;
        }else{
            has_chk = 0;
        }

        $.post('controller/ctrSettings.php',{
            url: $("input[name='check_url']").val(),
            status: $("input[name='check_status']:checked").val(),
            port:$("input[name='check_port']").val(),
            type:$("select[name='check_type']").val(),
            checked: has_chk,
            cmd: 'add'
        },function(response){
            if($.trim(response) =='saved'){
                show_list();
                close();
                clear();
                window.location = "settings.php";           
            }else{
                err_msg(response);
            }
        })

        return '';
    }
    
    function update_url(){
        var has_chk;

        if($("input[name='check_url']").val()==''){
                err_msg('Check URL is required');
                $("input[name='check_url']").focus();
                return false;}

        if($("input[name='check_port']").val()==''){
                err_msg('Check Port is required');
                $("input[name='check_port']").focus();
                return false;}
                
        if($("input[name='check_now']")[0].checked){
            has_chk = 1;
        }else{
            has_chk = 0;
        }

        $.post('controller/ctrSettings.php',{
            uid: $("input[name='uid']").val(),
            url: $("input[name='check_url']").val(),
            status: $("input[name='check_status']:checked").val(),
            port:$("input[name='check_port']").val(),
            type:$("select[name='check_type']").val(),
            checked: has_chk,
            cmd: 'edit'
        },function(response){
            if($.trim(response) =='updated'){
                show_list();
                close();
                clear();
            }else{
                err_msg(response);
            }
        })

        return '';
    }

    function delete_url(){
        $.post('controller/ctrSettings.php',{
            uid: $("input[name='uid']").val(),
            cmd: 'delete'
        },function(response){
            if($.trim(response) =='deleted'){
                clear();
                show_list(); 
                window.location = "settings.php";            
            }else{
                err_msg(response);
            }
        })
        
    }

    function notified(){

    var data = new Array();

    $("input[name='notify[]']:checked").each(function() {
            data.push($(this).val());
    });

        $.post('controller/ctrSettings.php',{
            check_period: $("input[name='check_period']").val(),
            summary: $("input[name='summary']").val(),
            email: $("input[name='email']").val(),
            sms: $("input[name='sms']").val(),
            call:$("input[name='phonenum']").val(),
            'notify[]':data,
            cmd: 'notify'
        },function(response){
            response = $.trim(response);
            
            if($.trim(response) =='notified'){
               $(".show_msg").show();
               $(".msg-notify").html('Notification saved.!');
            }else{
               $(".show_msg").show();
               $(".msg-notify").html(response);
            }
        })
    }

    function close(){
        $(".frmCheckURL").dialog("close");
    }
    
    function clear(){
        $("input[name='check_url']").val('');
        $("select[name='check_type']").val('http');
        $("input[name='check_port']").val('80');
        $("input[name='check_status']")[0].checked = true;
        $("input[name='uid']").val('');
        $("input[name='check_now']")[0].checked = true;
        $(".err-cont").hide();$(".err-msg").html('');
    }

    function err_msg(msg){
        $(".err-cont").show();
        $(".err-msg").html(msg);
    }

    function lst_action(id){
        var img_edit    = '<img title="EDIT" class="edit_url" alt="'+id+'" src="images/edit.jpg" width="24" height="20"  style="cursor:pointer;"/>';
        var img_del     = '<img title="DELETE" class="delete_url" alt="'+id+'" src="images/delete.jpg" width="24" height="20" style="cursor:pointer;margin-left:2px;"/>';

        return '<td style="padding-top:10px;" >'+img_edit+img_del+'</td>';
    }


    function show_list(){
        $.post('controller/ctrSettings.php',{cmd: 'list'},function(response){
            $(".tbl-checkurl tbody").html(response);
        })
    }

});



