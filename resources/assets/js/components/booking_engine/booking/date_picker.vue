<template>
    <div class="d-flex flex-column">
        <div class="d-inline-flex align-items-center p-2">
                <label for="date" class="col-md-2 control-label">Date</label>
                <div class="col-md-6">
                        <datepicker
                        v-model="date"
                        name="date"
                        :format="'yyyy-MM-dd'"
                        :disabledDates="datepicker_state.disabledDates"
                        :clear-button-icon="'fa fa-calendar-alt'"
                        :calendar-button="true"
                        :calendar-button-icon="'fa fa-calendar-alt'"
                        :bootstrap-styling="true">
                        </datepicker>
                </div>
        </div>
        <div class="d-inline-flex align-items-center p-2" v-if="date">
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
import Datepicker from 'vuejs-datepicker';

    export default {
        components: {
            Datepicker
        },
        data() {
            let self =this;
            return {
                date: null,
                availability: [],
                dates_regular: [],
                dates_interpreter : [],
                is_interpreter:false,
                times:[],
                hour:null,
                datepicker_state: {
                    disabledDates: {
                        days: [6, 0], // Disable Saturday's and Sunday's
                        customPredictor: function (date) {
                            return self.getAvailability(date);
                        },
                    }
                },
            }
        },
        methods: {
            getAvailability (date) {
                var self = this;
                let date_formated = moment(date).format('YYYY-MM-DD');
                if( !self.is_interpreter ) {
                    return  !self.dates_regular.includes(date_formated) ? true:false;
                } else if ( self.is_interpreter ) {
                    return  !self.dates_interpreter.includes(date_formated) ? true:false;
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
                        if(moment(date).format('YYYY-MM-DD') === day[0]) {
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