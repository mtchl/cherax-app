<template>
	<div class="item">
      <img v-if="capture.type =='image'" :src="baseUrl + capture.path " />
      <video v-if="capture.type =='video'" preload="metadata" controls>
        <source :src="baseUrl + capture.path + '#t=0.5'" type="video/mp4">
      </video>
      <p>day {{capture.dayIndex}} &bull; Cam {{capture.camLabel}} &bull; {{capture.timestamp.toLocaleString('en-AU')}} month: {{capture.month}}</p>
      <p><span v-for="t in localTags" class="itemTag" @click="setFilter({key:'species',value:t.routeTag})">{{t.tag}}</span> </p>
  </div>
</template>

<script>
export default {

  data () {
    return {

    }
  },

  computed:{
    localTags(){
      return this.capture.tags.map(t => { return {tag:t, routeTag: t.toLowerCase().replace(" ","-")}})
    }

  },

  methods:{
    setFilter(filter){
        this.$emit('set-filter',filter)
      }

  },
   props: ['capture','baseUrl']
  }

</script>

<style lang="css" scoped>

.item{
    flex:1;
    max-width: 900px;
    min-width:640px;
    margin-bottom:2rem;
    background-color: white;
    padding:1rem;
 
  }

  .item img, .item video {
    width:100%;
  }

  .item img{
    aspect-ratio: 4 / 2.8;
    object-fit: cover;
  }

  .item video{
    padding:6.9% 0;
    background-color: black;
  }

  .item p{
    margin:0.25rem;
  }
</style>