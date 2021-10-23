export default {
    actions:{
        async fetchVendorsForArea(ctx,area_id){
            const res = await fetch('/vue-api/get-vendors-for-area/'+area_id);
            const vendors = await res.json();

            ctx.commit('updateVendors',vendors);
        }
    },
    mutations:{
        updateVendors(state,vendors){
            state.vendors = vendors;
        }
    },
    state:{
        vendors:[]
    },
    getters:{
        allVendors(state){
            return state.vendors;
        }
    }
}
