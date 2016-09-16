### About ###

Lookbook is a mosaic styled photography portfolio theme for WordPress. Lookbook works on all modern browsers and even way back to IE9 and above.

### Installation ###

1. After downloading the zip file, go to Appearance &gt; Themes and click on the Install Themes tab
2. Click on the Upload link
3. Upload the zip file that you downloaded from your members dashboard and click Install Now
4. Click Activate to use the theme you just installed.

### Settings ###

The Media Settings are set to you automatically in functions.php.

    Thumbnail size: 640 x 0
    Medium size: 640 x 0
    Large size: 1280 x 0

If you wish to change it, please edit the following code in functions.php.

    update_option( 'thumbnail_size_w', 640 );
    update_option( 'thumbnail_size_h', '' );
    update_option( 'thumbnail_crop', false );
    update_option( 'medium_size_w', 640, true );
    update_option( 'medium_size_h', '', true );
    update_option( 'large_size_w', 1280, true );
    update_option( 'large_size_h', '', true );


### Setting Up Lookbook To Resemble The Demo ###

It's simple:

1. Create posts - Add posts and assign a featured images to every post.
2. Create a menu - Visit Appearance -> Menu and create your primary menu (details below if you've never done this before).
3. Set theme options - Visit Appearance -> Theme Options and customize your theme.
4. Enable Lookbook - Visit Appearance -> Theme Options -> Lookbook and enable the Lookbook. This will automatically publish out the Lookbook page and add the Lookbook menu icon located at top right.
5. Optionally install Sell Media plugin - The Lookbook demo is integrated with our free Sell Media plugin. This plugin allows you to sell images directly from your WordPress site.


### The Homepage ###

Lookbook works like any normal WordPress theme. You simply create posts and assign a featured image for each post. Thus, each image on the homepage is actually a post with a featured image assigned to it.

### The Lookbook ###

Lookbook allows your viewers to select their favorite photographs and download them as a lookbook pdf. This gives editors and potential clients and easy, centralized way to save their favorite photos seen on your website. Additionally, Lookbook allows you to optionally require that users provide an email address in order to download the PDF. This is a great way to help grow your email subscriber base.

The Lookbook feature can be enabled through the Theme Options &gt; Lookbook tab. This will also automatically create a Lookbook page for you. This page is a container where the images that users added gets collected. Once the Lookbook page is created you will see a Save link (with a folder icon) on top of the images (when you hover over the image). Click that icon to save the image to the Lookbook page and download the Lookbook pdf.

The folder icon on the top right shown on the demo is the menu for Lookbook page. It shows up automatically if you enable the Lookbook feature on Appearance -> Theme Options -> Lookbook. When your visitors click the Save icon on images to create their own Lookbook, the Lookbook menu count will increase. Clicking the Lookbook menu will take them to the Lookbook page where they can download their custom PDF.

And finally, each Lookbook that a user creates is saved in WordPress, which you can view on the Lookbook tab in wp-admin. This is useful when you want to track who creates Lookbooks and which images they saved.


### Sell Media integration (optional) ###

If you have activated Sell Media plugin (http://graphpaperpress.com/plugins/sell-media/), you will also see a cart menu on the top right. The Buy (cart icon) button only appears on the images that you upload via Sell Media menu in your wp-admin. This icon will lead to Sell Media Checkout page. Lookbook and Sell Media works independently and separately. Its obvious that you won't want to allow users to download the images that you want to sell. You can read more about Sell Media here (http://graphpaperpress.com/support/instructions/?plugin=sell-media).

### Creating Galleries ###

Insert a gallery of images (using the [gallery] shortcode) onto a post to show a grid of images, similar to the design seen on the homepage.

### Theme Customizer ###

The Theme Customizer can help you set your title and tagline, set a background image, assign your menus, and choose a static home page. You can preview your changes by clicking the Customize link below your active theme or your Appearance &gt; Themes page.

*Please note: Setting a static home page will remove the default home page elements built into the theme.


### Header Images ###

This theme supports header images. You can upload any favorite image and upload it under Appearance &gt; Header menu. The image will appear all over the site on the top of the mosaic layout.


### Widgets ###

Lookbook supports widgetized areas in the footer. To add widgets, simply drag widgets inside Footer Widget areas from Appearance &gt; Widgets page.


### Menus ###

Lookbook uses WordPress’s custom menus option. These can be created in Appearance > Menus. To add a new menu to your site:

1. Go to Appearance > Menus.
2. Create a new menu item by clicking the + tab.
3. Select the pages you want to display in your menu and click the Add to Menu button. If you do not see the type of page (category, tag, portfolio, gallery, etc) you want to display, click the Screen Options link at the top of the page and make sure the page type is checked.
4. Once you have set your menu as you want it, click the Save Menu button.
5. Then, assign that menu to your desired theme location to ensure your menu appears where you want it and click Save.


### Featured Images ###

This theme relies heavily on Featured Images. The Featured Image will be featured on the Home and Archive pages.


### Social Icons ###

To add social icons on the menu, you can add CSS classes in your menu items. You can go to Appearance &gt; Menus and if you open up each Menu Item, you should see a field called CSS Classes. If you do not see that, please click Screen Options on the top right and tick CSS Classes checkbox. Now, you should see the option. In that option, you can add following CSS Classes (without the quote):

Twitter     : "genericon genericon-twitter"
Facebook    : "genericon genericon-facebook"
Facebook    : "genericon genericon-facebook-alt"
WordPress   : "genericon genericon-wordpress"
Google Plus : "genericon genericon-googleplus"
Linkedin    : "genericon genericon-linkedin"
Linkedin    : "genericon genericon-linkedin-alt"
Pinterest   : "genericon genericon-pinterest"
Pinterest   : "genericon genericon-pinterest-alt"
Flickr      : "genericon genericon-flickr"
Vimeo       : "genericon genericon-vimeo"
YouTube     : "genericon genericon-youtube"
Instagram   : "genericon genericon-instagram"


### Sample Content ###

A sample content xml file is also included within the theme. Please check the lookbook theme folder for sample-lookbook-content.xml file. If you want to quickly check how the theme looks in your test site, you can import this file from Tools -> Import menu. If you do not see WordPress in the Import page, you might have to setup the WordPress Importer plugin (https://wordpress.org/plugins/wordpress-importer/). Once the data is imported, you should see your test site look similar to Lookbook Demo site (http://demo.graphpaperpress.com/lookbook/).


### Copyright, License & Other info ###

Lookbook theme, Copyright (C) 2013 Graph Paper Press.
Lookbook theme along with images and scripts are all licensed under 100% GPL. license.txt file has further details.
Lookbook theme uses Genericons for icons.
    Genericons - http://genericons.com/
    License: Distributed under the terms of the GPL
    Copyright: Genericons, genericons.com

Lookbook is based on the _s (Underscores) starter theme by Automattic Inc. (http://underscores.me/)


### Support ###

Please contact http://graphpaperpress.com/support if you have any questions about the theme.

Enjoy!
