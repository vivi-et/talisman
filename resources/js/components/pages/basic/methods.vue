<template>
  <div>
    <h1>the number is : {{ views }}</h1>
    <button @click="updateCounter(1)">Increase</button>
    <button @click="updateCounter(-1)">Decrease</button>
    <br />
    <br />
    <br />

    <div v-if="blogs.length">
      <div v-for="(blog, i) in blogs" :key="i">
        <h1>{{ blog.title }}</h1>
        <p>{{ blog.post }}</p>
      </div>

      <div>
        <h1 v-if="views===100">show this item if "showItem" is 100</h1>
        <h1 v-if="views===99">show this item if "showItem" is 99</h1>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  data() {
    return {
      views: 0,
      blogs: [],
      showItem: false
    };
  },
  methods: {
    updateCounter(number) {
      this.views += number;
    }
  },
  created() {
    // call the server to get views of this blog post..
    this.views = 100;
    //get the blog post from server
    let posts = [
      {
        id: 1,
        title: "this is the blog title 1",
        post: "this is the blog post 1"
      },
      {
        id: 2,
        title: "this is the blog title 2",
        post: "this is the blog post 2"
      },
      {
        id: 3,
        title: "this is the blog title 3",
        post: "this is the blog post 3"
      },
      {
        id: 4,
        title: "this is the blog title 4",
        post: "this is the blog post 4"
      }
    ];
    this.blogs = posts;
  }
};
</script>

<style>
</style>