<template>
    <div>
    </div>
</template>

<script>
    import Vue from 'vue';
    import EventBus from '../../utils/event-bus';

    export default {
        data: function() {
            return {
                requests: []
            }
        },
        methods: {
            removeValue(arr) { //Source of this function is Stackoverflow
                var what, a = arguments, L = a.length, ax;
                while (L > 1 && arr.length) {
                    what = a[--L];
                    while ((ax= arr.indexOf(what)) !== -1) {
                        arr.splice(ax, 1);
                    }
                }
                return arr;
            }
        },
        watch: {
            requests: function () {
                //Delay execution
                setTimeout(() => {
                    if(this.requests.length < 1){
                        $("#contentLoading").modal('hide');
                    }
                }, 2500);
            }
        },
        mounted () {
            let self = this;
            EventBus.$on('SHOW_LOADER', function (payLoad) {
                self.requests.push(payLoad);
                    setTimeout(() => {
                        $("#contentLoading").modal('show');
                    }, 500);
            });
            EventBus.$on('HIDE_LOADER', function (payLoad) {
                self.removeValue(self.requests,payLoad);
            });
        }
    };
</script>