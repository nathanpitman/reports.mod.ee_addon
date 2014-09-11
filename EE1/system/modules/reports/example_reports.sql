# Dump of table exp_reports
# ------------------------------------------------------------

LOCK TABLES `exp_reports` WRITE;
/*!40000 ALTER TABLE `exp_reports` DISABLE KEYS */;
INSERT INTO `exp_reports` (`report_id`,`title`,`description`,`file_name`,`member_id`,`query`,`post_processing`,`datetime`)
VALUES
	(1,'Property Statistics','Details view and shortlist counts for live properties.','property_stats',1,'SELECT exp_weblog_data.field_id_5 AS aspasia_id, exp_weblog_titles.title AS title, exp_weblog_titles.view_count_one AS view_count, exp_weblog_titles.favorites_count_public AS shortlist_count\nFROM exp_weblog_titles\nLEFT JOIN exp_weblog_data\nON exp_weblog_titles.entry_id=exp_weblog_data.entry_id\nWHERE exp_weblog_titles.weblog_id=4',NULL,'2011-04-27 23:47:53'),
	(2,'Property Search Log','Provides a log of search queries for manipulation in Excel.','search_patterns',1,'SELECT ip_address, search_date, search_terms\nFROM exp_search_log\nWHERE search_type = \"property search\"','$data = array();\n$i = 0;\n\nforeach($query->result as $row) {	\n	\n	foreach($row as $key => $value) {		\n	\n		if($key==\"search_terms\") {\n	\n			$search_terms = explode(\"/\",$value);\n	\n			if (count($search_terms)==7) {\n	\n				$data[$i][\'bedrooms\'] = $search_terms[0];\n				$data[$i][\'min_price\'] = $search_terms[1];\n				$data[$i][\'max_price\'] = $search_terms[2];\n				$data[$i][\'for_sale\'] = $search_terms[3];\n				$data[$i][\'to_let\'] = $search_terms[4];\n				$data[$i][\'new_homes_inc\'] = $search_terms[5];\n				$data[$i][\'region\'] = urldecode($search_terms[6]);\n	\n			}\n	\n		} else {\n	\n			$data[$i][$key] = $value;\n	\n		}\n	\n	}\n	$i++;\n}\n\nreturn $data;','2011-04-28 00:23:02');

/*!40000 ALTER TABLE `exp_reports` ENABLE KEYS */;
UNLOCK TABLES;