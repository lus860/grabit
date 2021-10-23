(window["webpackJsonp"] = window["webpackJsonp"] || []).push([[0],{

/***/ "./node_modules/babel-loader/lib/index.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/views/Home.vue?vue&type=script&lang=js&":
/*!**********************************************************************************************************************************************************!*\
  !*** ./node_modules/babel-loader/lib??ref--4-0!./node_modules/vue-loader/lib??vue-loader-options!./resources/js/views/Home.vue?vue&type=script&lang=js& ***!
  \**********************************************************************************************************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _modules_vendor_types__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ../modules/vendor_types */ "./resources/js/modules/vendor_types.js");
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
  name: "Home",
  data: function data() {
    return {
      vendorTypes: '',
      activeArea: '',
      disableServices: [],
      category: ''
    };
  },
  computed: {
    allVendorTypes: function allVendorTypes() {
      var vendor_types = this.$store.getters.allVendorTypes;
      var vendor_types_for_show = this.$store.getters.allVendorTypesForShow;

      for (var index in vendor_types) {
        if (vendor_types_for_show[index] === undefined) {
          this.disableServices[index] = 'disabled-service';
        } else {
          this.disableServices[index] = '';
        }
      }

      return vendor_types;
    }
  },
  mounted: function mounted() {
    this.$store.dispatch('fetchVendorTypes');
  },
  methods: {
    getVendors: function getVendors(area_id, that) {
      this.$store.dispatch('fetchVendorsTypeForArea', area_id);
      this.handleShowSelectAreas = false;
      this.activeArea = '';
      this.selectedArea = that.innerText;
    }
  }
});

/***/ }),

/***/ "./node_modules/css-loader/index.js?!./node_modules/vue-loader/lib/loaders/stylePostLoader.js!./node_modules/postcss-loader/src/index.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/views/Home.vue?vue&type=style&index=0&id=63cd6604&scoped=true&lang=css&":
/*!*****************************************************************************************************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/css-loader??ref--6-1!./node_modules/vue-loader/lib/loaders/stylePostLoader.js!./node_modules/postcss-loader/src??ref--6-2!./node_modules/vue-loader/lib??vue-loader-options!./resources/js/views/Home.vue?vue&type=style&index=0&id=63cd6604&scoped=true&lang=css& ***!
  \*****************************************************************************************************************************************************************************************************************************************************************************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {

exports = module.exports = __webpack_require__(/*! ../../../node_modules/css-loader/lib/css-base.js */ "./node_modules/css-loader/lib/css-base.js")(false);
// imports


// module
exports.push([module.i, "\n.title[data-v-63cd6604]{\n    color: #4a4a4a;\n}\n.color-white[data-v-63cd6604]{\n    color: white;\n}\n.font-size-4[data-v-63cd6604]{\n    font-size: 1rem;\n}\n.my-btn-white[data-v-63cd6604]{\n    border: 1px solid white;\n}\n.img-order[data-v-63cd6604]{\n    position: absolute;\n    width: 675px;\n    right: 0%;\n    bottom: -4.1rem;\n}\n.img-width-80[data-v-63cd6604]{\n    height: auto;\n    width: 80%;\n}\n.btn[data-v-63cd6604]:focus {\n    box-shadow: none;\n}\n\n", ""]);

// exports


/***/ }),

/***/ "./node_modules/style-loader/index.js!./node_modules/css-loader/index.js?!./node_modules/vue-loader/lib/loaders/stylePostLoader.js!./node_modules/postcss-loader/src/index.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/views/Home.vue?vue&type=style&index=0&id=63cd6604&scoped=true&lang=css&":
/*!*********************************************************************************************************************************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/style-loader!./node_modules/css-loader??ref--6-1!./node_modules/vue-loader/lib/loaders/stylePostLoader.js!./node_modules/postcss-loader/src??ref--6-2!./node_modules/vue-loader/lib??vue-loader-options!./resources/js/views/Home.vue?vue&type=style&index=0&id=63cd6604&scoped=true&lang=css& ***!
  \*********************************************************************************************************************************************************************************************************************************************************************************************************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {


var content = __webpack_require__(/*! !../../../node_modules/css-loader??ref--6-1!../../../node_modules/vue-loader/lib/loaders/stylePostLoader.js!../../../node_modules/postcss-loader/src??ref--6-2!../../../node_modules/vue-loader/lib??vue-loader-options!./Home.vue?vue&type=style&index=0&id=63cd6604&scoped=true&lang=css& */ "./node_modules/css-loader/index.js?!./node_modules/vue-loader/lib/loaders/stylePostLoader.js!./node_modules/postcss-loader/src/index.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/views/Home.vue?vue&type=style&index=0&id=63cd6604&scoped=true&lang=css&");

if(typeof content === 'string') content = [[module.i, content, '']];

var transform;
var insertInto;



var options = {"hmr":true}

options.transform = transform
options.insertInto = undefined;

var update = __webpack_require__(/*! ../../../node_modules/style-loader/lib/addStyles.js */ "./node_modules/style-loader/lib/addStyles.js")(content, options);

if(content.locals) module.exports = content.locals;

if(false) {}

/***/ }),

/***/ "./node_modules/vue-loader/lib/loaders/templateLoader.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/views/Home.vue?vue&type=template&id=63cd6604&scoped=true&":
/*!**************************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!./node_modules/vue-loader/lib??vue-loader-options!./resources/js/views/Home.vue?vue&type=template&id=63cd6604&scoped=true& ***!
  \**************************************************************************************************************************************************************************************************************/
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
    { staticClass: "clearfix home-default", attrs: { id: "main" } },
    [
      _c("div", { staticClass: "container" }, [
        _c("div", { staticClass: "banner-section text-center" }, [
          _c("h1", { staticClass: "title" }, [
            _vm._v(
              "Buy & Sell Cars, Properties, Electronics, Services, Hiring - Tanzania’s largest classified ads platform"
            )
          ]),
          _vm._v(" "),
          _c("h3", [
            _vm._v("Search from over thousands of adverts & post for free!")
          ]),
          _vm._v(" "),
          _c("div", { staticClass: "banner-form" }, [
            _c("form", { attrs: { action: "#" } }, [
              _c(
                "div",
                { staticClass: "dropdown category-dropdown" },
                [
                  _c(
                    "router-link",
                    { attrs: { "data-toggle": "dropdown", to: "#" } },
                    [
                      !_vm.category
                        ? _c("span", { staticClass: "change-text" }, [
                            _vm._v("Select Category")
                          ])
                        : _c("span", { staticClass: "change-text" }, [
                            _vm._v(_vm._s(_vm.category))
                          ]),
                      _vm._v(" "),
                      _c("i", { staticClass: "fa fa-angle-down" })
                    ]
                  ),
                  _vm._v(" "),
                  _c(
                    "ul",
                    { staticClass: "dropdown-menu category-change" },
                    [
                      _c(
                        "li",
                        [
                          _c("router-link", { attrs: { to: "#" } }, [
                            _vm._v("Select Category")
                          ])
                        ],
                        1
                      ),
                      _vm._v(" "),
                      _vm._l(_vm.allVendorTypes, function(vendor_type, index) {
                        return _c(
                          "li",
                          {
                            on: {
                              click: function($event) {
                                _vm.category = vendor_type.name
                              }
                            }
                          },
                          [
                            _c("router-link", { attrs: { to: "#" } }, [
                              _vm._v(_vm._s(vendor_type.name))
                            ])
                          ],
                          1
                        )
                      })
                    ],
                    2
                  )
                ],
                1
              ),
              _vm._v(" "),
              _c("input", {
                staticClass: "form-control",
                attrs: {
                  type: "text",
                  placeholder: "What are you looking for?"
                }
              }),
              _vm._v(" "),
              _c(
                "button",
                {
                  staticClass: "form-control",
                  attrs: { type: "submit", value: "Search" }
                },
                [_vm._v("Search")]
              )
            ])
          ]),
          _vm._v(" "),
          _c("ul", { staticClass: "banner-socail list-inline" }, [
            _c(
              "li",
              [
                _c("router-link", { attrs: { to: "#", title: "Facebook" } }, [
                  _c("i", { staticClass: "fa fa-facebook" })
                ])
              ],
              1
            ),
            _vm._v(" "),
            _c(
              "li",
              [
                _c("router-link", { attrs: { to: "#", title: "Twitter" } }, [
                  _c("i", { staticClass: "fa fa-twitter" })
                ])
              ],
              1
            ),
            _vm._v(" "),
            _c(
              "li",
              [
                _c(
                  "router-link",
                  { attrs: { to: "#", title: "Google Plus" } },
                  [_c("i", { staticClass: "fa fa-google-plus" })]
                )
              ],
              1
            ),
            _vm._v(" "),
            _c(
              "li",
              [
                _c("router-link", { attrs: { to: "#", title: "Youtube" } }, [
                  _c("i", { staticClass: "fa fa-youtube" })
                ])
              ],
              1
            )
          ])
        ]),
        _vm._v(" "),
        _c("div", { staticClass: "main-content" }, [
          _c("div", { staticClass: "row justify-content-md-center" }, [
            _c("div", { staticClass: "col-md-8" }, [
              _c("div", { staticClass: "section category-ad text-center" }, [
                _c("ul", { staticClass: "category-list" }, [
                  _c(
                    "div",
                    { staticClass: "row" },
                    _vm._l(_vm.allVendorTypes, function(vendor_type, index) {
                      return _c(
                        "li",
                        { staticClass: "category-item col-md-3" },
                        [
                          _c(
                            "div",
                            { staticClass: "w-50 mx-auto" },
                            [
                              _c(
                                "router-link",
                                { attrs: { to: "categories.html" } },
                                [
                                  _c("div", { staticClass: "category-icon" }, [
                                    _c("img", {
                                      staticClass: "img-fluid",
                                      attrs: {
                                        src: vendor_type.image,
                                        alt: "images"
                                      }
                                    })
                                  ]),
                                  _vm._v(" "),
                                  _c(
                                    "span",
                                    { staticClass: "category-title" },
                                    [_vm._v(_vm._s(vendor_type.name))]
                                  )
                                ]
                              )
                            ],
                            1
                          )
                        ]
                      )
                    }),
                    0
                  )
                ])
              ]),
              _vm._v(" "),
              _vm._m(0)
            ]),
            _vm._v(" "),
            _vm._m(1)
          ])
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
    return _c("div", { staticClass: "section featureds" }, [
      _c("div", { staticClass: "row" }, [
        _c("div", { staticClass: "col-sm-12" }, [
          _c("div", { staticClass: "section-title featured-top" }, [
            _c("h4", [_vm._v("Featured Ads")])
          ])
        ])
      ]),
      _vm._v(" "),
      _c("div", { staticClass: "featured-slider" }, [
        _c("div", { staticClass: "row", attrs: { id: "featured-slider" } }, [
          _c("div", { staticClass: "col-md-4" }, [
            _c("div", { staticClass: "featured" }, [
              _c("div", { staticClass: "featured-image" }, [
                _c("a", { attrs: { href: "details.html" } }, [
                  _c("img", {
                    staticClass: "img-fluid",
                    attrs: { src: "images/featured/1.jpg", alt: "" }
                  })
                ]),
                _vm._v(" "),
                _c(
                  "a",
                  {
                    staticClass: "verified",
                    attrs: {
                      href: "#",
                      "data-toggle": "tooltip",
                      "data-placement": "left",
                      title: "Verified"
                    }
                  },
                  [_c("i", { staticClass: "fa fa-check-square-o" })]
                )
              ]),
              _vm._v(" "),
              _c("div", { staticClass: "ad-info" }, [
                _c("h3", { staticClass: "item-price" }, [_vm._v("$800.00")]),
                _vm._v(" "),
                _c("h4", { staticClass: "item-title" }, [
                  _c("a", { attrs: { href: "#" } }, [
                    _vm._v(
                      "Apple MacBook Pro with Retina\n                                                Display"
                    )
                  ])
                ]),
                _vm._v(" "),
                _c("div", { staticClass: "item-cat" }, [
                  _c("span", [
                    _c("a", { attrs: { href: "#" } }, [
                      _vm._v("Electronics & Gedgets")
                    ])
                  ])
                ])
              ]),
              _vm._v(" "),
              _c("div", { staticClass: "ad-meta" }, [
                _c("div", { staticClass: "meta-content" }, [
                  _c("span", { staticClass: "dated" }, [
                    _c("a", { attrs: { href: "#" } }, [
                      _vm._v("7 Jan 10:10 pm ")
                    ])
                  ])
                ]),
                _vm._v(" "),
                _c("div", { staticClass: "user-option pull-right" }, [
                  _c(
                    "a",
                    {
                      attrs: {
                        href: "#",
                        "data-toggle": "tooltip",
                        "data-placement": "top",
                        title: "Los Angeles, USA"
                      }
                    },
                    [_c("i", { staticClass: "fa fa-map-marker" })]
                  ),
                  _vm._v(" "),
                  _c(
                    "a",
                    {
                      attrs: {
                        href: "#",
                        "data-toggle": "tooltip",
                        "data-placement": "top",
                        title: "Dealer"
                      }
                    },
                    [_c("i", { staticClass: "fa fa-suitcase" })]
                  )
                ])
              ])
            ])
          ]),
          _vm._v(" "),
          _c("div", { staticClass: "col-md-4" }, [
            _c("div", { staticClass: "featured" }, [
              _c("div", { staticClass: "featured-image" }, [
                _c("a", { attrs: { href: "details.html" } }, [
                  _c("img", {
                    staticClass: "img-fluid",
                    attrs: { src: "images/featured/2.jpg", alt: "" }
                  })
                ])
              ]),
              _vm._v(" "),
              _c("div", { staticClass: "ad-info" }, [
                _c("h3", { staticClass: "item-price" }, [_vm._v("$25000.00")]),
                _vm._v(" "),
                _c("h4", { staticClass: "item-title" }, [
                  _c("a", { attrs: { href: "#" } }, [
                    _vm._v(
                      "2018 Bugatti Veyron Sport\n                                                Middlecar"
                    )
                  ])
                ]),
                _vm._v(" "),
                _c("div", { staticClass: "item-cat" }, [
                  _c("span", [
                    _c("a", { attrs: { href: "#" } }, [
                      _vm._v("Cars & Vehicles")
                    ])
                  ])
                ])
              ]),
              _vm._v(" "),
              _c("div", { staticClass: "ad-meta" }, [
                _c("div", { staticClass: "meta-content" }, [
                  _c("span", { staticClass: "dated" }, [
                    _c("a", { attrs: { href: "#" } }, [
                      _vm._v("7 Jan 10:10 pm ")
                    ])
                  ])
                ]),
                _vm._v(" "),
                _c("div", { staticClass: "user-option pull-right" }, [
                  _c(
                    "a",
                    {
                      attrs: {
                        href: "#",
                        "data-toggle": "tooltip",
                        "data-placement": "top",
                        title: "Los Angeles, USA"
                      }
                    },
                    [_c("i", { staticClass: "fa fa-map-marker" })]
                  ),
                  _vm._v(" "),
                  _c(
                    "a",
                    {
                      attrs: {
                        href: "#",
                        "data-toggle": "tooltip",
                        "data-placement": "top",
                        title: "Individual"
                      }
                    },
                    [_c("i", { staticClass: "fa fa-user" })]
                  )
                ])
              ])
            ])
          ]),
          _vm._v(" "),
          _c("div", { staticClass: "col-md-4" }, [
            _c("div", { staticClass: "featured" }, [
              _c("div", { staticClass: "featured-image" }, [
                _c("a", { attrs: { href: "details.html" } }, [
                  _c("img", {
                    staticClass: "img-fluid",
                    attrs: { src: "images/featured/3.jpg", alt: "" }
                  })
                ]),
                _vm._v(" "),
                _c(
                  "a",
                  {
                    staticClass: "verified",
                    attrs: {
                      href: "#",
                      "data-toggle": "tooltip",
                      "data-placement": "left",
                      title: "Verified"
                    }
                  },
                  [_c("i", { staticClass: "fa fa-check-square-o" })]
                )
              ]),
              _vm._v(" "),
              _c("div", { staticClass: "ad-info" }, [
                _c("h3", { staticClass: "item-price" }, [
                  _vm._v("$250.00 "),
                  _c("span", { staticClass: "negotiable" }, [
                    _vm._v("(Negotiable)")
                  ])
                ]),
                _vm._v(" "),
                _c("h4", { staticClass: "item-title" }, [
                  _c("a", { attrs: { href: "#" } }, [
                    _vm._v("Vivster Acoustic Guitar")
                  ])
                ]),
                _vm._v(" "),
                _c("div", { staticClass: "item-cat" }, [
                  _c("span", [
                    _c("a", { attrs: { href: "#" } }, [_vm._v("Music & Art")])
                  ])
                ])
              ]),
              _vm._v(" "),
              _c("div", { staticClass: "ad-meta" }, [
                _c("div", { staticClass: "meta-content" }, [
                  _c("span", { staticClass: "dated" }, [
                    _c("a", { attrs: { href: "#" } }, [
                      _vm._v("7 Jan 10:10 pm ")
                    ])
                  ])
                ]),
                _vm._v(" "),
                _c("div", { staticClass: "user-option pull-right" }, [
                  _c(
                    "a",
                    {
                      attrs: {
                        href: "#",
                        "data-toggle": "tooltip",
                        "data-placement": "top",
                        title: "Los Angeles, USA"
                      }
                    },
                    [_c("i", { staticClass: "fa fa-map-marker" })]
                  ),
                  _vm._v(" "),
                  _c(
                    "a",
                    {
                      attrs: {
                        href: "#",
                        "data-toggle": "tooltip",
                        "data-placement": "top",
                        title: "Dealer"
                      }
                    },
                    [_c("i", { staticClass: "fa fa-suitcase" })]
                  )
                ])
              ])
            ])
          ])
        ])
      ])
    ])
  },
  function() {
    var _vm = this
    var _h = _vm.$createElement
    var _c = _vm._self._c || _h
    return _c("div", { staticClass: "row justify-content-md-center" }, [
      _c("div", { staticClass: "col-md-8 text-center" }, [
        _c("div", { staticClass: "section trending-ads" }, [
          _c("div", { staticClass: "section-title tab-manu" }, [
            _c("h4", [_vm._v("Listing Ads")])
          ]),
          _vm._v(" "),
          _c("div", { staticClass: "tab-content" }, [
            _c(
              "div",
              {
                staticClass: "tab-pane fade in active show",
                attrs: { role: "tabpanel", id: "recent-ads" }
              },
              [
                _c("div", { staticClass: "ad-item row" }, [
                  _c("div", { staticClass: "item-image-box col-lg-4" }, [
                    _c("div", { staticClass: "item-image" }, [
                      _c("a", { attrs: { href: "details.html" } }, [
                        _c("img", {
                          staticClass: "img-fluid",
                          attrs: { src: "images/trending/1.jpg", alt: "Image" }
                        })
                      ]),
                      _vm._v(" "),
                      _c(
                        "a",
                        {
                          staticClass: "verified",
                          attrs: {
                            href: "#",
                            "data-toggle": "tooltip",
                            "data-placement": "left",
                            title: "Verified"
                          }
                        },
                        [_c("i", { staticClass: "fa fa-check-square-o" })]
                      )
                    ])
                  ]),
                  _vm._v(" "),
                  _c("div", { staticClass: "item-info col-lg-8" }, [
                    _c("div", { staticClass: "ad-info" }, [
                      _c("h3", { staticClass: "item-price" }, [
                        _vm._v("$50.00")
                      ]),
                      _vm._v(" "),
                      _c("h4", { staticClass: "item-title" }, [
                        _c("a", { attrs: { href: "#" } }, [
                          _vm._v(
                            "Apple TV - Everything you need to\n                                                    know!"
                          )
                        ])
                      ]),
                      _vm._v(" "),
                      _c("div", { staticClass: "item-cat" }, [
                        _c("span", [
                          _c("a", { attrs: { href: "#" } }, [
                            _vm._v("Electronics & Gedgets")
                          ])
                        ]),
                        _vm._v(
                          " /\n                                                    "
                        ),
                        _c("span", [
                          _c("a", { attrs: { href: "#" } }, [
                            _vm._v("Tv & Video")
                          ])
                        ])
                      ])
                    ]),
                    _vm._v(" "),
                    _c("div", { staticClass: "ad-meta" }, [
                      _c("div", { staticClass: "meta-content" }, [
                        _c("span", { staticClass: "dated" }, [
                          _c("a", { attrs: { href: "#" } }, [
                            _vm._v("7 Jan, 16  10:10 pm ")
                          ])
                        ]),
                        _vm._v(" "),
                        _c("a", { staticClass: "tag", attrs: { href: "#" } }, [
                          _c("i", { staticClass: "fa fa-tags" }),
                          _vm._v(" Used")
                        ])
                      ]),
                      _vm._v(" "),
                      _c("div", { staticClass: "user-option pull-right" }, [
                        _c(
                          "a",
                          {
                            attrs: {
                              href: "#",
                              "data-toggle": "tooltip",
                              "data-placement": "top",
                              title: "Los Angeles, USA"
                            }
                          },
                          [_c("i", { staticClass: "fa fa-map-marker" })]
                        ),
                        _vm._v(" "),
                        _c(
                          "a",
                          {
                            staticClass: "online",
                            attrs: {
                              href: "#",
                              "data-toggle": "tooltip",
                              "data-placement": "top",
                              title: "Dealer"
                            }
                          },
                          [_c("i", { staticClass: "fa fa-suitcase" })]
                        )
                      ])
                    ])
                  ])
                ]),
                _vm._v(" "),
                _c("div", { staticClass: "ad-item row" }, [
                  _c("div", { staticClass: "item-image-box col-lg-4" }, [
                    _c("div", { staticClass: "item-image" }, [
                      _c("a", { attrs: { href: "details.html" } }, [
                        _c("img", {
                          staticClass: "img-fluid",
                          attrs: { src: "images/trending/2.jpg", alt: "Image" }
                        })
                      ])
                    ])
                  ]),
                  _vm._v(" "),
                  _c("div", { staticClass: "item-info col-lg-8" }, [
                    _c("div", { staticClass: "ad-info" }, [
                      _c("h3", { staticClass: "item-price" }, [
                        _vm._v("$250.00 "),
                        _c("span", [_vm._v("(Negotiable)")])
                      ]),
                      _vm._v(" "),
                      _c("h4", { staticClass: "item-title" }, [
                        _c("a", { attrs: { href: "#" } }, [
                          _vm._v(
                            "Bark Furniture, Handmade Bespoke\n                                                    Furniture"
                          )
                        ])
                      ]),
                      _vm._v(" "),
                      _c("div", { staticClass: "item-cat" }, [
                        _c("span", [
                          _c("a", { attrs: { href: "#" } }, [
                            _vm._v("Home Appliances")
                          ])
                        ]),
                        _vm._v(
                          " /\n                                                    "
                        ),
                        _c("span", [
                          _c("a", { attrs: { href: "#" } }, [_vm._v("Sofa")])
                        ])
                      ])
                    ]),
                    _vm._v(" "),
                    _c("div", { staticClass: "ad-meta" }, [
                      _c("div", { staticClass: "meta-content" }, [
                        _c("span", { staticClass: "dated" }, [
                          _c("a", { attrs: { href: "#" } }, [
                            _vm._v("7 Jan, 16  10:10 pm ")
                          ])
                        ]),
                        _vm._v(" "),
                        _c("a", { staticClass: "tag", attrs: { href: "#" } }, [
                          _c("i", { staticClass: "fa fa-tags" }),
                          _vm._v(" Used")
                        ])
                      ]),
                      _vm._v(" "),
                      _c("div", { staticClass: "user-option pull-right" }, [
                        _c(
                          "a",
                          {
                            attrs: {
                              href: "#",
                              "data-toggle": "tooltip",
                              "data-placement": "top",
                              title: "Los Angeles, USA"
                            }
                          },
                          [_c("i", { staticClass: "fa fa-map-marker" })]
                        ),
                        _vm._v(" "),
                        _c(
                          "a",
                          {
                            staticClass: "online",
                            attrs: {
                              href: "#",
                              "data-toggle": "tooltip",
                              "data-placement": "top",
                              title: "Dealer"
                            }
                          },
                          [_c("i", { staticClass: "fa fa-suitcase" })]
                        )
                      ])
                    ])
                  ])
                ]),
                _vm._v(" "),
                _c("div", { staticClass: "ad-item row" }, [
                  _c("div", { staticClass: "item-image-box col-lg-4" }, [
                    _c("div", { staticClass: "item-image" }, [
                      _c("a", { attrs: { href: "details.html" } }, [
                        _c("img", {
                          staticClass: "img-fluid",
                          attrs: { src: "images/trending/4.jpg", alt: "Image" }
                        })
                      ]),
                      _vm._v(" "),
                      _c(
                        "a",
                        {
                          staticClass: "verified",
                          attrs: {
                            href: "#",
                            "data-toggle": "tooltip",
                            "data-placement": "left",
                            title: "Verified"
                          }
                        },
                        [_c("i", { staticClass: "fa fa-check-square-o" })]
                      )
                    ])
                  ]),
                  _vm._v(" "),
                  _c("div", { staticClass: "item-info col-lg-8" }, [
                    _c("div", { staticClass: "ad-info" }, [
                      _c("h3", { staticClass: "item-price" }, [
                        _vm._v("$800.00")
                      ]),
                      _vm._v(" "),
                      _c("h4", { staticClass: "item-title" }, [
                        _c("a", { attrs: { href: "#" } }, [
                          _vm._v("Rick Morton- Magicius Chase")
                        ])
                      ]),
                      _vm._v(" "),
                      _c("div", { staticClass: "item-cat" }, [
                        _c("span", [
                          _c("a", { attrs: { href: "#" } }, [
                            _vm._v("Books & Magazines")
                          ])
                        ]),
                        _vm._v(
                          " /\n                                                    "
                        ),
                        _c("span", [
                          _c("a", { attrs: { href: "#" } }, [
                            _vm._v("Story book")
                          ])
                        ])
                      ])
                    ]),
                    _vm._v(" "),
                    _c("div", { staticClass: "ad-meta" }, [
                      _c("div", { staticClass: "meta-content" }, [
                        _c("span", { staticClass: "dated" }, [
                          _c("a", { attrs: { href: "#" } }, [
                            _vm._v("7 Jan, 16  10:10 pm ")
                          ])
                        ]),
                        _vm._v(" "),
                        _c("a", { staticClass: "tag", attrs: { href: "#" } }, [
                          _c("i", { staticClass: "fa fa-tags" }),
                          _vm._v(" Used")
                        ])
                      ]),
                      _vm._v(" "),
                      _c("div", { staticClass: "user-option pull-right" }, [
                        _c(
                          "a",
                          {
                            attrs: {
                              href: "#",
                              "data-toggle": "tooltip",
                              "data-placement": "top",
                              title: "Los Angeles, USA"
                            }
                          },
                          [_c("i", { staticClass: "fa fa-map-marker" })]
                        ),
                        _vm._v(" "),
                        _c(
                          "a",
                          {
                            attrs: {
                              href: "#",
                              "data-toggle": "tooltip",
                              "data-placement": "top",
                              title: "Individual"
                            }
                          },
                          [_c("i", { staticClass: "fa fa-user" })]
                        )
                      ])
                    ])
                  ])
                ])
              ]
            ),
            _vm._v(" "),
            _c(
              "div",
              {
                staticClass: "tab-pane fade",
                attrs: { role: "tabpanel", id: "popular" }
              },
              [
                _c("div", { staticClass: "ad-item row" }, [
                  _c("div", { staticClass: "item-image-box col-lg-4" }, [
                    _c("div", { staticClass: "item-image" }, [
                      _c("a", { attrs: { href: "details.html" } }, [
                        _c("img", {
                          staticClass: "img-fluid",
                          attrs: { src: "images/trending/1.jpg", alt: "Image" }
                        })
                      ]),
                      _vm._v(" "),
                      _c(
                        "a",
                        {
                          staticClass: "verified",
                          attrs: {
                            href: "#",
                            "data-toggle": "tooltip",
                            "data-placement": "left",
                            title: "Verified"
                          }
                        },
                        [_c("i", { staticClass: "fa fa-check-square-o" })]
                      )
                    ])
                  ]),
                  _vm._v(" "),
                  _c("div", { staticClass: "item-info col-lg-8" }, [
                    _c("div", { staticClass: "ad-info" }, [
                      _c("h3", { staticClass: "item-price" }, [
                        _vm._v("$50.00")
                      ]),
                      _vm._v(" "),
                      _c("h4", { staticClass: "item-title" }, [
                        _c("a", { attrs: { href: "#" } }, [
                          _vm._v(
                            "Apple TV - Everything you need to\n                                                    know!"
                          )
                        ])
                      ]),
                      _vm._v(" "),
                      _c("div", { staticClass: "item-cat" }, [
                        _c("span", [
                          _c("a", { attrs: { href: "#" } }, [
                            _vm._v("Electronics & Gedgets")
                          ])
                        ]),
                        _vm._v(
                          " /\n                                                    "
                        ),
                        _c("span", [
                          _c("a", { attrs: { href: "#" } }, [
                            _vm._v("Tv & Video")
                          ])
                        ])
                      ])
                    ]),
                    _vm._v(" "),
                    _c("div", { staticClass: "ad-meta" }, [
                      _c("div", { staticClass: "meta-content" }, [
                        _c("span", { staticClass: "dated" }, [
                          _c("a", { attrs: { href: "#" } }, [
                            _vm._v("7 Jan, 16  10:10 pm ")
                          ])
                        ]),
                        _vm._v(" "),
                        _c("a", { staticClass: "tag", attrs: { href: "#" } }, [
                          _c("i", { staticClass: "fa fa-tags" }),
                          _vm._v(" Used")
                        ])
                      ]),
                      _vm._v(" "),
                      _c("div", { staticClass: "user-option pull-right" }, [
                        _c(
                          "a",
                          {
                            attrs: {
                              href: "#",
                              "data-toggle": "tooltip",
                              "data-placement": "top",
                              title: "Los Angeles, USA"
                            }
                          },
                          [_c("i", { staticClass: "fa fa-map-marker" })]
                        ),
                        _vm._v(" "),
                        _c(
                          "a",
                          {
                            staticClass: "online",
                            attrs: {
                              href: "#",
                              "data-toggle": "tooltip",
                              "data-placement": "top",
                              title: "Dealer"
                            }
                          },
                          [_c("i", { staticClass: "fa fa-suitcase" })]
                        )
                      ])
                    ])
                  ])
                ]),
                _vm._v(" "),
                _c("div", { staticClass: "ad-item row" }, [
                  _c("div", { staticClass: "item-image-box col-lg-4" }, [
                    _c("div", { staticClass: "item-image" }, [
                      _c("a", { attrs: { href: "details.html" } }, [
                        _c("img", {
                          staticClass: "img-fluid",
                          attrs: { src: "images/trending/3.jpg", alt: "Image" }
                        })
                      ])
                    ])
                  ]),
                  _vm._v(" "),
                  _c("div", { staticClass: "item-info col-lg-8" }, [
                    _c("div", { staticClass: "ad-info" }, [
                      _c("h3", { staticClass: "item-price" }, [
                        _vm._v("$890.00 "),
                        _c("span", [_vm._v("(Negotiable)")])
                      ]),
                      _vm._v(" "),
                      _c("h4", { staticClass: "item-title" }, [
                        _c("a", { attrs: { href: "#" } }, [
                          _vm._v("Samsung Galaxy S6 Edge")
                        ])
                      ]),
                      _vm._v(" "),
                      _c("div", { staticClass: "item-cat" }, [
                        _c("span", [
                          _c("a", { attrs: { href: "#" } }, [
                            _vm._v("Electronics & Gedgets")
                          ])
                        ]),
                        _vm._v(
                          " /\n                                                    "
                        ),
                        _c("span", [
                          _c("a", { attrs: { href: "#" } }, [
                            _vm._v("Mobile Phone")
                          ])
                        ])
                      ])
                    ]),
                    _vm._v(" "),
                    _c("div", { staticClass: "ad-meta" }, [
                      _c("div", { staticClass: "meta-content" }, [
                        _c("span", { staticClass: "dated" }, [
                          _c("a", { attrs: { href: "#" } }, [
                            _vm._v("7 Jan, 16  10:10 pm ")
                          ])
                        ]),
                        _vm._v(" "),
                        _c("a", { staticClass: "tag", attrs: { href: "#" } }, [
                          _c("i", { staticClass: "fa fa-tags" }),
                          _vm._v(" Used")
                        ])
                      ]),
                      _vm._v(" "),
                      _c("div", { staticClass: "user-option pull-right" }, [
                        _c(
                          "a",
                          {
                            attrs: {
                              href: "#",
                              "data-toggle": "tooltip",
                              "data-placement": "top",
                              title: "Los Angeles, USA"
                            }
                          },
                          [_c("i", { staticClass: "fa fa-map-marker" })]
                        ),
                        _vm._v(" "),
                        _c(
                          "a",
                          {
                            attrs: {
                              href: "#",
                              "data-toggle": "tooltip",
                              "data-placement": "top",
                              title: "Dealer"
                            }
                          },
                          [_c("i", { staticClass: "fa fa-suitcase" })]
                        )
                      ])
                    ])
                  ])
                ]),
                _vm._v(" "),
                _c("div", { staticClass: "ad-item row" }, [
                  _c("div", { staticClass: "item-image-box col-lg-4" }, [
                    _c("div", { staticClass: "item-image" }, [
                      _c("a", { attrs: { href: "details.html" } }, [
                        _c("img", {
                          staticClass: "img-fluid",
                          attrs: { src: "images/trending/2.jpg", alt: "Image" }
                        })
                      ])
                    ])
                  ]),
                  _vm._v(" "),
                  _c("div", { staticClass: "item-info col-lg-8" }, [
                    _c("div", { staticClass: "ad-info" }, [
                      _c("h3", { staticClass: "item-price" }, [
                        _vm._v("$250.00 "),
                        _c("span", [_vm._v("(Negotiable)")])
                      ]),
                      _vm._v(" "),
                      _c("h4", { staticClass: "item-title" }, [
                        _c("a", { attrs: { href: "#" } }, [
                          _vm._v(
                            "Bark Furniture, Handmade Bespoke\n                                                    Furniture"
                          )
                        ])
                      ]),
                      _vm._v(" "),
                      _c("div", { staticClass: "item-cat" }, [
                        _c("span", [
                          _c("a", { attrs: { href: "#" } }, [
                            _vm._v("Home Appliances")
                          ])
                        ]),
                        _vm._v(
                          " /\n                                                    "
                        ),
                        _c("span", [
                          _c("a", { attrs: { href: "#" } }, [_vm._v("Sofa")])
                        ])
                      ])
                    ]),
                    _vm._v(" "),
                    _c("div", { staticClass: "ad-meta" }, [
                      _c("div", { staticClass: "meta-content" }, [
                        _c("span", { staticClass: "dated" }, [
                          _c("a", { attrs: { href: "#" } }, [
                            _vm._v("7 Jan, 16  10:10 pm ")
                          ])
                        ]),
                        _vm._v(" "),
                        _c("a", { staticClass: "tag", attrs: { href: "#" } }, [
                          _c("i", { staticClass: "fa fa-tags" }),
                          _vm._v(" Used")
                        ])
                      ]),
                      _vm._v(" "),
                      _c("div", { staticClass: "user-option pull-right" }, [
                        _c(
                          "a",
                          {
                            attrs: {
                              href: "#",
                              "data-toggle": "tooltip",
                              "data-placement": "top",
                              title: "Los Angeles, USA"
                            }
                          },
                          [_c("i", { staticClass: "fa fa-map-marker" })]
                        ),
                        _vm._v(" "),
                        _c(
                          "a",
                          {
                            staticClass: "online",
                            attrs: {
                              href: "#",
                              "data-toggle": "tooltip",
                              "data-placement": "top",
                              title: "Dealer"
                            }
                          },
                          [_c("i", { staticClass: "fa fa-suitcase" })]
                        )
                      ])
                    ])
                  ])
                ]),
                _vm._v(" "),
                _c("div", { staticClass: "ad-item row" }, [
                  _c("div", { staticClass: "item-image-box col-lg-4" }, [
                    _c("div", { staticClass: "item-image" }, [
                      _c("a", { attrs: { href: "details.html" } }, [
                        _c("img", {
                          staticClass: "img-fluid",
                          attrs: { src: "images/trending/4.jpg", alt: "Image" }
                        })
                      ]),
                      _vm._v(" "),
                      _c(
                        "a",
                        {
                          staticClass: "verified",
                          attrs: {
                            href: "#",
                            "data-toggle": "tooltip",
                            "data-placement": "left",
                            title: "Verified"
                          }
                        },
                        [_c("i", { staticClass: "fa fa-check-square-o" })]
                      )
                    ])
                  ]),
                  _vm._v(" "),
                  _c("div", { staticClass: "item-info col-lg-8" }, [
                    _c("div", { staticClass: "ad-info" }, [
                      _c("h3", { staticClass: "item-price" }, [
                        _vm._v("$800.00")
                      ]),
                      _vm._v(" "),
                      _c("h4", { staticClass: "item-title" }, [
                        _c("a", { attrs: { href: "#" } }, [
                          _vm._v("Rick Morton- Magicius Chase")
                        ])
                      ]),
                      _vm._v(" "),
                      _c("div", { staticClass: "item-cat" }, [
                        _c("span", [
                          _c("a", { attrs: { href: "#" } }, [
                            _vm._v("Books & Magazines")
                          ])
                        ]),
                        _vm._v(
                          " /\n                                                    "
                        ),
                        _c("span", [
                          _c("a", { attrs: { href: "#" } }, [
                            _vm._v("Story book")
                          ])
                        ])
                      ])
                    ]),
                    _vm._v(" "),
                    _c("div", { staticClass: "ad-meta" }, [
                      _c("div", { staticClass: "meta-content" }, [
                        _c("span", { staticClass: "dated" }, [
                          _c("a", { attrs: { href: "#" } }, [
                            _vm._v("7 Jan, 16  10:10 pm ")
                          ])
                        ]),
                        _vm._v(" "),
                        _c("a", { staticClass: "tag", attrs: { href: "#" } }, [
                          _c("i", { staticClass: "fa fa-tags" }),
                          _vm._v(" Used")
                        ])
                      ]),
                      _vm._v(" "),
                      _c("div", { staticClass: "user-option pull-right" }, [
                        _c(
                          "a",
                          {
                            attrs: {
                              href: "#",
                              "data-toggle": "tooltip",
                              "data-placement": "top",
                              title: "Los Angeles, USA"
                            }
                          },
                          [_c("i", { staticClass: "fa fa-map-marker" })]
                        ),
                        _vm._v(" "),
                        _c(
                          "a",
                          {
                            attrs: {
                              href: "#",
                              "data-toggle": "tooltip",
                              "data-placement": "top",
                              title: "Individual"
                            }
                          },
                          [_c("i", { staticClass: "fa fa-user" })]
                        )
                      ])
                    ])
                  ])
                ])
              ]
            ),
            _vm._v(" "),
            _c(
              "div",
              {
                staticClass: "tab-pane fade",
                attrs: { role: "tabpanel", id: "hot-ads" }
              },
              [
                _c("div", { staticClass: "ad-item row" }, [
                  _c("div", { staticClass: "item-image-box col-lg-4" }, [
                    _c("div", { staticClass: "item-image" }, [
                      _c("a", { attrs: { href: "details.html" } }, [
                        _c("img", {
                          staticClass: "img-fluid",
                          attrs: { src: "images/trending/1.jpg", alt: "Image" }
                        })
                      ]),
                      _vm._v(" "),
                      _c(
                        "a",
                        {
                          staticClass: "verified",
                          attrs: {
                            href: "#",
                            "data-toggle": "tooltip",
                            "data-placement": "left",
                            title: "Verified"
                          }
                        },
                        [_c("i", { staticClass: "fa fa-check-square-o" })]
                      )
                    ])
                  ]),
                  _vm._v(" "),
                  _c("div", { staticClass: "item-info col-lg-8" }, [
                    _c("div", { staticClass: "ad-info" }, [
                      _c("h3", { staticClass: "item-price" }, [
                        _vm._v("$50.00")
                      ]),
                      _vm._v(" "),
                      _c("h4", { staticClass: "item-title" }, [
                        _c("a", { attrs: { href: "#" } }, [
                          _vm._v(
                            "Apple TV - Everything you need to\n                                                    know!"
                          )
                        ])
                      ]),
                      _vm._v(" "),
                      _c("div", { staticClass: "item-cat" }, [
                        _c("span", [
                          _c("a", { attrs: { href: "#" } }, [
                            _vm._v("Electronics & Gedgets")
                          ])
                        ]),
                        _vm._v(
                          " /\n                                                    "
                        ),
                        _c("span", [
                          _c("a", { attrs: { href: "#" } }, [
                            _vm._v("Tv & Video")
                          ])
                        ])
                      ])
                    ]),
                    _vm._v(" "),
                    _c("div", { staticClass: "ad-meta" }, [
                      _c("div", { staticClass: "meta-content" }, [
                        _c("span", { staticClass: "dated" }, [
                          _c("a", { attrs: { href: "#" } }, [
                            _vm._v("7 Jan, 16  10:10 pm ")
                          ])
                        ]),
                        _vm._v(" "),
                        _c("a", { staticClass: "tag", attrs: { href: "#" } }, [
                          _c("i", { staticClass: "fa fa-tags" }),
                          _vm._v(" Used")
                        ])
                      ]),
                      _vm._v(" "),
                      _c("div", { staticClass: "user-option pull-right" }, [
                        _c(
                          "a",
                          {
                            attrs: {
                              href: "#",
                              "data-toggle": "tooltip",
                              "data-placement": "top",
                              title: "Los Angeles, USA"
                            }
                          },
                          [_c("i", { staticClass: "fa fa-map-marker" })]
                        ),
                        _vm._v(" "),
                        _c(
                          "a",
                          {
                            staticClass: "online",
                            attrs: {
                              href: "#",
                              "data-toggle": "tooltip",
                              "data-placement": "top",
                              title: "Dealer"
                            }
                          },
                          [_c("i", { staticClass: "fa fa-suitcase" })]
                        )
                      ])
                    ])
                  ])
                ]),
                _vm._v(" "),
                _c("div", { staticClass: "ad-item row" }, [
                  _c("div", { staticClass: "item-image-box col-lg-4" }, [
                    _c("div", { staticClass: "item-image" }, [
                      _c("a", { attrs: { href: "details.html" } }, [
                        _c("img", {
                          staticClass: "img-fluid",
                          attrs: { src: "images/trending/4.jpg", alt: "Image" }
                        })
                      ]),
                      _vm._v(" "),
                      _c(
                        "a",
                        {
                          staticClass: "verified",
                          attrs: {
                            href: "#",
                            "data-toggle": "tooltip",
                            "data-placement": "left",
                            title: "Verified"
                          }
                        },
                        [_c("i", { staticClass: "fa fa-check-square-o" })]
                      )
                    ])
                  ]),
                  _vm._v(" "),
                  _c("div", { staticClass: "item-info col-lg-8" }, [
                    _c("div", { staticClass: "ad-info" }, [
                      _c("h3", { staticClass: "item-price" }, [
                        _vm._v("$800.00")
                      ]),
                      _vm._v(" "),
                      _c("h4", { staticClass: "item-title" }, [
                        _c("a", { attrs: { href: "#" } }, [
                          _vm._v("Rick Morton- Magicius Chase")
                        ])
                      ]),
                      _vm._v(" "),
                      _c("div", { staticClass: "item-cat" }, [
                        _c("span", [
                          _c("a", { attrs: { href: "#" } }, [
                            _vm._v("Books & Magazines")
                          ])
                        ]),
                        _vm._v(
                          " /\n                                                    "
                        ),
                        _c("span", [
                          _c("a", { attrs: { href: "#" } }, [
                            _vm._v("Story book")
                          ])
                        ])
                      ])
                    ]),
                    _vm._v(" "),
                    _c("div", { staticClass: "ad-meta" }, [
                      _c("div", { staticClass: "meta-content" }, [
                        _c("span", { staticClass: "dated" }, [
                          _c("a", { attrs: { href: "#" } }, [
                            _vm._v("7 Jan, 16  10:10 pm ")
                          ])
                        ]),
                        _vm._v(" "),
                        _c("a", { staticClass: "tag", attrs: { href: "#" } }, [
                          _c("i", { staticClass: "fa fa-tags" }),
                          _vm._v(" Used")
                        ])
                      ]),
                      _vm._v(" "),
                      _c("div", { staticClass: "user-option pull-right" }, [
                        _c(
                          "a",
                          {
                            attrs: {
                              href: "#",
                              "data-toggle": "tooltip",
                              "data-placement": "top",
                              title: "Los Angeles, USA"
                            }
                          },
                          [_c("i", { staticClass: "fa fa-map-marker" })]
                        ),
                        _vm._v(" "),
                        _c(
                          "a",
                          {
                            attrs: {
                              href: "#",
                              "data-toggle": "tooltip",
                              "data-placement": "top",
                              title: "Individual"
                            }
                          },
                          [_c("i", { staticClass: "fa fa-user" })]
                        )
                      ])
                    ])
                  ])
                ]),
                _vm._v(" "),
                _c("div", { staticClass: "ad-item row" }, [
                  _c("div", { staticClass: "item-image-box col-lg-4" }, [
                    _c("div", { staticClass: "item-image" }, [
                      _c("a", { attrs: { href: "details.html" } }, [
                        _c("img", {
                          staticClass: "img-fluid",
                          attrs: { src: "images/trending/3.jpg", alt: "Image" }
                        })
                      ])
                    ])
                  ]),
                  _vm._v(" "),
                  _c("div", { staticClass: "item-info col-lg-8" }, [
                    _c("div", { staticClass: "ad-info" }, [
                      _c("h3", { staticClass: "item-price" }, [
                        _vm._v("$890.00 "),
                        _c("span", [_vm._v("(Negotiable)")])
                      ]),
                      _vm._v(" "),
                      _c("h4", { staticClass: "item-title" }, [
                        _c("a", { attrs: { href: "#" } }, [
                          _vm._v("Samsung Galaxy S6 Edge")
                        ])
                      ]),
                      _vm._v(" "),
                      _c("div", { staticClass: "item-cat" }, [
                        _c("span", [
                          _c("a", { attrs: { href: "#" } }, [
                            _vm._v("Electronics & Gedgets")
                          ])
                        ]),
                        _vm._v(
                          " /\n                                                    "
                        ),
                        _c("span", [
                          _c("a", { attrs: { href: "#" } }, [
                            _vm._v("Mobile Phone")
                          ])
                        ])
                      ])
                    ]),
                    _vm._v(" "),
                    _c("div", { staticClass: "ad-meta" }, [
                      _c("div", { staticClass: "meta-content" }, [
                        _c("span", { staticClass: "dated" }, [
                          _c("a", { attrs: { href: "#" } }, [
                            _vm._v("7 Jan, 16  10:10 pm ")
                          ])
                        ]),
                        _vm._v(" "),
                        _c("a", { staticClass: "tag", attrs: { href: "#" } }, [
                          _c("i", { staticClass: "fa fa-tags" }),
                          _vm._v(" Used")
                        ])
                      ]),
                      _vm._v(" "),
                      _c("div", { staticClass: "user-option pull-right" }, [
                        _c(
                          "a",
                          {
                            attrs: {
                              href: "#",
                              "data-toggle": "tooltip",
                              "data-placement": "top",
                              title: "Los Angeles, USA"
                            }
                          },
                          [_c("i", { staticClass: "fa fa-map-marker" })]
                        ),
                        _vm._v(" "),
                        _c(
                          "a",
                          {
                            attrs: {
                              href: "#",
                              "data-toggle": "tooltip",
                              "data-placement": "top",
                              title: "Dealer"
                            }
                          },
                          [_c("i", { staticClass: "fa fa-suitcase" })]
                        )
                      ])
                    ])
                  ])
                ]),
                _vm._v(" "),
                _c("div", { staticClass: "ad-item row" }, [
                  _c("div", { staticClass: "item-image-box col-lg-4" }, [
                    _c("div", { staticClass: "item-image" }, [
                      _c("a", { attrs: { href: "details.html" } }, [
                        _c("img", {
                          staticClass: "img-fluid",
                          attrs: { src: "images/trending/2.jpg", alt: "Image" }
                        })
                      ])
                    ])
                  ]),
                  _vm._v(" "),
                  _c("div", { staticClass: "item-info col-lg-8" }, [
                    _c("div", { staticClass: "ad-info" }, [
                      _c("h3", { staticClass: "item-price" }, [
                        _vm._v("$250.00 "),
                        _c("span", [_vm._v("(Negotiable)")])
                      ]),
                      _vm._v(" "),
                      _c("h4", { staticClass: "item-title" }, [
                        _c("a", { attrs: { href: "#" } }, [
                          _vm._v(
                            "Bark Furniture, Handmade Bespoke\n                                                    Furniture"
                          )
                        ])
                      ]),
                      _vm._v(" "),
                      _c("div", { staticClass: "item-cat" }, [
                        _c("span", [
                          _c("a", { attrs: { href: "#" } }, [
                            _vm._v("Home Appliances")
                          ])
                        ]),
                        _vm._v(
                          " /\n                                                    "
                        ),
                        _c("span", [
                          _c("a", { attrs: { href: "#" } }, [_vm._v("Sofa")])
                        ])
                      ])
                    ]),
                    _vm._v(" "),
                    _c("div", { staticClass: "ad-meta" }, [
                      _c("div", { staticClass: "meta-content" }, [
                        _c("span", { staticClass: "dated" }, [
                          _c("a", { attrs: { href: "#" } }, [
                            _vm._v("7 Jan, 16  10:10 pm ")
                          ])
                        ]),
                        _vm._v(" "),
                        _c("a", { staticClass: "tag", attrs: { href: "#" } }, [
                          _c("i", { staticClass: "fa fa-tags" }),
                          _vm._v(" Used")
                        ])
                      ]),
                      _vm._v(" "),
                      _c("div", { staticClass: "user-option pull-right" }, [
                        _c(
                          "a",
                          {
                            attrs: {
                              href: "#",
                              "data-toggle": "tooltip",
                              "data-placement": "top",
                              title: "Los Angeles, USA"
                            }
                          },
                          [_c("i", { staticClass: "fa fa-map-marker" })]
                        ),
                        _vm._v(" "),
                        _c(
                          "a",
                          {
                            staticClass: "online",
                            attrs: {
                              href: "#",
                              "data-toggle": "tooltip",
                              "data-placement": "top",
                              title: "Dealer"
                            }
                          },
                          [_c("i", { staticClass: "fa fa-suitcase" })]
                        )
                      ])
                    ])
                  ])
                ])
              ]
            )
          ])
        ]),
        _vm._v(" "),
        _c("div", { staticClass: "section cta text-center" }, [
          _c("div", { staticClass: "row" }, [
            _c("div", { staticClass: "col-lg-4" }, [
              _c("div", { staticClass: "single-cta" }, [
                _c("div", { staticClass: "cta-icon icon-secure" }, [
                  _c("img", {
                    staticClass: "img-fluid",
                    attrs: { src: "images/icon/13.png", alt: "Icon" }
                  })
                ]),
                _vm._v(" "),
                _c("h4", [_vm._v("Secure Trading")]),
                _vm._v(" "),
                _c("p", [
                  _vm._v("Duis autem vel eum iriure dolor in hendrerit in")
                ])
              ])
            ]),
            _vm._v(" "),
            _c("div", { staticClass: "col-lg-4" }, [
              _c("div", { staticClass: "single-cta" }, [
                _c("div", { staticClass: "cta-icon icon-support" }, [
                  _c("img", {
                    staticClass: "img-fluid",
                    attrs: { src: "images/icon/14.png", alt: "Icon" }
                  })
                ]),
                _vm._v(" "),
                _c("h4", [_vm._v("24/7 Support")]),
                _vm._v(" "),
                _c("p", [
                  _vm._v("Duis autem vel eum iriure dolor in hendrerit in")
                ])
              ])
            ]),
            _vm._v(" "),
            _c("div", { staticClass: "col-lg-4" }, [
              _c("div", { staticClass: "single-cta" }, [
                _c("div", { staticClass: "cta-icon icon-trading" }, [
                  _c("img", {
                    staticClass: "img-fluid",
                    attrs: { src: "images/icon/15.png", alt: "Icon" }
                  })
                ]),
                _vm._v(" "),
                _c("h4", [_vm._v("Easy Trading")]),
                _vm._v(" "),
                _c("p", [
                  _vm._v("Duis autem vel eum iriure dolor in hendrerit in")
                ])
              ])
            ])
          ])
        ])
      ])
    ])
  }
]
render._withStripped = true



/***/ }),

/***/ "./resources/js/views/Home.vue":
/*!*************************************!*\
  !*** ./resources/js/views/Home.vue ***!
  \*************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _Home_vue_vue_type_template_id_63cd6604_scoped_true___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./Home.vue?vue&type=template&id=63cd6604&scoped=true& */ "./resources/js/views/Home.vue?vue&type=template&id=63cd6604&scoped=true&");
/* harmony import */ var _Home_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ./Home.vue?vue&type=script&lang=js& */ "./resources/js/views/Home.vue?vue&type=script&lang=js&");
/* empty/unused harmony star reexport *//* harmony import */ var _Home_vue_vue_type_style_index_0_id_63cd6604_scoped_true_lang_css___WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ./Home.vue?vue&type=style&index=0&id=63cd6604&scoped=true&lang=css& */ "./resources/js/views/Home.vue?vue&type=style&index=0&id=63cd6604&scoped=true&lang=css&");
/* harmony import */ var _node_modules_vue_loader_lib_runtime_componentNormalizer_js__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! ../../../node_modules/vue-loader/lib/runtime/componentNormalizer.js */ "./node_modules/vue-loader/lib/runtime/componentNormalizer.js");






/* normalize component */

var component = Object(_node_modules_vue_loader_lib_runtime_componentNormalizer_js__WEBPACK_IMPORTED_MODULE_3__["default"])(
  _Home_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_1__["default"],
  _Home_vue_vue_type_template_id_63cd6604_scoped_true___WEBPACK_IMPORTED_MODULE_0__["render"],
  _Home_vue_vue_type_template_id_63cd6604_scoped_true___WEBPACK_IMPORTED_MODULE_0__["staticRenderFns"],
  false,
  null,
  "63cd6604",
  null
  
)

/* hot reload */
if (false) { var api; }
component.options.__file = "resources/js/views/Home.vue"
/* harmony default export */ __webpack_exports__["default"] = (component.exports);

/***/ }),

/***/ "./resources/js/views/Home.vue?vue&type=script&lang=js&":
/*!**************************************************************!*\
  !*** ./resources/js/views/Home.vue?vue&type=script&lang=js& ***!
  \**************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _node_modules_babel_loader_lib_index_js_ref_4_0_node_modules_vue_loader_lib_index_js_vue_loader_options_Home_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../node_modules/babel-loader/lib??ref--4-0!../../../node_modules/vue-loader/lib??vue-loader-options!./Home.vue?vue&type=script&lang=js& */ "./node_modules/babel-loader/lib/index.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/views/Home.vue?vue&type=script&lang=js&");
/* empty/unused harmony star reexport */ /* harmony default export */ __webpack_exports__["default"] = (_node_modules_babel_loader_lib_index_js_ref_4_0_node_modules_vue_loader_lib_index_js_vue_loader_options_Home_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__["default"]); 

/***/ }),

/***/ "./resources/js/views/Home.vue?vue&type=style&index=0&id=63cd6604&scoped=true&lang=css&":
/*!**********************************************************************************************!*\
  !*** ./resources/js/views/Home.vue?vue&type=style&index=0&id=63cd6604&scoped=true&lang=css& ***!
  \**********************************************************************************************/
/*! no static exports found */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _node_modules_style_loader_index_js_node_modules_css_loader_index_js_ref_6_1_node_modules_vue_loader_lib_loaders_stylePostLoader_js_node_modules_postcss_loader_src_index_js_ref_6_2_node_modules_vue_loader_lib_index_js_vue_loader_options_Home_vue_vue_type_style_index_0_id_63cd6604_scoped_true_lang_css___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../node_modules/style-loader!../../../node_modules/css-loader??ref--6-1!../../../node_modules/vue-loader/lib/loaders/stylePostLoader.js!../../../node_modules/postcss-loader/src??ref--6-2!../../../node_modules/vue-loader/lib??vue-loader-options!./Home.vue?vue&type=style&index=0&id=63cd6604&scoped=true&lang=css& */ "./node_modules/style-loader/index.js!./node_modules/css-loader/index.js?!./node_modules/vue-loader/lib/loaders/stylePostLoader.js!./node_modules/postcss-loader/src/index.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/views/Home.vue?vue&type=style&index=0&id=63cd6604&scoped=true&lang=css&");
/* harmony import */ var _node_modules_style_loader_index_js_node_modules_css_loader_index_js_ref_6_1_node_modules_vue_loader_lib_loaders_stylePostLoader_js_node_modules_postcss_loader_src_index_js_ref_6_2_node_modules_vue_loader_lib_index_js_vue_loader_options_Home_vue_vue_type_style_index_0_id_63cd6604_scoped_true_lang_css___WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(_node_modules_style_loader_index_js_node_modules_css_loader_index_js_ref_6_1_node_modules_vue_loader_lib_loaders_stylePostLoader_js_node_modules_postcss_loader_src_index_js_ref_6_2_node_modules_vue_loader_lib_index_js_vue_loader_options_Home_vue_vue_type_style_index_0_id_63cd6604_scoped_true_lang_css___WEBPACK_IMPORTED_MODULE_0__);
/* harmony reexport (unknown) */ for(var __WEBPACK_IMPORT_KEY__ in _node_modules_style_loader_index_js_node_modules_css_loader_index_js_ref_6_1_node_modules_vue_loader_lib_loaders_stylePostLoader_js_node_modules_postcss_loader_src_index_js_ref_6_2_node_modules_vue_loader_lib_index_js_vue_loader_options_Home_vue_vue_type_style_index_0_id_63cd6604_scoped_true_lang_css___WEBPACK_IMPORTED_MODULE_0__) if(__WEBPACK_IMPORT_KEY__ !== 'default') (function(key) { __webpack_require__.d(__webpack_exports__, key, function() { return _node_modules_style_loader_index_js_node_modules_css_loader_index_js_ref_6_1_node_modules_vue_loader_lib_loaders_stylePostLoader_js_node_modules_postcss_loader_src_index_js_ref_6_2_node_modules_vue_loader_lib_index_js_vue_loader_options_Home_vue_vue_type_style_index_0_id_63cd6604_scoped_true_lang_css___WEBPACK_IMPORTED_MODULE_0__[key]; }) }(__WEBPACK_IMPORT_KEY__));
 /* harmony default export */ __webpack_exports__["default"] = (_node_modules_style_loader_index_js_node_modules_css_loader_index_js_ref_6_1_node_modules_vue_loader_lib_loaders_stylePostLoader_js_node_modules_postcss_loader_src_index_js_ref_6_2_node_modules_vue_loader_lib_index_js_vue_loader_options_Home_vue_vue_type_style_index_0_id_63cd6604_scoped_true_lang_css___WEBPACK_IMPORTED_MODULE_0___default.a); 

/***/ }),

/***/ "./resources/js/views/Home.vue?vue&type=template&id=63cd6604&scoped=true&":
/*!********************************************************************************!*\
  !*** ./resources/js/views/Home.vue?vue&type=template&id=63cd6604&scoped=true& ***!
  \********************************************************************************/
/*! exports provided: render, staticRenderFns */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_Home_vue_vue_type_template_id_63cd6604_scoped_true___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!../../../node_modules/vue-loader/lib??vue-loader-options!./Home.vue?vue&type=template&id=63cd6604&scoped=true& */ "./node_modules/vue-loader/lib/loaders/templateLoader.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/views/Home.vue?vue&type=template&id=63cd6604&scoped=true&");
/* harmony reexport (safe) */ __webpack_require__.d(__webpack_exports__, "render", function() { return _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_Home_vue_vue_type_template_id_63cd6604_scoped_true___WEBPACK_IMPORTED_MODULE_0__["render"]; });

/* harmony reexport (safe) */ __webpack_require__.d(__webpack_exports__, "staticRenderFns", function() { return _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_Home_vue_vue_type_template_id_63cd6604_scoped_true___WEBPACK_IMPORTED_MODULE_0__["staticRenderFns"]; });



/***/ })

}]);