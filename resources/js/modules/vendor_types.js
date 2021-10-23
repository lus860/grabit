export default {
    actions:{
        async fetchVendorTypes(ctx){
            const res = await fetch('/vue-api/get-vendors-types');
            const vendor_types = await res.json();

            ctx.commit('updateVendorTypes',vendor_types);
        },
        async fetchVendorsTypeForArea(ctx,area_id){
            const res = await fetch('/vue-api/get-vendors-for-area/'+area_id);
            const vendor_types = await res.json();
            ctx.commit('updateVendorsTypesFopShow',vendor_types);
        }
    },
    mutations:{
        updateVendorTypes(state,vendor_types){
            state.vendor_types = vendor_types;
        },
        updateVendorsTypesFopShow(state,vendor_types_show){
            state.vendor_types_show = vendor_types_show;
        }
    },
    state:{
        vendor_types:[],
        vendor_types_show:[]
    },
    getters:{
        allVendorTypes(state){
            return state.vendor_types;
        },
        allVendorTypesForShow(state){
            return state.vendor_types_show;
        }
    }
}
