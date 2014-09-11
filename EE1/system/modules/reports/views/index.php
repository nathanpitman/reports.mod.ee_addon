<?php if (!empty($reports)) { ?>
<table cellspacing="0" cellpadding="0" border="0" class="tableBorder" style="width:100%;">
	<tr>
		<td class="tableHeading">Report Title</td>
		<td class="tableHeading">Description</td>
		<td class="tableHeading">Action</td>
	</tr>

	<?php
	$i = 0;
	foreach($reports AS $report) {
		if($i&1) {
			$class = "tableCellOne";
		} else {
			$class = "tableCellTwo";
		}
	?>
	<tr>
		<td style="white-space: nowrap;" class="<?=$class;?>"><span class="defaultBold"><?=$report['title'];?></span></td>
		<td style="width: 100%;" class="<?=$class;?>"><?=$report['description'];?></td>
		<td style="white-space: nowrap;" class="<?=$class;?>"><a href="?S=0&C=modules&M=reports&P=run&report_id=<?=$report['report_id'];?>">Export to CSV</a></td>
	</tr>
	<?php 
		$i++;
	}
	?>
	
</table>
<?php } else { ?>

<p>Sorry, there are currently no reports specified.</p>

<?php } ?>