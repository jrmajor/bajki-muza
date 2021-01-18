module.exports = {
  'env': {
    'browser': true,
    'es2021': true,
  },
  'extends': [
    'spatie',
  ],
  'parserOptions': {
    'ecmaVersion': 12,
    'sourceType': 'module',
  },
  'rules': {
    'indent': ['error', 2, {
      'SwitchCase': 1,
    }],
    'semi': ['error', 'never'],
  },
};
