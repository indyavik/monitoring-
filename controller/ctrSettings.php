<?php

session_start();

if(!$_SESSION['logged_in']) {
    exit('No direct Access');
}

include ("../dbconf.php");
include ("../frontend_func.php");

/* GLOBAL */
     

/* START */

     #  ACTION LISTENER settings
     #  Purpose: accepts raw data send from settings.php and
     #           evaluates and fetch records.

     if(isset($_POST['cmd'])){
         switch($_POST['cmd']){
             /* Param   : cmd = list
              * Desc    : list of customer url
              * return  : html format
              */
           case 'list':
                $chkUrl = get_cust_url();
                if(count($chkUrl)){
                    foreach($chkUrl as $data){
                        echo "
                            <tr>
                            <td>{$data['url']}</td>
                            <td>{$data['port']}</td>
                            <td>{$data['check_type']}</td>
                            <td>{$data['status']}</td>".
                            '<td><img title="EDIT" class="edit_url" alt="'.$data['id'].'" src="images/edit.jpg" width="24" height="20"  style="cursor:pointer;"/>
                            <img title="DELETE" class="delete_url" alt="'.$data['id'].'" src="images/delete.jpg" width="24" height="20" style="cursor:pointer;margin-left:2px;"/></td>'.
                            "</tr>";
                    }
                }else{
                    echo '<tr><td colspan="5" class="no-data">No Data</td></tr>';
                }
                exit();
                break;

             /* Param   : cmd = edit
              * Desc    : update customer info in URL table
              * return  : string
              */
           case 'edit':
                @$id        = $_POST['uid'];
                @$url       = $_POST['url'];
                @$status    = $_POST['status'];
                @$port      = $_POST['port'];
                @$type      = $_POST['type'];
                @$chk       = $_POST['checked'];
                
                if(!$id)        exit('Required URL ID.');
                if(!$url)       exit('Required Check URL');
                if(!$status)    exit('Required Check status.');
                if(!$port)      exit('Required Check port.');
                if(!$type)      exit('Required Check type.');
                
                if(!check_url($url)){
                    exit($url.' is not valid url');
                }

                if(!$chk){
                    $chk = 0;
                }

                $data = array(
			"url" 		=> $url,
			"status" 	=> $status,
			"port"          => $port,
                        "check_type"    => $type,
                        "checked"       => $chk
		);

                set_update_url($data,$id);
                exit('updated');
                break;

           case 'add':

                if(!get_has_available_url()){
                    exit("you have NO additional checks available ! please upgrade.!");
                }

                @$url       = $_POST['url'];
                @$status    = $_POST['status'];
                @$port      = $_POST['port'];
                @$type      = $_POST['type'];
                @$chk       = $_POST['checked'];

                if(!$url)       exit('Required Check URL');
                if(!$status)    exit('Required Check status.');
                if(!$port)      exit('Required Check port.');
                if(!$type)      exit('Required Check type.');

                if(!check_url($url)){
                    exit($url.' is not valid url');
                }

                if(!$chk){
                    $chk = 0;
                }

                if(intval(get_num_checks()) == 0){
                    $chkNum = 1;
                }else{
                    $chkNum = intval(get_num_checks()) + 1;
                }

                $data = array(
			"url" 		=> $url,
			"status" 	=> $status,
			"port"          => $port,
                        "check_type"    => $type,
                        "cust_id"       => $_SESSION['cust_id'],
                        "url_nm"        => $chkNum
		);

               set_save_url($data, $chk);

               exit('saved');
               break;

           case 'delete':
               @$id        = $_POST['uid'];
               if(!$id)        exit('Required URL ID.');
               
               set_delete_url($id);
               exit('deleted');
               break;

           case 'notify':
                $data = array(
			"check_period" 	=> $_POST['check_period'],
			"summary" 	=> $_POST['summary'],
			"email" 	=> $_POST['email'],
			"sms" 		=> $_POST['sms'],
			"call" 		=> $_POST['call'],
			"notify"	=> $_POST['notify']
		);

                $result = validate_notify($data);
                if($result['success']) {
                    foreach($_POST['notify'] as $value) {

                            if($value == "email")
                                    $notify = "E|";
                            elseif($value == "sms")
                                    $notify .= "S|";
                            elseif($value == "call")
                                    $notify .= "P|";
                            elseif($value == "web")
                                    $notify .= "N|";

                    }

                    $notify  = substr($notify,0,-1);

                    $data = array(
                                    "check_period" 	=> $_POST['check_period'],
                                    "summary" 	=> $_POST['summary'],
                                    "email" 	=> $_POST['email'],
                                    "sms" 		=> $_POST['sms'],
                                    "call" 		=> $_POST['call'],
                                    "notify"	=> $notify
                            );

                    update_notify($data);
                    exit('notified');
                }else{
                    exit($result['error']);
                }
                break;

         }

     }

     #  ACTION LISTENER settings
     #  Purpose: accepts raw data send from home.php and
     #           evaluates confirmation of email.

    if(isset($_POST['conf'])){
        send_email($_SESSION['email'],'',md5($_SESSION['cust_id']));
        exit('Confirmation  Successfully resend to <strong>'.$_SESSION['email'].'</strong>');
    }

    exit('nothing');

 /* END */


?>