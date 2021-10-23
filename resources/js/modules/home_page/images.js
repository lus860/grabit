export default {
    actions:{
        async fetchImagesForHomePage(ctx){
            const res = await fetch('/vue-api/get-images-for-homepage');
            const images = await res.json();

            ctx.commit('updateImagesThird',images.images_third);
            ctx.commit('updateImagesFifth',images.images_fifth);
        }
    },
    mutations:{
        updateImagesThird(state,images){
            state.images_third = images;
        },
        updateImagesFifth(state,images){
            state.images_fifth = images;
        }
    },
    state:{
        images_third:[],
        images_fifth:[]
    },
    getters:{
        allImages(state){
            return state;
        }
    }
}
