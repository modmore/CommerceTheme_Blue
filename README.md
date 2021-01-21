Starter Pack "Blue" for Commerce
----

Welcome to the second official Starter Pack (or theme) for [Commerce](https://modmore.com/commerce/). This starter pack uses bespoke styling (no bootstrap/foundation or other framework). 

For a starter pack based on Bootstrap, see [Red](https://github.com/modmore/CommerceTheme_Red).

The design is best summarised as modernly minimalistic, with blue accents. It's meant for you to make it your own and fit any type of branding without heaving to tear out all sorts of details that don't fit, however, we've given this design a little more work to make it suitable to launch as-is compared to the first starter pack Red. 

The starter pack includes a variety of elements and assets to deliver the following functionality:

- Homepage with a hero image, featured product listing, category listing, simple header
- Simple categories overview with:
    - Links to your other categories
    - Simple filtering powered by Tagger (single group)
    - Photos, price ranges, and per product, and short description per product
    - Pagination support (pdoPage)
- Simple product page with:
    - Dependent select boxes for the product variety selection based on the [Product Matrix](https://docs.modmore.com/en/Commerce/v1/Product_Catalog/Product_Matrix.html), with dynamic rendering of available stock and variety price
    - Support for the product list TV as well for simpler shops 
    - AJAX-enhanced add to cart button
    - CSS-only tabs for additional content, filled by TVs on a per-resource basis, to hold anything from reviews or ingredients to more specifications.
    - Automatic list of related products based on the resource tags using Tagger
    - Automatic list of related products based on resource information using getRelated. 
- Mini-cart in the header, showing individual items, shipping costs, and total. 
- Wide cart design including product images (if available) and simple instant (AJAX) changing of quantity and deleting items, including support for standard coupons, and a much more simplified totals section than the default Commerce theme. 
- One-page(-ish) checkout through JavaScript enhancements. Including support for all standard Commerce checkout functionality (repeat visit address selection, on-site and redirect payment gateways, etc) with a more opiniated design than "Red" or the standard theme in Commerce.
- Signup page for account registration, including email activation with the Login package
- Basic "My Account" section offering:
    - Login
    - Commerce order history
    - Basic profile editing

The starter pack uses as little JavaScript as possible, loading only a single vanillajs file to offer the AJAX enhancements for the dependent selects (product varieties), cart and checkout. 

The compiled CSS <...>

The markup has been kept as simple as possible as well, using BEM-ish classes.  

## Customising

The starter pack is distributed in such a way that you can make changes that are preserved when upgrading to a newer version of the theme. To learn more about how to do that, and what considerations to keep in mind, please find the readme in assets/components/commercetheme_blue/. 

## Preview

[A quick preview of the theme can be found here.](http://theme-blue.modmore.modxcloud.com/) Please be aware that site is more of a playground than a proper demo site, so there's been very little work down in terms of proper content. 

## Credits

Using a [gulp workflow for assets](https://gulpjs.com/), including autoprefixer, cssnano, gulp-postcss, gulp-sass and gulp-sourcemaps to compile and optimise the CSS.
