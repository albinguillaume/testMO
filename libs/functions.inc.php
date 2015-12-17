<?php
	function convertFileSize($size=0){
		$units = array( 'B', 'KB', 'MB', 'GB', 'TB', 'PB', 'EB', 'ZB', 'YB');
		$power = $size > 0 ? floor(log($size, 1024)) : 0;
		return number_format($size / pow(1024, $power), 2, '.', ',') . ' ' . $units[$power];
	}
	
	function getFileListWidthData($exp_reg, $dir="."){
		$files = array();
		$handle = opendir($dir);
		while($file = readdir($handle)) {
			if(preg_match ($exp_reg,$file)) {
				$files[] = array(
					'name'=> $file, 
					'date'=>date("d/m/Y H:i:s",filemtime($dir.$file)), 
					'size'=>convertFileSize(filesize($dir.$file))
				);
			}
		}
		closedir($handle);
		sort($files);
		return $files;
	}
	
	function getTabData( $entetes, $data, $param=array() ) {
		$default_option = array( 'size'=>array(), 'align'=>array());
		$option = array_merge( $default_option, $param);
		
		$out = '<table class="listing" cellspacing="0" cellpadding="0">';
		if( !empty( $entetes) ) {
			$out .= '<thead><tr>';
			for( $i = 0 ; $i < count( $entetes) ; $i++ ) {
				$style = '';
				if( !empty( $option['size'][$i]) && intval( $option['size'][$i]) > 0 ) $style .= 'width: '.intval( $option['size'][$i]).'px;';
				if( !empty( $option['align'][$i]) && ( $option['align'][$i] == 'left' || $option['align'][$i] == 'center' || $option['align'][$i] == 'right' ) ) $style = 'text-align: '.$option['align'][$i].';';
				$style = ' style="'.$style.'"';
				$out .= '<th'.$style.'>'.$entetes[$i].'</th>';
			}
			$out .= '</tr></thead>';
		}
		if( !empty( $data) ) {
			$out .= '<tbody>';
			for( $i = 0 ; $i < count( $data) ; $i++ ) {
				$out .= '<tr>';
				for( $j = 0 ; $j < count( $data[$i]) ; $j++ ) {
					$style = '';
					if( !empty( $option['align'][$j]) && ( $option['align'][$j] == 'left' || $option['align'][$j] == 'center' || $option['align'][$j] == 'right' ) ) $style = ' style="text-align: '.$option['align'][$j].';"';
					$out .= '<td'.$style.'>'.$data[$i][$j].'</td>';
				}
				$out .= '</tr>';
			}
			$out .= '</tbody>';
		}
		$out .= '</table>';
		return $out;
	}
?>