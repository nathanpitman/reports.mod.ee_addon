<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * One calorie SEO module, no sugar added!
 *
 * @package     Reports
 * @subpackage  ThirdParty
 * @category    Modules
 * @author      Nine Four
 * @link        http://ninefour.co.uk/labs
 */
class Reports_upd {

    var $version = '1.0.0';
    var $module_name = "Reports";

    /**
     * Installer for the Reports module
     */
    function install() {

        $data = array(
            'module_name'    => $this->module_name,
            'module_version' => $this->version,
            'has_cp_backend' => 'y',
            'has_publish_fields' => 'n'
        );

        ee()->db->insert('modules', $data);

        ee()->load->dbforge();

        $reports_module_fields = array(
            'report_id' => array(
                'type' => 'INT',
                'constraint' => '10',
                'unsigned' => TRUE,
                'auto_increment' => TRUE,),
            'site_id' => array(
                'type' => 'INT',
                'constraint' => '10',
                'null' => TRUE,),
            'title' => array(
                'type' => 'VARCHAR',
                'constraint' => '120',
                'null' => FALSE,),
            'description' => array(
                'type' => 'VARCHAR',
                'constraint' => '255',),
            'file_name' => array(
                'type' => 'VARCHAR',
                'constraint' => '120',),
            'member_id' => array(
                'type' => 'INT',
                'constraint' => '10',),
            'query' => array(
                'type' => 'TEXT',),
            'post_processing' => array(
                'type' => 'TEXT',),
            'datetime' => array(
                'type' => 'TIMESTAMP DEFAULT CURRENT_TIMESTAMP',),
        );

        ee()->dbforge->add_field($reports_module_fields);
        ee()->dbforge->add_key('report_id', TRUE);
        ee()->dbforge->create_table('reports');

        return TRUE;
    }

    /**
     * Uninstall the Reports module
     */
    function uninstall()
    {
        ee()->load->dbforge();

        ee()->db->select('module_id');
        $query = ee()->db->get_where('modules', array('module_name' => $this->module_name));

        ee()->db->where('module_id', $query->row('module_id'));
        ee()->db->delete('module_member_groups');

        ee()->db->where('module_name', $this->module_name);
        ee()->db->delete('modules');

        ee()->dbforge->drop_table('reports');

        return TRUE;
    }

    /**
     * Update the Reports module
     *
     * @param $current current version number
     * @return boolean indicating whether or not the module was updated
     */
    function update($current = '')
    {
        if ($current == $this->version)
        {
            return FALSE;
        }

        return TRUE;
    }

}

/* End of file upd.reports.php */
/* Location: ./system/expressionengine/third_party/reports/upd.reports.php */