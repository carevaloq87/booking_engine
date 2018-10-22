<template>
    <div>
        <div class="form-group ">
                <label for="date" class="col-md-2 control-label">Date</label>
                <div class="col-md-6">
                    <div class="col-sm-6 col-md-4">
                        <dropdown class="form-group">
                            <div class="input-group">
                                <input class="form-control" id="date" type="text" v-model="date" name="date" required>
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
                    <input type="radio" id='time' :value="time" v-model="hour" required>
                    <input type="hidden"  v-if="hour" name="resource_id" id="resource_id" :value="hour.resource_id">
                    <input type="hidden"  v-if="hour" name="time_length" id="time_length" :value="hour.duration">
                    <input type="hidden"  v-if="hour" name="start_hour" id="start_hour" :value="hour.start_time">
                    {{ time.text }}<span></span><br>
                </label>
            </div>
        </div>
    </div>
</template>

<script>
import moment from 'moment';
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
                hour:null,
            }
        },
        methods: {
            getAvailability (date) {
                var self = this;
                let date_formated = moment(date).format('YYYY-MM-DD');
                if(!self.is_interpreter && self.dates_regular.length > 0) {
                    return  !self.dates_regular.includes(date_formated) ? 'date_disabled':'date_enabled';
                }
                else if (self.is_interpreter && self.dates_interpreter.length > 0) {
                    return  !self.dates_interpreter.includes(date_formated) ? 'date_disabled':'date_enabled';
                }

            },

        },
        watch:{
            date: function() {
                let date = this.date;
                let times = [];
                this.times = [];
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
                            let time = Object.values(day[1]).slice(0);
                            time.forEach(function(hour){
                            if (Array.isArray(hour)) {
                                var first = function(element) { return !!element };
                                times.push(hour.find(first));
                            }
                            else {
                                let time_data = Object.values(hour);
                                times.push(time_data[0]);
                            }
                            });
                        }
                    });
                }
                this.times = times;
            }
        }
    }
</script>