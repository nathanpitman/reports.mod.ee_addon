<?php if (!empty($reports)) { ?>

	<?php

	ee()->table->set_heading(lang('table_heading_id'),lang('table_heading_title'), lang('table_heading_description'), lang('table_heading_action'));

	$i = 0;
	foreach($reports AS $report) {

		if($i&1) {
			$class = "tableCellOne";
		} else {
			$class = "tableCellTwo";
		}

		$cell['id'] = array('data' => $report['report_id'], 'style' => 'white-space: nowrap;');
        $cell['title'] = array('data' => $report['title'], 'style' => 'white-space: nowrap;');
		$cell['description'] = array('data' => $report['description'], 'style' => 'width: 100%;');
		$cell['action'] = array('data' => '<a href="'.BASE.AMP.'C=addons_modules'.AMP.'M=show_module_cp'.AMP.'module=reports&method=report_run&report_id='.$report['report_id'].'">Export to CSV</a>', 'style' => 'white-space: nowrap;');

        $this->table->add_row(
        	$cell['id'],
            $cell['title'],
        	$cell['description'],
        	$cell['action']
        	);
    }

	echo $this->table->generate();

	?>

<?php } else { ?>

<p>Sorry, there are currently no reports specified.</p>

<?php } ?>