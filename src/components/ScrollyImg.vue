

<template>

		<div class="outer-wrapper">
			<div class="slide">
				<img v-if="type == 'img'" :src="src" />
				<video v-if="type == 'video'" :src="src" loop muted ref="vid" />
			</div>
		</div>

</template>

<script>
export default {

  name: 'ScrollyImg',
  props: ['src','type','playtrigger'],

  data () {
    return {

    }
  },

  methods(){

  },

  watch:{
  	playtrigger(newvalue,oldvalue){
  		if (newvalue){ 
  			this.$refs.vid.play()
  			console.log("watched trigger")
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

	  	transition: opacity 1.5s ease-out;

		border: 1px solid black;
		box-shadow: 0px 0px 4rem 0rem rgba(0,0,0,0.2);
		box-sizing: border-box;
		overflow: hidden;
	}

	.slide img, .slide video{
		width:100%;
		height:auto;
		object-fit: cover;
		display: block;
		box-sizing: border-box;
		border:1vw solid black;
	}

	.slide video{
      padding:6.9% 0;
      background-color: black;

	}

	.slide img{
		aspect-ratio: 4 / 2.8;

	}


</style>