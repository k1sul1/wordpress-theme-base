module.exports = {
  parser: 'babel-eslint',
  extends: [
    'standard',
    'plugin:react/recommended'
  ],
  rules: {
    'comma-dangle': ['error', 'always-multiline'],
    'indent': ['warn', 2],
    'linebreak-style': ['error', 'unix'],
    'quotes': ['error', 'single'],
    'no-unused-vars': ['warn'],
    'no-console': 0,
    'arrow-parens': ['error', 'always'],
    'arrow-body-style': ['error', 'as-needed', { 'requireReturnForObjectLiteral': false }],
    'no-param-reassign': [1, { 'props': false } ],
    'one-var': 0,
    'one-var-declaration-per-line': 0,
    'no-underscore-dangle': 0,
    'no-confusing-arrow': [1, { "allowParens": true } ],
    'class-methods-use-this': [0],
    'max-len': [1, { 'code': 110, 'ignoreComments': true }],
    'no-confusing-arrow': 0, // It isn't confusing. Hopefully.
    'react/jsx-filename-extension': 0,
    'react/jsx-uses-vars': 1,
  },
  env: {
    browser: true,
    node: true,
  },
  parserOptions: {
    sourceType: 'module',
    ecmaVersion: 2018,
    'ecmaFeatures': {
      'jsx': true
    }
  },
  settings: {
    react: {
      version: 'detect',
    },
    // 'import/resolver': {
    //   webpack: {
    //     config: 'config/webpack.client.js',
    //   },
    // },
  },
};
