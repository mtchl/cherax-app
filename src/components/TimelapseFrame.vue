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
		height:623px;
		width:860px;
		object-fit:cover;
		object-position: 50% top;
		display: block;
		margin: 1rem auto;
	}

</style>