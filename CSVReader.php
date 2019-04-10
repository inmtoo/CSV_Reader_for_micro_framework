<?php
	class CSVReader {
		
		function csv2array($filename='', $file_size, $delimiter=';')
		{
			ini_set('auto_detect_line_endings', true);
			if(!file_exists($filename) || !is_readable($filename)) {
				return false;
			}
			$header = null;
			$data = array();
			if(($handle = fopen($filename, 'r')) !== false) {
				while(($row = fgetcsv($handle, $file_size, $delimiter)) !== false) {
					if(!$header) {
						if($row[0] != 'sep=') {
							$header = $row;
						}
					} else {
						if (count($header) > count($row)) {
							$difference = count($header) - count($row);
							for ($i = 1; $i <= $difference; $i++) {
								$row[count($row) + 1] = '';
							}
						}
					}
					if($row[0] != 'sep=') {
						$data[] = $row;
					}
				}
				fclose($handle);
			}
			return $data;
		}
		
		function csvgetdata($csvfile)
		{
			ini_set('auto_detect_line_endings', true);
			if (file_exists($csvfile) && is_readable($csvfile)) {
				if(($handle = fopen($csvfile, 'r')) !== false) {
					
					$limiter = fgets($handle, filesize($csvfile));
					rewind($handle);
					if (stristr($limiter, ',') !== false) {
						$delimiter = ',';
					} elseif (stristr($limiter, ';') !== false) {
						$delimiter = ';';
					}
					$columnnames = fgetcsv($handle, filesize($csvfile), $delimiter);
					while (($data = fgetcsv($handle, filesize($csvfile), $delimiter)) !== FALSE) {
						$row = array_combine($columnnames, $data);
						$csv[] = $row;
					}
					fclose($handle);
				}
			}
			return $csv;
			//return $limiter;
		}
		
		function csvsortcol($csvfile)
		{
			$csvdata = CSVReader::csvgetdata($csvfile);
			$csvfval = $csvdata[array_key_first($csvdata)];
			$csvcolumns = array_keys($csvfval);
			return $csvcolumns;
		}
		
		function csvgetbycol($csvfile, $csvcolumns)
		{
			$csvdata = CSVReader::csvgetdata($csvfile);
			foreach ($csvdata as $csvstring) {
				foreach ($csvcolumns as $column) {
					$csvbycolumns[$column][] = $csvstring[$column];
				}
				
			}
			return $csvbycolumns;
		}
		
		function csvread($csvfile)
		{
			$csvdata = CSVReader::csvgetdata($csvfile);
			return $csvdata;
		}
		
	}
?>