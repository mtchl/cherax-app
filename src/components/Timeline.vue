<script setup>
 // import Spectro from './components/Spectro.vue'
 import SpectroMacro from '@/components/SpectroMacro.vue'
 import TimelapseFrame from '@/components/TimelapseFrame.vue'
 // import AudioAnnotationsCurrent from './components/AudioAnnotationsCurrent.vue'
</script>

<script type="text/javascript">

  export default{
    data(){
      return {
        // dateRange: ["20241029","20241030","20241031","20241101"],
        dateRange: ["20241031","20241101"],
        focusDate: "20241102",
        currentTimeSeconds:23400,
        audioDuration:0,
        playing:false,
        timelapseCams:["cam1","cam2"],
        activeCam:["cam1"],
        audioUrl: "https://storage.googleapis.com/cherax-media/audio",
        imageUrl: "https://storage.googleapis.com/cherax-media"
      }
    },

    computed: {
      audioPath(){
        // return new URL(`./assets/audio/${this.focusDate}.mp3`, import.meta.url).href
        return new URL(`${this.audioUrl}/${this.focusDate}.mp3`).href; // gDrive url
      },

      currentTimeStamp(){
        let stamp = {};
        
        const hours = Math.floor(this.currentTimeSeconds/(60*60)) // hours
        stamp.h = hours.toString().padStart(2,'0')
        const mins = Math.floor((this.currentTimeSeconds - (stamp.h*3600))/60) // mins
        stamp.m = mins.toString().padStart(2,'0')
        const secs = Math.floor(this.currentTimeSeconds - (stamp.h*3600) - (stamp.m*60)) // seconds
        stamp.s = secs.toString().padStart(2,'0')
        return stamp
      },

      focusDateNice(){
        const dateStamp = this.focusDate;
        // Input validation: Check if the dateStamp is in the correct format YYYYMMDD
        if (!/^\d{8}$/.test(dateStamp)) {
          return "Invalid date format. Please use YYYYMMDD.";
        }

        const year = dateStamp.slice(0, 4);
        const month = dateStamp.slice(4, 6);
        const day = dateStamp.slice(6, 8);

        const monthNames = [
          "January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December"
        ];

        const monthName = monthNames[parseInt(month) - 1]; // Month is 0-indexed

        let dayWithSuffix;
        const dayNum = parseInt(day);

        if (dayNum >= 11 && dayNum <= 13) {
          dayWithSuffix = dayNum + "th";
        } else {
          switch (dayNum % 10) {
            case 1:
              dayWithSuffix = dayNum + "st";
              break;
            case 2:
              dayWithSuffix = dayNum + "nd";
              break;
            case 3:
              dayWithSuffix = dayNum + "rd";
              break;
            default:
              dayWithSuffix = dayNum + "th";
          }
        }

        return `${dayWithSuffix} ${monthName} ${year}`;
      }

    },

    mounted(){
      this.$refs.audio.currentTime = this.currentTimeSeconds;
     //this.seekAudio({date:this.focusDate,time:this.currentTimeSeconds})
    },

    methods:{
      playAudio(play){
        this.$refs.audio.play();
        this.playing = true;
      },

      stopAudio(){
        this.$refs.audio.pause();
        this.playing = false;
      },

      seekAudio(seek){
        // receive seek events from the spectro timeline
        if (seek.date != this.focusDate){ // switch to a different day
          this.focusDate = seek.date;
        }
        this.$refs.audio.currentTime = seek.time;
        this.updateCurrentTime();
      },

      updateCurrentTime() {
        this.currentTimeSeconds = this.$refs.audio.currentTime;
      },

      audioLoaded(){
        this.audioDuration = this.$refs.audio.duration;
        // console.log("audio duration " + this.audioDuration)
      },

      toggleAudio(){
        if (this.playing) {
          this.stopAudio();
        } else {
          this.playAudio({date:this.focusDate, time:this.currentTimeSeconds});
        }
      }
    }
}
</script>

<template>

  <div class="timelineWrapper">

   <TimelapseFrame :date="focusDate" :current-play-time="currentTimeSeconds" :cam="activeCam" :image-url="imageUrl"/>

    <div class="controlPanel">
        <span>Cherax Swamp, {{focusDateNice}}, {{currentTimeStamp.h}}:{{currentTimeStamp.m}}:{{currentTimeStamp.s}}</span>
        <button class="playButton" :class="{'paused': playing}" @click="toggleAudio"></button>
        cam: 
        <select v-model="activeCam">
          <option v-for="c in timelapseCams" :value="c">{{c}}</option> 
        </select>  
      </div>

    <SpectroMacro :date="focusDate" :current-play-time="currentTimeSeconds" :audioDuration="audioDuration" :playing="playing" :timelapseCam="activeCam" :image-url="imageUrl" @seekAudio="seekAudio" @stopAudio="stopAudio" @playAudio="playAudio" /> 

    <!-- <AudioAnnotationsCurrent :date="focusDate" :current-play-time="currentTimeSeconds" @seekAudio="seekAudio">  </AudioAnnotationsCurrent> -->



  <audio @timeupdate="updateCurrentTime" ref="audio" controls @canplay="audioLoaded">
    <source :src="audioPath" type="audio/mp3">
  </audio> 

</div>

</template>



<style scoped>

  .timelineWrapper{
    background-color: rgb(112 111 87);
  }

  audio{
    display:none;
  }

  .controlPanel{
    position:sticky;
    top:0;
    z-index:1;
    width:100%;
    padding: 1rem 0;
    
    display: flex;
    align-items: center;
    justify-content: center;
/*    font-family: 'Inconsolata', monospace;*/
    font-weight: 300;
    font-size:120%;
    opacity: 0.8;
    background: #111;
    color:white;
  }

  button.playButton {
    border: 0;
    background: transparent;
    box-sizing: border-box;
    width: 20px;
    height: 20px;

    border-color: transparent transparent transparent #fff;
/*    transition: 100ms all ease;*/
    cursor: pointer;

    border-style: solid;
    border-width: 9px 0 9px 15px;

/*    transform:scale(0.35);*/
    margin:0 2rem;

  }

  .playButton.paused {
    border-style: double;
    border-width: 0px 0 0px 15px;
  }

  .playButton:hover {
    border-color: transparent transparent transparent #aaa;
  }

</style>
