
<script setup>
   import TimelapseFrame from '@/components/TimelapseFrame.vue'
   import ScrollyImg from '@/components/ScrollyImg.vue'
   // import 'intersection-observer' // for cross-browser support
   import VueScrollama from "@/lib/VueScrollama.vue";
   import { ImgComparisonSlider } from '@img-comparison-slider/vue';
   import 'vue3-carousel/carousel.css'
import { Carousel, Slide, Pagination, Navigation } from 'vue3-carousel'

    const carouselConfig = {
      itemsToShow: 1.5,
      wrapAround: true,
    // height:420
    }
</script>
<script>

   export default{
      data(){
         return {
            currentSlide: null,
            triggers:{},
            animations:{},
            progress:{},
            introParallax:0,
            introFade:false,
            audio:{
                intro: {
                    src: "https://storage.googleapis.com/cherax-media/narrative/intro-faded.mp3",
                    playing:false
                },
                whistlingKite: {
                    src: "https://storage.googleapis.com/cherax-media/narrative/whistling-kite.mp3",
                    playing:false
                }
            },
            audioEnabled:false,
            carouselSlides:[
                {src: "aust-bittern-1600.jpg", caption:"Australian Bittern"},
                {src: "major-mitchell-1600.jpg", caption:" Major Mitchell Cockatoo"},
                {src: "painted-snipe-1600.jpg", caption: "Painted Snipe"},
                {src: "regent-parrot-1620.jpg",caption: "Regent Parrot"},
                {src: "snipe-swamp-1600.jpg", caption:""},
                ]
            
         }
      },

      methods: {
          stepEnterHandler ({element, index, direction}) {
            if (element.dataset.playtrigger){
                this.triggers[element.dataset.playtrigger] = true;
                console.log("playtrigger " + element.dataset.playtrigger)
            }
           
            if (element.dataset.animate){
                this.animations[element.dataset.animtarget] = element.dataset.animate;
                console.log(element.dataset.animate)
            }

            if (element.dataset.slide){
                this.currentSlide = element.dataset.slide;
                console.log(this.currentSlide)
            }

            if (element.dataset.fadeaudio){
                this.fadeOutAudio();
            }
          },

          stepProgressHandler({ element, progress, index }){
            if (element.dataset.progress){
                this.progress[element.dataset.progress] = progress;
                //console.log(progress)
            }

            if (element.dataset.parallax){
                this.introParallax = progress;
                //console.log(progress)
            }
          },

          stepExitHandler({ element, index, direction }){
            //console.log("step exit", {element, index})

            if (direction == "up" && element.dataset.playtrigger){
                if (this.triggers[element.dataset.playtrigger]){
                    this.triggers[element.dataset.playtrigger] = false;
                }
            }

            if (direction == "up" && element.dataset.slide){
                this.currentSlide = element.dataset.slide - 1;
                console.log("back " + this.currentSlide)
            }
          },

          playAudio(audiokey){
            this.$refs.audioPlayer.src = this.audio[audiokey].src;
            this.$refs.audioPlayer.volume = 0.8;
            this.$refs.audioPlayer.play();
            this.audioEnabled = true;
          },

          fadeOutAudio(){

            const audio = this.$refs.audioPlayer;
            if (audio.volume > 0 && !audio.paused) {
                console.log("fading out")
                const fadeInterval = setInterval(() => {
                    if (audio.volume > 0.05) {
                        audio.volume -= 0.05;
                        //console.log(audio.volume)
                    } else {
                        clearInterval(fadeInterval);
                        audio.volume = 0;
                        audio.pause();
                    }
                }, 200); 
            }
          }
      }
   }


</script>


<template>

    <VueScrollama offset="0.5" progress     
         @step-enter="stepEnterHandler" @step-progress="stepProgressHandler" 
         @step-exit="stepExitHandler"
         class="main__scrollama">

    <div class="splash" :style="{'top': introParallax * -40 + 'vh'}" :class="{'fade': triggers.introFade}">

            <div class="sky-extender"></div>
            <img src="@/assets/img/splash-aerial.webp">

    </div>


     <section class="intro">

        <h1>Title</h1>

        
        
            
            <div class="story no-bottom-margin"><p>In south-western New South Wales, on Mutthi Mutthi country, the Murrumbidgee River reaches the western lakes.</p>

            </div>

            
            <div class="step parallax" data-parallax="true">

                <div class="map">
                    <img src="@/assets/img/mini-map-45.svg"/>
                </div>

                <div class="story"><p>This is a mosaic of temporary and permanent wetlands, actively managed by the Commonwealth in collaboration with local landholders.</p></div>

                <div class="story less-bottom-margin less-top-margin"><p>Dianne Williams and the Morton family initiated a project to regenerate the wetlands around Paika Lake and restore this area's natural beauty. This work is ongoing.</p></div>


                <div class="story step less-bottom-margin" data-playTrigger="introFade" ><p>Landholder Peter Morton’s photos and videos inspired us to create a portrait of life in the western lakes.</p></div>
            </div>

            <Carousel v-bind="carouselConfig" :gap="40">
                    <Slide v-for="(s,idx) in carouselSlides" :key="idx">
                      <div class="carousel__item">
                          <img :src="'https://storage.googleapis.com/cherax-media/narrative/'+s.src">
                          <div class="caption"><p>{{s.caption}}</p></div>
                      </div>
                    </Slide>

                    <template #addons>
                      <Navigation />
                    </template>
            </Carousel>


        <div class="story less-top-margin less-bottom-margin"><p>So over four months from November 2024, we set out cameras and recorders to gather sound, images and data. This website shares what we found.</p></div>  


        <div class="inline-nav">
            
            <div class="col captures" @click="$router.push('/captures')">
                <h2>Captures</h2>
                <p>Browse over 1500 images and videos</p>
            </div>
            <div class="col timeline" @click="$router.push('/timeline')">
                <h2>Timeline</h2>
                <p>Dive into 24 hours<br/> of wetland soundscape</p>
            </div>
        </div>

         <div class="story less-top-margin less-bottom-margin">
                <p>Or <strong>scroll on</strong> for a guided tour of these western wetlands...</p>
         </div>

    </section>


    <audio ref="audioPlayer" :src="null"></audio>



        <section>
             <div class="section-head">
                   <h3>1. Waterbirds</h3>
            </div>

            <div class="listen-button">
                <button @click="playAudio('intro')">Listen</button>
            </div>
         

         
            <ScrollyImg :class="{'hide':currentSlide > 1}" class="step" type="img" src="https://storage.googleapis.com/cherax-media/cam1/20241102/cam1-20241102-40.JPG" data-slide="1"></ScrollyImg>
       
       

            <div class="story" >
                 The water is high in early November 
            </div>

         
            <ScrollyImg :class="{'hide':currentSlide > 2}" class="step" type="img" src="https://storage.googleapis.com/cherax-media/cam4/cam4-20241102-060724.JPG" data-slide="2" data-offset="0.5"></ScrollyImg>
            
             
            <div class="story">
                 At dawn, a <router-link :to="'/captures?species=pelican'">pelican</router-link> touches down at the edge of the flooded Crop Paddock 
            </div>

            
            <ScrollyImg :class="{'hide':currentSlide > 3}" :inactive="currentSlide > 3" class="step" type="video" :playtrigger="triggers.spoonbillHenSnag" src="https://storage.googleapis.com/cherax-media/cam7/cam7-20241106-103414.mp4" data-slide="3" data-offset="0.8"></ScrollyImg>
           

            <div class="story step" data-playtrigger="spoonbillHenSnag" data-fadeaudio="1" data-offset="0.8">
                    A <router-link :to="'/captures?species=yellow-billed-spoonbill'">yellow-billed spoonbill</router-link> and a flock of <router-link :to="'/captures?species=native-hen'">native hens</router-link> feed around the nearby snag... 
            </div>

             <ScrollyImg :class="{'hide':currentSlide > 4}" :inactive="currentSlide > 4" class="step" type="video"  :playtrigger="triggers.spearMorsel" src="https://storage.googleapis.com/cherax-media/cam7/cam7-20241109-072636.mp4" data-slide="4" data-offset="0.8"></ScrollyImg>


            <div class="story step" data-playtrigger="spearMorsel" data-offset="0.8">
                    and a <router-link :to="'/captures?species=white-faced-heron'">white-faced heron</router-link> spears a morsel. 
            </div>


            <ScrollyImg :class="{'hide':currentSlide > 5}" class="step" type="img" src="https://storage.googleapis.com/cherax-media/cam6/cam6-20241122-060704.JPG" data-slide="5" data-offset="0.5"></ScrollyImg>


            <div class="story">
                   Later in November, <router-link :to="'/captures?species=great-egret'">great egrets</router-link> and a <router-link :to="'/captures?species=white-necked-heron'">white-necked heron</router-link> pose on <router-link :to="'/captures?cam=D'">Camera D</router-link>            
            </div>

            <ScrollyImg data-slide="6" :class="{'hide':currentSlide > 6}" class="step" type="img" src="https://storage.googleapis.com/cherax-media/cam3/cam3-20241116-055548.JPG"></ScrollyImg>

            <div class="story">
                   <router-link :to="'/captures?cam=A'">Camera A,</router-link> Looking towards Upper Cherax, snaps a flock of <router-link :to="'/captures?species=white-faced-heron'">pacific black ducks</router-link> taking off in the pre-dawn 
            </div>

            <ScrollyImg data-slide="7" :class="{'hide':currentSlide > 7}"class="step" type="img" src="https://storage.googleapis.com/cherax-media/cam3/cam3-20241216-093840.JPG"></ScrollyImg>

            <div class="story">
                   This shot captures  <router-link :to="'/captures?species=white-ibis'">white ibis,</router-link> <router-link :to="'/captures?species=straw-necked-ibis'">straw-necked ibis</router-link> and <router-link :to="'/captures?species=white-necked-heron'">white-necked herons</router-link> in flight 
            </div>

            <ScrollyImg data-slide="8" :class="{'hide':currentSlide > 8}" :inactive="currentSlide > 8" class="step"  type="video" :playtrigger="triggers.greatCormorant" mute-video="true" src="https://storage.googleapis.com/cherax-media/cam3/cam3-20241216-093850.mp4"></ScrollyImg>

            <div class="story step" data-playtrigger="greatCormorant" data-offset="0.8">
                   The <router-link :to="'/captures?species=great-cormorant'">great cormorant</router-link> follows them a second later  
            </div>

             <ScrollyImg data-slide="9" :class="{'hide':currentSlide > 9}"class="step" type="img" src="https://storage.googleapis.com/cherax-media/cam4/cam4-20250131-105624.JPG"></ScrollyImg>

             <div class="story step final" >
                   By the end of January, the water level has dropped, and <router-link :to="'/captures?species=pacific-black-duck'">ducks</router-link>, <router-link :to="'/captures?species=pelican'">pelicans</router-link> and <router-link :to="'/captures?species=black-swan'">black swans</router-link> are gathering in numbers out on the Crop Paddock   
             </div>

        </section>


        <section>
            <div class="section-head nomargin">
                <h3>2. Feeding</h3>
            </div>

            <ScrollyImg class="step" data-slide="10" :class="{'hide':currentSlide > 10}" :inactive="currentSlide > 10" data-playtrigger="footStir" type="video" :playtrigger="triggers.footStir" src="https://storage.googleapis.com/cherax-media/cam6/cam6-20241207-113632.mp4"></ScrollyImg>

            <div class="story" >
               Videos capture some of these waterbirds' signature feeding moves.    
            </div>

            <div class="story">
              Herons and egrets “stir” the flooded ground to scare out small fish and insects – here's a <router-link :to="'/captures?species=white-faced-heron'">white-faced heron</router-link> demonstrating   
            </div>

             <ScrollyImg class="step" data-slide="11" :class="{'hide':currentSlide > 11}" :inactive="currentSlide > 11" type="video" :playtrigger="triggers.spoonbillHunting" src="https://storage.googleapis.com/cherax-media/cam6/cam6-20241121-072410.mp4" mute-video="true"></ScrollyImg>

            <div class="story step" data-playtrigger="spoonbillHunting" data-offset="0.8">
              This <router-link :to="'/captures?species=yellow-spoonbill'">yellow spoonbill</router-link> sweeps its broad, sensitive bill from side to side, hunting by feel,  
            </div>

            <div class="story step">
              Ducks like the <router-link :to="'/captures?species=pacific-black-duck'">pacific black duck</router-link> are named “dabblers” for their head-down, tail-up feeding style.   
            </div>

            <ScrollyImg class="step" data-slide="12" :class="{'hide':currentSlide > 12}" :inactive="currentSlide > 12" type="video" :playtrigger="triggers.squabbleFrog" data-offset="0.8" src="https://storage.googleapis.com/cherax-media/cam7/cam7-20241121-165624.mp4"></ScrollyImg>

            <div class="story step" data-playtrigger="squabbleFrog" data-offset="0.8">
              Later that day at the snag, a <router-link :to="'/captures?species=white-ibis'">white ibis</router-link> and a <router-link :to="'/captures?species=straw-necked-ibis'">straw-necked ibis</router-link> squabble over possession of a juicy frog.    
            </div>
            
        </section>


        <section>

            <div class="section-head nomargin">
                  <h3>3. Raptors</h3>
            </div>

            <ScrollyImg class="step" data-slide="13" :class="{'hide':currentSlide > 13}"type="img" src="https://storage.googleapis.com/cherax-media/cam7/cam7-20241230-155324.JPG" :animate="animations.hidingKite"></ScrollyImg>

            <div class="story step" data-animate="reset" data-animtarget="hidingKite">
                As well as waterbirds, wetlands like this are havens for birds of prey 
            </div>

             <div class="step story" data-animate="zoom-200-right" data-animtarget="hidingKite">
                <p>Hiding in this frame is a <router-link :to="'/captures?species=whistling-kite'">whistling kite,</router-link> an Australian raptor often found near water.</p> 
            </div>

            <ScrollyImg class="step" data-slide="14" :class="{'hide':currentSlide > 14}" type="img"  src="https://storage.googleapis.com/cherax-media/cam3/cam3-20250109-105800.JPG" :animate="animations.fingersKite"></ScrollyImg>

            <div class="step story" data-animate="reset" data-animtarget="fingersKite">
                This capture from <router-link :to="'/captures?cam=A'">Camera A</router-link> shows the distinctive ‘fingers’ on the whistling kite’s wingtips  
            </div>

            <div class="step story" data-animate="zoom-200-left" data-animtarget="fingersKite">
               Whistling kites are named for their piercing call. <a @click="playAudio('whistlingKite')">listen</a> here, or find it in the <router-link :to="'/timeline?species=whistling-kite'">Timeline</router-link>
            </div>

            <ScrollyImg class="step" data-slide="15" :class="{'hide':currentSlide > 15}" :inactive="currentSlide > 15" type="video" src="https://storage.googleapis.com/cherax-media/cam3/cam3-20241219-063226.mp4" :playtrigger="triggers.kiteTussle" :progress="progress.kiteTussle"></ScrollyImg>

            <div class="step story" data-playtrigger="kiteTussle" data-offset="0.8">
              <p>This video finds two <router-link :to="'/captures?species=whistling-kite'">kites</router-link> tussling over their catch – perhaps a crayfish.</p>       
            </div>

            <div class="story less-bottom-margin">
                <p><strong>Scroll slowly</strong> to see the moment the crayfish splashes down to safety</p>
            </div>

            <div class="step scroller" data-progress="kiteTussle"></div> 

             <ScrollyImg class="step" data-slide="16" :class="{'hide':currentSlide > 16}" type="img" src="https://storage.googleapis.com/cherax-media/cam4/cam4-20250222-185740.JPG"></ScrollyImg>

            <div class="story">
                We also spotted a <router-link :to="'/captures?species=swamp-harrier'">swamp harrier,</router-link> another raptor often found in wetlands  
            </div>

            <ScrollyImg class="step" data-slide="17" :class="{'hide':currentSlide > 17}" :inactive="currentSlide > 17"  type="video" src="https://storage.googleapis.com/cherax-media/cam4/cam4-20250222-185752.mp4" :playtrigger="triggers.swampHarrierPaddock" mute-video="true"></ScrollyImg>

            <div class="step story" data-playtrigger="swampHarrierPaddock" data-offset="0.8">
              Here it is on the drying edges of the Crop Paddock wetland, in February 
            </div>

            <ScrollyImg class="step" data-slide="18" :class="{'hide':currentSlide > 18}" :inactive="currentSlide > 18" type="video" src="https://storage.googleapis.com/cherax-media/narrative/swamp-harrier-nest-standing.mov" :playtrigger="triggers.swampHarrierNesting"></ScrollyImg>

            <div class="step story" data-playtrigger="swampHarrierNesting" data-offset="0.8">
              Landholder Peter Morton shared this video of a <router-link :to="'/captures?species=swamp-harrier'">swamp harrier</router-link> nesting in reeds
            </div>

            <ScrollyImg class="step" data-slide="19" :class="{'hide':currentSlide > 19}" :inactive="currentSlide > 19" type="video" src="https://storage.googleapis.com/cherax-media/narrative/swamp-harrier-nest-settling.mov" :playtrigger="triggers.swampHarrierSettling"></ScrollyImg>

            <div class="step story" data-playtrigger="swampHarrierSettling" data-offset="0.8">
              Peter's use of automated cameras to document the wetlands on his property was the inspiration for this project. 
            </div>

        </section>

        <section>

            <div class="section-head">
                  <h3>4. Seen and Heard</h3>
            </div>

            <ScrollyImg class="step" data-slide="20" :class="{'hide':currentSlide > 20}" :inactive="currentSlide > 20" type="video" src="https://storage.googleapis.com/cherax-media/narrative/dragonfly-timelapse.mov" :playtrigger="triggers.timeLapse" rate="0.5" slideclass="nopad"></ScrollyImg>

            <div class="step story" data-playtrigger="timeLapse" >
               Wetlands are packed with life, but it’s not always easy to see
            </div> 

            <div class="story">
               Insects and invertebrates are crucial to these ecosystems. One of our time-lapse cameras shows spiders hunting through the night
            </div> 

            <div class="story" >
               A dragonfly nymph perches to shed its exoskeleton
            </div>

            <div class="story">
               And emerges as an adult dragonfly, opening its wings to fly off before dawn
            </div>

             <ScrollyImg class="step" data-slide="21" :class="{'hide':currentSlide > 21}" type="img" src="https://storage.googleapis.com/cherax-media/narrative/timeline-grab.jpg"></ScrollyImg>

            <div class="step story" >
               Many of the birds in the wetland chorus, like <router-link :to="'timeline?species=sacred-kingfisher'">kingfishers</router-link>, 
               <router-link :to="'timeline?species=australian-reed-warbler'">reed warblers</router-link> and <router-link :to="'timeline?species=white-plumed-honeyeater'">honeyeaters</router-link>, can be heard more than seen
            </div>

            <div class="story">
               Find them in the <router-link :to="'/timeline'">Timeline</router-link>, where we use automated recognition to identify their calls.
            </div>

            <ScrollyImg class="step" data-slide="22" :class="{'hide':currentSlide > 22}" type="img" src="https://storage.googleapis.com/cherax-media/narrative/bell-frog-closeup.jpg"></ScrollyImg>

            <div class="story" >
               <p>But this system doesn't recognise frogs, the noisiest wetland creatures.</p> 
               <p>Endangered southern bell frogs are booming in these wetlands.</p>
            </div>

            <ScrollyImg class="step" data-slide="23" :class="{'hide':currentSlide > 23}" :inactive="currentSlide > 23" type="video" src="https://storage.googleapis.com/cherax-media/narrative/bell-frog-hopping.mov" :playtrigger="triggers.bellFrogHopping" ></ScrollyImg>

            <div class="story step" data-playtrigger="bellFrogHopping" data-offset="0.8">
               <p>Landholder Peter Morton shared this southern bell frog with us.</p> 

               <p>Check out <a class="newtab" href="https://media.flow-mer.org.au/napnap/" target="_blank">The Sound of Water</a> to learn more about frogs in Murrumbidgee wetlands.</p>
            </div>

        </section>

        <section>

            <div class="section-head">
                  <h3>5. Farmland / Wetland</h3>
            </div>

            <div class="story less-bottom-margin" >
               <p>The landscape changes as the wetland dries out. Slide to see the difference between November and January on <router-link :to="'captures?cam=D'">Camera D</router-link></p> 
            </div>
          
            <ImgComparisonSlider class="slider" value="80">
                <!-- eslint-disable -->
                <img
                  slot="first"
                  style="width: 100%"
                  src="https://storage.googleapis.com/cherax-media/cam6/cam6-20241122-070424.JPG"
                />
                <img
                  slot="second"
                  style="width: 100%"
                  src="https://storage.googleapis.com/cherax-media/cam6/cam6-20250126-194728.JPG"
                />
                <!-- eslint-enable -->
            </ImgComparisonSlider>

            <ScrollyImg class="step" data-slide="24" :class="{'hide':currentSlide > 24}" type="img" src="https://storage.googleapis.com/cherax-media/narrative/histo-grab.jpg" slideclass="histograb"></ScrollyImg>

            <div class="story">
               <p>In the <router-link :to="'/captures'">Captures</router-link> we see fewer waterbirds, and more mammals, including <router-link :to="'/captures?species=kangaroo'">roos,</router-link> <router-link :to="'/captures?species=sheep'">sheep</router-link> and feral species as the water recedes </p>
            </div>

            <ScrollyImg class="step" data-slide="25" :class="{'hide':currentSlide > 25}" type="video" :inactive="currentSlide > 25" src="https://storage.googleapis.com/cherax-media/cam4/cam4-20241128-203050.mp4" :playtrigger="triggers.sheepGrazing" rate="0.5" slideclass="nopad"></ScrollyImg>

            <div class="story step" data-playtrigger="sheepGrazing">
               <p>It may seem strange to see <router-link :to="'/captures?species=sheep'">sheep</router-link> on a wetland, but this is also farmland, and the work of landholders like Peter Morton is essential in sustaining it.</p> 
            </div>

            <ScrollyImg class="step" data-slide="26" :class="{'hide':currentSlide > 26}" type="img" src="https://storage.googleapis.com/cherax-media/narrative/peter-at-carp-screen.jpg" slideclass="noaspect"></ScrollyImg>

             <div class="story" data-layout="contain" data-aspect="false">
               <p>Peter has installed screens to exclude European carp from these wetlands, improving water quality and helping native frogs, fish and birds to thrive.</p>
            </div>

            <ScrollyImg class="step" data-slide="27" :class="{'hide':currentSlide > 27}" type="img" src="https://storage.googleapis.com/cherax-media/narrative/carp-screen-midshot.jpg" slideclass="noaspect"></ScrollyImg>

            <ScrollyImg class="step" data-slide="28" :class="{'hide':currentSlide > 28}" type="img" src="https://storage.googleapis.com/cherax-media/narrative/carp-closeup.jpg" slideclass="noaspect"></ScrollyImg>

            <ScrollyImg class="step" data-slide="29" :class="{'hide':currentSlide > 29}" :inactive="currentSlide > 29" type="video" src="https://storage.googleapis.com/cherax-media/narrative/swamp-harrier-nest-settling.mov" :playtrigger="triggers.harrierSettlingFinal" slideclass="nopad"></ScrollyImg>

            <div class="step story" data-playtrigger="harrierSettlingFinal" data-offset="0.8">
               
               <p>Remember the <router-link :to="'/captures?species=swamp-harrier'">swamp harrier</router-link>, nesting in the reeds?</p> 

            </div>

            <ScrollyImg class="step" data-slide="30" :class="{'hide':currentSlide > 30}" :inactive="currentSlide > 30"  type="video" src="https://storage.googleapis.com/cherax-media/narrative/swamp-harrier-nest-fox.mov" :playtrigger="triggers.harrierNestFox" slideclass="nopad"></ScrollyImg>

              <div class="step story" data-playtrigger="harrierNestFox" data-offset="0.8">
               
               <p>The same day, Peter's camera captured a <router-link :to="'/captures?species=fox'">fox</router-link> eating all the eggs in the nest </p> 

            </div>

            <ScrollyImg class="step" data-slide="31" :class="{'hide':currentSlide > 31}" :inactive="currentSlide > 31" type="video" src="https://storage.googleapis.com/cherax-media/narrative/exclusion-fence-causeway.mov" :playtrigger="triggers.exclusionFence" slideclass="nopad"></ScrollyImg>

             <div class="step story" data-playtrigger="exclusionFence" data-offset="0.8">
               <p>Seeing this, and the impacts from other ferals, prompted Peter to install an exclusion fence in 2025</p>
            </div>

            <div class="story">
               <p>This ten-kilometre fence will keep out goats, <router-link :to="'/captures?species=fox'">foxes</router-link>, <router-link :to="'/captures?species=cat'">cats</router-link> and other feral species.</p>
            </div>

            <ScrollyImg class="step" data-slide="32" :class="{'hide':currentSlide > 32}" type="img" src="https://storage.googleapis.com/cherax-media/narrative/peter-upper-cherax.jpg" slideclass="noaspect"></ScrollyImg>

            <div class="story">
               <p>For Peter, this work and these wetlands will be his legacy.</p>
            </div>

            <ScrollyImg class="step" data-slide="33" :class="{'hide':currentSlide > 33}" type="img" src="https://storage.googleapis.com/cherax-media/narrative/upper-cherax-red-weed.jpg" slideclass="noaspect"></ScrollyImg>

            <div class="story">
               <p>It’s a legacy that benefits all of us.</p>
            </div>

        </section>
     </VueScrollama>
 
        
    
</template>

<style scoped>

    .step{
        transition: opacity 1s;
    }

    .step.hide{
    opacity:0;
   }

   section{
    padding-bottom: 1vh;
   }

   .splash{
        position:fixed;
        top:0;
        transition: opacity 2s;
   }

   .splash.fade{
    opacity:0;
   }

   .splash img{
    width:100%;
    height:auto;
    min-height:120vh;
    object-fit:cover;
    object-position: 50% 50%;
    margin-top:-8vh;
   }

   .sky-extender{
    width:100%;
    height:40vh;
    background: #91bac4;
background: linear-gradient(180deg, rgba(145, 186, 196, 1) 0%, rgba(195, 218, 224, 1) 80%, rgba(195, 218, 224, 0) 100%);
    position:relative;
    z-index:1;
   }

   .intro{
    margin: 10vh auto;
    position:relative;
    z-index:2;
   }

   .intro h1{
    margin: 0 auto;
    font-size:400%;
    text-align: center;
   }

   .intro .story{
    margin:30vh auto;
   }

   .intro .map{
    width: 30%;
    height:250px;
    max-width:460px;
    min-width: 240px;
    margin: 10vh auto;
/*    display: block;*/
   }

   .intro .map img{
    width:100%;
    object-fit: contain;
    object-position: 50% 50%;
   }

   .inline-nav{
    width:50%;
    min-width:320px;
    max-width:580px;
/*    height:120px;*/
/*    height:80px;*/
    margin: 0 auto;
    display: flex;
   }

   .inline-nav a{
    
   }

   .inline-nav .col{
/*    height:100%;*/
    width:50%;
    margin:0;
    padding:2rem 1rem;
    position:relative;
    opacity:0.7;
    cursor: pointer;
    transition: transform 0.5s;
   }

   .inline-nav .col:hover{
    opacity:1;
    transform:scale(1.05);
   }

   .inline-nav .col h2{
    
    margin:0.5rem 0;
    width:100%;
    font-size: 2rem;
    height:2rem;
   }

   .inline-nav .col p{
    font-family: 'Lato', sans-serif;
    font-size:1.3rem;
    margin:0 0 1rem;
/*    max-width:120px;*/
/*    position:absolute;*/
/*    width:180px;*/
   }

   .col.captures{
    text-align: right;
    background-color: #8fcece;
    background: linear-gradient(270deg,rgba(143, 206, 206, 1) 0%, rgba(143, 206, 206, 0) 80%);
    clip-path: polygon(0% 0%, 100% 0%, calc(100% - 20px) 100%, 0% 100%);
    padding-right:3rem;
    margin-right:-0.25rem;
   }

   .col.captures p{
/*    position:absolute;*/
/*    right:0;*/
   }

   .col.timeline{
    text-align: left;
    background-color: #e0b2a3;
    background: linear-gradient(90deg,rgba(224, 178, 163, 1) 0%, rgba(224, 178, 163, 0) 80%);;
    clip-path: polygon(20px 0%, 100% 0%, 90% 100%, 0% 100%);
    padding-left:3rem;
    margin-left:-0.25rem;

   }





   .story{
      margin: -10vh auto 60vh;
      width:50%;
      max-width: 480px;
      background-color: white;
      padding:2rem;
      box-shadow: 0px 0px 12px rgba(0,0,0,0.2);
      position: relative;
      z-index:1;
   }

   .story, .story p{
      font-family: Lato, sans-serif;
      font-size: 1.3rem;
      line-height: 1.8rem;
   }

   .story p{
    margin:0;
   }

   .story.no-bottom-margin{
    margin-bottom:0;
   }

   .story.less-bottom-margin{
/*    margin-top:5vh;*/
    margin-bottom:10vh;
   }

   .story.less-top-margin{
    margin-top:10vh;
   }


   .section-head{
      margin:20vh auto 10vh;
      padding:0;
      max-width:800px;
      width:80%;
   }

   .section-head.nomargin{
    margin-top:none;
   }

   .section-head h3{
      width:100%;
      text-align: center;
      font-family: 'Inconsolata', monospace;
      font-size: 3rem;
      color: white;
      text-shadow: 2px 2px 9px rgba(0,0,0,0.7);
   }

   .step.trigger{
      margin:15vh auto;
   }

   .step.nomargin{
      margin:4rem auto;
   }

   .story.step.final{
    margin-bottom:100vh;
   }

   .scroller{
    height:50vh;
    background-color: rgba(0,0,0,0.2);
    width:10px;
    opacity:0;
   }



   .slider{
      display: block;
      margin:0 auto;
      width:80%;
      max-width: 1400px;
      max-height: 80vh;
      --divider-width: 2px;
      --default-handle-width: 100px;
      border:1vw solid black;
   }

   .slider:focus{
      outline: none;
   }

   .story a, button {
     display:inline-block;
     padding: 2px 9px;
     margin:0.2rem 0.2rem ;
     color:black;
     text-decoration:none;
     background-color: #e0b2a3ba;
     border-radius: 0.66rem;
     border:1px solid #e0b2a3ba;
     cursor: pointer;
     font-family: 'Inconsolata', monospace;
   }

   .story a:hover, button:hover{
      border:1px solid black;
    }

   .story a.newtab{
     padding: 4px 27px 4px 9px;
     background-color: rgba(226, 227, 216, 1);
     border:1px solid rgba(226, 227, 216, 1);
   }

    .story a:hover{
      border:1px solid black;
    }


   .story a.newtab::after{
       content: " ";
       background-image: url(@/assets/img/newtab.png);
       background-size: contain;
       background-repeat: no-repeat;
       background-position:20% 0;
       display: inline-block;
       height: 0.9em;
       width: 2em;
       margin-right: -2em;
       position: relative;
       top: 2px;
   }

   .listen-button button{
    font-size: 18px;
    padding:9px 12px;
    display: block;
    margin:0 auto;
   }


</style>



<style>
    
    .carousel__item img{
        width: 100%;
        height: 100%;
/*        object-fit: contain;*/
   }

   .carousel__item .caption{
    position:absolute;
    bottom:0;
    z-index:1;
    color:white;
    font-weight: 600;
    text-align: center;
    text-shadow: 0px 0px 6px rgba(0,0,0,0.5);
    width:100%;
    font-size:1.2rem;
   }

   .carousel {
      --vc-pgn-background-color: rgba(255, 255, 255, 0.7);
      --vc-pgn-active-color: rgba(255, 255, 255, 1);
      --vc-nav-background: rgba(255, 255, 255, 0.7);
      --vc-nav-border-radius: 100%;
      background-color: black;
      padding:1rem 0;
      max-width:1280px;
      margin: 0 auto;
    /*  height:460px;*/
    }

    .carousel__next{
        margin-right:0.5rem;
    }

    .carousel__prev{
        margin-left:0.5rem;
    }
</style>