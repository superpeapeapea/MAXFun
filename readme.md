
# About
It is for MAX. Code based on laravel, angularjs. A key-value storage system like Redis is recommended for queue service and key storage of artists. Supervisord is recommended for queue service

# Install

> Require php 5.4+, php-cli

#### Do the following on your machine to install MAXFun laravel:
```shell
git clone https://github.com/superpeapeapea/MAXFun;
cd ./MAXFun;
php composer.phar update
```

#### Do the following to config server:
```shell
cp .env-sample .env-
```
Please change queue server and cache server to corresponding service on your machine if there were.

#### Please make a virtual machine server and make the public root ./MAXFun/public
Then it should be all set. Just request the home page.

# Review
If you are not gonna install it. Please simply visit the [DEMO](http://junhuang.us)

I made all code aggregated, so it is very simple to review them together in git:

1 HTML+CSS:
[max-home.php](https://github.com/superpeapeapea/MAXFun/blob/master/resources/views/max-home.php)

2 JS(Angular Based)
[maxfun.js](https://github.com/superpeapeapea/MAXFun/blob/master/public/js/maxfun.js)

3 Backend(Laravel Based)
[MXArtistController.php](https://github.com/superpeapeapea/MAXFun/blob/master/app/Http/Controllers/MXArtistController.php)
With which all the base class, helper class, system service, models, controller are defined. Just for easy review.



## License

The Laravel framework is open-sourced software licensed under the [MIT license](http://opensource.org/licenses/MIT).
