(function ()
{
    'use strict';

    angular
        .module('app.settings', [])
        .config(config);

    /** @ngInject */
    function config($stateProvider, $translatePartialLoaderProvider, msApiProvider, msNavigationServiceProvider)
    {
        
        //State
        $stateProvider
            .state('app.settings', {
                resolve: {
                    checksession : function (sessionService)
                    {
                        setTimeout(function(){ 
                           $(".settings-block").removeClass("collapsed");
                        }, 2000);
                    },
                },
                url    : '/settings',
                views  : {
                    'content@app': {
                        templateUrl: 'app/main/settings/settings.html',
                        controller : 'SettingsController as vm'
                    }
                }
            }).state('app.settings.userProfile', {
                resolve: {
                    checksession : function (sessionService,$state)
                    {
                        setTimeout(function(){ 
                           $(".settings-block").removeClass("collapsed");
                        }, 2000);
                        return sessionService.AccessService('ALL');
                    },
                },
                url  : '/userProfile',
                views: {
                    'content@app': {
                        templateUrl: 'app/main/settings/views/userProfile/userProfile.html',
                        controller : 'UserProfileController as vm'
                    }
                }
            }).state('app.settings.priceGrid', {
                resolve: {
                    checksession : function (sessionService,$state)
                    {
                        setTimeout(function(){ 
                           $(".settings-block").removeClass("collapsed");
                        }, 2000);
                        return sessionService.AccessService('ALL');
                    }
                },
                url  : '/priceGrid',
                views: {
                    'content@app': {
                        templateUrl: 'app/main/settings/views/priceGrid/priceGrid.html',
                        controller : 'PriceGridController as vm'
                    }
                }
            }).state('app.settings.price-info', {
                url  : '/price-info/:id',
                views: {
                    'content@app': {
                        templateUrl: 'app/main/settings/views/priceGrid/createPriceGrid.html',
                        controller : 'CreatePriceGridDialogController as vm'
                    }
                },resolve: {
                   
                }
            }).state('app.settings.companyProfile', {
                resolve: {
                    checksession : function (sessionService,$state)
                    {
                        setTimeout(function(){ 
                           $(".settings-block").removeClass("collapsed");
                        }, 2000);
                        return sessionService.AccessService('CA');
                    }
                },
                url  : '/companyProfile',
                views: {
                    'content@app': {
                        templateUrl: 'app/main/settings/views/companyProfile/companyProfile.html',
                        controller : 'UserProfileController as vm'
                    }
                }
            }).state('app.settings.companyDetails', {
                resolve: {
                    checksession : function (sessionService,$state)
                    {
                        setTimeout(function(){ 
                           $(".settings-block").removeClass("collapsed");
                        }, 2000);
                        return sessionService.AccessService('CA');
                    }
                },
                url  : '/companyDetails',
                views: {
                    'content@app': {
                        templateUrl: 'app/main/settings/views/companyDetails/companyDetails.html',
                        controller : 'CompanyDetailsController as vm'
                    }
                },resolve: {
                   
                }
            }).state('app.settings.userManagement', {
                resolve: {
                    checksession : function (sessionService,$state)
                    {
                        setTimeout(function(){ 
                           $(".settings-block").removeClass("collapsed");
                        }, 2000);
                        return sessionService.AccessService('CA');
                    }
                },
                url  : '/userManagement',
                views: {
                    'content@app': {
                        templateUrl: 'app/main/settings/views/userManagement/userManagement.html',
                        controller : 'UserManagementController as vm'
                    }
                },resolve: {
                   
                }
            }).state('app.settings.affiliate', {
                resolve: {
                    checksession : function (sessionService,$state)
                    {
                        setTimeout(function(){ 
                           $(".settings-block").removeClass("collapsed");
                        }, 2000);
                        return sessionService.AccessService('CA');
                    }
                },
                url  : '/affiliate',
                views: {
                    'content@app': {
                        templateUrl: 'app/main/settings/views/affiliate/affiliate.html',
                        controller : 'AffiliateController as vm'
                    }
                },resolve: {
                   
                }
            }).state('app.settings.integrations', {
                resolve: {
                    checksession : function (sessionService,$state)
                    {
                        setTimeout(function(){ 
                           $(".settings-block").removeClass("collapsed");
                        }, 2000);
                        return sessionService.AccessService('CA');
                    }
                },
                url  : '/integrations',
                views: {
                    'content@app': {
                        templateUrl: 'app/main/settings/views/integrations/integrations.html',
                        controller : 'IntegrationsController as vm'
                    }
                },resolve: {
                   
                }
            }).state('app.settings.support', {
                resolve: {
                    checksession : function (sessionService,$state)
                    {
                        setTimeout(function(){ 
                           $(".settings-block").removeClass("collapsed");
                        }, 2000);
                        return sessionService.AccessService('CA');
                    }
                },
                url  : '/support',
                views: {
                    'content@app': {
                        templateUrl: 'app/main/settings/views/support/support.html',
                        controller : 'SupportController as vm'
                    }
                },resolve: {
                   
                }
            })
            ;
        // Navigation
       /* msNavigationServiceProvider.saveItem('fuse', {
            title : '',
            group : true,
            weight: 1
        });*/

        msNavigationServiceProvider.saveItem('fuse.settings', {
            title    : 'Settings',
            icon     : 'icon-cog',
            //state    : 'app.settings',
            class      : 'navigation-dashboards project-dashboard settings-block',
            
            weight   : 1
        });

        msNavigationServiceProvider.saveItem('fuse.settings.userProfile', {
            title      : 'User Profile',
            state      : 'app.settings.userProfile',
            stateParams: {'id': 1},
            class      : 'navigation-dashboards project-dashboard',
            
            weight     : 1
        });

        msNavigationServiceProvider.saveItem('fuse.settings.companyProfile', {
            title      : 'Company Profile',
            state      : 'app.settings.companyProfile',
            stateParams: {'id': 2},
            class      : 'navigation-dashboards project-dashboard',
            weight     : 2
        });

        msNavigationServiceProvider.saveItem('fuse.settings.companyDetails', {
            title      : 'Company Details',
            state      : 'app.settings.companyDetails',
            stateParams: {'id': 3},
            class      : 'navigation-dashboards project-dashboard',
            weight     : 3
        });

        msNavigationServiceProvider.saveItem('fuse.settings.userManagement', {
            title      : 'User Management',
            state      : 'app.settings.userManagement',
            stateParams: {'id': 4},
            class      : 'navigation-dashboards project-dashboard',
            
            weight     : 4
        });
        msNavigationServiceProvider.saveItem('fuse.settings.priceGrid', {
            title      : 'Price Grid',
            state      : 'app.settings.priceGrid',
            stateParams: {'id': 5},
            class      : 'navigation-dashboards project-dashboard',
            
            weight     : 5
        });
        msNavigationServiceProvider.saveItem('fuse.settings.affiliate', {
            title      : 'Affiliate',
            state      : 'app.settings.affiliate',
            stateParams: {'id': 6},
            class      : 'navigation-dashboards project-dashboard',
            weight     : 6
        });
        msNavigationServiceProvider.saveItem('fuse.settings.integrations', {
            title      : 'Integrations',
            state      : 'app.settings.integrations',
            stateParams: {'id': 7},
            class      : 'navigation-dashboards project-dashboard',
            weight     : 7
        });/*
        msNavigationServiceProvider.saveItem('fuse.settings.support', {
            title      : 'Support',
            state      : 'app.settings.support',
            stateParams: {'id': 8},
            weight     : 8
        });
        msNavigationServiceProvider.saveItem('fuse.settings.billing', {
            title      : 'Billing',
            state      : 'app.settings.billing',
            stateParams: {'id': 9},
            weight     : 9
        });

        msNavigationServiceProvider.saveItem('fuse.settings.platformSettings', {
            title      : 'Platform Settings',
            state      : 'app.settings.platformSettings',
            stateParams: {'id': 10},
            weight     : 10
        });*/



    }
})();