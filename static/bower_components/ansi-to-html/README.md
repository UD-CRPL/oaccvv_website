

Ansi to Html
============

Based on the coffee script code in https://npmjs.org/package/ansi-to-html and translated to js with: http://js2coffee.org/ and stylized a bit.

I don't know too much about modules and packages in js or node, but this works nice in angular.

Usage
=====

Just include the file and in any place you want to use add the dependency to your module: `var app = angular.module('app', ['ansiToHtml'])` and inject the object `ansi2html`: 

    var app = angular.module('app', ['ansiToHtml']);
    
    app.controller('contr', function($scope, ansi2html) {
         $scope.text = ansi2html.toHtml("some ansi text"); 
         $scope.bbCode = ansi2html.toBBCode("some ansi text");
    })
   
bower.json
=====
    "dependencies": { 
        "ansi-to-html": "~0.0.4"    
    }
