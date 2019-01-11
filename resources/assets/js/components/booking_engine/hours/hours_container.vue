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
            makeActive: function(val) {
                this.choice = val;
            },
            //Check if a tab/container should be visible
            isActiveTime: function(val) {
                if(this.choice === val) {
                    return 'active';
                }
                return '';
            },
            setTimeStructure(time_structure_name) {
                let self = this;
                self.time_structure_active = time_structure_name; //i.e Hour, Half hour or quarter hour
                let regular_selection = (document.querySelector("#regular .ds-button.ds-selected") ? self.$parent.ds_regular.getSelectedValuesByContext("#regular .ds-button.ds-selected") : []);
                let interpreter_selection = (document.querySelector("#interpreter .ds-button.ds-selected") ? self.$parent.ds_regular.getSelectedValuesByContext("#interpreter .ds-button.ds-selected") : []);
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
                                    keydownListenerCapture: true
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