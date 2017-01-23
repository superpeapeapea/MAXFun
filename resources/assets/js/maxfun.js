/**
 * Created by jun on 1/22/17.
 */
GlobalMXApp = angular.module('maxfun', [], [$interpolateProvider, function($interpolateProvider) {
    $interpolateProvider.startSymbol('{%');
    $interpolateProvider.endSymbol('%}');
}]).config([$controllerProvider, function($controllerProvider){
    GlobalCathodApp.loadController = $controllerProvider.register;//for later dynamically add controller
}]).run();