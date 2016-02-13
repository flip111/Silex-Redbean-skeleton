# What is this? #
This is a project skeleton that features Silex and RedbeanPHP. Have a look in the composer.json to see which libraries are loaded.

![Screenshot of homepage](/screenshot.png?raw=true "Screenshot of homepage")

# Installation #
1. Clone the repository or download the zip file
2. Place files somewhere on disk
3. Install composer https://getcomposer.org/
4. Go to the directory where composer.json is located and run: `composer install`
5. Point your webserver to the web directory (see apache below for an example)

# Apache #
Look for `apache/conf/httpd.conf` and inside you should make the necessary configuration. `D:/dev/http` should be replaced to the `web` directory in the project.

```
DocumentRoot "D:/dev/http"
<Directory "D:/dev/http">
    Options Indexes FollowSymLinks
    AllowOverride All
    Require all granted
</Directory>
```

The `AllowOverride All` is the important part here. This is needed so that you can get pretty url's like `www.domain.com/users/yourname` instead of `www.domain.com/users.php?user=yourname`

Documentation: https://httpd.apache.org/docs/current/howto/htaccess.html

# Making your own pages #
To get started making your own pages. For example `www.domain.com/guestbook` you can copy

* [The controller code](https://github.com/flip111/Silex-Redbean-skeleton/blob/453f178f5a642babf1cce1ba530dc0cbe0514335/src/Controller/home.php#L8-L41) into the same file or another file in the same directory. Make sure you change `$app->match('/'` to `$app->match('/guestbook'`
* Change [the controller name](https://github.com/flip111/Silex-Redbean-skeleton/blob/453f178f5a642babf1cce1ba530dc0cbe0514335/src/Controller/home.php#L41) so that silex knows there are different controllers and also so that a link to your new page will be added in the bootstrap menu up top.
* [The view](https://github.com/flip111/Silex-Redbean-skeleton/blob/453f178f5a642babf1cce1ba530dc0cbe0514335/src/View/home.html.twig) can be copied to a file in the same directory with a different name. Make sure that in your controller you change the name of the view template [here](https://github.com/flip111/Silex-Redbean-skeleton/blob/453f178f5a642babf1cce1ba530dc0cbe0514335/src/Controller/home.php#L37)

# Expanding functionality #
Right now pretty much everything is done in the controller. This is on purpose. When you start out prototyping your project you want to get going fast and see what works. Later when you want to make more reusable components you can start making [Form classes](http://symfony.com/doc/current/book/forms.html) or real [Model classes](www.redbeanphp.com/models). Also take a look at [index.php](https://github.com/flip111/Silex-Redbean-skeleton/blob/453f178f5a642babf1cce1ba530dc0cbe0514335/web/index.php#L31-L74) where already quite a few services have been enabled, you can read about them by just googling for them.

# Production #
Even though this setup is initially geared towards prototyping on your own computer, it doesn't mean you need super heavy frameworks and libraries to be production ready. This setup is actually really fast. There is [a section](https://github.com/flip111/Silex-Redbean-skeleton/blob/453f178f5a642babf1cce1ba530dc0cbe0514335/web/index.php#L19-L25) about debugging that you should configure like this
```
$app['debug'] = false;
R::debug(false);
R::freeze(true);
R::useWriterCache(true);
```
Twig templates are cached be default. If you need more speed please install PHP 7.

# Implementation #
Quite a few javascript file have been moved to the header, while it's best practice to put them just before the closing body tag. This is done because the timepicker widget places javascript inline for which some libraries have to be already loaded. I also made a twig extension that collects all the javascript snippets and places them in one block at the end of this page. However since this project is just meant as skeleton i didn't want to add too advanced features.

## Database ##
As you might have noticed there is no section so far about any database. The default configuration writes to a sqlite database, which is a high performance database which lacks the more advanced query features. You can keep using this database or read the redbean documentation on how to configure another database.

Redbean configures your database on the fly. This is a huge benefit in development speed, but it also means that you can not use an existing schema. If you need to use existing data then first model the data with redbean on an empty database and then write queries to load your data in the redbean schema.

# Todo #
* Update frontend code (made by http://www.initializr.com/) last update was at 19-9-2014
* Add more widgets (Redbean, Select2)