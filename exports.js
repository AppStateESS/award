/* global __dirname, exports */
exports.path = require('path')
exports.APP_DIR = exports.path.resolve(__dirname, 'javascript')

exports.entry = {
  AwardList: exports.APP_DIR + '/Admin/AwardList/index.tsx',
  AwardForm: exports.APP_DIR + '/Admin/AwardForm/index.tsx',
  CycleForm: exports.APP_DIR + '/Admin/CycleForm/index.tsx',
  CycleList: exports.APP_DIR + '/Admin/CycleList/index.tsx',
  ForgotPassword: exports.APP_DIR + '/ForgotPassword/index.tsx',
  InvitationList: exports.APP_DIR + '/Admin/InvitationList/index.tsx',
  ParticipantList: exports.APP_DIR + '/Admin/ParticipantList/index.tsx',
  Setting: exports.APP_DIR + '/Setting/index.tsx',
  SignInForm: exports.APP_DIR + '/SignInForm/index.jsx',
  SignUpForm: exports.APP_DIR + '/SignUpForm/index.jsx',
}
