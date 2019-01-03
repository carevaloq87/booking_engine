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
                        <div :class="tableClass + '_option_day option_day holiday'" :id="month + '-' + day_obj.day" v-html="day_obj.day" v-if="day_obj !== '' && day_obj.holiday"></div>
                        <div v-if="day_obj === ''">&nbsp;</div>
                    </td>

                </tr>
            </tbody>
        </table>

        <div class="row mt-3 pl-3" v-if="copyField">
            <label class="m-checkbox m-checkbox--solid m-checkbox--single m-checkbox--brand mr-3">
                    <input type="checkbox" v-model="copy_days" v-on:click="copyDates"><span></span>
            </label>
            <span>Copy regular dates for interpreter appointments <i class="fa fa-info-circle" data-skin="dark" data-container="body" data-toggle="m-tooltip" data-placement="right" title="" data-original-title="This will override current interpreter selections"></i></span>
        </div>
    </div>

</template>

<script>
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
        methods: {
            copyDates() {
                var self = this;
                if(!self.copy_days) {
                    if(self.tableClass == 'current'){
                        let preselection_current = self.$parent.ds_current.getSelectedValues();
                        self.$parent.ds_current_interpreter.clear();
                        self.$parent.ds_current_interpreter.setInitialSelections('.current_interpreter ', preselection_current);
                    }

                    if(self.tableClass == 'next'){
                        let preselection_next = self.$parent.ds_next.getSelectedValues();
                        self.$parent.ds_next_interpreter.clear();
                        self.$parent.ds_next_interpreter.setInitialSelections('.next_interpreter ', preselection_next);
                    }
                }
            }
        }
    }
</script>