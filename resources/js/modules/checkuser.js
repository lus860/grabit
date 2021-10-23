export default {
    actions:{
        async fetchAuthUser(ctx,data){
            const res = await fetch('/vue-api/check-user/'+data.login);
            const user = await res.json();

            ctx.commit('updateUser',user);
        },
        async clearUser(ctx,){
            const user = null
            ctx.commit('updateUser',user);
        }
    },
    mutations:{
        updateUser(state,user){
            state.user = user;
        }
    },
    state:{
        user:''
    },
    getters:{
        authUser(state){
            return state.user;
        }
    }
}
