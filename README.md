EE 2.x Reports Module
=====================

An ExpressionEngine 1.x and ExpressionEngine 2.x module which allows for the export of SQL query based reports through a friendly control panel interface. The module can also perform post processing on queries prior to export in CSV format.

Install
-------

1. Download the module
2. Move third\_party/reports to expressionengine/third\_party
3. Install the module from the ExpressionEngine control panel
5. Populate the 'exp_reports' table with a row for each SQL query based report you want to run

Adding Reports
--------------

At present you cannot add Reports via the control panel interface, this module simply provides clients with a tidy way to access your SQL query based reports.

For each report you want to provide access to you will need to add a row to the 'exp_reports' table with the following values:

* title - The title for your report (i.e. Member Data)
* description - A short description for the report (i.e. Exports all data recorded for website members)
* file_name - The filename you wish your CSV to have upon export (.csv is automatically appended for you)
* query - Where the magic happens, just add your SQL query here (i.e. SELECT * FROM exp_members)
* post_processing - If you're feeling clever you can add some post processing

Queries & Post Processing
-------------------------

You can use any valid SQL query in the query field so feel free to get creative with joins across tables to create the exact report your client needs from ExpressionEngine. A simple example:

```
SELECT m.member_id, DATE(FROM_UNIXTIME(m.join_date)) AS join_date, m.screen_name, DATE(FROM_UNIXTIME(m.last_visit)) AS last_visit
FROM exp_members AS m
JOIN exp_member_data d ON m.member_id=d.member_id
```

If you need to do more than just export existing data you can apply post processing by specifiying valid PHP in the post_processing field. A simple example:

```
$data = array();
$i = 0;
foreach($query->result_array() as $row) {
    $data[$i] = $row;
    $data[$i]['is_the_special'] = "No!";
        foreach($row as $key => $value) {
            if($key=="member_id") {
                if ($value==1) {
                    $data[$i]['is_the_special'] = "Yes!";
                }
            }
        }
    $i++;
}
return $data;
```
