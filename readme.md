
## Admin
<pre>
URL : domain.com/wemeet_sysadministrator
username : myadmin
password : admin!@#
email : my@admin.com
</pre>

## S-Cart functions:

<pre>
======= FRONT-END =======
- Multi-language
- Multi-currency
- Multi-address
- Shopping cart
- Customer login
- Product attributes: cost price, promotion price, stock, tax..
- CMS content: category, news, content, web page
- Plugin: Shipping, payment, Discount, Total, Multiple vendor...
- Upload manager: banner, images,..
- SEO support: custome URL
- API module
- Support library plugin, template online
...

======= ADMIN =======

- Admin roles, permission
- Product manager
- Order management
- Customer management
- Template manager
- Plugin manager
- Store manager
- System config: email setting, info shop, maintain status,...
- Backup, restore data
- Report: chart, statistics, export csv, pdf...
...

</pre>

## Technology
- Core <a href="https://laravel.com">Laravel Framework</a>

## Requirements:

From Version 5.0

> Core laravel framework 8.x Requirements::

```
- PHP >= 7.3
- OpenSSL PHP Extension
- PDO PHP Extension
- Mbstring PHP Extension
- Tokenizer PHP Extension
- XML PHP Extension
- Ctype PHP Extension
- JSON PHP Extension
- BCMath PHP Extension
```



## Useful information:

To view S-Cart version information

`php artisan sc:info`

To update the core version of S-Cart:

`composer update s-cart/core`
Or you can use `php composer.phar update s-cart/core` if you don't have composer installed.

To create a plugin:

`php artisan sc:make plugin  --name=Group\PluginName`

To create data backup file (The sql file is stored in storage/backups):

`php artisan sc:backup --path=abc.sql`

To recover data:

`php artisan sc:restore --path=abc.sql`

To manually customize the admin page (<code>resources/views/admin + config/admin.php</code>):

`php artisan sc:customize admin`

This command will create new directories `resources/views/admin` and file `config/admin.php`
After set the value `customize=true` in `config/admin.php` you can modify template admin. 

To manually customize file config validation (<code>config/validation.php</code>):

`php artisan sc:customize validation`

More detail: https://s-cart.org/en/docs/master


## License:

`S-Cart` is licensed under [The MIT License (MIT)](LICENSE).

## Demo:

- Font-end : http://demo.s-cart.org
- Back-end: http://demo.s-cart.org/sc_admin   <code>User/pass: test/123456</code>
