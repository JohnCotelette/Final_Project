var Encore = require('@symfony/webpack-encore');

if (!Encore.isRuntimeEnvironmentConfigured()) {
    Encore.configureRuntimeEnvironment(process.env.NODE_ENV || 'dev');
}

Encore
    .setOutputPath('public/build/')
    .setPublicPath('/build')
    .addEntry('app', './assets/js/app.js')
    .addEntry('register', './assets/js/components/user/register.js')
    .addEntry('home', './assets/js/components/home/home.js')
    .addStyleEntry('forgotPassword', './assets/css/components/security/forgotPassword.scss')
    .addStyleEntry('changePassword', './assets/css/components/security/changePassword.scss')
    .splitEntryChunks()
    .enableSingleRuntimeChunk()
    .cleanupOutputBeforeBuild()
    .enableBuildNotifications()
    .enableSourceMaps(!Encore.isProduction())
    .enableVersioning(Encore.isProduction())
    .configureBabelPresetEnv((config) => {
        config.useBuiltIns = 'usage';
        config.corejs = 3;
    })
    .enableSassLoader()
    .autoProvidejQuery()
    .copyFiles({
        from: './assets/img',
        to: 'images/[path][name].[hash:8].[ext]',
    })
;

module.exports = Encore.getWebpackConfig();
