<!DOCTYPE head PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<style>
body{
	background-color:#fff;	
    text-align:center;
}
section#main{
	min-width:400px;
	max-width:1500px;
	display:inline-block;
	width: 68%;
	border-radius:10px;	
	background-color:#F1F3F3;	
	min-height:500px;
	padding: 3rem;
}
input#search-input{
	padding: 6px 1rem;
    color: black;
    -webkit-box-flex: 1;
    -moz-box-flex: 1;
    -ms-flex: 1;
    flex: 1;
    border: 0;
    background: rgba(255,255,255,0.6);
    border-radius: 6px;
    box-shadow: inset 2px 2px rgba(0,0,0,0.5), 2px 2px 2px rgba(255,255,255,0.5);
    font-family: inherit;
    outline: 0;
    line-height: 2.5rem;
    width: 68%;
	display-inline:block;
	font-size:2rem;
	vertical-align: middle;
}
#search-button{
	display: inline-block;
    vertical-align: middle;
    border-radius: 0.3rem;
    width: 3rem;
    height: 2.1rem;
    background-color: #17C0D0;
    padding: 0.7rem 0.8rem !important;
	white-space: nowrap;
	cursor:pointer;
}
#search-button.disabled{
    backgroud-color:#7a7d7d;
}
svg.icon-search{
	    fill: white;
	width: 2rem;
    height: 2rem;
}
.unit{
	width:100%;
}


.card{
    display: inline-block;
    padding: 1rem;    
    font-size: 1.2rem;
    border-radius: 0.3rem;
    background-color: #fff;
    /* border: solid 1px #a9e8e5; */
    box-shadow: 2px 2px 2px 1px rgba(0, 0, 0, 0.2);
    width: 350px;
	margin:0.5rem;
	vertical-align: top;
	text-align:center;
	
}

#target-unit .card{
	border:4px #17C0D0 solid;
}
#target-unit{
	position:relative;
	display:inline-block;
	text-align:center;
}
.card .avatar{
	display: inline-block;
    width: 100%;
}
.card .avater-img{
	display: inline-block;
    width: 100%;
}
.card .name{
    width: 80%;
    display: inline-block;
    font-size: 1.2rem;
	vertical-align: top;
	font-weight:500;
}

.explain{
	font-size: 0.8rem;
	color: #807a7a	
}
.card .follow{
    width: 80%;
    display: inline-block;
	vertical-align: top;
}
.card .follow .number{
	font-size: 1rem;
}

#result-unit{
	margin:2rem 0;
	text-align:left;
	position: relative;
}

.card .tip{
	font-size:2rem;
	color:#17C0D0;
	line-height:3rem;
}

.card .genre-tag{
    color: #17C0D0;
	display: inline-block;
    border: solid 1px #a9e8e5;
    margin: 0.5rem 1rem;
    line-height: 2rem;
    padding: 0 0.5rem;
    border-radius: 10px;
}
.card .genre-tag.disabled{
	color: #807a7a;
	border-color:#807a7a;
}
.card .genre-tag.highlight{
    border: solid 2px blue;	
	color:blue;
}

.card.split{
	width:95%;
	position:absolute;
	bottom:-7rem;
}

#realated-title.card.split{
	position:relative;
	bottom:0;
	margin:1rem 0;
}
.clickable{
	cursor:pointer;
}
</style>

</head>
<body ng-app="maxfun">
	<svg style="display:none">
		<symbol id="icon-search" viewBox="0 0 100 100"><title>icon-search</title><path d="M80.65 66.78a33.55 33.55 0 0 1-47.44-47.44 33.55 33.55 0 1 1 47.44 47.44zm6.73-54.16a43.06 43.06 0 0 0-65.32 55.71L2 88.39A6.8 6.8 0 0 0 11.61 98l20.06-20.06a43.06 43.06 0 0 0 55.71-65.32z"></path></symbol>
	</svg>
	<section id="main" ng-controller="SpotifyArtistController">
		<div id="search-unit" class="unit">
    		<input type="search" placeholder="Search by Artist Name" id="search-input" ng-model="searchStr" onkeydown = "if(event.keyCode == 13) document.getElementById('search-button').click()">
    		<div type="submit" id="search-button"  class="button button-header {% isReadyToSearch() ? '' : 'diabled'%}" ng-click="searchByArtistName()">
                <svg class="icon-search">
                  <use xlink:href="#icon-search"></use>
                </svg>
             </div>
		</div>
		<div id="target-unit" class="unit" ng-if="curState=='similar'" ng-cloak>
			<div class="card"  ng-include="'card.html'"  ng-cloak ng-repeat="artist in stateContext[curState].targetArtists"></div>
			<div class="card split" id="realated-title" ng-cloak ng-repeat="artist in stateContext[curState].targetArtists">
				<span class="tip"><span ng-repeat="artist in stateContext[curState].targetArtists">{%artist.name%}</span> <span class="explain">related artists</span></span>
			</div>
		</div>
		<div id="result-unit" masonry class="unit"  load-images="false">
        	<div class="card masonry-brick"  ng-include="'card.html'" ng-class="{true:'clickable'}[artist.genres.length>0]"ng-click="artist.genres.length>0 && searchSimilarByArtistId(artist)"  ng-cloak ng-repeat="artist in stateContext[curState].artists"></div>
        	<div class="card split" ng-show="loadState==loadStateConfig.loading">
        		<img src="/img/loading.gif" style="height:4rem;display:inline-block;">
        	</div>
        	<div class="card split clickable" ng-show="loadState==loadStateConfig.ready && stateContext[curState].artists.length>0" ng-click="loadMoreArtists()">
        		<span class="tip">Load More</span>
        	</div>
        	<div class="card split" style="width:100%;margin:0;" ng-show="loadState==loadStateConfig.bottom && stateContext[curState].artists.length==0">
        		<span  class="tip">Sorry, no results.</span>
        	</div>
		</div>
	</section>
	<script src="//ajax.googleapis.com/ajax/libs/angularjs/1.5.7/angular.min.js"></script>
    
    
    <!-- For Masonry-Angular. Can be packed up for real project. -->
<!--       <script src="node_modules/jquery/dist/jquery.js" ></script> -->
<!--       <script src="node_modules/jquery-bridget/jquery-bridget.js" ></script> -->
<!--       <script src="node_modules/ev-emitter/ev-emitter.js" ></script> -->
<!--       <script src="node_modules/desandro-matches-selector/matches-selector.js" ></script> -->
<!--       <script src="node_modules/fizzy-ui-utils/utils.js" ></script> -->
<!--       <script src="node_modules/get-size/get-size.js" ></script> -->
<!--       <script src="node_modules/outlayer/item.js" ></script> -->
<!--       <script src="node_modules/outlayer/outlayer.js" ></script> -->
<!--       <script src="node_modules/masonry-layout/dist/masonry.pkgd.js" ></script> -->
<!--       <script src="node_modules/imagesloaded/imagesloaded.js" ></script> -->
<!--       <script src="node_modules/angular-masonry/angular-masonry.js" ></script> -->
	
    <script src="/js/maxfun.js"></script>
    
    <script type="text/ng-template" id="card.html">
				<header class="top">
					<div class="avatar">
						<img ng-if="artist.images.length>0" ng-src="{%::artist.images[0].url%}" class="avater-img" style="height:{%::(350*(artist.images[0].height/artist.images[0].width))+'px'%}">
					</div>
					<div class="user-info">
						<div class="name">{%::artist.name%}</div>
						<div class="follow explain"><span class="number">{%::artist.followers.total%}</span> Followers</div>
					</div>
				</header>
				<div>
					<span class="genre-tag" ng-class="{true: 'highlight', false: ''}[((curState=='similar')&&(stateContext.similar.targetGenres.indexOf(genre)>-1))]" ng-repeat="genre in artist.genres">
                    {%::genre%}
                    </span>
                    <span class="genre-tag disabled" ng-if="artist.genres.length==0">No Genres</span>
				</div>
    </script>	
    
	<script src="//unpkg.com/masonry-layout@4.1.1/dist/masonry.pkgd.min.js"></script>
</body>
</html>


