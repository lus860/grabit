<template>
    <component :is="layout">
        <router-view></router-view>
    </component>
</template>

<script>
    import Main from './layouts/Main';
    export default {
        name: "App",
        computed:{
            layout(){
                return (this.$route.meta.layout || 'Main');
            }
        },
        components:{
            Main
        },
    }
</script>

<style>

/*@import './assets/vendor/owl-carousel/owl.carousel.css';*/
/*@import './assets/vendor/owl-carousel/owl.theme.css';*/

</style>
