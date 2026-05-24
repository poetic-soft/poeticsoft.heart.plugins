const dotenv = require('dotenv');
const dotenvExpand = require('dotenv-expand');
const env = dotenv.config();
dotenvExpand.expand(env);

const path = require('path')
const fs = require('fs');
const MiniCssExtractPlugin = require('mini-css-extract-plugin')

module.exports = env => { 
                                                    
  const input = Object.keys(env)[2] || ''
  const params = input.split('-')

  /**
  * type -> block, ui, metabox, etc (bc externals)
  * so/ur/ce -> where in src
  * des/ti/nation -> detination of compilation js & css
  */  

  const type = params[0]
  const source = './src/' + params[1]
  const output = params[2]

  console.log(type)
  console.log(source)
  console.log(output)

  return 

  const wpblockexternals = {
    '@wordpress/element': 'wp.element',
    '@wordpress/i18n': 'wp.i18n',
    '@wordpress/blocks': 'wp.blocks'
  }
  const wpcompexternals = {          
    react: 'wp.element',
    'react-dom': 'wp.element'
  }

  const config = {
    output: path.join(dest_dir, dest_name, output),
    public: `/wp-content/${dest_public}/${dest_name}`,
    cssfilename: '[name].css',
    entry: {},
    externals: {},
    mode: 'dev',
    watch: 'no',
    alias: {
      assets: path.join(dest_dir, dest_name, 'assets'), 
      common: path.join(process.env.dev, 'src', 'common'),          
      blocks: path.join(dest_dir, dest_name, 'blocks')
    }
  }

  switch (type) {

    case 'block':

      if(!fs.existsSync(config.output)) {

        return console.log('Falta el directorio destino del block, usar dev/scripts/newblock.')
      }
      
      config.output = path.join(config.output, 'build')

      config.entry = {
        edit: source + '/edit.js',
        view: source + '/view.js'
      }

      config.externals = wpblockexternals

    // case 'ui':
      
    //   paths.output = destdir + '/ui/' + name 

    //   entry = {
    //     main: './src/ui/' + name + '/main.js'
    //   }

    //   if(
    //     name == 'prompteditor'
    //   ) {

    //     externals = wpcompexternals
    //   }

    //   break;

    // case 'metabox':
      
    //   paths.output = destdir  + '/ui/metabox/' + name

    //   entry = {
    //     main: './src/metabox/' + name + '/main.js'
    //   }
      
    //   externals = wpcompexternals
      
    //   break;

    default:

      break
  }

  // console.log(config)

  return {
    context: __dirname,
    stats: 'minimal',
    watch: config.watch == 'si',
    name: 'minimal',
    entry: config.entry,
    output: {
      path: config.output,
      publicPath: config.public,
      filename: '[name].js'
    },
    mode: config.mode == 'prod' ? 'production' : 'development',
    devtool: config.mode == 'prod' ? false : 'source-map',
    module: {
      rules: [
        {
          test: /\.jsx?$/,
          exclude: /node_modules/,
          use: [          
            { 
              loader: 'babel-loader',
              options: {
                presets: [
                  '@babel/preset-env',
                  '@babel/preset-react'
                ]
              }
            }
          ]
        },
        {
          test: /\.s[ac]ss$/i,
          exclude: /node_modules/,
          use: [
            { 
              loader: MiniCssExtractPlugin.loader
            },
            {
              loader: 'css-loader'
            },
            {
              loader: 'sass-loader',
              options: {
                api: "modern-compiler"
              }
            }
          ]
        },
        {
          test: /\.css$/,
          include: /node_modules/,
          use: [
            { 
              loader: MiniCssExtractPlugin.loader
            },
            'style-loader',
            'css-loader'
          ]
        },
        // Assets
        {
          test: /\.(jpg|jpeg|png|gif|svg|woff|ttf|eot|mp3|woff|woff2|webm|mp4)$/,
          type: 'asset/resource',
          generator: {
            emit: false,
            filename: content => { 

              return content.filename.replace(themename, '')
            }
          }
        }
      ]
    },
    plugins: [
      new MiniCssExtractPlugin({
        filename: config.cssfilename
      })
    ],
    resolve: {
      extensions: ['.js'],
      alias: config.alias
    },
    externals: config.externals
  }
}