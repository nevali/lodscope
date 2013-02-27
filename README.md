# LODscope

This is LODscope, a simple linked data browser for debugging and such.

It's built on [Eregansu](https://github.com/eregansu), and requires that
you have the [cURL](http://curl.haxx.se/) and [librdf](http://librdf.org/)
PHP5 modules installed and enabled.

After checking out, you will need to:

    git submodule update --init --recursive
    ./cli install

If you specify the application name as `lodscope`, the existing
`appconfig.lodscope.php` will be used, which you shouldn't need to re-generate.
Otherwise, the installer will create a new `appconfig.php` for you.

It will also generate an `apache2.conf` for you -- adjust the virtual host
name and symlink or copy it into your Apache `conf.d` or `sites-enabled` (or
whatever your local equivalent is).

See the existing instance-specific `config.php` files for tunables you that
you can define in yours (such as `CURL_CACHE_DIR` and `EREGANSU_DEBUG`).

LODscope is licensed under the terms of the Apache License, Version 2.0.
