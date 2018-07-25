<?php
	

echo "QQQQQ";

	$deal_query = "SELECT * from sc_deals where (sales_stage_id = '".$sales_stage_key."')";
	$deal_result = $this->db->query($deal_query)->result();
	$num_deals = $this->db->affected_rows();


	$dollar_amount_query = "SELECT SUM(value) as total from sc_deals where (sales_stage_id = '".$sales_stage_key."')";
	$da_result = $this->db->query($dollar_amount_query);
	$da_assoc = $da_result->result();
	$deal_total = $da_assoc[0]->total;
	
	if ($deal_total != NULL) { $deal_total = number_format($deal_total,2); }
else { $deal_total = 0.00;}

			
			 $key_edit = str_replace(" ","_",$sales_stage_value);
			 $key_edit = str_replace("/","_",$key_edit);
			 $key_edit = str_replace(".","_",$key_edit);

echo "<p class='ss_bold'>".$sales_stage_value."</p><br>";

if ($num_deals > 0) {
echo "<p class='ss_amount'>";	
echo "$".$deal_total."<br><br>";
if ($num_deals == 1) { echo "1 Deal"; }
else { echo $num_deals." Deals"; } 
	echo "</p>";
}

else {
	
echo "<p class='ss_amount'>";
echo "0 Deals";	
}

echo "QQQQQ";
