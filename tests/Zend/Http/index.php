<?php

Zend_Loader_Autoloader::getInstance();
$autoloader = Zend_Loader_Autoloader::getInstance();

error_reporting(E_ALL);
set_time_limit(0);

$config['wurflapi']['wurfl_lib_dir'] = __DIR__.'/_files/Wurfl/1.1/';
$config['wurflapi']['wurfl_config_file'] = __DIR__.'/_files/Wurfl/resources/wurfl-config.php';
$config['terawurfl']['terawurfl_lib_dir'] = __DIR__.'/_files/TeraWurfl_2.1.3/tera-WURFL/';
$config['deviceatlas']['deviceatlas_lib_dir'] = __DIR__.'/_files/DA_php_1.4.1/';
$config['deviceatlas']['deviceatlas_data'] = __DIR__.'/_files/DA_php_1.4.1/sample/json/20101014.json';
$config['server'] = $_SERVER;

if (!empty($_GET['userAgent'])) {
    $config['server']['http_user_agent'] = $_GET['userAgent'];
} else {
    $_GET['userAgent'] = $_SERVER['HTTP_USER_AGENT'];
}

if (!empty($_GET['sequence'])) {
    $config['identification_sequence'] = $_GET['sequence'];
}

$options = [
    '',
    'mobile, text, desktop',
    'bot, mobile, validator, checker, console, offline, email, text',
    'text, bot, validator, checker, console, offline, email',
];
?>

<div id="content">

<p><b>Query by providing the user agent:</b></p>
<p>look at <a target="_blank"
	href="http://www.useragentstring.com/pages/useragentstring.php"
	target="_blank">http://www.useragentstring.com/pages/useragentstring.php</a>
or <a href="http://www.user-agents.org/" target="_blank">http://www.user-agents.org/</a></p>
<p>For mobile, look at <a target="_blank"
	href="http://en.wikipedia.org/wiki/List_of_user_agents_for_mobile_phones">http://en.wikipedia.org/wiki/List_of_user_agents_for_mobile_phones</a></p>
<fieldset>
<form method="get">
<div>Sequence : <select name="sequence" style="width: 500">
	<?php
foreach ($options as $option) {
    $selected = ($option == $_GET['sequence'] ? ' selected ' : '');
    echo '<option value="'.$option.'"'.$selected.'>'.($option ? $option : '(standard)').'</option>';
}
?>
</select> (DON'T FORGET TO CLEAN SESSION COOKIE)<br />
User Agent : <input type="text" name="userAgent" style="width: 700"
	value="<?php echo htmlentities($_GET['userAgent']); ?>" /> <br />
<input type="submit" /></div>
</form>
</fieldset>
</div>
