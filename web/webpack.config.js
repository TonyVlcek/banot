var Encore = require('@symfony/webpack-encore');

// Manually configure the runtime environment if not already configured yet by the "encore" command.
// It's useful when you use tools that rely on webpack.config.js file.
if (!Encore.isRuntimeEnvironmentConfigured()) {
	Encore.configureRuntimeEnvironment(process.env.NODE_ENV || 'dev');
}

Encore
	.setOutputPath('www/dist/') // directory where compiled assets will be stored
	.setPublicPath('/dist') // public path used by the web server to access the output path

	.addEntry('app', './app/assets/app.js')

	.enableSingleRuntimeChunk() // extra script tag runtime.js required - enables use of multiple entry points

	/*
	 * FEATURE CONFIG
	 * Full list: https://symfony.com/doc/current/frontend.html#adding-more-features
	 */
	.cleanupOutputBeforeBuild()
	.enableBuildNotifications()
	.enableSourceMaps(!Encore.isProduction())

	// enables @babel/preset-env polyfills
	.configureBabelPresetEnv((config) => {
		config.useBuiltIns = 'usage';
		config.corejs = 3;
	})

	.enableSassLoader()

	.copyFiles({
		from: './app/assets/images',
		to: 'images/[path][name].[ext]', // relative to the output dir
		pattern: /\.(png|jpg|jpeg|gif|svg)$/
	})
;

module.exports = Encore.getWebpackConfig();
