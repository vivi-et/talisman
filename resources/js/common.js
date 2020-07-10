// mixin에 사용할 코드들, app.js와 연결된 모든 곳에서 사용가능
export default {
    data() {
        return {

        }
    },
    methods: {
        async callApi(method, url, dataObj) {

            try {
                return await axios({
                    method: method,
                    url: url,
                    data: dataObj,
                });
            } catch (e) {
                return e.response;
            }

        },

        info(desc, title = "Hey") {
            this.$Notice.info({
                title: title,
                desc: desc
            });
        },
        success(desc, title = "Great") {
            this.$Notice.success({
                title: title,
                desc: desc
            });
        },
        warning(desc, title = "Oops!") {
            this.$Notice.warning({
                title: title,
                desc: desc
            });
        },
        error(desc, title = "Oops!") {
            this.$Notice.error({
                title: title,
                desc: desc
            });
        },
        swr(desc='Something went wrong', title = "Hey") {
            this.$Notice.error({
                title: title,
                desc: desc
            });
        },

    },
}
