<script setup>
     import CaptureItem from '@/components/CaptureItem.vue'
     import CaptureHisto from '@/components/CaptureHisto.vue'
     import CamMap from '@/components/CamMap.vue'
     import SpeciesInfo from '@/components/SpeciesInfoGrid.vue'
</script>

<script type="text/javascript">
   import jsonData from '@/assets/data/captures.json';
   import tagData from '@/assets/data/tags.json';
   import { interpolateRgb } from 'd3-interpolate';

   export default{
    data(){
      return {
        captureData: jsonData,
        cams: ["A","B","C","D","E","F","G"],
        months: [{label:"nov", dayrange:[0,35]}, {label:"dec", dayrange:[36,67]},{label:"jan", dayrange: [68,98]},{label:"feb", dayrange:[99,122]} ],
        baseUrl: "https://storage.googleapis.com/cherax-media/",
        viewItems: 10,
        faves: []
      }
    },

    props: ['filterSpecies','filterCam','filterMonth','sharedCapture','showFaves'],
    emits: ['updateFaves'],

    mounted(){
      let storageData = localStorage.getItem('MosaicFaves');
      if (storageData){
        this.faves = JSON.parse(storageData);
      }
    },

    methods: {

      setFilter(key,value){
        const f = {species:this.filterSpecies, cam: this.filterCam, month: this.filterMonth}
        f[key] = value;
        if (key == 'species' && this.filterSpecies == value) delete f.species;
        if (key == 'cam' && this.filterCam == value) delete f.cam;
        if (key == 'month' && this.filterMonth == value) delete f.month;
     
        if (f.cam == undefined) delete f.cam;
        if (f.species == undefined) delete f.species;
        if (f.month == undefined) delete f.month;
           console.log(f)
        this.$router.push({path: '/captures', query: f})
        this.viewItems = 10;
      },
    
      loadMore(){
        this.viewItems += 10;
      },

      convertTimestampToDate(timestampString) {
        // Ensure the input string has the correct format and length
        if (!timestampString || timestampString.length !== 15 || timestampString[8] !== '-') {
          console.error("Invalid timestamp format. Expected 'YYYYMMDD-HHMMSS'.");
          return null; // Return null for invalid input
        }

        // Extract date and time components using substring
        const year = parseInt(timestampString.substring(0, 4), 10);
        // Month is 0-indexed in JavaScript Date, so subtract 1
        const month = parseInt(timestampString.substring(4, 6), 10) - 1; 
        const day = parseInt(timestampString.substring(6, 8), 10);
        const hours = parseInt(timestampString.substring(9, 11), 10);
        const minutes = parseInt(timestampString.substring(11, 13), 10);
        const seconds = parseInt(timestampString.substring(13, 15), 10);

        // Create and return the new Date object
        return new Date(year, month, day, hours, minutes, seconds);
      },

      closeSharedModal(){
        this.$router.push({path: '/captures'});
      },

      toggleFave(id){
        let idx = this.faves.indexOf(id);
        if (idx == -1){
          this.faves.push(id);
        } else {
          this.faves.splice(idx,1); // remove the item
        }
        console.log(this.faves)
      }

    },


    computed: {

      filterState(){
        return {species: this.filterSpecies, cam: this.filterCam, month: this.filterMonth}
      },

      tags(){
        // make route friendly tags
        return tagData.map(t => {return {...t, routeTag: t.tag.toLowerCase().replaceAll(" ","-")}})
      },

      focusedSpecies(){ // full tag data for the focused species
        let s = null;
        if (this.filterSpecies) s = this.tags.find(t => t.routeTag == this.filterSpecies)
        return s;
      },


      allTags(){
        let tagData = this.monthFilteredSet.intersection(this.camFilteredSet)
        let tagArray = [...tagData]
        let ft = this.tags.map(t => {
          let c = tagArray.filter(c => c.tags.indexOf(t.tag) > -1)
          return {...t, count:c.length }
        })
        // to filter out tags with no count
        // return ft.filter(c => c.count > 0)
        return ft
      },

      tagMap(){
        let m = {};
        this.tags.forEach(t => m[t.tag] = t);
        return m;
      },

      captures(){
        
         let caps = this.captureData.filter(c => !c.blank).map(c => {

          let id = c.filename.split(".")[0];
          let cam = c.path.split("/")[0]
          let thumbUrl = c.path.split("/")[0]+"/thumbnails/thumb_" +c.path.split("/")[1];
          if (c.type == "video") thumbUrl = c.path.split("/")[0] + "/thumbnails/thumb_" + c.path.split("/")[1].replace("mp4","jpg");

          let date = c.filename.split("-")[1];
          let time = c.filename.split("-")[2].split(".")[0];
          let datetime = date+time;

          let startdate = new Date(2024,9,25); // month is zero indexed!
          let capdate = new Date(parseInt(date.substring(0,4)),parseInt(date.substring(4,6))-1,parseInt(date.substring(6,8)) )
    
          const oneDay = 24 * 60 * 60 * 1000;
          const diffDays = Math.round(Math.abs((startdate - capdate) / oneDay));

          let camLabel = cam.replace("cam","");
          // if (cam == "cam3") camLabel = "A"
          // if (cam == "cam4" && diffDays < 97) camLabel = "B"
          // if (cam == "cam4" && diffDays >= 97) camLabel = "F"
          // if (cam == "cam5" && diffDays < 97) camLabel = "C"
          // if (cam == "cam5" && diffDays >= 97) camLabel = "G"
          // if (cam == "cam6") camLabel = "D"
          // if (cam == "cam7") camLabel = "E"

          let month;
          let matchMonth = this.months.find(m => ((m.dayrange[0] <= diffDays) && (m.dayrange[1] >= diffDays)));
          month = matchMonth ? matchMonth.label : "none" ;  

          const timestamp = this.convertTimestampToDate(date+"-"+time)

         return {  ...c, 
                   id:id, 
                   cam: cam,
                   camLabel:camLabel,
                   thumb: thumbUrl, 
                   datetime:parseInt(datetime),
                   timestamp: timestamp,
                   dayIndex: diffDays,
                   month:month
                 };
        })

         caps =  caps.sort((a,b) => { return a.datetime - b.datetime})
         console.log(caps.length)

         if (this.showFaves){
          caps = caps.filter(c => this.faves.indexOf(c.id) > -1)
         }
         return caps
      },

      allCapturesSet(){
        return new Set(this.captures)
      },

      monthFilteredSet(){
        if (this.filterMonth){
          return new Set(this.captures.filter(c => c.month == this.filterMonth))
        } else {
          return this.allCapturesSet;
        }
      },

      camFilteredSet(){
        if (this.filterCam){
          return new Set(this.captures.filter(c => c.camLabel == this.filterCam))
        } else {
          return this.allCapturesSet;
        }
      },

      speciesFilteredSet(){
        if (this.filterSpecies) {
            let matchingTag = this.tags.find(t => t.routeTag == this.filterSpecies)
            if (!matchingTag) {
              console.log(" no matching tag ")
              console.log(this.filterSpecies)
              return this.allCapturesSet
            };
            return new Set(this.captures.filter(c => c.tags.indexOf(matchingTag.tag) > -1))
        } else {
            return this.allCapturesSet;
        }
      },

      filteredCaptures(){
        let allFilterSet = this.monthFilteredSet.intersection(this.camFilteredSet.intersection(this.speciesFilteredSet))
        return [...allFilterSet]
      },


      viewPage(){
        let p = this.filteredCaptures.slice(0,this.viewItems);
        return p
      },

      camData(){
        // use sets to find the intersection of two other filters
        let camHistoData = this.speciesFilteredSet.intersection(this.monthFilteredSet)
        let camHistoArray = [...camHistoData]
        return camHistoArray;
      },

      monthContextHisto(){
        let monthHistoSet = this.speciesFilteredSet.intersection(this.camFilteredSet)
        return [...monthHistoSet]
      },

      webShareApiSupported() {
        return navigator.share
      },


      matchingSharedCapture(){
        if (!this.sharedCapture) return;
        let m = this.captures.find(c => {
          let cn = c.filename.split(".")[0]
          return (cn == this.sharedCapture)
        })
        return m
      }
    },

    watch:{
      faves: {
        handler(newvalue,oldvalue){
          localStorage.setItem('MosaicFaves', JSON.stringify(newvalue));
          // console.log("stored faves")
          this.$emit('updateFaves', newvalue)
        },
        deep:true
      }
    }
  }

</script>

<template>
  
  <div v-if="!showFaves">

    <div class="controlWrapper">
      <CaptureHisto :capture-data="captures" :context-captures="monthContextHisto" :tag-map="tagMap" :filter-state="filterState" @set-filter="setFilter"></CaptureHisto>
      <CamMap :cam-data="camData" :filter-state="filterState" @set-filter="setFilter"></CamMap>
    </div>

   <div class="prompt">
        <p>Filter by month, camera and species to browse over 1700 images and videos.</p> 
        <p>Use <img class="favesbutton" src="@/assets/img/fave-heart.svg"/> to save your favourites.</p>
    </div>

    <div class="headerTags">
      <span class="itemTag big label">Species:</span>
      <span v-for="t in allTags" class="itemTag big" :class="{'active': filterSpecies == t.routeTag, 'mammal': t.group == 'mammal', 'bird': t.group != 'mammal', 'zero':t.count == 0}" @click="setFilter('species',t.routeTag)">{{t.tag}} 
            <span v-if="t.count > 0">({{t.count}})</span>
        </span>
    </div>

    <SpeciesInfo v-if="focusedSpecies" :species-name="focusedSpecies.scientificName" context="captures"/>

  </div>

  <div class="favesHeader" v-if="showFaves">
    <h2>Favourites</h2>
    <img src="@/assets/img/mosaic-graphic.svg" class="section-graphic">
       <div class="prompt">
          <p>Favourite captures are stored on this device. Build your collection and use <img class="sharebutton"src="@/assets/img/share-button.svg"/> to share your finds.</p>
        </div>

  </div>


  <div class="captures">
    <CaptureItem v-for="c in viewPage" :key="c.path" :capture="c" :base-url="baseUrl" @set-filter="setFilter"  @click-fave="toggleFave" :share="webShareApiSupported" :faved="faves.indexOf(c.id) > -1">

    </CaptureItem>
    <div class="loadMore" v-if="filteredCaptures.length > viewItems" >
      <a @click="loadMore()"> + LOAD MORE</a>
    </div>
  </div>

  <div class="sharedModal" v-if="sharedCapture">
    <div class="inner">
    
        <CaptureItem :capture="matchingSharedCapture" :base-url="baseUrl" :share="webShareApiSupported" modal="true" @close-modal="closeSharedModal" @set-filter="setFilter">

        </CaptureItem>

          <div class="shareIntro">
            <p>This is just part of the picture. Explore:</p>
            <router-link to="/"><img src="@/assets/img/logo-lockup.svg"/></router-link>
          </div>
    </div>
    
  </div>
</template>

<style>

  p, span{
    font-family: Inconsolata, sans-serif;
  }

  .prompt{
    
    background-color: white;
    padding: 0.75rem 1rem 0.5rem;
    margin: 0 auto 0.5rem;
    width: fit-content;
  }

  .prompt p{
    font-family: Lato, sans-serif;
    margin:0 0 0.25rem;
    font-size: 0.9rem;
    text-align: center;
    color:#222;
  }
  
  .captures, .headerTags{
    display: flex;
    flex-wrap: wrap;
    flex-direction: row;
    margin:0 auto;
    width:95%;
    max-width: 1800px;
    justify-content: center;
  }

  .headerTags{
    margin-bottom: 1rem;
  }

  .headerTags span.label{
    font-weight: 600;
/*    font-size: 1.2rem;*/
    background: none;
    pointer-events: none;
   
/*    height:2rem;*/
/*    display: inline-block;*/
  }

  .captures{
    column-gap: 2rem;
    padding-bottom: 240px;
  }

  .itemTag{
    font-size: 80%;
    display: inline-block;
    margin:0.25rem 0.25rem 0 0;
    background-color: #ddd;
    padding:0.25rem 0.5rem;
    cursor: pointer;
    border-radius:0.75em;
    border:1px solid transparent;
  }

  .itemTag:not(.zero):hover{
    border:1px solid black;
  }

  .controlWrapper{
    display: flex;
    flex-direction: row;
    flex-wrap: no-wrap;
    justify-content: center;
    margin: 1rem 0;
    gap:1rem;
    align-items: center;
  }

  .big{
    font-size: 105%;
  }

  .itemTag.bird{
    background-color: #8fcece;
  }

  .itemTag.mammal{
    background-color: #e0b2a3;
  }

  .itemTag.zero{
 background-color: #aaa;
    opacity:0.5;
    cursor: default;
  }

  .itemTag.active{
    background-color: #222;
    color:white;

  }


  .loadMore{
/*    min-width: 100px;
    max-width:180px;*/
/*    padding:1rem;*/
/*    background-color: white;*/
    text-align: center;
    margin:0.5rem;
    flex-basis: 100%;
  }

  .loadMore a{
    display: inline-block;
    padding:0.5rem 1rem;
    background-color: white;
    cursor:pointer;
    border-radius: 1rem;
  }

  .loadMore a:hover{
    background-color: #222;
    color:white;
  }

  .sharedModal{
    position:fixed;
    top:0;
    left:0;
    width:100%;
    height:100%;
    background-color: rgba(0,0,0,0.8);
    z-index: 100;
  }

 .sharedModal .inner{
    margin:5vh auto ;

    height:90vh;
    width: 90vw;
/*    width:90%;*/
    display: flex;
    justify-content: center; 
    align-items: center; 
    flex-direction: column;
    flex-wrap: nowrap ;
  }

  .shareIntro{
    flex:1;
    background: rgba(226, 227, 216, 1);
    padding:0.5rem 3rem 1rem;
    text-align: center;
    border-radius: 1.5rem;
    margin-top:1rem;
    flex-grow: 0;

  }

  .shareIntro img{
    width:13rem;
  }

  .shareIntro p{
    font-family: Lato, sans-serif;
    margin:0.5rem 0;
  }

  .favesHeader{
    margin-bottom:2rem;
  }
  .favesHeader h2{
    font-size: 3rem;
    margin:2rem auto 1rem;
    text-align: center;
  }

  .sharebutton, .favesbutton{
    display: inline-block;
    width:1.0rem;
    opacity:0.25;
    margin:0 0.1rem;
    vertical-align: bottom;
  }

  .favesbutton{
    width:1.25rem;
/*    padding-top:0.25rem;*/
/*    position:relative;*/
/*    top:0.25rem;*/
    margin: 0 0.05rem;
/*    height: 1rem;*/
/*    overflow-y: visible;*/
  }

  .section-graphic{
    width:8rem;
    margin:1rem auto;
    display: block;
  }





    @media screen and (width < 720px) {

      .headerTags{
        font-size:90%;
      }

/*      .prompt{
        flex-basis:100%;
        max-width:unset;
        margin:0 auto;
      }*/

    }



</style>
