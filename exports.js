/* global __dirname, exports */
exports.path = require('path')
exports.APP_DIR = exports.path.resolve(__dirname, 'javascript')

const adminScripts = {
  AwardList: exports.APP_DIR + '/Admin/AwardList/index.tsx',
  AwardForm: exports.APP_DIR + '/Admin/AwardForm/index.tsx',
  CycleForm: exports.APP_DIR + '/Admin/CycleForm/index.tsx',
  CycleList: exports.APP_DIR + '/Admin/CycleList/index.tsx',
  InvitationList: exports.APP_DIR + '/Admin/InvitationList/index.tsx',
  Judges: exports.APP_DIR + '/Admin/Judges/index.tsx',
  ParticipantList: exports.APP_DIR + '/Admin/ParticipantList/index.tsx',
  Setting: exports.APP_DIR + '/Admin/Setting/index.tsx',
}

const userScripts = {
  ForgotPassword: exports.APP_DIR + '/User/ForgotPassword/index.tsx',
  ResetPassword: exports.APP_DIR + '/User/ResetPassword/index.tsx',
  SignInForm: exports.APP_DIR + '/User/SignInForm/index.tsx',
  SignUpForm: exports.APP_DIR + '/User/SignUpForm/index.tsx',
}

const participantScripts = {
  Name: exports.APP_DIR + '/Participant/Name/index.tsx',
}

exports.entry = {...adminScripts, ...userScripts, ...participantScripts}
