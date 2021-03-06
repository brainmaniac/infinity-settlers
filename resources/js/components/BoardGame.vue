<template>
  <div>
    <v-stage
      ref="stage"
      :config="configKonva"
      @dragstart="handleDragstart"
      @dragend="handleDragend"
    >
      <v-layer :v-if="imagesReady()" ref="layer">

        <v-text 
            :config="{
                x: 600,
                y: 100,
                text: 'VINLAND VIKINGS',
                fontSize: 20,
                fontFamily: 'Calibri',
                fill: 'white'
            }"
        />

        <v-rect 
            :config="{
                width: width,
                height: height,
                zIndex: -1,
                fillPatternImage: background,
                fillPatternRepeat: 'no-repeat',
                fillPatternScale: {
                    x: 0.3,
                    y: 0.3
                }
            }"
        />


        <v-rect 
            :config="{
                width: 100,
                height: 100,
                x: 200,
                y: 500,                
                fillPatternImage: dices,
                fillPatternRepeat: 'no-repeat',
                fillPatternScale: {
                    x: 0.2,
                    y: 0.2
                },
                draggable: true,
            }"
        />        

        <v-regular-polygon
          v-for="item in list"
          @click="rotate"
          :key="item.id"
          :config="{  
            x: item.x,
            y: item.y,
            sides: 6,
            rotation: item.rotation,
            id: item.id,
            radius: hexagonRadius,
            opacity: 0.8,
            //fill: 'green',
            fillPatternImage: images[item.image],
            fillPatternRepeat: 'no-repeat',
            fillPatternOffset: {
                x: image.width/2,
                y: image.height/2,
            },
            fillPatternScale: {
                x: hexagonRadius/image.height,
                y: hexagonRadius/image.height,
            },
            draggable: true,
            scaleX: dragItemId === item.id ? item.scale * 1.2 : item.scale,
            scaleY: dragItemId === item.id ? item.scale * 1.2 : item.scale,
            shadowColor: 'black',
            shadowBlur: 10,
            shadowOffsetX: dragItemId === item.id ? 5 : 2,
            shadowOffsetY: dragItemId === item.id ? 5 : 2,
            shadowOpacity: 0.6
          }"
        ></v-regular-polygon>

        <v-rect
          v-for="item in resource_list"
          :key="item.id"
          :config="{
                width: 100,
                height: 400,
                x: item.x,
                y: item.y,
                rotation: item.rotation,                
                fillPatternImage: resources[item.image],
                fillPatternRepeat: 'no-repeat',
                fillPatternScale: {
                    x: 0.5,
                    y: 0.5
                },
                draggable: true,
            }"
        ></v-rect>        
      </v-layer>
    </v-stage>
  </div>
</template>

<script>
import sounds from '../sounds';

const width = window.innerWidth;
const height = window.innerHeight;

export default {
  data() {
    return {
      width: window.innerWidth,
      height: window.innerHeight,
      image: false,
      background: false,
      dices: false,
      images: {},
      resources: {},
      hexagonRadius: 100,
      list: [],
      resource_list: [],
      dragItemId: null,
      configKonva: {
        width: width,
        height: height,       
      }
    };
  },
  methods: {
    imagesReady() {
        return Object.keys(this.images).length == this.image_names().length
    },

    handleDragstart(e) {
      // save drag element:
      this.dragItemId = e.target.id();

      // move current element to the top:
      const item = this.list.find(i => i.id === this.dragItemId);
      const index = this.list.indexOf(item);
      item ? this.list.splice(index, 1) && this.list.push(item) : null;
    },
    handleDragend(e) {
        this.dragItemId = e.target.id();
        // move current element to the top:
        let item = this.list.find(i => i.id === this.dragItemId);
        const index = this.list.indexOf(item);
        this.dragItemId = null;        

        sounds.place.play();

        // Snap to hex grid!
        let closest = this.positions().map(point => {
            return {
                ...point,
                distance: Math.abs(point.x - e.evt.x) + Math.abs(point.y - e.evt.y)
            }
        }).reduce(function(prev, curr) {
            return prev.distance < curr.distance ? prev : curr;
        });

        e.target.attrs.x = closest.x;
        e.target.attrs.y = closest.y;      
    },
    rotate(e) {
        let clickItemId = e.target.id();
        const item = this.list.find(i => i.id === clickItemId);
        const index = this.list.indexOf(item);
        this.list.splice(index, 1);
        this.list.push(item);

        item.rotation += 60;

        sounds.rotate.play();
    },

    // See https://www.redblobgames.com/grids/hexagons/#coordinates
        positions() {
            let positions = [];
            for(let r = 0; r*this.hexagonRadius/2<width; r++) {
                for(let q = 0; q*this.hexagonRadius/2<height; q++) {
                    positions.push(
                        {
                            q: q,
                            r: r,
                            x: q * this.hexagonRadius * 0.866 + 0.5 * r * this.hexagonRadius * 0.866,
                            y: r * this.hexagonRadius * 3/4
                        }
                    )
                }            
            }

            return positions
        },

        image_names() {
            return [
                //...Array(10).fill("0_1.png"),
                "0_1.png",
                "0_2.png",
                "1_1.png",
                "1_2.png",
                "1_3.png",
                "2_1.png",
                "2_2.png",
                "3_1.png",
                "3_2.png",
                "4_1.png",
                "4_2.png",
                "5_1.png",
                "5_2.png",
                "5_3.png",
                "6_1.png",
                "6_2.png",          
            ]
        },        
    },



    created() {
        const background = new window.Image();
        background.src = "/images/background.jpg";
        background.onload = () => {
            this.background = background
        };
        
        const dices = new window.Image();
        dices.src = "/images/dices.png";
        dices.onload = () => {
            this.dices = dices
        };         

        for(const name of this.image_names()) {
            const image = new window.Image();
            image.src = "/images/" + name;
            image.onload = () => {
                this.image = image
                this.images[name] = image;
            };
        }

        for(const name of ['wood.jpg', 'stone.jpg', 'clay.jpg', 'wheat.jpg', 'sheep.jpg' ]) {
            const resource = new window.Image();
            resource.src = "/images/" + name;
            resource.onload = () => {
                this.resources[name] = resource;
            };
        }        
    },

  mounted() {
    for (let n = 0; n < 60; n++) {
      this.list.push({
        id: Math.round(Math.random() * 1000000).toString(),
        image: this.image_names()[Math.floor(Math.random() * this.image_names().length)],
        x: Math.random() * width/2,
        y: Math.random() * height/2,
        rotation: 0, //120 * Math.floor(Math.random() * 5),
        scale: 1
      });
    }

    for (let n = 0; n < 20; n++) {
      this.resource_list.push({
        id: Math.round(Math.random() * 1000000).toString(),
        image: ['wood.jpg', 'stone.jpg', 'clay.jpg', 'wheat.jpg', 'sheep.jpg' ][Math.floor(Math.random() * 5)],
        x: 500 + Math.random() * 100,
        y: 500 + Math.random() * 10,
        rotation: 10 * Math.floor(Math.random() * 5) - 10 * Math.floor(Math.random() * 5),
        scale: 1
      });
    }    
  }
};
</script>

<style>
body {
  margin: 0;
  padding: 0;
}
</style>