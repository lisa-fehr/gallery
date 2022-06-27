# gallery
Packaged version of the recreate gallery project

Run:

`composer require lisa-fehr/gallery`

`php artisan storage:link`

`php artisan gallery:install`

`php artisan images:generate`

`npm run development`



Add this div to a view called portfolio:
```html
<div id="gallery-app" class="text-sm w-full" data-filters="{{$filter['tags'] ?? ''}}"></div>
```

Add this kind of code to routes where tags match the uber_tags table names you want to show:

```php
Route::get('photos', function () {

return view('portfolio')->with('filter', ['tags' => 'photos,california2014']);

})->name('photos');
```
