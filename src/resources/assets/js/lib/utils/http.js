'use strict';

var _jsCookie = require('js-cookie');

var _jsCookie2 = _interopRequireDefault(_jsCookie);

var _errorModal = require('./errorModal');

var _errorModal2 = _interopRequireDefault(_errorModal);

function _interopRequireDefault(obj) { return obj && obj.__esModule ? obj : { default: obj }; }

var Http = {

	get: function get(url, data, config) {

		return DMFetch(url, 'GET', data, config);
	},
	post: function post(url, data, config) {

		return DMFetch(url, 'POST', data, config);
	},
	put: function put(url, data, config) {

		return DMFetch(url, 'PUT', data, config);
	},
	delete: function _delete(url, data, config) {

		return DMFetch(url, 'DELETE', data, config);
	}

};

function DMFetch(url, method, data, config) {

	if (!config) config = {};

	url = 'http://playorange-react.app:8000' + url;

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

		if (config.returnResponse) {

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