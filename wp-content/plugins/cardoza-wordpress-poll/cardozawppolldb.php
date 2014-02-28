<?php
global $CWP_db_version;
$CWP_db_version = "1.4";

function CWP_install(){
    global $wpdb;
    global $CWP_db_version;
    
    $poll_table = $wpdb->prefix."cwp_poll";
    $poll_answer_table = $wpdb->prefix."cwp_poll_answers";
    $poll_logs_table = $wpdb->prefix."cwp_poll_logs";
    
    $create_poll_table = "CREATE TABLE ".$poll_table." (
				id int(10) not null auto_increment,
				name tinytext not null,
                                question tinytext not null,
                                answer_type tinytext not null,
                                no_of_answers tinytext null,
                                start_date tinytext not null,
                                end_date tinytext not null,
                                total_votes int(10) not null,
                                poll_type tinytext null,
				UNIQUE KEY id (id));";
    
    $create_poll_answer_table = "CREATE TABLE ".$poll_answer_table." (
				id int(10) not null auto_increment,
				pollid int(10) not null,
                                answer tinytext not null,
                                votes int(10) not null,
				UNIQUE KEY id (id));";
    
    $create_poll_logs_table = "CREATE TABLE ".$poll_logs_table." (
				id int(10) not null auto_increment,
				pollid int(10) not null,
                                ip_address tinytext not null,
                                country tinytext null,
                                state tinytext null,
                                city tinytext null,
                                polledtime tinytext null,
				userid tinytext null,
                                answerid tinytext null,
				UNIQUE KEY id (id));";
        
    require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
    dbDelta($create_poll_table);
    dbDelta($create_poll_answer_table);
    dbDelta($create_poll_logs_table);
    
    add_option("CWP_db_Version", $CWP_db_version);
}

function cwp_db_update(){
    
    global $wpdb;
    global $CWP_db_version;
    
    $installed_version = get_option('CWP_db_Version');
    $poll_logs_table = $wpdb->prefix."cwp_poll_logs";
    $poll_table = $wpdb->prefix."cwp_poll";
    
    if(!empty($installed_version) && $installed_version == '1.0'){
        if($installed_version != $CWP_db_version){
            $create_poll_logs_table = "CREATE TABLE ".$poll_logs_table." (
				id int(10) not null auto_increment,
				pollid int(10) not null,
                                ip_address tinytext not null,
                                country tinytext null,
                                state tinytext null,
                                city tinytext null,
                                polledtime tinytext null,
				userid tinytext null,
                                answerid tinytext null,
				UNIQUE KEY id (id));";
            $alter_poll_table = "ALTER TABLE ".$poll_table."
				ADD poll_type tinytext null";
            require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
            dbDelta($create_poll_logs_table);
            $wpdb->query($alter_poll_table);
        }
    }
    
    if(!empty($installed_version) && $installed_version == '1.1'){
        if($installed_version != $CWP_db_version){
            $create_poll_logs_table = "CREATE TABLE ".$poll_logs_table." (
				id int(10) not null auto_increment,
				pollid int(10) not null,
                                ip_address tinytext not null,
                                country tinytext null,
                                state tinytext null,
                                city tinytext null,
                                polledtime tinytext null,
				userid tinytext null,
                                answerid tinytext null,
				UNIQUE KEY id (id));";
            $alter_poll_table = "ALTER TABLE ".$poll_table."
				ADD poll_type tinytext null";
            require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
            dbDelta($create_poll_logs_table);
            $wpdb->query($alter_poll_table);
        }
    }
    
    if(!empty($installed_version) && $installed_version == '1.2'){
        if($installed_version != $CWP_db_version){
            $create_poll_logs_table = "ALTER TABLE ".$poll_logs_table."
                                    ADD answerid tinytext null";
            
            $alter_poll_table = "ALTER TABLE ".$poll_table."
                                    ADD poll_type tinytext null";
            
            require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
            dbDelta($create_poll_logs_table);
            $wpdb->query($alter_poll_table);
        }
    }
    if(!empty($installed_version) && $installed_version == '1.3'){
        if($installed_version != $CWP_db_version){
            $alter_poll_table = "ALTER TABLE ".$poll_table." ADD poll_type tinytext null";
            require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
            $wpdb->query($alter_poll_table);
        }
    }
    
    
    update_option("CWP_db_Version", $CWP_db_version);
}
?>
