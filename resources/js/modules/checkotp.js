export default {
    actions:{
        async fetchCheckOtp(ctx,data){
            const res = await fetch('/vue-api/check-opt/'+data.user_id+'/'+data.otp);
            const otp = await res.json();

            ctx.commit('updateOtp',otp);
        },
        async fetchCheckOtpNewUser(ctx,data){
            const res = await fetch(`/vue-api/check-opt/${data.user_id}/${data.otp}/${data.user_name}/${data.password}`);
            const otp = await res.json();

            ctx.commit('updateOtp',otp);
        }
    },
    mutations:{
        updateOtp(state,otp){
            state.otp = otp;
        },

    },
    state:{
        otp:'',
        user_name:''
    },
    getters:{
        authOtp(state){
            return state.otp;
        },
        currentUser(state,getters){
            if(localStorage.getItem('user')){
                state.user_name = localStorage.getItem('user')
            }else if(getters.authOtp.name){
                localStorage.setItem('user', getters.authOtp.name);
                state.user_name = localStorage.getItem('user')
            }else {
                return false
            }
            return state.user_name;
        }
    }
}
