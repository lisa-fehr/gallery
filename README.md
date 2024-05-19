# gallery
Packaged version of the recreate gallery project

<img width="100%" alt="Screen Shot 2024-05-19 at 3 41 36 AM" src="https://github.com/lisa-fehr/gallery/assets/6653340/fd768d85-75fa-4c76-bebe-27a5b88e9e98">

## Run:

`composer require lisa-fehr/gallery`

`php artisan storage:link`

`php artisan gallery:install`

`php artisan images:generate`

`npm run development`

## Add the css and js files to your layout file:
```html
<link rel="stylesheet" href="{{ asset('vendor/lisa-fehr/gallery/css/app.css') }}" type="text/css" media="screen"/>
...
<script src="{{mix('js/app.js', 'vendor/lisa-fehr/gallery')}}"></script>
```

## Add this div to a view called `portfolio`:
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

## Add to routes file
Add this kind of code to routes, where `tags` match the `uber_tags` table (route `name` is required to match the tag):

```php
Route::get('photos', function () {

return view('portfolio')->with('filter', ['tags' => 'California,California2005,California2009,California2014']);

})->name('photos');
```

Or use the Tag model to get all the children:
```php
Route::get('California', function () {
    $children = UberTags::where('name', 'california')->allChildren()->pluck('name')->implode(',');
    return view('portfolio')->with('filter', ['tags' => 'california,' . $children]);
})->name('California');
```

If the named route for the tag doesn't exist, it will not show up in navigation to prevent broken links.
