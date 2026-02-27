<script setup>
  import VLazyImage from "v-lazy-image";
  import AudioAnnotations from '@/components/AudioAnnotations.vue'
</script>

<script>
    export default {
    data() {
      return {
        hourTicks:[0,3,6,9,12,15,18,21,24],
        resumeAfterDrag:false,
        playPos:0,
        playSeconds:0,
        bigSpectroWidth:9600,
        
        spectroDragOffset:0,
        spectroDragInit:0,
        spectroMouseInit:0,
        
        dragSpectroDiff:0,
        dragging:false,
      }
    },
    props: ['date','initPlayTime','audioDuration', 'currentPlayTime', 'playing','timelapseCam','imageUrl','focusSpecies'],

    methods:{

      seekAudio(time){
        this.$emit('seek-audio',{date:this.date, time:time})
      },

      jumpAudio(payload){
        //const playSeconds = this.audioDuration * this.playPos;
        this.$emit('seek-audio',{date:this.date, time:payload.time})
        this.$emit('play-audio');
      },

      stopAudio(){
        this.$emit('stop-audio')
      },


      startMouseDrag(evt){
        if (this.dragging) return;
        if (this.playing) {
          this.stopAudio();
          this.resumeAfterDrag = true;
        } else {
          this.resumeAfterDrag = false;
        }
        this.dragging = true;
        this.spectroMouseInit = evt.pageX;
        this.spectroDragInit = this.$refs.spectro.scrollLeft;
        this.$refs.spectro.addEventListener('mousemove', this.mouseDragging)
      },

      startTouchDrag(){
        if (this.dragging) return;
        if (this.playing) {
          this.stopAudio();
          this.resumeAfterDrag = true;
        } else {
          this.resumeAfterDrag = false
        }
        this.dragging = true;
        this.$refs.spectro.addEventListener('scrollend', this.touchDragEnd)
      },

      endMouseDrag(evt){
        this.dragging = false;
        this.$refs.spectro.removeEventListener('mousemove', this.mouseDragging)
        this.updateAudioAfterScroll();
      },

      mouseDragging(evt){
        if (!this.dragging) return;
        evt.preventDefault();
        const diff = evt.pageX - this.spectroMouseInit
        this.$refs.spectro.scrollLeft = this.spectroDragInit - diff; // Apply scroll
      },

      

      updateAudioAfterScroll(){
        this.playPos = this.$refs.spectro.scrollLeft / this.bigSpectroWidth;
        this.playSeconds = this.audioDuration * this.playPos;
        this.seekAudio(this.playSeconds);
        if (this.resumeAfterDrag) this.$emit('play-audio');
      },

      touchDragEnd(){
        console.log("touch drag end")
        this.dragging = false
        this.$refs.spectro.removeEventListener('scrollend', this.touchDragEnd);
        this.updateAudioAfterScroll();
      },

      timelapseImagePath(f){
        // in the form {storage URL}/cam1/20241101/cam1-20241101-{frameNum}-thumb.JPG
        return new URL(`${this.imageUrl}/${this.timelapseCam}/${this.date}/${this.timelapseCam}-${this.date}-${f}-thumb.JPG`, import.meta.url).href
      }
    },

    mounted(){
      this.playSeconds = this.initPlayTime;
      this.playPos = this.playSeconds / this.audioDuration; // convert from seconds to %
      this.$refs.spectro.scrollLeft = this.bigSpectroWidth * this.playPos;
    },

    watch:{
      currentPlayTime(seconds){ // watch the time as it is updated by audio playback
        this.playSeconds = seconds;
        this.playPos = seconds / this.audioDuration; // convert from seconds to %
        this.$refs.spectro.scrollLeft = this.bigSpectroWidth * this.playPos;
      },
      audioDuration(duration){
          this.playPos = this.initPlayTime / duration; 
      }
    }
  }

</script>

<template>

  <div class="spectro" ref="spectro" @mousedown="startMouseDrag" @mouseup="endMouseDrag" @touchstart="startTouchDrag">


    <div class="wrapper"  :class="{'dragging':dragging}" >

      <div class="timelapse">
        <div class="frame" v-for="i in 144" :style="{left: 100 * ((i-1)/144) + '%'}">
            <v-lazy-image :src="timelapseImagePath(i)" draggable="false" />
        </div>
      </div>

      <img class="fcs" ref="fcs"  src="@/assets/img/20241102.jpg" draggable="false" :style="{width: bigSpectroWidth+'px'}">

      <div class="tick" v-for="h in 24" :style="{left: 100*((h-1)/24)+'%'}">
        <span>{{h-1}}:00</span>
      </div>

      <AudioAnnotations :date="date" :audio-time="playSeconds" @jump-audio="jumpAudio" @stop-audio="stopAudio" :focus-species="focusSpecies"> </AudioAnnotations>
    </div>


    
  </div>


</template>

<style scoped>

  .spectro{
    position:relative;
    height:1620px;
    width:100%;
    overflow-x: scroll;
    user-select: none;
    background-color: #111;

  }

  .spectro img{
/*    height:320px;*/
    user-select: none;
  }

  .spectro .fcs{
    margin-top:2px;
  }

  .spectro .wrapper{
    position:absolute;
    cursor: grab;
    left:50vw;
/*    margin-bottom:60px;*/
  }

  .spectro .wrapper.dragging{
    cursor: grabbing;
  }


  .timelapse{
    height:48px;
    position:relative;
  }

  .timelapse .frame{
    position:absolute;
    height:100%; 
  }

  .timelapse .frame img{
    height:100%;
    user-select: none;
    opacity:0.9;
  }


  .tick{
    position:absolute;
    height:100%;
    width:10px;
    top:0;
    border-left:1px solid white;
    opacity:0.25;
  }

  .tick span{
    position:absolute;
    top:100%;
    font-size:75%;
  }



  .playbutton{
    cursor:pointer;
    font-weight:bold;
    text-align: center;
  }

  .arrow{
    position:absolute;

  }

  .arrow-up {
      width: 0; 
      height: 0; 
      border-left: 8px solid transparent;
      border-right: 8px solid transparent;
      border-bottom: 8px solid white;
      bottom:0;
      left:-8.5px;
  
    }

    .arrow-down {
      width: 0; 
      height: 0; 
      border-left: 8px solid transparent;
      border-right: 8px solid transparent;
      border-top: 8px solid white;
      top:0;
      left:-8.5px;

    }





</style>
