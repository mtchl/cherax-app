
<script setup>
   import TimelapseFrame from '@/components/TimelapseFrame.vue'
   import ScrollyImg from '@/components/ScrollyImg.vue'
   // import SlideShow from '@/components/SlideShow.vue'
   // import 'intersection-observer' // for cross-browser support
   import VueScrollama from "@/lib/VueScrollama.vue";
   import { ImgComparisonSlider } from '@img-comparison-slider/vue';
</script>
<script>

   export default{
      data(){
         return {
            currentSlide: null,
            hidden:false,
            animate:null,
            // currentAnimation:null
            // triggers: {
            //     spoonbillHenSnag:false, 
            //     spearMorsel:false,
            //     greatCormorant:false,
            //     footStir:false,
            //     spoonbillHunting:false,
            //     squabbleFrog:false,
            //     kiteTussle:false,
            //     swampHarrierPaddock:false,
            //     swampHarrierSettling:false,
            // },
            triggers:{},
            animations:{},
            progress:{}
         }
      },

      methods: {
          stepEnterHandler ({element, index, direction}) {
            // console.log(element)
            if (element.dataset.playtrigger){
                this.triggers[element.dataset.playtrigger] = true;
                console.log("playtrigger " + element.dataset.playtrigger)

            }
            // stash the animation class in the animations object
            if (element.dataset.animate){
                this.animations[element.dataset.animtarget] = element.dataset.animate;
                console.log(element.dataset.animate)
            }



          },

          stepProgressHandler({ element, progress, index }){
            if (element.dataset.progress){
                this.progress[element.dataset.progress] = progress;
                console.log(progress)
            }
          },

          stepExitHandler({ element, index, direction }){
            //console.log("step exit", {element, index})

            if (direction == "up" && element.dataset.playtrigger){
                if (this.triggers[element.dataset.playtrigger]){
                    this.triggers[element.dataset.playtrigger] = false;
                }
            }
          }
      }
   }


</script>


<template>

   <div class="steps">

     <VueScrollama offset="0.8" progress     
         @step-enter="stepEnterHandler" @step-progress="stepProgressHandler" 
         @step-exit="stepExitHandler"
         class="main__scrollama">
        <section>
             <div class="section-head">
                   <h3>1. Waterbirds</h3>
            </div>
         

            <ScrollyImg class="step" type="img" src="https://storage.googleapis.com/cherax-media/cam1/20241102/cam1-20241102-40.JPG"></ScrollyImg>
       

            <div class="story" >
                 The water is high in early November 
            </div>

            <ScrollyImg class="step" type="img" src="https://storage.googleapis.com/cherax-media/cam4/cam4-20241102-060724.JPG"></ScrollyImg>
             
            <div class="story">
                 At dawn, a <router-link :to="'/captures?species=pelican'">pelican</router-link> touches down at the edge of the flooded Crop Paddock 
            </div>

            <ScrollyImg class="step" type="video" data-playtrigger="spoonbillHenSnag" :playtrigger="triggers.spoonbillHenSnag" src="https://storage.googleapis.com/cherax-media/cam7/cam7-20241106-103414.mp4"></ScrollyImg>

            <div class="story" >
                    A <router-link :to="'/captures?species=yellow-billed-spoonbill'">yellow-billed spoonbill</router-link> and a flock of <router-link :to="'/captures?species=native-hen'">native hens</router-link> feed around the nearby snag... 
            </div>

            <ScrollyImg class="step" type="video" :playtrigger="triggers.spearMorsel" src="https://storage.googleapis.com/cherax-media/cam7/cam7-20241109-072636.mp4"></ScrollyImg>

            <div class="story step" data-playtrigger="spearMorsel">
                    and a <router-link :to="'/captures?species=white-faced-heron'">white-faced heron</router-link> spears a morsel. 
            </div>

            <ScrollyImg class="step" type="img" src="https://storage.googleapis.com/cherax-media/cam6/cam6-20241122-060704.JPG"></ScrollyImg>

            <div class="story">
                   Later in November, <router-link :to="'/captures?species=great-egret'">great egrets</router-link> and a <router-link :to="'/captures?species=white-necked-heron'">white-necked heron</router-link> pose on <router-link :to="'/captures?cam=D'">Camera D</router-link>            
            </div>

            <ScrollyImg class="step" type="img" src="https://storage.googleapis.com/cherax-media/cam3/cam3-20241116-055548.JPG"></ScrollyImg>

            <div class="story">
                   <router-link :to="'/captures?cam=A'">Camera A,</router-link> Looking towards Upper Cherax, snaps a flock of <router-link :to="'/captures?species=white-faced-heron'">pacific black ducks</router-link> taking off in the pre-dawn 
            </div>

            <ScrollyImg class="step" type="img" src="https://storage.googleapis.com/cherax-media/cam3/cam3-20241216-093840.JPG"></ScrollyImg>

            <div class="story">
                   This shot captures  <router-link :to="'/captures?species=white-ibis'">white ibis,</router-link> <router-link :to="'/captures?species=straw-necked-ibis'">straw-necked ibis</router-link> and <router-link :to="'/captures?species=white-necked-heron'">white-necked herons</router-link> in flight 
            </div>

            <ScrollyImg class="step" data-playtrigger="greatCormorant" type="video" :playtrigger="triggers.greatCormorant" src="https://storage.googleapis.com/cherax-media/cam3/cam3-20241216-093850.mp4"></ScrollyImg>

            <div class="story" >
                   The <router-link :to="'/captures?species=great-cormorant'">great cormorant</router-link> follows them a second later  
            </div>

             <ScrollyImg class="step" type="img" src="https://storage.googleapis.com/cherax-media/cam4/cam4-20250131-105624.JPG"></ScrollyImg>

             <div class="story step final" >
                   By the end of January, the water level has dropped, and <router-link :to="'/captures?species=pacific-black-duck'">ducks</router-link>, <router-link :to="'/captures?species=pelican'">pelicans</router-link> and <router-link :to="'/captures?species=black-swan'">black swans</router-link> are gathering in numbers out on the Crop Paddock   
             </div>

        </section>


        <section>
            <div class="section-head nomargin">
                <h3>2. Feeding</h3>
            </div>

            <ScrollyImg class="step" data-playtrigger="footStir" type="video" :playtrigger="triggers.footStir" src="https://storage.googleapis.com/cherax-media/cam6/cam6-20241207-113632.mp4"></ScrollyImg>

            <div class="story" >
               Videos capture some of these waterbirds' signature feeding moves.    
            </div>

            <div class="story">
              Herons and egrets “stir” the flooded ground to scare out small fish and insects – here's a <router-link :to="'/captures?species=white-faced-heron'">white-faced heron</router-link> demonstrating   
            </div>

             <ScrollyImg  type="video" :playtrigger="triggers.spoonbillHunting" src="https://storage.googleapis.com/cherax-media/cam6/cam6-20241121-072410.mp4"></ScrollyImg>

            <div class="story step" data-playtrigger="spoonbillHunting">
              This <router-link :to="'/captures?species=yellow-spoonbill'">yellow spoonbill</router-link> sweeps its broad, sensitive bill from side to side, hunting by feel,  
            </div>

            <div class="story step">
              Ducks like the <router-link :to="'/captures?species=pacific-black-duck'">pacific black duck</router-link> are named “dabblers” for their head-down, tail-up feeding style.   
            </div>

            <ScrollyImg type="video" :playtrigger="triggers.squabbleFrog" src="https://storage.googleapis.com/cherax-media/cam7/cam7-20241121-165624.mp4"></ScrollyImg>

            <div class="story step" data-playtrigger="squabbleFrog">
              Later that day at the snag, a <router-link :to="'/captures?species=white-ibis'">white ibis</router-link> and a <router-link :to="'/captures?species=straw-necked-ibis'">straw-necked ibis</router-link> squabble over possession of a juicy frog.    
            </div>
            
        </section>


        <section>

            <div class="section-head nomargin">
                  <h3>3. Raptors</h3>
            </div>

            <ScrollyImg type="img" src="https://storage.googleapis.com/cherax-media/cam7/cam7-20241230-155324.JPG" :animate="animations.hidingKite"></ScrollyImg>

            <div class="story step" data-animate="reset" data-animtarget="hidingKite">
                As well as waterbirds, wetlands like this are havens for birds of prey 
            </div>

             <div class="step story" data-animate="zoom-200-right" data-animtarget="hidingKite">
                <p>Hiding in this frame is a <router-link :to="'/captures?species=whistling-kite'">whistling kite,</router-link> an Australian raptor often found near water.</p> 
            </div>

            <ScrollyImg type="img"  src="https://storage.googleapis.com/cherax-media/cam3/cam3-20250109-105800.JPG" :animate="animations.fingersKite"></ScrollyImg>

            <div class="step story" data-animate="reset" data-animtarget="fingersKite">
                This capture from <router-link :to="'/captures?cam=A'">Camera A</router-link> shows the distinctive ‘fingers’ on the whistling kite’s wingtips  
            </div>

            <div class="step story" data-animate="zoom-200-left" data-animtarget="fingersKite">
               Whistling kites are named for their piercing call. [Listen] here, or find it in the <router-link :to="'/timeline?species=whistling-kite'">Timeline</router-link>
            </div>

            <ScrollyImg type="video" src="https://storage.googleapis.com/cherax-media/cam3/cam3-20241219-063226.mp4" :playtrigger="triggers.kiteTussle" :progress="progress.kiteTussle"></ScrollyImg>

            <div class="step story" data-playtrigger="kiteTussle">
              <p>This video finds two <router-link :to="'/captures?species=whistling-kite'">kites</router-link> tussling over their catch – perhaps a crayfish.</p>       
            </div>

            <div class="story no-bottom-margin">
                <p><strong>Scroll</strong> to see the moment the crayfish splashes down to safety</p>
            </div>

            <div class="step scroller" data-progress="kiteTussle"></div> 

             <ScrollyImg type="img" src="https://storage.googleapis.com/cherax-media/cam4/cam4-20250222-185740.JPG"></ScrollyImg>

              <div class="story">
                We also spotted a <router-link :to="'/captures?species=swamp-harrier'">swamp harrier,</router-link> another raptor often found in wetlands  
            </div>

            <ScrollyImg type="video" src="https://storage.googleapis.com/cherax-media/cam4/cam4-20250222-185752.mp4" :playtrigger="triggers.swampHarrierPaddock"></ScrollyImg>

            <div class="step story" data-playtrigger="swampHarrierPaddock">
              Here it is on the drying edges of the Crop Paddock wetland, in February 
            </div>

            <ScrollyImg type="video" src="https://storage.googleapis.com/cherax-media/narrative/swamp-harrier-nest-standing.mov" :playtrigger="triggers.swampHarrierNesting"></ScrollyImg>

            <div class="step story" data-playtrigger="swampHarrierNesting">
              Landholder Peter Morton shared this video of a <router-link :to="'/captures?species=swamp-harrier'">swamp harrier</router-link> nesting in reeds
            </div>

            <ScrollyImg type="video" src="https://storage.googleapis.com/cherax-media/narrative/swamp-harrier-nest-settling.mov" :playtrigger="triggers.swampHarrierSettling"></ScrollyImg>

            <div class="step story" data-playtrigger="swampHarrierSettling">
              Peter's use of automated cameras to document the wetlands on his property was the inspiration for this project. 
            </div>

        </section>



     </VueScrollama>
    </div>
        
    
</template>

<style scoped>
   
/*   .steps{
      padding:0 0 600px 0;
      position:relative;
      z-index:1;
   }
*/

   section{
    padding-bottom: 1vh;
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
      line-height: 2rem;
   }

   .story.no-bottom-margin{
    margin-bottom:0;
   }

   .step:first-of-type{
      margin-top: 20vh;
   }


   .section-head{
      margin:20vh auto 20vh;
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

   .step.hider, .step.trigger{
      background:none;
      box-shadow: none;
      margin:5vh auto;
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
   }


   .slider{
      display: block;
      margin:0 auto;
      width:80%;
      max-width: 1000px;
      max-height: 80vh;
      --divider-width: 2px;
      --default-handle-width: 100px;
   }

   .slider:focus{
      outline: none;
   }

   .story a {
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

   .story a:hover{
      border:1px solid black;
    }

   a.newtab{
     padding: 4px 27px 4px 9px;
     background-color: rgba(226, 227, 216, 1);
     border:1px solid rgba(226, 227, 216, 1);
   }

    a:hover{
      border:1px solid black;
    }


   a.newtab::after{
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




</style>