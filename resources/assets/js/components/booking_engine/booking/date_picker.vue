<template>
    <div>
        <div class="form-group ">
                <label for="date" class="col-md-2 control-label">Date</label>
                <div class="col-md-6">
                    <div class="col-sm-6 col-md-4">
                        <dropdown class="form-group">
                            <div class="input-group">
                                <input class="form-control" id="booking_date" type="text" v-model="date" name="booking_date" >
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
        </div>
        <div class="form-group " v-if="date">
            <label for="date" class="col-md-2 control-label">Available Times</label>
            <div class="col-md-6">
                <label class="mt-radio mt-radio-outline" v-for= "time in times" :key="time.start_time" >
                    <input type="radio" name="serviceTime"
                    :value="time.start_time">
                    {{ time.text }}<span></span><br>
                </label>
            </div>
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
                is_interpreter:false,
                times:[],
            }
        },
        methods: {
            getAvailability (date) {
                var self = this;
                let month = (date.getMonth() +1)< 10 ? "0" + (date.getMonth() +1).toString(): (date.getMonth() +1).toString();
                let day =  date.getDate()< 10 ? "0" + date.getDate().toString(): date.getDate().toString();
                let date_formated = date.getFullYear().toString() +"-"+
                                    month+"-"+
                                    day;
                if(!self.is_interpreter && self.dates_regular.length > 0) {
                    return  !self.dates_regular.includes(date_formated) ? 'disabled':'';
                }
                else if (self.is_interpreter && self.dates_interpreter.length > 0) {
                    return  !self.dates_interpreter.includes(date_formated) ? 'disabled':'';
                }

            },

        },
        watch:{
            date: function() {
                let date = this.date;
                let times = [];
                let days = [];
                if(!this.is_interpreter && this.dates_regular.length > 0) {
                    days = Object.entries(this.availability.regular);
                }
                if(this.is_interpreter && this.dates_interpreter.length > 0) {
                    days = Object.entries(this.availability.interpreter);
                }
                if(days.length > 0) {
                    days.forEach(function(day) {
                        if(date === day[0]) {
                            times = Object.values(day[1]);
                        }
                    });
                }
                this.times = times;

            }
        }
    }
</script>