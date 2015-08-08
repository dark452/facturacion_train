var app = angular.module('myApp', ['ui.bootstrap','ngRoute']);
//Add this to have access to a global variable



app.filter('startFrom', function() {
    return function(input, start) {
        if(input) {
            start = +start; //parse to int
            return input.slice(start);
        }
        return [];
    }
});


    app.config(function($routeProvider) {
        $routeProvider

            // route for the home page
            .when('/notas', {
                templateUrl : 'notas.html',
                controller  : 'notas'
            })

            .when('/enviadas', {
                templateUrl : 'enviadas.html',
                controller  : 'enviadasControlador'
            })
                    // route for the about page
            .when('/', {
                templateUrl : 'facturas.html',
                controller  : 'facturasCrtl'
            });
        });
            
app.controller('notas', function ($scope, $http, $timeout) {
   $scope.setLoading= function(loading){
         $scope.display = loading;
    };

    getNCND();
  
    function getNCND(){  
      $scope.setLoading(true);  
      $http.get('ajax/getNCND.php').success(function(data){
        $scope.list = data;
        $scope.currentPage = 1; //current page
        $scope.entryLimit = 10; //max no of items to display in a page
        $scope.filteredItems = $scope.list.length; //Initially for no filter  
        $scope.totalItems = $scope.list.length;

        $scope.setLoading(false);  
        });      
    };
 
   
    $scope.setPage = function(pageNo) {
        $scope.currentPage = pageNo;
    };
    $scope.filter = function() {
        $timeout(function() { 
            $scope.filteredItems = $scope.filtered.length;
        }, 10);
    };
    $scope.sort_by = function(predicate) {
        $scope.predicate = predicate;
        $scope.reverse = !$scope.reverse;
    };

          
     $scope.Traspasar= function(folio,tipo_fact){   
    // alert("folio y tipo de factura" + folio + ", " + tipo_fact);
     $scope.setLoading(true);  
    var url = "ajax/traspasar_ventas.php?folio="+folio+"&tipo_fact="+tipo_fact;
               
            $http.post(url).success(function(data){

                if(!data.success){
                  //  $scope.errorEmpresa = data.errors.empresa;
                   // $scope.errorFechaR = data.errors.fechar;
                   // $scope.errorFechaC = data.errors.fechac;
                   alert("Hubo un error, llore en la playa!!");
                   //console.log(data);
                  $scope.setLoading(false);  
                } else{
        alert("Magia!");
                    getNCND();
                }

            })
      
          };
      
     $scope.Sincronizar= function(folio,tipo_fact){   
    // alert("folio y tipo de factura" + folio + ", " + tipo_fact);
     $scope.setLoading(true);  
    var url = "ajax/sincronizar.php?folio="+folio+"&tipo_fact="+tipo_fact;
               
            $http.post(url).success(function(data){

                if(!data.success){
                  //  $scope.errorEmpresa = data.errors.empresa;
                   // $scope.errorFechaR = data.errors.fechar;
                   // $scope.errorFechaC = data.errors.fechac;
                   alert("Hubo un error, llore en la playa!!");
                   //console.log(data);
                  $scope.setLoading(false);  
                } else{
        alert("Magia!");
                    getNCND();
                }

            })
      
         
              };
  
    });


app.controller('enviadasControlador', function($scope,$http,$window) {
        // create a message to display in our view
        $scope.rut = 79740770;//rut sin DV de la empresa TRAIN

        $scope.setLoading= function(loading){
         $scope.display = loading;
        };


         getFacturasEnviadas();

         function getFacturasEnviadas(){   
   $scope.setLoading(true);  
    $http.get('ajax/getFacturasEnviadas.php').success(function(data){
        $scope.list = data;
        $scope.currentPage = 1; //current page
        $scope.entryLimit = 10; //max no of items to display in a page
        $scope.filteredItems = $scope.list.length; //Initially for no filter  
        $scope.totalItems = $scope.list.length;
    $scope.setLoading(false);  
        });
      
    };
 
   
    $scope.setPage = function(pageNo) {
        $scope.currentPage = pageNo;
    };
    $scope.filter = function() {
        $timeout(function() { 
            $scope.filteredItems = $scope.filtered.length;
        }, 10);
    };
    $scope.sort_by = function(predicate) {
        $scope.predicate = predicate;
        $scope.reverse = !$scope.reverse;
    };


     $scope.Ver= function(folio,tipo_fact){   
    // alert("folio y tipo de factura" + folio + ", " + tipo_fact);
    // $scope.setLoading(true);  
    var url = "ajax/GeneraPdf.php?folio="+folio+"&tipo_fact="+tipo_fact+"&rut_empresa="+$scope.rut;
            
            $http.post(url).success(function(data){
              $scope.dato=data[0];              
                     $window.open($scope.dato);
               })
      
          };

    });

 
    

app.controller('facturasCrtl', function ($scope, $http, $timeout,$window) {
	 $scope.setLoading= function(loading){
         $scope.display = loading;
    };

    getFacturas();
	
    function getFacturas(){	 
	$scope.setLoading(true);  
    $http.get('ajax/getFacturas.php').success(function(data){
        $scope.list = data;
        $scope.currentPage = 1; //current page
        $scope.entryLimit = 10; //max no of items to display in a page
        $scope.filteredItems = $scope.list.length; //Initially for no filter  
        $scope.totalItems = $scope.list.length;
		$scope.setLoading(false);  
        });
		  
    };
 
   
    $scope.setPage = function(pageNo) {
        $scope.currentPage = pageNo;
    };
    $scope.filter = function() {
        $timeout(function() { 
            $scope.filteredItems = $scope.filtered.length;
        }, 10);
    };
    $scope.sort_by = function(predicate) {
        $scope.predicate = predicate;
        $scope.reverse = !$scope.reverse;
    };

    
    $scope.checkAll = function () {
        if ($scope.selectedAll) {
            $scope.selectedAll = false;
        } else {
            $scope.selectedAll = true;
        }
        angular.forEach($scope.filtered, function (data) {
            data.Selected = $scope.selectedAll;
        });

    };
	
	   $scope.Traspasar= function(folio,tipo_fact){   
	  // alert("folio y tipo de factura" + folio + ", " + tipo_fact);
	   $scope.setLoading(true);  
		var url = "ajax/traspasar_ventas.php?folio="+folio+"&tipo_fact="+tipo_fact;
               
            $http.post(url).success(function(data){

                if(!data.success){
                  //  $scope.errorEmpresa = data.errors.empresa;
                   // $scope.errorFechaR = data.errors.fechar;
                   // $scope.errorFechaC = data.errors.fechac;
                   alert("Hubo un error, llore en la playa!!");
                   //console.log(data);
                  $scope.setLoading(false);  
                } else{
				alert("Magia!");
                    getFacturas();
                }

            })
			
          };
		  
		 $scope.Sincronizar= function(folio,tipo_fact){   
	  // alert("folio y tipo de factura" + folio + ", " + tipo_fact);
	   $scope.setLoading(true);  
		var url = "ajax/sincronizar.php?folio="+folio+"&tipo_fact="+tipo_fact;
               
            $http.post(url).success(function(data){

                if(!data.success){
                  //  $scope.errorEmpresa = data.errors.empresa;
                   // $scope.errorFechaR = data.errors.fechar;
                   // $scope.errorFechaC = data.errors.fechac;
                   alert("Hubo un error, llore en la playa!!");
                   //console.log(data);
                  $scope.setLoading(false);  
                } else{
				alert("Magia!");
                    getFacturas();
                }

            })
			
          };

      $scope.Redondear= function(folio,tipo_fact,diferencia){   
    

     $scope.setLoading(true);  
     var url = "ajax/redondear.php?folio="+folio+"&tipo_fact="+tipo_fact+"&diferencia="+diferencia;
               
            $http.post(url).success(function(data){

                if(!data.success){                  
                   alert("Hubo un error, llore en la playa!!");
                   console.log(data);
                  $scope.setLoading(false);  
                } else{
        alert("Magia redonda!");
                    getFacturas();
                }

            })
      
         
              };

	
    $scope.getListado = function(cod_empresa,nro_version,fecha_consulta) {
       // alert("empresa:" + cod_empresa+ " version: "+nro_version);
         
       $scope.$on('myPostRepeatDirective', function(scope, element, attrs){  
                //work your magic   
                     
          $scope.exportData(cod_empresa,nro_version,fecha_consulta); 

         
        
       });

    
       $scope.setLoading(true);
        $http.get("ajax/listado.php?cod_empresa="+cod_empresa+"&nro_version="+nro_version).success(function(data2){
        $scope.listado2 = data2;   
       // exportData();
       //getMateriales(); 
       // setTimeout(200);

        });
   
    };
       

    $scope.exportData = function (cod_empresa,nro_version,fecha_consulta) {     
        

        var blob = new Blob([document.getElementById('exportable').innerHTML], {
            type: "application/vnd.openxmlformats-officedocument.spreadsheetml.sheet;charset=utf-8"
        });
     
       saveAs(blob, 'ResumenExistencia_Emp_'+cod_empresa+'_Fecha_'+fecha_consulta+'_version_'+nro_version+'.xls'); 
      //  saveAs(blob, 'ResumenExistencia.xls'); 
       
          $scope.setLoading(false);
      $timeout(function() {  }, 0); // wait...
    }

    
});



 app.directive('myPostRepeatDirective', function() {
  return function(scope, element, attrs) {
    if (scope.$last)setTimeout(function(){
                scope.$emit('myPostRepeatDirective', element, attrs);
            }, 1);
    }
  });
