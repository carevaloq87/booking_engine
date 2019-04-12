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
            removeValueFromArr(arr) { //Source of this function is Stackoverflow
                var what, a = arguments, L = a.length, ax;
                while (L > 1 && arr.length) {
                    what = a[--L];
                    while ((ax= arr.indexOf(what)) !== -1) {
                        arr.splice(ax, 1);
                    }
                }
                return arr;
            },
            addValue(value) {
                let self = this;
                return new Promise((resolve, reject) => {
                    self.requests.push(value);
                    resolve(self.requests.length);
                });
            },
            removeValue(value) {
                let self = this;
                return new Promise((resolve, reject) => {
                    self.removeValueFromArr(self.requests,value);
                    resolve(self.requests.length);
                });
            },
            checkIfEmpty(){
                if(this.requests.length < 1){
                    $("#contentLoading").modal('hide');
                }
                if(this.requests.length > 1) {
                    $("#contentLoading").modal('show');
                }
            }
        },
        watch: {
            requests: function () {
                this.checkIfEmpty();
            }
        },
        mounted () {
            let self = this;
            EventBus.$on('SHOW_LOADER', function (payLoad) {
                self.addValue(payLoad).then(() => {
                    self.checkIfEmpty();
                });
            });
            EventBus.$on('HIDE_LOADER', function (payLoad) {
                self.removeValue(payLoad)
                .then(() => {
                    self.checkIfEmpty();
                })
                .catch(err => console.error('Error: ', err));
            });
        }
    };
</script>