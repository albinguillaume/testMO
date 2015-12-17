
/* affiche dans la console le message str */
var netdebug = function( str) {
	//if( CONFIG == 'TEST' ) {
		if(window.console) {
			if(window.console.log) {
				console.log( str);
			}
		}
	//}
}

function trim (str, charlist) {
    // Strips whitespace from the beginning and end of a string
    //
    // version: 1103.1210
    // discuss at: http://phpjs.org/functions/trim
    // +   original by: Kevin van Zonneveld (http://kevin.vanzonneveld.net)
    // +   improved by: mdsjack (http://www.mdsjack.bo.it)
    // +   improved by: Alexander Ermolaev (http://snippets.dzone.com/user/AlexanderErmolaev)
    // +      input by: Erkekjetter
    // +   improved by: Kevin van Zonneveld (http://kevin.vanzonneveld.net)
    // +      input by: DxGx
    // +   improved by: Steven Levithan (http://blog.stevenlevithan.com)
    // +    tweaked by: Jack
    // +   bugfixed by: Onno Marsman
    // *     example 1: trim('    Kevin van Zonneveld    ');
    // *     returns 1: 'Kevin van Zonneveld'
    // *     example 2: trim('Hello World', 'Hdle');
    // *     returns 2: 'o Wor'
    // *     example 3: trim(16, 1);
    // *     returns 3: 6
    var whitespace, l = 0,
        i = 0;
    str += '';

    if (!charlist) {
        // default list
        whitespace = " \n\r\t\f\x0b\xa0\u2000\u2001\u2002\u2003\u2004\u2005\u2006\u2007\u2008\u2009\u200a\u200b\u2028\u2029\u3000";
    } else {
        // preg_quote custom list
        charlist += '';
        whitespace = charlist.replace(/([\[\]\(\)\.\?\/\*\{\}\+\$\^\:])/g, '$1');
    }

    l = str.length;
    for (i = 0; i < l; i++) {
        if (whitespace.indexOf(str.charAt(i)) === -1) {
            str = str.substring(i);
            break;
        }
    }

    l = str.length;
    for (i = l - 1; i >= 0; i--) {
        if (whitespace.indexOf(str.charAt(i)) === -1) {
            str = str.substring(0, i + 1);
            break;
        }
    }

    return whitespace.indexOf(str.charAt(0)) === -1 ? str : '';
}

/* return true si la chaine est vide ou indefini */
var empty = function( mixed_var) {
	// !No description available for empty. @php.js developers: Please update the function summary text file.
	// 
	// version: 911.1619
	// discuss at: http://phpjs.org/functions/empty    // +   original by: Philippe Baumann
	// +      input by: Onno Marsman
	// +   bugfixed by: Kevin van Zonneveld (http://kevin.vanzonneveld.net)
	// +      input by: LH
	// +   improved by: Onno Marsman    // +   improved by: Francesco
	// +   improved by: Marc Jansen
	// +   input by: Stoyan Kyosev (http://www.svest.org/)
	// *     example 1: empty(null);
	// *     returns 1: true    // *     example 2: empty(undefined);
	// *     returns 2: true
	// *     example 3: empty([]);
	// *     returns 3: true
	// *     example 4: empty({});    // *     returns 4: true
	// *     example 5: empty({'aFunc' : function () { alert('humpty'); } });
	// *     returns 5: false
	
	var key;
	if (mixed_var === "" ||
		mixed_var === 0 ||
		mixed_var === "0" ||
		mixed_var === null ||
		mixed_var === false ||
		typeof mixed_var === 'undefined'
	){
		return true;
	}
	if (typeof mixed_var == 'object') {
		for (key in mixed_var) {
			return false;
		}
		return true;
	}
	
	return false;
}

function isset () {
	// !No description available for isset. @php.js developers: Please update the function summary text file.
	// 
	// version: 909.322
	// discuss at: http://phpjs.org/functions/isset    // +   original by: Kevin van Zonneveld (http://kevin.vanzonneveld.net)
	// +   improved by: FremyCompany
	// +   improved by: Onno Marsman
	// *     example 1: isset( undefined, true);
	// *     returns 1: false    // *     example 2: isset( 'Kevin van Zonneveld' );
	// *     returns 2: true
	
	var a=arguments, l=a.length, i=0;
	if (l===0) {
		throw new Error('Empty isset'); 
	}
	
	while (i!==l) {
		if (typeof(a[i])=='undefined' || a[i]===null) { 
			return false; 
		} else { 
			i++; 
		}
	}
	return true;
}

function number_format (number, decimals, dec_point, thousands_sep) {
    // http://kevin.vanzonneveld.net
    // +   original by: Jonas Raoni Soares Silva (http://www.jsfromhell.com)
    // +   improved by: Kevin van Zonneveld (http://kevin.vanzonneveld.net)
    // +     bugfix by: Michael White (http://getsprink.com)
    // +     bugfix by: Benjamin Lupton
    // +     bugfix by: Allan Jensen (http://www.winternet.no)
    // +    revised by: Jonas Raoni Soares Silva (http://www.jsfromhell.com)
    // +     bugfix by: Howard Yeend
    // +    revised by: Luke Smith (http://lucassmith.name)
    // +     bugfix by: Diogo Resende
    // +     bugfix by: Rival
    // +      input by: Kheang Hok Chin (http://www.distantia.ca/)
    // +   improved by: davook
    // +   improved by: Brett Zamir (http://brett-zamir.me)
    // +      input by: Jay Klehr
    // +   improved by: Brett Zamir (http://brett-zamir.me)
    // +      input by: Amir Habibi (http://www.residence-mixte.com/)
    // +     bugfix by: Brett Zamir (http://brett-zamir.me)
    // +   improved by: Theriault
    // +      input by: Amirouche
    // +   improved by: Kevin van Zonneveld (http://kevin.vanzonneveld.net)
    // *     example 1: number_format(1234.56);
    // *     returns 1: '1,235'
    // *     example 2: number_format(1234.56, 2, ',', ' ');
    // *     returns 2: '1 234,56'
    // *     example 3: number_format(1234.5678, 2, '.', '');
    // *     returns 3: '1234.57'
    // *     example 4: number_format(67, 2, ',', '.');
    // *     returns 4: '67,00'
    // *     example 5: number_format(1000);
    // *     returns 5: '1,000'
    // *     example 6: number_format(67.311, 2);
    // *     returns 6: '67.31'
    // *     example 7: number_format(1000.55, 1);
    // *     returns 7: '1,000.6'
    // *     example 8: number_format(67000, 5, ',', '.');
    // *     returns 8: '67.000,00000'
    // *     example 9: number_format(0.9, 0);
    // *     returns 9: '1'
    // *    example 10: number_format('1.20', 2);
    // *    returns 10: '1.20'
    // *    example 11: number_format('1.20', 4);
    // *    returns 11: '1.2000'
    // *    example 12: number_format('1.2000', 3);
    // *    returns 12: '1.200'
    // *    example 13: number_format('1 000,50', 2, '.', ' ');
    // *    returns 13: '100 050.00'
    // Strip all characters but numerical ones.
    number = (number + '').replace(/[^0-9+\-Ee.]/g, '');
    var n = !isFinite(+number) ? 0 : +number,
        prec = !isFinite(+decimals) ? 0 : Math.abs(decimals),
        sep = (typeof thousands_sep === 'undefined') ? ',' : thousands_sep,
        dec = (typeof dec_point === 'undefined') ? '.' : dec_point,
        s = '',
        toFixedFix = function (n, prec) {
            var k = Math.pow(10, prec);
            return '' + Math.round(n * k) / k;
        };
    // Fix for IE parseFloat(0.55).toFixed(0) = 0;
    s = (prec ? toFixedFix(n, prec) : '' + Math.round(n)).split('.');
    if (s[0].length > 3) {
        s[0] = s[0].replace(/\B(?=(?:\d{3})+(?!\d))/g, sep);
    }
    if ((s[1] || '').length < prec) {
        s[1] = s[1] || '';
        s[1] += new Array(prec - s[1].length + 1).join('0');
    }
    return s.join(dec);
}

//Fonction permettant de récupérer un nombre à la fin d'une chaîne
function getEndNumber(str) {
	return str.match(/\d+$/)[0];
}

/*********** GOOGLE ANALYTICS ***********/

function ga_track_event() {
	tab = ['_trackEvent'];
	// parcours les paramètres
	for (var i = 0; i < arguments.length; i++) {
		tab.push(arguments[i]);
	}
	if(CONFIG=='PROD') {
		_gaq.push(tab);
		if( GA_TAB_ACCOUNT_LETTERS.length > 0 ) {
			for( var i = 0 ; i < GA_TAB_ACCOUNT_LETTERS.length ; i++ ) {
				tab = [GA_TAB_ACCOUNT_LETTERS[i]+'._trackEvent'];
				// parcours les paramètres
				for (var i = 0; i < arguments.length; i++) {
					tab.push(arguments[i]);
				}
			}
		}
	}
}

function ga_track_page( pagename) {
	if( CONFIG == 'PROD') {
		if( !empty( SOPHUS) && SOPHUS == true && typeof s3_log == 'function' ) {
			s3_logging_active = true;
			s3_log( pagename);
		}
		if( !empty( pagename) ) _gaq.push(['_trackPageview', pagename]);
		else _gaq.push(['_trackPageview']);
		
		if( GA_TAB_ACCOUNT_LETTERS.length > 0 ) {
			for( var i = 0 ; i < GA_TAB_ACCOUNT_LETTERS.length ; i++ ) {
				if( !empty( pagename) ) _gaq.push([GA_TAB_ACCOUNT_LETTERS[i]+'._trackPageview', pagename]);
				else _gaq.push([GA_TAB_ACCOUNT_LETTERS[i]+'._trackPageview']);
			}
		}
	}
}


// function pour avoir un texte par défault dans un input
function init_focus_blur(jq_elt,text) {
	if(jq_elt.val()=='') {
		jq_elt.css('font-style','italic');
		jq_elt.val(text);
	}
	jq_elt.focus(function() {
		if( jq_elt.val() == text ) {
			jq_elt.val( '');
			jq_elt.css('font-style','normal');
		}
	});
	jq_elt.blur(function() {
		if( jq_elt.val() == "" ) {
			jq_elt.val( text);
			jq_elt.css('font-style','italic');
		}
	});
}

function verif_email( email) {
	//var atom   = '[-a-z0-9!#$%&\'*+\\/=?^_`{|}~]';   // caractères autorisés avant l'arobase
	//var domain = '([a-z0-9]([-a-z0-9]*[a-z0-9]+)?)'; // caractères autorisés après l'arobase (nom de domaine)
	//var reg = new RegExp("^" + atom + "+" + "(\." + atom + "+)*" +"@" +"(" + domain + "{1,63}\.)+" + domain + "{2,63}$", "i");
	
	//if( reg.test( email) ) {
	if( /^((([a-z]|\d|[!#\$%&'\*\+\-\/=\?\^_`{\|}~]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])+(\.([a-z]|\d|[!#\$%&'\*\+\-\/=\?\^_`{\|}~]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])+)*)|((\x22)((((\x20|\x09)*(\x0d\x0a))?(\x20|\x09)+)?(([\x01-\x08\x0b\x0c\x0e-\x1f\x7f]|\x21|[\x23-\x5b]|[\x5d-\x7e]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(\\([\x01-\x09\x0b\x0c\x0d-\x7f]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]))))*(((\x20|\x09)*(\x0d\x0a))?(\x20|\x09)+)?(\x22)))@((([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])*([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])))\.)+(([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])*([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])))\.?$/i.test( email) ) {
		return true;
	} else {
		return false;
	}
}


function setErreur( id) {
	if( $('#'+id).hasClass( 'error') == false ) $('#'+id).addClass( 'error');
	if( $('#contener_'+id).hasClass( 'error') == false ) $('#contener_'+id).addClass( 'error');
	if( $('#'+'label_'+id).hasClass( 'error') == false ) $('#'+'label_'+id).addClass( 'error');
};

function unsetErreur( id) {
	if( $('#'+id).hasClass( 'error') == true ) $('#'+id).removeClass( 'error');
	if( $('#contener_'+id).hasClass( 'error') == true ) $('#contener_'+id).removeClass( 'error');
	if( $('#'+'label_'+id).hasClass( 'error') == true ) $('#'+'label_'+id).removeClass( 'error');
};

function validateCP( str) {
	if( str.length != 5 ) return false;
	if( str < 1000 ) return false;
	var reg = new RegExp("^[0-9]{5}$", "");
	if(reg.test(str) == false) {
		return false;
	} else {
		return true;
	}
}

function validateTel( str) {
	if( str.length != 10 ) return false;
	var reg = new RegExp("^(0[1-9]{1}[0-9]{8})$", "");
	if(reg.test(str) == false) {
		return false;
	} else {
		return true;
	}
}

//Fonction permettant de récupérer un nombre à la fin d'une chaîne
function getEndNumber(str) {
	return str.match(/\d+$/)[0];
}

function verif_champ( champ, type) {
	if( empty( champ) ) return false;
	switch( type) {
		case 'email': if( !verif_email( champ) ) return false;break;
		case 'civilite':if( champ != 'Mme' && champ != 'Mle' && champ != 'M.' && champ != 'M' && champ != 'Mlle' && champ != 'Mademoiselle' && champ != 'Madame' && champ != 'Monsieur' ) return false;break;
		case 'telephone':if( /^([0-9 \.()+]{10,20})$/.test( champ) == false ) return false;break;
		case 'telephone_strict':if( /^(0[1-9]{1}[0-9]{8})$/.test( champ) == false ) return false;break;
		case 'telephone_strict_without_mobile':if( /^(0[1234589]{1}[0-9]{8})$/.test( champ) == false ) return false;break;
		case 'telephone_int_strict_without_mobile':if( /^(0|\+33|0033){1}((\s|\-|\.|\_)\(\s?0\s?\))?(\s|\-|\.|\_)?([1234589]){1}(\s|\-|\.|\_)?([0-9]){2}(\s|\-|\.|\_)?([0-9]){2}(\s|\-|\.|\_)?([0-9]){2}(\s|\-|\.|\_)?([0-9]){2}(\s|\-|\.|\_)?$/.test( champ) == false ) return false;break;
		case 'telephone_int_strict':if( /^(0|\+33|0033){1}((\s|\-|\.|\_)\(\s?0\s?\))?(\s|\-|\.|\_)?([123456789]){1}(\s|\-|\.|\_)?([0-9]){2}(\s|\-|\.|\_)?([0-9]){2}(\s|\-|\.|\_)?([0-9]){2}(\s|\-|\.|\_)?([0-9]){2}(\s|\-|\.|\_)?$/.test( champ) == false ) return false;break;
		case 'mobile_strict':if( /^(0[6-7]{1}[0-9]{8})$/.test( champ) == false ) return false;break;
		case 'mobile_int_strict':if( /^(0|\+33|0033){1}((\s|\-|\.|\_)\(\s?0\s?\))?(\s|\-|\.|\_)?([67]){1}(\s|\-|\.|\_)?([0-9]){2}(\s|\-|\.|\_)?([0-9]){2}(\s|\-|\.|\_)?([0-9]){2}(\s|\-|\.|\_)?([0-9]){2}(\s|\-|\.|\_)?$/.test( champ) == false ) return false;break;
		case 'varname': if( /^[-_a-zA-Z0-9]{1,50}$/.test( champ) == false ) return false;break;
		case 'word':if( /^[-_a-zA-Z0-9()\/\'éèàù^êûôîçäëïöü]{2,75}$/.test( champ) == false ) return false;break;
		case 'words':if( /^[-_a-zA-Z0-9()\/\'"& éèàù^êûôîçäëïöü]{2,75}$/.test( champ) == false ) return false;break;
		case 'name':if( /^[-a-zA-Z\' éèàùêûôîçäëïöü]{1,150}$/.test( champ) == false ) return false;break;
		case 'cp':if( /^[0-9]{4,5}$/.test( champ) == false ) return false;break;
		case 'cp_strict':if( /^[0-9]{5}$/.test( champ) == false ) return false;break;
		case 'cp_be':if( /^[0-9]{4}$/.test( champ) == false ) return false;break;
		case 'cp_es':if( /^[0-9]{5}$/.test( champ) == false ) return false;break;
		case 'cp_fr':if( /^[0-9]{5}$/.test( champ) == false ) return false;break;
		case 'cp_lu':if( /^[0-9]{4}$/.test( champ) == false ) return false;break;
		case 'cp_mc':if( /^[0-9]{5}$/.test( champ) == false ) return false;break;
		case 'cp_pt':if( /^[0-9]{7}$/.test( champ) == false ) return false;break;
		case 'integer':if( /^([0-9]*)$/.test( champ) == false ) return false;break;
		case 'float':if( /^([0-9]*)+(\.([0-9])+)?$/.test( champ) == false ) return false;break;
		case 'num_tridente':if( /^[0-9]{9}$/.test( champ) == false ) return false;break;
		//case 'date_ymd':var tab_date=champ.split('-');if(!checkdate((int)$tab_date[1],(int)$tab_date[2],(int)$tab_date[0])) return false;break;
		//case 'date_dmy':var tab_date=champ.split('/');if(!checkdate((int)$tab_date[1],(int)$tab_date[0],(int)$tab_date[2])) return false;break;
		//case 'time_his':var tab_time=champ.split(':');if(count($tab_time)!=3 || $tab_time[0]<0  || $tab_time[0]>23 || $tab_time[1]<0  || $tab_time[1]>59 || $tab_time[2]<0  || $tab_time[2]>59) return false;break;
		case 'key':if( /^[-_a-zA-Z0-9]{32}$/.test( champ) == false ) return false;break;
		case 'password':if( /^[-_a-zA-Z0-9]{6,20}$/.test( champ) == false ) return false;break;
		case 'telephone_be':break;
		case 'telephone_es':break;
		case 'telephone_fr':if( /^(0|\+33|0033){1}((\s|\-|\.|\_)\(\s?0\s?\))?(\s|\-|\.|\_)?([123456789]){1}(\s|\-|\.|\_)?([0-9]){2}(\s|\-|\.|\_)?([0-9]){2}(\s|\-|\.|\_)?([0-9]){2}(\s|\-|\.|\_)?([0-9]){2}(\s|\-|\.|\_)?$/.test( champ) == false ) return false;break;
		case 'telephone_lu':break;
		case 'telephone_mc':break;
		case 'telephone_pt':break;
		case 'none':break;
		default:return false;break;
   }
   return true;
}

function getValFromCheckboxName( name) {
	var data = new Array();
	$('input[name="'+name+'"]:checked').each( function() {
		data[data.length] = $(this).val();
	});
	return data;
}

function unselCheckboxName( name) {
	$('input[name="'+name+'"]:checked').each( function() {
		$(this).attr( 'checked', '');
	});
}

function getValFromRadioName( name) {
	var data = new Array();
	$('input[name="'+name+'"]:checked').each( function() {
		data[data.length] = $(this).val();
	});
	if( data.length > 0 ) return data[0];
	return '';
}

function preloader( images) {
	imageObjArray = new Array();
	for( var i = 0 ; i <= images.length ; i++ ) {
		imageObjArray[i] = new Image();
		imageObjArray[i].src = images[i];
	}
}

$.expr[':'].textEquals = function (a, i, m) { return $(a).text().match("^" + m[3] + "$"); };
