<?php
/*
mysql2i.func.php rev 3
member of mysql2i.class.php ver 1.3
*/

//predifined fetch constants
define('MYSQL_BOTH',MYSQLI_BOTH);
define('MYSQL_NUM',MYSQLI_NUM);
define('MYSQL_ASSOC',MYSQLI_ASSOC);

function _mysql_affected_rows($link=null){
    
    return mysql2i::mysql_affected_rows($link);
    
}

function _mysql_client_encoding($link=null){
    
    return mysql2i::mysql_client_encoding($link);
    
}

function _mysql_close($link=null){
    
    return mysql2i::mysql_close($link);
    
}

function _mysql_connect($host = '',$username = '',$passwd = '',$new_link = false,$client_flags = 0){
    
    return mysql2i::mysql_connect($host,$username,$passwd);
    
}

function _mysql_create_db($database_name,$link=null){
    
    return mysql2i::mysql_create_db($database_name,$link);
    
}

function _mysql_data_seek($result,$offset){
    
    return mysql2i::mysql_data_seek($result,$offset);
    
}

function _mysql_db_name($result,$row,$field=null){
    
    return mysql2i::mysql_db_name($result,$row,$field);
    
}

function _mysql_db_query($database,$query,$link=null){
    
    return mysql2i::mysql_db_query($database,$query,$link);
    
}

function _mysql_drop_db($database,$link=null){
    
    return mysql2i::mysql_drop_db($database,$link);
    
}

function _mysql_errorno($link=null){
    
    return mysql2i::mysql_errorno($link);
    
}

function _mysql_exec($query, $link=null){

    return mysql2i::mysql_query($query, $link);

}

function _mysql_error($link=null){
    
    return mysql2i::mysql_error($link);
    
}

function _mysql_escape_string($escapestr){
    
    return mysql2i::mysql_escape_string($escapestr);
    
}

function _mysql_fetch_array($result,$resulttype=MYSQLI_BOTH){
    
    return mysql2i::mysql_fetch_array($result,$resulttype);
    
}

function _mysql_fetch_assoc($result){
    
    return mysql2i::mysql_fetch_assoc($result);
    
}

function _mysql_fetch_field($result,$field_offset=null){
    
    return mysql2i::mysql_fetch_field($result,$field_offset);
    
}

function _mysql_fetch_lengths($result){
    
    return mysql2i::mysql_fetch_lengths($result);
    
}

function _mysql_fetch_object($result,$class_name='stdClass',$params=array()){
    
    return mysql2i::mysql_fetch_object($result,$class_name,$params);
    
}

function _mysql_fetch_row($result){
    
    return mysql2i::mysql_fetch_row($result);
    
}

function _mysql_field_flags($result,$field_offset){
    
    return mysql2i::mysql_field_flags($result,$field_offset);
    
}

function _mysql_field_len($result,$field_offset){
    
    return mysql2i::mysql_field_len($result,$field_offset);
    
}

function _mysql_field_name($result,$field_offset){
    
    return mysql2i::mysql_field_name($result,$field_offset);
    
}

function _mysql_field_seek($result,$fieldnr){
    
    return mysql2i::mysql_field_seek($result,$fieldnr);
    
}

function _mysql_field_table($result,$field_offset){
    
    return mysql2i::mysql_field_table($result,$field_offset);
    
}

function _mysql_field_type($result,$field_offset){
    
    return mysql2i::mysql_field_type($result,$field_offset);
    
}

function _mysql_free_result($result){
    
    return mysql2i::mysql_free_result($result);
    
}

function _mysql_get_client_info(){
    
    return mysql2i::mysql_get_client_info();
}

function _mysql_get_host_info($link=null){
    
    return mysql2i::mysql_get_host_info($link);
    
}

function _mysql_get_proto_info($link=null){
    
    return mysql2i::mysql_get_proto_info($link);
    
}

function _mysql_get_server_info($link=null){
    
    return mysql2i::mysql_get_server_info($link);
    
}

function _mysql_info($link=null){
    
    return mysql2i::mysql_info($link);
    
}

function _mysql_insert_id($link=null){
    
    return mysql2i::mysql_insert_id($link);
    
}

function _mysql_list_dbs($link=null){
    
    return mysql2i::mysql_list_dbs();
    
}

function _mysql_list_fields($database_name,$table_name,$link=null){
    
    return mysql2i::mysql_list_fields($database_name,$table_name,$link);
    
}

function _mysql_list_processes($link=null){
    
    return mysql2i::mysql_list_processes($link);
    
}

function _mysql_list_tables($database,$link){
    
    return mysql2i::mysql_list_tables($database,$link);
    
}

function _mysql_num_fields($result){
    
    return mysql2i::mysql_num_fields($result);
    
}
function _mysql_num_rows($result){
    
    return mysql2i::mysql_num_rows($result);
    
}
function _mysql_numrows($result){

    return mysql2i::mysql_num_rows($result);

}

function _mysql_pconnect($host = '',$username = '',$passwd = '',$new_link = false,$client_flags = 0){
    
    return mysql2i::mysql_pconnect($host,$username,$passwd,$new_link,$client_flags);
    
}

function _mysql_ping($link=null){
    
    return mysql2i::mysql_ping($link);
    
}

function _mysql_query($query,$link=null){
    
    return mysql2i::mysql_query($query,$link);
    
}

function _mysql_real_escape_string($escapestr,$link=null){
    
    return mysql2i::mysql_real_escape_string($escapestr,$link);
    
}

function _mysql_result($result,$row,$field=null){
    
    return mysql2i::mysql_result($result,$row,$field);
    
}

function _mysql_select_db($dbname,$link=null){
    
    return mysql2i::mysql_select_db($dbname,$link);
    
}

function _mysql_set_charset($charset,$link=null){
    
    return mysql2i::mysql_set_charset($charset,$link);
    
}

function _mysql_stat($link=null){
    
    return mysql2i::mysql_stat($link);
    
}

function _mysql_tablename($result,$row,$field=null){
    
    return mysql2i::mysql_tablename($result,$row,$field);
    
}

function _mysql_thread_id($link=null){
    
    return mysql2i::mysql_thread_id($link);
    
}

function _mysql_unbuffered_query($query,$link=null){
    
    return mysql2i::mysql_unbuffered_query($query,$link);
    
}
?>
