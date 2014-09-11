<?php

class Reports_CP {

    //module vars
	private $module_name = 'Reports';
	private $module_version = '1.1';
	private $backend_bool = 'y';

	var $base = "";
	var $export_type = 'csv';

	function Reports_CP( $switch = TRUE )
    {
        global $IN;
        
        if ($switch)
        {
            switch($IN->GBL('P'))
            {
                 case 'run'            :	$this->report_run();
                     break;	
                 default                :	$this->reports_home();
                     break;
            }
        }
    }

    function reports_module_install() {
        global $DB;

        $sql[] = "CREATE TABLE IF NOT EXISTS exp_reports (
				 report_id INT(10) unsigned NOT NULL auto_increment,
				 title VARCHAR(120),
				 description VARCHAR(255),
				 file_name VARCHAR(120),
				 member_id INT(10) UNSIGNED,
                 query TEXT,
                 post_processing TEXT,
				 datetime TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
				 PRIMARY KEY (report_id));";

        $sql[] = "INSERT INTO exp_modules (module_id, module_name, module_version, has_cp_backend)
				  VALUES ('', '".$this->module_name."', '".$this->module_version."', '".$this->backend_bool."')";

        foreach ($sql as $query) {
            $DB->query($query);
        }

        return true;
    }

    function reports_module_deinstall() {
        global $DB;

        $sql[] = "DELETE FROM exp_modules WHERE module_name = '".$this->module_name."'";
        $sql[] = "DROP TABLE IF EXISTS exp_reports";

        foreach ($sql as $query) {
            $DB->query($query);
        }

        return true;
    }
    
    function reports_home()
    {
        global $DB, $DSP, $LANG;
		
		$this->base = 'C=modules&M=Reports';
		
		$data = array();
		
		// Get reports list
		$query = $DB->query("SELECT * FROM exp_reports");
		
		if ($query->num_rows > 0) {
			$data['reports'] = $query->result;
		} else {
			$data['reports'] = NULL;
		}

		$DSP->title = $LANG->line('reports_module_name');
		$DSP->crumbline = true;
		$DSP->crumb = $DSP->anchor(BASE.AMP.$this->base, $LANG->line('reports_module_name'));
		$DSP->body = $DSP->view('index', $data, TRUE);
        
    }
    
    function report_run()
    {
    	global $DB, $IN;
    	    	
    	// Get the report data
		$report = $DB->query("SELECT * FROM exp_reports WHERE report_id=".$IN->GBL('report_id')." LIMIT 1");
		$report = $report->result[0];
		
		//$report['title'];
		//$report['description'];
		//$report['query'];
		//$report['post_processing'];
		//$report['file_name'];
		
		// Run the query
		$query = $DB->query($report['query']);
    	
    	//print_r($query);
		//exit;
    	
    	if ($query->num_rows > 0) {
	    	
	    	// do any post processing which is required
	    	
	    	if (!empty($report['post_processing'])) {
				
				$report['data'] = eval($report['post_processing']);
				
			} else {
			
				$report['data'] = $query->result;
			
			}
			
    		$this->export($report);
    	
    	}
    
    }
        
	function export($report)
	{
		global $DB;
		
		$tab  = ($this->export_type == 'csv') ? ',' : "\t"; 
		$cr	  = "\n"; 
		$data = '';

		//print_r($report);
		//exit;

		foreach($report['data'][0] as $key => $value)
		{				
			$data .= $key.$tab;
		}
			
		$data = trim($data).$cr; // Remove end tab and add carriage
			
		foreach($report['data'] as $row)
		{
			$datum = '';
			
			foreach($row as $key => $value)
			{
				if (strpos($value, ",") !== FALSE) {
					//$datum .= $value.$tab;
					$datum .= '"'.$value.'"'.$tab;
				} else {
					$datum .= '"'.$value.'"'.$tab;
				}
			}
				
			$data .= trim($datum).$cr;
		}
		
		if (strstr($_SERVER['HTTP_USER_AGENT'], "MSIE"))
   		{
        	header('Content-Type: application/octet-stream');
        	header('Content-Disposition: inline; filename="'.$report['file_name'].'.'.$this->export_type.'"');
        	header('Expires: 0');
        	header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
        	header('Pragma: public');
    	} 
    	else 
    	{
        	header('Content-Type: application/octet-stream');
        	header('Content-Disposition: attachment; filename="'.$report['file_name'].'.'.$this->export_type.'"');
        	header('Expires: 0');
        	header('Pragma: no-cache');
    	}
	
		echo $data;
		exit;
	}

}

?>