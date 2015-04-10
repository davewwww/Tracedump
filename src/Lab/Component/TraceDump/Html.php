<?php

namespace Lab\Component\TraceDump;

/**
 * @author David Wolter <david@dampfer.net>
 */
class Html implements TraceDumperInterface
{
    /**
     * {@inheritdoc}
     */
    public function tracedump()
    {
        $end = array();
        $args = func_get_args();
        foreach ($args as $a) {
            $end[] = $this->diear($a, 1);
        }

        return implode("<hr>", $end);
    }

    function diear($ar)
    {

        //vars
        $end = array();

        //[ check ]
        if (is_object($ar)) {
            return $this->dieobj($ar);

        } elseif (is_array($ar)) {
            if ($count = count($ar)) {
                foreach ($ar as $name => $value) {
                    $end[] = array("name"  => "[<span style='color:red'>\"".$name."\"</span>]",
                                   "value" => $this->dievalue($value),
                                   "type"  => gettype($value),);
                }
                $table = $this->dietable($end);
            } else {
                $table = "&nbsp;= <b>array()</b>";
            }
        } elseif (is_resource($ar)) {
            $table = "Ressource: ".get_resource_type($ar);
        } else {
            $table = "<pre>".var_export($ar, 1)."</pre>";
        }

        return $table;
    }

    function dieobj($class)
    {

        //[ check ]
        if (!is_object($class)) {
            $this->diear($class);
        }

        $refl = new \ReflectionClass($class);

        $end = $this->dieobj_class($refl, $class);

        //Properties
        if ($ar = $refl->getProperties()) {
            $end = array_merge($end, $this->dieobj_properties($ar, $class, $refl));
        }

        //Properties static
        if ($ar = $refl->getStaticProperties()) {
            $end = array_merge($end, $this->dieobj_properties($ar, $class, $refl));
        }

        //Methods
        if ($ar = $refl->getMethods()) {
            $end = array_merge($end, $this->dieobj_methods($ar, $class, $refl));
        }

        return $this->dietable($end);
    }

    function dieobj_class($data, $class = null, $refl = null)
    {
        $end = array();

        $end[] = array("prop" => " ",
                       "name" => "<b><span style='color:darkblue'>class</span> <big>".$this->dievalue_class($data, $class)."</big></b>",

        );

        return $end;

        $ar = array( #"getName" ,
            "getShortName"."getParentClass", "getInterfaces", #"getInterfaceNames" ,
            "getFileName", "getExtension", "getExtensionName",);

        foreach ($ar as $key) {

            if (!method_exists($data, $key)) {
                continue;
            }

            $value = $data->$key();

            if (!$value) {
                continue;
            }

            if ($key == "getFileName" AND $value) {
                $server = $_SERVER["SERVER_NAME"];
                $script = $_SERVER["SCRIPT_NAME"];
                $e = explode("/", $script);
                $path = "http://".$server."/".$e[1];
            } else {
                $value = $this->dievalue($value);
            }

            $end[] = array("prop"  => "class",
                           "name"  => "<b>".$key."</b>",
                           "value" => $value,);
        }

        return $end;
    }

    function dieobj_methods($data, $class = null, $refl = null)
    {
        $end = array();

        $className = $refl->getName();

        foreach ($data as $k => $v) {

            //name
            $name = $v->getName();

            //Method Parameter
            $ar_param = array();
            if ($param = $v->getParameters()) {
                foreach ($param as $vv) {
                    $ar_param[] = "$".$vv->getName()."";
                }
            }
            $param = $ar_param ? " ".implode(" , ", $ar_param)." " : "";

            //extends
            $extendsClass = $v->getDeclaringClass();
            if ($className == $extendsClass->getName()) {
                $extendsClass = "";
            }
            if ($extendsClass) {
                $extendsClass = $this->dievalue_class($extendsClass, $class);
            }

            //Doc
            $doc = "";
            if ($doc = $v->getDocComment()) {
                $doc = " <span title=\"".htmlspecialchars($doc)."\" style='cursor:help;color:rgb(128,128,64)'>/* doc */</span>";
            }

            $end[] = array("prop"   => $this->dieaccess($v),
                           "name"   => "<b style='color:darkblue'>function</b> <b style='color:blue'>".$name."</b>(<span style='color:blue'>".$param."</span>)".$doc,
                           "type"   => "func",
                #"header"	=> "<b>Methods</b>" . ($extendsClass ? " extends ". $extendsClass : ""),
                           "header" => "<big>".($extendsClass ? $extendsClass : "")."</big>",);
        }

        return $end;
    }

    function dieobj_properties($data, $class = null, $refl = null)
    {
        $end = array();

        $className = $refl->getName();

        foreach ($data as $v) {

            if (!is_object($v)) {
                continue;
            }

            //Name
            $name = $v->getName();

            //Value
            $value = null;
            if (method_exists($v, "setAccessible"))
                $v->setAccessible(true);
            if (method_exists($v, "getValue"))
                $value = $v->getValue($class);

            //extends
            $extendsClass = null;
            if (method_exists($v, "getDeclaringClass")) {
                $extendsClass = $v->getDeclaringClass();
                if ($className == $extendsClass->getName()) {
                    $extendsClass = "";
                }
                if ($extendsClass) {
                    $extendsClass = $this->dievalue_class($extendsClass, $class);
                }
            }

            $end[] = array("prop"   => $this->dieaccess($v),
                           "name"   => "<span style='color:blue'>$"."this -> <b>".$name."</b></span>",
                           "value"  => $this->dievalue($value, $class),
                           "type"   => gettype($value),
                #"header"=> "<b>Properties</b>" . ($extendsClass ? " extends ". $extendsClass : ""),
                           "header" => "<big>".($extendsClass ? $extendsClass : "")."</big> ",);
        }

        return $end;
    }

    function dietable($data)
    {

        $trs = "";
        $ar_trs = array();

        foreach ($data as $fe) {
            $type = isset($fe["type"]) ? $fe["type"] : null;
            $prop = isset($fe["prop"]) ? $fe["prop"] : null;
            $name = isset($fe["name"]) ? $fe["name"] : null;
            $value = isset($fe["value"]) ? $fe["value"] : null;
            $header = isset($fe["header"]) ? $fe["header"] : null;

            if ($value) {
                if ($type == "array") {
                    $name = "<div style='float:left;'>".$name."</div>"."<div style='float:left;'><pre style='margin:0;padding:0'>".trim($value)."</pre></div>";
                } else {
                    $name = "<div style='float:left;margin-right:5px'>".$name." =</div>"."<div style='float:left;'><pre style='margin:0;padding:0'>".trim($value)."</pre></div>";
                }

                $name .= "<div style='clear:both'></div>";
            }

            $line = "<tr>".($prop ? "<td valign=top width=80px style='text-align:right;padding-right:10px'>".$prop /*($type ? $prop .":". $type  : $prop)*/."</td>" : "")."<td valign=top>".$name."</td>"."</tr>";

            if ($header) {
                if (!isset($ar_trs[$header])) {
                    $ar_trs[$header] = "";
                }

                $ar_trs[$header] .= $line;
            } else {
                $trs .= $line;
            }
        }

        if (count($ar_trs)) {
            foreach ($ar_trs as $header => $fe_trs) {
                $trs .= "<tr><td></td><td colspan=4 height=30px valign=bottom>".$header."</td></tr>".$fe_trs;
            }
        }

        return "<style>table.td *{font-family:-moz-fixed;background:white;font-size:11px}</style><table cellspacing=0 cellpadding=0 class=td>".$trs."</table>";
    }

    function dievalue($value, $class = null)
    {

        $type = gettype($value);

        switch ($type) {
            case "string":
                $l = strlen($value);
                $max = 100;
                if ($l > $max) {
                    $value = substr($value, 0, $max)." ... ";
                }
                $value = "<span style='color:red'>\"".$value."\"</span>";
                break;

            case "integer":
            case "double":
                $value = "<b style='color:#0B615E'>".$value."</b>";
                break;

            case "boolean":
                $value = " <span style='color:white;background-color:".($value ? "green" : "red")."'>".($value ? "true" : "false")."</span>";
                break;

            case "array":
                $value = $this->diear($value, 1);
                break;

            case "NULL":
                $value = "<b>null</b>";
                break;

            case "object":
                $refl = new \ReflectionClass($value);
                #$value = $refl -> getName();
                $value = $this->dievalue_class($refl, $class);
                break;

            default:
                $value = "<i>(".$type.")</i>";
                break;
        }

        return $value;
    }

    function dievalue_class($data, $class = null)
    {

        $className = $data->getName();
        $classLink = $data->getFileName();

        if (!isset($_SERVER["SERVER_NAME"])) {
            return $className;
        }

        $server = $_SERVER["SERVER_NAME"];
        $script = $_SERVER["SCRIPT_NAME"];
        $e = explode("/", $script);
        $path = "http://".$server."/".$e[1];

        $link = "<a href='".$path."/dd.php?readfile=".urlencode($classLink)."' target='href' style='color:black;text-decoration:none'><small>".$className."</small></a>";

        #$link.= (method_exists($class -> $className,"__toString") ? " | ". $class : "");

        return $link;
    }

    function dieaccess($ref)
    {
        $ar = array("static", "public", "procted", "protected", "private", "abstract", "abstract",);
        foreach ($ar as $fe) {
            $method = "is".ucfirst($fe);
            if (method_exists($ref, $method)) {
                if ($ref->$method()) {
                    return $fe;
                }
            }
        }

        return "unknown";
    }
}
