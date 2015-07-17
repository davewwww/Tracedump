<?php

	/*
		http://www.developer.com/open/article.php/10930_631241_2/Linux-Console-Colors--Other-Tricks.htm
		https://wiki.archlinux.org/index.php/Color_Bash_Prompt

		TextFarbe
		\033[XXm

		BackgroundFarbe
		\033[XX;YYm

		0 = default colour
		1 = bold
		4 = underlined
		5 = flashing text
		7 = reverse field
		31 = red
		32 = green
		33 = orange
		34 = blue
		35 = purple
		36 = cyan
		37 = grey
		40 = black background
		41 = red background
		42 = green background
		43 = orange background
		44 = blue background
		45 = purple background
		46 = cyan background
		47 = grey background
		90 = dark grey
		91 = light red
		92 = light green
		93 = yellow
		94 = light blue
		95 = light purple
		96 = turquoise
		100 = dark grey background
		101 = light red background
		102 = light green background
		103 = yellow background
		104 = light blue background
		105 = light purple background
		106 = turquoise background

		die("\033[7m" . "Blabla". "\033[0m");
	*/



function td() {
    $end = array();
    $args = func_get_args();
    foreach($args as $a) {
        $end[]= diear($a,1);
    }
    $end = implode("\n\n". LCC(str_repeat("-",40),90) ."\n",$end) ."\n\n";

    header("HTTP/1.1 500");
    die($end);
}
function tde() {
    $end = array();
    $args = func_get_args();
    foreach($args as $a) {
        $end[]= diear($a,1);
    }
    $end = implode("\n\n". LCC(str_repeat("-",40),90) ."\n",$end) ."\n\n";

    echo $end;
}

function diear( &$ar , $return=0 ) {

	//vars
	$end = array();

    //[ check ]
    if( is_object($ar) ) {
        if( $return ) {
            return dieobj($ar, $return);
        } else {
            dieobj($ar, $return);
        }
    }

	elseif(is_array($ar)) {
		if($count = count($ar)) {
			foreach($ar as $name => $value) {
				$end[] = array(	"name"	=> "". LCC("\"". $name ."\"" , 31) . "",
								"value"	=> dievalue($value),
								"type"	=> gettype($value),
							);
			}
			$table = dietable($end);
		}
		else
			$table = " = array()";
	}
    elseif(is_resource($ar)) {
        $table = "Ressource: ". get_resource_type($ar) ;
    }
	else {
		$table = "\n".var_export($ar,1);
    }

	//Return
	if($return) return $table;

    header("HTTP/1.1 500");
	die( $table );
}


function dieobj($class, $return = 0) {

	//[ check ]
	if(!is_object($class))
		diear( $class );


	$refl = new \ReflectionClass( $class );



	//Class
	if($refl) {
		$end = dieobj_class( $refl , $class );
	}

	//Properties
	if($ar = $refl-> getProperties()) {
		$end = array_merge( $end , dieobj_properties( $ar , $class , $refl ) );
	}

	//Properties static
	if($ar = $refl-> getStaticProperties()) {
		$end = array_merge( $end , dieobj_properties( $ar , $class , $refl ) );
	}


	//Methods
	if($ar = $refl-> getMethods()) {
		$end = array_merge( $end , dieobj_methods( $ar , $class , $refl ) );
	}

    if($return) {
        return dietable($end);
    }

	//End
	die(dietable($end));
}



function dieobj_class( $data , $class=null , $refl=null ) {
	$end = array();


	$end[] = array(		"prop"	=> " ",
						"name"	=> LCC("class ". dievalue_class( $data, $class ) , "1;44") ,

					);

	return $end;

}




function dieobj_methods( $data , $class=null , $refl=null ) {
	$end = array();

	$className = $refl->getName();

	foreach($data as $k => $v) {

		//name
		$funcname = $v -> getName();

		//Method Parameter
		$ar_param = array();
		if($param = $v -> getParameters()) {
			foreach($param as $vv) {
				$ar_param[] = "$". $vv->getName()."";
			}
		}
		$param = $ar_param ? " ". implode(" , ",$ar_param) ." " : "";

		//extends
		$extendsClass = $v -> getDeclaringClass();
		if($className == $extendsClass -> getName()) $extendsClass = "";
		if($extendsClass) $extendsClass = dievalue_class( $extendsClass , $class);




		$name  = "function ";
		$name .= LCC( $funcname , 1);
		$name .= "(" . LCC( $param , 93) .")";

		$end[] = array(	"prop"		=> dieaccess( $v ),
						"name"		=> $name,
						"type"		=> "func",
						#"header"	=> "<b>Methods</b>" . ($extendsClass ? " extends ". $extendsClass : ""),
						"header"=> LCC( $extendsClass ? $extendsClass : "" , "0;104" ),
					);
	}

	return $end;
}






function dieobj_properties( $data  , $class=null , $refl=null ) {
	$end = array();

	$className = $refl->getName();

	foreach($data as $v) {

		if(!is_object($v)) {
			continue;
		}

		//Name
		$name = $v -> getName();

		//Value
		$value = null;
		if(method_exists($v,"setAccessible"))	$v -> setAccessible( true );
		if(method_exists($v,"getValue"))		$value = $v -> getValue($class);


		//extends
		$extendsClass = null;
		if(method_exists($v,"getDeclaringClass"))	$extendsClass = $v -> getDeclaringClass();
		if($extendsClass) if($className == $extendsClass -> getName()) $extendsClass = "";
		if($extendsClass) $extendsClass = dievalue_class( $extendsClass , $class );


		$end[] = array(	"prop"	=> dieaccess( $v ) ,
						"name"	=> LCC( "$".$name , 93 ),
						"value"	=> dievalue($value , $class ),
						"type"	=> gettype($value),
						"header"=> LCC( $extendsClass ? $extendsClass : "", "0;104" ),
					);
	}

	return $end;
}



function dietable($data) {

	$trs="";
	$ar_trs=array();

	foreach($data as $fe) {
		$type = isset($fe["type"]) ? $fe["type"] : null;
		$prop = isset($fe["prop"]) ? $fe["prop"] : null;
		$name = isset($fe["name"]) ? $fe["name"] : null;
		$value = isset($fe["value"]) ? $fe["value"] : null;
		$header = isset($fe["header"]) ? $fe["header"] : null;


		if($value) {
			if($type == "array") {
				$name = $name ."\t". trim($value);
			}
			else
				$name =
				$name = $name ."\t= ". trim($value);

		}

		$line = ($prop ? $prop ."\t" : "").
				 $name.
				"\n";

		if($header) {
			if(!isset($ar_trs[ $header ])) $ar_trs[ $header ] = "";

			$ar_trs[ $header ] .= $line;
		}
		else
			$trs .= $line;
	}

	if(count($ar_trs)) {
		foreach($ar_trs as $header => $fe_trs) {
			$trs .= $header ."\n". $fe_trs ."\n";
		}
	}


	return "\n".$trs."\n";
}


function dievalue($value , $class=null ) {

	$type = gettype($value);

	switch($type) {
		case "string":
			$l = strlen($value);
			$max = 100;
			if($l>$max)
				$value = substr($value,0,$max) ." ... ";
			$value = LCC( "\"".$value . "\"" , 31 );
		break;

		case "integer":
		case "double":
			$value = LCC( $value , 92 );
		break;

		case "boolean":
			$value = $value?"true":"false";
			$color = $value=="true" ? "1;42" : "1;41";
			$value = LCC($value , $color);
		break;

		case "array":
			$value = " = array (\n".diear($value , 1) .")";
		break;

		case "NULL":
			$value = LCC( "null" , 5);
		break;

		case "object":
			$refl = new \ReflectionClass( $value );
			#$value = $refl -> getName();
			$value = dievalue_class( $refl , $class )	;
		break;

		default:
			$value = LCC( "(". $type .")" , 5);
		break;
	}

	return $value;
}



function dievalue_class( $data , $class=null ) {

	$className = $data->getName();

	return $className;
}

function dieaccess($ref) {
	$ar = array("static","public","procted","protected","private","abstract","abstract" , );
	foreach($ar as $fe) {
		$method = "is". ucfirst($fe);
		if(method_exists($ref,$method)) {
			if($ref -> $method()) {
				#if($fe != "protected")
				#	$fe .= "\t";


				$fe = " ". $fe ." ";
				$leer = str_repeat(" " , 13 - strlen($fe));

				return $leer . LCC( $fe , 7);
			}
		}
	}
	return "unknown";
}


function LCC($text,$command="") {

	return "\033[". $command ."m". $text . "\033[0m";
}
