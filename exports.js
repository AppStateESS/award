/* global __dirname, exports */
exports.path = require('path')
exports.APP_DIR = exports.path.resolve(__dirname, 'javascript')

exports.entry = {
  SignUpForm: exports.APP_DIR + '/SignUpForm/index.jsx',
  AwardList: exports.APP_DIR + '/Admin/AwardList/index.tsx',
  AwardForm: exports.APP_DIR + '/Admin/AwardForm/index.tsx',
  CycleForm: exports.APP_DIR + '/Admin/CycleForm/index.tsx',
  CycleList: exports.APP_DIR + '/Admin/CycleList/index.tsx',
}
