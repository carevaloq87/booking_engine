<template>
	<full-calendar
    :config="config"
    :events="events"
    ref="calendar"
	@event-selected="eventSelected"
	@event-render="eventRendered"
    />
</template>

<script>
	import moment from 'moment'
    import EventBus from '../../utils/event-bus';
	export default {
        props: {
            sv_id: Number
        },
        data () {
            let self = this;
            return {
                    regular: {},
                    interpreter: {},
                    unavailable: {},
                    availability_url: '/services/getAvailabilitybyService/' + this.sv_id,
                    events: [
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
                            },
                            agendaWeek:{
                                columnHeaderFormat:'ddd D/M'
                            }
                        },
                        weekends: false,
                        editable: false,
                        droppable: false, // this allows things to be dropped onto the calendar !!!
                        businessHours: {
                            start: '7:00', // a start time (7 am in this example)
                            end: '19:00', // an end time (7 pm in this example)
                        },
                        eventSources: [{
                            url: '/services/getAvailabilitybyService/' + this.sv_id,
                            type: 'GET',
                            data: {
                                //sp_id: currentServiceProvider
                            },
                            beforeSend: function () {
                                self.showLoader();
                            },
                            error: function() {
                                alert('there was an error while fetching events!');
                                self.hideLoader();
                            },
                            success: function (data) {
                                self.events = [];
                                self.initCalendar(data);
                            },
                            complete:function () {
                                self.hideLoader();
                            }
                        }]
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
                    content: 'Start: ' + moment(event.start).format('HH:mm A') + '<br />End: ' + moment(event.end).format('HH:mm A'),
                    trigger: 'hover',
                    placement: 'top',
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
                            resolve(response.data);
                        })
                        .catch(error => {
                            console.log(error);
                            reject(error.data);
                        });
                });
            },
            initCalendar(response) {
                let self = this;
                this.regular = response.regular;
                this.interpreter = response.interpreter;
                this.unavailable = response.unavailable;
                this.showInCalendar();
            },
            showInCalendar() {
                this.addEventsToCalendar(this.regular, 0, 0);
                this.addEventsToCalendar(this.interpreter, 1, 0);
                this.addEventsToCalendar(this.unavailable, 1, 1);
            },
            addEventsToCalendar(appts, is_interpreter, is_booking){
                let service_events = [];
                let self = this;
                Object.keys(appts).forEach(function (date_appt) {
                    let av_hours = appts[date_appt];
                    Object.keys(av_hours).forEach(function (time_available) {
                        let slots = av_hours[time_available];
                        Object.keys(slots).forEach(function (slot) {
                            let slot_text = (is_booking == 1 ? 'Slot Taken' : 'Slot Available');
                            let slot_time = parseInt(slots[slot].start_time);
                            let slot_duration = parseInt(slots[slot].duration);
                            let start_time = moment(date_appt).add(slot_time, 'm');
                            let end_time = moment(date_appt).add((slot_time + slot_duration), 'm');
                            let color = (is_booking == 1 ? 'red' : ( is_interpreter == 1 ? 'blue' : 'green'));

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
            },
            updateCalendar() {
                let self = this;
                EventBus.$on('calendar', (data) => {
                    self.showLoader();
                    //let response = self.getFutureAvailability();
                    let calendar = self.$refs.calendar.fireMethod('getView');
                    let start_day = moment(calendar.start).format('YYYY-MM-DD');
                    let end_day = moment(calendar.end).format('YYYY-MM-DD');
                    axios['get'](self.availability_url, {
                        params: {
                            start: start_day,
                            end:   end_day
                            }
                        })
                        .then(response => {
                            self.events = [];
                            self.initCalendar(response.data);
                            self.hideLoader();
                        })
                        .catch(error => {
                            self.hideLoader();
                        });
                });
            },
            showLoader() {
                EventBus.$emit('SHOW_LOADER', 'calendar');
            },
            hideLoader() {
                EventBus.$emit('HIDE_LOADER', 'calendar');
            }
        },
        mounted() {
            this.updateCalendar();
        },
    }

</script>
