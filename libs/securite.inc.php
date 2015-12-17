<?php 
$INTERDIT=array('content-type','insert','delete','update','select');

//Contrôle headers
function verif_headers($text)
{
   return preg_match("/(%0A|%0D|\\n+|\\r+)(content-type:|to:|cc:|bcc:)/i", $text) == 0;
} 

function verif_email($email) {
	$atom   = '[-a-z0-9!#$%&\'*+\\/=?^_`{|}~]';   // caractères autorisés avant l'arobase
	$domain = '([a-z0-9]([-a-z0-9]*[a-z0-9]+)?)'; // caractères autorisés après l'arobase (nom de domaine)
	$regex = '/^' . $atom . '+' .'(\.' . $atom . '+)*' .'@' .'(' . $domain . '{1,63}\.)+' .$domain . '{2,63}$/i'; ///i indique que l'expression est insensible à la casse.

	if (preg_match($regex, $email)) 
	    return true;
	else 
	    return false;
}

function verif_champ($champ,$type) {
global $INTERDIT;
	if(empty($champ)) return false;
	switch($type)  {
		case 'email':if(!verif_email($champ) || !verif_headers($champ)) return false;break;
		case 'civilite':if($champ!='Mme' && $champ!='Mle' && $champ!='M.' && $champ!='M' && $champ!='Mlle' && $champ!='Mademoiselle' && $champ!='Madame' && $champ!='Monsieur') return false;break;
		case 'telephone':if(!ereg('^([0-9 \.()+]{10,20})$',$champ)) return false;break;
		case 'telephone_strict':if(!ereg('^(0[1-9]{1}[0-9]{8})$',$champ)) return false;break;
		case 'telephone_strict_without_mobile':if(!ereg('^(0[1234589]{1}[0-9]{8})$',$champ)) return false;break;
		case 'telephone_int_strict_without_mobile':if(!preg_match('/^(0|\+33|0033){1}((\s|\-|\.|\_)\(\s?0\s?\))?(\s|\-|\.|\_)?([1234589]){1}(\s|\-|\.|\_)?([0-9]){2}(\s|\-|\.|\_)?([0-9]){2}(\s|\-|\.|\_)?([0-9]){2}(\s|\-|\.|\_)?([0-9]){2}(\s|\-|\.|\_)?$/',$champ)) return false;break;
		case 'telephone_int_strict':if(!preg_match('/^(0|\+33|0033){1}((\s|\-|\.|\_)\(\s?0\s?\))?(\s|\-|\.|\_)?([123456789]){1}(\s|\-|\.|\_)?([0-9]){2}(\s|\-|\.|\_)?([0-9]){2}(\s|\-|\.|\_)?([0-9]){2}(\s|\-|\.|\_)?([0-9]){2}(\s|\-|\.|\_)?$/',$champ)) return false;break;
		case 'mobile_strict':if(!ereg('^(0[6-7]{1}[0-9]{8})$',$champ)) return false;break;
		case 'mobile_int_strict':if(!preg_match('/^(0|\+33|0033){1}((\s|\-|\.|\_)\(\s?0\s?\))?(\s|\-|\.|\_)?([67]){1}(\s|\-|\.|\_)?([0-9]){2}(\s|\-|\.|\_)?([0-9]){2}(\s|\-|\.|\_)?([0-9]){2}(\s|\-|\.|\_)?([0-9]){2}(\s|\-|\.|\_)?$/',$champ)) return false;break;
		case 'varname': if(!ereg('^[-_a-zA-Z0-9]{1,50}$',$champ)) return false;break;
		case 'word':if(!ereg('^[-_a-zA-Z0-9()\/\'éèàù^êûôîçäëïöü]{2,75}$',$champ)) return false;break;
		case 'words':if(!ereg('^[-_a-zA-Z0-9()\/\'"& éèàù^êûôîçäëïöü]{2,75}$',$champ)) return false;break;
		case 'name':if(!ereg('^[-a-zA-Z\' éèàùêûôîçäëïöü]{1,150}$',$champ)) return false;break;
		case 'cp':if(!ereg('^[0-9]{4,5}$',$champ)) return false;break;
		case 'cp_strict':if(!ereg('^[0-9]{5}$',$champ)) return false;break;
		case 'integer':if(!ereg('^([0-9]*)$',$champ)) return false;break;
		case 'float':if(!ereg('^([0-9]*)+'.'(\.([0-9])+)?$',$champ)) return false;break;
		case 'num_tridente':if(!ereg('^[0-9]{9}$',$champ)) return false;break;
		case 'date_ymd':$tab_date=explode('-',$champ);if(!checkdate((int)$tab_date[1],(int)$tab_date[2],(int)$tab_date[0])) return false;break;
		case 'date_dmy':$tab_date=explode('/',$champ);if(!checkdate((int)$tab_date[1],(int)$tab_date[0],(int)$tab_date[2])) return false;break;
		case 'time_his':$tab_time=explode(':',$champ);if(count($tab_time)!=3 || $tab_time[0]<0  || $tab_time[0]>23 || $tab_time[1]<0  || $tab_time[1]>59 || $tab_time[2]<0  || $tab_time[2]>59) return false;break;
		case 'key':if(!ereg('^[-_a-zA-Z0-9]{32}$',$champ)) return false;break;
		case 'url_var':if(!ereg('^[-_a-zA-Z0-9]{2,64}$',$champ)) return false;break;
		case 'password':if(!ereg('^[-_a-zA-Z0-9]{6,20}$',$champ)) return false;break;
		case 'telephone_be':break;
		case 'telephone_es':break;
		case 'telephone_fr':if(!preg_match('/^(0|\+33|0033){1}((\s|\-|\.|\_)\(\s?0\s?\))?(\s|\-|\.|\_)?([123456789]){1}(\s|\-|\.|\_)?([0-9]){2}(\s|\-|\.|\_)?([0-9]){2}(\s|\-|\.|\_)?([0-9]){2}(\s|\-|\.|\_)?([0-9]){2}(\s|\-|\.|\_)?$/',$champ)) return false;break;
		case 'telephone_lu':break;
		case 'telephone_mc':break;
		case 'telephone_pt':break;
		case 'cp_be':if(!ereg('^[0-9]{4}$',$champ)) return false;break;
		case 'cp_es':if(!ereg('^[0-9]{5}$',$champ)) return false;break;
		case 'cp_fr':if(!ereg('^[0-9]{5}$',$champ)) return false;break;
		case 'cp_lu':if(!ereg('^[0-9]{4}$',$champ)) return false;break;
		case 'cp_mu':if(!ereg('^[0-9]{5}$',$champ)) return false;break;
		case 'cp_pt':if(!ereg('^[0-9]{7}$',$champ)) return false;break;
		case 'none':break;
		default:return false;break;
   }
   /*for($i=0;$i<count($INTERDIT);$i++)
  	 if(strpos('|'.strtolower($champ),strtolower($INTERDIT[$i]))) return false;*/
   return true;
}

function verif_champ_iso($champ,$type) {
	return verif_champ(utf8_encode($champ),$type);
}
?>