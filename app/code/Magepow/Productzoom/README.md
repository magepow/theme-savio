## Magento 2 Product image zoom extension 

Are you selling products on an online Magento store? 
**Magento 2 Product image zoom extension** is for you!
When shopping in an online store, the problem that hinders the customer's shopping process is the inability to view product details directly. Understanding this, the Magepow team developed the Product image zoom extension.

This extension allows customers to enlarge product images to see details that they cannot see at normal resolution. 3 zoom types you can use are Window zoom, Inner zoom, and Lens zoom.

- Window zoom: Positioning the window can be done by setting a default position and then using x and y offset to adjust. You can also position the window into a container. 
The administrator can customize the size of the Zoom window (width, height) and zoom window offset (x-axis, y-axis) in the admin panel.

- Inner Zoom: the zoom can be placed inside of the image

- Lens zoom: You can use the lens zoom setting to "Magnify the image". The image to the least has been constrained so it tucks underneath the image.

[![Latest Stable Version](https://poser.pugx.org/magepow/productzoom/v/stable)](https://packagist.org/packages/magepow/productzoom)
[![Total Downloads](https://poser.pugx.org/magepow/productzoom/downloads)](https://packagist.org/packages/magepow/productzoom)
[![Daily Downloads](https://poser.pugx.org/magepow/productzoom/d/daily)](https://packagist.org/packages/magepow/productzoom)

### Magepow_Productzoom Features
- Use 3 Zoom type to suit your product

- Product image zoom hover: hover over your product image, scroll forward to enlarge product details, scroll back down to reduce zoom. 

- Smooth zooming with Easing effect and FadeIn & FadeOut Speed.

- Choose Cursor: Options are default, cursor, crosshair

- Support responsive

- Image Cross Fade

- Customize Boder

- Customize Tints

## 1. How to install Magento 2 Product Zoom

### a. Install via composer (recommend)

Run the following commands in Magento 2 root folder:

```
composer require magepow/productzoom
php bin/magento setup:upgrade
php bin/magento setup:static-content:deploy -f
```

### b. Install manual


* extract files from an archive

* deploy files into Magento2 folder `app/code/Magepow/Productzoom`
Run the following command in Magento 2 root folder:

```
php bin/magento setup:upgrade
php bin/magento setup:static-content:deploy -f
php bin/magento cache:flush
```

## 2. Magepow Product Zoom user guide
### General Configuration
#### Enable Magepow Product Zoom
Go to `Admin Panel > Stores > Settings > Configuration > Magepow > Product Zoom`
![enable-module-img](https://github.com/magepow/magento2-productzoom/blob/master/media/enable.PNG)

Select `Yes` to enable module.
#### Setting Magepow Product Zoom
Go to `Admin Panel > Stores > Settings > Configuration > Magepow > Product Zoom`
![config-module-img](https://github.com/magepow/magento2-productzoom/blob/master/media/backend_config.PNG)
* Set to true to activate zoom on mouse scroll. Possible Values: "True", "False"
* Set Zoom Type : Outside, Inside, Lens.
* Set Zoom Window Position : Select one value from 1 to 18 like as image
* Set z-index window zoom
* Set with of window zoom in Zoom window width
* Set height of window zoom in Zoom window height
![config-module-img](https://github.com/magepow/magento2-productzoom/blob/master/media/backend_config2.PNG)
* Set zoom window offetx : x-axis offset of the zoom window
* Set zoom window offety : y-axis offset of the zoom window
* Set Zoom Window FadeIn : Set as a number e.g 200 for speed of Window fadeIn
* Set Lens Zoom : enable a tint overlay, other options: true
* Set Tint Colour : colour of the tint, can be #hex, word (red, blue), or rgb(x, x, x)
* Set Tint Opacity : opacity of the tint
![config-module-img](https://github.com/magepow/magento2-productzoom/blob/master/media/backend_config3.PNG)
* Set Cursor : The default cursor is usually the arrow, if using a lightbox, then set the cursor to pointer so it looks clickable - Options are default, cursor, crosshair
* Set Responsive : Set to true to activate responsivenes. If you have a theme which changes size, or tablets which change orientation this is needed to be on. Possible Values: "True", "False"
* Set Image Cross Fade : Set to true to activate simultaneous crossfade of images on gallery change. Possible Values: "True", "False"
* Set Border Size : Border Size of the ZoomBox - Must be set here as border taken into account for plugin calculations
* Set Border Colour : Select Border Colour
* Set Easing : Set to true to activate easing. Possible Values: "True", "False"
### This Is Result In Frontend
![config-module-img](https://github.com/magepow/magento2-productzoom/blob/master/media/frontend.gif)

## Donation

If this project help you reduce time to develop, you can give me a cup of coffee :) 

[![paypal](https://www.paypalobjects.com/en_US/i/btn/btn_donateCC_LG.gif)](https://www.paypal.com/paypalme/alopay)


**Free Extensions List**

* [Magento 2 Recent Sales Notification](https://magepow.com/magento-2-recent-sales-notification.html)

* [Magento 2 Categories Extension](https://magepow.com/magento-categories-extension.html)

* [Magento 2 Sticky Cart](https://magepow.com/magento-sticky-cart.html)

**Premium Extensions List**

* [Magento 2 Pages Speed Optimizer](https://magepow.com/magento-speed-optimizer.html)

* [Magento 2 Mutil Translate](https://magepow.com/magento-multi-translate.html)

* [Magento 2 Instagram Integration](https://magepow.com/magento-2-instagram.html)

* [Magento 2 Lookbook Pin Products](https://magepow.com/lookbook-pin-products.html)

**Featured Magento Themes**

* [Expert Multipurpose responsive Magento 2 Theme](https://1.envato.market/c/1314680/275988/4415?u=https://themeforest.net/item/expert-premium-responsive-magento-2-and-1-support-rtl-magento-2-/21667789)

* [Gecko Premium responsive Magento 2 Theme](https://1.envato.market/c/1314680/275988/4415?u=https://themeforest.net/item/gecko-responsive-magento-2-theme-rtl-supported/24677410)

* [Milano Fashion responsive Magento 2 Theme](https://1.envato.market/c/1314680/275988/4415?u=https://themeforest.net/item/milano-fashion-responsive-magento-1-2-theme/12141971)

* [Electro responsive Magento 2 Theme](https://1.envato.market/c/1314680/275988/4415?u=https://themeforest.net/item/electro-responsive-magento-1-2-theme/17042067)

* [Pizzaro Food responsive Magento 2 Theme](https://1.envato.market/c/1314680/275988/4415?u=https://themeforest.net/item/pizzaro-food-responsive-magento-1-2-theme/19438157)

* [Biolife Organic responsive Magento 2 Theme](https://1.envato.market/c/1314680/275988/4415?u=https://themeforest.net/item/biolife-organic-food-magento-2-theme-rtl-supported/25712510)

* [Market responsive Magento 2 Theme](https://1.envato.market/c/1314680/275988/4415?u=https://themeforest.net/item/market-responsive-magento-2-theme/22997928)

* [Kuteshop responsive Magento 2 Theme](https://1.envato.market/c/1314680/275988/4415?u=https://themeforest.net/item/kuteshop-multipurpose-responsive-magento-1-2-theme/12985435)

**Featured Magento Services**

* [PSD to Magento 2 Theme Conversion](https://magepow.com/psd-to-magento-theme-conversion.html)

* [Magento Speed Optimization Service](https://magepow.com/magento-speed-optimization-service.html)

* [Magento Security Patch Installation](https://magepow.com/magento-security-patch-installation.html)

* [Magento Website Maintenance Service](https://magepow.com/website-maintenance-service.html)

* [Magento Professional Installation Service](https://magepow.com/professional-installation-service.html)

* [Magento Upgrade Service](https://magepow.com/magento-upgrade-service.html)

* [Customization Service](https://magepow.com/customization-service.html)

* [Hire Magento Developer](https://magepow.com/hire-magento-developer.html)

