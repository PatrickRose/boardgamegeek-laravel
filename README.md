boardgamegeek-laravel
=====================

[![Build Status](https://travis-ci.org/PatrickRose/boardgamegeek-laravel.png?branch=master)](https://travis-ci.org/PatrickRose/boardgamegeek-laravel)

A wrapper for accessing the BGG api for Laravel 4

Installation
============

To use, just add

```json
requires {
"patrickrose/boardgamegeek": "dev-master"
}
```

to your composer.json

Then run

```bash
composer update
```

The BoardGameGeek api isn't the fastest of things, so a model and migration have
been provided to give you some help with response times. Just run:

```bash
php artisan migrate --package="patrickrose/boardgamegeek"
```

and you'll be given a table to house any information that you grab.

Usage
=====

The BoardGame is an Eloquent model, so you can do the usual find etc.
If you want to be certain of getting a game back, then use BoardGame::search():

```php
$game = BoardGame::search(14);
```

This will find out if you have this game stored in your database, and return it if so.
If not, then it'll ask BGG for the details and make a game from those.

> **Note:** Whenever we get a game's information from BGG, we insert it into the database.

If you need to get lots of games, use BoardGame::getManyGames() to get an array of them (and insert them all into the database):

```php
$idList = [1,2,3,4];
$games = BoardGame::getManyGames($idList);
```

If you've got a game a while ago and want to update the details, then just run:

```php
$game->reload();
```

This will requery BGG, get the latest information and update the object.

You can also get all the ids of a game that a user owns. To do that:

```php
$gameList = BoardGame::getUsersCollection($username);
```

You'll just get a list of ids, which you can then feed into getManyGames() or loop through for many-to-many purposes.

```php
$gameList = BoardGame::getUsersCollection($user->boardGameGeek);
foreach($gameList as $game) {
    $user->games()->attach($game);
}
$games = BoardGame::getManyGames($game);
```

> **Note:** There may be a faster way of doing this, which prevents 2 api calls. But I've not yet seen a way.

If you want to search BGG by name, then just use searchByName() to get the title and id.

```php
$games = BoardGame::searchByName($title);
foreach($games as $game) {
    echo $game['title'] . ' has id number: ' . $game['id'];
}
```

This won't actually create the games, since that'd be another api call.

Contributing
============

I welcome any contributions! Just fork this and send me a pull request. If I'm not responding, poke me on [Twitter][Twitter]

[Twitter]: http://twitter.com/DrugCrazed "Twitter"
