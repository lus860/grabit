export default {
    actions:{
        async fetchCities(ctx){
            const res = await fetch('/vue-api/get-all-cities');
            const cities = await res.json();

            ctx.commit('updateCities',cities);
        }
    },
    mutations:{
        updateCities(state,cities){
            state.cities = cities;
        }
    },
    state:{
        cities:[]
    },
    getters:{
        allCities(state){
            return state.cities;
        }
    }
}
