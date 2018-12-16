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

                <td class="week_day p-0"  v-for="(day_number, day) in calendar" :key="day">

                    <div :class="tableClass + '_option_day option_day'" :id="month + '-' + day_number" v-html="day_number" v-if="day_number !== ''"></div>
                    <div v-if="day_number === ''">&nbsp;</div>

                </td>

            </tr>
        </tbody>
    </table>

</template>

<script>
    export default {
        props: {
            currentCalendar: Object,
            tableClass: String
        }
    }
</script>