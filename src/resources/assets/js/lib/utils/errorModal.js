'use strict';

var _createClass = function () { function defineProperties(target, props) { for (var i = 0; i < props.length; i++) { var descriptor = props[i]; descriptor.enumerable = descriptor.enumerable || false; descriptor.configurable = true; if ("value" in descriptor) descriptor.writable = true; Object.defineProperty(target, descriptor.key, descriptor); } } return function (Constructor, protoProps, staticProps) { if (protoProps) defineProperties(Constructor.prototype, protoProps); if (staticProps) defineProperties(Constructor, staticProps); return Constructor; }; }();

var _react = require('react');

var _react2 = _interopRequireDefault(_react);

var _reactDom = require('react-dom');

var _reactstrap = require('reactstrap');

function _interopRequireDefault(obj) { return obj && obj.__esModule ? obj : { default: obj }; }

function _classCallCheck(instance, Constructor) { if (!(instance instanceof Constructor)) { throw new TypeError("Cannot call a class as a function"); } }

function _possibleConstructorReturn(self, call) { if (!self) { throw new ReferenceError("this hasn't been initialised - super() hasn't been called"); } return call && (typeof call === "object" || typeof call === "function") ? call : self; }

function _inherits(subClass, superClass) { if (typeof superClass !== "function" && superClass !== null) { throw new TypeError("Super expression must either be null or a function, not " + typeof superClass); } subClass.prototype = Object.create(superClass && superClass.prototype, { constructor: { value: subClass, enumerable: false, writable: true, configurable: true } }); if (superClass) Object.setPrototypeOf ? Object.setPrototypeOf(subClass, superClass) : subClass.__proto__ = superClass; }

var CreateErrorModal = function (_Component) {
	_inherits(CreateErrorModal, _Component);

	function CreateErrorModal(props) {
		_classCallCheck(this, CreateErrorModal);

		var _this = _possibleConstructorReturn(this, (CreateErrorModal.__proto__ || Object.getPrototypeOf(CreateErrorModal)).call(this, props));

		_this.toggle = _this.toggle.bind(_this);

		_this.state = {
			modal: true
		};

		return _this;
	}

	_createClass(CreateErrorModal, [{
		key: 'toggle',
		value: function toggle() {
			this.setState({
				modal: !this.state.modal
			});
		}
	}, {
		key: 'closeError',
		value: function closeError() {

			var elem = document.getElementById(this.props['data-id']);
			elem.parentNode.removeChild(elem);
		}
	}, {
		key: 'render',
		value: function render() {

			return _react2.default.createElement(
				_reactstrap.Modal,
				{ isOpen: this.state.modal, toggle: this.toggle },
				_react2.default.createElement(
					_reactstrap.ModalHeader,
					null,
					'Error'
				),
				_react2.default.createElement(
					_reactstrap.ModalBody,
					null,
					this.props['data-error-message']
				),
				_react2.default.createElement(
					_reactstrap.ModalFooter,
					null,
					_react2.default.createElement(
						'button',
						{ type: 'button', className: 'btn btn-default', onClick: this.toggle.bind(this) },
						'Close'
					)
				)
			);
		}
	}]);

	return CreateErrorModal;
}(_react.Component);

var errorModal = function errorModal(errorMessage) {

	var ID = 'EM' + new Date().getTime();

	var comp = document.createElement('div');

	comp.id = ID;

	document.body.appendChild(comp);

	(0, _reactDom.render)(_react2.default.createElement(CreateErrorModal, { 'data-error-message': errorMessage, 'data-id': ID }), document.getElementById(ID));
};

module.exports = errorModal;