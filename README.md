Likable Helper for Eloquent models
=

Installation
------------
Run `composer require miragepresent/likable`;

And `php artisan migrate`


Usage
-----

* **The simple way.** Just use `Likable` trait in your model:  
```
namespace App;
 
use Illuminate\Database\Eloquent\Model;
use MiragePresent\Likable\Likable;
 
class Photo extends Model {
 
    use Likable;
 
}
```

Now You are able to use methods `like()`, `dislike()` and `toggleLike()` in your model for adding/removing a like of an
authorized user.

Example: 
```

/** @var \App\Photo $photo */
$photo = Photo::find(1); 
 
// Add like to the photo
$photo->like();
 
var_dump($photo->is_liked); \\ true, 1
 
echo $photo->count_likes. ' user(s) liked this photo';
echo $photo->likedBy->ipmlode('name', ',') . ' liked this photo';

```