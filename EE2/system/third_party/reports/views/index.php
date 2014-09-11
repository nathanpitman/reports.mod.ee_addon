<?php if (!empty($reports)) { ?>

	<?php

	ee()->table->set_heading('Title', 'Description', 'Action');

	$i = 0;
	foreach($reports AS $report) {

		if($i&1) {
			$class = "tableCellOne";
		} else {
			$class = "tableCellTwo";
		}

		$cell['title'] = array('data' => $report['title'], 'style' => 'white-space: nowrap;');
		$cell['description'] = array('data' => $report['description'], 'style' => 'width: 100%;');
		$cell['action'] = array('data' => '<a href="'.BASE.AMP.'C=addons_modules'.AMP.'M=show_module_cp'.AMP.'module=reports&method=report_run&report_id='.$report['report_id'].'">Export to CSV</a>', 'style' => 'white-space: nowrap;');

        $this->table->add_row(
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