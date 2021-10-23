<template>
    <div class="" style="background-color: #eef0ef">
        <div class="container pb-4">
            <h1 class="title mbs-desktop mbl-touch is-1 is-size-2-mobile has-text-centered-desktop has-text-left-touch vertical-center">
                Everything you need, delivered now
            </h1>
            <!--        <h2 class="title is-4 is-size-3-mobile is-hidden-touch has-text-centered-desktop has-text-left-touch"> </h2>-->
            <div class="row">
                <div class="w-75 m-auto">
                    <div class="col-md-6 col-md-offset-2 text-left">
                        <h3 class="services-wrapper-title mbm-desktop mbl-touch fl-wrap fv-ctr">
                            <i aria-hidden="true" class="fa fa-lg fa-location-arrow has-text-primary fsh-0"></i>
                            <span class="mlxs fsh-0">Delivering to</span>
                        </h3>
                    </div>
                    <div class="col-md-6"></div>
                </div>
            </div>
            <div class="row mb-4">
                <div class="row w-75 m-auto">
                    <div class="col-xs-12 col-md-6 is-12-touch pvn-touch">
                        <div class="cities-items-container is-hidden-touch select is-fullwidth text-left">
                            <div class="city-list-selector pvn" v-on:click="handleCitiesSelect" :class="active">
                                {{selectedCity}}
                            </div>
                            <div v-if="handleShowSelectCities" class="cities-items-list"><!---->
                                <div>
                                    <div class="cities-items-list-item">
                                        <div class="item has-ellipsis" v-for="city in allCities" @click="getAreas(city.id,$event.target)">
                                            {{city.name}}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-xs-12 col-md-6 is-12-touch pvn-touch ">
                        <div class="cities-items-container is-hidden-touch select is-fullwidth text-left" :class="disableSelectArea">
                            <div class="city-list-selector pvn " v-on:click="handleSelectAreas" :class="activeArea">
                                {{selectedArea}}
                            </div>
                            <div v-if="handleShowSelectAreas" class="cities-items-list"><!---->
                                <div>
                                    <div class="cities-items-list-item">
                                        <div class="item has-ellipsis" v-for="area in allAreas" @click="getVendors(area.id,$event.target)">
                                            {{area.name}}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row w-75 m-auto">
                <div class="mtl-desktop mtm-touch service-card-list is-hidden-touch text-left ">
                    <h3 class="services-wrapper-title mbm fs-15 pl-1">Choose a service</h3>
                    <div class="row" >
                        <div class="col-md-4 col disabled" v-for="(vendor_type,index) in allVendorTypes" :class="disableServices[index]">
                            <div class="loading-placeholder">
                                <div class="simple-card disabled">
                                    <img class="card-image mb-2" data-src="https://d2lev5xroqke9e.cloudfront.net/ng/view/61c8f2a371?width=168&amp;height=168" src="https://d2lev5xroqke9e.cloudfront.net/ng/view/61c8f2a371?width=168&amp;height=168" loading="lazy">
                                    <p class="card-title">
                                        {{vendor_type.vendor_name}}
                                    </p>
                                    <p class="card-description">
                                        Gifts, electronics, airtime &amp; more
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="text-center" style="background-color: #ffffff">
            <div class="container pb-5 pt-5">
                <div class="row">
                    <div class="col-md-3 col-xs-12" v-for="image in getImages.images_third">
                        <img  class="img-responsive" :src="image.image" alt="" width="100%" height="250px">
                    </div>
                </div>
            </div>
        </div>

        <div class="text-center" style="background-color: #fff;min-height: 500px;">
            <div class="container pb-5 pt-5">
                <div class="row">
                    <div class="col-md-6 col-xs-12 text-center">
                        <h4>Operating a store and want to sell with us?</h4>
                        <p>Text</p>
                        <button class="btn btn-primary">Work with us</button>
                    </div>
                    <div class="col-md-6 col-xs-12 text-center">
                        <h4>Start grabbing whatever you want at ease</h4>
                        <p>Text</p>
                        <button class="btn btn-primary">Sign up</button>
                    </div>
                </div>
            </div>
        </div>

        <div class="text-center" style="background-color: #ea5b31">
            <div class="container pb-5 pt-5">
                <div class="row">
                    <div class="col-xs-12 col-md-6">
                        <h1 class="color-white text-left">
                            Order food
                        </h1>
                        <p class="color-white text-left font-size-4">The Grab It app makes it simple for anybody to use a mobile device to look over the menu of the best vendors and place an order for home delivery.</p>
                        <div class="text-left">
                            <a href="#" target="_blank" class="btn my-btn-white">
                                <img src="/images/apple.png" alt="Apple app download">
                            </a>
                            <a href="#" target="_blank" class="btn my-btn-white">
                                <img src="/images/android.png" alt="Android app download">
                            </a>
                        </div>

                    </div>
                    <div class="col-xs-12 col-md-6 text-right">
                        <div class="img-order">
                            <img v-if="this.images_fifth" class="img-width-80" :src="this.images_fifth.image" alt="Phone images">
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
</template>

<script>
    import vendor_types from "../modules/vendor_types";

    export default {
        name: "Home",
        data() {
            return {
                handleShowSelectCities:false,
                selectedCity:'Select your city',
                selectedArea:'Select your area',
                handleShowSelectAreas:false,
                vendorTypes:'',
                active:'',
                activeArea:'',
                disableSelectArea:'disabled-select',
                disableServices:[],
                images_fifth:'',
            }
        },
        computed:{
            allCities(){
                return this.$store.getters.allCities;
            },
            allAreas(){
                let area = this.$store.getters.allAreas;
                if (area.length === 0){
                    this.selectedArea = 'Select your area';
                    this.handleShowSelectAreas = false;
                    this.disableSelectArea = 'disabled-select';
                    this.activeArea = '';
                }
                return this.$store.getters.allAreas;
            },
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
            getImages(){
                if (this.$store.getters.allImages.images_fifth[0]){
                    this.images_fifth = this.$store.getters.allImages.images_fifth[0];
                }
                return this.$store.getters.allImages;
            }
        },
        mounted() {
            this.$store.dispatch('fetchVendorTypes');
            this.$store.dispatch('fetchImagesForHomePage');
        },
        methods:{
            handleCitiesSelect(){
                this.handleShowSelectCities = !this.handleShowSelectCities;
                if (this.handleShowSelectCities){
                    this.$store.dispatch('fetchCities')
                    this.active = 'active'
                }else{
                    this.active = ''
                }
            },
            handleSelectAreas(){
                this.handleShowSelectAreas = !this.handleShowSelectAreas;
                if (this.handleShowSelectAreas){
                    // this.$store.dispatch('fetchAreasForCities')
                    this.activeArea = 'active'
                }else{
                    this.activeArea = ''
                }
            },
            getAreas(e,that){
                this.$store.dispatch('fetchAreasForCities',e);
                this.handleShowSelectCities = false;
                this.active = '';
                this.disableSelectArea = '';
                this.selectedCity = that.innerText;
            },

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
