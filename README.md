# Gallery
Packaged version of the recreate gallery project

<img width="100%" alt="Screen Shot 2024-05-19 at 3 41 36 AM" src="https://github.com/lisa-fehr/gallery/assets/6653340/fd768d85-75fa-4c76-bebe-27a5b88e9e98">

## Requirements
- PHP ^8.1
- Laravel 9 and up
- GD Library *

## Getting Started
```bash
composer require lisa-fehr/gallery
```

| Command                              | Description                                                                                                                                                                               |
|--------------------------------------|-------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------|
|`php artisan storage:link`| Connect the [public/storage] link to [storage/app/public]                                                                                                                                 |
|`php artisan gallery:install`| Publish Gallery Assets and Config. Gives access to the gallery vue components. Can use `gallery-originals` and `gallery` filesystems. Adds a gallery file under /config for custom routes |
|`php artisan images:generate`| Creates the missing image placeholders. Place images under storage/gallery in a nested folder structure like: Photos/California, `{parent}`/`{tag}`                                       |                                                                                                                                                         |


## Usage

### Add the css and js files to your layout file
```html
<link rel="stylesheet" href="{{ asset('vendor/lisa-fehr/gallery/css/app.css') }}" type="text/css" media="screen"/>
...
<script src="{{mix('js/app.js', 'vendor/lisa-fehr/gallery')}}"></script>
```

### Add gallery component to a view called `portfolio`
```html
<div id="gallery-app" class="text-sm w-full" data-filters="{{$filter['tags'] ?? ''}}"></div>
```

You can also override routes with inconsistent patterns:
```javascript
{
    "tag": "path from root - no starting slash needed"
}
```
```html
<div id="gallery-app" class="text-sm w-full" 
    data-filters="{{$filter['tags'] ?? ''}}"
    data-routes='{"portfolio":"portfolio","Folder2005":"Folder\/2005"}'
></div>
```

### Add to web routes file
The landing page doesn't take any filters and should show everything.
```php
Route::get('portfolio', function () {
    return view('portfolio');
})->name('portfolio');
```

Additional pages: Route name must match the `name` field in the `uber_tags` table. Must include the current tag at the beginning.
```php
Route::get('photos', function () {
    return view('portfolio')
        ->with('filter', [
            'tags' => 'photos,California,California2005,California2009,California2014'
        ]);
})->name('photos');
```

Or use the Tag model to get all the children:
```php
Route::get('California', function () {
    $children = UberTags::where('name', 'california')->allChildren()->pluck('name')->implode(',');
    return view('portfolio')
        ->with('filter', [
            'tags' => 'california,' . $children
        ]);
})->name('California');
```

If the named route for the tag doesn't exist, it will not show up in navigation to prevent broken links.

## Development

Run the migrations
```bash
php artisan migrate --path=vendor/lisa-fehr/gallery/src/database/migrations
```
Run the seeders
```bash
php artisan db:seed --class=LisaFehr\\Gallery\\Database\\Seeders\\GalleryDatabaseSeeder
```

## Troubleshooting
### The missing thumbnail or image is broken
  - Check that the `APP_URL` in the .env is correct. IE: Contains the port number.
  - Make sure these were run: `php artisan storage:link` and `php artisan images:generate`
### Using the UberTags model returns error
> Illuminate\Database\QueryException  SQLSTATE[HY000]: General error: 1 HAVING clause on a non-aggregate query

Don't use sqlite. Check the .env file and fill in:
```php
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=laravel
DB_USERNAME=root
DB_PASSWORD=
```
