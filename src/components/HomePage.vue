
<script setup>
   import TimelapseFrame from '@/components/TimelapseFrame.vue'
   import SlideShow from '@/components/SlideShow.vue'
   // import 'intersection-observer' // for cross-browser support
   import VueScrollama from "@/lib/VueScrollama.vue";
   import { ImgComparisonSlider } from '@img-comparison-slider/vue';
</script>
<script>

   export default{
      data(){
         return {
            currentSlide: null,
            hidden:false
            // currentAnimation:null
         }
      },

      methods: {
          stepEnterHandler ({element, index, direction}) {
            if (element.dataset.url) this.currentSlide = element.dataset;
            if (element.dataset.hide) {
               this.hidden = true
            } else {
               this.hidden = false
            }
            // if (element.dataset.animate) this.currentAnimation = element.dataset.animate;
          },

          stepProgressHandler({ element, progress, index }){
            console.log("step-progress", { element, progress, index });
          },

          stepExitHandler({ element, index }){
            console.log("step exit", {element, index})
          }
      }
   }


</script>


<template>

   <div class="sticky">
      <SlideShow :slide="currentSlide" :initSlide="'https://storage.googleapis.com/cherax-media/cam1/20241102/cam1-20241102-40.JPG'" :hidden="hidden"></SlideShow>
   </div>

      <div class="steps">

        <VueScrollama
            :debug="true"
            :offset="0.8"
            :progress="true"
            @step-enter="stepEnterHandler" @step-progress="stepProgressHandler" 
            @step-exit="stepExitHandler"
            class="main__scrollama">



  
            <div class="step section-head">
                  <h3>1. Waterbirds</h3>
            </div>

            <div class="step" 
            data-url="https://storage.googleapis.com/cherax-media/cam1/20241102/cam1-20241102-40.JPG" 
            data-type="img">
                The water is high in early November 
            </div>
            
            <div class="step" data-url="https://storage.googleapis.com/cherax-media/cam4/cam4-20241102-060724.JPG" 
            data-type="img" >
                At dawn, a <router-link :to="'/captures?species=pelican'">pelican</router-link> touches down at the edge of the flooded Crop Paddock 
            </div>
           
           <div class="step" data-url="https://storage.googleapis.com/cherax-media/cam7/cam7-20241106-103414.mp4" data-type="vid" >
                A <router-link :to="'/captures?species=yellow-billed-spoonbill'">yellow-billed spoonbill</router-link> and a flock of <router-link :to="'/captures?species=native-hen'">native hens</router-link> feed around the nearby snag... 
            </div>
            
            <div class="step" data-url="https://storage.googleapis.com/cherax-media/cam7/cam7-20241109-072636.mp4 " data-type="vid" data-offset="0.8">
                and a <router-link :to="'/captures?species=white-faced-heron'">white-faced heron</router-link> spears a morsel. 
            </div>

<!--             <div class="step">
               These wetlands are bustling with waterbirds.
            </div> -->

            <div class="step" data-url="https://storage.googleapis.com/cherax-media/cam6/cam6-20241122-060704.JPG" data-type="img" data-offset="0.8">
               Later in November, <router-link :to="'/captures?species=great-egret'">great egrets</router-link> and a <router-link :to="'/captures?species=white-necked-heron'">white-necked heron</router-link> pose on <router-link :to="'/captures?cam=D'">Camera D</router-link>            
            </div>

            <div class="step" data-url="https://storage.googleapis.com/cherax-media/cam3/cam3-20241116-055548.JPG" data-type="img" data-offset="0.8">
               <router-link :to="'/captures?cam=A'">Camera A,</router-link> Looking towards Upper Cherax, snaps a flock of <router-link :to="'/captures?species=white-faced-heron'">pacific black ducks</router-link> taking off in the pre-dawn 
            </div>

            <div class="step" data-url="https://storage.googleapis.com/cherax-media/cam3/cam3-20241216-093840.JPG" data-type="img" data-offset="0.8">
               This shot captures  <router-link :to="'/captures?species=white-ibis'">white ibis,</router-link> <router-link :to="'/captures?species=straw-necked-ibis'">straw-necked ibis</router-link> and <router-link :to="'/captures?species=white-necked-heron'">white-necked herons</router-link> in flight 
            </div>

            <div class="step" data-url="https://storage.googleapis.com/cherax-media/cam3/cam3-20241216-093850.mp4" data-type="vid" data-offset="0.8">
               The <router-link :to="'/captures?species=great-cormorant'">great cormorant</router-link> follows them a second later  
            </div>

             <div class="step" data-url="https://storage.googleapis.com/cherax-media/cam4/cam4-20250131-105624.JPG" data-type="img" data-offset="0.8">
               By the end of January, the water level has dropped, and <router-link :to="'/captures?species=pacific-black-duck'">ducks</router-link>, <router-link :to="'/captures?species=pelican'">pelicans</router-link> and <router-link :to="'/captures?species=black-swan'">black swans</router-link> are gathering in numbers out on the Crop Paddock   
            </div>

            <!-- SECTION -->

            <div class="step hider" data-hide="true"></div>

            <div class="step section-head" data-hide="true">
                  <h3>2. Feeding</h3>
            </div>

             <div class="step" data-url="https://storage.googleapis.com/cherax-media/cam6/cam6-20241207-113632.mp4" data-type="vid" data-offset="0.8">
               Videos capture some of these waterbirds' signature feeding moves.    
            </div>

            <div class="step">
              Herons and egrets “stir” the flooded ground to scare out small fish and insects – here's a <router-link :to="'/captures?species=white-faced-heron'">white-faced heron</router-link> demonstrating   
            </div>

            <div class="step" data-url="https://storage.googleapis.com/cherax-media/cam6/cam6-20241121-072410.mp4" data-type="vid" data-offset="0.8">
              This <router-link :to="'/captures?species=yellow-spoonbill'">yellow spoonbill</router-link> sweeps its broad, sensitive bill from side to side, hunting by feel,  
            </div>

            <div class="step">
              Ducks like the <router-link :to="'/captures?species=pacific-black-duck'">pacific black duck</router-link> are named “dabblers” for their head-down, tail-up feeding style.   
            </div>

            <div class="step" data-url="https://storage.googleapis.com/cherax-media/cam7/cam7-20241121-165624.mp4" data-type="vid" data-offset="0.8">
              At the snag, a <router-link :to="'/captures?species=white-ibis'">white ibis</router-link> and a <router-link :to="'/captures?species=straw-necked-ibis'">straw-necked ibis</router-link> squabble over possession of a juicy frog.    
            </div>


            <!-- SECTION -->

             <div class="step hider" data-hide="true"></div>

            <div class="step section-head" data-hide="true">
                  <h3>3. Raptors</h3>
            </div>

            <div class="step" data-url="https://storage.googleapis.com/cherax-media/cam7/cam7-20241230-155324.JPG" data-type="img" data-offset="0.8">
                As well as waterbirds, wetlands like this are havens for birds of prey 
            </div>

             <div class="step">
                <p>Hiding in this frame is a <router-link :to="'/captures?species=whistling-kite'">whistling kite,</router-link> an Australian raptor often found near water.</p>
                <p>[ zoom in animation ]</p>  
            </div> 

            <div class="step" data-url="https://storage.googleapis.com/cherax-media/cam3/cam3-20250109-105800.JPG" data-type="img" data-offset="0.8">
                This capture from <router-link :to="'/captures?cam=A'">Camera A</router-link> shows the distinctive ‘fingers’ on the whistling kite’s wingtips 
                <p>[ zoom in animation ]</p>  
            </div> 

            <div class="step">
               Whistling kites are named for their piercing call. [Listen] here, or find it in the <router-link :to="'/timeline?species=whistling-kite'">Timeline</router-link>
               
            </div>

            <div class="step" data-url="https://storage.googleapis.com/cherax-media/cam3/cam3-20241219-063226.mp4" data-type="vid" data-offset="0.8">
              <p>This video finds two <router-link :to="'/captures?species=whistling-kite'">kites</router-link> squabbling over their catch – perhaps a crayfish.</p>   

            <p>[Scroll] to see the moment the crayfish splashes down to safety</p>    
            </div>

             <div class="step" data-url="https://storage.googleapis.com/cherax-media/cam4/cam4-20250222-185740.JPG" data-type="img" data-offset="0.8">
                We also spotted a <router-link :to="'/captures?species=swamp-harrier'">swamp harrier,</router-link> another raptor often found in wetlands  
            </div>

            <div class="step" data-url="https://storage.googleapis.com/cherax-media/cam4/cam4-20250222-185752.mp4" data-type="vid" data-offset="0.8">
              Here it is on the drying edges of the Crop Paddock wetland, in February 
            </div>

            <div class="step" data-url="https://storage.googleapis.com/cherax-media/narrative/swamp-harrier-nest-standing.mov " data-type="vid" data-offset="0.8">
              Peter Morton shared this video of a <router-link :to="'/captures?species=swamp-harrier'">swamp harrier</router-link> nesting in reeds
            </div>

            <div class="step" data-url="https://storage.googleapis.com/cherax-media/narrative/swamp-harrier-nest-settling.mov " data-type="vid" data-offset="0.8">
              Peter's use of automated cameras to document the wetlands on his property was the inspiration for this project 
            </div>

            
            <!-- SECTION -->

            <div class="step hider" data-hide="true"></div>

            <div class="step section-head" data-hide="true">
                  <h3>4. Seen and Heard</h3>
            </div>

            <div class="step" data-url="https://storage.googleapis.com/cherax-media/narrative/dragonfly-timelapse.mov " data-type="vid" data-class="nopad" data-offset="0.8">
               Wetlands are packed with life, but it’s not always easy to see
            </div>  

            <div class="step" >
               Insects and invertebrates are crucial to these ecosystems. One of our time-lapse cameras shows spiders hunting through the night
            </div> 

            <div class="step" >
               A dragonfly nymph perches to shed its exoskeleton
            </div>

            <div class="step" >
               And emerges as an adult dragonfly, opening its wings to fly off before dawn
            </div>

            <div class="step" data-url="https://storage.googleapis.com/cherax-media/narrative/timeline-grab.jpg" data-type="img" data-offset="0.8">
               Many of the birds in the wetland chorus, like <router-link :to="'timeline?species=sacred-kingfisher'">kingfishers</router-link>, 
               <router-link :to="'timeline?species=australian-reed-warbler'">reed warblers</router-link> and <router-link :to="'timeline?species=white-plumed-honeyeater'">honeyeaters</router-link>, can be heard more than seen
            </div>

            <div class="step">
               Find them in the <router-link :to="'/timeline'">Timeline</router-link>, where we use automated recognition to identify their calls.
            </div>

             <div class="step" data-url="https://storage.googleapis.com/cherax-media/narrative/bell-frog-closeup.jpg" data-type="img" data-offset="0.8">
               <p>But this system doesn't recognise frogs, the noisiest wetland creatures.</p> 
               <p>Endangered southern bell frogs are booming in these wetlands.</p>
            </div>

            <div class="step" data-url="https://storage.googleapis.com/cherax-media/narrative/bell-frog-hopping.mov" data-type="vid" data-offset="0.8">
               <p>Peter shared this southern bell frog with us.</p> 

               <p>Check out <a class="newtab" href="https://media.flow-mer.org.au/napnap/" target="_blank">The Sound of Water</a> to learn more about frogs in Murrumbidgee wetlands.</p>
            </div>

            <!-- SECTION -->



            <div class="step hider" data-hide="true"></div>

            <div class="step section-head" data-hide="true">
                  <h3>5. Farmland / Wetland</h3>
            </div>

            <div class="step nomargin" >
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

            <div class="step" data-url="https://storage.googleapis.com/cherax-media/narrative/histo-grab.jpg" data-type="img" data-offset="0.8" data-layout="contain" >
               <p>In the <router-link :to="'/captures'">Captures</router-link> we see fewer waterbirds, and more mammals, including <router-link :to="'/captures?species=kangaroo'">roos,</router-link> <router-link :to="'/captures?species=sheep'">sheep</router-link> and feral species as the water recedes </p>
            </div>

            <div class="step" data-url="https://storage.googleapis.com/cherax-media/cam4/cam4-20241128-203050.mp4" data-type="vid" data-offset="0.8" >
               
               <p>It may seem strange to see <router-link :to="'/captures?species=sheep'">sheep</router-link> on a wetland, but this is also farmland, and the work of landholders like Peter is essential in sustaining it.</p> 

            </div>

             <div class="step" data-url="https://storage.googleapis.com/cherax-media/narrative/peter-at-carp-screen.jpg" data-type="img" data-offset="0.8" data-layout="contain" data-aspect="false">
               <p>Peter has installed screens to exclude European carp from these wetlands, improving water quality and helping native frogs, fish and birds to thrive.</p>
            </div>

             <div class="step trigger" data-url="https://storage.googleapis.com/cherax-media/narrative/carp-screen-midshot.jpg" data-type="img" data-offset="0.5" data-layout="contain" data-aspect="false">
           
            </div>

            <div class="step trigger" data-url="https://storage.googleapis.com/cherax-media/narrative/carp-closeup.jpg" data-type="img" data-offset="0.5" data-layout="contain" data-aspect="false">
         
            </div>

             <div class="step" data-url="https://storage.googleapis.com/cherax-media/narrative/swamp-harrier-nest-settling.mov" data-type="vid" data-offset="0.8" >
               
               <p>Remember the <router-link :to="'/captures?species=swamp-harrier'">swamp harrier</router-link>, nesting in the reeds?</p> 

            </div>

            <div class="step" data-url="https://storage.googleapis.com/cherax-media/narrative/swamp-harrier-nest-fox.mov" data-type="vid" data-offset="0.8" >
               
               <p>The same day, Peter's camera captured a <router-link :to="'/captures?species=fox'">fox</router-link> eating all the eggs in the nest </p> 

            </div>

            <div class="step" data-url="https://storage.googleapis.com/cherax-media/narrative/exclusion-fence-causeway.mov" data-type="vid" data-offset="0.8" >
               
               <p>Seeing this, and the impacts from other ferals, prompted Peter to install an exclusion fence in 2025</p>
            </div>

            <div class="step">
               <p>This ten-kilometre fence will keep out goats, <router-link :to="'/captures?species=fox'">foxes</router-link>, <router-link :to="'/captures?species=cat'">cats</router-link> and other feral species.</p>
            </div>

             <div class="step" data-url="https://storage.googleapis.com/cherax-media/narrative/peter-upper-cherax.jpg" data-type="img" data-offset="0.8" data-layout="contain" data-aspect="false">
               <p>For Peter, this work and these wetlands will be his legacy.</p>
            </div>

            <div class="step" data-url="https://storage.googleapis.com/cherax-media/narrative/upper-cherax-red-weed.jpg" data-type="img" data-offset="0.8" data-layout="contain" data-aspect="false">
               <p>It’s a legacy that we all benefit from.</p>
            </div>




             



           







             




        </VueScrollama>
      </div>




</template>

<style scoped>
   
   .steps{
      padding:0 0 600px 0;
      position:relative;
      z-index:1;
   }


   .step{
      margin: 80vh auto 80vh;
      width:50%;
      max-width: 480px;
      background-color: white;
      padding:2rem;
      box-shadow: 0px 0px 12px rgba(0,0,0,0.2);
/*      z-index:1;*/
   }

   .step, .step p{
      font-family: Lato, sans-serif;
      font-size: 1.3rem;
      line-height: 2rem;
   }

   .step:first-of-type{
      margin-top: 20vh;
   }


   .step.section-head{
      margin:10vh auto;
      padding:0;
      background:none;
      box-shadow: none;
      max-width:800px;
      width:80%;

   }

   .step.section-head h3{
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

   .sticky{
      position:sticky;
      top:0;
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

   .step a {
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

   .step a:hover{
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