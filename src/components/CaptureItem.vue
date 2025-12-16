<template>
	<div class="item">
      <img v-if="capture.type =='image'" :src="baseUrl + capture.path " loading="lazy"/>
      <video v-if="capture.type =='video'" :poster="baseUrl + posterPath" loading="lazy" controls>
        <source :src="baseUrl + capture.path" type="video/mp4">
      </video>
      <div class="metadata">
        <div class="col left">Cam {{capture.camLabel}} &bull; {{capture.timestamp.toLocaleString('en-AU')}}</div>
        <div class="col right"><span v-for="t in localTags" class="itemTag" @click="setFilter({key:'species',value:t.routeTag})">{{t.tag}}</span> </div>
      </div>
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
    },
    posterPath(){
      let filename = this.capture.path.split("/")[1];
      let dir = this.capture.path.split("/")[0];
      let stripped = filename.replace(".mp4","")
      return dir + "/thumbnails/thumb_" + stripped + ".jpg" 
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

  .metadata{
    display: flex;
    flex-direction: row;
    flex-wrap: wrap;
    justify-content: space-between;
  }

  .metadata .col.left{
    margin:0.5rem 0;
    
  }

  .metadata .col.right{
    text-align: right;
  }
</style>