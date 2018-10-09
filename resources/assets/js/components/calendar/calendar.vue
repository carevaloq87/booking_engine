<template>
	<full-calendar
    :config="config"
    :events="events"
	@event-selected="eventSelected"
	@event-render="eventRendered"
    />
</template>

<script>
	import moment from 'moment'
	export default {
        props: {
            sv_id: Number
        },
        data () {
            return {
                    regular: {},
                    interpreter: {},
                    availability_url: '/services/getAvailabilitybyService/' + this.sv_id,
                    events: [
                        {
                            start: '2018-10-10T10:00:00',
                            end: '2018-10-10T16:00:00',
                            rendering: 'background'
                        }
                    ],
                    config: {
                        header: {
                            left: 'title',
                            center: '',
                            right: 'prev,next today,month,agendaWeek,agendaDay,listMonth'
                        },
                        defaultView: 'agendaWeek',
                        views: {
                            month: {
                                eventLimit: 5 // adjust to 6 only for agendaWeek/agendaDay
                            }
                        },
                        weekends: false,
                        businessHours: {
                            start: '7:00', // a start time (7 am in this example)
                            end: '19:00', // an end time (7 pm in this example)
                        }
                    },
            }
        },
        methods: {
            eventRendered(event, element, view){
                $(element).popover({
                    /*
                    container: '.fc-scroller',
                    title: event.title,*/
                    html: true,
                    content: 'Start: ' + event.start + '<br />End: ' + event.end,
                    trigger: 'hover',
                    placement: 'auto top',
                    container: 'body'
                });
            },
            eventSelected(event, jsEvent){
                console.log(event, jsEvent);
                $(jsEvent.target).popover('toggle');
            },
            getFutureAvailability() {
                return new Promise((resolve, reject) => {
                axios['get'](this.availability_url, {})
                    .then(response => {
                        console.log(response);
                        resolve(response.data);
                    })
                    .catch(error => {
                        console.log(error);
                        reject(error.data);
                    });
                });
            },
            initCalendar() {
                this.getFutureAvailability()
                    .then(response => {
                        this.regular = response.regular;
                        this.interpreter = response.interpreter;
                        this.showInCalendar();
                    })
                    .catch(error => {
                        console.log(error);
                    });
            },
            showInCalendar() {
                this.addEventsToCalendar(this.regular, 0);
                this.addEventsToCalendar(this.interpreter, 1);
            },
            addEventsToCalendar(appts, is_interpreter){
                let service_events = [];
                let self = this;
                Object.keys(appts).forEach(function (date_appt) {
                    let av_hours = appts[date_appt];
                    Object.keys(av_hours).forEach(function (time_available) {
                        let slots = av_hours[time_available];
                        Object.keys(slots).forEach(function (slot) {
                            let slot_text = 'Slot Available';
                            let slot_time = parseInt(slots[slot].start_time);
                            let slot_duration = parseInt(slots[slot].duration);
                            let start_time = moment(date_appt).add(slot_time, 'm');
                            let end_time = moment(date_appt).add((slot_time + slot_duration), 'm');
                            let color = ( is_interpreter == 1 ? 'blue' : 'green');

                            self.events.push({
                                title: slot_text,
                                start: start_time,
                                end: end_time,
                                editable: false,
                                slot: slots[slot],
                                color: color
                            });
                        });
                    });
                });
            }
        },
        mounted() {
            this.initCalendar();
        },
    }

</script>
