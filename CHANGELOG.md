# Changelog

All notable changes to `flatblog-engine` will be documented in this file

## 0.0.1 - 2019.05.23

Made first prerelease
Added .webmanifest file type, to file types that will not be deleted when building a site

## 0.0.2 - 2020.03.27

Implemented new variables for posts:

* alt
* feature_post
* seo_title
* seo_description
* seo_keywords
* fb_url
* header_image

## 0.0.3 - 2020.03.28

Implemented better slug generation for categories, to support danish characters and spaces

## 0.0.4 - 2020.03.28

Fixed issue with transformation of the danish letters æ, ø, å

## 0.0.5 - 2020.03.28

Fixed issue where post images would not get copied to the right destination

## 0.0.6 - 2020.03.28

Switched slug package to be able to support Netlify build process, since these does not have the intl extension

## 0.0.7 - 2020-04-04

Implemented thumbnails variable in settings file

Implemented ability to give an array of related posts in a posts settings file and have these post available at build-time

## 0.1.0 - 2021.01.14

Added .webp image filetype

## 0.1.1 - 2021.01.14

Fixed issue with `SettingsReferenceHandler`, where a `NULL` would trigger an exception

