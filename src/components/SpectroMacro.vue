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
        dragSpectroDiff:0,
        dragging:false,
      }
    },
    props: ['date','initPlayTime','audioDuration', 'currentPlayTime', 'playing','timelapseCam','imageUrl'],

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
      
      spectroDragStart(evt){
        console.log("drag - start")
        let pointerx = evt.clientX ? evt.clientX : evt.touches[0].clientX;
        let dx = pointerx - evt.target.offsetLeft;
        this.dragSpectroDiff = dx;


        this.$refs.spectro.addEventListener("mousemove", this.spectroDragging);
        this.$refs.spectro.addEventListener("mouseup", this.spectroDragEnd);
        this.$refs.spectro.addEventListener("mouseleave", this.spectroDragEnd);
        this.dragging = true;
        if (this.playing) {
          this.stopAudio();
          this.resumeAfterDrag = true;
        } else {
          this.resumeAfterDrag = false;
        }
      },

      spectroDragging(evt){
        let pointerx = evt.clientX ? evt.clientX : evt.touches[0].clientX;
        this.spectroDragOffset = pointerx - this.dragSpectroDiff;
      },

      spectroDragEnd(evt){
         console.log("drag - end")
        let pointerx = evt.clientX ? evt.clientX : evt.touches[0].clientX;
        this.$refs.spectro.removeEventListener("mousemove", this.spectroDragging);
        this.$refs.spectro.removeEventListener("mouseup", this.spectroDragEnd);
        this.$refs.spectro.removeEventListener("mouseleave", this.spectroDragEnd);
        this.playPos = ((this.bigSpectroWidth*this.playPos) - this.spectroDragOffset)/this.bigSpectroWidth;
        this.playSeconds = this.audioDuration * this.playPos;
        this.spectroDragOffset = 0;
        this.dragging = false;
        this.seekAudio(this.playSeconds);
        if (this.resumeAfterDrag) this.$emit('play-audio');
      },

      timelapseImagePath(f){
        // in the form {storage URL}/cam1/20241101/cam1-20241101-{frameNum}-thumb.JPG
        return new URL(`${this.imageUrl}/${this.timelapseCam}/${this.date}/${this.timelapseCam}-${this.date}-${f}-thumb.JPG`, import.meta.url).href
      }
    },

    computed:{
      spectroUrl() {
        return new URL(`@/assets/img/${this.date}.jpg`, import.meta.url).href
      }
    },

    mounted(){
      this.playSeconds = this.initPlayTime;
    },

    watch:{
      currentPlayTime(seconds){ // watch the time as it is updated by audio playback
        this.playSeconds = seconds;
        this.playPos = seconds / this.audioDuration; // convert from seconds to %
      },
      audioDuration(duration){
          this.playPos = this.initPlayTime / duration; 
      }
    }
  }

</script>

<template>

  <div class="spectro">
    <div class="wrapper" :style="{left: 'calc(50% - '+((bigSpectroWidth*playPos)-spectroDragOffset)+'px)'}" ref="spectro" @mousedown="spectroDragStart" :class="{'dragging':dragging}">

      <div class="timelapse">
        <div class="frame" v-for="i in 144" :style="{left: 100 * ((i-1)/144) + '%'}">
            <v-lazy-image :src="timelapseImagePath(i)" draggable="false" />
        </div>
      </div>

      <img src="@/assets/img/20241102.jpg" draggable="false" :style="{width: bigSpectroWidth+'px'}">

      <div class="tick" v-for="h in 24" :style="{left: 100*((h-1)/24)+'%'}">
        <span>{{h-1}}:00</span>
      </div>

      <AudioAnnotations :date="date" :audio-time="playSeconds" @jump-audio="jumpAudio"> </AudioAnnotations>
    </div>

    <div class="play-marker">
        <div class="arrow arrow-down"></div>
        <div class="arrow arrow-up"></div>
    </div>
    
  </div>


</template>

<style scoped>

  .spectro{
    position:relative;
    height:1420px;
    user-select: none;
    background-color: #111;
  }

  .spectro img{
/*    height:320px;*/
    user-select: none;
  }

  .spectro .wrapper{
    position:absolute;
    cursor: grab;
    margin-bottom:60px;
  }

  .spectro .wrapper.dragging{
    cursor: grabbing;
  }

  .spectro .play-marker{
    position: absolute;
    height:95%;
    top:-1%;
    left:50%;
    border-left:1px solid white;
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
