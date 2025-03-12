# Kirby Image Collections

A Kirby CMS plugin that provides methods and collections to easily access unique images from pages and across your entire site.

## Features

- **Page Images**: Access all unique images from the current page and its immediate children
- **Recursive Page Images**: Access all unique images from the current page and all its descendants
- **Site Images**: Access all unique images from the entire site
- Convenient blueprint and template usage
- Prevents duplicate images with the same name

## Installation

### Via Git Submodule (recommended)

```bash
git submodule add git@github.com:grovesdm/kirby-image-collections.git src/site/site/plugins/kirby-image-collections
```

### Via Composer

```bash
composer require grovesdm/kirby-image-collections
```

### Manual Installation

1. Download or clone this repository
2. Place the folder in `site/plugins/kirby-image-collections`

## Usage

### In Templates

#### 1. Get images from the current page and its immediate children

```php
// Get all unique images from the current page and its children
$images = $page->pageImages();

// With sorting options
$images = $page->pageImages('date', 'desc');

// Get 4 random images
$randomImages = $page->pageImages()->shuffle()->limit(4);
```

#### 2. Get images recursively from all descendant pages

```php
// Get all unique images from the current page and all descendants
$allSectionImages = $page->pageImages('filename', 'asc', true);
```

#### 3. Get images from the entire site

```php
// Get all unique images from the entire site
$siteImages = site()->siteImages();

// With sorting
$siteImages = site()->siteImages('date', 'desc');
```

#### 4. Using collections directly

```php
// Using collections directly
$pageImages = collection('page-images');
$recursiveImages = collection('page-images-recursive');
$allSiteImages = collection('site-images');
```

### In Blueprints (Kirby 4.6+)

```yaml
fields:
  # For current page and immediate children images
  slides:
    label: Page Images
    type: files
    query: page.pageImages
    multiple: true
    
  # For recursive page images
  recursive_slides:
    label: All Section Images (Recursive)
    type: files
    query: page.pageImages('filename', 'asc', true)
    multiple: true
    
  # For all site images
  site_slides:
    label: All Site Images
    type: files
    query: site.siteImages
    multiple: true
```

## Examples

### Display 4 random images from the current page

```php
<div class="random-gallery">
  <?php foreach($page->pageImages()->shuffle()->limit(4) as $image): ?>
  <figure>
    <img src="<?= $image->resize(400, 300)->url() ?>" alt="<?= $image->alt() ?>">
    <figcaption><?= $image->caption()->html() ?></figcaption>
  </figure>
  <?php endforeach ?>
</div>
```

### Create a site-wide slider with 5 random images

```php
<div class="site-slider">
  <?php foreach(site()->siteImages()->shuffle()->limit(5) as $image): ?>
  <div class="slide">
    <img src="<?= $image->resize(1200, 600)->url() ?>" alt="<?= $image->alt() ?>">
    <div class="caption">
      <h3><?= $image->caption()->html() ?></h3>
      <p>From: <?= $image->parent()->title() ?></p>
    </div>
  </div>
  <?php endforeach ?>
</div>
```

## Options

All methods accept the following parameters:

- `$sort` (string): Property to sort by (default: 'filename')
- `$direction` (string): Sort direction, 'asc' or 'desc' (default: 'asc')
- `$recursive` (boolean): For `pageImages()` only - whether to include images from all descendants (default: false)

## Usage Examples

```yaml
# Example blueprint for a slider section in Kirby 4.6

title: Slider
icon: image

fields:
  # For current page and immediate children images
  slides:
    label: Page Images
    type: files
    query: page.pageImages
    multiple: true
    layout: cards
    info: "{{ file.dimensions }}"
    
  # For current page and all descendant images (recursive)
  recursive_slides:
    label: All Section Images (Recursive)
    type: files
    query: page.pageImages('filename', 'asc', true)
    multiple: true
    layout: cards
    
  # For all site images
  site_slides:
    label: All Site Images
    type: files
    query: site.siteImages
    multiple: true
    layout: cards
    
  # Example select field for a single featured image
  featured_image:
    label: Featured Image
    type: select
    options: query
    query: site.siteImages
    text: "{{ file.filename }}"
    value: "{{ file.id }}"
```

```php
<?php
// Example 1: Display 4 random images from the current page and its children
?>
<div class="random-gallery">
  <?php foreach($page->pageImages()->shuffle()->limit(4) as $image): ?>
  <figure>
    <img src="<?= $image->resize(400, 300)->url() ?>" alt="<?= $image->alt() ?>">
    <figcaption><?= $image->caption()->html() ?></figcaption>
  </figure>
  <?php endforeach ?>
</div>

<?php
// Example 2: Display all images recursively from the current section
?>
<div class="section-gallery">
  <?php foreach($page->pageImages('filename', 'asc', true) as $image): ?>
  <figure>
    <img src="<?= $image->thumb(['width' => 300])->url() ?>" alt="<?= $image->alt() ?>">
    <figcaption><?= $image->caption()->html() ?></figcaption>
  </figure>
  <?php endforeach ?>
</div>

<?php
// Example 3: Create a site-wide slider with 5 random images
?>
<div class="site-slider">
  <?php foreach(site()->siteImages()->shuffle()->limit(5) as $image): ?>
  <div class="slide">
    <img src="<?= $image->resize(1200, 600)->url() ?>" alt="<?= $image->alt() ?>">
    <div class="caption">
      <h3><?= $image->caption()->html() ?></h3>
      <p>From: <?= $image->parent()->title() ?></p>
    </div>
  </div>
  <?php endforeach ?>
</div>

<?php
// Example 4: Using the collections directly
?>
<div class="featured-images">
  <h2>Featured Images</h2>
  <?php foreach(collection('site-images')->shuffle()->limit(3) as $image): ?>
  <figure>
    <img src="<?= $image->crop(400, 400)->url() ?>" alt="<?= $image->alt() ?>">
    <figcaption><?= $image->caption()->html() ?></figcaption>
  </figure>
  <?php endforeach ?>
</div>
```

## License

MIT License

## Author

[grovesdm](https://github.com/grovesdm)