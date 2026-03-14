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
          
          <button type="button" class="icon fave" @click="clickFaveButton" >
              <img v-if="!faved" src="@/assets/img/fave-heart.svg" title="Add to favourites"/>
              <img v-if="faved" src="@/assets/img/fave-heart-black.svg" title="Remove from favourites"/>
          </button>

          <button type="button" v-if="share" class="icon share" @click="shareCapture" title="Share this capture">
            <img src="@/assets/img/share-button.svg"/>
          </button>


        </div>
        <div class="col right"><span v-for="t in localTags" class="itemTag" @click="setFilter('species',t.routeTag)">{{t.tag}}</span> </div>
      </div>
      
      <div class="closebutton" v-if="modal" @click="closeModal">
        <img src="@/assets/img/close.svg">
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

    closeModal(key,value){
        this.$emit('close-modal')
      },

    clickFaveButton(){
        this.$emit('click-fave',this.captureID)
    },

    shareCapture(){
      navigator.share({
        title: 'Mosaic: Capture ' + this.captureID,
        url: 'https://cherax.netlify.app/captures/share/' + this.captureID
      })

    }

  },
   props: ['capture','baseUrl','share','modal','faved']
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
/*    height: 100%;*/
    aspect-ratio: 1.32;
    width: auto;
    max-height: 80%;
    max-width: 100%;
    flex: 1;
    flex-grow: 0;
    margin-bottom: 0;
    min-width: unset;
    position: relative;
    box-sizing: border-box;
  }

/*  .item.modal video{
    padding:0;
  }*/

  .item img, .item video {
    width:100%;
  }

  .item img{
    aspect-ratio: 4 / 2.8;
    object-fit: cover;
  }

  .item.modal .closebutton{
    width:2rem;
    height:2rem;
    position:absolute;
    top:0;
    right:0;
    background-color: white;
    
  }

  .item.modal .closebutton img{
    width:1.5rem;
    height:1.5rem;
    margin:0.25rem;
    cursor: pointer;
  }

  .item.modal .closebutton img:hover{
    transform-origin: center;
    transform: scale(1.1);
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

  button.icon{
    background-color: transparent;
    border: none;
    padding: 0; 
    cursor: pointer; 
    width:1.9rem;
/*    height:1.5rem;*/
/*    overflow: visible;*/
    margin-right:0.1rem;
    position:relative;
    top:0.25rem;
    opacity:0.25;
  }

  button.share{
    top:0.15rem;
    width:1.8rem;
  }

  button.icon img{
    display: block;
    width:100%;
    object-fit: contain;
  }

  button.icon:hover{
    opacity:0.8;
  }

  @media screen and (width < 600px) {

    .item{
      min-width:320px;
      margin-bottom:1rem;
    }

    .item.modal{
      width:100%;
      aspect-ratio: unset;
      height:auto;
    }
  }
</style>