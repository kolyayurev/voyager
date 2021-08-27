<p align="center"><a href="https://the-control-group.github.io/voyager/" target="_blank"><img width="400" src="https://s3.amazonaws.com/thecontrolgroup/voyager.png"></a></p>

<p align="center">
<a href="https://github.styleci.io/repos/72069409/"><img src="https://styleci.io/repos/72069409/shield?style=flat" alt="Build Status"></a>
<a href="https://packagist.org/packages/tcg/voyager"><img src="https://poser.pugx.org/tcg/voyager/downloads.svg?format=flat" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/tcg/voyager"><img src="https://poser.pugx.org/tcg/voyager/v/stable.svg?format=flat" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/tcg/voyager"><img src="https://poser.pugx.org/tcg/voyager/license.svg?format=flat" alt="License"></a>
<a href="https://github.com/larapack/awesome-voyager"><img src="https://cdn.rawgit.com/sindresorhus/awesome/d7305f38d29fed78fa85652e3a63e154dd8e8829/media/badge.svg" alt="Awesome Voyager"></a>
</p>

# **V**oyager - The Missing Laravel Admin
Made with ❤️ by [The Control Group](https://www.thecontrolgroup.com)

![Voyager Screenshot](https://s3.amazonaws.com/thecontrolgroup/voyager-screenshot.png)

Website & Documentation: https://voyager.devdojo.com/

Video Tutorial Here: https://voyager.devdojo.com/academy/

Join our Slack chat: https://voyager-slack-invitation.herokuapp.com/

View the Voyager Cheat Sheet: https://voyager-cheatsheet.ulties.com/

<hr>

Laravel Admin & BREAD System (Browse, Read, Edit, Add, & Delete), supporting Laravel 6 and newer!

> Want to use Laravel 5? Use [Voyager 1.3](https://github.com/the-control-group/voyager/tree/1.3)

## Installation Steps

### 1. Require the Package

After creating your new Laravel application you can include the Voyager package with the following command:

```bash
composer require tcg/voyager
```

### 2. Add the DB Credentials & APP_URL

Next make sure to create a new database and add your database credentials to your .env file:

```
DB_HOST=localhost
DB_DATABASE=homestead
DB_USERNAME=homestead
DB_PASSWORD=secret
```

You will also want to update your website URL inside of the `APP_URL` variable inside the .env file:

```
APP_URL=http://localhost:8000
```

### 3. Run The Installer

Lastly, we can install voyager. You can do this either with or without dummy data.
The dummy data will include 1 admin account (if no users already exists), 1 demo page, 4 demo posts, 2 categories and 7 settings.

To install Voyager without dummy simply run

```bash
php artisan voyager:install
```

If you prefer installing it with dummy run

```bash
php artisan voyager:install --with-dummy
```

And we're all good to go!

Start up a local development server with `php artisan serve` And, visit [http://localhost:8000/admin](http://localhost:8000/admin).

## Creating an Admin User

If you did go ahead with the dummy data, a user should have been created for you with the following login credentials:

>**email:** `admin@admin.com`   
>**password:** `password`

NOTE: Please note that a dummy user is **only** created if there are no current users in your database.

If you did not go with the dummy user, you may wish to assign admin privileges to an existing user.
This can easily be done by running this command:

```bash
php artisan voyager:admin your@email.com
```

If you did not install the dummy data and you wish to create a new admin user you can pass the `--create` flag, like so:

```bash
php artisan voyager:admin your@email.com --create
```

And you will be prompted for the user's name and password.


## Front Control Panel

Add `admin-controls-expanded` cookie to `EncryptCookies` middleware


## Custom fields

Model must have function

```

public function adminFields():array{
    
     return [
      'meta_1' => [
          "name" => "meta_1",
          "type" => "varchar",
          "null" => "YES",
          "field" => "timestamp",
          "key" => null,
          // OTHERS
          "default" => null,
          "notnull" => false,
          "length" => 0,
          "precision" => 10,
          "scale" => 0,
          "fixed" => false,
          "unsigned" => false,
          "autoincrement" => false,
          "columnDefinition" => null,
          "comment" => null,
          "oldName" => "timestamp",
          "extra" => "",
          "composite" => false,
          "indexes" => [],
      ],
     ];
    }
```
And setter and getter

```
    public function setOptionsAttribute($value)
    {
        $this->attributes['options'] = json_encode($value);
    }

    public function getOptionsAttribute($value)
    {
        return json_decode(!empty($value) ? $value : '{}');
    }

    public function setMeta1Attribute($value)
    {
        $this->attributes['options'] = collect($this->options)->merge(['meta_1' => $value]);
    }
    public function getMeta1Attribute()
    {
        return $this->options->meta_1 ?? null;
    }
```

## Custom breads 

You must enter a unique slug and specify the model for BREAD.

Example slug: `feedback-client`, model: `App\Models\FeedbackClient`

> But first you have to create a model

Model must have next code 

```

public static function boot(){
    parent::boot();
    static::creating(function ($model) {
        $model->type = 'client';
    });
    static::addGlobalScope('client', function($builder){
        $builder->where('type', 'client');
    });
}

```


## Import seeder 

```
php artisan db:seed --class=VoyagerBreadFeedbacksSeeder
```

Next, you need go to imported bread edit page and save. Voyager will generate permissions and then you need to give the roles access to this BREAD.