<template>

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

</template>

<script>
    export default {
        props: {
            currentCalendar: Object,
            holidays:Array,
            tableClass: String
        }
    }
</script>