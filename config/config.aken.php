<?php

/* Instance configuration for aken - generated by Eregansu Install at 2013-02-27 21:09:50*/

/* Each copy of an application must have a unique INSTANCE_NAME
 * If you use (either directly or directly) the "cluster" module,
 * then INSTANCE_NAME is used to determine which cluster this
 * is part of.
 */
define('INSTANCE_NAME', 'aken');

/* The "heartbeat" module will use HOST_NAME in preference to INSTANCE_NAME if
 * defined.
 */
/* define('HOST_NAME', 'aken'); */

/* With the below defined, if jQuery is used by any pages, it will be loaded
 * from Google's servers. If you comment it out or remove it, Eregansu will
 * expect to load jQuery from SCRIPTS_IRI/jquery/jquery-<version>/jquery.min.js
 */
define('SCRIPTS_USE_GAPI', true);

/* Uncomment the below to override the path used to serve scripts,
 * which by default is set to the root URL of your application.
 */
/* define('SCRIPTS_IRI', 'http://static1.example.com/scripts/'); */

/* For applications which make use of the object store, set
 * OBJECT_CACHE_ROOT to the absolute path of a directory where
 * a JSON-encoded copy of each stored object should be written to.
 * Objects are stored at <OBJECT_CACHE_ROOT>/<kind>/<uuid[0..2]>/<uuid>.json
 * If specified, OBJECT_CACHE_ROOT must include a trailing slash.
 */
/* define('OBJECT_CACHE_ROOT', '/shared/cache/objects/'); */

define('CURL_CACHE_DIR', INSTANCE_ROOT . 'cache/curl/');
define('CACHE_TIME', 60);
