const path = require('path');

module.exports = {
  entry: './components/InteractiveMap.tsx',
  output: {
    path: path.resolve(__dirname, 'assets/js'),
    filename: 'interactive-map.js',
    library: 'InteractiveMap',
    libraryTarget: 'umd'
  },
  resolve: {
    extensions: ['.tsx', '.ts', '.js', '.jsx']
  },
  module: {
    rules: [
      {
        test: /\.(ts|tsx)$/,
        use: 'ts-loader',
        exclude: /node_modules/
      },
      {
        test: /\.css$/,
        use: ['style-loader', 'css-loader']
      }
    ]
  },
  externals: {
    react: 'React',
    'react-dom': 'ReactDOM'
  }
};