'use strict';

var _createClass = function () { function defineProperties(target, props) { for (var i = 0; i < props.length; i++) { var descriptor = props[i]; descriptor.enumerable = descriptor.enumerable || false; descriptor.configurable = true; if ("value" in descriptor) descriptor.writable = true; Object.defineProperty(target, descriptor.key, descriptor); } } return function (Constructor, protoProps, staticProps) { if (protoProps) defineProperties(Constructor.prototype, protoProps); if (staticProps) defineProperties(Constructor, staticProps); return Constructor; }; }();

var _react = require('react');

var _react2 = _interopRequireDefault(_react);

var _redexConnect = require('../../../../utils/redex-connect');

var _redexConnect2 = _interopRequireDefault(_redexConnect);

var _classnames = require('classnames');

var _classnames2 = _interopRequireDefault(_classnames);

var _Http = require('../../../../utils/Http');

var _Http2 = _interopRequireDefault(_Http);

var _reactImageCrop = require('react-image-crop');

var _reactImageCrop2 = _interopRequireDefault(_reactImageCrop);

var _ReactCrop = require('react-image-crop/dist/ReactCrop.css');

var _ReactCrop2 = _interopRequireDefault(_ReactCrop);

function _interopRequireDefault(obj) { return obj && obj.__esModule ? obj : { default: obj }; }

function _classCallCheck(instance, Constructor) { if (!(instance instanceof Constructor)) { throw new TypeError("Cannot call a class as a function"); } }

function _possibleConstructorReturn(self, call) { if (!self) { throw new ReferenceError("this hasn't been initialised - super() hasn't been called"); } return call && (typeof call === "object" || typeof call === "function") ? call : self; }

function _inherits(subClass, superClass) { if (typeof superClass !== "function" && superClass !== null) { throw new TypeError("Super expression must either be null or a function, not " + typeof superClass); } subClass.prototype = Object.create(superClass && superClass.prototype, { constructor: { value: subClass, enumerable: false, writable: true, configurable: true } }); if (superClass) Object.setPrototypeOf ? Object.setPrototypeOf(subClass, superClass) : subClass.__proto__ = superClass; }

var ProfilePic = function (_Component) {
	_inherits(ProfilePic, _Component);

	function ProfilePic(props, context) {
		_classCallCheck(this, ProfilePic);

		var _this2 = _possibleConstructorReturn(this, (ProfilePic.__proto__ || Object.getPrototypeOf(ProfilePic)).call(this, props, context));

		_this2.state = {

			image: null,
			crop: {},
			message: '',
			saving_image: false

		};

		return _this2;
	}

	_createClass(ProfilePic, [{
		key: 'handleSubmit',
		value: function handleSubmit(e) {
			var _this3 = this;

			this.setState({
				message: '',
				saving_image: true
			});

			e.preventDefault();

			_Http2.default.post('/data/settings/uploadprofileimage', {

				imageData: this.state.image,

				crop: this.state.crop

			}).then(function (data) {

				_this3.setState({
					saving_image: false
				});

				if (data.image) {

					var user = _this3.props.user;

					var cupi = user.image;

					user.image = data.image;

					_this3.context.store.dispatch({

						type: 'STORE_USER',
						user: user

					});

					var imgs = document.getElementsByTagName('img');

					var regex = new RegExp('/' + user.id + '-p-');

					for (var i = 0; i < imgs.length; i++) {

						if (imgs[i].src.match(regex)) {

							imgs[i].src = user.image;
						}
					}

					if (_this3.props.finished) {
						_this3.props.finished(_this3);
					} else {
						_this3.setState({
							message: 'Image saved!'
						});
					}
				}
			});
		}
	}, {
		key: 'handleFile',
		value: function handleFile(input) {
			var _this4 = this;

			input = input.nativeEvent;

			if (input.target.files && input.target.files[0]) {
				var FR;

				(function () {
					FR = new FileReader();


					var _this = _this4;

					FR.onload = function (e) {

						_this.setState({
							image: e.target.result,
							crop: {}
						});
					};
					FR.readAsDataURL(input.target.files[0]);
				})();
			}
		}
	}, {
		key: 'updateCrop',
		value: function updateCrop(crop) {

			var image = document.getElementsByClassName('ReactCrop--image')[0];

			crop.imageWidth = image.clientWidth;
			crop.imageHeight = image.clientHeight;

			if (!crop.aspect) {

				var size = crop.imageHeight;

				if (crop.imageWidth < crop.imageHeight) {

					size = crop.imageWidth;
				}

				crop.width = size * 100 / crop.imageWidth;
				crop.height = size * 100 / crop.imageHeight;

				crop.aspect = 1;

				crop.x = (crop.imageWidth - size) * 50 / crop.imageWidth;
				crop.y = (crop.imageHeight - size) * 50 / crop.imageHeight;
			}

			this.setState({
				crop: crop
			});
		}
	}, {
		key: 'render',
		value: function render() {

			var tspi = this.state.image;

			if (!tspi) {
				tspi = this.props.user.image;
			}

			return _react2.default.createElement(
				'div',
				null,
				_react2.default.createElement(
					'div',
					{ className: 'row' },
					!tspi ? '' : _react2.default.createElement(
						'div',
						{ className: 'col-xs-6 text-xs-center' },
						!this.state.cropped_image ? '' : _react2.default.createElement('img', { src: this.state.cropped_image.src }),
						_react2.default.createElement(_reactImageCrop2.default, {

							src: tspi,

							crop: this.state.crop,

							keepSelection: true,

							onImageLoaded: this.updateCrop.bind(this),

							onComplete: this.updateCrop.bind(this),

							minWidth: 5000 / this.state.crop.imageWidth,

							minHeight: 5000 / this.state.crop.imageHeight

						})
					),
					_react2.default.createElement(
						'div',
						{ className: (0, _classnames2.default)(tspi ? 'col-md-6' : 'col-xs-12', 'text-xs-center') },
						_react2.default.createElement(
							'div',
							{ className: 'row' },
							_react2.default.createElement(
								'div',
								{ className: 'col-xs-12 text-xs-center', style: { marginTop: '20px' } },
								_react2.default.createElement(
									'label',
									{ className: (0, _classnames2.default)("uploadButton btn btn-primary", this.state.saving_image ? 'disabled' : '') },
									_react2.default.createElement('input', { type: 'file', onChange: this.handleFile.bind(this), disabled: this.state.saving_image }),
									_react2.default.createElement(
										'span',
										null,
										tspi ? 'Choose another photo' : 'Upload a photo'
									)
								)
							),
							!tspi ? '' : _react2.default.createElement(
								'div',
								{ className: 'col-xs-12', style: { marginTop: '20px' } },
								_react2.default.createElement(
									'button',
									{ className: 'btn btn-danger', disabled: this.state.saving_image, onClick: this.handleSubmit.bind(this) },
									'Save photo'
								)
							),
							!this.state.message ? '' : _react2.default.createElement(
								'div',
								{ className: 'alert alert-success col-xs-8 offset-xs-2', role: 'alert', style: { marginTop: '20px' } },
								this.state.message
							)
						)
					)
				)
			);
		}
	}]);

	return ProfilePic;
}(_react.Component);

module.exports = (0, _redexConnect2.default)(ProfilePic, {
	website: 'website',
	user: 'user'
});