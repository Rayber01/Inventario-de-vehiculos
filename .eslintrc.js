module.exports = {
	'env': {
		'browser': true,
		'node': true
	},
	'extends': [
		'eslint:recommended',
		'plugin:vue/recommended'
	],
	'parserOptions': {
		'ecmaVersion': 'latest',
		'sourceType': 'module'
	},
	'plugins': ['vue'],
	'rules': {
		'camelcase': 2,
		'array-bracket-newline': 2,
		'array-bracket-spacing': 2,
		'comma-spacing': 2,
		'comma-dangle': 2,
		'no-cond-assign': 2,
		'no-constant-condition': 2,
		'no-empty': 1,
		'no-unexpected-multiline': 2,
		'curly': 2,
		'dot-location': 1,
		'eqeqeq': 2,
		'no-floating-decimal': 2,
		'no-lone-blocks': 2,
		'no-multi-spaces': 2,
		'no-octal': 2,
		'no-redeclare': 2,
		'no-self-compare': 2,
		'yoda': 2,
		'init-declarations': [2, 'always'],
		'indent': [
			'error',
			'tab'
		],
		'linebreak-style': [
			'error',
			'unix'
		],
		'quotes': [
			'error',
			'single'
		],
		'semi': [
			'error',
			'never'
		],
		'vue/multi-word-component-names': 'off',
		'vue/no-mutating-props': 'off',
		'vue/no-v-for-template-key': 'off'
	}
}
