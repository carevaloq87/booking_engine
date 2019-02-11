<template>

    <div :class="tableClass">
        <div class="form-group">
            <span>Change time display: <small>(in minutes)</small></span>
            <button type="button" :class="'btn btn-sm default ' + isActiveTime('quarter_hour')" id="quarter_hour" v-on:click="setTimeStructure('quarter_hour')">15</button>
            <button type="button" :class="'btn btn-sm default ' + isActiveTime('half_hour')" id="half_hour" v-on:click="setTimeStructure('half_hour')">30</button>
            <button type="button" :class="'btn btn-sm default ' + isActiveTime('hour')" id="hour" v-on:click="setTimeStructure('hour')">60</button>
        </div>
        <div class="form-group margin-top-10 hours_selection">
            <div class="col-sm-12">
                <div class="row top_dates noSelect">
                    <div class="day_col">&nbsp;</div>
                    <div class="hours_col">00:00</div>
                    <div class="hours_col">01:00</div>
                    <div class="hours_col">02:00</div>
                    <div class="hours_col">03:00</div>
                    <div class="hours_col">04:00</div>
                    <div class="hours_col">05:00</div>
                    <div class="hours_col">06:00</div>
                    <div class="hours_col">07:00</div>
                    <div class="hours_col">08:00</div>
                    <div class="hours_col">09:00</div>
                    <div class="hours_col">10:00</div>
                    <div class="hours_col">11:00</div>
                    <div class="hours_col">12:00</div>
                    <div class="hours_col">13:00</div>
                    <div class="hours_col">14:00</div>
                    <div class="hours_col">15:00</div>
                    <div class="hours_col">16:00</div>
                    <div class="hours_col">17:00</div>
                    <div class="hours_col">18:00</div>
                    <div class="hours_col">19:00</div>
                    <div class="hours_col">20:00</div>
                    <div class="hours_col">21:00</div>
                    <div class="hours_col">22:00</div>
                    <div class="hours_col">23:00</div>
                </div>
                <div v-for="(day, id) in week_days" :key="id">
                    <div class="row week_days">
                        <div class="day_col noSelect" v-text="day"></div>
                        <div class="hours_col" v-for="(n,i) in 24" :key="i">
                            <div v-html="drawHours(day,n)"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row mt-3 pl-3" v-if="copyField">
            <label class="m-checkbox m-checkbox--solid m-checkbox--single m-checkbox--brand mr-3">
                    <input type="checkbox" v-model="copy_hours" v-on:click="confirmCopy"><span></span>
            </label>
            <span>Copy regular hours for interpreter appointments <i class="fa fa-info-circle" data-skin="dark" data-container="body" data-toggle="m-tooltip" data-placement="right" title="" data-original-title="This will override current interpreter selections"></i></span>
        </div>
    </div>

</template>


<script>
    import EventBus from '../../../utils/event-bus';
    export default {
        props: {
            currentSchedule: Object,
            tableClass: String,
            copyField: Boolean
        },
        data() {
            return {
                choice: 'hour',
                copy_hours: false,
                week_days: ['Mon','Tue','Wed','Thu','Fri','Sat','Sun'],
                time_structure_active: 'hour',
                time_structure: {
                    hour: {
                        lenght: 60,
                        columns: 24,
                        el_in_col: 1
                    },
                    half_hour: {
                        lenght: 30,
                        columns: 48,
                        el_in_col: 2
                    },
                    quarter_hour: {
                        lenght: 15,
                        columns: 96,
                        el_in_col: 4
                    }
                }
            }
        },
        methods: {
            //Make tab/container visible or hidden
            makeActive(val) {
                this.choice = val;
            },
            //Check if a tab/container should be visible
            isActiveTime(val) {
                if(this.choice === val) {
                    return 'active';
                }
                return '';
            },
            setTimeStructure(time_structure_name) {
                let self = this;
                let prev_time_structure_name = self.time_structure_active;
                self.time_structure_active = time_structure_name; //i.e Hour, Half hour or quarter hour
                let regular_selection = self.getSelections("#regular .ds-button.ds-selected", time_structure_name, prev_time_structure_name);
                let interpreter_selection = self.getSelections("#interpreter .ds-button.ds-selected", time_structure_name, prev_time_structure_name);
                let selected_options = {
                    regular:  regular_selection,
                    interpreter:  interpreter_selection
                };
                setTimeout(function(){
                    self.$emit('reload-ds', selected_options); //This emits a message to the parent component in order to re-initialize drag all drag and select buttons
                    self.hideLoader();
                }, 1000);
                self.makeActive(time_structure_name);
            },
            getSelections(context, time_structure_name, prev_time_structure_name) {
                let self = this;
                let selection = [];
                if( document.querySelector(context) ) {
                    let ini_selection = self.$parent.ds_regular.getSelectedValuesByContext(context);
                    selection = self.getComplementarySelections(ini_selection, time_structure_name, prev_time_structure_name);
                }
                return selection;
            },
            getComplementarySelections(selections, time_structure_name, prev_time_structure_name){
                let self = this;
                let sel_length = parseInt(self.time_structure[time_structure_name].lenght);
                let prev_sel_length = parseInt(self.time_structure[prev_time_structure_name].lenght);
                let complementary_sel = [];
                const hour = self.time_structure.hour.lenght;
                const half_hour = self.time_structure.half_hour.lenght;
                const quarter_hour = self.time_structure.quarter_hour.lenght;

                for (let index = 0; index < selections.length; index++) {
                    let selection_data = selections[index].split('-');
                    let minutes = parseInt(selection_data[1]);
                    const prefix = `${selection_data[0]}-`;

                    const minute_module = minutes % hour;
                    const initial_minute = minutes - minute_module;
                    let min_to_add = initial_minute;

                    if(((prev_sel_length == half_hour) || (prev_sel_length == quarter_hour))
                        && sel_length == hour){
                        self.addToArray(prefix, complementary_sel, initial_minute);
                    } else if(prev_sel_length == quarter_hour && sel_length == half_hour){
                        if(minute_module >= half_hour) {
                            self.addToArray(prefix, complementary_sel, initial_minute + half_hour);
                        } else {
                            self.addToArray(prefix, complementary_sel, initial_minute);
                        }
                    } else if(prev_sel_length == half_hour && sel_length == quarter_hour){
                        if(minute_module >= half_hour) {
                            self.addToArray(prefix, complementary_sel, initial_minute + half_hour);
                            self.addToArray(prefix, complementary_sel, initial_minute + half_hour + quarter_hour);
                        } else {
                            self.addToArray(prefix, complementary_sel, initial_minute);
                            self.addToArray(prefix, complementary_sel, initial_minute + quarter_hour);
                        }
                    } else if(prev_sel_length == hour){
                        if(sel_length == quarter_hour){
                            self.addToArray(prefix, complementary_sel, min_to_add);
                            self.addToArray(prefix, complementary_sel, initial_minute + quarter_hour);
                            self.addToArray(prefix, complementary_sel, initial_minute + half_hour);
                            self.addToArray(prefix, complementary_sel, initial_minute + half_hour + quarter_hour);
                        } else if(sel_length == half_hour) {
                            self.addToArray(prefix, complementary_sel, min_to_add);
                            self.addToArray(prefix, complementary_sel, initial_minute + half_hour);
                        }
                    }
                }
                return complementary_sel;
            },
            addToArray(prefix, the_array, value){
                if(the_array.indexOf(value) < 0){ //Add only if is not in the array
                    the_array.push(`${prefix}${value}`);
                }
                return the_array;
            },
            drawHours(week_day, hour) {
                let self = this;
                let current_time_structure = self.time_structure[self.time_structure_active];
                let standard_container = '';

                let initial_minute =  current_time_structure.el_in_col * (hour-1);

                for (let index = 0; index < current_time_structure.el_in_col; index++) {
                    let element_id = (initial_minute + index) * current_time_structure.lenght;
                    let btn = '<button type="button" class="btn ds-button ' + self.time_structure_active + '" id="' + week_day + '-' + element_id + '">&nbsp;</button>';
                    standard_container += btn;
                }
                return standard_container;
            },
            hideNonWorkingHours: function() {
                $(".week_days .hours_col:nth-of-type(-n+8), .top_dates .hours_col:nth-of-type(-n+8)").hide();
                $(".week_days .hours_col:nth-last-child(-n+6), .top_dates .hours_col:nth-last-child(-n+6)").hide();
            },
            showLoader() {
                EventBus.$emit('SHOW_LOADER', 'hours_container');
            },
            hideLoader() {
                EventBus.$emit('HIDE_LOADER', 'hours_container');
            },
            confirmCopy() {
                var self = this;
                if(!self.copy_hours && self.tableClass == 'current') {
                    self.$swal({
                                    title: 'Are you sure?',
                                    text: "You won't be able to revert this!",
                                    type: 'warning',
                                    showCancelButton: true,
                                    confirmButtonColor: '#17c4bb',
                                    cancelButtonColor: '#e2e5ec',
                                    confirmButtonText: 'Yes, copy them!',
                                    allowEscapeKey : false,
                                    allowOutsideClick: false
                                }).then((result) => {
                                    if (result.value) {
                                        self.copyHours();
                                        self.$swal(
                                                    'Copied!',
                                                    'Hours has been copied.',
                                                    'success'
                                                );
                                    } else {
                                        self.copy_hours = false;
                                    }
                                });
                }

            },
            copyHours() {
                var self = this;
                let preselection_current = self.$parent.ds_regular.getSelectedValuesByContext('#regular .ds-selected');
                document.querySelector('#interpreter #' + self.choice).click();
                setTimeout(() => {
                    self.$parent.ds_interpreter.clear();
                    self.$parent.ds_interpreter.setInitialSelections(self.$parent.interpreter_selector, preselection_current);
                }, 1000);
            },
            eventFetchHours() {
                let self = this;
                EventBus.$on('FETCH_HOURS', function () {
                    self.copy_hours = false;
                });
            }
        },
        watch: {
            currentSchedule: function () {
                this.time_structure_active = this.currentSchedule.time_name;
                this.choice = this.currentSchedule.time_name;
            }
        },
        mounted() {
            this.hideNonWorkingHours();
            this.eventFetchHours();
        },
    }
</script>