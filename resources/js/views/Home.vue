<template>
    <!-- main -->
    <section id="main" class="clearfix home-default">
        <div class="container">
            <!-- banner -->
            <div class="banner-section text-center">
                <h1 class="title">Buy & Sell Cars, Properties, Electronics, Services, Hiring - Tanzania’s largest classified ads platform</h1>
                <h3>Search from over thousands of adverts & post for free!</h3>
                <!-- banner-form -->
                <div class="banner-form">
                    <form action="#">
                        <!-- category-change -->
                        <div class="dropdown category-dropdown">
                            <router-link data-toggle="dropdown" to="#"><span class="change-text"  v-if="!category">Select Category</span>
                                <span class="change-text"  v-else>{{category}}</span>
                                <i class="fa fa-angle-down"></i></router-link>
                            <ul class="dropdown-menu category-change">
                                <li><router-link to="#" >Select Category</router-link></li>
                                <li @click="category=vendor_type.name" v-for="(vendor_type,index) in allVendorTypes">
                                    <router-link to="#" >{{vendor_type.name}}</router-link>
                                </li>
                            </ul>
                        </div><!-- category-change -->

                        <input type="text" class="form-control" placeholder="What are you looking for?">
                        <button type="submit" class="form-control" value="Search">Search</button>
                    </form>
                </div><!-- banner-form -->

                <!-- banner-socail -->
                <ul class="banner-socail list-inline">
                    <li>
                        <router-link to="#" title="Facebook"><i class="fa fa-facebook"></i></router-link>
                    </li>
                    <li>
                        <router-link to="#" title="Twitter"><i class="fa fa-twitter"></i></router-link>
                    </li>
                    <li>
                        <router-link to="#" title="Google Plus"><i class="fa fa-google-plus"></i></router-link>
                    </li>
                    <li>
                        <router-link to="#" title="Youtube"><i class="fa fa-youtube"></i></router-link>
                    </li>
                </ul><!-- banner-socail -->
            </div><!-- banner -->

            <!-- main-content -->
            <div class="main-content">
                <!-- row -->
                <div class="row justify-content-md-center">
                    <!-- product-list -->
                    <div class="col-md-8">
                        <!-- categorys -->
                        <div class="section category-ad text-center">
                            <ul class="category-list">
                                <div class="row">
                                    <li class="category-item col-md-3" v-for="(vendor_type,index) in allVendorTypes">
                                        <div class="w-50 mx-auto">
                                            <router-link to="categories.html">
                                                <div class="category-icon"><img :src="vendor_type.image" alt="images" class="img-fluid"></div>
                                                <span class="category-title">{{vendor_type.name}}</span>
                                            </router-link>
                                        </div>
                                    </li><!-- category-item -->
                                </div>

                            </ul>
                        </div><!-- category-ad -->

                        <!-- featureds -->
                        <div class="section featureds">
                            <div class="row">
                                <div class="col-sm-12">
                                    <div class="section-title featured-top">
                                        <h4>Featured Ads</h4>
                                    </div>
                                </div>
                            </div>

                            <!-- featured-slider -->
                            <div class="featured-slider">
                                <div id="featured-slider" class="row">
                                    <!-- featured -->
                                    <div class="col-md-4">
                                        <div class="featured">
                                            <div class="featured-image">
                                                <a href="details.html"><img src="images/featured/1.jpg" alt=""
                                                                            class="img-fluid"></a>
                                                <a href="#" class="verified" data-toggle="tooltip" data-placement="left"
                                                   title="Verified"><i class="fa fa-check-square-o"></i></a>
                                            </div>

                                            <!-- ad-info -->
                                            <div class="ad-info">
                                                <h3 class="item-price">$800.00</h3>
                                                <h4 class="item-title"><a href="#">Apple MacBook Pro with Retina
                                                    Display</a></h4>
                                                <div class="item-cat">
                                                    <span><a href="#">Electronics & Gedgets</a></span>
                                                </div>
                                            </div><!-- ad-info -->

                                            <!-- ad-meta -->
                                            <div class="ad-meta">
                                                <div class="meta-content">
                                                    <span class="dated"><a href="#">7 Jan 10:10 pm </a></span>
                                                </div>
                                                <!-- item-info-right -->
                                                <div class="user-option pull-right">
                                                    <a href="#" data-toggle="tooltip" data-placement="top"
                                                       title="Los Angeles, USA"><i class="fa fa-map-marker"></i> </a>
                                                    <a href="#" data-toggle="tooltip" data-placement="top"
                                                       title="Dealer"><i class="fa fa-suitcase"></i> </a>
                                                </div><!-- item-info-right -->
                                            </div><!-- ad-meta -->
                                        </div><!-- featured -->
                                    </div>

                                    <div class="col-md-4">
                                        <div class="featured">
                                            <div class="featured-image">
                                                <a href="details.html"><img src="images/featured/2.jpg" alt=""
                                                                            class="img-fluid"></a>
                                            </div>

                                            <!-- ad-info -->
                                            <div class="ad-info">
                                                <h3 class="item-price">$25000.00</h3>
                                                <h4 class="item-title"><a href="#">2018 Bugatti Veyron Sport
                                                    Middlecar</a></h4>
                                                <div class="item-cat">
                                                    <span><a href="#">Cars & Vehicles</a></span>
                                                </div>
                                            </div><!-- ad-info -->

                                            <!-- ad-meta -->
                                            <div class="ad-meta">
                                                <div class="meta-content">
                                                    <span class="dated"><a href="#">7 Jan 10:10 pm </a></span>
                                                </div>
                                                <!-- item-info-right -->
                                                <div class="user-option pull-right">
                                                    <a href="#" data-toggle="tooltip" data-placement="top"
                                                       title="Los Angeles, USA"><i class="fa fa-map-marker"></i> </a>
                                                    <a href="#" data-toggle="tooltip" data-placement="top"
                                                       title="Individual"><i class="fa fa-user"></i> </a>
                                                </div><!-- item-info-right -->
                                            </div><!-- ad-meta -->
                                        </div><!-- featured -->

                                    </div>
                                    <div class="col-md-4">
                                        <div class="featured">
                                            <div class="featured-image">
                                                <a href="details.html"><img src="images/featured/3.jpg" alt=""
                                                                            class="img-fluid"></a>
                                                <a href="#" class="verified" data-toggle="tooltip" data-placement="left"
                                                   title="Verified"><i class="fa fa-check-square-o"></i></a>
                                            </div>

                                            <!-- ad-info -->
                                            <!-- ad-info -->
                                            <div class="ad-info">
                                                <h3 class="item-price">$250.00 <span
                                                    class="negotiable">(Negotiable)</span></h3>
                                                <h4 class="item-title"><a href="#">Vivster Acoustic Guitar</a></h4>
                                                <div class="item-cat">
                                                    <span><a href="#">Music & Art</a></span>
                                                </div>
                                            </div><!-- ad-info -->

                                            <!-- ad-meta -->
                                            <div class="ad-meta">
                                                <div class="meta-content">
                                                    <span class="dated"><a href="#">7 Jan 10:10 pm </a></span>
                                                </div>
                                                <!-- item-info-right -->
                                                <div class="user-option pull-right">
                                                    <a href="#" data-toggle="tooltip" data-placement="top"
                                                       title="Los Angeles, USA"><i class="fa fa-map-marker"></i> </a>
                                                    <a href="#" data-toggle="tooltip" data-placement="top"
                                                       title="Dealer"><i class="fa fa-suitcase"></i> </a>
                                                </div><!-- item-info-right -->
                                            </div><!-- ad-meta -->
                                        </div><!-- featured -->
                                    </div>
                                </div>
                            </div><!-- featured-slider -->
                        </div><!-- #featured-slider -->
                    </div><!-- featureds -->


                    <div class="row justify-content-md-center">

                        <div class="col-md-8 text-center">

                            <div class="section trending-ads">
                                <div class="section-title tab-manu">
                                    <h4>Listing Ads</h4>
                                    <!-- Nav tabs -->
                                </div>

                                <!-- Tab panes -->
                                <div class="tab-content">
                                    <!-- tab-pane -->
                                    <div role="tabpanel" class="tab-pane fade in active show" id="recent-ads">
                                        <!-- ad-item -->
                                        <div class="ad-item row">
                                            <!-- item-image -->
                                            <div class="item-image-box col-lg-4">
                                                <div class="item-image">
                                                    <a href="details.html"><img src="images/trending/1.jpg" alt="Image"
                                                                                class="img-fluid"></a>
                                                    <a href="#" class="verified" data-toggle="tooltip"
                                                       data-placement="left" title="Verified"><i
                                                        class="fa fa-check-square-o"></i></a>
                                                </div><!-- item-image -->
                                            </div>

                                            <!-- rending-text -->
                                            <div class="item-info col-lg-8">
                                                <!-- ad-info -->
                                                <div class="ad-info">
                                                    <h3 class="item-price">$50.00</h3>
                                                    <h4 class="item-title"><a href="#">Apple TV - Everything you need to
                                                        know!</a></h4>
                                                    <div class="item-cat">
                                                        <span><a href="#">Electronics & Gedgets</a></span> /
                                                        <span><a href="#">Tv & Video</a></span>
                                                    </div>
                                                </div><!-- ad-info -->

                                                <!-- ad-meta -->
                                                <div class="ad-meta">
                                                    <div class="meta-content">
                                                        <span class="dated"><a href="#">7 Jan, 16  10:10 pm </a></span>
                                                        <a href="#" class="tag"><i class="fa fa-tags"></i> Used</a>
                                                    </div>
                                                    <!-- item-info-right -->
                                                    <div class="user-option pull-right">
                                                        <a href="#" data-toggle="tooltip" data-placement="top"
                                                           title="Los Angeles, USA"><i class="fa fa-map-marker"></i>
                                                        </a>
                                                        <a class="online" href="#" data-toggle="tooltip"
                                                           data-placement="top" title="Dealer"><i
                                                            class="fa fa-suitcase"></i> </a>
                                                    </div><!-- item-info-right -->
                                                </div><!-- ad-meta -->
                                            </div><!-- item-info -->
                                        </div><!-- ad-item -->

                                        <!-- ad-item -->
                                        <div class="ad-item row">
                                            <div class="item-image-box col-lg-4">
                                                <!-- item-image -->
                                                <div class="item-image">
                                                    <a href="details.html"><img src="images/trending/2.jpg" alt="Image"
                                                                                class="img-fluid"></a>
                                                </div><!-- item-image -->
                                            </div><!-- item-image-box -->

                                            <!-- rending-text -->
                                            <div class="item-info col-lg-8">
                                                <!-- ad-info -->
                                                <div class="ad-info">
                                                    <h3 class="item-price">$250.00 <span>(Negotiable)</span></h3>
                                                    <h4 class="item-title"><a href="#">Bark Furniture, Handmade Bespoke
                                                        Furniture</a></h4>
                                                    <div class="item-cat">
                                                        <span><a href="#">Home Appliances</a></span> /
                                                        <span><a href="#">Sofa</a></span>
                                                    </div>
                                                </div><!-- ad-info -->

                                                <!-- ad-meta -->
                                                <div class="ad-meta">
                                                    <div class="meta-content">
                                                        <span class="dated"><a href="#">7 Jan, 16  10:10 pm </a></span>
                                                        <a href="#" class="tag"><i class="fa fa-tags"></i> Used</a>
                                                    </div>
                                                    <!-- item-info-right -->
                                                    <div class="user-option pull-right">
                                                        <a href="#" data-toggle="tooltip" data-placement="top"
                                                           title="Los Angeles, USA"><i class="fa fa-map-marker"></i>
                                                        </a>
                                                        <a class="online" href="#" data-toggle="tooltip"
                                                           data-placement="top" title="Dealer"><i
                                                            class="fa fa-suitcase"></i> </a>
                                                    </div><!-- item-info-right -->
                                                </div><!-- ad-meta -->
                                            </div><!-- item-info -->
                                        </div><!-- ad-item -->

                                        <!-- ad-item -->
                                        <div class="ad-item row">
                                            <div class="item-image-box col-lg-4">
                                                <!-- item-image -->
                                                <div class="item-image">
                                                    <a href="details.html"><img src="images/trending/4.jpg" alt="Image"
                                                                                class="img-fluid"></a>
                                                    <a href="#" class="verified" data-toggle="tooltip"
                                                       data-placement="left" title="Verified"><i
                                                        class="fa fa-check-square-o"></i></a>
                                                </div><!-- item-image -->
                                            </div><!-- item-image-box -->

                                            <!-- rending-text -->
                                            <div class="item-info col-lg-8">
                                                <!-- ad-info -->
                                                <div class="ad-info">
                                                    <h3 class="item-price">$800.00</h3>
                                                    <h4 class="item-title"><a href="#">Rick Morton- Magicius Chase</a>
                                                    </h4>
                                                    <div class="item-cat">
                                                        <span><a href="#">Books & Magazines</a></span> /
                                                        <span><a href="#">Story book</a></span>
                                                    </div>
                                                </div><!-- ad-info -->

                                                <!-- ad-meta -->
                                                <div class="ad-meta">
                                                    <div class="meta-content">
                                                        <span class="dated"><a href="#">7 Jan, 16  10:10 pm </a></span>
                                                        <a href="#" class="tag"><i class="fa fa-tags"></i> Used</a>
                                                    </div>
                                                    <!-- item-info-right -->
                                                    <div class="user-option pull-right">
                                                        <a href="#" data-toggle="tooltip" data-placement="top"
                                                           title="Los Angeles, USA"><i class="fa fa-map-marker"></i>
                                                        </a>
                                                        <a href="#" data-toggle="tooltip" data-placement="top"
                                                           title="Individual"><i class="fa fa-user"></i> </a>
                                                    </div><!-- item-info-right -->
                                                </div><!-- ad-meta -->
                                            </div><!-- item-info -->
                                        </div><!-- ad-item -->

                                    </div><!-- tab-pane -->

                                    <!-- tab-pane -->
                                    <div role="tabpanel" class="tab-pane fade" id="popular">

                                        <div class="ad-item row">
                                            <!-- item-image -->
                                            <div class="item-image-box col-lg-4">
                                                <div class="item-image">
                                                    <a href="details.html"><img src="images/trending/1.jpg" alt="Image"
                                                                                class="img-fluid"></a>
                                                    <a href="#" class="verified" data-toggle="tooltip"
                                                       data-placement="left" title="Verified"><i
                                                        class="fa fa-check-square-o"></i></a>
                                                </div><!-- item-image -->
                                            </div>

                                            <!-- rending-text -->
                                            <div class="item-info col-lg-8">
                                                <!-- ad-info -->
                                                <div class="ad-info">
                                                    <h3 class="item-price">$50.00</h3>
                                                    <h4 class="item-title"><a href="#">Apple TV - Everything you need to
                                                        know!</a></h4>
                                                    <div class="item-cat">
                                                        <span><a href="#">Electronics & Gedgets</a></span> /
                                                        <span><a href="#">Tv & Video</a></span>
                                                    </div>
                                                </div><!-- ad-info -->

                                                <!-- ad-meta -->
                                                <div class="ad-meta">
                                                    <div class="meta-content">
                                                        <span class="dated"><a href="#">7 Jan, 16  10:10 pm </a></span>
                                                        <a href="#" class="tag"><i class="fa fa-tags"></i> Used</a>
                                                    </div>
                                                    <!-- item-info-right -->
                                                    <div class="user-option pull-right">
                                                        <a href="#" data-toggle="tooltip" data-placement="top"
                                                           title="Los Angeles, USA"><i class="fa fa-map-marker"></i>
                                                        </a>
                                                        <a class="online" href="#" data-toggle="tooltip"
                                                           data-placement="top" title="Dealer"><i
                                                            class="fa fa-suitcase"></i> </a>
                                                    </div><!-- item-info-right -->
                                                </div><!-- ad-meta -->
                                            </div><!-- item-info -->
                                        </div><!-- ad-item -->

                                        <div class="ad-item row">
                                            <div class="item-image-box col-lg-4">
                                                <!-- item-image -->
                                                <div class="item-image">
                                                    <a href="details.html"><img src="images/trending/3.jpg" alt="Image"
                                                                                class="img-fluid"></a>
                                                </div><!-- item-image -->
                                            </div><!-- item-image-box -->


                                            <div class="item-info col-lg-8">
                                                <!-- ad-info -->
                                                <div class="ad-info">
                                                    <h3 class="item-price">$890.00 <span>(Negotiable)</span></h3>
                                                    <h4 class="item-title"><a href="#">Samsung Galaxy S6 Edge</a></h4>
                                                    <div class="item-cat">
                                                        <span><a href="#">Electronics & Gedgets</a></span> /
                                                        <span><a href="#">Mobile Phone</a></span>
                                                    </div>
                                                </div><!-- ad-info -->

                                                <!-- ad-meta -->
                                                <div class="ad-meta">
                                                    <div class="meta-content">
                                                        <span class="dated"><a href="#">7 Jan, 16  10:10 pm </a></span>
                                                        <a href="#" class="tag"><i class="fa fa-tags"></i> Used</a>
                                                    </div>
                                                    <!-- item-info-right -->
                                                    <div class="user-option pull-right">
                                                        <a href="#" data-toggle="tooltip" data-placement="top"
                                                           title="Los Angeles, USA"><i class="fa fa-map-marker"></i>
                                                        </a>
                                                        <a href="#" data-toggle="tooltip" data-placement="top"
                                                           title="Dealer"><i class="fa fa-suitcase"></i> </a>
                                                    </div><!-- item-info-right -->
                                                </div><!-- ad-meta -->
                                            </div><!-- item-info -->
                                        </div><!-- ad-item -->

                                        <!-- ad-item -->
                                        <div class="ad-item row">
                                            <div class="item-image-box col-lg-4">
                                                <!-- item-image -->
                                                <div class="item-image">
                                                    <a href="details.html"><img src="images/trending/2.jpg" alt="Image"
                                                                                class="img-fluid"></a>
                                                </div><!-- item-image -->
                                            </div><!-- item-image-box -->

                                            <!-- rending-text -->
                                            <div class="item-info col-lg-8">
                                                <!-- ad-info -->
                                                <div class="ad-info">
                                                    <h3 class="item-price">$250.00 <span>(Negotiable)</span></h3>
                                                    <h4 class="item-title"><a href="#">Bark Furniture, Handmade Bespoke
                                                        Furniture</a></h4>
                                                    <div class="item-cat">
                                                        <span><a href="#">Home Appliances</a></span> /
                                                        <span><a href="#">Sofa</a></span>
                                                    </div>
                                                </div><!-- ad-info -->

                                                <!-- ad-meta -->
                                                <div class="ad-meta">
                                                    <div class="meta-content">
                                                        <span class="dated"><a href="#">7 Jan, 16  10:10 pm </a></span>
                                                        <a href="#" class="tag"><i class="fa fa-tags"></i> Used</a>
                                                    </div>
                                                    <!-- item-info-right -->
                                                    <div class="user-option pull-right">
                                                        <a href="#" data-toggle="tooltip" data-placement="top"
                                                           title="Los Angeles, USA"><i class="fa fa-map-marker"></i>
                                                        </a>
                                                        <a class="online" href="#" data-toggle="tooltip"
                                                           data-placement="top" title="Dealer"><i
                                                            class="fa fa-suitcase"></i> </a>
                                                    </div><!-- item-info-right -->
                                                </div><!-- ad-meta -->
                                            </div><!-- item-info -->
                                        </div><!-- ad-item -->

                                        <!-- ad-item -->
                                        <div class="ad-item row">
                                            <div class="item-image-box col-lg-4">
                                                <!-- item-image -->
                                                <div class="item-image">
                                                    <a href="details.html"><img src="images/trending/4.jpg" alt="Image"
                                                                                class="img-fluid"></a>
                                                    <a href="#" class="verified" data-toggle="tooltip"
                                                       data-placement="left" title="Verified"><i
                                                        class="fa fa-check-square-o"></i></a>
                                                </div><!-- item-image -->
                                            </div><!-- item-image-box -->

                                            <!-- rending-text -->
                                            <div class="item-info col-lg-8">
                                                <!-- ad-info -->
                                                <div class="ad-info">
                                                    <h3 class="item-price">$800.00</h3>
                                                    <h4 class="item-title"><a href="#">Rick Morton- Magicius Chase</a>
                                                    </h4>
                                                    <div class="item-cat">
                                                        <span><a href="#">Books & Magazines</a></span> /
                                                        <span><a href="#">Story book</a></span>
                                                    </div>
                                                </div><!-- ad-info -->

                                                <!-- ad-meta -->
                                                <div class="ad-meta">
                                                    <div class="meta-content">
                                                        <span class="dated"><a href="#">7 Jan, 16  10:10 pm </a></span>
                                                        <a href="#" class="tag"><i class="fa fa-tags"></i> Used</a>
                                                    </div>
                                                    <!-- item-info-right -->
                                                    <div class="user-option pull-right">
                                                        <a href="#" data-toggle="tooltip" data-placement="top"
                                                           title="Los Angeles, USA"><i class="fa fa-map-marker"></i>
                                                        </a>
                                                        <a href="#" data-toggle="tooltip" data-placement="top"
                                                           title="Individual"><i class="fa fa-user"></i> </a>
                                                    </div><!-- item-info-right -->
                                                </div><!-- ad-meta -->
                                            </div><!-- item-info -->
                                        </div><!-- ad-item -->
                                    </div><!-- tab-pane -->

                                    <!-- tab-pane -->
                                    <div role="tabpanel" class="tab-pane fade" id="hot-ads">
                                        <!-- ad-item -->
                                        <div class="ad-item row">
                                            <!-- item-image -->
                                            <div class="item-image-box col-lg-4">
                                                <div class="item-image">
                                                    <a href="details.html"><img src="images/trending/1.jpg" alt="Image"
                                                                                class="img-fluid"></a>
                                                    <a href="#" class="verified" data-toggle="tooltip"
                                                       data-placement="left" title="Verified"><i
                                                        class="fa fa-check-square-o"></i></a>
                                                </div><!-- item-image -->
                                            </div>

                                            <!-- rending-text -->
                                            <div class="item-info col-lg-8">
                                                <!-- ad-info -->
                                                <div class="ad-info">
                                                    <h3 class="item-price">$50.00</h3>
                                                    <h4 class="item-title"><a href="#">Apple TV - Everything you need to
                                                        know!</a></h4>
                                                    <div class="item-cat">
                                                        <span><a href="#">Electronics & Gedgets</a></span> /
                                                        <span><a href="#">Tv & Video</a></span>
                                                    </div>
                                                </div><!-- ad-info -->

                                                <!-- ad-meta -->
                                                <div class="ad-meta">
                                                    <div class="meta-content">
                                                        <span class="dated"><a href="#">7 Jan, 16  10:10 pm </a></span>
                                                        <a href="#" class="tag"><i class="fa fa-tags"></i> Used</a>
                                                    </div>
                                                    <!-- item-info-right -->
                                                    <div class="user-option pull-right">
                                                        <a href="#" data-toggle="tooltip" data-placement="top"
                                                           title="Los Angeles, USA"><i class="fa fa-map-marker"></i>
                                                        </a>
                                                        <a class="online" href="#" data-toggle="tooltip"
                                                           data-placement="top" title="Dealer"><i
                                                            class="fa fa-suitcase"></i> </a>
                                                    </div><!-- item-info-right -->
                                                </div><!-- ad-meta -->
                                            </div><!-- item-info -->
                                        </div><!-- ad-item -->
                                        <!-- ad-item -->
                                        <div class="ad-item row">
                                            <div class="item-image-box col-lg-4">
                                                <!-- item-image -->
                                                <div class="item-image">
                                                    <a href="details.html"><img src="images/trending/4.jpg" alt="Image"
                                                                                class="img-fluid"></a>
                                                    <a href="#" class="verified" data-toggle="tooltip"
                                                       data-placement="left" title="Verified"><i
                                                        class="fa fa-check-square-o"></i></a>
                                                </div><!-- item-image -->
                                            </div><!-- item-image-box -->

                                            <!-- rending-text -->
                                            <div class="item-info col-lg-8">
                                                <!-- ad-info -->
                                                <div class="ad-info">
                                                    <h3 class="item-price">$800.00</h3>
                                                    <h4 class="item-title"><a href="#">Rick Morton- Magicius Chase</a>
                                                    </h4>
                                                    <div class="item-cat">
                                                        <span><a href="#">Books & Magazines</a></span> /
                                                        <span><a href="#">Story book</a></span>
                                                    </div>
                                                </div><!-- ad-info -->

                                                <!-- ad-meta -->
                                                <div class="ad-meta">
                                                    <div class="meta-content">
                                                        <span class="dated"><a href="#">7 Jan, 16  10:10 pm </a></span>
                                                        <a href="#" class="tag"><i class="fa fa-tags"></i> Used</a>
                                                    </div>
                                                    <!-- item-info-right -->
                                                    <div class="user-option pull-right">
                                                        <a href="#" data-toggle="tooltip" data-placement="top"
                                                           title="Los Angeles, USA"><i class="fa fa-map-marker"></i>
                                                        </a>
                                                        <a href="#" data-toggle="tooltip" data-placement="top"
                                                           title="Individual"><i class="fa fa-user"></i> </a>
                                                    </div><!-- item-info-right -->
                                                </div><!-- ad-meta -->
                                            </div><!-- item-info -->
                                        </div><!-- ad-item -->

                                        <div class="ad-item row">
                                            <div class="item-image-box col-lg-4">
                                                <!-- item-image -->
                                                <div class="item-image">
                                                    <a href="details.html"><img src="images/trending/3.jpg" alt="Image"
                                                                                class="img-fluid"></a>
                                                </div><!-- item-image -->
                                            </div><!-- item-image-box -->

                                            <!-- ad-item -->
                                            <div class="item-info col-lg-8">
                                                <!-- ad-info -->
                                                <div class="ad-info">
                                                    <h3 class="item-price">$890.00 <span>(Negotiable)</span></h3>
                                                    <h4 class="item-title"><a href="#">Samsung Galaxy S6 Edge</a></h4>
                                                    <div class="item-cat">
                                                        <span><a href="#">Electronics & Gedgets</a></span> /
                                                        <span><a href="#">Mobile Phone</a></span>
                                                    </div>
                                                </div><!-- ad-info -->

                                                <!-- ad-meta -->
                                                <div class="ad-meta">
                                                    <div class="meta-content">
                                                        <span class="dated"><a href="#">7 Jan, 16  10:10 pm </a></span>
                                                        <a href="#" class="tag"><i class="fa fa-tags"></i> Used</a>
                                                    </div>
                                                    <!-- item-info-right -->
                                                    <div class="user-option pull-right">
                                                        <a href="#" data-toggle="tooltip" data-placement="top"
                                                           title="Los Angeles, USA"><i class="fa fa-map-marker"></i>
                                                        </a>
                                                        <a href="#" data-toggle="tooltip" data-placement="top"
                                                           title="Dealer"><i class="fa fa-suitcase"></i> </a>
                                                    </div><!-- item-info-right -->
                                                </div><!-- ad-meta -->
                                            </div><!-- item-info -->
                                        </div><!-- ad-item -->

                                        <!-- ad-item -->
                                        <div class="ad-item row">
                                            <div class="item-image-box col-lg-4">
                                                <!-- item-image -->
                                                <div class="item-image">
                                                    <a href="details.html"><img src="images/trending/2.jpg" alt="Image"
                                                                                class="img-fluid"></a>
                                                </div><!-- item-image -->
                                            </div><!-- item-image-box -->

                                            <!-- rending-text -->
                                            <div class="item-info col-lg-8">
                                                <!-- ad-info -->
                                                <div class="ad-info">
                                                    <h3 class="item-price">$250.00 <span>(Negotiable)</span></h3>
                                                    <h4 class="item-title"><a href="#">Bark Furniture, Handmade Bespoke
                                                        Furniture</a></h4>
                                                    <div class="item-cat">
                                                        <span><a href="#">Home Appliances</a></span> /
                                                        <span><a href="#">Sofa</a></span>
                                                    </div>
                                                </div><!-- ad-info -->

                                                <!-- ad-meta -->
                                                <div class="ad-meta">
                                                    <div class="meta-content">
                                                        <span class="dated"><a href="#">7 Jan, 16  10:10 pm </a></span>
                                                        <a href="#" class="tag"><i class="fa fa-tags"></i> Used</a>
                                                    </div>
                                                    <!-- item-info-right -->
                                                    <div class="user-option pull-right">
                                                        <a href="#" data-toggle="tooltip" data-placement="top"
                                                           title="Los Angeles, USA"><i class="fa fa-map-marker"></i>
                                                        </a>
                                                        <a class="online" href="#" data-toggle="tooltip"
                                                           data-placement="top" title="Dealer"><i
                                                            class="fa fa-suitcase"></i> </a>
                                                    </div><!-- item-info-right -->
                                                </div><!-- ad-meta -->
                                            </div><!-- item-info -->
                                        </div><!-- ad-item -->
                                    </div><!-- tab-pane -->
                                </div>
                            </div><!-- trending-ads -->

                            <!-- cta -->
                            <div class="section cta text-center">
                                <div class="row">
                                    <!-- single-cta -->
                                    <div class="col-lg-4">
                                        <div class="single-cta">
                                            <!-- cta-icon -->
                                            <div class="cta-icon icon-secure">
                                                <img src="images/icon/13.png" alt="Icon" class="img-fluid">
                                            </div><!-- cta-icon -->

                                            <h4>Secure Trading</h4>
                                            <p>Duis autem vel eum iriure dolor in hendrerit in</p>
                                        </div>
                                    </div><!-- single-cta -->

                                    <!-- single-cta -->
                                    <div class="col-lg-4">
                                        <div class="single-cta">
                                            <!-- cta-icon -->
                                            <div class="cta-icon icon-support">
                                                <img src="images/icon/14.png" alt="Icon" class="img-fluid">
                                            </div><!-- cta-icon -->

                                            <h4>24/7 Support</h4>
                                            <p>Duis autem vel eum iriure dolor in hendrerit in</p>
                                        </div>
                                    </div><!-- single-cta -->

                                    <!-- single-cta -->
                                    <div class="col-lg-4">
                                        <div class="single-cta">
                                            <!-- cta-icon -->
                                            <div class="cta-icon icon-trading">
                                                <img src="images/icon/15.png" alt="Icon" class="img-fluid">
                                            </div><!-- cta-icon -->

                                            <h4>Easy Trading</h4>
                                            <p>Duis autem vel eum iriure dolor in hendrerit in</p>
                                        </div>
                                    </div><!-- single-cta -->
                                </div><!-- row -->
                            </div><!-- cta -->
                        </div>
                    </div>
                </div><!-- product-list -->
            </div><!-- row -->
        </div><!-- main-content --><!-- container -->
    </section><!-- main -->

        <!-- download -->

</template>

<script>
    import vendor_types from "../modules/vendor_types";

    export default {
        name: "Home",
        data() {
            return {
                vendorTypes:'',
                activeArea:'',
                disableServices:[],
                category:'',
            }
        },
        computed:{

            allVendorTypes(){
                let vendor_types = this.$store.getters.allVendorTypes;
                let vendor_types_for_show = this.$store.getters.allVendorTypesForShow;
                for (let index in vendor_types){
                    if (vendor_types_for_show[index] === undefined){
                        this.disableServices[index]='disabled-service';
                    }else{
                        this.disableServices[index]='';
                    }
                }
                return vendor_types;
            },

        },
        mounted() {
            this.$store.dispatch('fetchVendorTypes');
        },
        methods:{
            getVendors(area_id,that){
                this.$store.dispatch('fetchVendorsTypeForArea',area_id);
                this.handleShowSelectAreas = false;
                this.activeArea = '';
                this.selectedArea = that.innerText;
            },
        },
    }
</script>

<style scoped>
    .title{
        color: #4a4a4a;
    }
    .color-white{
        color: white;
    }
    .font-size-4{
        font-size: 1rem;
    }
    .my-btn-white{
        border: 1px solid white;
    }

    .img-order{
        position: absolute;
        width: 675px;
        right: 0%;
        bottom: -4.1rem;
    }

    .img-width-80{
        height: auto;
        width: 80%;
    }
    .btn:focus {
        box-shadow: none;
    }

</style>
