var Encore = require("@symfony/webpack-encore");

if (!Encore.isRuntimeEnvironmentConfigured()) {
    Encore.configureRuntimeEnvironment(process.env.NODE_ENV || "dev");
}

Encore
    .setOutputPath("public/build/")
    .setPublicPath("/build")
    .addEntry("app", "./assets/js/app.js")
    .addEntry("home", "./assets/js/components/home/home.js")
    .addEntry("offers", "./assets/js/components/offer/offers.js")
    .addEntry("offer", "./assets/js/components/offer/offer.js")
    .addEntry("offerAdd", "./assets/js/components/offer/offerAdd.js")
    .addEntry("business", "./assets/js/components/business/business.js")
    .addEntry("dashboard", "./assets/js/components/user/common_components/dashboard.js")
    .addEntry("dashboardProfile", "./assets/js/components/user/common_components/dashboardProfile.js")
    .addEntry("dashboardProfileUpdate", "./assets/js/components/user/common_components/dashboardProfileUpdate.js")
    .addEntry("candidateDashboardApplications", "./assets/js/components/user/candidateDashboardApplications.js")
    .addEntry("candidateDashboardCV", "./assets/js/components/user/candidateDashboardCV.js")
    .addEntry("dashboardRecruiterProfileUpdate", "./assets/js/components/user/dashboardRecruiterProfileUpdate.js")
    .addEntry("recruiterDashboardOffers", "./assets/js/components/user/recruiterDashboardOffers.js")
    .addEntry("publicProfile", "./assets/js/components/user/publicProfile.js")
    .addEntry("allBusiness", "./assets/js/components/business/allBusiness.js")
    .addEntry("recruiterRegister", "./assets/js/components/user/recruiterRegister.js")
    .addStyleEntry("register", "./assets/css/components/user/register.scss")
    .addStyleEntry("login", "./assets/css/components/security/login.scss")
    .addStyleEntry("forgotPassword", "./assets/css/components/security/forgotPassword.scss")
    .addStyleEntry("changePassword", "./assets/css/components/security/changePassword.scss")
    .addStyleEntry("errors", "./assets/css/components/error/403_404.scss")
    .addStyleEntry("easyAdmin", "./assets/css/components/easyAdmin/easyAdmin.scss")

    .splitEntryChunks()
    .enableSingleRuntimeChunk()
    .cleanupOutputBeforeBuild()
    .enableBuildNotifications()
    .enableSourceMaps(!Encore.isProduction())
    .enableVersioning(Encore.isProduction())
    .configureBabelPresetEnv((config) => {
        config.useBuiltIns = "usage";
        config.corejs = 3;
    })
    .enableSassLoader()
    .copyFiles({
        from: "./assets/img",
        to: "images/[path][name].[hash:8].[ext]",
    })
;

module.exports = Encore.getWebpackConfig();
