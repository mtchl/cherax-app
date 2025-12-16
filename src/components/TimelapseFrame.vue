<template>
	<div>
		<img :src="frameUrl">
	</div>
</template>

<script>
export default {

  name: 'TimelapseFrame',

  data () {
    return {

    }
  },

  props: ['date','currentPlayTime','cam','imageUrl'],

  computed:{
  	frameUrl(){
  		const framenum = 1 + Math.floor(this.currentPlayTime / (60 * 10))
  		return new URL(`${this.imageUrl}/${this.cam}/${this.date}/${this.cam}-${this.date}-${framenum}.JPG`, import.meta.url).href
  	}
  }
}

</script>

<style lang="css" scoped>

	img{
		
		max-width:100%;
		aspect-ratio: 1.38;
		max-height: 65vh;
		object-fit:cover;
		object-position: 50% top;
		display: block;
		margin: 0 auto;
	}

</style>