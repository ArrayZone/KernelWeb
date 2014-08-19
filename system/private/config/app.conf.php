<?php
class app {
	static $template = 'dashboard';
	static $dir; // It contain the absolute HTTP URL to PUBLIC (auto generated)
	static $tdir; // it contain the absolute HTTP URL to THE CURRENT TEMPLATE (auto generated)
	static $others = array();
}

app::$dir = kw::$config['url_base'] . 'public/';
app::$tdir = app::$dir . 'templates/'.app::$template.'/'; 