<template>
	<div class="item" :class="{'modal':modal}">
      <img v-if="capture.type =='image'" 
      :src="baseUrl + capture.path"
      :srcset="srcset" 
      sizes="(width < 600px) 1200px
      (width > 600px) 2000px"
      loading="lazy"/>
      
      <video v-if="capture.type =='video'" :poster="baseUrl + posterPath" loading="lazy" controls playsinline preload="metadata">
        <source :src="baseUrl + capture.path" type="video/mp4">
      </video>
      <div class="metadata">
        <div class="col left">Cam {{capture.camLabel}} &bull; {{capture.timestamp.toLocaleString('en-AU')}}
          <button v-if="share" class="share" @click="shareCapture">Share</button>
        </div>
        <div class="col right"><span v-for="t in localTags" class="itemTag" @click="setFilter('species',t.routeTag)">{{t.tag}}</span> </div>
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

    captureID(){
      let filename = this.capture.path.split("/")[1];
      let namestring = filename.split(".")[0]
      return namestring;

    },
    
    posterPath(){
      let filename = this.capture.path.split("/")[1];
      let dir = this.capture.path.split("/")[0];
      let stripped = filename.replace(".mp4","")
      return dir + "/thumbnails/thumb_" + stripped + ".jpg" 
    },

    smallerImgPath(){
      let filename = this.capture.path.split("/")[1];
      let dir = this.capture.path.split("/")[0];
      let stripped = filename.replace(".JPG","")
      return dir + "/thumbnails/thumb_" + stripped + ".JPG" 
    },

    srcset(){
      return `${this.baseUrl}${this.capture.path} 2560w, ${this.baseUrl}${this.smallerImgPath} 1280w`
    }

  },

  methods:{
    setFilter(key,value){
        this.$emit('set-filter',key,value)
      },
    shareCapture(){
      navigator.share({
        title: 'Mosaic: Capture ' + this.captureID,
        url: 'https://cherax.netlify.app/captures/share/' + this.captureID
      })

    }

  },
   props: ['capture','baseUrl','share','modal']
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

  .item.modal{
    flex:none;
    display: block;
    margin:0 auto;
    width:90%;
    max-width: 1400px;
    box-sizing: border-box;
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

  @media screen and (width < 600px) {

    .item{
      min-width:320px;
      margin-bottom:1rem;
    }

    .item.modal{
      width:100%;
    }
  }
</style>