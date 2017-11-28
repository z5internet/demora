"use strict";function _interopRequireDefault(e){return e&&e.__esModule?e:{default:e}}function _classCallCheck(e,t){if(!(e instanceof t))throw new TypeError("Cannot call a class as a function")}function _possibleConstructorReturn(e,t){if(!e)throw new ReferenceError("this hasn't been initialised - super() hasn't been called");return!t||"object"!=typeof t&&"function"!=typeof t?e:t}function _inherits(e,t){if("function"!=typeof t&&null!==t)throw new TypeError("Super expression must either be null or a function, not "+typeof t);e.prototype=Object.create(t&&t.prototype,{constructor:{value:e,enumerable:!1,writable:!0,configurable:!0}}),t&&(Object.setPrototypeOf?Object.setPrototypeOf(e,t):e.__proto__=t)}var _createClass=function(){function e(e,t){for(var r=0;r<t.length;r++){var a=t[r];a.enumerable=a.enumerable||!1,a.configurable=!0,"value"in a&&(a.writable=!0),Object.defineProperty(e,a.key,a)}}return function(t,r,a){return r&&e(t.prototype,r),a&&e(t,a),t}}(),_react=require("react"),_react2=_interopRequireDefault(_react),_reduxConnect=require("rufUtils/redux-connect"),_reduxConnect2=_interopRequireDefault(_reduxConnect),_http=require("rufUtils/http"),_http2=_interopRequireDefault(_http),_modal=require("rufUtils/modals/modal"),_modal2=_interopRequireDefault(_modal),_formInModal=require("rufUtils/modals/formInModal"),_formInModal2=_interopRequireDefault(_formInModal);require("./products.css");var Products=function(e){function t(){_classCallCheck(this,t);var e=_possibleConstructorReturn(this,(t.__proto__||Object.getPrototypeOf(t)).call(this));return e.state={products:[],loaded:!1},e}return _inherits(t,e),_createClass(t,[{key:"componentDidMount",value:function(){var e=this;_http2.default.get("/data/admin/products").then(function(t){e.addProductsToState(t)})}},{key:"componentWillUnmount",value:function(){this.unmounted=!0}},{key:"drawProducts",value:function(){var e=this;return this.state.products.map(function(t,r){return _react2.default.createElement("div",{key:r,className:"mt-3 pt-3",style:{borderTop:"1px solid #eee"}},_react2.default.createElement("a",{onClick:e.editProductModal.bind(e,t)},_react2.default.createElement("div",{className:"row"},_react2.default.createElement("div",{className:"col-8"},_react2.default.createElement("h5",null,t.description),_react2.default.createElement("small",null,t.product_id)),_react2.default.createElement("div",{className:"col-4"},t.currency," ",(t.amount/100).toFixed(2)))))})}},{key:"editProduct",value:function(e){var t=this;e=this.changeSaveData(e),console.log(e),_http2.default.put("/data/admin/products",e).then(function(e){t.addProductsToState(e),_modal2.default.close("addProduct")})}},{key:"saveNewProduct",value:function(e){var t=this;e=this.changeSaveData(e),_http2.default.post("/data/admin/products",e).then(function(e){t.addProductsToState(e),_modal2.default.close("addProduct")})}},{key:"addProductsToState",value:function(e){this.unmounted||this.setState({products:e.products,loaded:!0})}},{key:"changeSaveData",value:function(e){return e.amount=100*e.amount,e.initial_payment_amount=100*e.initial_payment_amount,0==e.users_included?(e.amount_per_user=null,e.users_included=0):e.amount_per_user={0:100*e.amount_per_user},e.is_initial_payment||(delete e.initial_payment_amount,delete e.initial_payment_quantity,delete e.initial_payment_term),delete e.is_initial_payment,e.trial_period?e.trial_period=e.trial_period+" day":(delete e.trial_period_card_required,delete e.trial_period),delete e.is_trial_period,e}},{key:"changeShowData",value:function(e){return e.product_id?(e.trial_period&&(e.trial_period=parseFloat(e.trial_period)),e.is_recurring=!!e.is_recurring,e.trial_period_card_required=!!e.trial_period_card_required,e.is_trial_period=!(!e.trial_period_card_required||!e.trial_period),console.log(e.amount_per_user[0]),e.amount_per_user=e.amount_per_user[0]?(e.amount_per_user[0]/100).toFixed(2):0,console.log(e.amount_per_user),e.amount=(e.amount/100).toFixed(2),e.initial_payment_amount=(e.initial_payment_amount/100).toFixed(2),e.is_initial_payment=!!(e.initial_payment_amount&&e.initial_payment_quantity&&e.initial_payment_term),e.users_included=e.users_included?e.users_included:"0",e):{}}},{key:"render",value:function(){return this.state.loaded?_react2.default.createElement("div",null,_react2.default.createElement("div",{className:"text-right"},_react2.default.createElement("button",{className:"btn btn-primary",onClick:this.addProductModal.bind(this)},"Add product")),_react2.default.createElement("div",null,this.drawProducts())):_react2.default.createElement("div",{className:"text-center"},_react2.default.createElement("div",{className:"fa fa-3x fa-cog fa-spin"}))}},{key:"editProductModal",value:function(e,t){this.addProductModal(e,t)}},{key:"addProductModal",value:function(e){e=this.changeShowData(e),console.log(e);var t={id:"addProduct",header:"Add product",form:{fields:[{name:"product_id",label:"ID",placeHolder:"Product ID",inputType:"text",dataType:"string",errorMessage:"You must add a product ID",helpMessage:"The ID/SKU for this product",disabled:!!e.product_id,value:e.product_id,required:!0},{name:"product_group",label:"Group",placeHolder:"Product Group",inputType:"text",dataType:"string",errorMessage:"",helpMessage:"The group ID if this product is part of a group.",value:e.product_group,required:!1},{name:"description",label:"Description",placeHolder:"Product title",inputType:"text",dataType:"string",errorMessage:"",helpMessage:"This will be the title of the product that users will see.",value:e.description,required:!0},{name:"currency",label:"Currency of product",placeHolder:"Choose currency",inputType:"select",dataType:"string",errorMessage:"",helpMessage:"The currency you want to use for this product.",required:!0,value:e.currency,values:[{0:""},{id:"EUR",name:"EUR"},{id:"GBP",name:"GBP"},{id:"USD",name:"USD"}]},{label:"Pricing",inputType:"subtitle",showIf:"currency"},{name:"amount",label:"Price",placeHolder:"Price of product",inputType:"number",dataType:"number",errorMessage:"",helpMessage:"Price of product excluding sales tax",required:!0,value:e.amount,showIf:"currency"},{name:"tax",label:"Sales tax/VAT",placeHolder:"Percentage of tax",inputType:"number",dataType:"number",errorMessage:"",helpMessage:"Percentage of tax to charge",required:!0,value:e.tax,showIf:"currency"},{label:"Payment Term",inputType:"subtitle",showIf:"currency"},{name:"is_recurring",label:"Recurring",placeHolder:"Is this a recurring payment?",inputType:"checkbox",dataType:"boolean",errorMessage:"",helpMessage:"",value:e.is_recurring,showIf:"currency",elementClassName:"productsCheckboxRow",required:!0},{name:"term",label:"Payment Term",placeHolder:"",inputType:"select",dataType:"string",errorMessage:"",helpMessage:"The length of time this payment gives access.",required:!0,showIf:["currency"],value:e.term,values:[{id:"100 year",name:"Unlmited"},{id:"1 month",name:"Monthly"},{id:"1 year",name:"Annually"}]},{label:"Trial Period",inputType:"subtitle",showIf:"currency"},{name:"is_trial_period",label:"Trial period",placeHolder:"Is there a trial period?",inputType:"checkbox",dataType:"boolean",errorMessage:"",helpMessage:"",required:!1,value:e.is_trial_period,elementClassName:"productsCheckboxRow",showIf:"currency"},{name:"trial_period_card_required",label:"Card required?",placeHolder:"Do you require the user to enter payment details for the trial period?",inputType:"checkbox",dataType:"boolean",errorMessage:"",helpMessage:"",required:!1,value:e.trial_period_card_required,elementClassName:"productsCheckboxRow",showIf:"is_trial_period"},{name:"trial_period",label:"Trial period",placeHolder:"Number of days",inputType:"number",dataType:"number",errorMessage:"",helpMessage:"How long is the trial period in days?",required:!1,value:e.trial_period,showIf:"is_trial_period"},{label:"Initial Payment",inputType:"subtitle",showIf:["currency","is_recurring"]},{name:"is_initial_payment",label:"Initial payment",placeHolder:"Is there an initial payment?",inputType:"checkbox",dataType:"boolean",errorMessage:"",helpMessage:"",required:!1,value:e.is_initial_payment,elementClassName:"productsCheckboxRow",showIf:["currency","is_recurring"]},{name:"initial_payment_amount",label:"Initial Price",placeHolder:"Initial price of product",inputType:"number",dataType:"number",errorMessage:"",helpMessage:"Set the initial price to be different",value:e.initial_payment_amount,required:!1,showIf:"is_initial_payment"},{name:"initial_payment_quantity",label:"Quantity",placeHolder:"",inputType:"number",dataType:"number",errorMessage:"",helpMessage:"Number of payments at the initial price",value:e.initial_payment_quantity,required:!1,showIf:"is_initial_payment"},{name:"initial_payment_term",label:"Select term",placeHolder:"Initial payment term",inputType:"select",dataType:"string",errorMessage:"",helpMessage:"",showIf:"is_initial_payment",value:e.initial_payment_term,required:!1,values:[{0:""},{id:"1 month",name:"1 month"},{id:"2 month",name:"2 months"}]}],button:{text:"Save",class:"btn-primary",position:"right"},labelsAboveFields:!0,submit:e.product_id?this.editProduct.bind(this):this.saveNewProduct.bind(this),clearFormAfterSubmit:!0}};this.props.website.multiAccounts&&this.props.website.multiAccounts.allowMultiUsers&&(t.form.fields.push({label:"Additional Users",inputType:"subtitle",showIf:"currency"}),t.form.fields.push({name:"users_included",label:"Users included in price",placeHolder:"Number of users",inputType:"number",dataType:"number",errorMessage:"",helpMessage:"Set to 0 for unlimited users",required:!1,value:e.users_included,showIf:"currency"}),t.form.fields.push({name:"amount_per_user",label:"Additional price per user",placeHolder:"Price",inputType:"number",dataType:"number",errorMessage:"",helpMessage:"",required:!1,value:e.amount_per_user,showIf:"users_included"})),(0,_formInModal2.default)(t)}}]),t}(_react2.default.Component);module.exports=(0,_reduxConnect2.default)(Products,{website:"website"});