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
        initialTimeSeconds:23400,
        audioDuration:86275,
        playing:false,
        timelapseCams:["cam1","cam2"],
        activeCam:["cam1"],
        audioUrl: "https://storage.googleapis.com/cherax-media/audio",
        imageUrl: "https://storage.googleapis.com/cherax-media"
      }
    },

    props: ['focusSpecies'],

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
       this.seekAudio({date:this.focusDate,time:this.currentTimeSeconds})
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
         if (this.$refs.audio) this.currentTimeSeconds = this.$refs.audio.currentTime;
      },

      // audioLoaded(){
      //   if (this.$refs.audio) this.audioDuration = this.$refs.audio.duration;
      //   console.log("audio duration " + this.audioDuration)
      // },

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
        <span class="date">{{focusDateNice}}</span> 
        <button class="playButton" :class="{'playing': playing}" @click="toggleAudio"></button> 
        <span class="timestamp">{{currentTimeStamp.h}}:{{currentTimeStamp.m}}:{{currentTimeStamp.s}}</span>
        
        <div class="play-marker">
          <div class="arrow arrow-up"></div>
        </div>

    </div>

    <SpectroMacro  :date="focusDate" :init-play-time="initialTimeSeconds" :current-play-time="currentTimeSeconds" :audioDuration="audioDuration" :playing="playing" :timelapseCam="activeCam" :image-url="imageUrl" @seekAudio="seekAudio" @stopAudio="stopAudio" @playAudio="playAudio" :focus-species="focusSpecies" /> 

    <audio @timeupdate="updateCurrentTime" ref="audio" controls>
      <source :src="audioPath" type="audio/mp3">
    </audio>

<div class="footer">
    <div class="about">
      <div class="col left intro">
        <h2>About this interface</h2>
        <p class="bigger">This timeline lets you browse through 24 hours of wetland sound and image.</p><p class="bigger"> Use it how you like: sit for a while and immerse yourself in the soundscape, drag to sample different times of night and day, or scan through birds and their calls.</p>
      </div>

      <div class="col right ">
        <img src="@/assets/img/spectro-grab-bigger-drag.jpg">
        <p>This "false colour" spectrogram visualises sound to reveal distinctive patterns and calls, including birds and frogs. <strong>Drag</strong> to scan back and forward through the day.</p>
     
        <img src="@/assets/img/bird-annotations-labels.png">
        <p><strong>Select a bird species and tap the arrows</strong> to skip through its calls. We used an automated bird-call detector to identify each species. It's not perfectly accurate but it helps reveal more of the diversity of life in this place. </p>
      </div>
    </div>
  </div>

  </div>
  



</template>



<style scoped>

  .timelineWrapper{
    background-color: #111; 
    width:100%;
   
  }

  .footer{
    background-color: #111;
    padding-top:1rem;
    padding-bottom: 5rem;
    position: relative;
    z-index:4;
  }

  .play-marker{
    position: absolute;
    height:1400px;
    top:100%;
    left:50%;
    border-left:1px solid white;
/*    z-index:2;*/
    box-shadow: 0px 0px 4px 6px rgba(0,0,0,0.1);
    z-index:1;
  }


  audio{
    display:none;
  }

  .controlPanel{
    position:sticky;
    top:2rem;
    z-index:2;
    width:100%;
    height:20px;
    padding: 1rem 0;
    font-weight: 300;
    font-size:120%;
    background: #111;
    color:white;
    overflow-y: visible;
  }

  button.playButton{
    display:inline-block;
    width:40px;
    height:64px;
    background-image: url('@/assets/img/playbutton-teardrop.png');
    background-repeat: no-repeat;
    background-position: 0% 50%;
    background-size: contain;
    background-color: transparent;
/*    border-style: solid;*/
    border:none;
/*    border-radius: 50%;*/
    margin:0.5 0rem;
    cursor:pointer;
    position:absolute;
    left:calc(50% - 20px);
    top:0px;
    z-index: 5;
  }

  button.playButton:hover{
/*    background-color: #222;*/
    transform: scale(1.05);
    transition: scale 0.5s;
  }

  button.playButton.playing{
    background-image: url('@/assets/img/pausebutton-teardrop.png');
  }

  span.date{
    position:absolute;
    right:calc(50% + 32px);
  }

  span.timestamp{
    position:absolute;
    left:calc(50% + 32px);
  }

  .about{
    margin:1rem auto;
    border-radius: 2rem;
    background-color: #333;
    display: flex;
    flex-flow:row wrap;
    color:white;
    padding:2rem;
    max-height: 600px;
    align-items: center;
    width:calc(100% - 2rem);
    max-width:1400px;
    box-sizing: border-box;

  }

  .about h2{
    font-size: 2rem;
    margin: 0 0 1rem;
  }

  .about p{
    font-family: Lato, sans-serif;
    font-size: 1.1rem;
    line-height: 1.3em;
  }

  p.bigger{
    font-size: 1.35rem;
  }

  .about .col{
    flex:1;
    min-width: 320px;
/*    width:45%;*/
  }

  .col.left{
        margin-right:1rem;
  }

  .col.right p{
    margin-bottom: 2rem;
  }


  .col img{
    width:360px;
    max-width: 100%;
    border: 2px solid black;
  }



</style>
