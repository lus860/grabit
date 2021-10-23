export default {
    actions:{
        async fetchAreasForCities(ctx,city_id){
            const res = await fetch('/vue-api/get-areas-for-city/'+city_id);
            const areas = await res.json();

            ctx.commit('updateAreas',areas);
        }
    },
    mutations:{
        updateAreas(state,areas){
            state.areas = areas;
        }
    },
    state:{
        areas:[]
    },
    getters:{
        allAreas(state){
            return state.areas;
        }
    }
}
