<?php
/* 
ELIGEO CRM VERSION 1.0
Module: Contacts
Filename: import.php
File Version: 1.0
Date Modified: 03/22/2010
Author: Joel Collyer
*/
//authenticate();

$tmp = "";
$step = false;
//Allow the script to run for two minutes max.
set_time_limit(120);
error_reporting(E_ALL);
ini_set('display_errors', 1);
ini_set('max_execution_time', 0);


//Connect to database server.
function connect() {
	
	global $host;
	global $user;
	global $pass;

$host = "localhost";
$db = "tealcrm.localhost.com";
$user = "root";
$pass = "root";  
  $connection = mysql_connect($host, $user, $pass)
				  or die('Error connecting to database.');
  
 // mysql_select_db($database, $connection);
}

//Disconnect
function disconnect() {
	
}

//Connect to specific database
function connect_db($database) {
	global $host;
	global $user;
	global $pass;
	global $master_db;
	
	if(!$database) {
		$database = $master_db;
	}
	
	$connection = mysql_connect($host, $user, $pass) or die(mysql_error);
	mysql_select_db($database, $connection) or die(mysql_error());
}

$host = "localhost";
$db = "tealcrm.localhost.com";
$user = "root";
$pass = "root";
/*
$host = "secretcrm.eligeo.com";
$db = "tealcrm.localhost.com";
$user = "eligeo_secretcrm";
$pass = "GeoKits@101";
*/

//Define DB table names as drop down options
function table_drop_down($table, $name='table', $val=false, $default_val=false, $default_text=false, $disallowed_fields=false) {
	
	global $db;
  	$db->connect();
	
	$q_table = "DESCRIBE ".$table;
	$t_table = $db->select($q_table) or die($db->last_error);
	$r_table = $db->get_row($t_table);
		
	$out = '<select name="'.$name.'" id="'.$name.'">';
	if($default_text) {
		$out.= '<option value="'.$default_val.'">'.$default_text.'</option>';
	}
	do {
		$col_name = $r_table['Field'];
		$formatted_name = ucwords(str_replace("_"," ",$col_name));
		
		if(!eregi("_id",$col_name) && !in_array($col_name,$disallowed_fields)) {
			if($val == $col_name) { $extra = ' selected="selected"'; } else { $extra = false; }
			$out.= '<option value="'.$col_name.'"'.$extra.'>'.$formatted_name.'</option>';
		}
	} while($r_table = $db->get_row($t_table));
	$out.= '</select>';
	
	return $out;
}

function step_two_table($upload_path, $delimiter="", $drop_first_row) {
	//loop through the file and generate the step 2 form arrays
	if($handle = fopen($upload_path, "r")){
		$count = 0;
		$data_arr = array('');
		
		if($delimiter != "") $data = fgetcsv($handle, 0, $delimiter);
		else $data = fgetcsv($handle, 0);
		while ($data !== FALSE && $count<3) {
			//how many columns are in the CSV file?
			$cols = count($data);
			
			//add this row of the CSV file to the data array
			array_push($data_arr, $data);
			$count++;
		}
		
		//Build drop down menus for building association information
		$drop_downs = array('');
		$dont_show = array('people_id', 'date_entered', 'date_modified', 'modified_user_id', 'assigned_user_id', 'created_by', 'salutation_id', 'lead_source_id', 'reports_to_id', 'do_not_call', 'email_opt_out');
		for($i=0; $i<$cols; $i++) {
			$drop_name = 'col'.$i;
			//global $$drop_name;
			$dropdown = table_drop_down('sc_people', $drop_name, "col".$i, false, '-- do not import this field --', $dont_show);
			array_push($drop_downs, $dropdown);
		}
		
		//remove the dead item from the start of the arrays.
		array_shift($drop_downs);
		array_shift($data_arr);
		
		//Loop through data to create table rows
		for($i=0; $i<$cols; $i++) {
			$tmp = array('drop_down' => $drop_downs[$i],
						'col1' => $data_arr[0][$i],
						'col2' => $data_arr[1][$i],
						'col3' => $data_arr[2][$i]
					);
			$result[$i] = $tmp;
		}
		return $result;
	} else {
		return false;
	}
}

if(isset($_FILES['csv'])){
	foreach($_POST as $key => $value) {
		//$smarty->assign($key, $value);
		//$$key = $value;
	}

	if($_POST && ($step=='1' || !$step)) {
		//STEP 1 - upload csv file, build forms for step 2
		
		$ext = strtolower(substr(trim($_FILES['csv']['name']), -3));
		//validate
		//if($file_error = file_error_check($_FILES['csv']['error'])){
		if($_FILES['csv']['error'] > 0){
			$file_error = $_FILES['csv']['error'];
			$error.= $file_error;
			echo "file valid 1<br/>"; 
		//} else if(!preg_match(".csv$",$_FILES['csv']['name'])) {
		}elseif($ext !== 'csv'){
			$error.= '<li>The file you uploaded is not a CSV file.</li>';
			echo "file not valid 1<br/>"; 
		}
		
		if(!$error) {
			//move the file
			$new_name = strtolower($_SESSION['database'].time().".csv");
			
			$upload_path = "temp/".basename($new_name);
			
			if(move_uploaded_file($_FILES['csv']['tmp_name'], $upload_path)) {
				$table_array = step_two_table($upload_path, $delimiter, $drop_first_row);
				if(!$table_array) {
					$error.='<li>An error has occured, please go back and try again.</li>';
				} else {
					//$table_array
					var_dump($table_array);
				}
			} else {
				$error.= '<li>The file could not be moved. Please contact the administrator.</li>';
			}
		}
		
		//next step
		if(!$error) {
			$step = 2;
		}else{
			echo $error;
		}
		
	} else if($_POST && $step=='2') {
		//STEP 2 - run queries based on the data submitted through the step 2 form.
		
		//set timestamp to apply to all contacts.
		$timestamp = time();
		
		//validate
		if(!$upload_path) {
			$step = 1;
			$error.='<li>File name lost, importing restarted.</li>';
		}
		
		//Open the CSV file, build queries, run queries, and specify results.
		if(!$error) {
			$completed = 0;
			$count = 0;
			$handle = fopen($upload_path, "r");
			
			while (($data = fgetcsv($handle, 0, $delimiter)) !== FALSE) {
				if($drop_first_row=='1' && $count=='0') {
					//ignore this column and move on with your life.
				} else {
					$cols = count($data);
					for($i=0;$i<$cols;$i++) {
						$col_name = 'col'.$i;
						
						if($$col_name) {
							//fix the data to escape bad characters and setup the data for import.
							$tmp[$$col_name] = $data[$i];
						} // end if col_name
					} // end for loop for columns
					$result[$count] = $tmp;
				} // end if else for column 1 ignore check
				$count++;
			} // end while for data
			
			$complete = array('');
			foreach($result as $key => $value) {
				//build an insert query for this data	
				$part1 = false;
				$part2 = false;
			
				//setup required field switches
				#All these must be set to 1 for the input to be valid.
				$first_name = 0;
				$last_name = 0;

				foreach($value as $column => $content) {
					//Update switch information for valid fields.
					#This should set all switches to 1 while completing the loop.
					if($column=='first_name') { $first_name = 1; }
					if($column=='last_name') { $last_name = 1; }
					
					//add this field to the query
					if($column && $content) {
						$part1.= " ".$column.",";
						$part2.= " '".addslashes($content)."',";
					}
				}
				
				$part1.= " created_by,";
				$part2.= " '".$_SESSION['user_id']."',";
				
				$part1.= " import_timestamp,";
				$part2.= " '".$timestamp."',";

				$part1 = substr($part1, 0, strlen($part1)-1);
				$part2 = substr($part2, 0, strlen($part2)-1);
				$part1 = substr($part1, 1, strlen($part1)-1);
				$part2 = substr($part2, 1, strlen($part2)-1);
				$query = "INSERT INTO people (".$part1.") VALUES (".$part2.")";
				
				//the query has been built, check to make sure that all the required field switches are set to 1
				if($query && ($first_name==1 && $last_name==1)) {
					global $db;
					$db->connect();
					
					if(ereg("INSERT INTO", $query)) {
						$id = $db->insert_sql($query);
						if(!$id) {
							$error.='<li>The query was not run properly. '.$db->last_error.' ('.$query.')</li>';
						}
					} else if(ereg("UPDATE", $query)) {
						$result = $db->update_sql($query);
						if(!$result) {
							$error.='<li>The query was not run properly. '.$db->last_error.' ('.$query.')</li>';
						}
					} else {
						$error.='<li>The query was not run properly. ('.$query.')</li>';
					}
					
					if($id) {
						array_push($complete, $id);
					}
					$successful++;
				} else {
					$breakforerror = true;
				}
			}
			
			if($breakforerror) {
				$error.='<li>The person was not imported. Please make sure all the required fields have been mapped.</li>';	
			} else {
				array_shift($complete);
				$temp_array = false;
				$i=0;
				foreach($complete as $key => $value) {
					//construct table for step three.
					$q = "SELECT * FROM people WHERE contact_id='".$value."'";
					$t = $db->select($q);
					while($r = $db->get_row($t)){
						$tmp = array(
							'contact_id'=>$r['contact_id'],
							'first_name'=>$r['first_name'],
							'last_name'=>$r['last_name'],
							'email'=>$r['email1']
						);
						$table_array[$i++]=$tmp;
					}
				}
				
			} // end if breakforerror
		}
		
		//final step
		if(!$error) {
			$added = $successful;
			$step = 3; //completed
		} else {
			$step = 2;
			$table_array = step_two_table($upload_path, $delimiter, $drop_first_row);
		}
	}
}
if(!$step) { $step = '1'; }


/*
//assign variables for smarty
$smarty->assign('added', $added);
$smarty->assign('step', $step);
$smarty->assign('timestamp', $timestamp);
$smarty->assign('table_array', $table_array);
$smarty->assign('drop_first_row', $drop_first_row);
$smarty->assign('delimiter', $delimiter);
$smarty->assign('upload_path', $upload_path);


$smarty->assign('completed', $completed);
$smarty->assign('warn_message', $warn_message);
$smarty->assign('error', $error);
$smarty->display("contacts/actions/import.tpl");
*/


?>
Fill this form
<form action="#" method="post" enctype="multipart/form-data">
	<input type="file" name="csv">
	<input type="submit" name="submit" value="Submit">
</form>