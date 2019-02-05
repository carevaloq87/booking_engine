<template>
    <div>
        <table :class="tableClass">
            <thead>
                <tr>
                    <th>&nbsp;</th>
                    <th>M</th>
                    <th>T</th>
                    <th>W</th>
                    <th>T</th>
                    <th>F</th>
                    <th>S</th>
                    <th>S</th>
                    <th>M</th>
                    <th>T</th>
                    <th>W</th>
                    <th>T</th>
                    <th>F</th>
                    <th>S</th>
                    <th>S</th>
                    <th>M</th>
                    <th>T</th>
                    <th>W</th>
                    <th>T</th>
                    <th>F</th>
                    <th>S</th>
                    <th>S</th>
                    <th>M</th>
                    <th>T</th>
                    <th>W</th>
                    <th>T</th>
                    <th>F</th>
                    <th>S</th>
                    <th>S</th>
                    <th>M</th>
                    <th>T</th>
                    <th>W</th>
                    <th>T</th>
                    <th>F</th>
                    <th>S</th>
                    <th>S</th>
                    <th>M</th>
                    <th>T</th>
                </tr>
            </thead>
            <tbody>
                <tr class="month" v-for="(calendar, month) in currentCalendar" :key="month">

                    <td class="month_label" v-text="month"></td>

                    <td class="week_day p-0"  v-for="(day_obj, day) in calendar" :key="day">
                        <div :class="tableClass + '_option_day option_day'" :id="month + '-' + day_obj.day" v-html="day_obj.day" v-if="day_obj !== '' && !day_obj.holiday"></div>
                        <div :class="'_option_day option_day holiday'" :id="month + '-' + day_obj.day" v-html="day_obj.day" v-if="day_obj !== '' && day_obj.holiday"></div>
                        <div v-if="day_obj === ''">&nbsp;</div>
                    </td>

                </tr>
            </tbody>
        </table>

        <div class="row mt-3 pl-3" v-if="copyField">
            <label class="m-checkbox m-checkbox--solid m-checkbox--single m-checkbox--brand mr-3">
                    <input type="checkbox" v-model="copy_days"><span></span>
            </label>
            <span>Copy regular dates for interpreter appointments <i class="fa fa-info-circle" data-skin="dark" data-container="body" data-toggle="m-tooltip" data-placement="right" title="" data-original-title="This will override current interpreter selections"></i></span>
        </div>
    </div>

</template>

<script>
    import EventBus from '../../../utils/event-bus';

    export default {
        props: {
            currentCalendar: Object,
            tableClass: String,
            copyField: Boolean
        },
        data: function () {
            return {
                copy_days: false
            }
        },
        watch: {
            copy_days: function (val) {
                if(val === true){
                    this.disable_ds();
                    this.confirmCopy();
                }
            }
        },
        methods: {
            disable_ds() {
                var self = this;
                self.$parent.ds_current.selectable.stop();
                self.$parent.ds_current_interpreter.selectable.stop();
                self.$parent.ds_next.selectable.stop();
                self.$parent.ds_next_interpreter.selectable.stop();
            },
            enable_ds() {
                var self = this;
                self.$parent.ds_current.selectable.start();
                self.$parent.ds_current_interpreter.selectable.start();
                self.$parent.ds_next.selectable.start();
                self.$parent.ds_next_interpreter.selectable.start();
            },
            confirmCopy() {
                var self = this;
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
                                self.copyDates();
                                self.$swal(
                                            'Copied!',
                                            'Days has been copied.',
                                            'success'
                                        );
                            } else {
                                self.copy_days = false;
                            }
                            self.enable_ds();
                        });
            },
            copyDates() {
                var self = this;

                if(self.tableClass == 'current'){
                    let preselection_current = self.$parent.ds_current.getSelectedValuesByContext('#current_regular .ds-selected'); //Use this function because a bug in the regular one, so send the context
                    self.$parent.ds_current_interpreter.clear();
                    self.$parent.ds_current_interpreter.setInitialSelections('.current_interpreter ', preselection_current);
                }

                if(self.tableClass == 'next'){
                    let preselection_next = self.$parent.ds_next.getSelectedValuesByContext('#next_regular .ds-selected'); //Use this function because a bug in the regular one, so send the context
                    self.$parent.ds_next_interpreter.clear();
                    self.$parent.ds_next_interpreter.setInitialSelections('.next_interpreter ', preselection_next);
                }
            },
            eventFetchDays() {
                let self = this;
                EventBus.$on('FETCH_DAYS', function () {
                    self.copy_days = false;
                });
            }
        },
        mounted() {
            this.eventFetchDays();
        },
    }
</script>