import api from "@/plugins/utilites";
import axios from "@axios";

export const useCalendarStore = defineStore("calendar", {
  // arrow function recommended for full type inference
  state: () => ({
    availableCalendars: [
      // {
      //   color: "error",
      //   label: "Personal",
      // },
      // {
      //   color: "primary",
      //   label: "Business",
      // },
      // {
      //   color: "warning",
      //   label: "Family",
      // },
      // {
      //   color: "success",
      //   label: "Holiday",
      // },
    ],

    selectedCalendars: [],
  }),
  actions: {
    async fetchEvents() {
      // return axios.get("/apps/calendar/events", {
      //   params: { calendars: this.selectedCalendars.join(",") },
      // });

      return api.post("/events-all");
    },
    async addEvent(event) {
      // return axios.post("/apps/calendar/events", { event });
      return api.post("/events-store", event);
    },

    async showEvent(event) {
      return api.post("/events-show", event);
    },

    async updateEvent(event) {
      return api.post(`/events-update`, event);
    },

    async removeEvent(eventId) {
      // return api.delete(`/apps/calendar/events/${eventId}`);
    },

    async getMeetingTypes() {
      // this.availableCalendars = await api
      //   .post("/meeting-types-all")
      //   .then((res) => res.data.data.data);

      await api
        .post("/meeting-types-all")
        .then((res) => {
          this.availableCalendars = res.data.data.data;
        })
        .catch((err) => {
          console.log(err);
        });

      // this.selectedCalendars = [];

      this.availableCalendars.map((a) => {
        if (!this.selectedCalendars.includes(a.id))
          this.selectedCalendars.push(a.id);
      });
    },
  },
});
