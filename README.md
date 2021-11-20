# QrCode Bulk generator simple web app

this web app is based on the [chillerlan/php-qrcode](https://github.com/chillerlan/php-qrcode)
created this simple app to make a [php script](https://github.com/HijenHEK/qrbulk-generator-script) that generates qrcode from an array accessible via a VueJs interface

## set up locally

- clone the repo `git clone git@github.com:HijenHEK/qrbulk-generator.git`
- `cd qrbulk-generator`
- install composer dependencies  `composer install`    
- `cp .env.example .env`
- generate an app key `php artisan key:generate`
- that's it !

## usage

- run your server `php artisan serve`
- open the url (somthing like `localhost:8000`)
- use the app

> ![image](https://drive.google.com/uc?export=view&id=1cvdBDx407rrexsnfIjHW-bGFOdzui3sH)
  
