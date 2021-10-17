## Upgrade to 1.21.11

>Rebuild assets

>Make migrate 

Add to voyager config 

```
'menu'=>[
    'admin'=> [
        'cache'=> 86400,
    ],
]
```

## Upgrade to 1.20.13

>Rebuild styles

## Upgrade to 1.20.3

Change bread browse template. You must remove server side search form  and include
```
@include('voyager::bread.partials.browse.server-side-search')
```
or rename variable in template `searchNames` to `searchableFields`

>Rebuild styles and scripts
