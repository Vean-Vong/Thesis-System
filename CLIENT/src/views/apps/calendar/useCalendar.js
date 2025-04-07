import api from '@/plugins/utilites'
import { useCalendarStore } from '@/views/apps/calendar/useCalendarStore'
import { useThemeConfig } from '@core/composable/useThemeConfig'
import dayGridPlugin from '@fullcalendar/daygrid'
import interactionPlugin from '@fullcalendar/interaction'
import listPlugin from '@fullcalendar/list'
import timeGridPlugin from '@fullcalendar/timegrid'

export const blankEvent = {
  title: '',
  start: '',
  end: '',
  allDay: false,
  url: '',

  // extendedProps: {
  //   /*
  //         ℹ️ We have to use undefined here because if we have blank string as value then select placeholder will be active (moved to top).
  //         Hence, we need to set it to undefined or null
  //       */
  //   calendar: undefined,
  //   guests: [],
  //   location: "",
  //   description: "",
  // },
  staffs: [],
  meeting_type_id: null,
  location: null,
  description: null,
}
export const useCalendar = (event, isEventHandlerSidebarActive, isLeftSidebarOpen) => {
  // 👉 themeConfig
  const { isAppRtl } = useThemeConfig()

  // 👉 Store
  const store = useCalendarStore()

  // 👉 Calendar template ref
  const refCalendar = ref()

  // 👉 Calendar colors
  // const calendarsColor = store.availableCalendars;

  // ℹ️ Extract event data from event API
  const extractEventDataFromEventApi = eventApi => {
    console.log(eventApi)

    const {
      publicId,
      title,
      start,
      end,
      url,

      extendedProps: { meeting_type_id, location, description },

      allDay,
    } = eventApi

    return {
      id: publicId,
      title,
      start,
      end,
      url,

      extendedProps: { meeting_type_id, location, description },

      allDay,
    }
  }

  // 👉 Fetch events
  const fetchEvents = (info, successCallback) => {
    // If there's no info => Don't make useless API call
    if (!info) return
    store
      .fetchEvents()
      .then(res => {
        successCallback(
          res.data.data.data.map(e => ({
            ...e,

            // Convert string representation of date to Date object
            start: new Date(e.start),
            end: new Date(e.end),
          })),
        )
      })
      .catch(e => {
        console.error('Error occurred while fetching calendar events', e)
      })
  }

  // 👉 Calendar API
  const calendarApi = ref(null)

  // 👉 Update event in calendar [UI]
  const updateEventInCalendar = (updatedEventData, propsToUpdate, extendedPropsToUpdate) => {
    const existingEvent = calendarApi.value?.getEventById(updatedEventData.publicId)

    if (!existingEvent) {
      console.warn("Can't found event in calendar to update")

      return
    }

    // ---Set event properties except date related
    // ? Docs: https://fullcalendar.io/docs/Event-setProp
    // dateRelatedProps => ['start', 'end', 'allDay']
    for (let index = 0; index < propsToUpdate.length; index++) {
      const propName = propsToUpdate[index]

      existingEvent.setProp(propName, updatedEventData[propName])
    }

    // --- Set date related props
    // ? Docs: https://fullcalendar.io/docs/Event-setDates
    existingEvent.setDates(updatedEventData.start, updatedEventData.end, {
      allDay: updatedEventData.allDay,
    })

    // --- Set event's extendedProps
    // ? Docs: https://fullcalendar.io/docs/Event-setExtendedProp
    for (let index = 0; index < extendedPropsToUpdate.length; index++) {
      const propName = extendedPropsToUpdate[index]

      existingEvent.setExtendedProp(propName, updatedEventData.extendedProps[propName])
    }
  }

  // 👉 Remove event in calendar [UI]
  const removeEventInCalendar = eventId => {
    const _event = calendarApi.value?.getEventById(eventId)
    if (_event) _event.remove()
  }

  // 👉 refetch events
  const refetchEvents = () => {
    calendarApi.value?.refetchEvents()
  }

  watch(() => store.selectedCalendars, refetchEvents)

  // 👉 Add event
  const addEvent = _event => {
    store.addEvent(_event).then(res => {
      console.log(res.data)

      refetchEvents()
    })
  }

  // 👉 Update event
  const updateEvent = _event => {
    store.updateEvent(_event).then(res => {
      console.log(res.data)
    })

    // store.updateEvent(_event).then((r) => {
    //   const propsToUpdate = ["id", "title", "url"];
    //   const extendedPropsToUpdate = [
    //     "calendar",
    //     "guests",
    //     "location",
    //     "description",
    //   ];

    //   updateEventInCalendar(r.data.event, propsToUpdate, extendedPropsToUpdate);
    // });
  }

  // 👉 Remove event
  const removeEvent = eventId => {
    store.removeEvent(eventId).then(() => {
      removeEventInCalendar(eventId)
    })
  }

  // 👉 Calendar options
  const calendarOptions = {
    plugins: [dayGridPlugin, interactionPlugin, timeGridPlugin, listPlugin],
    initialView: 'dayGridMonth',

    // slotMinTime: "07:00:00",
    // slotMaxTime: "20:00:00",

    headerToolbar: {
      start: 'drawerToggler,prev,next,today',
      center: 'title',

      end: 'dayGridMonth,timeGridWeek,timeGridDay,listMonth',

      // end: "dayGridMonth,timeGridWeek,timeGridDay",
    },
    buttonText: {
      today: 'Today',
    },

    events: fetchEvents,

    // ❗ We need this to be true because when its false and event is allDay event and end date is same as start data then Full calendar will set end to null
    forceEventDuration: true,

    /*
        Enable dragging and resizing event
        Docs: https://fullcalendar.io/docs/editable
      */
    editable: true,

    // editable: useCalendarStore().editable,

    /*
        Enable resizing event from start
        Docs: https://fullcalendar.io/docs/eventResizableFromStart
      */
    eventResizableFromStart: true,

    /*
        Automatically scroll the scroll-containers during event drag-and-drop and date selecting
        Docs: https://fullcalendar.io/docs/dragScroll
      */
    // dragScroll: useCalendarStore().dragScroll,
    dragScroll: true,

    /*
        Automatically scroll the scroll-containers during event drag-and-drop and date selecting
        Docs: https://fullcalendar.io/docs/dragScroll
      */

    // selectable: useCalendarStore().selectable,
    selectable: true,

    /*
        Max number of events within a given day
        Docs: https://fullcalendar.io/docs/dayMaxEvents
      */
    dayMaxEvents: 2,

    /*
        Determines if day names and week names are clickable
        Docs: https://fullcalendar.io/docs/navLinks
      */
    navLinks: true,

    // eventClassNames({ event: calendarEvent }) {
    //   const colorName = {};

    //   //   calendarsColor[calendarEvent._def.extendedProps.calendar];

    //   if (store.availableCalendars) {
    //     colorName.value = store.availableCalendars.find(
    //       (a) => a.id === calendarEvent._def.extendedProps.calendar
    //     );
    //   }

    //   return [
    //     // Background Color
    //     // `bg-light-[${colorName.color}] text-[${colorName.color}]`,
    //   ];
    // },

    async eventDidMount(info) {
      await api
        .post('/meeting-types-all')
        .then(res => {
          store.availableCalendars = res.data.data.data
        })
        .catch(err => {
          console.log(err)
        })

      if (info.event._def.extendedProps.meeting_type_id) {
        const colorName = ref(null)

        colorName.value = store.availableCalendars.find(a => a.id === info.event._def.extendedProps.meeting_type_id)

        info.el.style.background = colorName.value.color
        info.el.style.color = 'white'
      }
    },

    select(e) {
      event.value = {
        ...event.value,
        start: new Date(e.startStr).toISOString().split('T')[0],
        end: new Date(e.endStr).toISOString().split('T')[0],
        allDay: e.allDay,
      }

      isEventHandlerSidebarActive.value = true
    },

    eventClick({ event: clickedEvent }) {
      console.log(clickedEvent)

      // * Only grab required field otherwise it goes in infinity loop
      // ! Always grab all fields rendered by form (even if it get `undefined`) otherwise due to Vue3/Composition API you might get: "object is not extensible"
      event.value = extractEventDataFromEventApi(clickedEvent)

      // const eventToUpdate = {
      //   id: droppedEvent._def.publicId,
      //   title: droppedEvent._def.title,
      //   extendedProps: droppedEvent._def.extendedProps,
      //   url: droppedEvent._def.url,
      //   allDay: droppedEvent._def.allDay,
      //   start: droppedEvent.start,
      //   end: droppedEvent.end,
      // };

      isEventHandlerSidebarActive.value = true
    },

    // customButtons
    dateClick(info) {
      console.log(info)

      event.value = { ...event.value, start: info.date }
      isEventHandlerSidebarActive.value = true
    },

    /*
          Handle event drop (Also include dragged event)
          Docs: https://fullcalendar.io/docs/eventDrop
          We can use `eventDragStop` but it doesn't return updated event so we have to use `eventDrop` which returns updated event
        */
    eventDrop({ event: droppedEvent }) {
      const eventToUpdate = {
        id: droppedEvent._def.publicId,
        title: droppedEvent._def.title,
        extendedProps: droppedEvent._def.extendedProps,
        url: droppedEvent._def.url,
        allDay: droppedEvent._def.allDay,
        start: droppedEvent.start,
        end: droppedEvent.end,
      }

      // updateEvent(extractEventDataFromEventApi(droppedEvent._def));
      updateEvent(eventToUpdate)
    },

    /*
          Handle event resize
          Docs: https://fullcalendar.io/docs/eventResize
        */
    eventResize({ event: resizedEvent }) {
      if (resizedEvent.start && resizedEvent.end) updateEvent(extractEventDataFromEventApi(resizedEvent))

      console.log(123)
    },
    customButtons: {
      drawerToggler: {
        text: 'calendarDrawerToggler',
        click() {
          isLeftSidebarOpen.value = true
        },
      },
    },
  }

  // 👉 onMounted
  onMounted(async () => {
    calendarApi.value = refCalendar.value.getApi()
  })

  // 👉 Jump to date on sidebar(inline) calendar change
  const jumpToDate = currentDate => {
    calendarApi.value?.gotoDate(new Date(currentDate))
  }

  watch(
    isAppRtl,
    val => {
      calendarApi.value?.setOption('direction', val ? 'rtl' : 'ltr')
    },
    { immediate: true },
  )

  return {
    refCalendar,
    calendarOptions,
    refetchEvents,
    fetchEvents,
    addEvent,
    updateEvent,
    removeEvent,
    jumpToDate,
  }
}
