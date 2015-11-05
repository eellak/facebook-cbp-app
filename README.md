# Facebook App for Epinoo.gr
####Contributor: George Metaxas
####Tested on: WP 4.3

## Description 

The app consists of the epinoo-fb-map plugin that has two widgets:
- the epinoo_fp_map_widget displays a map of the registered users locations, which are in the vicinity of the current user
- the epinoo_fb_header_widget which loads the appropriate code, which registers the page where it is placed with the corresponding facebook applciation

There are also the following templates: 
- template.fb-app.php: a generic template to include the necessary facebook app code
- template.map.php: shows a page with the epinoo-fb-map plugin embedded

##README Contents
* [Installation](#installation)

<a name="installation"></a>
## Installation

0. Copy the two templates to the theme's root folder
1. Login to the wordpress administration page
2. Install the plugins: CE WP-Menu per Page and Super RSS Reader 
3. Create three pages, one parent page and two subpages 
4. For the parent page, set the template to Google Maps, and for the two 
   child pages set the template to Facebook App page.
5. Set the following additional params to false for all pages
  * Show category
  * Show tags
  * Show  comments
6. Install the epinoo-fb-map plugin, by copying the epinoo-fb-map folder to the
   wordpress plugins folder
7. Go to the menus pages and define a new menu. Place any pages you wish 
   to be under this menu.
8. Go back to each page, find the section **Select the menu for this page**
   and select the newly created menu
9. Go to Settings Epinoo Facebook App plugin, and give the Facebook App ID, Facebook App Secret 
   of the corresponding Facebook application you wish to connect to Facebook. Also
   set the maximum distance from which the other users of the site will be displayed 
   on the map.
