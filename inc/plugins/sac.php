<?php
if(!defined("IN_MYBB"))
{
	die("Direct initialization of this file is not allowed.<br /><br />Please make sure IN_MYBB is defined.");
}

$plugins->add_hook("showthread_start","select_all_code");

function sac_info()
{
	return array(
		"name"			=> "Seleccionar Todo (MyCodes)",
		"description"	=> "Seleccionar en Códigos PHP y Código preformateado",
		"website"		=> "http://darkneo.skn1.com",
		"author"		=> "Dark Neo",
		"authorsite"	=> "http://darkneo.skn1.com",
		"version"		=> "1.0",
		"compatibility" => "16*",
		"guid"			=> ""
	);
}

function sac_activate()
{
    global $db;
	$query_tid = $db->write_query("SELECT tid FROM ".TABLE_PREFIX."themes");
	$themetid = $db->fetch_array($query_tid);
	$style = array(
			'name'         => 'sac.css',
			'tid'          => $themetid['tid'],
			'stylesheet'   => $db->escape_string('.sac{
	padding: 8px 12px;
	text-decoration: none;
	font-family: Verdana, Tahoma, Sans-Serif;
	font-size: 9px;
	font-weight: bold;
}

.sac:hover{
	color: #ffffff;
	text-decoration: none;
	transition: all 0.5s ease;
}'),
			'lastmodified' => TIME_NOW
		);
		$sid = $db->insert_query('themestylesheets', $style);
		$db->update_query('themestylesheets', array('cachefile' => "css.php?stylesheet={$sid}"), "sid='{$sid}'", 1);
		$query = $db->simple_select('themes', 'tid');
		while($theme = $db->fetch_array($query))
		{
			require_once MYBB_ADMIN_DIR.'inc/functions_themes.php';
			update_theme_stylesheet_list($theme['tid']);
		}
}

function sac_deactivate()
{
	global $db;
    	$db->delete_query('themestylesheets', "name='sac.css'");
		$query = $db->simple_select('themes', 'tid');
		while($theme = $db->fetch_array($query))
		{
			require_once MYBB_ADMIN_DIR.'inc/functions_themes.php';
			update_theme_stylesheet_list($theme['tid']);
		}
}

function select_all_code()
{
	global $mybb, $headerinclude, $lang;
	
	$lang->load("global", false, true);
	$lang->load("sac", false, true);
	
	$headerinclude .= "<br /><script type=\"text/javascript\" src=\"{$mybb->settings['bburl']}/jscripts/sac.js\"></script>";

	$lang->php_code .= " <a href=\"#\" onclick=\"selectCode(this); return false;\" class=\"sac\">{$lang->sac_select_all}</a>";
	$lang->code .= " <a href=\"#\" onclick=\"selectCode(this); return false;\" class=\"sac\">{$lang->sac_select_all}</a>";
}
?>