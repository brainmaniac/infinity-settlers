<template>
  <div>
    <v-stage
      ref="stage"
      :config="configKonva"
      @dragstart="handleDragstart"
      @dragend="handleDragend"
    >
      <v-layer v-if="image" ref="layer">
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
            numPoints: 5,
            radius: 100,
            outerRadius: 100,
            opacity: 0.8,
            fillPatternImage: image,
            fillPatternRepeat: 'no-repeat',
            fillPatternOffset: {
                x: 512,
                y: 512,
            },
            fillPatternScale: {
                x: 0.2,
                y: 0.2,
            },
            draggable: true,
            scaleX: dragItemId === item.id ? item.scale * 1.2 : item.scale,
            scaleY: dragItemId === item.id ? item.scale * 1.2 : item.scale,
            shadowColor: 'black',
            shadowBlur: 10,
            shadowOffsetX: dragItemId === item.id ? 15 : 5,
            shadowOffsetY: dragItemId === item.id ? 15 : 5,
            shadowOpacity: 0.6
          }"
        ></v-regular-polygon>
      </v-layer>
    </v-stage>
  </div>
</template>

<script>
import {Howl, Howler} from 'howler';

const width = window.innerWidth;
const height = window.innerHeight;

export default {
  data() {
    return {
      image: false,
      list: [],
      dragItemId: null,
      configKonva: {
        width: width,
        height: height,       
      }
    };
  },
  methods: {
    handleDragstart(e) {
      // save drag element:
      this.dragItemId = e.target.id();
      // move current element to the top:
      const item = this.list.find(i => i.id === this.dragItemId);
      const index = this.list.indexOf(item);
      this.list.splice(index, 1);
      this.list.push(item);
    },
    handleDragend(e) {
        this.dragItemId = e.target.id();
        // move current element to the top:
        let item = this.list.find(i => i.id === this.dragItemId);
        const index = this.list.indexOf(item);
        this.dragItemId = null;        

        var sound = new Howl({
            src: ['sounds/punch.mp3']
        });

        sound.play();

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

        var sound = new Howl({
            src: ['sounds/rotate.mp3']
        });

        sound.play();
    },

    // See https://www.redblobgames.com/grids/hexagons/#coordinates
    positions() {
        let positions = [];
        for(let r = 0; r*50<width; r++) {
            for(let q = 0; q*50<height; q++) {
                positions.push(
                    {
                        q: q,
                        r: r,
                        x: q * 86.6 + 0.5 * r * 86.6,
                        y: r * 100 * 3/4
                    }
                )
            }            
        }

        return positions
    }
  },

  created() {
    const image = new window.Image();
    image.src = "/images/terrain.png";
    image.onload = () => {
      this.image = image;
    };
  },

  mounted() {
    for (let n = 0; n < 100; n++) {
      this.list.push({
        id: Math.round(Math.random() * 1000000).toString(),
        x: Math.random() * width/2,
        y: Math.random() * height/3,
        rotation: 120 * Math.floor(Math.random() * 5),
        scale: 0.5
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