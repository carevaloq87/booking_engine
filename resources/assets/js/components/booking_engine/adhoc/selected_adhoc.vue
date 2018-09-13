<template>
    <div>

    </div>
</template>
<script>
    export default {
        props:['service'],
        data() {
            return {
                adhocs: {}
            }
        },
        methods: {
            getAdhocs() {

                $("#contentLoading").modal("show");
                var self = this;
                let url = '/calendar/service/adhoc/' + self.service;

                axios['get'](url, {})
                    .then(response => {
                        self.adhocs = response.data;
                        $("#contentLoading").modal("hide");
                    })
                    .catch(error => {
                        console.log(error);
                        $("#contentLoading").modal("hide");
                    });
            }
        },
        mounted() {
            this.getAdhocs();
        }
    }
</script>