


app.controller('staffListCtrl', ['$scope','$http','$location','$state','$stateParams','fileUpload','AllConstant', function($scope,$http,$location,$state,$stateParams,fileUpload,AllConstant) {
  
  $http.get('api/public/admin/staff').success(function(result, status, headers, config) {

                                  $scope.staffs = result.data.records;
                                  $scope.pagination = AllConstant.pagination;
                         
                          });

                         $scope.delete = function (staff_id,user_id) {
                          
                           var combine_array_delete = {};
                          combine_array_delete.staff_id = staff_id;
                          combine_array_delete.user_id = user_id;

                         
                            var permission = confirm(AllConstant.deleteMessage);
                            if (permission == true) {
                            $http.post('api/public/admin/staffDelete',combine_array_delete).success(function(result, status, headers, config) {
                                          
                                          if(result.data.success=='1')
                                          {
                                           
                                            $state.go('staff.list');
                                            $("#comp_"+staff_id).remove();
                                            return false;
                                          }  
                                     });
                                  }
                              } 

}]);

app.controller('staffAddEditCtrl', ['$scope','$http','$location','$state','$stateParams','fileUpload','AllConstant','$filter', function($scope,$http,$location,$state,$stateParams,fileUpload,AllConstant,$filter) {
   
    

$http.get('api/public/common/type/timeoff').success(function(result, status, headers, config) {

                                  $scope.timeOffTypes = result.data.records;
                         
                          });



    /*$http.get('api/public/common/type/staff').success(function(result, status, headers, config) {

                                  $scope.types = result.data.records;
                         
                          });*/


    var miscData = {};
      miscData.table ='misc_type'
      miscData.cond ={status:1,is_delete:1,type:'staff_type'}
      miscData.notcond ={value:""}
      $http.post('api/public/common/GetTableRecords',miscData).success(function(result) {
          if(result.data.success == '1') 
          {
              $scope.types =result.data.records;
          } 
          else
          {
              $scope.types=[];
          }
        });



     $http.get('api/public/common/staffRole').success(function(result, status, headers, config) {

              $scope.staffRoles = result.data.records;
     
      });

   $scope.files = [];
                    $scope.setFiles = function (element) {
                        $scope.$apply(function ($scope) {
                            console.log('files:', element.files);

                            var uploadFile, uploadFileExt;
                            // Turn the FileList object into an Array
                            $scope.files = []
                            for (var i = 0; i < element.files.length; i++) {
                                uploadFile = element.files[i].name
                                uploadFileExt = uploadFile.split('.').pop() // Find the upload file extension for select valid images only

                                if (uploadFileExt == 'jpg' || uploadFileExt == 'png' || uploadFileExt == 'jpeg' || uploadFileExt == 'bmp' || uploadFileExt == 'gif')
                                {
                                    $scope.files.push(element.files[i])
                                }
                                else
                                {
                                  
                                    angular.element("input[type='file']").val(null)
                                    var data = {"status": "error", "message": "Please select image file to upload"}
                                    
                                    return false;
                                }
                            }
                        });
                    };

                    $scope.saveStaff = function () {
                        var user_data_staff = $scope.staff;
                        var user_data_users = $scope.users;
                        
                        var fd = new FormData()
                        for (var i in $scope.files) {
                            fd.append("image", $scope.files[i])
                        }

                        fd.append("notes_data_all", angular.toJson($scope.allnotes))
                        fd.append("timeoff_data_all", angular.toJson($scope.allTimeOff))



                         $.each(user_data_staff, function( index, value ) {
                            fd.append(index, value)
                          });

                           $.each(user_data_users, function( index, value ) {
                            fd.append(index, value)
                          });


                       var xhr = new XMLHttpRequest()
                        xhr.onreadystatechange = function () {
                         

                            if (xhr.readyState == 4 && xhr.status == 200) {
                                var data = JSON.parse(xhr.responseText)

                                
                                if (data.success === '0') {
                                    return false;
                                }
                                else {


                                  setTimeout(function () {
                                      
                                        
                                          $scope.$apply(function ($scope) {
                                          $state.go('staff.list');
                                     });
                                    }, 10);

                                }
                            }
                        }
                        if(user_data_staff.id) {

                           xhr.open("POST","api/public/admin/staffEdit")
                           xhr.send(fd);

                        } else {
                           xhr.open("POST","api/public/admin/staffAdd")
                           xhr.send(fd);
                        }

                       
                    };

                    if($stateParams.id) {

                           $http.post('api/public/admin/staffDetail',$stateParams.id).success(function(result, status, headers, config) {
        
                            if(result.data.success == '1') {
                                      
                                    
                                     result.data.records[0]['date_start'] = $filter('dateWithFormat')(result.data.records[0]['date_start']);
                                     result.data.records[0]['birthday'] = $filter('dateWithFormat')(result.data.records[0]['birthday']);
                                     result.data.records[0]['date_end'] = $filter('dateWithFormat')(result.data.records[0]['date_end']); 
                                     
                                     $scope.staff = result.data.records[0];
                                     $scope.users = result.data.users_records[0];
                                     
                                     var count_no = 0;
                                     angular.forEach(result.data.allTimeOff, function(){
                                     

                                     result.data.allTimeOff[count_no].date_begin = $filter('dateWithFormat')(result.data.allTimeOff[count_no].date_begin);
                                     result.data.allTimeOff[count_no].date_begin = $filter('dateWithFormat')(result.data.allTimeOff[count_no].date_begin);


                                     result.data.allTimeOff[count_no].date_end = $filter('dateWithFormat')(result.data.allTimeOff[count_no].date_end);
                                     result.data.allTimeOff[count_no].date_end = $filter('dateWithFormat')(result.data.allTimeOff[count_no].date_end); 
                                      count_no++;

                                   
                                    });

                                     $scope.allnotes = result.data.allnotes;
                                     $scope.allTimeOff = result.data.allTimeOff;
                                     

                             }  else {
                             $state.go('app.dashboard');
                             }
                         
                          });

                         }


                         $scope.allnotes = [];
                          $scope.addNotes = function(){
                            $scope.allnotes.push({note:'', points:''});
                          }

                          $scope.removeNotes = function(index){
                              $scope.allnotes.splice(index,1);
                          }


                          $scope.allTimeOff = [];
                          $scope.addTimeOff = function(){
                            $scope.allTimeOff.push({classification:'', date_begin:'',date_end:'', applied_hours:''});
                          }

                          $scope.removeTimeOff = function(index){
                              $scope.allTimeOff.splice(index,1);
                          }


                       

}]);
