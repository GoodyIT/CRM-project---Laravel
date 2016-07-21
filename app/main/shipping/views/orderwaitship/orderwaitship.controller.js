(function ()
{
    'use strict';

    angular
            .module('app.shipping')
            .controller('orderWaitController', orderWaitController);

    /** @ngInject */
    function orderWaitController($document,$window,$timeout,$mdDialog,$stateParams,sessionService,$http,$scope,$state,notifyService,AllConstant)
    {
        var vm = this;

        $scope.address_id = 0;

        var combine_array_id = {};
        combine_array_id.id = $stateParams.id;
        combine_array_id.company_id = sessionService.get('company_id');
        $scope.order_id = $stateParams.id;
        

        $http.post('api/public/order/orderDetail',combine_array_id).success(function(result, status, headers, config) {
            if(result.data.success == '1') {
                $("#ajax_loader").hide();
               $scope.order = result.data.records[0];
               $scope.order_items = result.data.order_item;
            } else {
                $state.go('app.order');
            }
        });

        $scope.assignedItems = [];

        $scope.shipOrder = function()
        {
            var combine_array = {};
            combine_array.order_id = $stateParams.id;
            
            $http.post('api/public/shipping/shipOrder',combine_array).success(function(result, status, headers, config) {
                if(result.data.success == '1') {
                    $("#ajax_loader").hide();
                   $scope.unshippedProducts = result.data.unshippedProducts;
                   $scope.assignAddresses = result.data.assignAddresses;
                   $scope.unAssignAddresses = result.data.unAssignAddresses;

                   if($scope.address_id > 0)
                   {
                        $scope.getProductByAddress($scope.address_id);
                   }
                }
            });
        }
        $scope.shipOrder();

        $scope.getProductByAddress = function(address_id)
        {
            $scope.address_id = address_id;

            var combine_array = {};
            combine_array.address_id = address_id;
            combine_array.order_id = $scope.order_id;
            
            $http.post('api/public/shipping/getProductByAddress',combine_array).success(function(result, status, headers, config) {
                
                if(result.data.success == '1') {
                    $("#ajax_loader").hide();
                    $scope.assignedItems = result.data.products;
                }
            });
        }

        $scope.updateShipping = function(productArr)
        {
            if($scope.address_id == 0)
            {
                var data = {"status": "error", "message": "Please select address"}
                notifyService.notify(data.status, data.message);
                return false;
            }
            if(parseInt(productArr.remaining_qnty) < parseInt(productArr.distributed_qnty))
            {
                var data = {"status": "error", "message": "Please enter valid qnty"}
                notifyService.notify(data.status, data.message);
                return false;
            }

            var combine_array = {};
            combine_array.product = productArr;
            combine_array.address_id = $scope.address_id;
            combine_array.order_id = $scope.order_id;

            $http.post('api/public/shipping/addProductToShip',combine_array).success(function(result, status, headers, config) {
                
                if(result.data.success == '1') {
                    $scope.shipOrder();
                }
            });
        }

        vm.openaddAddressDialog = openaddAddressDialog;

        function openaddAddressDialog(ev, order)
        {
            $mdDialog.show({
                controller: 'AddAddressController',
                controllerAs: 'vm',
                templateUrl: 'app/main/order/dialogs/addAddress/addAddress.html',
                parent: angular.element($document.body),
                targetEvent: ev,
                clickOutsideToClose: true,
                locals: {
                    Orders: $scope.order,
                    event: ev
                },
                onRemoving : $scope.reloadPage
            });
        }
    }
})();
