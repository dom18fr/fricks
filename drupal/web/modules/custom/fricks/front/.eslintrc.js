module.exports = {
    'env': {
        'browser': true,
        'es6': true
    },
    'extends': [
        'eslint:recommended',
        'plugin:react/recommended',
        'plugin:@typescript-eslint/eslint-recommended'
    ],
    'globals': {
        'Atomics': 'readonly',
        'SharedArrayBuffer': 'readonly',
        'process': 'readonly',
        'module': 'writable',
    },
    'parser': '@typescript-eslint/parser',
    'parserOptions': {
        'ecmaFeatures': {
            'jsx': true
        },
        'ecmaVersion': 2018,
        'sourceType': 'module'
    },
    'plugins': [
        'react',
        'react-hooks',
        '@typescript-eslint'
    ],
    'settings': {
        'react': {
            'version': 'detect',
        }
    },
    'rules': {
            'indent': [
            'error',
            2,
            {
                SwitchCase: 1,
            }
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
        'no-unused-vars': 'off',
        '@typescript-eslint/no-unused-vars': ['error'],
        '@typescript-eslint/type-annotation-spacing': ['error'],
        'react/prop-types': 'off',
        'react/jsx-curly-spacing': [
            'error',
            {
                'when': 'always',
                children: true,
                attributes: false,
            }
        ],
        'react-hooks/rules-of-hooks': 'error',
        'react-hooks/exhaustive-deps': 'warn',
        'object-curly-spacing': [
            'error',
            'always'
        ],
        'react/jsx-max-props-per-line': [
            'error',
            {
                'when': 'always',
                'maximum': 1,
            }
        ],
        'react/self-closing-comp': [
            'error',
            {
                'component': true,
                'html': false
            }
        ],
        'react/jsx-first-prop-new-line': [
            'error',
            'multiline'
        ],
        'react/jsx-wrap-multilines': [
            'error',
            {
                'declaration': 'parens-new-line',
                'assignment': 'parens-new-line',
                'return': 'parens-new-line',
                'arrow': 'parens-new-line',
                'condition': 'parens-new-line',
                'logical': 'parens-new-line',
                'prop': 'ignore'
            }
        ],
        'react/jsx-closing-bracket-location': [
            'error',
            'tag-aligned',
        ],
        'react/jsx-closing-tag-location': [
            'error'
        ],
        'arrow-body-style': [
            'error',
            'as-needed'
        ],
        'array-bracket-spacing': [
            'error',
            'always',
        ],
        'react/jsx-no-useless-fragment': [
            'error'
        ],
        'prefer-arrow-callback': [
            'error'
        ],
        'padding-line-between-statements': [
            'error',
            { 
                'blankLine': 'always',
                'prev': '*', 
                'next': 'return'
            },
            { 
                'blankLine': 'always',
                'prev': '*', 
                'next': 'if'
            },
            { 
                'blankLine': 'always',
                'prev': '*', 
                'next': 'block-like'
            },
            { 
                'blankLine': 'always',
                'prev': '*', 
                'next': 'block'
            },
        ],
        'no-multiple-empty-lines': [
            'error',
            {
                'max': 1
            }
        ],
        'arrow-parens': [
            'error',
            'as-needed',
        ],
        'function-paren-newline': [
            'error',
            'multiline-arguments',
        ],
        'react/jsx-one-expression-per-line': [
            'error',
            {
                'allow': 'single-child'
            }
        ],
        'no-case-declarations': [
            'off',
        ],
        'react/jsx-indent': [
            'error',
            2,
        ]
    }
}