<?php
require('../includes/app_config.php');
?>
<div id="container">
    <div id="sidebar" class="sidebar-fixed" style="width: 299px;">
        <div id="sidebar-content">

            <!-- Search Input -->
            <!--<form class="sidebar-search">
                <div class="input-box">
                    <button type="submit" class="submit">
                        <i class="icon-search"></i>
                    </button>
                    <span>
                        <input type="text" placeholder="Search...">
                    </span>
                </div>
            </form> -->

            <!-- Search Results -->
            <div class="sidebar-search-results">

                <i class="icon-remove close"></i>
                <!-- Documents -->
                <div class="title">
                    Documents
                </div>
                <ul class="notifications">
                    <li>
                        <a href="javascript:void(0);">
                            <div class="col-left">
                                <span class="label label-info"><i class="icon-file-text"></i></span>
                            </div>
                            <div class="col-right with-margin">
                                <span class="message"><strong>John Doe</strong> received $1.527,32</span>
                                <span class="time">finances.xls</span>
                            </div>
                        </a>
                    </li>
                    <li>
                        <a href="javascript:void(0);">
                            <div class="col-left">
                                <span class="label label-success"><i class="icon-file-text"></i></span>
                            </div>
                            <div class="col-right with-margin">
                                <span class="message">My name is <strong>John Doe</strong> ...</span>
                                <span class="time">briefing.docx</span>
                            </div>
                        </a>
                    </li>
                </ul>
                <!-- /Documents -->
                <!-- Persons -->
                <div class="title">
                    Persons
                </div>
                <ul class="notifications">
                    <li>
                        <a href="javascript:void(0);">
                            <div class="col-left">
                                <span class="label label-danger"><i class="icon-female"></i></span>
                            </div>
                            <div class="col-right with-margin">
                                <span class="message">Jane <strong>Doe</strong></span>
                                <span class="time">21 years old</span>
                            </div>
                        </a>
                    </li>
                </ul>
            </div> <!-- /.sidebar-search-results -->

            <!--=== Navigation ===-->
            <?php $page = basename($_SERVER['PHP_SELF']); ?>
            <ul id="nav">
              <li <?php if($page=="unit.php" || $page=="unitlist.php" || $page=="terms.php" || $page=="termslist.php" || $page=="purity.php" || $page=="puritylist.php" || $page=="pallet.php" || $page=="palletlist.php" || $page=="grade.php" || $page=="gradelist.php" || $page=="deliverytype.php" || $page=="deliverytypelist.php" || $page=="package.php" || $page=="packagelist.php" || $page=="cities.php" || $page=="citieslist.php" || $page=="states.php" || $page=="stateslist.php" || $page=="country.php" || $page=="countrylist.php" || $page=="ratecategory.php" || $page=="ratecategorylist.php" || $page=="businesstype.php" || $page=="businesstypelist.php" || $page=="currency.php" || $page=="currencylist.php") echo "class=\"current\""; ?> >
                    <a href="#">
                       <i class="icon-angle-right"></i>
                     Master Management
                     </a>
                     <ul class="sub-menu">
                <li <?php if($page == "activitytype.php" || $page == "activitytypelist.php")  echo "class=\"current\""; ?> >
                    <a href="activitytypelist.php">
                        <i class="icon-angle-right"></i>
                        
                        Activity Type
                    </a>
                                    </li>
                <li <?php if($page == "tags.php" || $page == "tagslist.php") echo "class=\"current\""; ?> >
                    <a href="tagslist.php">
                        <i class="icon-angle-right"></i>
                        Tag Type
                    </a>
                </li>
                      </ul>
                </li>
                <li <?php if($page=='events.php'|| $page == "eventslist.php") echo "class=\"current\""; ?> >
                    <a href="eventslist.php">
                        <i class="icon-angle-right"></i>
                        Event Management
                    </a>
                    
                </li>
                     
                <li <?php if($page == "members.php" || $page == "memberslist.php") echo "class=\"current\""; ?> >
                    <a href="memberslist.php">
                        <i class="icon-angle-right"></i>
                       user Management
                    </a>
                </li>
                <li <?php if($page == "notimessages.php" || $page == "notimessageslist.php" || $page == "notification.php" || $page == "notificationlist.php") echo "class=\"current\""; ?> >
                    <a href="notimessageslist.php">
                        <i class="icon-angle-right"></i>
                         Notification Management 
                    </a>
                                    </li>
                  <li <?php if($page == "contacts.php" || $page == "contactslist.php") echo "class=\"current\""; ?> >
                    <a href="contactslist.php">
                        <i class="icon-angle-right"></i>
                        Contacts Management
                    </a>
                </li>

                <li <?php if($page == "cms.php" || $page == "cmslist.php") echo "class=\"current\""; ?> >
                    <a href="cmslist.php">
                        <i class="icon-angle-right"></i>
                        CMS Management
                    </a>
                </li>
                <li <?php if($page == "change_password.php" || $page == "change_password.php") echo "class=\"current\""; ?> >
                    <a href="change_password.php">
                        <i class="icon-angle-right"></i>
                        Settings
                    </a>
                </li>
                
            </ul>

            <!-- /Navigation -->
            <!--			<div class="sidebar-title">
                                <span>Notifications</span>
                            </div>-->
            <!--				<ul class="notifications demo-slide-in">  .demo-slide-in is just for demonstration purposes. You can remove this. 
                                <li style="display: none;">  style-attr is here only for fading in this notification after a specific time. Remove this. 
                                    <div class="col-left">
                                        <span class="label label-danger"><i class="icon-warning-sign"></i></span>
                                    </div>
                                    <div class="col-right with-margin">
                                        <span class="message">Server <strong>#512</strong> crashed.</span>
                                        <span class="time">few seconds ago</span>
                                    </div>
                                </li>
                                <li style="display: none;">  style-attr is here only for fading in this notification after a specific time. Remove this. 
                                    <div class="col-left">
                                        <span class="label label-info"><i class="icon-envelope"></i></span>
                                    </div>
                                    <div class="col-right with-margin">
                                        <span class="message"><strong>John</strong> sent you a message</span>
                                        <span class="time">few second ago</span>
                                    </div>
                                </li>
                                <li>
                                    <div class="col-left">
                                        <span class="label label-success"><i class="icon-plus"></i></span>
                                    </div>
                                    <div class="col-right with-margin">
                                        <span class="message"><strong>Emma</strong>"s account was created</span>
                                        <span class="time">4 hours ago</span>
                                    </div>
                                </li>
                            </ul>-->

            <!--				<div class="sidebar-widget align-center">
                                <div class="btn-group" data-toggle="buttons" id="theme-switcher">
                                    <label class="btn active">
                                        <input type="radio" name="theme-switcher" data-theme="bright"><i class="icon-sun"></i> Bright
                                    </label>
                                    <label class="btn">
                                        <input type="radio" name="theme-switcher" data-theme="dark"><i class="icon-moon"></i> Dark
                                    </label>
                                </div>
                            </div>-->

        </div>
        <div id="divider" class="resizeable"></div>
    </div>
    <!-- /Sidebar -->


