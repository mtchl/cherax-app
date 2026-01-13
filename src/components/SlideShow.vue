
<template>

	<div class="outer-wrapper">
		
			<div class="slide back" :class="{'show': show =='back'&&backLoaded}">
				<img v-if="back.type == 'img' && back.url" :src="back.url" @load="onLoad('back')">
				<video v-if="back.type == 'vid' && back.url" :src="back.url" @play="onLoad('back')" autoplay muted loop></video>
				
			</div>

			<div class="slide front" :class="{'show': show=='front'&&frontLoaded}">
				<img v-if="front.type == 'img' && front.url" :src="front.url" @load="onLoad('front')"> 
				<video v-if="front.type == 'vid' && front.url" :src="front.url" @play="onLoad('front')" autoplay muted loop></video>
				
			</div>

	</div>

</template>








<script>
export default {

  name: 'SlideShow',
  props: ['slide'],

  data () {
    return {
    	back:{url:null, type:null, loaded:false},
    	front:{url:null, type:null, loaded:false},
    	show:"back",
    	frontLoaded:false,
    	backLoaded:false,
    	// playingStep:null
    	// timeoutId:null
    }
  },

  methods:{
  	onLoad(slide){
  		console.log(slide)
  	
  		// if (this.timeoutId) {
	    //     clearTimeout(this.timeoutId);
	    //  }

	     // this.timeoutId = setTimeout(() => {

		 //        this.timeoutId = null;
		 //      }, 500);

  		if (slide == "front") {
        	this.frontLoaded = true;
        	this.backLoaded = false;

		}
        
        if (slide == "back" ) {
        	this.backLoaded = true;
        	this.frontLoaded = false;

        }
  	
  	}
  	



  },

  watch:{
  	slide(newSlide,oldSlide){
  		 console.log(newSlide)
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
  	}
  }
}
</script>

<style lang="css" scoped>

	.outer-wrapper{
		margin:0 auto;
		max-width:1400px;
		position:relative;
		width:100%;
		height:100vh;
	}

	.slide{
		position: absolute;
	    top: 50%;
	    left: 50%;
	    transform: translate(-50%, -50%); 
/*	    min-width:80vw;*/
	    width: 95%;
	    height: auto;
	    max-height:90vh;

	    transition: opacity 1.5s;
		opacity:0;

		border: 1px solid black;
		box-shadow: 0px 0px 4rem 0rem rgba(0,0,0,0.2);
		box-sizing: border-box;
/*		overflow: hidden;*/

	}

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
	}

	.slide video{
        padding:6.9% 0;
    	background-color: black;
	}

	.back{
		z-index:0;
	}

	.front{
		z-index:1;
	}



</style>