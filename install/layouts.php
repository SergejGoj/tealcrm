<?php

// placeholder for installer stuff



// Array for COMPANIES
$framework = 
	array(
	  	array ( "Overview" =>	  
		  		array ("company_name", "assigned_user_id"),
				array ("phone_work", "company_type"),
				array ("email1", "email2"),  			  
		),
		array ("Address Info" =>
				array ("address1", "address2"),
				array ("city", "province"),
				array ("postal_code", "country"),  
		),
		array ("Other Info" =>
				array ("lead_status_id", "lead_source_id"),
				array ("webpage", "industry"),
				array ("phone_fax", "description"),  
				array ("do_not_call", "email_opt_out")
		)
    );
    

// Array for PEOPLE
$framework = 
	array(
	  	array ( "Overview" =>	  
		  		array ("first_name", "last_name"),
				array ("company_id", "job_title"),
                array ("assigned_user_id", "lead_source_id"),  		
				array ("phone_work", "phone_home"),  			  
				array ("phone_mobile", "birthdate"),  			  
				array ("email1", "email2"),  			  
				array ("contact_type", ""),  			  
	  
		),
		array ("Address Info" =>
				array ("address1", "address2"),
				array ("city", "province"),
				array ("postal_code", "country"),  
		),
		array ("Other Info" =>
                array ("do_not_call", "email_opt_out"),
				array ("description", ""),  
		)
    );

