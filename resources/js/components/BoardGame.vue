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
          :key="item.id"
          :config="{
            x: item.x,
            y: item.y,
            sides: 6,
            rotation: item.rotation,
            id: item.id,
            numPoints: 5,
            radius: 32,
            outerRadius: 50,
            opacity: 0.8,
            fillPatternImage: image,
            fillPatternRepeat: 'no-repeat',
            fillPatternOffset: {
                x: 512,
                y: 512,
            },
            fillPatternScale: {
                x: 0.07,
                y: 0.07,
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
        //e.target.attrs.x=50;
        var sound = new Howl({
            src: ['sounds/punch.mp3']
        });

        sound.play();

      this.dragItemId = null;
    }
  },

  created() {
    const image = new window.Image();
    //image.src = "https://konvajs.org/assets/yoda.jpg";
    image.src = "http://infinity-settlers.test/images/terrain.png";
    image.onload = () => {
      // set image only when it is loaded
      this.image = image;
    };
  },

  mounted() {
    for (let n = 0; n < 60; n++) {
      this.list.push({
        id: Math.round(Math.random() * 1000000).toString(),
        x: Math.random() * width,
        y: Math.random() * height,
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