<?php
// site/plugins/kirby-image-collections/index.php

Kirby::plugin('slyfox/image-collections', [
    'pageMethods' => [
        'pageImages' => function($sort = 'filename', $direction = 'asc', $recursive = false) {
            // Get all images from current page
            $allImages = $this->files()->filterBy('type', 'image');

            // Add child page images
            foreach($this->children() as $child) {
                $allImages = $allImages->add($child->files()->filterBy('type', 'image'));

                // If recursive is true, add images from all descendants
                if($recursive === true) {
                    $allImages = $this->addDescendantImages($allImages, $child);
                }
            }

            return $this->makeUniqueCollection($allImages, $sort, $direction);
        },

        'addDescendantImages' => function($collection, $page) {
            // Recursively add images from all descendants
            foreach($page->children() as $child) {
                $collection = $collection->add($child->files()->filterBy('type', 'image'));
                $collection = $this->addDescendantImages($collection, $child);
            }

            return $collection;
        },

        'makeUniqueCollection' => function($allImages, $sort = 'filename', $direction = 'asc') {
            // Track seen filenames and build unique collection
            $uniqueFiles = new Files([]);
            $seenNames = [];

            foreach($allImages as $image) {
                $name = $image->name();
                if(!in_array($name, $seenNames)) {
                    $seenNames[] = $name;
                    $uniqueFiles = $uniqueFiles->add($image);
                }
            }

            // Sort the collection
            return $uniqueFiles->sort($sort, $direction);
        }
    ],

    'siteMethods' => [
        'siteImages' => function($sort = 'filename', $direction = 'asc') {
            // Get all site images
            $allImages = new Files([]);

            // Start from the site's root pages
            foreach($this->pages() as $rootPage) {
                // Add images from this page
                $allImages = $allImages->add($rootPage->files()->filterBy('type', 'image'));

                // Add all descendant images recursively
                if($rootPage->hasChildren()) {
                    $allImages = $rootPage->addDescendantImages($allImages, $rootPage);
                }
            }

            // Make the collection unique
            return $rootPage->makeUniqueCollection($allImages, $sort, $direction);
        }
    ],

    'collections' => [
        'page-images' => function($kirby) {
            $page = page();
            if(!$page) return new Files([]);

            return $page->pageImages();
        },

        'page-images-recursive' => function($kirby) {
            $page = page();
            if(!$page) return new Files([]);

            return $page->pageImages('filename', 'asc', true);
        },

        'site-images' => function($kirby) {
            return $kirby->site()->siteImages();
        }
    ]
]);