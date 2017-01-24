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
.card .follow{
    width: 80%;
    display: inline-block;
	vertical-align: top;
	font-size: 0.8rem;
	color: #807a7a
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
				<span class="tip"><span ng-repeat="artist in stateContext[curState].targetArtists">{%artist.name%}</span> related artists</span>
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
	<script type="text/javascript">
		var tttest = {"data":[{"external_urls":{"spotify":"https:\/\/open.spotify.com\/artist\/3nFkdlSjzX9mRTtwJOzDYB"},"followers":{"href":null,"total":2340690},"genres":["east coast hip hop","hip hop","pop rap","rap"],"href":"https:\/\/api.spotify.com\/v1\/artists\/3nFkdlSjzX9mRTtwJOzDYB","id":"3nFkdlSjzX9mRTtwJOzDYB","images":[{"height":667,"url":"https:\/\/i.scdn.co\/image\/f58a98bc92cd2dc7020fc45a45eebb29380048ab","width":1000},{"height":427,"url":"https:\/\/i.scdn.co\/image\/9dd1bab34761764a125508534e443a9b245a9b56","width":640},{"height":133,"url":"https:\/\/i.scdn.co\/image\/620b2133ab3f20045c0d9669e706bbf11786cf78","width":200},{"height":43,"url":"https:\/\/i.scdn.co\/image\/5018ebe8a0d1060e6b39b2021132c082be1a913d","width":64}],"name":"JAY Z","popularity":87,"type":"artist","uri":"spotify:artist:3nFkdlSjzX9mRTtwJOzDYB"},{"external_urls":{"spotify":"https:\/\/open.spotify.com\/artist\/4XDi67ZENZcbfKnvMnTYsI"},"followers":{"href":null,"total":113241},"genres":["dance pop","k-pop","korean pop"],"href":"https:\/\/api.spotify.com\/v1\/artists\/4XDi67ZENZcbfKnvMnTYsI","id":"4XDi67ZENZcbfKnvMnTYsI","images":[{"height":640,"url":"https:\/\/i.scdn.co\/image\/974df9331c87f0b84f138d0540fef2d897c0acc6","width":640},{"height":300,"url":"https:\/\/i.scdn.co\/image\/4d2faf04c08e91e64e0556a54d3f6159b9481231","width":300},{"height":64,"url":"https:\/\/i.scdn.co\/image\/f833058841696d7c9393855f9db0c82e9aff7fc1","width":64}],"name":"Jay Park","popularity":67,"type":"artist","uri":"spotify:artist:4XDi67ZENZcbfKnvMnTYsI"},{"external_urls":{"spotify":"https:\/\/open.spotify.com\/artist\/28ExwzUQsvgJooOI0X1mr3"},"followers":{"href":null,"total":106844},"genres":["alternative hip hop","dirty south rap","gangster rap","hip hop","pop rap","rap","southern hip hop","trap music","underground hip hop"],"href":"https:\/\/api.spotify.com\/v1\/artists\/28ExwzUQsvgJooOI0X1mr3","id":"28ExwzUQsvgJooOI0X1mr3","images":[{"height":640,"url":"https:\/\/i.scdn.co\/image\/58c75b3ebfb272eca88635e5f3f0c5a260bd228c","width":640},{"height":300,"url":"https:\/\/i.scdn.co\/image\/d8b8d22ac04b33f347b8eab9bc02b8a826a29969","width":300},{"height":64,"url":"https:\/\/i.scdn.co\/image\/97e82a9faa4b67613e4da7b66f9f5abe8a06837f","width":64}],"name":"Jay Rock","popularity":69,"type":"artist","uri":"spotify:artist:28ExwzUQsvgJooOI0X1mr3"},{"external_urls":{"spotify":"https:\/\/open.spotify.com\/artist\/0IVcLMMbm05VIjnzPkGCyp"},"followers":{"href":null,"total":135528},"genres":["alternative hip hop","detroit hip hop","hip hop","indie r&b","neo soul","underground hip hop"],"href":"https:\/\/api.spotify.com\/v1\/artists\/0IVcLMMbm05VIjnzPkGCyp","id":"0IVcLMMbm05VIjnzPkGCyp","images":[{"height":1312,"url":"https:\/\/i.scdn.co\/image\/bf1bd61ca9468f0f6328f4095d376826380afe95","width":1000},{"height":840,"url":"https:\/\/i.scdn.co\/image\/2bd9d99470ef41c8ea60ab31d4b02a411bcc65c6","width":640},{"height":262,"url":"https:\/\/i.scdn.co\/image\/14778c27dc3944e3895de3b455fa0fdfa5edd725","width":200},{"height":84,"url":"https:\/\/i.scdn.co\/image\/969adfa36461a09f8a7d71048f6770c601b5b124","width":64}],"name":"J Dilla","popularity":64,"type":"artist","uri":"spotify:artist:0IVcLMMbm05VIjnzPkGCyp"},{"external_urls":{"spotify":"https:\/\/open.spotify.com\/artist\/0TkqXdyWLsssJH7okthMPQ"},"followers":{"href":null,"total":41063},"genres":["alternative hip hop","hip hop","pop rap","southern hip hop","underground hip hop"],"href":"https:\/\/api.spotify.com\/v1\/artists\/0TkqXdyWLsssJH7okthMPQ","id":"0TkqXdyWLsssJH7okthMPQ","images":[{"height":640,"url":"https:\/\/i.scdn.co\/image\/baa2da790cc3956af2c2eea44c5694bede2ce9e9","width":640},{"height":300,"url":"https:\/\/i.scdn.co\/image\/6a2716609079dd47a5a9e4691f69bfe6967df5ff","width":300},{"height":64,"url":"https:\/\/i.scdn.co\/image\/e937ede7a46df212a88841329c15761efc5c756a","width":64}],"name":"Jay Electronica","popularity":60,"type":"artist","uri":"spotify:artist:0TkqXdyWLsssJH7okthMPQ"},{"external_urls":{"spotify":"https:\/\/open.spotify.com\/artist\/4pADjHPWyrlAF0FA7joK2H"},"followers":{"href":null,"total":220709},"genres":["dance pop","desi","indian pop","pop","pop rap","post-teen pop","r&b","urban contemporary","viral pop"],"href":"https:\/\/api.spotify.com\/v1\/artists\/4pADjHPWyrlAF0FA7joK2H","id":"4pADjHPWyrlAF0FA7joK2H","images":[{"height":640,"url":"https:\/\/i.scdn.co\/image\/b16f5d691d7e918b6171f89f3e72ea70c4704e80","width":640},{"height":320,"url":"https:\/\/i.scdn.co\/image\/41461f45b15f83173fa0c69455f5103ef149352b","width":320},{"height":160,"url":"https:\/\/i.scdn.co\/image\/345d6a35edbe8de85ce1a183f45667da6dbf8ac1","width":160}],"name":"Jay Sean","popularity":71,"type":"artist","uri":"spotify:artist:4pADjHPWyrlAF0FA7joK2H"},{"external_urls":{"spotify":"https:\/\/open.spotify.com\/artist\/6PhukEDmCNt2jyDZnM4TrK"},"followers":{"href":null,"total":2709},"genres":["west coast trap"],"href":"https:\/\/api.spotify.com\/v1\/artists\/6PhukEDmCNt2jyDZnM4TrK","id":"6PhukEDmCNt2jyDZnM4TrK","images":[{"height":640,"url":"https:\/\/i.scdn.co\/image\/5990d7f4ac9d465081b591a6ae7093124826d6a8","width":640},{"height":300,"url":"https:\/\/i.scdn.co\/image\/3b80f2483e06175fe0b7bd2468425991fbf50c5e","width":300},{"height":64,"url":"https:\/\/i.scdn.co\/image\/514b09f7ca359ee10ef9afcdfa58b2af48882663","width":64}],"name":"Jay Ant","popularity":58,"type":"artist","uri":"spotify:artist:6PhukEDmCNt2jyDZnM4TrK"},{"external_urls":{"spotify":"https:\/\/open.spotify.com\/artist\/1V6rvT87qMQvo6HAixjlpY"},"followers":{"href":null,"total":16482},"genres":["azontobeats","deep pop r&b","dwn trap"],"href":"https:\/\/api.spotify.com\/v1\/artists\/1V6rvT87qMQvo6HAixjlpY","id":"1V6rvT87qMQvo6HAixjlpY","images":[{"height":563,"url":"https:\/\/i.scdn.co\/image\/36a7c171dd5ca56ae8c839357386bd6a91d42e7a","width":1000},{"height":360,"url":"https:\/\/i.scdn.co\/image\/967631516ea578ffd75360d5c931de7a822fb2d8","width":640},{"height":113,"url":"https:\/\/i.scdn.co\/image\/7ca6e4966c841cc4d7a0b8dc9cdf37ed9fc39052","width":200},{"height":36,"url":"https:\/\/i.scdn.co\/image\/2c936a955bf8a2abdb1c68b6e21409cf12a4047f","width":64}],"name":"Ayo Jay","popularity":64,"type":"artist","uri":"spotify:artist:1V6rvT87qMQvo6HAixjlpY"},{"external_urls":{"spotify":"https:\/\/open.spotify.com\/artist\/12SPNXi0aDpFt0rMVbmLrr"},"followers":{"href":null,"total":46309},"genres":["big room","deep big room","edm","electro house","house","progressive electro house","tropical house"],"href":"https:\/\/api.spotify.com\/v1\/artists\/12SPNXi0aDpFt0rMVbmLrr","id":"12SPNXi0aDpFt0rMVbmLrr","images":[{"height":640,"url":"https:\/\/i.scdn.co\/image\/7e425114a44bc4db384aa006f4f1c4cbaff2987d","width":640},{"height":320,"url":"https:\/\/i.scdn.co\/image\/f9254b0c2dd4010e51e6c133de1b8eff1357e86d","width":320},{"height":160,"url":"https:\/\/i.scdn.co\/image\/ea3be18b94cd7fe753ae30a4b873ef397481d0c1","width":160}],"name":"Jay Hardway","popularity":64,"type":"artist","uri":"spotify:artist:12SPNXi0aDpFt0rMVbmLrr"},{"external_urls":{"spotify":"https:\/\/open.spotify.com\/artist\/2elBjNSdBE2Y3f0j1mjrql"},"followers":{"href":null,"total":187340},"genres":["c-pop","mandopop","taiwanese pop"],"href":"https:\/\/api.spotify.com\/v1\/artists\/2elBjNSdBE2Y3f0j1mjrql","id":"2elBjNSdBE2Y3f0j1mjrql","images":[{"height":563,"url":"https:\/\/i.scdn.co\/image\/8a83a7db473a494498c0745ff7ffb88d0bf1a09a","width":1000},{"height":360,"url":"https:\/\/i.scdn.co\/image\/721a9d091a7479a03b865ccadb3813fe93012c42","width":640},{"height":113,"url":"https:\/\/i.scdn.co\/image\/77837987df6646d947f997ea1917fd2726dec3a2","width":200},{"height":36,"url":"https:\/\/i.scdn.co\/image\/d63621a6c62e265994d02bdc0d378b26376de82f","width":64}],"name":"\u5468\u6770\u502b","popularity":71,"type":"artist","uri":"spotify:artist:2elBjNSdBE2Y3f0j1mjrql"},{"external_urls":{"spotify":"https:\/\/open.spotify.com\/artist\/2TLYSzGyVYkxAgYSCqUnQj"},"followers":{"href":null,"total":16450},"genres":["deep indie r&b","escape room","indie r&b","underground hip hop"],"href":"https:\/\/api.spotify.com\/v1\/artists\/2TLYSzGyVYkxAgYSCqUnQj","id":"2TLYSzGyVYkxAgYSCqUnQj","images":[{"height":640,"url":"https:\/\/i.scdn.co\/image\/600c10e4275ee5f11fec7d37a9268a7996c8ac6b","width":640},{"height":320,"url":"https:\/\/i.scdn.co\/image\/894c3e08e3dda55fb12385928f31c7c3cb5e7faa","width":320},{"height":160,"url":"https:\/\/i.scdn.co\/image\/bcc1aeae9693b0924ce89ca7c356580c0cbe76ba","width":160}],"name":"Jay Prince","popularity":56,"type":"artist","uri":"spotify:artist:2TLYSzGyVYkxAgYSCqUnQj"},{"external_urls":{"spotify":"https:\/\/open.spotify.com\/artist\/2l2o6ibYa7h1w4HwaS1uZV"},"followers":{"href":null,"total":2016},"genres":["west coast trap"],"href":"https:\/\/api.spotify.com\/v1\/artists\/2l2o6ibYa7h1w4HwaS1uZV","id":"2l2o6ibYa7h1w4HwaS1uZV","images":[{"height":1000,"url":"https:\/\/i.scdn.co\/image\/be72f42b8395c47e5d12ea7da45cc8465ade115d","width":1000},{"height":640,"url":"https:\/\/i.scdn.co\/image\/6c06bf774842ff68baf924bfd4ca22d3979152e9","width":640},{"height":200,"url":"https:\/\/i.scdn.co\/image\/ba9dea9406fd04cee86d2fbde41bbeb8b3f15b99","width":200},{"height":64,"url":"https:\/\/i.scdn.co\/image\/90603d3ebee8fb260ce7711a792abc772e084961","width":64}],"name":"Jay 305","popularity":50,"type":"artist","uri":"spotify:artist:2l2o6ibYa7h1w4HwaS1uZV"},{"external_urls":{"spotify":"https:\/\/open.spotify.com\/artist\/3ac6q417BsfHwNCjCzRM4u"},"followers":{"href":null,"total":54},"genres":[],"href":"https:\/\/api.spotify.com\/v1\/artists\/3ac6q417BsfHwNCjCzRM4u","id":"3ac6q417BsfHwNCjCzRM4u","images":[{"height":640,"url":"https:\/\/i.scdn.co\/image\/de7ded532afc583a1f924b0a1c62cd28de1f7552","width":640},{"height":300,"url":"https:\/\/i.scdn.co\/image\/3f2c64ce04c82746740d5c793c9501ca024e0f22","width":300},{"height":64,"url":"https:\/\/i.scdn.co\/image\/67affe2ad3c1d53b573731ea9be4668d6987dc85","width":64}],"name":"Jay Tose","popularity":51,"type":"artist","uri":"spotify:artist:3ac6q417BsfHwNCjCzRM4u"},{"external_urls":{"spotify":"https:\/\/open.spotify.com\/artist\/1d0f2TQJF6CgmJ9o52kH4x"},"followers":{"href":null,"total":433},"genres":[],"href":"https:\/\/api.spotify.com\/v1\/artists\/1d0f2TQJF6CgmJ9o52kH4x","id":"1d0f2TQJF6CgmJ9o52kH4x","images":[{"height":640,"url":"https:\/\/i.scdn.co\/image\/9ae154d09f3b98bc9776d398c34a7f777c387959","width":640},{"height":300,"url":"https:\/\/i.scdn.co\/image\/29274774575871f57274a3d1accbc9ab487f36aa","width":300},{"height":64,"url":"https:\/\/i.scdn.co\/image\/f57aeb8a334f1de69ddf257e1d2b8ce6aa483a55","width":64}],"name":"Cassius Jay","popularity":47,"type":"artist","uri":"spotify:artist:1d0f2TQJF6CgmJ9o52kH4x"},{"external_urls":{"spotify":"https:\/\/open.spotify.com\/artist\/0p7L2MImTqtWEAlMN9qHYx"},"followers":{"href":null,"total":5227},"genres":[],"href":"https:\/\/api.spotify.com\/v1\/artists\/0p7L2MImTqtWEAlMN9qHYx","id":"0p7L2MImTqtWEAlMN9qHYx","images":[{"height":640,"url":"https:\/\/i.scdn.co\/image\/0e8091e7fffb17de919ce6f432e96bb181aec0d9","width":640},{"height":320,"url":"https:\/\/i.scdn.co\/image\/04cfe28627222046232a07376b0b64709749f8ef","width":320},{"height":160,"url":"https:\/\/i.scdn.co\/image\/47d73487a98d3dd63761e0335953f28e5c7c7fd4","width":160}],"name":"Jay Cosmic","popularity":54,"type":"artist","uri":"spotify:artist:0p7L2MImTqtWEAlMN9qHYx"},{"external_urls":{"spotify":"https:\/\/open.spotify.com\/artist\/5SIWtJB0dlWtIF2hmChoTG"},"followers":{"href":null,"total":973},"genres":[],"href":"https:\/\/api.spotify.com\/v1\/artists\/5SIWtJB0dlWtIF2hmChoTG","id":"5SIWtJB0dlWtIF2hmChoTG","images":[{"height":640,"url":"https:\/\/i.scdn.co\/image\/68e1e059434a587c3acd2e24dc35d7968e3cb39d","width":640},{"height":300,"url":"https:\/\/i.scdn.co\/image\/8b12daef4c05a19e3452cb5c1b0c3eba046a3e44","width":300},{"height":64,"url":"https:\/\/i.scdn.co\/image\/c116bb4532cf04d8d09a91f11eb6bf1cb6dfaf6c","width":64}],"name":"Jay Karama","popularity":51,"type":"artist","uri":"spotify:artist:5SIWtJB0dlWtIF2hmChoTG"},{"external_urls":{"spotify":"https:\/\/open.spotify.com\/artist\/6aiFCgyKNwF9Rv5TOxnE8E"},"followers":{"href":null,"total":5581},"genres":["deep underground hip hop","underground hip hop"],"href":"https:\/\/api.spotify.com\/v1\/artists\/6aiFCgyKNwF9Rv5TOxnE8E","id":"6aiFCgyKNwF9Rv5TOxnE8E","images":[{"height":1000,"url":"https:\/\/i.scdn.co\/image\/7bbe6f7dd41ca95080a55b8607808ebad171a8ce","width":1000},{"height":640,"url":"https:\/\/i.scdn.co\/image\/21175207105c39d67c33424dd5ce0597a28fe61a","width":640},{"height":200,"url":"https:\/\/i.scdn.co\/image\/31b6efd4beb6703e14c489e087ae28b39dcaae27","width":200},{"height":64,"url":"https:\/\/i.scdn.co\/image\/3a02a59f4ecce6ca4c2e98780ba2242c0c424ce3","width":64}],"name":"Jay IDK","popularity":46,"type":"artist","uri":"spotify:artist:6aiFCgyKNwF9Rv5TOxnE8E"},{"external_urls":{"spotify":"https:\/\/open.spotify.com\/artist\/4vUAD0POkGvO6J9c9hv6qR"},"followers":{"href":null,"total":11982},"genres":["electro house"],"href":"https:\/\/api.spotify.com\/v1\/artists\/4vUAD0POkGvO6J9c9hv6qR","id":"4vUAD0POkGvO6J9c9hv6qR","images":[{"height":600,"url":"https:\/\/i.scdn.co\/image\/28d398c6a374b0d09df03a065a018174f6231724","width":600},{"height":300,"url":"https:\/\/i.scdn.co\/image\/e02eba07215871e55fed903c3fda7aa5d8757c2f","width":300},{"height":64,"url":"https:\/\/i.scdn.co\/image\/fb96e9250ca3c5ae4d7829e7e58bf9946a13e9c1","width":64}],"name":"Lazy Jay","popularity":55,"type":"artist","uri":"spotify:artist:4vUAD0POkGvO6J9c9hv6qR"},{"external_urls":{"spotify":"https:\/\/open.spotify.com\/artist\/1wmiQ6ytATiGnJs6uFluKO"},"followers":{"href":null,"total":2638},"genres":["bay area indie","garage psych","indie garage rock","indie psych-rock","indie punk","preverb"],"href":"https:\/\/api.spotify.com\/v1\/artists\/1wmiQ6ytATiGnJs6uFluKO","id":"1wmiQ6ytATiGnJs6uFluKO","images":[{"height":640,"url":"https:\/\/i.scdn.co\/image\/c1569039d35f0f0895121f1d31b1920b3ffa7b21","width":640},{"height":320,"url":"https:\/\/i.scdn.co\/image\/de458fc37ea9a0c7329cfddce1a7913ff220b271","width":320},{"height":160,"url":"https:\/\/i.scdn.co\/image\/c7f7803ca8f04ad59c6b7fa3b344a98d8ddb89d3","width":160}],"name":"Jay Som","popularity":43,"type":"artist","uri":"spotify:artist:1wmiQ6ytATiGnJs6uFluKO"},{"external_urls":{"spotify":"https:\/\/open.spotify.com\/artist\/3cAGUcRbwsFHwKbJv8FT4T"},"followers":{"href":null,"total":21400},"genres":["alternative rock","anti-folk","dance-punk","experimental rock","freak folk","garage pop","garage psych","garage punk","garage rock","indie garage rock","indie punk","indie rock","lo-fi","neo-psychedelic","noise pop","noise rock","nu gaze","post-hardcore","punk","punk blues"],"href":"https:\/\/api.spotify.com\/v1\/artists\/3cAGUcRbwsFHwKbJv8FT4T","id":"3cAGUcRbwsFHwKbJv8FT4T","images":[{"height":640,"url":"https:\/\/i.scdn.co\/image\/6f28f6508a5962e51ad951c68cd8503d451b25aa","width":640},{"height":300,"url":"https:\/\/i.scdn.co\/image\/db4f3a9d4bb3e91b3875ffa18d3afb6734692645","width":300},{"height":64,"url":"https:\/\/i.scdn.co\/image\/a896337d8ef1d2aed67411912129720102e4735b","width":64}],"name":"Jay Reatard","popularity":44,"type":"artist","uri":"spotify:artist:3cAGUcRbwsFHwKbJv8FT4T"}],"code":0,"message":null};
	</script>
    
    
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
						<div class="follow"><span class="number">{%::artist.followers.total%}</span> Followers</div>
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


