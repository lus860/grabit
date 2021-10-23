import Vue from 'vue';
import Vuex from 'vuex';
import cities from "../modules/cities";
import areas from "../modules/areas";
import vendors from "../modules/vendor";
import vendors_types from "../modules/vendor_types";
import images from "../modules/home_page/images";
import user from "../modules/checkuser.js";
import otp from "../modules/checkotp.js";

Vue.use(Vuex);

export default new Vuex.Store({
        modules:{
            otp,
            user,
            cities,
            areas,
            images,
            vendors,
            vendors_types,
        }
})
