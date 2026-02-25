
<script setup>
	import VLazyImage from "v-lazy-image";
</script>


<template>
		<div class="outer-wrapper">
			<div class="slide" :class="[slideclass]">
				<v-lazy-image v-if="type == 'img'" :src="src" :class="[animate, crop, {loaded:imageLoaded}]" @load="imgLoad"/>
				<video v-if="type == 'video'" :src="src" loop ref="vid" :class="[crop]"/>
			</div>
		</div>

</template>

<script>
export default {

  name: 'ScrollyImg',
  props: ['src','type','playtrigger','animate','progress','videoScroll','rate','videokeyframe','slideclass', 'inactive','muteVideo', 'crop','noloop'],

  data () {
    return {
    	imageLoaded: false,
    }
  },

  methods:{
  	imgLoad(){
  		this.imageLoaded = true;
  	}
  },

  watch:{
  	playtrigger(newvalue,oldvalue){
  		if (newvalue){

  			if (this.rate){
  				this.$refs.vid.playbackRate = this.rate;
  				console.log("rate " + this.rate)
  			}
  			if (this.muteVideo){
  				this.$refs.vid.muted = true;
  				console.log("video muted by prop")
  			}

  			if (this.noloop){
  				this.$refs.vid.loop = false;
  			}
  			this.$refs.vid.play()
  			.then(() => { 
  				console.log("watched trigger play") 
  			})
  			.catch(error => {
  				console.error("Playback failed: " + error.message + " retrying muted");
  				this.$refs.vid.muted = true;
  				this.$refs.vid.play();
  			}
  				);
  			
  		} else {
  			this.$refs.vid.pause()
  			this.$refs.vid.currentTime = 0;
  			console.log("watched trigger pause reset")
  		}

  	},

  	progress(newvalue,oldvalue){
  		if (!this.$refs.vid.paused) this.$refs.vid.pause();
  		this.$refs.vid.currentTime = newvalue*this.$refs.vid.duration*0.6;
  	},

  	inactive(newvalue,oldvalue){
  		// pause videos once they are inactive
  		if (newvalue && this.$refs.vid && !this.$refs.vid.paused){
  			console.log("inactive, pausing video")
  			this.$refs.vid.pause()
  		}
  	}

  }
}
</script>

<style lang="css" scoped>

	.outer-wrapper{
		position:sticky;
		top:2rem;
		left:0;
		margin:0 auto;
		width:100%;
		max-width:1400px;
		height:100vh;
		background: rgba(226, 227, 216, 1);
		background: linear-gradient(180deg, rgba(226, 227, 216, 0) 0%, rgba(226, 227, 216, 1) 10vh);
	}

	.slide{
		position: absolute;
    top: 30%;
    left: 50%;
    transform: translate(-50%, -30%); 
    width: 95%;
    height: auto;
    max-height:90vh;

		border: 1vw solid #222;
		box-shadow: 0px 0px 4rem 0rem rgba(0,0,0,0.2);
		box-sizing: border-box;
		overflow: hidden;
		background-color: #222;
	}

	.slide img, .slide video{
		width:100%;
		height:auto;
		object-fit: cover;
		display: block;

	}

	.slide img{
		opacity:0;
		transition: opacity 0.5s;
	}

	.slide img.loaded{
		opacity:1;
	}

	.slide video{
/*      padding:6.9% 0;*/
      background-color: #222;
	}

	.slide.nopad video{
		padding:0;
	}

	.slide.histograb{
		border:none;
		width:auto;
		box-shadow: none;
/*		height:60vh;*/
	}
	.slide.histograb img{
		aspect-ratio: 0;
		object-fit: contain;
		width: auto;
		height:auto;
		max-height:70vh;
	}

	.slide img{
		aspect-ratio: 4 / 2.8;
		transition: all 2s ease-out;
	}

	.slide.noaspect img{
		aspect-ratio: 0;
	}

	img.zoom-200-right{
		transform:scale(2.5) translate(-25%,5%);
	}

	img.zoom-200-left{
		transform:scale(2.5) translate(10%,5%);
	}

/*	MOBILE */

	@media screen and (width < 600px) {

		.slide{
			width:100%;
			border: 0.5rem solid #222;
		}

		.slide img{
			aspect-ratio:1.0;       		/*		square slides for mobile	*/
			transform:scale(1.04);			/*	re-crop the camera image footer		*/
			transform-origin: center top;
		}

		.slide.noaspect img{
			aspect-ratio:1.0;
		}

		.slide img.cropright{
			object-position: right top;
		}

		.slide img.cropleft{
			object-position: left top;
		}

		.slide img.cropleftquarter{
			object-position: 25% top;
		}

		.slide.histograb img{
			max-width:100%;
		}


		.slide video.cropleft{
      aspect-ratio: 1;
      object-position: left 0;
      padding:0;
    }

    .slide video.cropright{
      aspect-ratio: 1;
      object-position: right 0;
      padding:0;
    }

    .slide video.cropcenter{
      aspect-ratio: 1;
      object-position: center 0;
      padding:0;
    }

	}


</style>