/* eslint-disable */
const webpack = require('webpack')
const path = require('path')

module.exports = (env, argv) => {
  
  return [
    {
      name: 'fricks',
      resolve: {
        extensions: ['.ts', '.tsx', '.js'],
      },
      module: {
        rules: [
          {
            // Include ts, tsx, js, and jsx files.
            test: /\.(tsx|jsx|js)?$/,
            exclude: [/node_modules/],
            loader: 'babel-loader',
          }
        ],
      },
      plugins: [
        new webpack.EnvironmentPlugin([
          'FRICKS_BASE_URL',
        ]),
      ],
    }
  ]
}