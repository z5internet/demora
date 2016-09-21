'use strict';

var _jsCookie = require('js-cookie');

var _jsCookie2 = _interopRequireDefault(_jsCookie);

var _errorModal = require('./errorModal');

var _errorModal2 = _interopRequireDefault(_errorModal);

var _config = require('../../../../../../../../../resources/assets/react-app/config');

var _config2 = _interopRequireDefault(_config);

function _interopRequireDefault(obj) { return obj && obj.__esModule ? obj : { default: obj }; }

var Http = {

	get: function get(url, data, fetchConfig) {

		return DMFetch(url, 'GET', data, fetchConfig);
	},
	post: function post(url, data, fetchConfig) {

		return DMFetch(url, 'POST', data, fetchConfig);
	},
	put: function put(url, data, fetchConfig) {

		return DMFetch(url, 'PUT', data, fetchConfig);
	},
	delete: function _delete(url, data, fetchConfig) {

		return DMFetch(url, 'DELETE', data, fetchConfig);
	}

};

function DMFetch(url, method, data, fetchConfig) {

	if (!fetchConfig) fetchConfig = {};

	if (_config2.default && _config2.default.dataDomain) {

		url = _config2.default.dataDomain + url;
	}

	var headers = {};

	headers['X-XSRF-TOKEN'] = _jsCookie2.default.get('XSRF-TOKEN');
	headers['X-Requested-With'] = 'XMLHttpRequest';

	if (method != "GET") {

		headers['Accept'] = 'application/json';
		headers['Content-Type'] = 'application/json';
	}

	if (!data) {

		data = undefined;
	}

	return fetch(url, {

		method: method,
		headers: headers,
		credentials: 'include', //'same-origin',
		body: JSON.stringify(data)

	}).then(function (response) {

		if (fetchConfig.returnResponse) {

			return response;
		}

		return returnDataFromResponse(response);
	});
}

function returnDataFromResponse(response) {

	return responseToJson(response).then(function (data) {

		if (data.data.error) {

			(0, _errorModal2.default)(data.data.error);
		}

		return data.data;
	});
}

function responseToJson(response) {

	return response.json();
}

module.exports = Http;