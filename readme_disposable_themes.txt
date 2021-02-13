PHPVMS v7 Themes : Disposable & Disposable SideBar v1.01

Both themes use Bootstrap v4.6, Fontawesome v5+ solid icons and my personal widgets to enhance the look of your phpvms installation.
Templates are compatible with latest dev build as of date 13.FEB.21 and i will try to keep it updated as the development continues.

** Installation **

Same folder structure is used with phpvms v7, so if you have a default installation for it, then installing the themes will take only seconds.

Just extract the zip file at the root folder of your phpvms v7 installation, and select one of the templates from admin section.

If you want to manually upload the files or have a modified installtion of phpvms please follow the instructions below;

* Contents of app\widgets should be placed in your phpvms app\Widgets folder

* Contents of public\image should be placed in your phpvms public folder. 

If you do not have an image folder directly under your public folder then you may need to create it.

If you do not want to create a new folder or your folder structure is special, then you need to edit the css/stylesheet file and some blades to point
the correct folder according to your installation.

If you can not manage to handle the blade changes and/or pointing out the right folder, you will not be able to see a nice cloudy background image 
and loose the "no photo" (or ghost) image for users who do not have uploaded an avatar.

* Contents of public\assets\frontend\css should be placed in your phpvms public\assets\frontend\css folder.

This is where the default stylesheet files are already placed, so you should be able to find it without any problems.
But if you miss or skip this step, templates will really look terrible and you would spot the problem in seconds.

* Contents of public\assets\frontend\js should be placed in your phpvms public\assets\frontend\js folder.

* Contents of resources\views\layouts folder should be placed under your phpvms resources\views\layouts folder.

** Re-Styling the Theme **

I tried to follow a standard on all blade files, for example all page headings use the same class (card-title) and they are all in <h3></h3> tags.
All card headers are in <h5></h5> tags, no manual style="..." codes used in the blades except when it is really necessary etc.

If you want to change all page title's color you can add below line to the css file instead of editing dozens of blade files one by one.

.card-title { color: pink; } or .card-title { font-size: 150%; color: #FFFFFF; }

Also i tried to make the css files simple for user to edit, just the top part of the css would be enough for basic style changes. Of course you can do
whatever you want in the blade files or in the css but if you are not experienced in things I kindly suggest to always have backups ;)

There are two style/css files in this pack, one for the classic top navbar edition and the second one is for the sidebar edition.
Technically they are same except the background image but provided for you to be able to test/have different styles in each theme, 
simply if you want only one style and use it in both themes then change the style line in the top part of app.blade.php and use the same css for each theme.

** IVAO and VATSIM profile links  **

If you want the links to be functional please add two profile fields (check admin->users->profile fields) as below;

name : IVAO (this field is checked for the correct url)
description : Ivao ID or IVAO ID (this field is displayed in the blade with the value, can be anything you wish)

name : VATSIM (this field is checked for the correct url)
description : Vatsim ID or VATSIM ID (this field is displayed in the blade with the value, can be anything you wish)

Do not use any spaces in the name field while creating the profile fields, that will generate an error both during display and during user registration.
If you already defined your profile fields, you can change their names from the admin panel or just change the profile\index.blade to reflect your settings.

** vmsAcars Users and Extended Stats **

User profile will give you simple stats for pilots but if you are using vmsAcars as your flight tracking software, template will dedect it and enable
more stats at profile page. Some directly visible some are packed in a collapsable row.

** Flights Page/View **

All the details of your flights are by default collapsed, just click the show/hide details icon to see full details of flights (like route, subfleets, notes)

** Footer Positioning and Content **

If you want to have a larger footer (like 2-3 lines or images/links etc), then you need to edit the css file to give it more space 
'cause to keep it really where it belongs (at the bottom of the page) I had to use some definitions for it.

To do this, head to the css file and go to end directly, you will find the definitions and simple explanations for footer there. 

** Differences Between Templates **

Except the sidebar on the left there are no differences between them.

** Differences From Previous Versions **

The folder used for Widgets was corrected
Widget controllers and blades got a some updates
PersonalStats Widget now able to show stats for current last and previous month and current and last year (like the TopPilotsByPeriod)
User Table fixed
SimBrief Form Route Error fixed
SimBrief Briefing VATSIM Prefile fixed

Please check the PhpVms Forum post for the usage and options of my widgets, you do not need to download the widgets pack 'cause this pack contains all of them.

** Special Thanks **

To Mr.Doug and Mr.Maco for their support and suggestions during the development of my themes.

** Tests **

This theme is tested with Chrome,Firefox,Safari and Ms Edge on desktop computers. Also some tests done with mobile phones and tablets.
So far all was good thus I dediced to share it.

If you encounter problems in a specific device just let me know, maybe i can fix it.

Safe flights and enjoy.
B.Fatih KOZ
'Disposable Hero'
https://github.com/FatihKoz
13.FEB.21