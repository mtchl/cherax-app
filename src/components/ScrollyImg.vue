

<template>
		<div class="outer-wrapper">
			<div class="slide" :class="[slideclass]">
				<img v-if="type == 'img'" :src="src" :class="[animate]"/>
				<video v-if="type == 'video'" :src="src" loop ref="vid" />
			</div>
		</div>

</template>

<script>
export default {

  name: 'ScrollyImg',
  props: ['src','type','playtrigger','animate','progress','videoScroll','rate','videokeyframe','slideclass', 'inactive','muteVideo'],

  data () {
    return {

    }
  },

  methods(){

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
  		this.$refs.vid.currentTime = newvalue*this.$refs.vid.duration*0.3;
  	},

  	inactive(newvalue,oldvalue){
  		// pause videos once they are inactive
  		if (newvalue && this.$refs.vid){
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
		top:0;
		left:0;
		margin:0 auto;
		width:100%;
		max-width:1400px;
		height:100vh;
	}

	.slide{
		position: absolute;
	    top: 30%;
	    left: 50%;
	    transform: translate(-50%, -30%); 
	    width: 95%;
	    height: auto;
	    max-height:90vh;

/*	  	transition: opacity 1.5s ease-out;*/

		border: 1vw solid black;
		box-shadow: 0px 0px 4rem 0rem rgba(0,0,0,0.2);
		box-sizing: border-box;
		overflow: hidden;
	}

	.slide img, .slide video{
		width:100%;
		height:auto;
		object-fit: cover;
		display: block;
/*		box-sizing: border-box;*/
/*		border:1vw solid black;*/
	}

	.slide video{
      padding:6.9% 0;
      background-color: black;

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


</style>