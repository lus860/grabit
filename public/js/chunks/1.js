(window["webpackJsonp"] = window["webpackJsonp"] || []).push([[1],{

/***/ "./node_modules/babel-loader/lib/index.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/views/Signin.vue?vue&type=script&lang=js&":
/*!************************************************************************************************************************************************************!*\
  !*** ./node_modules/babel-loader/lib??ref--4-0!./node_modules/vue-loader/lib??vue-loader-options!./resources/js/views/Signin.vue?vue&type=script&lang=js& ***!
  \************************************************************************************************************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
/* harmony default export */ __webpack_exports__["default"] = ({
  data: function data() {
    return {
      otp: '',
      login: '',
      password: '',
      user: false,
      name: '',
      new_user: false,
      user_id: '',
      message: {
        success: {
          message: ''
        },
        error: {
          message_otp: 'You wrote OTP wrong',
          message: '',
          show: false
        }
      },
      spinner_signin: false,
      spinner_otp: false
    };
  },
  watch: {
    'message.error.message': function messageErrorMessage(val) {
      return this.message.error.message;
    }
  },
  computed: {
    getOtp: function getOtp() {
      var otp = this.$store.getters.authOtp;

      if (otp) {
        this.spinner_otp = false;

        if (otp === 1) {
          this.message.success.message = '';
          this.message.error.message = '';
          return true;
        } else {
          return this.$router.push('/');
        }
      }

      return false;
    },
    getUser: function getUser() {
      var user = this.$store.getters.authUser; // if (localStorage.getItem('click-signIn')){
      //     this.user = false
      //     localStorage.clear()
      //     return false;
      // }

      if (user) {
        if (user === 1) {
          this.message.error.message = 'Not correct phone';
          this.spinner_signin = false;
          return false;
        } else {
          if (user.status === 1) {
            this.user = true;
            this.message.success.message = 'Please, fill in OTP';
            this.user_id = user.id;
            return true;
          } else if (user.status === 0) {
            this.user = true;
            this.new_user = true;
            this.message.success.message = 'Please, fill in OTP, name and password';
            this.user_id = user.id;
            return true;
          }
        }
      }

      this.spinner_signin = false;
      this.spinner_otp = false;
      this.user = false;
      this.login = '';
      this.message.error.message = '';
      this.message.success.message = ''; //this.message.error.message_otp = ''

      return false;
    }
  },
  mounted: function mounted() {},
  methods: {
    signIn: function signIn() {
      if (!this.validate_login()) {
        this.$store.dispatch('fetchAuthUser', {
          login: this.login
        });
        this.message.error.message = '';
        this.spinner_signin = true;
      }
    },
    validate_login: function validate_login() {
      if (this.login === '') {
        this.message.error.message = 'Login is required';
        return true;
      }

      return false;
    },
    validate_new_user: function validate_new_user() {
      if (this.otp === '' || this.name === '' || this.password === '') {
        this.message.error.message = 'All fields is required';
        this.message.success.message = '';
        return true;
      } else if (this.password.length < 6) {
        this.message.error.message = 'The password must be at least 6';
        this.message.success.message = '';
        return true;
      }

      return false;
    },
    validate_otp: function validate_otp() {
      if (this.otp === '') {
        this.message.error.message = 'OTP is required';
        this.message.success.message = '';
        console.log(this.message.error.message);
        return true;
      }

      return false;
    },
    sendOtp: function sendOtp() {
      if (this.new_user) {
        if (!this.validate_new_user()) {
          this.$store.dispatch('fetchCheckOtpNewUser', {
            password: this.password,
            user_id: this.user_id,
            user_name: this.name,
            otp: this.otp
          });
        }
      } else {
        if (!this.validate_otp()) {
          this.$store.dispatch('fetchCheckOtp', {
            user_id: this.user_id,
            otp: this.otp
          });
          this.spinner_otp = true;
        }
      }
    }
  }
});

/***/ }),

/***/ "./node_modules/vue-loader/lib/loaders/templateLoader.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/views/Signin.vue?vue&type=template&id=f914eefe&":
/*!****************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!./node_modules/vue-loader/lib??vue-loader-options!./resources/js/views/Signin.vue?vue&type=template&id=f914eefe& ***!
  \****************************************************************************************************************************************************************************************************/
/*! exports provided: render, staticRenderFns */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "render", function() { return render; });
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "staticRenderFns", function() { return staticRenderFns; });
var render = function() {
  var _vm = this
  var _h = _vm.$createElement
  var _c = _vm._self._c || _h
  return _c(
    "section",
    { staticClass: "clearfix user-page", attrs: { id: "main" } },
    [
      _c("div", { staticClass: "container" }, [
        _c("div", { staticClass: "row text-center" }, [
          !_vm.getUser
            ? _c(
                "div",
                { staticClass: "col-md-8 offset-md-2 col-lg-6 offset-lg-3" },
                [
                  _c("div", { staticClass: "user-account" }, [
                    _c("h2", [_vm._v("Enter mobile number or email address")]),
                    _vm._v(" "),
                    _c("div", { staticClass: "form-group" }, [
                      _c("input", {
                        directives: [
                          {
                            name: "model",
                            rawName: "v-model",
                            value: _vm.login,
                            expression: "login"
                          }
                        ],
                        staticClass: "form-control login",
                        attrs: {
                          type: "text",
                          name: "email_or_phone",
                          placeholder: "0755 123 456 or john.doe@hotmail.com"
                        },
                        domProps: { value: _vm.login },
                        on: {
                          input: function($event) {
                            if ($event.target.composing) {
                              return
                            }
                            _vm.login = $event.target.value
                          }
                        }
                      })
                    ]),
                    _vm._v(" "),
                    _c(
                      "button",
                      {
                        staticClass: "btn",
                        attrs: { type: "button" },
                        on: { click: _vm.signIn }
                      },
                      [_vm._v("Proceed")]
                    ),
                    _c("br"),
                    _vm._v(" "),
                    _vm.spinner_signin
                      ? _c("img", {
                          staticStyle: { width: "100px" },
                          attrs: { src: "images/loader.gif", alt: "images" }
                        })
                      : _vm._e(),
                    _vm._v(" "),
                    _vm._m(0)
                  ]),
                  _vm._v(" "),
                  _vm.message.error.message
                    ? _c(
                        "div",
                        {
                          staticClass: "alert alert-danger",
                          attrs: { role: "alert" }
                        },
                        [
                          _vm._v(
                            "\n                    " +
                              _vm._s(_vm.message.error.message) +
                              "\n                "
                          )
                        ]
                      )
                    : _vm._e(),
                  _vm._v(" "),
                  _c(
                    "a",
                    { staticClass: "btn-primary", attrs: { href: "#" } },
                    [_vm._v("Create a New Account")]
                  )
                ]
              )
            : _c(
                "div",
                { staticClass: "col-md-8 offset-md-2 col-lg-6 offset-lg-3" },
                [
                  _vm.getOtp
                    ? _c(
                        "div",
                        {
                          staticClass: "alert alert-danger",
                          attrs: { role: "alert" }
                        },
                        [
                          _vm._v(
                            "\n                    " +
                              _vm._s(_vm.message.error.message_otp) +
                              "\n                "
                          )
                        ]
                      )
                    : _vm._e(),
                  _vm._v(" "),
                  _vm.message.error.message
                    ? _c(
                        "div",
                        {
                          staticClass: "alert alert-danger",
                          attrs: { role: "alert" }
                        },
                        [
                          _vm._v(
                            "\n                    " +
                              _vm._s(_vm.message.error.message) +
                              "\n                "
                          )
                        ]
                      )
                    : _vm._e(),
                  _vm._v(" "),
                  _vm.message.success.message
                    ? _c(
                        "div",
                        {
                          staticClass: "alert alert-success",
                          attrs: { role: "alert" }
                        },
                        [
                          _vm._v(
                            "\n                    " +
                              _vm._s(_vm.message.success.message) +
                              "\n                "
                          )
                        ]
                      )
                    : _vm._e(),
                  _vm._v(" "),
                  _c("div", { staticClass: "user-account" }, [
                    _c("h2", [_vm._v("Enter OTP")]),
                    _vm._v(" "),
                    _c("div", { staticClass: "form-group" }, [
                      _c("input", {
                        directives: [
                          {
                            name: "model",
                            rawName: "v-model",
                            value: _vm.otp,
                            expression: "otp"
                          }
                        ],
                        staticClass: "form-control",
                        attrs: {
                          type: "text",
                          name: "otp",
                          placeholder: "OTP"
                        },
                        domProps: { value: _vm.otp },
                        on: {
                          input: function($event) {
                            if ($event.target.composing) {
                              return
                            }
                            _vm.otp = $event.target.value
                          }
                        }
                      })
                    ]),
                    _vm._v(" "),
                    _c("div", { staticClass: "form-group" }, [
                      _vm.new_user
                        ? _c("input", {
                            directives: [
                              {
                                name: "model",
                                rawName: "v-model",
                                value: _vm.name,
                                expression: "name"
                              }
                            ],
                            staticClass: "form-control",
                            attrs: {
                              type: "text",
                              name: "name",
                              placeholder: "Name"
                            },
                            domProps: { value: _vm.name },
                            on: {
                              input: function($event) {
                                if ($event.target.composing) {
                                  return
                                }
                                _vm.name = $event.target.value
                              }
                            }
                          })
                        : _vm._e()
                    ]),
                    _vm._v(" "),
                    _c("div", { staticClass: "form-group" }, [
                      _vm.new_user
                        ? _c("input", {
                            directives: [
                              {
                                name: "model",
                                rawName: "v-model",
                                value: _vm.password,
                                expression: "password"
                              }
                            ],
                            staticClass: "form-control",
                            attrs: {
                              type: "password",
                              name: "password",
                              placeholder: "Password"
                            },
                            domProps: { value: _vm.password },
                            on: {
                              input: function($event) {
                                if ($event.target.composing) {
                                  return
                                }
                                _vm.password = $event.target.value
                              }
                            }
                          })
                        : _vm._e()
                    ]),
                    _vm._v(" "),
                    _c(
                      "button",
                      {
                        staticClass: "btn",
                        attrs: { type: "button" },
                        on: { click: _vm.sendOtp }
                      },
                      [_vm._v("Send")]
                    ),
                    _c("br"),
                    _vm._v(" "),
                    _vm.spinner_otp
                      ? _c("img", {
                          staticStyle: { width: "100px" },
                          attrs: { src: "images/loader.gif", alt: "images" }
                        })
                      : _vm._e()
                  ])
                ]
              )
        ])
      ])
    ]
  )
}
var staticRenderFns = [
  function() {
    var _vm = this
    var _h = _vm.$createElement
    var _c = _vm._self._c || _h
    return _c("div", { staticClass: "user-option" }, [
      _c("div", { staticClass: "pull-right forgot-password" }, [
        _c("a", { attrs: { href: "#" } }, [_vm._v("Forgot password")])
      ])
    ])
  }
]
render._withStripped = true



/***/ }),

/***/ "./resources/js/views/Signin.vue":
/*!***************************************!*\
  !*** ./resources/js/views/Signin.vue ***!
  \***************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _Signin_vue_vue_type_template_id_f914eefe___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./Signin.vue?vue&type=template&id=f914eefe& */ "./resources/js/views/Signin.vue?vue&type=template&id=f914eefe&");
/* harmony import */ var _Signin_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ./Signin.vue?vue&type=script&lang=js& */ "./resources/js/views/Signin.vue?vue&type=script&lang=js&");
/* empty/unused harmony star reexport *//* harmony import */ var _node_modules_vue_loader_lib_runtime_componentNormalizer_js__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ../../../node_modules/vue-loader/lib/runtime/componentNormalizer.js */ "./node_modules/vue-loader/lib/runtime/componentNormalizer.js");





/* normalize component */

var component = Object(_node_modules_vue_loader_lib_runtime_componentNormalizer_js__WEBPACK_IMPORTED_MODULE_2__["default"])(
  _Signin_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_1__["default"],
  _Signin_vue_vue_type_template_id_f914eefe___WEBPACK_IMPORTED_MODULE_0__["render"],
  _Signin_vue_vue_type_template_id_f914eefe___WEBPACK_IMPORTED_MODULE_0__["staticRenderFns"],
  false,
  null,
  null,
  null
  
)

/* hot reload */
if (false) { var api; }
component.options.__file = "resources/js/views/Signin.vue"
/* harmony default export */ __webpack_exports__["default"] = (component.exports);

/***/ }),

/***/ "./resources/js/views/Signin.vue?vue&type=script&lang=js&":
/*!****************************************************************!*\
  !*** ./resources/js/views/Signin.vue?vue&type=script&lang=js& ***!
  \****************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _node_modules_babel_loader_lib_index_js_ref_4_0_node_modules_vue_loader_lib_index_js_vue_loader_options_Signin_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../node_modules/babel-loader/lib??ref--4-0!../../../node_modules/vue-loader/lib??vue-loader-options!./Signin.vue?vue&type=script&lang=js& */ "./node_modules/babel-loader/lib/index.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/views/Signin.vue?vue&type=script&lang=js&");
/* empty/unused harmony star reexport */ /* harmony default export */ __webpack_exports__["default"] = (_node_modules_babel_loader_lib_index_js_ref_4_0_node_modules_vue_loader_lib_index_js_vue_loader_options_Signin_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__["default"]); 

/***/ }),

/***/ "./resources/js/views/Signin.vue?vue&type=template&id=f914eefe&":
/*!**********************************************************************!*\
  !*** ./resources/js/views/Signin.vue?vue&type=template&id=f914eefe& ***!
  \**********************************************************************/
/*! exports provided: render, staticRenderFns */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_Signin_vue_vue_type_template_id_f914eefe___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!../../../node_modules/vue-loader/lib??vue-loader-options!./Signin.vue?vue&type=template&id=f914eefe& */ "./node_modules/vue-loader/lib/loaders/templateLoader.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/views/Signin.vue?vue&type=template&id=f914eefe&");
/* harmony reexport (safe) */ __webpack_require__.d(__webpack_exports__, "render", function() { return _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_Signin_vue_vue_type_template_id_f914eefe___WEBPACK_IMPORTED_MODULE_0__["render"]; });

/* harmony reexport (safe) */ __webpack_require__.d(__webpack_exports__, "staticRenderFns", function() { return _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_Signin_vue_vue_type_template_id_f914eefe___WEBPACK_IMPORTED_MODULE_0__["staticRenderFns"]; });



/***/ })

}]);