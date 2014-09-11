<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Reports_mcp {

    //module vars
    private $module_name = 'Reports';
    private $module_version = '1.0';
    private $backend_bool = 'y';

    var $base = "";
    var $export_type = 'csv';

    function Reports_mcp( $switch = TRUE )
    {

        $this->base = BASE.AMP.'C=addons_modules'.AMP.'M=show_module_cp'.AMP.'module='.$this->module_name;

        //  Onward!
        ee()->load->library('table');
        ee()->lang->loadfile('reports');
    }

    function index()
    {

        ee()->cp->set_breadcrumb(BASE.AMP.'C=addons_modules'.AMP.'M=show_module_cp'.AMP.'module=reports', lang('reports_module_name'));
        ee()->view->cp_page_title = "All Reports";

        ee()->load->library('table');

        $this->base = 'C=modules&M=Reports';

        $vars = array();

        // Get reports list
        ee()->db->select('report_id, title, description');

        $query = ee()->db->get('reports');

        if ($query->num_rows > 0) {
            $vars['reports'] = $query->result_array();
        } else {
            $vars['reports'] = NULL;
        }

        return ee()->load->view('index', $vars, TRUE);

    }

    function report_run()
    {

        // Get the report data
        $report = ee()->db->query("SELECT * FROM exp_reports WHERE report_id=".ee()->input->get('report_id')." LIMIT 1");
        $report = $report->row_array();

        //$report['title'];
        //$report['description'];
        //$report['query'];
        //$report['post_processing'];
        //$report['file_name'];

        // Run the query
        $query = ee()->db->query($report['query']);

        if ($query->num_rows() > 0) {

            // do any post processing which is required
            if (!empty($report['post_processing'])) {
                $report['data'] = eval($report['post_processing']);
                if (!$report['data']) {
                    show_error('There was an error with your post processing code.');
                    exit;
                }
            } else {
                $report['data'] = $query->result_array();
            }

            $this->export($report);

        } else {

            show_error('Sorry - this report query does not return any results.');

        }

    }

    function export($report)
    {

        $tab  = ($this->export_type == 'csv') ? ',' : "\t";
        $cr   = "\n";
        $data = '';

        echo('<pre>');
        print_r($report);
        exit;

        foreach($report['data'] as $key => $value)
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
        } else {
            header('Content-Type: application/octet-stream');
            header('Content-Disposition: attachment; filename="'.$report['file_name'].'.'.$this->export_type.'"');
            header('Expires: 0');
            header('Pragma: no-cache');
        }

        echo $data;
        exit;
    }

}

/* End of file mcp.reports.php */
/* Location: ./system/expressionengine/third_party/reports/mcp.reports.php */