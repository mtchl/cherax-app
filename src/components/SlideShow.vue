
<template>

	<div class="outer-wrapper" :class="{'hide':hideWrapper}">
		
			<div class="slide back" :class="{'show': show =='back'&&backLoaded, 'nopad':back.class=='nopad', 'contain': back.layout == 'contain', 'noaspect':back.aspect=='false'}">
				
				<img v-if="back.type == 'img' && back.url" :src="back.url" @load="onLoad('back')">
				
				<video v-if="back.type == 'vid' && back.url" :src="back.url" @play="onLoad('back')" autoplay muted loop></video>
				
			</div>

			<div class="slide front" :class="{'show': show=='front'&&frontLoaded, 'nopad':front.class=='nopad', 'contain': front.layout == 'contain', 'noaspect':front.aspect=='false'}">
				
				<img v-if="front.type == 'img' && front.url" :src="front.url" @load="onLoad('front')" > 
				
				<video v-if="front.type == 'vid' && front.url" :src="front.url" @play="onLoad('front')"  autoplay muted loop></video>
				
			</div>

	</div>

</template>








<script>
export default {

  name: 'SlideShow',
  props: ['slide','initSlide','hidden'],

  data () {
    return {
    	back:{url:this.initSlide, type:'img', loaded:false},
    	//back:{url:null, type:null, loaded:false},
    	front:{url:null, type:null, loaded:false},
    	show:"back",
    	frontLoaded:false,
    	backLoaded:false,
    	hideWrapper:false,
    	// playingStep:null
    	// timeoutId:null
    }
  },

  methods:{
  	onLoad(slide){
  		 console.log(slide + " loaded")

  		if (slide == "front") {
        	this.frontLoaded = true;
        	// this.backLoaded = false;
		}
        
        if (slide == "back" ) {
        	this.backLoaded = true;
        	// this.frontLoaded = false;
        }
  	
  	}

  },

  watch:{
  	slide(newSlide,oldSlide){
  		 console.log(newSlide)

  		 if (this.back.url == newSlide.url) {
  		 	this.show = "back";
  		 	console.log("re-showing back")
  		 	return
  		 } else if (this.front.url == newSlide.url) {
  		 	this.show = "front";
  		 	console.log("re-showing front")
  		 	return
  		 }

  		// console.log(this.slide)

  		if (this.show == "back"){
  			this.front = newSlide;
  			this.show = "front"
  			console.log("new slide front")
  		} else if (this.show == "front"){
  			this.back = newSlide;
  			this.show = "back"
  			console.log("new slide back")
  		}
  		//this.playingStep = newSlide.step;
  	},

  	hidden(newvalue,oldvalue){
  		console.log("hidden " + newvalue)
  		this.hideWrapper = newvalue;
  		if (this.hideWrapper){ // turn off any showing slides so they don't reappear
  			this.frontLoaded = false;
  			this.backLoaded = false;
  		}

  	}

  	// animate(newAnimate,oldAnimate){
  	// 	if (this.show == "front") this.front.animate = JSON.parse(newAnimate);
  	// 	if (this.show == "back") this.back.animate = JSON.parse(newAnimate);
  	// 	console.log(JSON.parse(newAnimate))
  	// }
  }
}
</script>

<style lang="css" scoped>

	.outer-wrapper{
		position:absolute;
		left:0;
		right:0;
		margin:0 auto;
		
		width:100%;
		max-width:1400px;

		height:100vh;
		transition: opacity 1s;
		opacity:1;
	}

	.outer-wrapper.hide{
		opacity:0;
	}

	.slide{
		position: absolute;
	    top: 50%;
	    left: 50%;
	    transform: translate(-50%, -50%); 
/*	    min-width:80vw;*/
	    width: 95%;
/*		width:auto;*/
	    height: auto;
	    max-height:90vh;

	    transition: opacity 1.5s;
		opacity:0;

		border: 1px solid black;
		box-shadow: 0px 0px 4rem 0rem rgba(0,0,0,0.2);
		box-sizing: border-box;
		overflow: hidden;

	}

/*	.slide.back{
		opacity:1;
	}*/

	.slide.show{
		opacity:1;
	}

	.slide img, .slide video{
		width:100%;
/*		max-height:100%;*/
		height:auto;
		object-fit: cover;
		display: block;

	}


	.slide img{
		aspect-ratio: 4 / 2.8;
		transition: all 2s;
	}

	.slide.contain img{
		object-fit: contain;
	}

	.slide.contain.noaspect img{
		aspect-ratio: 0;
	}

	.slide video{
        padding:6.9% 0;
    	background-color: black;
	}

	.slide.nopad video{
		padding:0;
	}

	.back{
		z-index:0;
	}

	.front{
		z-index:1;
	}



</style>