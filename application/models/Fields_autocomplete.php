<?php
class Fields_autocomplete extends CI_Model {

	/**
	* param: $hint some characters provided by the user to search for companies that contain that $hint
	* param: (optional) $uacc_uid user unique id (36 long)
	* return: empty array $list IDs and Names if no companies matches the provided hint or list of IDs and Names
	 */
	public function getAccountsList($hint, $uacc_uid=''){
		//format datetime into "time ago"
		$list = array();

		if($uacc_uid != '')
			$query = $this->db->select("company_id,company_name")->like("company_name", $hint)->where(array("created_by"=>$uacc_uid,"deleted"=>0))->order_by("company_name", "asc")->get("sc_companies");
		else
			$query = $this->db->select("company_id,company_name")->like("company_name", $hint)->where("deleted",0)->order_by("company_name", "asc")->get("sc_companies");

	    if ($query->num_rows() > 0){
			foreach($query->result() as $row){

				$list[] = array("name"=>$row->company_name, "label"=>$row->company_name, "id"=>$row->company_id);
			}
		}
		return $list;
	}

	/**
	* param: $hint some characters provided by the user to search for people that contain that $hint
	* param: (optional) $uacc_uid user unique id (36 long)
	* return: empty array $list IDs and Names if no persons matches the provided hint or list of IDs and Names
	 */
	public function getPersonsList($hint, $uacc_uid=''){
		//format datetime into "time ago"
		$list = array();

		if($uacc_uid != '')
			$query = $this->db->select('people_id,first_name,last_name,company_id')->like("first_name", $hint)->or_like("last_name", $hint)->where(array("created_by"=>$uacc_uid,"deleted"=>0))->order_by("company_id", "desc")->get("sc_people");
		else
			$query = $this->db->select("people_id,first_name,last_name,company_id")->like("first_name", $hint)->or_like("last_name", $hint)->where("deleted",0)->order_by("company_id", "desc")->get("sc_people");

		$this->load->model("general");


	    if ($query->num_rows() > 0){
			foreach($query->result() as $row){
				$company_name = $this->general->getAccountName($row->company_id);
				if($company_name != "" && $company_name != " - ")
					$list[] = array("name"=>$row->first_name . " " . $row->last_name . " (" . $company_name . ")", "label"=>$row->first_name . " " . $row->last_name . " (" . $company_name . ")", "id"=>$row->people_id);
				else
					$list[] = array("name"=>$row->first_name . " " . $row->last_name, "label"=>$row->first_name . " " . $row->last_name, "id"=>$row->people_id);

			}
		}
		return $list;
	}
	/**
	* param: $hint some characters provided by the user to search for deals that contain that $hint
	* param: (optional) $uacc_uid user unique id (36 long)
	* return: empty array $list IDs and Names if no deals matches the provided hint or list of IDs and Names
	 */
	public function getDealsList($hint, $uacc_uid=''){
		//format datetime into "time ago"
		$list = array();

		if($uacc_uid != '')
			$query = $this->db->select("deal_id,name")->like("name", $hint)->where("created_by",$uacc_uid)->order_by("name", "asc")->get("sc_deals");
		else
			$query = $this->db->select("deal_id,name")->like("name", $hint)->order_by("name", "asc")->get("sc_deals");

	    if ($query->num_rows() > 0){
			foreach($query->result() as $row){

				$list[] = array("name"=>$row->name, "label"=>$row->name, "id"=>$row->deal_id);
			}
		}
		return $list;
	}

	public function getProjectsList($hint, $uacc_uid=''){
		//format datetime into "time ago"
		$list = array();

		if($uacc_uid != '')
			$query = $this->db->select("project_id,project_name")->like("project_name", $hint)->where(array("created_by"=>$uacc_uid,"deleted"=>0, "archived"=>0))->order_by("project_name", "asc")->get("sc_projects");
		else
			$query = $this->db->select("project_id,project_name")->like("project_name", $hint)->where(array("deleted"=>0, "archived"=>0))->order_by("project_name", "asc")->get("sc_projects");

	    if ($query->num_rows() > 0){
			foreach($query->result() as $row){

				$list[] = array("name"=>$row->project_name, "label"=>$row->project_name, "id"=>$row->project_id);
			}
		}
		return $list;
	}


	public function getListnew($hint, $uacc_uid=''){
		//format datetime into "time ago"
		$list = array();

		if($uacc_uid != '')
			$query = $this->db->select("deal_id,name")->like("name", $hint)->where(array("created_by"=>$uacc_uid,"deleted",0))->order_by("name", "asc")->get("sc_deals");
		else
			$query = $this->db->select("deal_id,name")->like("name", $hint)->where("deleted",0)->order_by("name", "asc")->get("sc_deals");

	    if ($query->num_rows() > 0){
			foreach($query->result() as $row){

				$list[] = array("name"=>$row->name, "label"=>$row->name, "id"=>$row->deal_id);
			}
		}
		return $list;
	}
	public function getTasksList($hint, $uacc_uid=''){
		//format datetime into "time ago"
		$list = array();

		if($uacc_uid != '')
			$query = $this->db->select("task_id,subject")->like("subject", $hint)->where(array("created_by"=>$uacc_uid,"deleted"=>0,"parent_id"=>0))->order_by("subject", "asc")->get("sc_tasks");
		else
			$query = $this->db->select("task_id,subject")->like("subject", $hint)->where(array("deleted"=>0,"parent_id"=>0))->order_by("subject", "asc")->get("sc_tasks");

	    $findprojectsquery = "SELECT task_id FROM sc_tasks WHERE task_id IN (SELECT * FROM (SELECT parent_id FROM sc_tasks GROUP BY parent_id HAVING COUNT(parent_id) > 0) AS a)";
		$findprojects = $this->db->query($findprojectsquery)->result();
		$num = 0;
		if ($query->num_rows() > 0){
		foreach($query->result() as $row){
		foreach($findprojects as $projects)
		{
			if($projects->task_id = $row->task_id);{
				$list[] = array("name"=>$row->subject, "label"=>$row->subject, "id"=>$row->task_id);

			break;
			}
			}
			}

		}
		return $list;
	}
	/**
	* param: $hint some characters provided by the user to search for products that contain that $hint
	* param: (optional) $uacc_uid user unique id (36 long)
	* return: empty array $list IDs and Names if no products matches the provided hint or list of IDs and Names
	 */
	public function getProductsList($hint, $uacc_uid=''){
		//format datetime into "time ago"
		$list = array();

		if($uacc_uid != '')
			$query = $this->db->select("product_id,product_name,cost,list_price,description")->like("product_name", $hint)->where(array("created_by"=>$uacc_uid,"deleted"=>0,"active"=>0))->order_by("product_name", "asc")->get("sc_products");
		else
			$query = $this->db->select("product_id,product_name,cost,list_price,description")->like("product_name", $hint)->where(array("deleted"=>0,"active"=>0))->order_by("product_name", "asc")->get("sc_products");

	    if ($query->num_rows() > 0){
			foreach($query->result() as $row){

				$list[] = array("name"=>$row->product_name, "label"=>$row->product_name . " (Cost: $" . $row->cost . "/Price: $" . $row->list_price . ")", "id"=>$row->product_id,"cost"=>$row->cost,"list"=>$row->list_price,'description'=>$row->description);
			}
		}
		return $list;
	}

	public function getTemplatesList($hint, $uacc_uid=''){
		//format datetime into "time ago"

		$list = array();

		if($uacc_uid != '')
			$query = $this->db->select("template_id,name,html_body")->like("name", $hint)->where(array("created_by"=>$uacc_uid,"deleted"=>0))->order_by("name", "asc")->get("sc_templates");
		else
			$query = $this->db->select("template_id,name,html_body")->like("name", $hint)->where(array("deleted"=>0))->order_by("name", "asc")->get("sc_templates");

	    if ($query->num_rows() > 0){
			foreach($query->result() as $row){

				$list[] = array("name"=>$row->name, "label"=>$row->name, "id"=>$row->template_id);
			}
		}
		return $list;
	}

}
