<template>
    <div>
        <div class="col-sm-6 col-md-4">
            <dropdown class="form-group">
                <div class="input-group">
                    <input class="form-control" type="text" v-model="date" name="booking_date">
                    <div class="input-group-btn">
                    <btn class="dropdown-toggle"><i class="glyphicon glyphicon-calendar"></i></btn>
                    </div>
                </div>
                <template slot="dropdown">
                    <li>
                    <date-picker v-model="date"
                                :width="200"
                                :today-btn="false"
                                :clear-btn="false"
                                :limit-from="limit_from"
                                :date-class="getAvailability"/>
                    </li>
                </template>
            </dropdown>
        </div>

    </div>
</template>

<script>
    export default {
        data() {
            return {
                date: null,
                limit_from: new Date().toISOString().split('T')[0],
                availability: [],
                dates_regular: [],
                dates_interpreter : [],
            }
        },
        methods: {
            init() {
                var self = this;
            },
            getAvailability (date) {
                var self = this;
                let month = (date.getMonth() +1)< 10 ? "0" + (date.getMonth() +1).toString(): (date.getMonth() +1).toString();
                let day =  date.getDate()< 10 ? "0" + date.getDate().toString(): date.getDate().toString();
                let date_formated = date.getFullYear().toString() +"-"+
                                    month+"-"+
                                    day;
                if(self.dates_regular.length > 0) {
                    return  !self.dates_regular.includes(date_formated) ? 'disabled':'';
                }

            },

        },
        mounted() {
            this.init();
        },

    }
</script>