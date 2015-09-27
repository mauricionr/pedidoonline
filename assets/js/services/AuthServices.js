;(function() {
	var auth = angular.module('Auth',[]);
	auth.factory('Auth',function($http,$q){
		var Auth = {};
        Auth.user = {}
        Auth.user.userExist = false
        Auth.login = function(user){
	      	Auth.tempUser = user
			var defer = $q.defer()
			Auth.userExist(user).then(function(response){
				if(response.user.userExist){
					Auth.continueLogin(Auth.tempUser).then(function(response){
						defer.resolve(response)
					})
				}
				else defer.resolve({exist:false,message:"usuario nao existe"})
			})
			return defer.promise
	    };
	      Auth.continueLogin = function(user){
          	var defer = $q.defer();
			$http.get('api/login/'+user.username+'/'+user.password+'').then(function(response){
				if(response.data.length > 0){
					response.data.exist = true
					Auth.user.isLoggedIn = true
					data = response.data
					Auth.Storage.set(data) 
					defer.resolve(data)
				}
				else {
					defer.resolve({exist:false,message:"Senha Invalida"})
				}
			});
			return defer.promise
          };
          Auth.userExist = function(user){
	          	var defer = $q.defer()
	            $http.get('api/userByUserName/'+user.username).success(function(data) {
	              Auth.user.userExist = data.length > 0
	              defer.resolve(Auth)
	            });
	            return defer.promise
          };
          Auth.cadastrar = function(user){
          	var defer  = $q.defer()
            $http.post('api/add_user', user).then(function(response){
            	defer.resolve(response)
            	Auth.Storage.set(response)
            })
            return defer.promise
          };
          Auth.logout = function(){
          	Auth.Storage.del()
          	window.location.hash = "#/"
          };
          
          Auth.Storage = {}
          Auth.Storage.set = function(user){
          	window.localStorage.setItem('sku',JSON.stringify(user))
          }
          Auth.Storage.get = function(){
          	return JSON.parse(window.localStorage.getItem('sku'))
          }
          Auth.Storage.del = function(){
          	window.localStorage.removeItem('sku')	
          }
		return Auth;
	});
	var restAPI = angular.module('restAPI',['ngResource']);
	restAPI.factory('restAPI',function($resource){
		return $resource('api/restaurantes/:id', { id: '@_id' }, {
		    update: {
		      method: 'PUT'
		    }
		  });
	})
})();