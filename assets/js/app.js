var app = angular.module('ionicApp', ['ionic','Auth','restAPI'])

app.config(function($stateProvider, $urlRouterProvider) {
  $urlRouterProvider.otherwise('/auth')

  $stateProvider.state('app', {
    abstract: true,    
    controller:function($scope,Auth){
      $scope.isLoggedIn = Auth.user.isLoggedIn;
      $scope.$on('user:isLoggedIn',function(currentScope, options){
        Auth.user = options[0];
        $scope.isLoggedIn = true;
      })
    },
    templateUrl: 'main.html'
  })
  //auth
  $stateProvider.state('app.auth', {
      abstract: true,
      url: '/auth',
      views: {
        auth: {
          templateUrl: 'auth.html'
        }
      }
  })

  $stateProvider.state('app.auth.index', {
    url: '',
    templateUrl: 'index.html',
    controller:function($scope){
      
    }
  })

  $stateProvider.state('app.auth.login', {
    url: '/login',
    templateUrl: 'login.html',
    controller:'LoginCtrl'
  })

  $stateProvider.state('app.auth.cadastro', {
    url: '/cadastro',
    templateUrl: 'cadastro.html',
    controller:'CadastroCtrl'
  })

//restaurantes
  $stateProvider.state('app.restaurantes', {
    abstract: true,
    url: '/restaurantes',
    views: {
      restaurantes: {
        templateUrl: 'restaurantesView.html'
      }
    }
  })

  $stateProvider.state('app.restaurantes.index', {
    url: '',
    templateUrl: 'restaurantes.html',
    controller: 'RestaurantesCtrl'
  })

  $stateProvider.state('app.restaurantes.detail', {
    url: '/:restaurante',
    templateUrl: 'restaurante.html',
    controller: 'RestauranteCtrl',
    resolve: {
      restaurante: function($stateParams, restaurantesService,restAPI) {
        return restAPI.query({id:$stateParams.restaurante})
      }
    }
  })

//help
  $stateProvider.state('app.help', {
    url: '/help',
    views: {
      help: {
        templateUrl: 'help.html'
      }
    }
  })
$stateProvider.state('app.contact', {
    url: '/contact',
    views: {
      contact: {
        templateUrl: 'contact.html'
      }
    }
  })

})

app.factory('restaurantesService', function() {
  var restaurantes = [
      {title: "Take out the trash", done: true},
      {title: "Do laundry", done: false},
      {title: "Start cooking dinner", done: false}
   ]

  return {
    restaurantes: restaurantes,
    getrestaurante: function(index) {
      return restaurantes[index]
    }
  }
})

//controllersrestAPI
app.controller('LoginCtrl',function($scope,Auth,$state,$ionicLoading){
  $scope.state = $state;
  
  $scope.login = function(user){
    $scope.message = undefined;
    $ionicLoading.show({
      template: 'Loading...'
    });
    Auth.login(user).then(function(response){
        if(!response.exist){
          $scope.message = response.message
        }else{
          $scope.$emit('user:isLoggedIn',response)
          window.location.hash = "#/restaurantes"
        }
        $ionicLoading.hide()
    })
  }
})

app.controller('CadastroCtrl',function($scope,Auth,$ionicLoading){
  $scope.cadastrar = function(user){
    $ionicLoading.show({
      template: 'Loading...'
    });
    $scope.message = undefined;
    Auth.userExist(user).then(function(data){
      if(!data.user.userExist){
        Auth.cadastrar(user).then(function(response){
          $scope.$emit('user:isLoggedIn',response)
          window.location.hash = "#/restaurantes"
        })
      }else{
        $scope.message = "Nome de usuario existente"
      }
      $ionicLoading.hide()
    })
  }
})


app.controller('RestaurantesCtrl', function($scope, restaurantesService,restAPI,$ionicLoading) {
  $ionicLoading.show({
      template: 'Loading...'
    });
  
  restAPI.query().$promise.then(function(data){
    $scope.restaurantes = data
    $ionicLoading.hide()
  })
})

app.controller('RestauranteCtrl', function($scope,$http, restaurante,Auth,$ionicLoading,$state) {
  debugger
  $ionicLoading.show({
    template: 'Loading...'
  });
  var promise;
  if(Auth.user.role == 'admin'){
    promise = $http.get('api/comandasPerRestaurante/'+$state.params.restaurante)
  }else{
    promise = $http.get('api/comandasPerRestauranteAndUser/'+Auth.user.id+"/"+$state.params.restaurante)
  }
  restaurante.$promise.then(function(data){
    $scope.restaurante = data[0]
    $ionicLoading.hide()
  })

})

window.location.hash = "#/"