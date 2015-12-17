<?php
	include 'chemin.inc.php';
	//include LIBS.'xls/PHPExcel.php';
error_reporting(E_ALL);
		ini_set( 'display_errors', true);
		ini_set( 'memory_limit', '999M');
die();

$table = 'lead_igd2015q4';
$format = 'html';
$fn = ''.$table.'_'.date( 'Y-m-d_H-i-s');
$req = 'SELECT * FROM `'.$table.'` WHERE 1';
$res = mysql_query( $req);

$entetes = array();
$datas = array();
$i = 0;
if( mysql_num_rows( $res) > 0 ) {
	while( $row = mysql_fetch_assoc( $res) ) {
		if( $i == 0 ) {
			/*$t = array_keys( $row);
			if( !empty( $t) ) {
				for( $e = 0 ; $e < count( $t) ; $e++ ) {
					$entetes[$t[$e]] = $t[$e];
				}
			}
			*/
			$entetes = array_keys( $row);
		}
		$datas[] = array_values( $row);
		
		$i++;
	}

if( $format == 'html' ) {
	header("Content-Type:   application/vnd.ms-excel; charset=utf-8");
	header("Content-type:   application/x-msexcel; charset=utf-8");
	header("Content-Disposition: attachment; filename=".$fn.".xsl"); 
	header("Expires: 0");
	header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
	header("Cache-Control: private",false);
	echo '<table border="1">';
	echo '<tr>';
	for( $i = 0 ; $i < count( $entetes) ; $i++ ) {
		echo '<td>'.$entetes[$i].'</td>';
	}
	echo '</tr>';
	for( $i = 0 ; $i < count( $datas) ; $i++ ) {
		echo '<tr>';
		for( $j = 0 ; $j < count( $datas[$i]) ; $j++ ) {
			echo '<td>'.$datas[$i][$j].'</td>';
		}
		echo '</tr>';
	}
	echo '</table>';
}
	
	
}
?>