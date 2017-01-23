/**
 * Created by jun on 1/22/17.
 */
GlobalMXApp = angular.module('maxfun', [],  function($interpolateProvider) {
    $interpolateProvider.startSymbol('{%');
    $interpolateProvider.endSymbol('%}');
}).controller('SpotifyArtistController',['$scope', '$http', function($scope, $http){
    $scope.stateContext = {
        'search':{'artists':[], 'page':0},
        'similar':{'artists':[], 'targetArtists':[], 'page':0}
    };
    $scope.curState = 'search';
    $scope.loadStateConfig = {'ready':0, 'loading':1, 'bottom':2};
    $scope.loadState = $scope.loadStateConfig.ready;
    $scope.isReadyToSearch = function(){
        return $scope.searchStr && $scope.searchStr.length>2;
    };

    var lastApiParam = {};
    $scope.loadMoreArtists = function(apiParam){
        if($scope.loadState == $scope.loadStateConfig.ready){
            $scope.loadState = $scope.loadStateConfig.loading;
            if(apiParam===undefined){
                lastApiParam['params'].page = $scope.stateContext[$scope.curState].page;
                apiParam = lastApiParam;
            }else{
                lastApiParam = apiParam;
            }
            var p = $http(apiParam);
            p.success(function(response, status, headers, config){
                $scope.loadState = $scope.loadStateConfig.ready;
                if(response.code==0){
                    if(response.data.length==0){
                        $scope.loadState = $scope.loadStateConfig.bottom;
                    }else{
                        $scope.stateContext[$scope.curState].artists = $scope.stateContext[$scope.curState].artists.concat(response.data);
                        $scope.stateContext[$scope.curState].page += 1;
                    }

                }else{
                    alert(response.message);
                }
            });
            p.error(function(){
                $scope.loadState = $scope.loadStateConfig.ready;
                alert('Request failed.');
            });
        }

    };

    $scope.changeState = function(toState){
        $scope.loadState = $scope.loadStateConfig.ready;
        $scope.stateContext[$scope.curState].page = 0;
        if($scope.curState != toState){
            $scope.curState = toState;
        }
    };

    $scope.searchSimilarByArtistId = function(artist){
        $scope.stateContext.similar.artists = [];
        $scope.stateContext.similar.targetArtists = [artist];
        $scope.changeState('similar');
        $scope.loadMoreArtists({
            'method': 'GET',
            'url': '/artist/similar/'+artist.id,
            'page':$scope.stateContext[$scope.curState].page
        });
    };

    $scope.searchByArtistName = function(){
        $scope.changeState('search');
        if($scope.isReadyToSearch()){
            $scope.stateContext.search.artists = [];
            $scope.loadMoreArtists({
                method: 'GET',
                url: '/artist/search',
                params:{'search_str':$scope.searchStr, 'page':$scope.stateContext[$scope.curState].page}
            });
        }
    };


}]).run();
