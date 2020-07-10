<template>
  <div>
    <h1 ref="myref">Hook tutorial</h1>
    <p>My name is: {{name}}</p>
  </div>
</template>
<script>
export default {
  data() {
    return {
      name: "hooks.name"
    };
  },
  // 이름 그대로 before create, 많이는 안씀?
  beforeCreate() {
    //eg)라우팅 등 특별한 작업용
  },
  // created -> vue 파일이 build 된 후 실행할 작업. HTML 렌더링 전에 실행됨
  created() {
    // 초기 데이터
    // API 호출 등
    // 가장 많이씀

    console.log(
      "data and other functions are avaliable, but not html",
      this.name
    );
  },
  // mounted : 파일접근, HTML 접근, HTML 렌더링 후 실행됨
  mounted() {
      console.log(this.$refs.myref);

  }
};
</script>