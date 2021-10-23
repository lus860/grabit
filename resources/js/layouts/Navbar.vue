<template>
    <header id="header" class="clearfix">
        <!-- navbar -->
        <nav class="navbar navbar-default navbar-expand-lg">
            <div class="container">
                <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#tr-mainmenu" aria-controls="tr-mainmenu" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"><i class="fa fa-align-justify"></i></span>
                </button>

                <router-link class="navbar-brand" to="/"><img class="img-fluid" src="images/logo1.png" alt="Logo"></router-link>

                <div class="nav-right">
                    <!-- sign-in -->

                    <ul class="sign-in" v-if="!currentUser">
                        <li><i class="fa fa-user"></i></li>
                        <li><router-link to="signin">
                            <span @click="changeLoading">Sign In/Register</span>
                        </router-link></li>
                    </ul><!-- sign-in -->
                    <ul class="sign-in" v-else>
                        <li><i class="fa fa-user"></i></li>
                        <li><router-link to="signin">Hi {{user}}</router-link></li>
                    </ul><!-- sign-in -->

                    <router-link to="ad-post.html" class="btn">Post Your Ad</router-link>
                </div>

            </div><!-- container -->
        </nav><!-- navbar -->
    </header><!-- header -->

</template>

<script>
    export default {
        name: "Navbar",
        data() {
            return {
                user:'',
                auth_user:'',
                loading:false,
            }
        },
        watch: {
            loading() {
                return location.reload();
            },
        },
        computed: {
            currentUser() {
                if(this.$store.getters.currentUser){
                    this.user  = this.$store.getters.currentUser;
                    return  this.user;
                }
                return false;

            },

        },
        methods:{
            changeLoading() {
                this.$store.dispatch('clearUser');
            }
        }
     }
</script>

<style scoped>

</style>
