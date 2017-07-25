# Simple Form Helper
___
Simple library to handle PHP form data with highly secure environment.

### Why this ?
 * Lighy-weight,
 * Highly optimizable library,
 * No Need to worry about security issues from FORM,
 * Eliminating suspicious content,
 * Simple library for Core-PHP Developers.


## Steps to Use

#### Initiating Handler

```php
       $post = new form();
```

#### Set data to handle

```php
       $data = $_REQUEST;
       $post->setForm($data);
```

#### Set return type for array or object

```php
      $post->toJSON(true);   // The data in the form of array or object will return as JSON.
```

#### Getting data from safe environment

```php
    $post->get('Element','Default_value')
```
___
# License
### MIT
