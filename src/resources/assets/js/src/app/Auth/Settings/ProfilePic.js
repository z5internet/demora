"use strict";function _interopRequireDefault(e){return e&&e.__esModule?e:{default:e}}function _classCallCheck(e,t){if(!(e instanceof t))throw new TypeError("Cannot call a class as a function")}function _possibleConstructorReturn(e,t){if(!e)throw new ReferenceError("this hasn't been initialised - super() hasn't been called");return!t||"object"!=typeof t&&"function"!=typeof t?e:t}function _inherits(e,t){if("function"!=typeof t&&null!==t)throw new TypeError("Super expression must either be null or a function, not "+typeof t);e.prototype=Object.create(t&&t.prototype,{constructor:{value:e,enumerable:!1,writable:!0,configurable:!0}}),t&&(Object.setPrototypeOf?Object.setPrototypeOf(e,t):e.__proto__=t)}var _createClass=function(){function e(e,t){for(var a=0;a<t.length;a++){var r=t[a];r.enumerable=r.enumerable||!1,r.configurable=!0,"value"in r&&(r.writable=!0),Object.defineProperty(e,r.key,r)}}return function(t,a,r){return a&&e(t.prototype,a),r&&e(t,r),t}}(),_react=require("react"),_react2=_interopRequireDefault(_react),_reduxConnect=require("rufUtils/redux-connect"),_reduxConnect2=_interopRequireDefault(_reduxConnect),_classnames=require("classnames"),_classnames2=_interopRequireDefault(_classnames),_http=require("rufUtils/http"),_http2=_interopRequireDefault(_http),_reactImageCrop=require("react-image-crop"),_reactImageCrop2=_interopRequireDefault(_reactImageCrop),_ReactCrop=require("react-image-crop/dist/ReactCrop.css"),_ReactCrop2=_interopRequireDefault(_ReactCrop),_image=require("rufUtils/image"),_image2=_interopRequireDefault(_image),_errorModal=require("rufUtils/errorModal"),_errorModal2=_interopRequireDefault(_errorModal),_resizeImage=require("rufUtils/resizeImage"),_resizeImage2=_interopRequireDefault(_resizeImage),ProfilePic=function(e){function t(e,a){_classCallCheck(this,t);var r=_possibleConstructorReturn(this,(t.__proto__||Object.getPrototypeOf(t)).call(this,e,a));return r.state={image:"",crop:{},message:"",saving_image:!1},r.updateCrop=r.updateCrop.bind(r),r.handleFile=r.handleFile.bind(r),r.handleSubmit=r.handleSubmit.bind(r),r}return _inherits(t,e),_createClass(t,[{key:"handleSubmit",value:function(e){var t=this;this.setState({message:"",saving_image:!0}),e.preventDefault(),_http2.default.post("/data/settings/uploadprofileimage",{imageData:this.state.image,crop:this.state.crop}).then(function(e){if(t.setState({saving_image:!1}),e.image){var a=t.props.user;a.image;a.image=e.image,t.context.store.dispatch({type:"STORE_USER",user:a});for(var r=document.getElementsByTagName("img"),i=new RegExp("/"+a.id+"-p-"),s=0;s<r.length;s++)r[s].src.match(i)&&(r[s].src=a.image);t.props.finished?t.props.finished(t):t.setState({message:"Image saved!"})}})}},{key:"handleFile",value:function(e){if(e=e.nativeEvent,e.target.files&&e.target.files[0]){var t=this;(0,_resizeImage2.default)(e.target.files[0],800,800).then(function(e){if(e.imgSrc.length>1048576)return void(0,_errorModal2.default)("The image is too big, try resizing the image so it is less than 1Mb");t.setState({image:e.imgSrc,crop:{}})}).catch(function(e){console.log(e),(0,_errorModal2.default)("There was an error uploading your image")})}}},{key:"updateCrop",value:function(e){var t=document.getElementsByClassName("ReactCrop__image")[0];if(e.imageWidth=t.clientWidth,e.imageHeight=t.clientHeight,!e.aspect){var a=e.imageHeight;e.imageWidth<e.imageHeight&&(a=e.imageWidth),e.width=100*a/e.imageWidth,e.height=100*a/e.imageHeight,e.aspect=1,e.x=50*(e.imageWidth-a)/e.imageWidth,e.y=50*(e.imageHeight-a)/e.imageHeight}this.setState({crop:e})}},{key:"render",value:function(){var e=this.state.image;return!e&&this.props.user.image.u&&(e=(0,_image2.default)(this.props.user.image,200)),_react2.default.createElement("div",null,_react2.default.createElement("div",{className:"row"},e?_react2.default.createElement("div",{className:"col-12 col-md-6 text-center"},this.state.cropped_image?_react2.default.createElement("img",{src:this.state.cropped_image.src}):"",e.match(/blank/)?"":_react2.default.createElement(_reactImageCrop2.default,{src:e,crop:this.state.crop,keepSelection:!0,onImageLoaded:this.updateCrop,onComplete:this.updateCrop,minWidth:5e3/this.state.crop.imageWidth,minHeight:5e3/this.state.crop.imageHeight})):"",_react2.default.createElement("div",{className:(0,_classnames2.default)(e?"col-md-6":"col-12","text-center")},_react2.default.createElement("div",{className:"row"},_react2.default.createElement("div",{className:"col-12 text-center",style:{marginTop:"20px"}},_react2.default.createElement("label",{className:(0,_classnames2.default)("uploadButton btn btn-primary",this.state.saving_image?"disabled":"")},_react2.default.createElement("input",{type:"file",onChange:this.handleFile,disabled:this.state.saving_image}),_react2.default.createElement("span",null,e?"Choose another photo":"Upload a photo"))),e?_react2.default.createElement("div",{className:"col-12",style:{marginTop:"20px"}},_react2.default.createElement("button",{className:"btn btn-danger",disabled:this.state.saving_image,onClick:this.handleSubmit},"Save photo")):"",this.state.message?_react2.default.createElement("div",{className:"alert alert-success col-8 offset-xs-2 mx-auto",role:"alert",style:{marginTop:"20px"}},this.state.message):""))))}}]),t}(_react.Component);module.exports=(0,_reduxConnect2.default)(ProfilePic,{website:"website",user:"user"});