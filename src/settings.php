<?php

date_default_timezone_set('America/Lima');

$scriptName = str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME']));
$requestUri = parse_url('http://example.com' . $_SERVER['REQUEST_URI'], PHP_URL_PATH);
$virtualPath = '/' . ltrim(substr($requestUri, strlen($scriptName)), '/');
$hostName = stripos($_SERVER['REQUEST_SCHEME'], 'https') === 0 ? 'https://' : 'http://' . $_SERVER['SERVER_NAME'];

define('HOST', $hostName);
define('URI', $requestUri);
define('URL_PATH', $scriptName === '/' ? '' : ('/'. trim($scriptName,'/')));
define('URL',$virtualPath);

define('ROOT_DIR', $_SERVER["DOCUMENT_ROOT"] . $scriptName);
define('CONTROLLER_PATH', ROOT_DIR. '/src/Controllers');
define('MODEL_PATH', ROOT_DIR. '/src/Models');
define('VIEW_PATH', ROOT_DIR. '/src/Views');
define('CONTROLLER_GROUP','SnControllerGroup');

define('SESS_KEY','SnId');
define('SESS_MENU','SnMenu');
define('SESS_CURRENT_LOCAL','SnCurrentLocal');

define('APP_NAME','SnFact');
define('APP_AUTHOR','SnFact');
define('APP_DESCRIPTION','SnFact');

// Temp
define('SUNAT_SERVICE_URL', 'https://e-beta.sunat.gob.pe/ol-ti-itcpfegem-beta/billService?wsdl');
define('SUNAT_GUIDE_SERVICE_URL', 'https://e-beta.sunat.gob.pe/ol-ti-itemision-guia-gem-beta/billService?wsdl');