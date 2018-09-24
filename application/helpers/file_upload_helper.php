<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
// -----------------------------------------------------------------------
/**
 * File upload helper
 *
 * @package TealCRM
 * @subpackage Controller
 * @author SecretCRM Team
 * @since 1.1
 * @version 1.1
 * Purpose: To assist with uploading and managing of files

 */

  /**
 * upload_file
 *
 * This function will decide which is the name_value for the specific module.  Only 1 name value exists for a module.
 *
 * @access    public
 * @param    string    the module we are working with
 * @param    string    the record we are dealing with
 * @param   bool    returns the display name
 * @return    string
 */
function upload_file($module, $record_id, $created_by, $CI){

   // $CI =& get_instance();

    $config['upload_path'] = APPPATH.'attachments';
    $config['allowed_types'] = 'jpg|png|doc|docx|xml|pdf|html|txt|csv|xls|xlsx|pptx|ppt';
    $config['max_size']	= '25600';//25mb

    $CI->load->library('upload',$config);

    $attachment_warning = "none";

    $CI->upload->do_upload("attach_file");
    $file_data = $CI->upload->data();

    pr($file_data);

    if ( $CI->upload->do_upload("attach_file") ){
        $file_data = $CI->upload->data();
        
        $file_upload = array (
            'date_entered' => gmdate('Y-m-d H:i:s'),
            'created_by' => $created_by,
            'related_module' => $module,
            'related_record_id' => $record_id,
            'filename_original' => $file_data['file_name'],
            'filename_mimetype' => $file_data['file_type']
        );

        if ( ! $CI->db->insert($CI->config->item('db_prefix')."file_uploads", $file_upload) ){
            notify_set( array('status'=>'error', 'message'=> $_SESSION['language']['global']['error_uploading']) );
        }

        return true;
    }else{
        $attachment_warning = $CI->upload->display_errors();
        $file_data = $CI->upload->data();
        echo $file_data['file_type'];
        echo $CI->upload->display_errors(); exit();
        notify_set( array('status'=>'error', 'message'=> $_SESSION['language']['global']['error_uploading']) );
        return false;
    }

}
