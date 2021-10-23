<template>
    <!-- main -->
    <section id="main" class="clearfix user-page">
        <div class="container">
            <div class="row text-center">
                <!-- user-login -->
                <div class="col-md-8 offset-md-2 col-lg-6 offset-lg-3" v-if="!getUser">
                    <div class="user-account">
                        <h2>Enter mobile number or email address</h2>
                            <div class="form-group">
                                <input type="text" name="email_or_phone" v-model="login" class="form-control login" placeholder="0755 123 456 or john.doe@hotmail.com" >
                            </div>
                            <button type="button" @click="signIn" class="btn">Proceed</button><br>
                        <img src="images/loader.gif" alt="images" style="width: 100px" v-if="spinner_signin">

                        <!-- forgot-password -->
                        <div class="user-option">
                            <div class="pull-right forgot-password">
                                <a href="#">Forgot password</a>
                            </div>
                        </div><!-- forgot-password -->
                    </div>
                    <div class="alert alert-danger" role="alert" v-if="message.error.message_login">
                        {{message.error.message_login}}
                    </div>
                    <a href="#" class="btn-primary">Create a New Account</a>
                </div><!-- user-login -->
                <div class="col-md-8 offset-md-2 col-lg-6 offset-lg-3" v-else>

                    <div class="alert alert-danger" role="alert" v-if="getOtp">
                        {{message.error.message_otp}}
                    </div>
                    <div class="alert alert-danger" role="alert" v-show="message.error.message">
                        {{message.error.message}}
                    </div>
                    <div class="alert alert-success" role="alert" v-if="message.success.message">
                        {{message.success.message}}
                    </div>
                    <div class="user-account">
                        <h2>Enter OTP</h2>
                        <!-- form -->
                            <div class="form-group">
                                <input type="text" name="otp" v-model="otp" class="form-control" placeholder="OTP" >
                            </div>
                            <div class="form-group">
                               <input type="text" name="name" v-model="name" class="form-control" placeholder="Name" v-if="new_user">
                            </div>
                            <div class="form-group">
                               <input type="password" name="password" v-model="password" class="form-control" placeholder="Password" v-if="new_user">
                            </div>
                            <button type="button" @click="sendOtp" class="btn">Send</button><br>
                        <img src="images/loader.gif" alt="images" style="width: 100px" v-if="spinner_otp">
                    </div>
                </div><!-- user-login -->
            </div><!-- row -->

        </div><!-- container -->
    </section><!-- signin-page -->
</template>

<script>
    export default {
        data() {
            return {
                otp:'',
                login:'',
                password:'',
                user:false,
                name:'',
                new_user:false,
                user_id:'',
                message:{
                    success:{
                        message:'',
                    },
                    error:{
                        message_otp:'You wrote OTP wrong',
                        message:'',
                        message_login:'',
                        show:false,
                    }
                },
                spinner_signin:false,
                spinner_otp:false
            }
        },
        watch: {
           message: function (val) {
                return this.message.error.message
            }
        },
        computed:{
            getOtp(){
                let otp = this.$store.getters.authOtp;
                if (otp){
                    this.spinner_otp = false
                    if(otp === 1){
                        this.message.success.message = '';
                        this.message.error.message = '';
                        return true
                    }else{
                        return this.$router.push('/')
                    }
                }
                return false;
            },

            getUser(){
               let user = this.$store.getters.authUser;
               if (user) {
                   if (user === 1) {
                       this.message.error.message_login = 'Not correct phone'
                       this.spinner_signin = false
                       return false;
                   } else {
                       if (user.status === 1) {
                           this.user = true
                           this.message.success.message = 'Please, fill in OTP'
                           this.message.error.message_login ='';
                           this.user_id = user.id
                           return true
                       } else if (user.status === 0) {
                           this.user = true
                           this.new_user = true
                           this.message.success.message = 'Please, fill in OTP, name and password'
                           this.message.error.message_login ='';
                           this.message_otp = '';
                           this.user_id = user.id
                           return true
                       }

                   }
               }
                this.spinner_signin = false
                this.spinner_otp = false
                this.user = false
                this.login = ''
                this.message.error.message =''
                this.message.success.message =''
                //this.message.error.message_otp = ''
               return false;
            },

        },
        mounted() {

        },
        methods:{
            signIn(){
                if(!this.validate_login()){
                    this.$store.dispatch('fetchAuthUser', {login:this.login});
                    this.message.error.message = '';
                    this.message.error.message_login ='';
                    this.spinner_signin = true;
                }

            },
            validate_login(){
                if(this.login === ''){
                    this.message.error.message_login = 'Login is required';
                    return true;
                }
                return false;
            },

            validate_new_user(){
                if(this.otp === '' || this.name === '' || this.password === ''){
                    this.message.error.message ='All fields is required';
                    this.message.success.message = '';
                    return true;
                }else if(this.password.length < 6) {
                    this.message.error.message = 'The password must be at least 6';
                    this.message.success.message = '';
                    return true;
                }
                return false;
            },
            validate_otp(){
                if(this.otp === '' ){
                    this.message.error.message ='OTP is required';
                    this.message.success.message = '';
                    console.log(this.message.error.message)
                    return true;
                }
                return false;
            },
            sendOtp(){
                if(this.new_user){
                    if(!this.validate_new_user()){
                        this.$store.dispatch('fetchCheckOtpNewUser', {password:this.password,user_id:this.user_id,user_name:this.name, otp:this.otp});
                    }
                }else{
                    if(!this.validate_otp()) {
                        this.$store.dispatch('fetchCheckOtp', {user_id: this.user_id, otp: this.otp});
                        this.spinner_otp = true;
                    }
                }
            },

        },

    }
</script>


