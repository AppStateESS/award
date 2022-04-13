/* global __dirname, exports */
exports.path = require('path')
exports.APP_DIR = exports.path.resolve(__dirname, 'javascript')

exports.entry = {
  SignUpForm: exports.APP_DIR + '/SignUpForm/index.jsx',
}
